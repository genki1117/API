<?php
declare(strict_types=1);
namespace App\Exceptions;

use App\Exceptions\AuthenticateException;
use App\Exceptions\ConflictException;
use PDOException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param Throwable $e
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
     * @throws Throwable
     */
    public function render($request, Throwable $e)
    {
        if ($e instanceof ValidationException) {
            // ----------------------------------------------------------------
            // バリデーションエラーの配列から、フロント甩のレスポンス形式に変換する
            // ----------------------------------------------------------------
            //
            // 【エラー形式】
            // [
            //     'document_id' => ['error.message.require'],
            //     'docinfo.0.title.0.userid' => ['error.message.format', 'error.message.length'],
            //     'docinfo.0.title.0.documentid' => ['error.message.required'],
            //     'docinfo.0.title.1.userid' => ['error.message.number'],
            //     'docinfo.0.startdate' => ['error.message.date'],
            //     'docinfo.1.title.0.userid' => ['error.message.format', 'error.message.length'],
            //     'docinfo.5.title.1.userid' => ['error.message.format', 'error.message.length'],
            //     'guest.mail' => ['error.message.email'],
            //     'guest.companyid' => ['error.message.numbeer', 'error.message.length'],
            //     'host.2.company_id' => ['error.message.length'],
            //     'host.2.mail' => ['error.message.length', 'error.message.format'],
            //     'host.5.company_id' => ['error.message.length'],
            //     'host.5.mail' => ['error.message.format'],
            // ];
            //
            // 【備考】
            // バリデーションエラー：$e->errors()は、上記のようなキーとエラーメッセージの配列形式で格納されている。
            // 階層が発生する場合には、ドットをつないだ形となり、数字であれば配列、数字以外はオブジェクトとして表現。
            // しかし、このままの状態で、JSON形式に変換したものをレスポンスで返しても、フロント側ではキーを判別ができない。
            // （例えば、'docinfo.0.title.0.userid'というキーと認識してしまい、階層的に捉えることができないため）
            //
            // 上記のため、ドットでキーを分解して、階層的な配列を作成してから、JSON形式に変換してレスポンスを返す。
            // また、配列の場合には、元のリクエストの何番目のエラーであるかが分からないため、マークとして、'number'というキーを付与する。
            // 'number'の付与は下記のようなイメージ
            // "host": [
            //     {
            //       "number": 2,
            //       "company_id": [
            //         "error.message.length"
            //       ],
            //       "mail": [
            //         "error.message.length",
            //         "error.message.format"
            //       ]
            //     },
            //     {
            //       "number": 5,
            //       "company_id": [
            //         "error.message.length"
            //       ],
            //       "mail": [
            //         "error.message.format"
            //       ]
            //     }
            //   ]
            // ----------------------------------------------------------------

            // バリデーションエラーの情報を取得する
            $origin_errors = $e->errors();

            // 同じキー名がまとまった順番で来ないケースがあるかもしれないので、念の為にKeyでソートしておく
            // 例 host.0.user  document_id  host.1.user
            ksort($origin_errors);

            // フロント用の調整後配列（最終的にJSON化する前の配列）を定義する。
            $adjustedArray = [];
            // ルート階層のキー名称を保管する変数を定義する。
            $baseKey = '';
            // 配列を作成するためのWork用変数（ルート階層のキー単位で使用）を定義する。
            $workArray = [];
            
            // バリデーションエラーを１件ずつ取得する。
            foreach ($origin_errors as $key => $errorMessageArray) {
                // ドット単位でキーを分割して、キー格納用の配列に設定する。
                $partArray = explode(".", $key);

                // 階層のMAXレベルを設定する
                $maxLevel = count($partArray) - 1;

                // 初回の場合は、ルート階層（１つ目のキー項目）のキー名称を、ルートキー保管用変数に設定しておく。
                if (empty($baseKey)) {
                    $baseKey = $partArray[0];
                }

                // ルート階層（１つ目のキー項目）のキー名称が、キー保管用変数と異なっている場合の処理。
                if ($baseKey != $partArray[0]) {
                    // フロント用の調整後配列に、作成中のWork用配列を追加する。
                    $adjustedArray = $adjustedArray + $workArray;

                    // Work用配列をクリアする。
                    $workArray = [];

                    // キー保管用変数を新しいキー名称で更新する。
                    $baseKey = $partArray[0];
                }
                
                // 子階層の配列を生成する（Work用配列を参照渡しで、再帰的に処理を実施）
                $this->createChildArray($workArray, $partArray, 0, $maxLevel, $errorMessageArray);
            }

            // 最後に作成したWork用配列をフロント用の調整後配列に追加する。
            if (count($workArray) > 0) {
                $adjustedArray = $adjustedArray + $workArray;
            }

            return new JsonResponse([
                'errors' => $adjustedArray
            ], 400);
        }

        if ($e instanceof AuthenticateException) {
            return new JsonResponse([
                'error' => $e->getMessage()
            ], $e->getCode());
        }

        if ($e instanceof ConflictException) {
            return new JsonResponse([
                'error' => "common.message.save-conflict"
            ], 409);
        }

        if ($e instanceof NotFoundHttpException) {
            return new JsonResponse([
                'error' => "common.message.not-found"
            ], 404);
        }

        if ($e instanceof PDOException) {
            return new JsonResponse([
                'error' => "common.message.db-connection.failed"
            ], 500);
        }

        $response = parent::render($request, $e);
        $statusCode = $response->getStatusCode();

        if ($statusCode === Response::HTTP_INTERNAL_SERVER_ERROR) {
            return new JsonResponse([
                'error' => "common.message.system.error"
            ], 500);
        } else {
            // その他エラーについても、メッセージは500と同じにしておく.
            // (今後、ステータス個別のメッセージが必要になれば、処理を分ける)
            return new JsonResponse([
                'error' => "common.message.system.error"
            ], $statusCode);
        }

        return $response;
    }

    /**
     * 再帰的に子階層の配列を生成する。
     * （$workArrayは参照渡し）
     *
     * @param array|null $workArray
     * @param array $partArray
     * @param int $level
     * @param int $maxLevel
     * @param array $errorMessageArray
     * @return void
     */
    private function createChildArray(?array &$workArray, array $partArray, int $level, int $maxLevel, array $errorMessageArray):void
    {
        // 最下層のレベルに到達した場合
        if ($level > $maxLevel) {
            // エラーメッセージ内容をWork用配列に設定して、処理を終了する。
            $workArray = $errorMessageArray;
            return;
        }

        // キー項目が数字かどうかをチェックする
        if (is_numeric($partArray[$level]) === true) {
            if (empty($workArray)) {
                // 同階層レベルの中で、はじめて配列形式で設定する場合は、
                // 'number'キーと値（リクエスト時の添字）を設定する
                $workArray[0]['number'] = intval($partArray[$level]);

                // 次の階層の配列を生成する（再呼び出し）
                $this->createChildArray($workArray[0], $partArray, $level+1, $maxLevel, $errorMessageArray);
            } else {
                $index = -1;
                $i = 0;
                // 'number'キーを既に登録済みであるかをチェックする。
                foreach ($workArray as $tmpdata) {
                    if (array_key_exists('number', $tmpdata)) {
                        // 既に'number'として設定されているキー項目の場合には、その際に使用したindexを設定する。
                        if (intval($partArray[$level]) == $tmpdata['number']) {
                            $index = $i;
                            break;
                        }
                    }
                    $i ++;
                }
                
                // 'number'キーが登録済み出ない場合の処理
                if ($index === -1) {
                    // indexを設定する（最大添字+1)
                    $index = count($workArray);

                    // 'number'キーと値（リクエスト時の添字）を設定する
                    $workArray[$index]['number'] = intval($partArray[$level]);
                }

                // 次の階層の配列を生成する（再呼び出し）
                $this->createChildArray($workArray[$index], $partArray, $level+1, $maxLevel, $errorMessageArray);
            }
        } else {
            // 次の階層の配列を生成する（再呼び出し）
            // （連想配列として作成することにより、最終的にJsonResponseに変換する際に、オブジェクト扱いとなる）
            $this->createChildArray($workArray[$partArray[$level]], $partArray, $level+1, $maxLevel, $errorMessageArray);
        }
    }
}

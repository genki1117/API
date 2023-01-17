<?php
declare(strict_types=1);
namespace App\Libraries;

use Illuminate\Support\Facades\Validator as ValidatorFacades;
use Illuminate\Contracts\Validation\Validator;

/**
 * レスポンス関連の関数群
 */
trait ResponseClient
{
    /**
     * フロント用に加工したバリデーション情報を返却する。
     *
     * 【備考】
     * (１) 本メソッドを使用するクラスは、必ずerrorsTable()関数を実装すること。
     *
     * (２) errorsTable()に、無名関数(Closure)を定義している際には、第一引数に該当するfailed情報を渡します。
     *  (引数のfailed情報 例)
     *  [
     *     'key' => 'email',    <---- errorsTable()上のキー名
     *     'failed' => [        <---- 上記キー名のうち、何要因でエラーとなっているか（配列形式であるが、１要素固定）
     *         'Max' => [10]          [10]の箇所は、Requestクラス：rules()で、max:10としていれば、10の部分
     *      ],
     *     'index' => 1         <---- 上記要因の中のerrorsTable上の何番目で呼ばれたClosureであるか(*1)
     *  ]
     *  (*1)errorsTable()に下記が定義されているとすれば、"Max"の中の2つ目(index=1)という意味である。
     *    'email' => [
     *           "Required" =>  [
     *                ["type" => "label", "content" => "user.label.item.email"],
     *           ],
     *           "Max" =>  [
     *               ["type" => "label", "content" => "user.label.item.email"],
     *               ["type" => "text", "content"  => $callback],
     *           ],
     *       ],
     *
     * (３) 無名関数(Closure)の設定方法について。
     *  $callback = function ($failed) {
     *        return "test";
     *  };
     *  上記のように無名関数を作成して、errorsTable上で["type" => "text", "content"  => $callback]のように定義する。
     *
     * @param Validator $validator
     * @return Validator
     */
    private function adjustValidator(Validator $validator): Validator
    {
        // フロント用に調整したValidator::errors()を格納する変数
        $adjustErrors = [];

        // メラーメッセージとその要因を取得する
        $errors = $validator->errors()->messages();
        $failed = $validator->failed();
        // --------------------------------------------------------------
        // 【備考】
        // ※errors()->messages()、failed()は同一の配列構造である。
        //
        // 例) 以下のような内容が格納されている。
        // failed()の下記"Numeric"等は、Laravelバリデーションルールの先頭を大文字にしたものが格納される。
        // failed()の下記"Max"の値は、Laravelバリデーションルールで「max:2」としたときの2が格納されている。
        //
        // errors()->messages() = [
        //     'document_id' => ["error.message.number", "error.message.length.over"],
        //     'category_id' => ["error.message.required"],
        // ];
        // failed() = [
        //     'document_id' => ["Numeric" => [], "Max" => [2]],
        //     'category_id' => ["Required" => []],
        // ];
        // --------------------------------------------------------------

        // 各APIのRequestクラスで定義しているエラー定義テーブルを読み込む
        $errorsTable = [];
        if (method_exists($this, 'errorsTable')) {
            $errorsTable = $this->errorsTable();
        }

        // エラーとなったパラメタを１件ずつ取得する。
        foreach ($errors as $key => $error) {
            $adjustErrors[$key] = [];
            // 各パラメタ毎のエラーメッセージを１件ずつ取得する。
            foreach ($error as $index => $errorMessage) {
                // message_keyを設定する。
                $adjustErrors[$key][$index]['message_key'] = $errorMessage;
                
                // $errorsTableから該当するパラメタとエラー要因を検索して、'message'に設定する。
                $adjustErrors[$key][$index]['messages'] = [];
                foreach ($errorsTable as $tableKey => $errorFactors) {
                    if (preg_match('/'. $tableKey .'/', $key) === 1) {
                        foreach ($errorFactors as $factorKey => $factorValues) {
                            if (array_key_exists($factorKey, $failed[$key])) {
                                $idx = 0;
                                foreach ($factorValues as $value) {
                                    if (array_key_exists('content', $value)) {
                                        // $errorsTableにて、無名関数を設定している場合の処理。
                                        if ($value['content'] instanceof \Closure) {
                                            $failParam = [
                                                'key' => $key,
                                                'failed' => [$factorKey => $failed[$key][$factorKey]],
                                                'index' => $idx
                                            ];
                                            // 該当するfailed情報を引数として、設定されている無名関数を呼び出す。
                                            $result = $value['content']($failParam);

                                            // 無名関数の戻り値を'content'に設定する。
                                            $value['content'] = $result;
                                        }
                                    }
                                    $adjustErrors[$key][$index]['messages'][] = $value;
                                    $idx++;
                                }
                                // 調整済の[パラメタ][factorKey]を$failedから削除して、以降の検索対象から外す。
                                unset($failed[$key][$factorKey]);
                                break;
                            }
                        }
                        break;
                    }
                }
            }
        }

        // 調整したバリデータ情報を返却する。
        return $this->createValidatorWithMessages(messages:$adjustErrors);
    }

    /**
     * 引数のメッセージでバリデーションを生成する
     *
     * @param array $messages
     * @return Validator
     */
    private function createValidatorWithMessages(array $messages): Validator
    {
        $validator = ValidatorFacades::make([], []);
        $validator->errors()->merge($messages);
        return $validator;
    }
}

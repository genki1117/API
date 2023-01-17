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
     * 　バリデーション情報をフロント用に加工する
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
                $adjustErrors[$key][$index]['message_key'] = $errorMessage;
                
                // $errorsTableから該当するパラメタとエラー要因を検索して、'message'に設定する。
                foreach ($errorsTable as $tableKey => $errorFactors) {
                    if (preg_match('/'. $tableKey .'/', $key) === 1) {
                        foreach ($errorFactors as $factorKey => $factorValues) {
                            if (array_key_exists($factorKey, $failed[$key])) {
                                foreach ($factorValues as $value) {
                                    if ($value['content'] instanceof \Closure) {
                                        // $errorsTableにて、無名関数を設定している場合は、該当するfailed情報を引数として無名関数を呼び出す。
                                        $failParam = [
                                            'key' => $key,
                                            'failed' => [$factorKey => $failed[$key][$factorKey]]
                                        ];
                                        $result = $value['content']($failParam);
                                        $value['content'] = $result;
                                    }
                                    $adjustErrors[$key][$index]['messages'][] = $value;
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

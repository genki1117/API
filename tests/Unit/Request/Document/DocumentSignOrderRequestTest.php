<?php

namespace Tests\Unit\Service\Document;

use App\Http\Requests\Document\DocumentSignOrderRequest;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class DocumentSignOrderRequestTest extends TestCase
{
     /**
      * 
     * バリデーションテスト（ホスト、ゲスト共通）
     * @param array 項目名の配列
     * @param array 値の配列
     * @param boolean 期待値(true:バリデーションOK、false:バリデーションNG)
     * このアノテーションに、下のメソッド名を記載
     * @dataProvider dataSignOrderRequest
     */
    public function DocumentTransferRequestTest1(array $keys, array $values, bool $expect)
    {
        //入力項目の配列（$keys）と値の配列($values)から、連想配列を生成する
        $dataList = array_combine($keys, $values);

        $request = new DocumentSignOrderRequest();

        //フォームリクエストで定義したルールを取得
        $rules = $request->rules();

        //Validatorファサードでバリデーターのインスタンスを取得、その際に入力情報とバリデーションルールを引数で渡す
        $validator = Validator::make($dataList, $rules);

        //入力情報がバリデーショルールを満たしている場合はtrue、満たしていな場合はfalseが返る
        $result = $validator->passes();

        //期待値($expect)と結果($result)を比較
        $this->assertEquals($expect, $result);
    }

    public function dataSignOrderRequest ()
    {
        return [
            'OK' => [
                ['document_id', 'doc_type_id', 'category_id'],
                [1, 1, 1],
                true
            ],
            'document_id_required' => [
                ['document_id', 'doc_type_id', 'category_id'],
                [null, 1, 1],
                false
            ],
            'document_id_number' => [
                ['document_id', 'doc_type_id', 'category_id'],
                ['testet', 1, 1],
                false
            ],
            'doc_type_id_required' => [
                ['document_id', 'doc_type_id', 'category_id'],
                [1, null, 1],
                false
            ],
            'doc_type_id_number' => [
                ['document_id', 'doc_type_id', 'category_id'],
                [1, 'testtest', 1],
                false
            ],
            'category_id_required' => [
                ['document_id', 'doc_type_id', 'category_id'],
                [1, 1, null],
                false
            ],
            'category_id_number' => [
                ['document_id', 'doc_type_id', 'category_id'],
                [1, 1, 'hotehotehote'],
                false
            ],

            
        ];
    }
}

<?php
namespace Tests\Unit\Request\Document;
// tests/Unit/Request/Document/DocumentDownloadCsvTest.php

use App\Http\Requests\Document\DocumentCsvDownloadRequest;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class DocumentDownloadCsvTest extends TestCase
{
    /**
     * @test
     * バリデーションテスト
     * @param array 項目名の配列
     * @param array 値の配列
     * @param boolean 期待値(true:バリデーションOK、false:バリデーションNG)
     * このアノテーションに、下のメソッド名を記載
     * @dataProvider dataUserRegistration
     */
    public function DocumentRequestDownloadTest(array $keys, array $values, bool $expect)
    {
        //入力項目の配列（$keys）と値の配列($values)から、連想配列を生成する
        $dataList = array_combine($keys, $values);

        $request = new DocumentCsvDownloadRequest();
        //フォームリクエストで定義したルールを取得
        $rules = $request->rules();

        //Validatorファサードでバリデーターのインスタンスを取得、その際に入力情報とバリデーションルールを引数で渡す
        $validator = Validator::make($dataList, $rules);

        //入力情報がバリデーショルールを満たしている場合はtrue、満たしていな場合はfalseが返る
        $result = $validator->passes();

        //期待値($expect)と結果($result)を比較
        $this->assertEquals($expect, $result);
    }

    public function dataUserRegistration()
    {
        return [
            'OK' => [
                ['category_id'],[1],true
            ],

            'category_id_required' => [
                ['category_id'],[null],false
            ],

            'category_id_number' => [
                ['category_id'],['test'],false
            ],
        ];
    }
}
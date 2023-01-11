<?php

namespace Tests\Unit\Request\Document;

use App\Accessers\DB\Document\DocumentWorkFlow;
use App\Domain\Consts\UserConst;
use App\Domain\Entities\Users\User;
use App\Foundations\Context\LoggedInUserContext;
use App\Http\Requests\Document\DocumentSaveRequest;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class DcoumentSaveRequestTest extends TestCase
{   
    /**
     * バリデーションテスト（ホスト、ゲスト共通）
     * @param array 項目名の配列
     * @param array 値の配列
     * @param boolean 期待値(true:バリデーションOK、false:バリデーションNG)
     * このアノテーションに、下のメソッド名を記載
     * @dataProvider dataUserRegistration
     */
    public function testDocumentTransferRequestTest1(array $keys, array $values, bool $expect)
    {
        //入力項目の配列（$keys）と値の配列($values)から、連想配列を生成する
        $dataList = array_combine($keys, $values);

        $request = new DocumentSaveRequest();

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
                ['document_id', 'company_id','category_id','template_id','doc_type_id','status_id','doc_no', 'product_name','title', 'amount', 'currency_id', 'counter_party_id', 'sign_level','cont_start_date', 'cont_end_date', 'conc_date', 'effective_date','cancel_date','expiry_date'],
                [1, 1, 0, 1, 1, 1, 'test', 'test', 'test', 12345, 1, 1, 1, '2022-10-10','2022-10-10','2022-10-10','2022-10-10','2022-10-10','2022-10-10'],
                true
            ],
            'document_id_number' => [
                ['document_id', 'company_id','category_id','template_id','doc_type_id','status_id','doc_no', 'product_name','title', 'amount', 'currency_id', 'counter_party_id', 'sign_level','cont_start_date', 'cont_end_date', 'conc_date', 'effective_date','cancel_date','expiry_date'],
                ['あ', 1, 0, 1, 1, 1, 'test', 'test', 'test', 12345, 1, 1, 1, '2022-10-10','2022-10-10','2022-10-10','2022-10-10','2022-10-10','2022-10-10'],
                false
            ],
            'company_id_required' => [
                ['document_id', 'company_id','category_id','template_id','doc_type_id','status_id','doc_no', 'product_name','title', 'amount', 'currency_id', 'counter_party_id', 'sign_level','cont_start_date', 'cont_end_date', 'conc_date', 'effective_date','cancel_date','expiry_date'],
                [1, null, 0, 1, 1, 1, 'test', 'test', 'test', 12345, 1, 1, 1, '2022-10-10','2022-10-10','2022-10-10','2022-10-10','2022-10-10','2022-10-10'],
                false
            ],
            'company_id_number' => [
                ['document_id', 'company_id','category_id','template_id','doc_type_id','status_id','doc_no', 'product_name','title', 'amount', 'currency_id', 'counter_party_id', 'sign_level','cont_start_date', 'cont_end_date', 'conc_date', 'effective_date','cancel_date','expiry_date'],
                [1, 'a', 0, 1, 1, 1, 'test', 'test', 'test', 12345, 1, 1, 1, '2022-10-10','2022-10-10','2022-10-10','2022-10-10','2022-10-10','2022-10-10'],
                false
            ],
            'template_id_number' => [
                ['document_id', 'company_id','category_id','template_id','doc_type_id','status_id','doc_no', 'product_name','title', 'amount', 'currency_id', 'counter_party_id', 'sign_level','cont_start_date', 'cont_end_date', 'conc_date', 'effective_date','cancel_date','expiry_date'],
                [1, 1, 0, 'a', 1, 1, 'test', 'test', 'test', 12345, 1, 1, 1, '2022-10-10','2022-10-10','2022-10-10','2022-10-10','2022-10-10','2022-10-10'],
                false
            ],
            'category_id_required' => [
                ['document_id', 'company_id','category_id','template_id','doc_type_id','status_id','doc_no', 'product_name','title', 'amount', 'currency_id', 'counter_party_id', 'sign_level','cont_start_date', 'cont_end_date', 'conc_date', 'effective_date','cancel_date','expiry_date'],
                [1, 1, null, 1, 1, 1, 'test', 'test', 'test', 12345, 1, 1, 1, '2022-10-10','2022-10-10','2022-10-10','2022-10-10','2022-10-10','2022-10-10'],
                false
            ],
            'doc_type_id_required' => [
                ['document_id', 'company_id','category_id','template_id','doc_type_id','status_id','doc_no', 'product_name','title', 'amount', 'currency_id', 'counter_party_id', 'sign_level','cont_start_date', 'cont_end_date', 'conc_date', 'effective_date','cancel_date','expiry_date'],
                [1, 1, 1, 1, null, 1, 'test', 'test', 'test', 12345, 1, 1, 1, '2022-10-10','2022-10-10','2022-10-10','2022-10-10','2022-10-10','2022-10-10'],
                false
            ],
            'doc_type_id_number' => [
                ['document_id', 'company_id','category_id','template_id','doc_type_id','status_id','doc_no', 'product_name','title', 'amount', 'currency_id', 'counter_party_id', 'sign_level','cont_start_date', 'cont_end_date', 'conc_date', 'effective_date','cancel_date','expiry_date'],
                [1, 1, 1, 1, 'a', 1, 'test', 'test', 'test', 12345, 1, 1, 1, '2022-10-10','2022-10-10','2022-10-10','2022-10-10','2022-10-10','2022-10-10'],
                false
            ],
            'status_id_number' => [
                ['document_id', 'company_id','category_id','template_id','doc_type_id','status_id','doc_no', 'product_name','title', 'amount', 'currency_id', 'counter_party_id', 'sign_level','cont_start_date', 'cont_end_date', 'conc_date', 'effective_date','cancel_date','expiry_date'],
                [1, 1, 1, 1, 1, 'a', 'test', 'test', 'test', 12345, 1, 1, 1, '2022-10-10','2022-10-10','2022-10-10','2022-10-10','2022-10-10','2022-10-10'],
                false
            ],
            'doc_no_string' => [
                ['document_id', 'company_id','category_id','template_id','doc_type_id','status_id','doc_no', 'product_name','title', 'amount', 'currency_id', 'counter_party_id', 'sign_level','cont_start_date', 'cont_end_date', 'conc_date', 'effective_date','cancel_date','expiry_date'],
                [1, 1, 1, 1, 1, 1, 12, 'test', 'test', 12345, 1, 1, 1, '2022-10-10','2022-10-10','2022-10-10','2022-10-10','2022-10-10','2022-10-10'],
                false
            ],
            'doc_no_length' => [
                ['document_id', 'company_id','category_id','template_id','doc_type_id','status_id','doc_no', 'product_name','title', 'amount', 'currency_id', 'counter_party_id', 'sign_level','cont_start_date', 'cont_end_date', 'conc_date', 'effective_date','cancel_date','expiry_date'],
                [1, 1, 1, 1, 1, 1, '11111111111111111111111111111111111111111111111111', 'test', 'test', 12345, 1, 1, 1, '2022-10-10','2022-10-10','2022-10-10','2022-10-10','2022-10-10','2022-10-10'],
                false
            ],
            'product_name_string' => [
                ['document_id', 'company_id','category_id','template_id','doc_type_id','status_id','doc_no', 'product_name','title', 'amount', 'currency_id', 'counter_party_id', 'sign_level','cont_start_date', 'cont_end_date', 'conc_date', 'effective_date','cancel_date','expiry_date'],
                [1, 1, 1, 1, 1, 1, 'test', 1, 'test', 12345, 1, 1, 1, '2022-10-10','2022-10-10','2022-10-10','2022-10-10','2022-10-10','2022-10-10'],
                false
            ],
            'title_required' => [
                ['document_id', 'company_id','category_id','template_id','doc_type_id','status_id','doc_no', 'product_name','title', 'amount', 'currency_id', 'counter_party_id', 'sign_level','cont_start_date', 'cont_end_date', 'conc_date', 'effective_date','cancel_date','expiry_date'],
                [1, 1, 1, 1, 1, 1, 'test', 'test', null, 12345, 1, 1, 1, '2022-10-10','2022-10-10','2022-10-10','2022-10-10','2022-10-10','2022-10-10'],
                false
            ],
            'title_string' => [
                ['document_id', 'company_id','category_id','template_id','doc_type_id','status_id','doc_no', 'product_name','title', 'amount', 'currency_id', 'counter_party_id', 'sign_level','cont_start_date', 'cont_end_date', 'conc_date', 'effective_date','cancel_date','expiry_date'],
                [1, 1, 1, 1, 1, 1, 'test', 'test', 100, 12345, 1, 1, 1, '2022-10-10','2022-10-10','2022-10-10','2022-10-10','2022-10-10','2022-10-10'],
                false
            ],
            'amount_required' => [
                ['document_id', 'company_id','category_id','template_id','doc_type_id','status_id','doc_no', 'product_name','title', 'amount', 'currency_id', 'counter_party_id', 'sign_level','cont_start_date', 'cont_end_date', 'conc_date', 'effective_date','cancel_date','expiry_date'],
                [1, 1, 1, 1, 1, 1, 'test', 'test', 'test', null, 1, 1, 1, '2022-10-10','2022-10-10','2022-10-10','2022-10-10','2022-10-10','2022-10-10'],
                false
            ],
            'amount_number' => [
                ['document_id', 'company_id','category_id','template_id','doc_type_id','status_id','doc_no', 'product_name','title', 'amount', 'currency_id', 'counter_party_id', 'sign_level','cont_start_date', 'cont_end_date', 'conc_date', 'effective_date','cancel_date','expiry_date'],
                [1, 1, 1, 1, 1, 1, 'test', 'test', 'test', 'testtest', 1, 1, 1, '2022-10-10','2022-10-10','2022-10-10','2022-10-10','2022-10-10','2022-10-10'],
                false
            ],
            'currency_id_requied' => [
                ['document_id', 'company_id','category_id','template_id','doc_type_id','status_id','doc_no', 'product_name','title', 'amount', 'currency_id', 'counter_party_id', 'sign_level','cont_start_date', 'cont_end_date', 'conc_date', 'effective_date','cancel_date','expiry_date'],
                [1, 1, 1, 1, 1, 1, 'test', 'test', 'test', 12345, null, 1, 1, '2022-10-10','2022-10-10','2022-10-10','2022-10-10','2022-10-10','2022-10-10'],
                false
            ],
            'currency_id_number' => [
                ['document_id', 'company_id','category_id','template_id','doc_type_id','status_id','doc_no', 'product_name','title', 'amount', 'currency_id', 'counter_party_id', 'sign_level','cont_start_date', 'cont_end_date', 'conc_date', 'effective_date','cancel_date','expiry_date'],
                [1, 1, 1, 1, 1, 1, 'test', 'test', 'test', 12345, 'testtest', 1, 1, '2022-10-10','2022-10-10','2022-10-10','2022-10-10','2022-10-10','2022-10-10'],
                false
            ],
            'counter_party_id_number' => [
                ['document_id', 'company_id','category_id','template_id','doc_type_id','status_id','doc_no', 'product_name','title', 'amount', 'currency_id', 'counter_party_id', 'sign_level','cont_start_date', 'cont_end_date', 'conc_date', 'effective_date','cancel_date','expiry_date'],
                [1, 1, 1, 1, 1, 1, 'test', 'test', 'test', 12345, 1, 'testtest', 1, '2022-10-10','2022-10-10','2022-10-10','2022-10-10','2022-10-10','2022-10-10'],
                false
            ],
            'sign_level_number' => [
                ['document_id', 'company_id','category_id','template_id','doc_type_id','status_id','doc_no', 'product_name','title', 'amount', 'currency_id', 'counter_party_id', 'sign_level','cont_start_date', 'cont_end_date', 'conc_date', 'effective_date','cancel_date','expiry_date'],
                [1, 1, 1, 1, 1, 1, 'test', 'test', 'test', 12345, 1, 1, 'testtest', '2022-10-10','2022-10-10','2022-10-10','2022-10-10','2022-10-10','2022-10-10'],
                false
            ],


            'cont_start_date_date' => [
                ['document_id', 'company_id','category_id','template_id','doc_type_id','status_id','doc_no', 'product_name','title', 'amount', 'currency_id', 'counter_party_id', 'sign_level','cont_start_date', 'cont_end_date', 'conc_date', 'effective_date','cancel_date','expiry_date'],
                [1, 1, 1, 1, 1, 1, 'test', 'test', 'test', 12345, 1, 1, 'testtest', 'testtest','2022-10-10','2022-10-10','2022-10-10','2022-10-10','2022-10-10'],
                false
            ],
            'cont_end_date_date' => [
                ['document_id', 'company_id','category_id','template_id','doc_type_id','status_id','doc_no', 'product_name','title', 'amount', 'currency_id', 'counter_party_id', 'sign_level','cont_start_date', 'cont_end_date', 'conc_date', 'effective_date','cancel_date','expiry_date'],
                [1, 1, 1, 1, 1, 1, 'test', 'test', 'test', 12345, 1, 1, 'testtest', '2022-10-10',11111,'2022-10-10','2022-10-10','2022-10-10','2022-10-10'],
                false
            ],
            'conc_date_date' => [
                ['document_id', 'company_id','category_id','template_id','doc_type_id','status_id','doc_no', 'product_name','title', 'amount', 'currency_id', 'counter_party_id', 'sign_level','cont_start_date', 'cont_end_date', 'conc_date', 'effective_date','cancel_date','expiry_date'],
                [1, 1, 1, 1, 1, 1, 'test', 'test', 'test', 12345, 1, 1, 'testtest', '2022-10-10',2022-10-10,'test','2022-10-10','2022-10-10','2022-10-10'],
                false
            ],
            'effective_date_date' => [
                ['document_id', 'company_id','category_id','template_id','doc_type_id','status_id','doc_no', 'product_name','title', 'amount', 'currency_id', 'counter_party_id', 'sign_level','cont_start_date', 'cont_end_date', 'conc_date', 'effective_date','cancel_date','expiry_date'],
                [1, 1, 1, 1, 1, 1, 'test', 'test', 'test', 12345, 1, 1, 'testtest', '2022-10-10',2022-10-10,'2022-10-10','testtest','2022-10-10','2022-10-10'],
                false
            ],
            'cancel_date_date' => [
                ['document_id', 'company_id','category_id','template_id','doc_type_id','status_id','doc_no', 'product_name','title', 'amount', 'currency_id', 'counter_party_id', 'sign_level','cont_start_date', 'cont_end_date', 'conc_date', 'effective_date','cancel_date','expiry_date'],
                [1, 1, 1, 1, 1, 1, 'test', 'test', 'test', 12345, 1, 1, 'testtest', '2022-10-10',2022-10-10,'2022-10-10','2022-10-10','testtest','2022-10-10'],
                false
            ],
            'expiry_date_date' => [
                ['document_id', 'company_id','category_id','template_id','doc_type_id','status_id','doc_no', 'product_name','title', 'amount', 'currency_id', 'counter_party_id', 'sign_level','cont_start_date', 'cont_end_date', 'conc_date', 'effective_date','cancel_date','expiry_date'],
                [1, 1, 1, 1, 1, 1, 'test', 'test', 'test', 12345, 1, 1, 'testtest', '2022-10-10',2022-10-10,'2022-10-10','2022-10-10','2022-10-10','testtesttest'],
                false
            ],
            // deal
            'OK' => [
                ['document_id', 'company_id','category_id','template_id','doc_type_id','status_id','doc_no', 'product_name','title', 'amount', 'currency_id', 'counter_party_id', 'sign_level', 'issue_date', 'payment_date', 'transaction_date', 'download_date'],
                [1, 1, 0, 1, 1, 1, 'test', 'test', 'test', 12345, 1, 1, 1, '2022-10-10', '2022-10-10', '2022-10-10', '2022-10-10'],
                true
            ],
            'issue_date_date' => [
                ['document_id', 'company_id','category_id','template_id','doc_type_id','status_id','doc_no', 'product_name','title', 'amount', 'currency_id', 'counter_party_id', 'sign_level', 'issue_date', 'payment_date', 'transaction_date', 'download_date'],
                [1, 1, 0, 1, 1, 1, 'test', 'test', 'test', 12345, 1, 1, 1, 'testtest', '2022-10-10', '2022-10-10', '2022-10-10'],
                false
            ],
            'payment_date_date' => [
                ['document_id', 'company_id','category_id','template_id','doc_type_id','status_id','doc_no', 'product_name','title', 'amount', 'currency_id', 'counter_party_id', 'sign_level', 'issue_date', 'payment_date', 'transaction_date', 'download_date'],
                [1, 1, 0, 1, 1, 1, 'test', 'test', 'test', 12345, 1, 1, 1, '2022-10-10', 'testtest', '2022-10-10', '2022-10-10'],
                false
            ],
            'transaction_date_date' => [
                ['document_id', 'company_id','category_id','template_id','doc_type_id','status_id','doc_no', 'product_name','title', 'amount', 'currency_id', 'counter_party_id', 'sign_level', 'issue_date', 'payment_date', 'transaction_date', 'download_date'],
                [1, 1, 0, 1, 1, 1, 'test', 'test', 'test', 12345, 1, 1, 1, '2022-10-10', '2022-10-10', 'test', '2022-10-10'],
                false
            ],
            'download_date_date' => [
                ['document_id', 'company_id','category_id','template_id','doc_type_id','status_id','doc_no', 'product_name','title', 'amount', 'currency_id', 'counter_party_id', 'sign_level', 'issue_date', 'payment_date', 'transaction_date', 'download_date'],
                [1, 1, 0, 1, 1, 1, 'test', 'test', 'test', 12345, 1, 1, 1, '2022-10-10', '2022-10-10', '2022-10-10', 'testest'],
                false
            ],

            //internal
            'OK' => [
                ['document_id', 'company_id','category_id','template_id','doc_type_id','status_id','doc_no', 'product_name','title', 'amount', 'currency_id', 'counter_party_id', 'sign_level', 'doc_create_date', 'sign_finish_date', 'content'],
                [1, 1, 0, 1, 1, 1, 'test', 'test', 'test', 12345, 1, 1, 1, '2022-10-10', '2022-10-10', 'testest'],
                true
            ],
            'doc_create_date_date' => [
                ['document_id', 'company_id','category_id','template_id','doc_type_id','status_id','doc_no', 'product_name','title', 'amount', 'currency_id', 'counter_party_id', 'sign_level', 'doc_create_date', 'sign_finish_date', 'content'],
                [1, 1, 0, 1, 1, 1, 'test', 'test', 'test', 12345, 1, 1, 1, 'testtesttest', '2022-10-10', 'testest'],
                false
            ],
            'sign_finish_date_date' => [
                ['document_id', 'company_id','category_id','template_id','doc_type_id','status_id','doc_no', 'product_name','title', 'amount', 'currency_id', 'counter_party_id', 'sign_level', 'doc_create_date', 'sign_finish_date', 'content'],
                [1, 1, 0, 1, 1, 1, 'test', 'test', 'test', 12345, 1, 1, 1, '2022-10-10', 'testestesst', 'testest'],
                false
            ],
            'content_string' => [
                ['document_id', 'company_id','category_id','template_id','doc_type_id','status_id','doc_no', 'product_name','title', 'amount', 'currency_id', 'counter_party_id', 'sign_level', 'doc_create_date', 'sign_finish_date', 'content'],
                [1, 1, 0, 1, 1, 1, 'test', 'test', 'test', 12345, 1, 1, 1, '2022-10-10', '2022-10-10', 11111111],
                false
            ],
            
            //archive
            'OK' => [
                ['document_id', 'company_id','category_id','template_id','doc_type_id','status_id','doc_no', 'product_name','title', 'amount', 'currency_id', 'counter_party_id', 'sign_level', 'scan_doc_flg', 'issue_date', 'expiry_date', 'transaction_date', 'timestamp_user'],
                [1, 1, 0, 1, 1, 1, 'test', 'test', 'test', 12345, 1, 1, 1, 1, '2022-10-10', '2022-10-10', '2022-10-10', 1],
                true
            ],
            'scan_doc_flg_number' => [
                ['document_id', 'company_id','category_id','template_id','doc_type_id','status_id','doc_no', 'product_name','title', 'amount', 'currency_id', 'counter_party_id', 'sign_level', 'scan_doc_flg', 'issue_date', 'expiry_date', 'transaction_date', 'timestamp_user'],
                [1, 1, 0, 1, 1, 1, 'test', 'test', 'test', 12345, 1, 1, 1, 'testtest', '2022-10-10', '2022-10-10', '2022-10-10', 1],
                false
            ],
            'issue_date_date' => [
                ['document_id', 'company_id','category_id','template_id','doc_type_id','status_id','doc_no', 'product_name','title', 'amount', 'currency_id', 'counter_party_id', 'sign_level', 'scan_doc_flg', 'issue_date', 'expiry_date', 'transaction_date', 'timestamp_user'],
                [1, 1, 0, 1, 1, 1, 'test', 'test', 'test', 12345, 1, 1, 1, 1, 111111, '2022-10-10', '2022-10-10', 1],
                false
            ],
            'expiry_date_date' => [
                ['document_id', 'company_id','category_id','template_id','doc_type_id','status_id','doc_no', 'product_name','title', 'amount', 'currency_id', 'counter_party_id', 'sign_level', 'scan_doc_flg', 'issue_date', 'expiry_date', 'transaction_date', 'timestamp_user'],
                [1, 1, 0, 1, 1, 1, 'test', 'test', 'test', 12345, 1, 1, 1, 1, '2022-10-10', 1111111, '2022-10-10', 1],
                false
            ],
            'transacition_date_date' => [
                ['document_id', 'company_id','category_id','template_id','doc_type_id','status_id','doc_no', 'product_name','title', 'amount', 'currency_id', 'counter_party_id', 'sign_level', 'scan_doc_flg', 'issue_date', 'expiry_date', 'transaction_date', 'timestamp_user'],
                [1, 1, 0, 1, 1, 1, 'test', 'test', 'test', 12345, 1, 1, 1, 1, '2022-10-10', '2022-10-10', 'testtesttest ', 1],
                false
            ],
            'timestamp_user_id' => [
                ['document_id', 'company_id','category_id','template_id','doc_type_id','status_id','doc_no', 'product_name','title', 'amount', 'currency_id', 'counter_party_id', 'sign_level', 'scan_doc_flg', 'issue_date', 'expiry_date', 'transaction_date', 'timestamp_user'],
                [1, 1, 0, 1, 1, 1, 'test', 'test', 'test', 12345, 1, 1, 1, 1, '2022-10-10', '2022-10-10', '2022-10-10', 'testtest'],
                false
            ],

            
            //storage
            'file_name_string' => [
                ['file_name', 'document_id', 'company_id','category_id','template_id','doc_type_id','status_id','doc_no', 'product_name','title', 'amount', 'currency_id', 'counter_party_id', 'sign_level','cont_start_date', 'cont_end_date', 'conc_date', 'effective_date','cancel_date','expiry_date'],
                [1, 1, 1, 0, 1, 1, 1, 'test', 'test', 'test', 12345, 1, 1, 1, '2022-10-10','2022-10-10','2022-10-10','2022-10-10','2022-10-10','2022-10-10'],
                false
            ],
            'file_path_string' => [
                ['file_path', 'document_id', 'company_id','category_id','template_id','doc_type_id','status_id','doc_no', 'product_name','title', 'amount', 'currency_id', 'counter_party_id', 'sign_level','cont_start_date', 'cont_end_date', 'conc_date', 'effective_date','cancel_date','expiry_date'],
                [1, 1, 1, 0, 1, 1, 1, 'test', 'test', 'test', 12345, 1, 1, 1, '2022-10-10','2022-10-10','2022-10-10','2022-10-10','2022-10-10','2022-10-10'],
                false
            ],
            'file_hash_string' => [
                ['file_hash', 'document_id', 'company_id','category_id','template_id','doc_type_id','status_id','doc_no', 'product_name','title', 'amount', 'currency_id', 'counter_party_id', 'sign_level','cont_start_date', 'cont_end_date', 'conc_date', 'effective_date','cancel_date','expiry_date'],
                [1, 1, 1, 0, 1, 1, 1, 'test', 'test', 'test', 12345, 1, 1, 1, '2022-10-10','2022-10-10','2022-10-10','2022-10-10','2022-10-10','2022-10-10'],
                false
            ],
            'file_prot_flg_number' => [
                ['file_prot_flg', 'document_id', 'company_id','category_id','template_id','doc_type_id','status_id','doc_no', 'product_name','title', 'amount', 'currency_id', 'counter_party_id', 'sign_level','cont_start_date', 'cont_end_date', 'conc_date', 'effective_date','cancel_date','expiry_date'],
                ['hohohhohoho', 1, 1, 0, 1, 1, 1, 'test', 'test', 'test', 12345, 1, 1, 1, '2022-10-10','2022-10-10','2022-10-10','2022-10-10','2022-10-10','2022-10-10'],
                false
            ],
            'file_prot_pw_flg_number' => [
                ['file_prot_pw_flg', 'document_id', 'company_id','category_id','template_id','doc_type_id','status_id','doc_no', 'product_name','title', 'amount', 'currency_id', 'counter_party_id', 'sign_level','cont_start_date', 'cont_end_date', 'conc_date', 'effective_date','cancel_date','expiry_date'],
                ['hohohhohoho', 1, 1, 0, 1, 1, 1, 'test', 'test', 'test', 12345, 1, 1, 1, '2022-10-10','2022-10-10','2022-10-10','2022-10-10','2022-10-10','2022-10-10'],
                false
            ],
            'file_timestamp_flg_number' => [
                ['file_timestamp_flg', 'document_id', 'company_id','category_id','template_id','doc_type_id','status_id','doc_no', 'product_name','title', 'amount', 'currency_id', 'counter_party_id', 'sign_level','cont_start_date', 'cont_end_date', 'conc_date', 'effective_date','cancel_date','expiry_date'],
                ['hohohhohoho', 1, 1, 0, 1, 1, 1, 'test', 'test', 'test', 12345, 1, 1, 1, '2022-10-10','2022-10-10','2022-10-10','2022-10-10','2022-10-10','2022-10-10'],
                false
            ],
            'file_sign_number' => [
                ['file_sign', 'document_id', 'company_id','category_id','template_id','doc_type_id','status_id','doc_no', 'product_name','title', 'amount', 'currency_id', 'counter_party_id', 'sign_level','cont_start_date', 'cont_end_date', 'conc_date', 'effective_date','cancel_date','expiry_date'],
                ['hohohhohoho', 1, 1, 0, 1, 1, 1, 'test', 'test', 'test', 12345, 1, 1, 1, '2022-10-10','2022-10-10','2022-10-10','2022-10-10','2022-10-10','2022-10-10'],
                false
            ],
            'width_number' => [
                ['width', 'document_id', 'company_id','category_id','template_id','doc_type_id','status_id','doc_no', 'product_name','title', 'amount', 'currency_id', 'counter_party_id', 'sign_level','cont_start_date', 'cont_end_date', 'conc_date', 'effective_date','cancel_date','expiry_date'],
                ['hohohhohoho', 1, 1, 0, 1, 1, 1, 'test', 'test', 'test', 12345, 1, 1, 1, '2022-10-10','2022-10-10','2022-10-10','2022-10-10','2022-10-10','2022-10-10'],
                false
            ],
            'height_number' => [
                ['height', 'document_id', 'company_id','category_id','template_id','doc_type_id','status_id','doc_no', 'product_name','title', 'amount', 'currency_id', 'counter_party_id', 'sign_level','cont_start_date', 'cont_end_date', 'conc_date', 'effective_date','cancel_date','expiry_date'],
                ['hohohhohoho', 1, 1, 0, 1, 1, 1, 'test', 'test', 'test', 12345, 1, 1, 1, '2022-10-10','2022-10-10','2022-10-10','2022-10-10','2022-10-10','2022-10-10'],
                false
            ],
            'dpi_number' => [
                ['dpi', 'document_id', 'company_id','category_id','template_id','doc_type_id','status_id','doc_no', 'product_name','title', 'amount', 'currency_id', 'counter_party_id', 'sign_level','cont_start_date', 'cont_end_date', 'conc_date', 'effective_date','cancel_date','expiry_date'],
                ['hohohhohoho', 1, 1, 0, 1, 1, 1, 'test', 'test', 'test', 12345, 1, 1, 1, '2022-10-10','2022-10-10','2022-10-10','2022-10-10','2022-10-10','2022-10-10'],
                false
            ],
            'color_depth_number' => [
                ['color_depth', 'document_id', 'company_id','category_id','template_id','doc_type_id','status_id','doc_no', 'product_name','title', 'amount', 'currency_id', 'counter_party_id', 'sign_level','cont_start_date', 'cont_end_date', 'conc_date', 'effective_date','cancel_date','expiry_date'],
                ['hohohhohoho', 1, 1, 0, 1, 1, 1, 'test', 'test', 'test', 12345, 1, 1, 1, '2022-10-10','2022-10-10','2022-10-10','2022-10-10','2022-10-10','2022-10-10'],
                false
            ],
            'pdf_type_string' => [
                ['pdf_type', 'document_id', 'company_id','category_id','template_id','doc_type_id','status_id','doc_no', 'product_name','title', 'amount', 'currency_id', 'counter_party_id', 'sign_level','cont_start_date', 'cont_end_date', 'conc_date', 'effective_date','cancel_date','expiry_date'],
                [1, 1, 1, 0, 1, 1, 1, 'test', 'test', 'test', 12345, 1, 1, 1, '2022-10-10','2022-10-10','2022-10-10','2022-10-10','2022-10-10','2022-10-10'],
                false
            ],
            'pdf_version_string' => [
                ['pdf_version', 'document_id', 'company_id','category_id','template_id','doc_type_id','status_id','doc_no', 'product_name','title', 'amount', 'currency_id', 'counter_party_id', 'sign_level','cont_start_date', 'cont_end_date', 'conc_date', 'effective_date','cancel_date','expiry_date'],
                [1, 1, 1, 0, 1, 1, 1, 'test', 'test', 'test', 12345, 1, 1, 1, '2022-10-10','2022-10-10','2022-10-10','2022-10-10','2022-10-10','2022-10-10'],
                false
            ],
            'toral_pages_number' => [
                ['pdf_version', 'document_id', 'company_id','category_id','template_id','doc_type_id','status_id','doc_no', 'product_name','title', 'amount', 'currency_id', 'counter_party_id', 'sign_level','cont_start_date', 'cont_end_date', 'conc_date', 'effective_date','cancel_date','expiry_date'],
                ['testtest', 1, 1, 0, 1, 1, 1, 'test', 'test', 'test', 12345, 1, 1, 1, '2022-10-10','2022-10-10','2022-10-10','2022-10-10','2022-10-10','2022-10-10'],
                false
            ],            
        ];
    }  
}
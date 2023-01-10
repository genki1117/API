<?php

namespace Tests\Unit\Request\Document;

use App\Domain\Consts\UserConst;
use App\Domain\Entities\Users\User;
use App\Foundations\Context\LoggedInUserContext;
use App\Http\Requests\Document\DocumentTransferRequest;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class DcoumentSaveRequestTest extends TestCase
{
    /**
     *  @test
     * 契約書類バリデーションテスト
     * 正常入力
     */
     public function contractDocumentSaveRequestTest()
    {
        $requestParams = [
            'document_id'       => 1,
            'company_id'        => 1,
            'category_id'       => 0,
            'template_id'       => 1,
            'doc_type_id'       => 1,
            'status_id'         => 1,
            'doc_no'            => 'test',
            'product_name'      => 'test',
            'title'             => 'test',
            'amount'            => 12345,
            'currency_id'       => 1,
            'counter_party_id'  => 1,
            'sign_level'        => 1,
            'cont_start_date'   => '2022-10-10',
            'cont_end_date'     => '2022-10-10',
            'conc_date'         => '2022-10-10',
            'effective_date'    => '2022-10-10',
            'cancel_date'       => '2022-10-10',
            'expiry_date'       => '2022-10-10',
        ];

        $request = new DocumentSaveRequest;
        $rules = $request->rules();

        $validator = Validator::make($requestParams, $rules);
        $result = $validator->passes();

        $this->assertTrue($result);
    }

    /**
     *  @test
     * 取引書類バリデーションテスト
     * 正常入力
     */
    public function dealDocumentSaveRequestTest()
    {
        $requestParams = [
            'document_id'       => 1,
            'company_id'        => 1,
            'category_id'       => 1,
            'template_id'       => 1,
            'doc_type_id'       => 1,
            'status_id'         => 1,
            'doc_no'            => 'test',
            'product_name'      => 'test',
            'title'             => 'test',
            'amount'            => 12345,
            'currency_id'       => 1,
            'counter_party_id'  => 1,
            'sign_level'        => 1,
            'issue_date'        => '2022-10-10',
            'payment_date'      => '2022-10-10',
            'transaction_date'  => '2022-10-10',
            'download_date'     => '2022-10-10',
        ];

        $request = new DocumentSaveRequest;
        $rules = $request->rules();

        $validator = Validator::make($requestParams, $rules);
        $result = $validator->passes();

        $this->assertTrue($result);
    }


    /**
     *  @test
     * 社内書類バリデーションテスト
     * 正常入力
     */
    public function internalDocumentSaveRequestTest()
    {
        $requestParams = [
            'document_id'       => 1,
            'company_id'        => 1,
            'category_id'       => 2,
            'template_id'       => 1,
            'doc_type_id'       => 1,
            'status_id'         => 1,
            'doc_no'            => 'test',
            'product_name'      => 'test',
            'title'             => 'test',
            'amount'            => 12345,
            'currency_id'       => 1,
            'counter_party_id'  => 1,
            'sign_level'        => 1,
            'doc_create_date'   => '2022-10-10',
            'sign_finish_date'  => '2022-10-10',
            'content'           => 'test',
        ];

        $request = new DocumentSaveRequest;
        $rules = $request->rules();

        $validator = Validator::make($requestParams, $rules);
        $result = $validator->passes();

        $this->assertTrue($result);
    }

    /**
     *  @test
     * 登録書類バリデーションテスト
     * 正常入力
     */
    public function archiveDocumentSaveRequestTest()
    {
        $requestParams = [
            'document_id'       => 1,
            'company_id'        => 1,
            'category_id'       => 3,
            'template_id'       => 1,
            'doc_type_id'       => 1,
            'status_id'         => 1,
            'doc_no'            => 'test',
            'product_name'      => 'test',
            'title'             => 'test',
            'amount'            => 12345,
            'currency_id'       => 1,
            'counter_party_id'  => 1,
            'sign_level'        => 1,
            'scan_doc_flg'      => 1,
            'issue_date'        => '2022-10-10',
            'expiry_date'       => '2022-10-10',
            'transaction_date'  => '2022-10-10',
            'timestamp_user'    => 11,
        ];

        $request = new DocumentSaveRequest;
        $rules = $request->rules();

        $validator = Validator::make($requestParams, $rules);
        $result = $validator->passes();

        $this->assertTrue($result);
    }


    /**
     *  @test
     * 必須チェック
     */
    public function DocumentSaveRequestRequiredTest()
    {
        $requestParams = [
            'document_id'       => 1,
            'company_id'        => 1,
            'category_id'       => null,
            'template_id'       => 1,
            'doc_type_id'       => 1,
            'status_id'         => 1,
            'doc_no'            => 'test',
            'product_name'      => 'test',
            'title'             => 'test',
            'amount'            => 12345,
            'currency_id'       => 1,
            'counter_party_id'  => 1,
            'sign_level'        => 1,
            'cont_start_date'   => '2022-10-10',
            'cont_end_date'     => '2022-10-10',
            'conc_date'         => '2022-10-10',
            'effective_date'    => '2022-10-10',
            'cancel_date'       => '2022-10-10',
            'expiry_date'       => '2022-10-10',
        ];

        $request = new DocumentSaveRequest;
        $rules = $request->rules();

        $validator = Validator::make($requestParams, $rules);
        $result = $validator->passes();

        $this->assertFalse($result);
    }

    /**
     *  @test
     * 契約書類バリデーションテスト
     * 日付チェック
     */
    public function contractDocumentSaveRequestDateTypeTest()
    {
        $requestParams = [
            'document_id'       => 1,
            'company_id'        => 1,
            'category_id'       => 0,
            'template_id'       => 1,
            'doc_type_id'       => 1,
            'status_id'         => 1,
            'doc_no'            => 'test',
            'product_name'      => 'test',
            'title'             => 'test',
            'amount'            => 12345,
            'currency_id'       => 1,
            'counter_party_id'  => 1,
            'sign_level'        => 1,
            'cont_start_date'   => 11111,
            'cont_end_date'     => '2022-10-10',
            'conc_date'         => '2022-10-10',
            'effective_date'    => '2022-10-10',
            'cancel_date'       => '2022-10-10',
            'expiry_date'       => '2022-10-10',
        ];

        $request = new DocumentSaveRequest;
        $rules = $request->rules();

        $validator = Validator::make($requestParams, $rules);
        $result = $validator->passes();

        $this->assertFalse($result);
    }

    /**
     *  @test
     * 取引書類バリデーションテスト
     * 日付書式
     */
    public function dealDocumentSaveRequestDateTypeTest()
    {
        $requestParams = [
            'document_id'       => 1,
            'company_id'        => 1,
            'category_id'       => 1,
            'template_id'       => 1,
            'doc_type_id'       => 1,
            'status_id'         => 1,
            'doc_no'            => 'test',
            'product_name'      => 'test',
            'title'             => 'test',
            'amount'            => 12345,
            'currency_id'       => 1,
            'counter_party_id'  => 1,
            'sign_level'        => 1,
            'issue_date'        => 1111,
            'payment_date'      => '2022-10-10',
            'transaction_date'  => '2022-10-10',
            'download_date'     => '2022-10-10',
        ];

        $request = new DocumentSaveRequest;
        $rules = $request->rules();

        $validator = Validator::make($requestParams, $rules);
        $result = $validator->passes();

        $this->assertFalse($result);
    }

     /**
     *  @test
     * 社内書類バリデーションテスト
     * 日付書式
     */
    public function internalDocumentSaveRequestDateTypeTest()
    {
        $requestParams = [
            'document_id'       => 1,
            'company_id'        => 1,
            'category_id'       => 2,
            'template_id'       => 1,
            'doc_type_id'       => 1,
            'status_id'         => 1,
            'doc_no'            => 'test',
            'product_name'      => 'test',
            'title'             => 'test',
            'amount'            => 12345,
            'currency_id'       => 1,
            'counter_party_id'  => 1,
            'sign_level'        => 1,
            'doc_create_date'   => '2022-10-10',
            'sign_finish_date'  => 'テスト',
            'content'           => 'test',
        ];

        $request = new DocumentSaveRequest;
        $rules = $request->rules();

        $validator = Validator::make($requestParams, $rules);
        $result = $validator->passes();

        $this->assertFalse($result);
    }

    /**
     *  @test
     * 登録書類バリデーションテスト
     * 日付書式
     */
    public function archiveDocumentSaveRequestDateTypeTest()
    {
        $requestParams = [
            'document_id'       => 1,
            'company_id'        => 1,
            'category_id'       => 3,
            'template_id'       => 1,
            'doc_type_id'       => 1,
            'status_id'         => 1,
            'doc_no'            => 'test',
            'product_name'      => 'test',
            'title'             => 'test',
            'amount'            => 12345,
            'currency_id'       => 1,
            'counter_party_id'  => 1,
            'sign_level'        => 1,
            'scan_doc_flg'      => 1,
            'issue_date'        => '2022-10-10',
            'expiry_date'       => 1234,
            'transaction_date'  => '2022-10-10',
            'timestamp_user'    => 11,
        ];

        $request = new DocumentSaveRequest;
        $rules = $request->rules();

        $validator = Validator::make($requestParams, $rules);
        $result = $validator->passes();

        $this->assertFalse($result);
    }
}
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

        $userData = (object)[
            'user_id' => 1,
            'user_type_id' => UserConst::USER_TYPE_HOST,
            'company_id' => 1,
        ];
        $user = new User(mUser:$userData, mUserRole:null);
        $userContext = new LoggedInUserContext();
        $userContext->set($user);

        $request = new DocumentSaveRequest($userContext);
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
            // 'faild_documentId_require' => [
            //     ['document_id', 'category_id','title','email','content','update_datetime'],
            //     ['', 0, '書類転送', 'test@email.com', 'お願いします', 1673024259],
            //     false
            // ],
            // 'faild_documentId_numeric' => [
            //     ['document_id', 'category_id','title','email','content','update_datetime'],
            //     ['a', 0, '書類転送', 'test@email.com', 'お願いします', 1673024259],
            //     false
            // ],
            // 'faild_categoryId_require' => [
            //     ['document_id', 'category_id','title','email','content','update_datetime'],
            //     [1, '', '書類転送', 'test@email.com', 'お願いします', 1673024259],
            //     false
            // ],
            // 'faild_categoryId_numeric' => [
            //     ['document_id', 'category_id','title','email','content','update_datetime'],
            //     [1, 'a', '書類転送', 'test@email.com', 'お願いします', 1673024259],
            //     false
            // ],
            // 'faild_title_require' => [
            //     ['document_id', 'category_id','title','email','content','update_datetime'],
            //     [1, 0, '', 'test@email.com', 'お願いします', 1673024259],
            //     false
            // ],
            // 'faild_title_max' => [
            //     ['document_id', 'category_id','title','email','content','update_datetime'],
            //     [1, 0, '書類転送のお知らせ！書類転送のお知らせ！書類転送のお知らせ！書類転送のお知らせ！書類転送のお知らせ！書類転送のお知らせ！書類転送のお知らせ！書類転送のお知らせ！書類転送のお知らせ！書類転送のお知らせ！＠', 'test@email.com', 'お願いします', 1673024259],
            //     false
            // ],
            // 'faild_email_require' => [
            //     ['document_id', 'category_id','title','email','content','update_datetime'],
            //     [1, 0, '書類転送', '', 'お願いします', 1673024259],
            //     false
            // ],
            // 'faild_email_emal' => [
            //     ['document_id', 'category_id','title','email','content','update_datetime'],
            //     [1, 0, '書類転送', 'testemail.com', 'お願いします', 1673024259],
            //     false
            // ],
            // 'faild_email_max' => [
            //     ['document_id', 'category_id','title','email','content','update_datetime'],
            //     [1, 0, '書類転送', '1234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901@email.com', 'お願いします', 1673024259],
            //     false
            // ],
            // 'faild_content_require' => [
            //     ['document_id', 'category_id','title','email','content','update_datetime'],
            //     [1, 0, '書類転送', 'test@email.com', '', 1673024259],
            //     false
            // ],
            // 'faild_content_max' => [
            //     ['document_id', 'category_id','title','email','content','update_datetime'],
            //     [1, 0, '書類転送', 'test@email.com', '12345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901', 1673024259],
            //     false
            // ],
            // 'faild_updateDatetime_require' => [
            //     ['document_id', 'category_id','title','email','content','update_datetime'],
            //     [1, 0, '書類転送', 'test@email.com', 'お願いします', ''],
            //     false
            // ],
            // 'faild_updateDatetime_require' => [
            //     ['document_id', 'category_id','title','email','content','update_datetime'],
            //     [1, 0, '書類転送', 'test@email.com', 'お願いします', 'a'],
            //     false
            // ],
        ];
    }  
}
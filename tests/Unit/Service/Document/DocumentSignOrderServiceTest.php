<?php

namespace Tests\Unit\Service\Document;

use App\Domain\Consts\QueueConst;
use App\Accessers\Queue\QueueUtility;
use App\Domain\Entities\Document\DocumentSignOrder;
use App\Domain\Repositories\Interface\Document\DocumentSignOrderRepositoryInterface;
use App\Domain\Services\Document\DocumentSignOrderService;
use Exception;
use App\Domain\Consts\UserTypeConst;
use App\Domain\Consts\DocumentConst;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

class DocumentSignOrderServiceTest extends TestCase
{
    private MockInterface|LegacyMockInterface $documentRepositoryMock;

    public function setUp(): void
    {
        parent::setUp();
        $this->queueConst             = new QueueConst;
        $this->queue                  = new QueueUtility;
        $this->userConst              = new UserTypeConst;
        $this->docConst               = new DocumentConst;
        $this->documentRepositoryMock = \Mockery::mock(DocumentSignOrderRepositoryInterface::class);
    }

    /**
     * @test
     * 契約書類
     *  file_prot_pw_flgがtrueだった場合エラー
     * @return void
     */
    public function signOrderContractFileProtPwFlgTrueTest()
    {
        $dataDoc = (object)[
            'document_id' => 1,
            'title' => '契約書類テストタイトル',
            'file_prot_pw_flg' => 0
    ];
        $this->documentRepositoryMock->shouldReceive('getContractIsseuAndNextSignUserInfo->getSignDoc')
        ->once()
        ->andReturn($dataDoc);
        
        $this->expectException(Exception::class);
        $this->getObject()->signOrder(mUserId: 1, mUserCompanyId: 1, mUserTypeId: 0, documentId: 1, docTypeId: 1, categoryId: 0);
    }

    /**
     * @test
     * 取引書類
     *  file_prot_pw_flgがtrueだった場合エラー
     * @return void
     */
    public function signOrderDealFileProtPwFlgTrueTest()
    {
        $dataDoc = (object)[
            'document_id' => 1,
            'title' => '取引書類テストタイトル',
            'file_prot_pw_flg' => 0
    ];
        $this->documentRepositoryMock->shouldReceive('getDealIsseuAndNextSignUserInfo->getSignDoc')
        ->once()
        ->andReturn($dataDoc);
        
        $this->expectException(Exception::class);
        $this->getObject()->signOrder(mUserId: 1, mUserCompanyId: 1, mUserTypeId: 0, documentId: 1, docTypeId: 1, categoryId: 1);
    }

    /**
     * @test
     * 社内書類
     *  file_prot_pw_flgがtrueだった場合エラー
     * @return void
     */
    public function signOrderArchiveFileProtPwFlgTrueTest()
    {
        $dataDoc = (object)[
            'document_id' => 1,
            'title' => '登録書類テストタイトル',
            'file_prot_pw_flg' => 0
    ];
        $this->documentRepositoryMock->shouldReceive('getArchiveIsseuAndNextSignUserInfo->getSignDoc')
        ->once()
        ->andReturn($dataDoc);
        
        $this->expectException(Exception::class);
        $this->getObject()->signOrder(mUserId: 1, mUserCompanyId: 1, mUserTypeId: 0, documentId: 1, docTypeId: 1, categoryId: 3);
    }

    /**
     * @test
     * 社内書類
     *  file_prot_pw_flgがtrueだった場合エラー
     * @return void
     */
    public function signOrderInternalFileProtPwFlgTrueTest()
    {
        $dataDoc = (object)[
            'document_id' => 1,
            'title' => '社内書類テストタイトル',
            'file_prot_pw_flg' => 0
        ];
        $this->documentRepositoryMock->shouldReceive('getInternalIsseuAndNextSignUserInfo->getSignDoc')
        ->once()
        ->andReturn($dataDoc);
        
        $this->expectException(Exception::class);
        $this->getObject()->signOrder(mUserId: 1, mUserCompanyId: 1, mUserTypeId: 0, documentId: 1, docTypeId: 1, categoryId: 2);
    }

    /**
     * @test
     * 契約書類
     * 次の署名者がゲスト
     * @return void
     */
    public function signOrderContractNextSignUserGuestTest()
    {
        $dataDoc = (object)[
                'document_id' => 1,
                'title' => '契約書類テストタイトル',
                'file_prot_pw_flg' => 1
        ];

        $dataSign =(object)[
            'user_id' => 2,
            'full_name' => '佐藤　次郎',
            'email' => 'testsato@test.jp',
            'user_type_id' => 0,
            'wf_sort' => 2,
            'category_id' => 0,
            'counter_party_id' => NULL,
            'counter_party_name' => NULL,
        ];

        $dataIssue = (object)[
            'full_name' => '大化　テスト',
            'family_name' => '大化',
            'first_name' => 'テスト',
            'wf_sort' => 0,
            'category_id' => 0,
        ];

        $docEntiry = new DocumentSignOrder($dataDoc, $dataSign, $dataIssue);

        $result111 = $this->documentRepositoryMock->shouldReceive('getContractIsseuAndNextSignUserInfo')
        ->once()
        ->andReturn($docEntiry);

        $result = $this->getObject()->signOrder(mUserId: 1, mUserCompanyId: 1, mUserTypeId: 1, documentId: 1, docTypeId: 1, categoryId: 0);
        
        $this->assertTrue($result);
        
    }

    /**
     * @test
     * 契約書類
     * 次の署名者がホスト
     * @return void
     */
    public function signOrderContractNextSignUserHostTest()
    {
        $dataDoc = (object)[
                'document_id' => 1,
                'title' => '契約書類テストタイトル',
                'file_prot_pw_flg' => 1
        ];

        $dataSign =(object)[
            'user_id' => 2,
            'full_name' => '佐藤　次郎',
            'email' => 'testsato@test.jp',
            'user_type_id' => 0,
            'wf_sort' => 2,
            'category_id' => 0,
            'counter_party_id' => NULL,
            'counter_party_name' => NULL,
        ];

        $dataIssue = (object)[
            'full_name' => '大化　テスト',
            'family_name' => '大化',
            'first_name' => 'テスト',
            'wf_sort' => 0,
            'category_id' => 0,
        ];

        $docEntiry = new DocumentSignOrder($dataDoc, $dataSign, $dataIssue);

        $dataAll = [
            'signDoc' => 
                (object) [
                    'document_id' => 1,
                    'title' => '契約書類テストタイトル',
                    'file_prot_pw_flg' => 1,
                ],
            'nextSignUser' => 
                (object) [
                    'user_id' => 3,
                    'full_name' => '佐藤　次郎',
                    'email' => 'testsato@test.jp',
                    'user_type_id' => 0,
                    'wf_sort' => 2,
                    'category_id' => 0,
                    'counter_party_id' => NULL,
                    'counter_party_name' => NULL,
                ],
            'issueUser' => 
                (object) [
                    'full_name' => '大化　テスト',
                    'family_name' => '大化',
                    'first_name' => 'テスト',
                    'wf_sort' => 0,
                    'category_id' => 0,
                ],
        ];

        $result111 = $this->documentRepositoryMock->shouldReceive('getContractIsseuAndNextSignUserInfo')
        ->once()
        ->andReturn($docEntiry);

        $result = $this->getObject()->signOrder(mUserId: 1, mUserCompanyId: 1, mUserTypeId: 0, documentId: 1, docTypeId: 1, categoryId: 0);
        
        $this->assertTrue($result);
        
    }

    /**
     * @test
     * 取引書類
     * 次の署名者がゲスト
     * @return void
     */
    public function signOrderDealNextSignUserGuestTest()
    {
        $dataDoc = (object)[
                'document_id' => 1,
                'title' => '取引書類テストタイトル',
                'file_prot_pw_flg' => 1
        ];

        $dataSign =(object)[
            'user_id' => 2,
            'full_name' => '佐藤　次郎',
            'email' => 'testsato@test.jp',
            'user_type_id' => 0,
            'wf_sort' => 2,
            'category_id' => 0,
            'counter_party_id' => NULL,
            'counter_party_name' => NULL,
        ];

        $dataIssue = (object)[
            'full_name' => '大化　テスト',
            'family_name' => '大化',
            'first_name' => 'テスト',
            'wf_sort' => 0,
            'category_id' => 0,
        ];

        $docEntiry = new DocumentSignOrder($dataDoc, $dataSign, $dataIssue);

        $result111 = $this->documentRepositoryMock->shouldReceive('getDealIsseuAndNextSignUserInfo')
        ->once()
        ->andReturn($docEntiry);

        $result = $this->getObject()->signOrder(mUserId: 1, mUserCompanyId: 1, mUserTypeId: 1, documentId: 1, docTypeId: 1, categoryId: 1);
        
        $this->assertTrue($result);
        
    }

    /**
     * @test
     * 取引書類
     * 次の署名者がゲスト
     * @return void
     */
    public function signOrderDealNextSignUserHostTest()
    {
        $dataDoc = (object)[
                'document_id' => 1,
                'title' => '取引書類テストタイトル',
                'file_prot_pw_flg' => 1
        ];

        $dataSign =(object)[
            'user_id' => 2,
            'full_name' => '佐藤　次郎',
            'email' => 'testsato@test.jp',
            'user_type_id' => 0,
            'wf_sort' => 2,
            'category_id' => 0,
            'counter_party_id' => NULL,
            'counter_party_name' => NULL,
        ];

        $dataIssue = (object)[
            'full_name' => '大化　テスト',
            'family_name' => '大化',
            'first_name' => 'テスト',
            'wf_sort' => 0,
            'category_id' => 0,
        ];

        $docEntiry = new DocumentSignOrder($dataDoc, $dataSign, $dataIssue);

        $result111 = $this->documentRepositoryMock->shouldReceive('getDealIsseuAndNextSignUserInfo')
        ->once()
        ->andReturn($docEntiry);

        $result = $this->getObject()->signOrder(mUserId: 1, mUserCompanyId: 1, mUserTypeId: 0, documentId: 1, docTypeId: 1, categoryId: 1);
        
        $this->assertTrue($result);
        
    }

    /**
     * @test
     * 社内書類
     * 次の署名者がホスト
     * @return void
     */
    public function signOrderInternalNextSignUserGuestTest()
    {
        $dataDoc = (object)[
                'document_id' => 1,
                'title' => '社内書類テストタイトル',
                'file_prot_pw_flg' => 1
        ];

        $dataSign =
            (object) [
                '0' =>
                (object) array(
                    'full_name' => '加藤　三郎',
                    'family_name' => '加藤',
                    'first_name' => '三郎',
                    'email' => 'testkatou@test.jp',
                    'wf_sort' => 1,
                    'category_id' => 2,
                ),
                '1' =>
                (object) array(
                    'full_name' => '木村　史郎',
                    'family_name' => '木村',
                    'first_name' => '史郎',
                    'email' => 'testkimura@test.jp',
                    'wf_sort' => 2,
                    'category_id' => 2,
                ),
            ];


        $dataIssue = (object)[
            'full_name' => '大化　テスト',
            'family_name' => '大化',
            'first_name' => 'テスト',
            'wf_sort' => 0,
            'category_id' => 0,
        ];

        $docEntiry = new DocumentSignOrder($dataDoc, $dataSign, $dataIssue);

        $result111 = $this->documentRepositoryMock->shouldReceive('getInternalSignUserListInfo')
        ->once()
        ->andReturn($docEntiry);

        $result = $this->getObject()->signOrder(mUserId: 1, mUserCompanyId: 1, mUserTypeId: 0, documentId: 1, docTypeId: 1, categoryId: 2);
        
        $this->assertTrue($result);
        
    }

    /**
     * @test
     * 登録書類
     * 次の署名者がホスト
     * @return void
     */
    public function signOrderArchiveNextSignUserGuestTest()
    {
        $dataDoc = (object)[
                'document_id' => 1,
                'title' => '登録書類テストタイトル',
                'file_prot_pw_flg' => 1
        ];

        $dataSign =
            (object) [
                'user_id' => 2,
                'full_name' => '佐藤　次郎',
                'email' => 'testsato@test.jp',
                'user_type_id' => 0,
                'wf_sort' => 2,
                'category_id' => 0,
                'counter_party_id' => NULL,
                'counter_party_name' => NULL,
            ];


        $dataIssue = (object)[
            'full_name' => '大化　テスト',
            'family_name' => '大化',
            'first_name' => 'テスト',
            'wf_sort' => 0,
            'category_id' => 0,
        ];

        $docEntiry = new DocumentSignOrder($dataDoc, $dataSign, $dataIssue);

        $result111 = $this->documentRepositoryMock->shouldReceive('getArchiveIsseuAndNextSignUserInfo')
        ->once()
        ->andReturn($docEntiry);

        $result = $this->getObject()->signOrder(mUserId: 1, mUserCompanyId: 1, mUserTypeId: 0, documentId: 1, docTypeId: 1, categoryId: 3);
        
        $this->assertTrue($result);
        
    }

    private function getObject()
    {
        return new DocumentSignOrderService($this->queueConst, $this->queue, $this->userConst, $this->docConst, $this->documentRepositoryMock);
    }   
}

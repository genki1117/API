<?php

namespace Tests\Unit\Repository\Document;

use Exception;
use App\Domain\Repositories\Document\DocumentSignOrderRepository;
use App\Accessers\DB\Log\System\LogDocOperation;
use App\Accessers\DB\Document\DocumentArchive;
use App\Accessers\DB\Document\DocumentContract;
use App\Accessers\DB\Document\DocumentDeal;
use App\Accessers\DB\Document\DocumentInternal;
use App\Accessers\DB\Document\DocumentWorkFlow;
use App\Accessers\DB\Master\MUser;
use App\Accessers\DB\TempToken;
use App\Domain\Consts\DocumentConst;
use App\Domain\Entities\Common\TempToken as TempTokenEn;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

class DocumentSignOrderRepositoryTest extends TestCase
{

    private MockInterface $mUserMock;
    private MockInterface $tempTokenMock;
    private MockInterface $docArchiveMock;
    private MockInterface $docContractMock;
    private MockInterface $docDealMock;
    private MockInterface $docInternalMock;
    private MockInterface $documentWorkFlowMock;

    public function setUp(): void
    {
        parent::setUp();
        $this->mUserMock            = \Mockery::mock(MUser::class);
        $this->tempTokenMock        = \Mockery::mock(TempToken::class);
        $this->docArchiveMock    = \Mockery::mock(DocumentArchive::class);
        $this->docContractMock      = \Mockery::mock(DocumentContract::class);
        $this->docDealMock          = \Mockery::mock(DocumentDeal::class);
        $this->docInternalMock      = \Mockery::mock(DocumentInternal::class);
        $this->documentWorkFlowMock = \Mockery::mock(DocumentWorkFlow::class);
    }

    /**
     * @test
     * ワークフローの取得のテスト
     * @return void
     */
    public function getLoginUserWorkflow_test()
    {
        $workflData = (object)[
            'wf_sort' => 1,
            'full_nama' => 'test test'
        ];
        
        $result = $this->mUserMock->shouldReceive('getLoginUserWorkflow')
        ->once()
        ->andReturn($workflData);

        $result = $this->getObject()->getLoginUserWorkflow(mUserId: 1, mUserCompanyId: 1);

        $this->assertEquals($result->wf_sort, 1);
        $this->assertEquals($result->full_nama, 'test test');
    }

    /**
     * @test
     * 契約書類正常テスト
     * @return void
     */
    public function getContractIsseuAndNextSignUserInfo_test_1 ()
    {
        $this->docContractMock->shouldReceive('getSignDocument')
        ->once()
        ->andReturn($this->getSignDocContract());

        $this->documentWorkFlowMock->shouldReceive('getContractNextSignUser')
        ->once()
        ->andReturn($this->getSignUserContract());

        $this->documentWorkFlowMock->shouldReceive('getContractIsseuUser')
        ->once()
        ->andReturn($this->getIssueUserContract());

        $result = $this->getObject()->getContractIsseuAndNextSignUserInfo(documentId: 1, categoryId: 0, mUserId: 1);
        $this->assertEquals($result->getSignDoc()->document_id, 1);
        $this->assertEquals($result->getSignDoc()->title, '契約書類テストタイトル');
        $this->assertEquals($result->getSignDoc()->file_prot_pw_flg, 1);
        $this->assertEquals($result->getNextSignUser()->user_id, 3);
        $this->assertEquals($result->getNextSignUser()->full_name, '佐藤　次郎');
        $this->assertEquals($result->getNextSignUser()->email, 'testsato@test.jp');
        $this->assertEquals($result->getNextSignUser()->user_type_id, 0);
        $this->assertEquals($result->getNextSignUser()->wf_sort, 2);
        $this->assertEquals($result->getNextSignUser()->category_id, 0);
        $this->assertEquals($result->getNextSignUser()->counter_party_id, null);
        $this->assertEquals($result->getNextSignUser()->counter_party_name, null);
        $this->assertEquals($result->getIssueUser()->full_name, '大化　テスト');
        $this->assertEquals($result->getIssueUser()->family_name, '大化');
        $this->assertEquals($result->getIssueUser()->first_name, 'テスト');
        $this->assertEquals($result->getIssueUser()->wf_sort, 0);
        $this->assertEquals($result->getIssueUser()->category_id, 0);
    }

    /**
     * @test
     * 契約書類異常テスト
     * 書類取得不可
     * @return void
     */
    public function getContractIsseuAndNextSignUserInfo_test_2 ()
    {
        $this->docContractMock->shouldReceive('getSignDocument')
        ->once()
        ->andReturn(null);

        $this->documentWorkFlowMock->shouldReceive('getContractNextSignUser')
        ->once()
        ->andReturn($this->getSignUserContract());

        $this->documentWorkFlowMock->shouldReceive('getContractIsseuUser')
        ->once()
        ->andReturn($this->getIssueUserContract());

        $this->expectException(Exception::class);
        $result = $this->getObject()->getContractIsseuAndNextSignUserInfo(documentId: 1, categoryId: 0, mUserId: 1);
    }
    /**
     * @test
     * 契約書類異常テスト
     * 署名者取得不可
     * @return void
     */
    public function getContractIsseuAndNextSignUserInfo_test_3 ()
    {
        $this->docContractMock->shouldReceive('getSignDocument')
        ->once()
        ->andReturn($this->getSignDocContract());

        $this->documentWorkFlowMock->shouldReceive('getContractNextSignUser')
        ->once()
        ->andReturn(null);

        $this->documentWorkFlowMock->shouldReceive('getContractIsseuUser')
        ->once()
        ->andReturn($this->getIssueUserContract());

        $this->expectException(Exception::class);
        $result = $this->getObject()->getContractIsseuAndNextSignUserInfo(documentId: 1, categoryId: 0, mUserId: 1);
    }
    /**
     * @test
     * 契約書類異常テスト
     * 起票者取得不可
     * @return void
     */
    public function getContractIsseuAndNextSignUserInfo_test_4 ()
    {
        $this->docContractMock->shouldReceive('getSignDocument')
        ->once()
        ->andReturn($this->getSignDocContract());

        $this->documentWorkFlowMock->shouldReceive('getContractNextSignUser')
        ->once()
        ->andReturn($this->getSignUserContract());

        $this->documentWorkFlowMock->shouldReceive('getContractIsseuUser')
        ->once()
        ->andReturn(null);

        $this->expectException(Exception::class);
        $result = $this->getObject()->getContractIsseuAndNextSignUserInfo(documentId: 1, categoryId: 0, mUserId: 1);
    }

     /**
     * @test
     * 取引書類正常テスト
     * @return void
     */
    public function getDealIsseuAndNextSignUserInfo_test_1 ()
    {
        $this->docDealMock->shouldReceive('getSignDocument')
        ->once()
        ->andReturn($this->getSignDocDeal());

        $this->documentWorkFlowMock->shouldReceive('getDealNextSignUser')
        ->once()
        ->andReturn($this->getSignUserDeal());

        $this->documentWorkFlowMock->shouldReceive('getDealIsseuUser')
        ->once()
        ->andReturn($this->getIssueUserDeal());

        $result = $this->getObject()->getDealIsseuAndNextSignUserInfo(documentId: 1, categoryId: 1, mUserId: 1);
        $this->assertEquals($result->getSignDoc()->document_id, 1);
        $this->assertEquals($result->getSignDoc()->title, '取引書類テストタイトル');
        $this->assertEquals($result->getSignDoc()->file_prot_pw_flg, 1);
        $this->assertEquals($result->getNextSignUser()->user_id, 3);
        $this->assertEquals($result->getNextSignUser()->full_name, '佐藤　次郎');
        $this->assertEquals($result->getNextSignUser()->email, 'testsato@test.jp');
        $this->assertEquals($result->getNextSignUser()->user_type_id, 0);
        $this->assertEquals($result->getNextSignUser()->wf_sort, 2);
        $this->assertEquals($result->getNextSignUser()->category_id, 1);
        $this->assertEquals($result->getNextSignUser()->counter_party_id, null);
        $this->assertEquals($result->getNextSignUser()->counter_party_name, null);
        $this->assertEquals($result->getIssueUser()->full_name, '大化　テスト');
        $this->assertEquals($result->getIssueUser()->family_name, '大化');
        $this->assertEquals($result->getIssueUser()->first_name, 'テスト');
        $this->assertEquals($result->getIssueUser()->wf_sort, 0);
        $this->assertEquals($result->getIssueUser()->category_id, 1);
    }

    /**
     * @test
     * 取引書類異常テスト
     * 書類取得不可
     * @return void
     */
    public function getDealIsseuAndNextSignUserInfo_test_2 ()
    {
        $this->docDealMock->shouldReceive('getSignDocument')
        ->once()
        ->andReturn(null);

        $this->documentWorkFlowMock->shouldReceive('getDealNextSignUser')
        ->once()
        ->andReturn($this->getSignUserDeal());

        $this->documentWorkFlowMock->shouldReceive('getDealIsseuUser')
        ->once()
        ->andReturn($this->getIssueUserDeal());

        $this->expectException(Exception::class);
        $this->getObject()->getDealIsseuAndNextSignUserInfo(documentId: 1, categoryId: 1, mUserId: 1);
        
    }
    /**
     * @test
     * 取引書類異常テスト
     * 署名者取得不可
     * @return void
     */
    public function getDealIsseuAndNextSignUserInfo_test_3 ()
    {
        $this->docDealMock->shouldReceive('getSignDocument')
        ->once()
        ->andReturn($this->getSignDocDeal());

        $this->documentWorkFlowMock->shouldReceive('getDealNextSignUser')
        ->once()
        ->andReturn(null);

        $this->documentWorkFlowMock->shouldReceive('getDealIsseuUser')
        ->once()
        ->andReturn($this->getIssueUserDeal());

        $this->expectException(Exception::class);
        $this->getObject()->getDealIsseuAndNextSignUserInfo(documentId: 1, categoryId: 1, mUserId: 1);
        
    }
    /**
     * @test
     * 取引書類異常テスト
     * 起票者取得不可
     * @return void
     */
    public function getDealIsseuAndNextSignUserInfo_test_4 ()
    {
        $this->docDealMock->shouldReceive('getSignDocument')
        ->once()
        ->andReturn($this->getSignDocDeal());

        $this->documentWorkFlowMock->shouldReceive('getDealNextSignUser')
        ->once()
        ->andReturn($this->getSignUserDeal());

        $this->documentWorkFlowMock->shouldReceive('getDealIsseuUser')
        ->once()
        ->andReturn(null);

        $this->expectException(Exception::class);
        $this->getObject()->getDealIsseuAndNextSignUserInfo(documentId: 1, categoryId: 1, mUserId: 1);
        
    }

    /**
     * @test
     * 社内書類正常テスト
     * @return void
     */
    public function getInternalIsseuAndNextSignUserInfo_test_1 ()
    {
        $this->docInternalMock->shouldReceive('getSignDocument')
        ->once()
        ->andReturn($this->getSignDocInternal());

        $this->documentWorkFlowMock->shouldReceive('getInternalSignUserList')
        ->once()
        ->andReturn($this->getSignUserInternal());

        $this->documentWorkFlowMock->shouldReceive('getInternalIsseuUser')
        ->once()
        ->andReturn($this->getIssueUserInternal());
        
        $result = $this->getObject()->getInternalSignUserListInfo(documentId: 1, categoryId: 2, mUserCompanyId: 0);

        $this->assertEquals($result->getSignDoc()->document_id, 1);
        $this->assertEquals($result->getSignDoc()->title, '社内書類テストタイトル');
        $this->assertEquals($result->getSignDoc()->file_prot_pw_flg, 1);
        $this->assertEquals($result->getNextSignUser()->signUser1->full_name, '加藤　三郎');
        $this->assertEquals($result->getNextSignUser()->signUser2->full_name, '木村　史郎');
        $this->assertEquals($result->getNextSignUser()->signUser1->family_name, '加藤');
        $this->assertEquals($result->getNextSignUser()->signUser2->family_name, '木村');
        $this->assertEquals($result->getNextSignUser()->signUser1->first_name, '三郎');
        $this->assertEquals($result->getNextSignUser()->signUser2->first_name, '史郎');
        $this->assertEquals($result->getNextSignUser()->signUser1->email, 'testkatou@test.jp');
        $this->assertEquals($result->getNextSignUser()->signUser2->email, 'testkimura@test.jp');
        $this->assertEquals($result->getNextSignUser()->signUser1->wf_sort, 1);
        $this->assertEquals($result->getNextSignUser()->signUser2->wf_sort, 2);
        $this->assertEquals($result->getNextSignUser()->signUser1->category_id, 2);
        $this->assertEquals($result->getNextSignUser()->signUser2->category_id, 2);
        $this->assertEquals($result->getIssueUser()->full_name, '大化　テスト');
        $this->assertEquals($result->getIssueUser()->family_name, '大化');
        $this->assertEquals($result->getIssueUser()->first_name, 'テスト');
        $this->assertEquals($result->getIssueUser()->wf_sort, 0);
        $this->assertEquals($result->getIssueUser()->category_id, 2);
    }
    
     /**
     * @test
     * 社内書類異常テスト
     * 書類取得不可
     * @return void
     */
    public function getInternalIsseuAndNextSignUserInfo_test_2 ()
    {
        $this->docInternalMock->shouldReceive('getSignDocument')
        ->once()
        ->andReturn(null);

        $this->documentWorkFlowMock->shouldReceive('getInternalSignUserList')
        ->once()
        ->andReturn($this->getSignUserArchive());

        $this->documentWorkFlowMock->shouldReceive('getInternalIsseuUser')
        ->once()
        ->andReturn($this->getIssueUserInternal());
        
        $this->expectException(Exception::class);
        $result = $this->getObject()->getInternalSignUserListInfo(documentId: 1, categoryId: 2, mUserCompanyId: 0);
    }

    /**
     * @test
     * 社内書類異常テスト
     * 署名者取得不可
     * @return void
     */
    public function getInternalIsseuAndNextSignUserInfo_test_3 ()
    {
        $this->docInternalMock->shouldReceive('getSignDocument')
        ->once()
        ->andReturn($this->getSignDocInternal());

        $this->documentWorkFlowMock->shouldReceive('getInternalSignUserList')
        ->once()
        ->andReturn(null);

        $this->documentWorkFlowMock->shouldReceive('getInternalIsseuUser')
        ->once()
        ->andReturn($this->getIssueUserInternal());
        
        $this->expectException(Exception::class);
        $result = $this->getObject()->getInternalSignUserListInfo(documentId: 1, categoryId: 2, mUserCompanyId: 0);
    }
    /**
     * @test
     * 社内書類異常テスト
     * 起票者取得不可
     * @return void
     */
    public function getInternalIsseuAndNextSignUserInfo_test_4 ()
    {
        $this->docInternalMock->shouldReceive('getSignDocument')
        ->once()
        ->andReturn($this->getSignDocInternal());

        $this->documentWorkFlowMock->shouldReceive('getInternalSignUserList')
        ->once()
        ->andReturn($this->getSignUserInternal());

        $this->documentWorkFlowMock->shouldReceive('getInternalIsseuUser')
        ->once()
        ->andReturn(null);
        
        $this->expectException(Exception::class);
        $result = $this->getObject()->getInternalSignUserListInfo(documentId: 1, categoryId: 2, mUserCompanyId: 0);
    }

    /**
     * @test
     * 登録書類正常テスト
     * @return void
     */
    public function getArchiveIsseuAndNextSignUserInfo_test_1 ()
    {
        $this->docArchiveMock->shouldReceive('getSignDocument')
        ->once()
        ->andReturn($this->getSignDogArchive());

        $this->documentWorkFlowMock->shouldReceive('getArchiveNextSignUser')
        ->once()
        ->andReturn($this->getSignUserArchive());

        $this->documentWorkFlowMock->shouldReceive('getArchiveIsseuUser')
        ->once()
        ->andReturn($this->getIssueUserArchive());
        

        $result = $this->getObject()->getArchiveIsseuAndNextSignUserInfo(documentId: 1, categoryId: 3, mUserCompanyId: 0);

        $this->assertEquals($result->getSignDoc()->document_id, 1);
        $this->assertEquals($result->getSignDoc()->title, '登録書類テストタイトル');
        $this->assertEquals($result->getSignDoc()->file_prot_pw_flg, 1);
        $this->assertEquals($result->getNextSignUser()->full_name, '加藤　三郎');
        $this->assertEquals($result->getNextSignUser()->family_name, '加藤');
        $this->assertEquals($result->getNextSignUser()->first_name, '三郎');
        $this->assertEquals($result->getNextSignUser()->email, 'testkatou@test.jp');
        $this->assertEquals($result->getNextSignUser()->wf_sort, 1);
        $this->assertEquals($result->getNextSignUser()->category_id, 3);
        $this->assertEquals($result->getIssueUser()->full_name, '大化　テスト');
        $this->assertEquals($result->getIssueUser()->family_name, '大化');
        $this->assertEquals($result->getIssueUser()->first_name, 'テスト');
        $this->assertEquals($result->getIssueUser()->wf_sort, 0);
        $this->assertEquals($result->getIssueUser()->category_id, 3);
    }

    /**
     * @test
     * 登録書類異常テスト
     * 書類取得不可
     * @return void
     */
    public function getArchiveIsseuAndNextSignUserInfo_test_2 ()
    {
        $this->docArchiveMock->shouldReceive('getSignDocument')
        ->once()
        ->andReturn(null);

        $this->documentWorkFlowMock->shouldReceive('getArchiveNextSignUser')
        ->once()
        ->andReturn($this->getSignUserArchive());

        $this->documentWorkFlowMock->shouldReceive('getArchiveIsseuUser')
        ->once()
        ->andReturn($this->getIssueUserArchive());
        
        $this->expectException(Exception::class);
        $result = $this->getObject()->getArchiveIsseuAndNextSignUserInfo(documentId: 1, categoryId: 3, mUserCompanyId: 0);
    }

    /**
     * @test
     * 登録書類異常テスト
     * 署名者取得不可
     * @return void
     */
    public function getArchiveIsseuAndNextSignUserInfo_test_3 ()
    {
        $this->docArchiveMock->shouldReceive('getSignDocument')
        ->once()
        ->andReturn($this->getSignDogArchive());

        $this->documentWorkFlowMock->shouldReceive('getArchiveNextSignUser')
        ->once()
        ->andReturn(null);

        $this->documentWorkFlowMock->shouldReceive('getArchiveIsseuUser')
        ->once()
        ->andReturn($this->getIssueUserArchive());
        
        $this->expectException(Exception::class);
        $result = $this->getObject()->getArchiveIsseuAndNextSignUserInfo(documentId: 1, categoryId: 3, mUserCompanyId: 0);
    }

    /**
     * @test
     * 登録書類異常テスト
     * 起票者取得不可
     * @return void
     */
    public function getArchiveIsseuAndNextSignUserInfo_test_4 ()
    {
        $this->docArchiveMock->shouldReceive('getSignDocument')
        ->once()
        ->andReturn($this->getSignDogArchive());

        $this->documentWorkFlowMock->shouldReceive('getArchiveNextSignUser')
        ->once()
        ->andReturn($this->getSignUserArchive());

        $this->documentWorkFlowMock->shouldReceive('getArchiveIsseuUser')
        ->once()
        ->andReturn(null);
        
        $this->expectException(Exception::class);
        $result = $this->getObject()->getArchiveIsseuAndNextSignUserInfo(documentId: 1, categoryId: 3, mUserCompanyId: 0);
    }

    public function getObject()
    {
        return new DocumentSignOrderRepository(
            $this->tempTokenMock,
            $this->mUserMock,
            $this->docContractMock,
            $this->docDealMock,
            $this->docInternalMock,
            $this->docArchiveMock,
            $this->documentWorkFlowMock,
        );
    }

    public function getSignDocContract()
    {
        $signDoc = (object)[
            'document_id' => 1,
            'title' => '契約書類テストタイトル',
            'file_prot_pw_flg' => 1,
        ];
        return $signDoc;
    }

    public function getSignUserContract()
    {
        $nextSignUser =(object)[
            'user_id' => 3,
            'full_name' => '佐藤　次郎',
            'email' => 'testsato@test.jp',
            'user_type_id' => 0,
            'wf_sort' => 2,
            'category_id' => 0,
            'counter_party_id' => NULL,
            'counter_party_name' => NULL,
        ];
        return $nextSignUser;
    }

    public function getIssueUserContract()
    {
        $issueUser = (object)[
            'full_name' => '大化　テスト',
            'family_name' => '大化',
            'first_name' => 'テスト',
            'wf_sort' => 0,
            'category_id' => 0,
        ];
        return $issueUser;
    }

    public function getSignDocDeal()
    {
        $signDoc = (object)[
            'document_id' => 1,
            'title' => '取引書類テストタイトル',
            'file_prot_pw_flg' => 1,
        ];
        return $signDoc;
    }

    public function getSignUserDeal()
    {
        $nextSignUser =(object)[
            'user_id' => 3,
            'full_name' => '佐藤　次郎',
            'email' => 'testsato@test.jp',
            'user_type_id' => 0,
            'wf_sort' => 2,
            'category_id' => 1,
            'counter_party_id' => NULL,
            'counter_party_name' => NULL,
        ];
        return $nextSignUser;
    }

    public function getIssueUserDeal()
    {
        $issueUser = (object)[
            'full_name' => '大化　テスト',
            'family_name' => '大化',
            'first_name' => 'テスト',
            'wf_sort' => 0,
            'category_id' => 1,
        ];
        return $issueUser;
    }

    public function getSignDocInternal()
    {
        $signDoc = (object)[
            'document_id' => 1,
            'title' => '社内書類テストタイトル',
            'file_prot_pw_flg' => 1,
        ];
        return $signDoc;
    }

    public function getSignUserInternal()
    {
        $nextSignUser = [
            'signUser1' =>
                (object) [
                'full_name' => '加藤　三郎',
                'family_name' => '加藤',
                'first_name' => '三郎',
                'email' => 'testkatou@test.jp',
                'wf_sort' => 1,
                'category_id' => 2,
                ],
            'signUser2' =>
                (object)[
                'full_name' => '木村　史郎',
                'family_name' => '木村',
                'first_name' => '史郎',
                'email' => 'testkimura@test.jp',
                'wf_sort' => 2,
                'category_id' => 2,
                ],
        ];
        return $nextSignUser;
    }

    public function getIssueUserInternal()
    {
        $issueUser = (object)[
            'full_name' => '大化　テスト',
            'family_name' => '大化',
            'first_name' => 'テスト',
            'wf_sort' => 0,
            'category_id' => 2,
        ];
        return $issueUser;
    }

    public function getSignDogArchive()
    {
        $signDoc = (object)[
            'document_id' => 1,
            'title' => '登録書類テストタイトル',
            'file_prot_pw_flg' => 1,
        ];
        return $signDoc;
    }

    public function getSignUserArchive()
    {
        $nextSignUser = (object) [
            'full_name' => '加藤　三郎',
            'family_name' => '加藤',
            'first_name' => '三郎',
            'email' => 'testkatou@test.jp',
            'wf_sort' => 1,
            'category_id' => 3,
        ];
        return $nextSignUser;
    }

    public function getIssueUserArchive()
    {
        $issueUser = (object)[
            'full_name' => '大化　テスト',
            'family_name' => '大化',
            'first_name' => 'テスト',
            'wf_sort' => 0,
            'category_id' => 3,
        ];
        return $issueUser;
    }

}

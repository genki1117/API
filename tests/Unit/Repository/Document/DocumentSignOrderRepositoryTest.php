<?php

namespace Tests\Unit\Repository\Document;

use App\Domain\Repositories\Document\DocumentSaveOrderRepository;
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
    private MockInterface $docdocArchiveMock;
    private MockInterface $docContractMock;
    private MockInterface $docDealMock;
    private MockInterface $docInternalMock;
    private MockInterface $documentWorkFlowMock;

    public function setUp(): void
    {
        parent::setUp();
        $this->mUserMock            = \Mockery::mock(MUser::class);
        $this->tempTokenMock        = \Mockery::mock(TempToken::class);
        $this->docdocArchiveMock    = \Mockery::mock(DocumentArchive::class);
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
        $signDoc = (object)[
            'document_id' => 1,
            'title' => '契約書類テストタイトル',
            'file_prot_pw_flg' => 1,
        ];

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

        $issueUser = (object)[
            'full_name' => '大化　テスト',
            'family_name' => '大化',
            'first_name' => 'テスト',
            'wf_sort' => 0,
            'category_id' => 0,
        ];


        $this->docContractMock->shouldReceive('getSignDocument')
        ->once()
        ->andReturn($signDoc);

        $this->documentWorkFlowMock->shouldReceive('getContractNextSignUser')
        ->once()
        ->andReturn($nextSignUser);

        $this->documentWorkFlowMock->shouldReceive('getContractIsseuUser')
        ->once()
        ->andReturn($issueUser);

        $result = $this->getObject()->getContractIsseuAndNextSignUserInfo(documentId: 1, categoryId: 0, loginUserWorkFlowSort: 0);
        var_dump($result);
     
       $this->assertEquals($result->signDoc->title, '契約書類テストタイトル');
    }



    public function getObject()
    {
        return new DocumentSaveOrderRepository(
            $this->tempTokenMock,
            $this->mUserMock,
            $this->docContractMock,
            $this->docDealMock,
            $this->docInternalMock,
            $this->docdocArchiveMock,
            $this->documentWorkFlowMock,
        );
    }

}

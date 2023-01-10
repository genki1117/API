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

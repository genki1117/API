<?php

namespace Tests\Unit\Repository\Document;

use App\Accessers\DB\Document\DocumentArchive;
use App\Accessers\DB\Document\DocumentContract;
use App\Accessers\DB\Document\DocumentDeal;
use App\Accessers\DB\Document\DocumentInternal;
use App\Accessers\DB\Document\DocumentPermissionArchive;
use App\Accessers\DB\Document\DocumentPermissionContract;
use App\Accessers\DB\Document\DocumentPermissionInternal;
use App\Accessers\DB\Document\DocumentPermissionTransaction;
use App\Accessers\DB\Document\DocumentWorkFlow;
use App\Accessers\DB\Log\System\LogDocAccess;
use App\Accessers\DB\Log\System\LogDocOperation;
use App\Domain\Entities\Document\Document;
use App\Domain\Repositories\Document\DocumentDetailRepository;
use PHPUnit\Framework\TestCase;

class DocumentDetailRepositoryTest extends TestCase
{
    private $documentDealMock;
    private $documentArchiveMock;
    private $documentContractMock;
    private $documentInternalMock;
    private $documentPermissionArchiveMock;
    private $documentPermissionInternalMock;
    private $documentPermissionContractMock;
    private $documentPermissionTransactionMock;
    private $logDocAccessMock;
    private $logDocOperationMock;
    private $documentWorkFlowMock;

    public function setUp(): void
    {
        $this->documentDealMock = \Mockery::mock(DocumentDeal::class);
        $this->documentArchiveMock = \Mockery::mock(DocumentArchive::class);
        $this->documentContractMock = \Mockery::mock(DocumentContract::class);
        $this->documentInternalMock = \Mockery::mock(DocumentInternal::class);
        $this->documentPermissionArchiveMock = \Mockery::mock(DocumentPermissionArchive::class);
        $this->documentPermissionInternalMock = \Mockery::mock(DocumentPermissionInternal::class);
        $this->documentPermissionContractMock = \Mockery::mock(DocumentPermissionContract::class);
        $this->documentPermissionTransactionMock = \Mockery::mock(DocumentPermissionTransaction::class);
        $this->logDocAccessMock = \Mockery::mock(LogDocAccess::class);
        $this->logDocOperationMock = \Mockery::mock(LogDocOperation::class);
        $this->documentWorkFlowMock = \Mockery::mock(DocumentWorkFlow::class);
    }

    /**
     * category_id = 1
     *  DBからの取得値が空の場合
     */
    public function test_getDetail_1()
    {
        $this->documentContractMock->shouldReceive('getList')
            ->once()
            ->andReturnNull();
        $this->documentPermissionContractMock->shouldReceive('getList')
            ->once()
            ->andReturnNull();
        $this->documentDealMock->shouldReceive('getList')->never();
        $this->documentPermissionTransactionMock->shouldReceive('getList')->never();
        $this->documentInternalMock->shouldReceive('getList')->never();
        $this->documentPermissionInternalMock->shouldReceive('getList')->never();
        $this->documentArchiveMock->shouldReceive('getList')->never();
        $this->documentPermissionArchiveMock->shouldReceive('getList')->never();

        $this->assertEquals(
            $this->getObject()->getDetail(categoryId: 1, documentId: 1, companyId: 1, userId: 1),
            new Document()
        );
    }

    private function getObject()
    {
        return new DocumentDetailRepository(
            $this->documentDealMock,
            $this->documentArchiveMock,
            $this->documentContractMock,
            $this->documentInternalMock,
            $this->documentPermissionArchiveMock,
            $this->documentPermissionInternalMock,
            $this->documentPermissionContractMock,
            $this->documentPermissionTransactionMock,
            $this->logDocAccessMock,
            $this->logDocOperationMock,
            $this->documentWorkFlowMock,
        );
    }
}

<?php

namespace Tests\Unit\Service\Document;

use App\Domain\Entities\Document\Document;
use App\Domain\Repositories\Interface\Document\DocumentDetailRepositoryInterface;
use App\Domain\Services\Document\DocumentDetailService;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

class DocumentDetailServiceTest extends TestCase
{
    private MockInterface|LegacyMockInterface $documentRepositoryMock;

    public function setUp(): void
    {
        parent::setUp();
        $this->documentRepositoryMock = \Mockery::mock(DocumentDetailRepositoryInterface::class);
    }

    /**
     * getDetail関数正常系テスト
     */
    public function test_getDetail()
    {
        $categoryId = 1;
        $documentId = 1;
        $companyId = 1;
        $userId = 1;
        $documentEntity = new Document([
            'doc_no' => 1
        ]);
        $this->documentRepositoryMock->shouldReceive('getDetail')
            ->once()
            ->with($categoryId, $documentId, $companyId, $userId)
            ->andReturn($documentEntity);
        $this->documentRepositoryMock->shouldReceive('getAccessLog')
            ->once()
            ->with($categoryId, $documentId, $companyId)
            ->andReturn(['getAccessLog']);
        $this->documentRepositoryMock->shouldReceive('getOperationLog')
            ->once()
            ->with($categoryId, $documentId, $companyId)
            ->andReturn(['getOperationLog']);
        $this->documentRepositoryMock->shouldReceive('getSelectSignGuestUsers')
            ->once()
            ->with($categoryId, $documentId, $companyId)
            ->andReturn(['getSelectSignGuestUsers']);
        $this->documentRepositoryMock->shouldReceive('getSelectSignUser')
            ->once()
            ->with($categoryId, $documentId, $companyId)
            ->andReturn(['getSelectSignUser']);
        $this->documentRepositoryMock->shouldReceive('getSelectSignUser')
            ->once()
            ->with($categoryId, $documentId, $companyId)
            ->andReturn(['getSelectSignUser']);

        $this->assertEquals(
            $this->getObject()->getDetail(categoryId: 1, documentId: 1, companyId: 1, userId: 1),
            [
                'documentDetail' => $documentEntity,
                'accessLog' => ['accessLog'],
                'operationLog' => ['operationLog'],
                'selectSignGuestUsers' => ['selectSignGuestUsers'],
                'selectViewUsers' => ['selectViewUsers'],
                'selectSignUsers'  => ['selectSignUsers']
            ]
        );

    }

    private function getObject()
    {
        return new DocumentDetailService($this->documentRepositoryMock);
    }

}

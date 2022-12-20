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
    public function test_getDetail_1()
    {
        $documentEntity = new Document([
            'docNo' => 1
        ]);
        $this->documentRepositoryMock->shouldReceive('getDetail')
            ->once()
            ->andReturn($documentEntity);
        $this->documentRepositoryMock->shouldReceive('getAccessLog')
            ->once()
            ->andReturn(['getAccessLog']);
        $this->documentRepositoryMock->shouldReceive('getOperationLog')
            ->once()
            ->andReturn(['getOperationLog']);
        $this->documentRepositoryMock->shouldReceive('getSelectSignGuestUsers')
            ->once()
            ->andReturn(['getSelectSignGuestUsers']);
        $this->documentRepositoryMock->shouldReceive('getSelectSignUser')
            ->once()
            ->andReturn(['getSelectSignUser']);
        $this->documentRepositoryMock->shouldReceive('getSelectViewUser')
            ->once()
            ->andReturn(['getSelectViewUser']);

        $this->assertEquals(
            $this->getObject()->getDetail(categoryId: 1, documentId: 1, companyId: 1, userId: 1),
            [
                'documentDetail' => $documentEntity,
                'accessLog' => ['getAccessLog'],
                'operationLog' => ['getOperationLog'],
                'selectSignGuestUsers' => ['getSelectSignGuestUsers'],
                'selectViewUsers' => ['getSelectViewUser'],
                'selectSignUsers'  => ['getSelectSignUser']
            ]
        );
    }

    /**
     * getDetail関数正常系テスト
     */
    public function test_getDetail_2()
    {
        $documentEntity = new Document();
        $this->documentRepositoryMock->shouldReceive('getDetail')
            ->once()
            ->andReturn($documentEntity);
        $this->documentRepositoryMock->shouldReceive('getAccessLog')
            ->once()
            ->andReturn([]);
        $this->documentRepositoryMock->shouldReceive('getOperationLog')
            ->once()
            ->andReturn([]);
        $this->documentRepositoryMock->shouldReceive('getSelectSignGuestUsers')
            ->once()
            ->andReturn([]);
        $this->documentRepositoryMock->shouldReceive('getSelectSignUser')
            ->once()
            ->andReturn([]);
        $this->documentRepositoryMock->shouldReceive('getSelectViewUser')
            ->once()
            ->andReturn([]);

        $this->assertEquals(
            $this->getObject()->getDetail(categoryId: 1, documentId: 1, companyId: 1, userId: 1),
            []
        );
    }

    private function getObject()
    {
        return new DocumentDetailService($this->documentRepositoryMock);
    }

}

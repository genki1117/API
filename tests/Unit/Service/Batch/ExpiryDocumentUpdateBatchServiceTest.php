<?php

namespace Tests\Unit\Service\Batch;

use Illuminate\Support\Collection;
use App\Domain\Services\Batch\ExpiryDocumentUpdateBatchService;
use App\Domain\Repositories\Interface\Batch\ExpiryDocumentUpdateInterface;
use PHPUnit\Framework\TestCase;

class ExpiryDocumentUpdateBatchServiceTest extends TestCase
{
    /** @var ExpiryDocumentUpdateInterface $interfaceMock */
    private $interfaceMock;
    
    public function setUp(): void
    {
        $this->interfaceMock = \Mockery::mock(ExpiryDocumentUpdateInterface::class);
    }

    public function tearDoen(): void
    {
        parent::tearDown();
        \Mockery::close();
    }

    /**
     * @test
     * バッチ処理正常終了テスト
     * @return void
     */
    public function expiryTokenGetTest()
    {
        $this->interfaceMock->shouldReceive('getExpiryTokenData')
        ->once()
        ->andReturn(new collection());

        $result = $this->getObject()->expiryDocumentUpdate();

        $this->assertTrue($result);
           
    }

    public function getObject()
    {
        return new ExpiryDocumentUpdateBatchService($this->interfaceMock);
    }
}

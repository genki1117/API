<?php

namespace Tests\Unit\Service\Batch;

use ReflectionClass;
use App\Domain\Consts\DocumentConst;
use Illuminate\Support\Collection;
use App\Domain\Services\Batch\ExpiryDocumentUpdateBatchService;
use App\Domain\Repositories\Interface\Batch\ExpiryDocumentUpdateInterface;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

class ExpiryDocumentUpdateBatchServiceTest extends TestCase
{
    /** @var MockInterface|LegacyMockInterface $interfaceMock */
    private MockInterface|LegacyMockInterface $interfaceMock;

    /** @var DocumentConst $constMock */
    private $constMock;

    public function setUp(): void
    {
        $this->interfaceMock = \Mockery::mock(ExpiryDocumentUpdateInterface::class);
        $this->constMock = new DocumentConst;
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
    public function expiryContractTest()
    {
        $this->interfaceMock->shouldReceive('getExpiryTokenData')
        ->once()
        ->andReturn(new collection());

        $result = $this->getObject()->expiryDocumentUpdate();

        $this->assertTrue($result);       
    }

    /**
     * @test
     * 契約書類テスト
     * @return void
     */
    public function contractTest()
    {
        $this->interfaceMock->shouldReceive('getExpiryTokenData')
        ->once()
        ->andReturn($this->contractTestData1());

        $this->interfaceMock->shouldReceive('expriyUpdateContract')
        ->once();

        $result = $this->getObject()->expiryDocumentUpdate();

        $this->assertTrue($result); 
    }

    /**
     * @test
     * 取引書類テスト
     * @return void
     */
    public function dealTest()
    {
        $this->interfaceMock->shouldReceive('getExpiryTokenData')
        ->once()
        ->andReturn($this->dealTestData1());

        $this->interfaceMock->shouldReceive('expriyUpdateDeal')
        ->once();

        $result = $this->getObject()->expiryDocumentUpdate();

        $this->assertTrue($result); 
    }

    /**
     * @test
     * 社内書類テスト
     * @return void
     */
    public function internalTest()
    {
        $this->interfaceMock->shouldReceive('getExpiryTokenData')
        ->once()
        ->andReturn($this->internalTestData1());

        $this->interfaceMock->shouldReceive('expriyUpdateInternal')
        ->once();

        $result = $this->getObject()->expiryDocumentUpdate();

        $this->assertTrue($result); 
    }

    /**
     * @test
     * 取引書類テスト
     * @return void
     */
    public function archiveTest()
    {
        $this->interfaceMock->shouldReceive('getExpiryTokenData')
        ->once()
        ->andReturn($this->archiveTestData1());

        $this->interfaceMock->shouldReceive('expriyUpdateArchive')
        ->once();

        $result = $this->getObject()->expiryDocumentUpdate();

        $this->assertTrue($result); 
    }

    public function archiveTestData1()
    {
        return [
            (object)[
                't_token' => 'test-token-1',
                'document_id' => '1',
                'category_id' => '3',
                'company_id' => '1',
            ],
            (object)[
                't_token' => 'test-token-2',
                'document_id' => '2',
                'category_id' => '3',
                'company_id' => '1',
            ],
        ];
    }

    public function internalTestData1()
    {
        return [
            (object)[
                't_token' => 'test-token-1',
                'document_id' => '1',
                'category_id' => '2',
                'company_id' => '1',
            ],
            (object)[
                't_token' => 'test-token-2',
                'document_id' => '2',
                'category_id' => '2',
                'company_id' => '1',
            ],
        ];
    }

    public function dealTestData1()
    {
        return [
            (object)[
                't_token' => 'test-token-1',
                'document_id' => '1',
                'category_id' => '1',
                'company_id' => '1',
            ],
            (object)[
                't_token' => 'test-token-2',
                'document_id' => '2',
                'category_id' => '1',
                'company_id' => '1',
            ],
        ];
    }
   
    public function contractTestData1()
    {
        return [
            (object)[
                't_token' => 'test-token-1',
                'document_id' => '1',
                'category_id' => '0',
                'company_id' => '1',
            ],
            (object)[
                't_token' => 'test-token-2',
                'document_id' => '2',
                'category_id' => '0',
                'company_id' => '1',
            ],
        ];
    }

    

    public function getObject()
    {
        return new ExpiryDocumentUpdateBatchService(
            $this->interfaceMock,
            $this->constMock
        );
    }
}

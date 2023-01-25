<?php

namespace Tests\Unit\Repository\Batch;

use Illuminate\Support\Collection;
use App\Domain\Repositories\Batch\ExpiryDocumentUpdateRepository;
use App\Accessers\DB\TempToken;
use App\Accessers\DB\Document\DocumentArchive;
use App\Accessers\DB\Document\DocumentContract;
use App\Accessers\DB\Document\DocumentDeal;
use App\Accessers\DB\Document\DocumentInternal;

use PHPUnit\Framework\TestCase;

class ExpiryDocumentUpdateRepositoryTest extends TestCase
{
    /** @var TempToken $tempTokenMock */
    private $tempTokenMock;

    /** @var DocumentDeal $documentDealMock */
    private $documentDealMock;

    /** @var DocumentArchive $documentArchiveMock */
    private $documentArchiveMock;

    /** @var DocumentContract $documentContractMock */
    private $documentContractMock;

    /** @var DocumentInternal $documentInternalMock */
    private $documentInternalMock;

    public function setUp(): void
    {
        $this->tempTokenMock        =  \Mockery::mock(TempToken::class);
        $this->documentDealMock     = \Mockery::mock(DocumentDeal::class);
        $this->documentArchiveMock  = \Mockery::mock(DocumentArchive::class);
        $this->documentContractMock = \Mockery::mock(DocumentContract::class);
        $this->documentInternalMock = \Mockery::mock(DocumentInternal::class);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        \Mockery::close();
    }

    /**
     * @test
     * トークンオブジェクト取得テスト
     * @return void
     */
    public function getTokenTest()
    {
        $this->tempTokenMock->shouldReceive('getExpiryToken')
        ->once()
        ->andReturn(new Collection);

       $result = $this->getObject()->getExpiryTokenData();
       $this->assertIsObject($result);
    }
    
    /**
     * @test
     * 契約書類期限切れ更新正常テスト
     * @return void
     */
    public function expriyUpdateContractTest()
    {
        $this->documentContractMock->shouldReceive('expiryUpdate')
        ->once()
        ->andReturn(1);

        $this->tempTokenMock->shouldReceive('deleteUpdate')
        ->once()
        ->andReturn(1);

        $result = $this->getObject()->expriyUpdateContract(new Collection);

        $this->assertTrue($result);
    }

    /**
     * @test
     * 契約書類期限切れ更新異常テスト
     * @return void
     */
    public function expriyUpdateContractFaildTest()
    {
        $this->documentContractMock->shouldReceive('expiryUpdate')
        ->once()
        ->andReturn(0);

        $this->tempTokenMock->shouldReceive('deleteUpdate')
        ->once()
        ->andReturn(0);

        $result = $this->getObject()->expriyUpdateContract(new Collection);

        $this->assertFalse($result);
    }

    /**
     * @test
     * 取引書類期限切れ更新正常テスト
     * @return void
     */
    public function expriyUpdateDealTest()
    {
        $this->documentDealMock->shouldReceive('expiryUpdate')
        ->once()
        ->andReturn(1);

        $this->tempTokenMock->shouldReceive('deleteUpdate')
        ->once()
        ->andReturn(1);

        $result = $this->getObject()->expriyUpdateDeal(new Collection);

        $this->assertTrue($result);
    }

    /**
     * @test
     * 取引書類期限切れ更新異常テスト
     * @return void
     */
    public function expriyUpdateDealFaildTest()
    {
        $this->documentDealMock->shouldReceive('expiryUpdate')
        ->once()
        ->andReturn(0);

        $this->tempTokenMock->shouldReceive('deleteUpdate')
        ->once()
        ->andReturn(0);

        $result = $this->getObject()->expriyUpdateDeal(new Collection);

        $this->assertFalse($result);
    }

    /**
     * @test
     * 社内書類期限切れ更新正常テスト
     * @return void
     */
    public function expriyUpdateInternalTest()
    {
        $this->documentInternalMock->shouldReceive('expiryUpdate')
        ->once()
        ->andReturn(1);

        $this->tempTokenMock->shouldReceive('deleteUpdate')
        ->once()
        ->andReturn(1);

        $result = $this->getObject()->expriyUpdateInternal(new Collection);

        $this->assertTrue($result);
    }

    /**
     * @test
     * 社内書類期限切れ更新異常テスト
     * @return void
     */
    public function expriyUpdateInternalFaildTest()
    {
        $this->documentInternalMock->shouldReceive('expiryUpdate')
        ->once()
        ->andReturn(0);

        $this->tempTokenMock->shouldReceive('deleteUpdate')
        ->once()
        ->andReturn(0);

        $result = $this->getObject()->expriyUpdateInternal(new Collection);

        $this->assertFalse($result);
    }

    /**
     * @test
     * 登録書類期限切れ更新正常テスト
     * @return void
     */
    public function expriyUpdateArchiveTest()
    {
        $this->documentArchiveMock->shouldReceive('expiryUpdate')
        ->once()
        ->andReturn(1);

        $this->tempTokenMock->shouldReceive('deleteUpdate')
        ->once()
        ->andReturn(1);

        $result = $this->getObject()->expriyUpdateArchive(new Collection);

        $this->assertTrue($result);
    }


    /**
     * @test
     * 登録書類期限切れ更新異常テスト
     * @return void
     */
    public function expriyUpdateArchiveFaildTest()
    {
        $this->documentArchiveMock->shouldReceive('expiryUpdate')
        ->once()
        ->andReturn(0);

        $this->tempTokenMock->shouldReceive('deleteUpdate')
        ->once()
        ->andReturn(0);

        $result = $this->getObject()->expriyUpdateArchive(new Collection);

        $this->assertFalse($result);
    }


    public function getObject()
    {
        return new ExpiryDocumentUpdateRepository(
            $this->documentArchiveMock,
            $this->documentInternalMock,
            $this->documentDealMock,
            $this->documentContractMock,
            $this->tempTokenMock
        );
    }
}

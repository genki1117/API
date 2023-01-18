<?php

namespace Tests\Unit\Service\Download;

use Exception;
use Reflection;
use App\Domain\Entities\Download\DownloadFile;
use App\Domain\Repositories\Interface\Download\DownloadFileServiceInterface;
use App\Domain\Services\Download\DownloadManagerService;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

class DownloadFileServiceTest extends TestCase
{
    private MockInterface|LegacyMockInterface $documentRepositoryMock;

    public function setUp(): void
    {
        parent::setUp();
        $this->downloadRepositoryMock = \Mockery::mock(DownloadFileServiceInterface::class);
    }

    /**
     * @test
     * 正常処理
     * @runInSeparateProcess
     * @return void
     */
    public function getFileTest ()
    {
        $this->downloadRepositoryMock->shouldReceive('getToken')
        ->once()
        ->andreturn(new DownloadFile($this->getTestToken()));

        $this->downloadRepositoryMock->shouldReceive('getDlFileData')
        ->once()
        ->andReturn(new DownloadFile($this->getTestFile()));

        $result = $this->getObject()->getFile('test-token-1', "2023-01-17T08:23:39.915853Z");

        $this->assertTrue($result);
    }

    /**
     * @test
     * トークン取得不可
     * @runInSeparateProcess
     * @return void
     */
    public function getTokenFaild()
    {
        $this->downloadRepositoryMock->shouldReceive('getToken')
        ->once()
        ->andreturn(null);

        $this->downloadRepositoryMock->shouldReceive('getDlFileData')
        ->once()
        ->andReturn(new DownloadFile($this->getTestFile()));

        $this->expectException(Exception::class);

        $result = $this->getObject()->getFile('test-token-1', "2023-01-17T08:23:39.915853Z");
    }


    private function getObject()
    {
        return new DownloadManagerService($this->downloadRepositoryMock);
    }

    public function getTestToken()
    {
        return $testTokenData = (object)[
            'data' => '{"user_id": 1, "company_id": 1, "dl_file_id": 1}',
            'expiry_date' => '2023-10-10 00:00:00',
        ];
    }

    public function getTestFile()
    {
        return $testFileData = (object)[
            'dl_file_path' => '/var/www/html/testImageFile/test/8a1182c882b5fe86af15c61bba718eea_t.jpg',
            'dl_file_name' => '8a1182c882b5fe86af15c61bba718eea_t.jpg',
        ];
    }

}

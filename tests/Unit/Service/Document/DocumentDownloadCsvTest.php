<?php

namespace Tests\Unit\Service\Document;

use Exception;
use ReflectionClass;
use App\Domain\Services\Document\DocumentDownloadCsvService;
use PHPUnit\Framework\TestCase;

class DocumentDownloadCsvTest extends TestCase
{
    /**
     * @test
     * ローカルにテストファイルを作成
     * @runInSeparateProcess
     * @return void
     */
    public function csvDownloadSccessTest()
    {
        $csv = new DocumentDownloadCsvService();

        $reflection = new ReflectionClass($csv);

        $csvStoragePathPropety = $reflection->getProperty('csvStoragePath');

        $csvStoragePathPropety->setAccessible(true);

        // ローカルのテストパスを取得
        $csvStoragePathPropety->setValue($csv, '/var/www/html/testCsv/');

        $result = $csv->downloadCsv(mUserId: 1, mUserCompanyId: 1, mUserTypeId: 0, categoryId: 0, fileName: 'test.csv');

        $this->assertTrue($result);
    }

    /**
     * @test
     * パスの取得が不正の場合Exception
     * @runInSeparateProcess
     * @return void
     */
    public function csvDownloadFaildTest1()
    {
        $csv = new DocumentDownloadCsvService();

        $reflection = new ReflectionClass($csv);

        $csvStoragePathPropety = $reflection->getProperty('csvStoragePath');

        $csvStoragePathPropety->setAccessible(true);

        // ローカルのテストパスを取得
        $csvStoragePathPropety->setValue($csv, 'testtesttest');

        $this->expectException(Exception::class);
        $result = $csv->downloadCsv(mUserId: 1, mUserCompanyId: 1, mUserTypeId: 0, categoryId: 0, fileName: 'test.csv');
    }

    /**
     * @test
     * ファイルの取得が不正の場合Exception
     * @runInSeparateProcess
     * @return void
     */
    public function csvDownloadFaildTest2()
    {
        $csv = new DocumentDownloadCsvService();

        $reflection = new ReflectionClass($csv);

        $csvStoragePathPropety = $reflection->getProperty('csvStoragePath');

        $csvStoragePathPropety->setAccessible(true);

        // ローカルのテストパスを取得
        $csvStoragePathPropety->setValue($csv, '/var/www/html/testCsv/');

        $this->expectException(Exception::class);
        $result = $csv->downloadCsv(mUserId: 1, mUserCompanyId: 1, mUserTypeId: 0, categoryId: 0, fileName: 'testtesttest.csv');
    }
}
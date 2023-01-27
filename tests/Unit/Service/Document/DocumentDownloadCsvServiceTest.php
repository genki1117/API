<?php

namespace Tests\Unit\Service\Document;

use ZipArchive;
use Exception;
use ReflectionClass;
use App\Domain\Services\Document\DocumentDownloadCsvService;
use PHPUnit\Framework\TestCase;

class DocumentDownloadCsvServiceTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();
    }

    public function tearDown():void
    {
        parent::tearDown();
    }

    /**
     * @test
     * @runInSeparateProcess
     * @return void
     */
    public function downloadCsv()
    {
        //ob_start();
        $result = $this->getObject()->downloadCsv(1, 1, 0, 1, 'test.csv');
        //ob_end_clean();
        $this->assertTrue($result);
    }

    /**
     * @test
     * @runInSeparateProcess
     * @return void
     */
    public function downloadCsv_1()
    {
        
        $this->expectException(Exception::class);
        $this->getObject()->downloadCsv(
            mUserId: 1,
            mUserCompanyId: 1,
            mUserTypeId: 1,
            categoryId: 1,
            fileName: 'test.csv'
        );
    }

    /**
     * @test
     * @runInSeparateProcess
     * @return void
     */
    public function downloadCsv_2()
    {
        
        $this->expectException(Exception::class);
        $this->getObject()->downloadCsv(
            mUserId: null,
            mUserCompanyId: 1,
            mUserTypeId: 1,
            categoryId: 1,
            fileName: 'test.csv'
        );
    }

    /**
     * @test
     * @runInSeparateProcess
     * @return void
     */
    public function downloadCsv_3()
    {
        
        $this->expectException(Exception::class);
        $this->getObject()->downloadCsv(
            mUserId: 1,
            mUserCompanyId: null,
            mUserTypeId: 1,
            categoryId: 1,
            fileName: 'test.csv'
        );
    }

    /**
     * @test
     * @runInSeparateProcess
     * @return void
     */
    public function downloadCsv_4()
    {
        
        $this->expectException(Exception::class);
        $this->getObject()->downloadCsv(
            mUserId: 1,
            mUserCompanyId: 1,
            mUserTypeId: null,
            categoryId: 1,
            fileName: 'test.csv'
        );
    }

    /**
     * @test
     * @runInSeparateProcess
     * @return void
     */
    public function downloadCsv_5()
    {
        
        $this->expectException(Exception::class);
        $this->getObject()->downloadCsv(
            mUserId: 1,
            mUserCompanyId: 1,
            mUserTypeId: 1,
            categoryId: null,
            fileName: 'test.csv'
        );
    }

    /**
     * @test
     * @runInSeparateProcess
     * @return void
     */
    public function downloadCsv_6()
    {
        
        $this->expectException(Exception::class);
        $this->getObject()->downloadCsv(
            mUserId: 1,
            mUserCompanyId: 1,
            mUserTypeId: 1,
            categoryId: 1,
            fileName: null
        );
    }

    /**
     * @test
     * @runInSeparateProcess
     * @return void
     */
    public function downloadCsv_7()
    {
        
        $this->expectException(Exception::class);
        $this->getObject()->downloadCsv(
            mUserId: 1,
            mUserCompanyId: 1,
            mUserTypeId: 1,
            categoryId: 1,
            fileName: null
        );
    }

    

    public function getObject()
    {
        return new DocumentDownloadCsvService();
    }
}
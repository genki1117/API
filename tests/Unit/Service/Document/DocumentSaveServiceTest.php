<?php

namespace Tests\Unit\Service\Document;

use Exception;
use App\Domain\Consts\DocumentConst;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use App\Domain\Entities\Document\Document;
use App\Domain\Entities\Document\DocumentUpdate;
use App\Domain\Repositories\Interface\Document\DocumentSaveRepositoryInterface;
use App\Domain\Services\Document\DocumentSaveService;
use PHPUnit\Framework\TestCase;

class DocumentSaveServiceTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->documentRepositoryMock = \Mockery::mock(DocumentSaveRepositoryInterface::class);
        $this->docConst = new DocumentConst;
    }

    /**
     * @test
     * 契約書類登録正常テスト
     * @return void
     */
    public function contractInsertSuccessTest()
    {
        $this->documentRepositoryMock->shouldReceive('contractInsert')
        ->once()->andReturn(true);

        $result = $this->getObject()->saveDocument($this->contractInsertData());
        $this->assertTrue($result);
    }

    /**
     * @test
     * 契約書類登録異常テスト
     * @return void
     */
    public function contractInserFaildTest()
    {
        $this->documentRepositoryMock->shouldReceive('contractInsert')
        ->once()->andReturn(false);

        $this->expectException(Exception::class);
        $result = $this->getObject()->saveDocument($this->contractInsertData());
    }

    /**
     * @test
     * 契約書類更新正常テスト
     * @return void
     */
    public function contractUpdateSuccessTest()
    {
        $this->documentRepositoryMock->shouldReceive('contractUpdate')
        ->once()->andReturn(true);

        $this->documentRepositoryMock->shouldReceive('getBeforOrAfterUpdateContract')
        ->once()->andReturn(new DocumentUpdate());
        
        $this->documentRepositoryMock->shouldReceive('getUpdateLogContract')
        ->once()->andReturn(true);

        $result = $this->getObject()->saveDocument($this->contractUpdateData());
        $this->assertTrue($result);
    }

    /**
     * @test
     * 契約書類更新異常テスト
     * @return void
     */
    public function contractUpdateFaildTest()
    {
        $this->documentRepositoryMock->shouldReceive('contractUpdate')
        ->once()->andReturn(false);

        $this->documentRepositoryMock->shouldReceive('getBeforOrAfterUpdateContract')
        ->once()->andReturn(new DocumentUpdate());
        
        $this->documentRepositoryMock->shouldReceive('getUpdateLogContract')
        ->once()->andReturn(true);

        $this->expectException(Exception::class);
        $result = $this->getObject()->saveDocument($this->contractUpdateData());
    }

    /**
     * @test
     * 取引書類登録正常テスト
     * @return void
     */
    public function dealInsertSuccessTest()
    {
        $this->documentRepositoryMock->shouldReceive('dealInsert')
        ->once()->andReturn(true);

        $result = $this->getObject()->saveDocument($this->dealInsertData());
        $this->assertTrue($result);
    }

    /**
     * @test
     * 取引書類登録正常テスト
     * @return void
     */
    public function dealInsertFaildTest()
    {
        $this->documentRepositoryMock->shouldReceive('dealUpdate')
        ->once()->andReturn(false);

        $this->expectException(Exception::class);
        $result = $this->getObject()->saveDocument($this->dealInsertData());
    }

    /**
     * @test
     * 取引書類更新正常テスト
     * @return void
     */
    public function dealUpdateSuccessTest()
    {
        $this->documentRepositoryMock->shouldReceive('dealUpdate')
        ->once()->andReturn(true);

        $this->documentRepositoryMock->shouldReceive('getBeforOrAfterUpdateDeal')
        ->once()->andReturn(new DocumentUpdate());
        
        $this->documentRepositoryMock->shouldReceive('getUpdateLogDeal')
        ->once()->andReturn(true);

        $result = $this->getObject()->saveDocument($this->dealUpdateData());
        $this->assertTrue($result);
    }

    /**
     * @test
     * 取引書類更新異常テスト
     * @return void
     */
    public function dealUpdateFaildTest()
    {
        $this->documentRepositoryMock->shouldReceive('dealUpdate')
        ->once()->andReturn(false);

        $this->documentRepositoryMock->shouldReceive('getBeforOrAfterUpdateDeal')
        ->once()->andReturn(new DocumentUpdate());
        
        $this->documentRepositoryMock->shouldReceive('getUpdateLogDeal')
        ->once()->andReturn(true);

        $this->expectException(Exception::class);
        $result = $this->getObject()->saveDocument($this->dealUpdateData());
    }

    /**
     * @test
     * 社内書類登録正常テスト
     * @return void
     */
    public function internalInsertSuccessTest()
    {
        $this->documentRepositoryMock->shouldReceive('internalInsert')
        ->once()->andReturn(true);

        $result = $this->getObject()->saveDocument($this->internalInsertData());
        $this->assertTrue($result);
    }

    /**
     * @test
     * 社内書類登録異常テスト
     * @return void
     */
    public function internalInsertFaildTest()
    {
        $this->documentRepositoryMock->shouldReceive('internalUpdate')
        ->once()->andReturn(false);

        $this->expectException(Exception::class);
        $result = $this->getObject()->saveDocument($this->internalInsertData());
    }

    /**
     * @test
     * 社内書類更新正常テスト
     * @return void
     */
    public function internalUpdateSuccessTest()
    {
        $this->documentRepositoryMock->shouldReceive('internalUpdate')
        ->once()->andReturn(true);

        $this->documentRepositoryMock->shouldReceive('getBeforOrAfterUpdateInternal')
        ->once()->andReturn(new DocumentUpdate());
        
        $this->documentRepositoryMock->shouldReceive('getUpdateLogInternal')
        ->once()->andReturn(true);

        $result = $this->getObject()->saveDocument($this->internalUpdateData());
        $this->assertTrue($result);
    }

    /**
     * @test
     * 社内書類更新異常テスト
     * @return void
     */
    public function internalUpdateFaildTest()
    {
        $this->documentRepositoryMock->shouldReceive('internalUpdate')
        ->once()->andReturn(false);

        $this->documentRepositoryMock->shouldReceive('getBeforOrAfterUpdateInternal')
        ->once()->andReturn(new DocumentUpdate());
        
        $this->documentRepositoryMock->shouldReceive('getUpdateLogInternal')
        ->once()->andReturn(true);

        $this->expectException(Exception::class);
        $result = $this->getObject()->saveDocument($this->internalUpdateData());
    }

    /**
     * @test
     * 登録書類登録正常テスト
     * @return void
     */
    public function archiveInsertSuccessTest()
    {
        $this->documentRepositoryMock->shouldReceive('archiveInsert')
        ->once()->andReturn(true);

        $result = $this->getObject()->saveDocument($this->archiveInsertData());
        $this->assertTrue($result);
    }

    /**
     * @test
     * 登録書類登録異常テスト
     * @return void
     */
    public function archiveInsertFaildTest()
    {
        $this->documentRepositoryMock->shouldReceive('archiveUpdate')
        ->once()->andReturn(false);

        $this->expectException(Exception::class);
        $result = $this->getObject()->saveDocument($this->archiveInsertData());
    }

    /**
     * @test
     * 登録書類更新正常テスト
     * @return void
     */
    public function archiveUpdateSuccessTest()
    {
        $this->documentRepositoryMock->shouldReceive('archiveUpdate')
        ->once()->andReturn(true);

        $this->documentRepositoryMock->shouldReceive('getBeforOrAfterUpdateArchive')
        ->once()->andReturn(new DocumentUpdate());
        
        $this->documentRepositoryMock->shouldReceive('getUpdateLogArchive')
        ->once()->andReturn(true);

        $result = $this->getObject()->saveDocument($this->archiveUpdateData());
        $this->assertTrue($result);
    }

    /**
     * @test
     * 登録書類更新異常テスト
     * @return void
     */
    public function archiveUpdateFaildTest()
    {
        $this->documentRepositoryMock->shouldReceive('archiveUpdate')
        ->once()->andReturn(false);

        $this->documentRepositoryMock->shouldReceive('getBeforOrAfterUpdateArchive')
        ->once()->andReturn(new DocumentUpdate());
        
        $this->documentRepositoryMock->shouldReceive('getUpdateLogArchive')
        ->once()->andReturn(true);

        $this->expectException(Exception::class);
        $result = $this->getObject()->saveDocument($this->archiveUpdateData());
    }

    

    public function archiveUpdateData()
    {
        return [
            "m_user_id" => 1,
            "m_user_company_id" => 1,
            "m_user_company_id" => 1,
            "m_user_type_id" => 0,
            "company_id" => 1,
            "category_id" => 3,
            "document_id" => 1,
            "template_id" => 1,
            "doc_type_id" => 1,
            "status_id" => 0,
        ];
    }

    public function archiveInsertData()
    {
        return [
            "m_user_id" => 1,
            "m_user_company_id" => 1,
            "m_user_company_id" => 1,
            "m_user_type_id" => 0,
            "company_id" => 1,
            "category_id" => 3,
            "document_id" => null,
            "template_id" => 1,
            "doc_type_id" => 1,
            "status_id" => 0,
        ];
    }

    public function internalUpdateData()
    {
        return [
            "m_user_id" => 1,
            "m_user_company_id" => 1,
            "m_user_company_id" => 1,
            "m_user_type_id" => 0,
            "company_id" => 1,
            "category_id" => 2,
            "document_id" => 1,
            "template_id" => 1,
            "doc_type_id" => 1,
            "status_id" => 0,
        ];
    }

    public function internalInsertData()
    {
        return [
            "m_user_id" => 1,
            "m_user_company_id" => 1,
            "m_user_company_id" => 1,
            "m_user_type_id" => 0,
            "company_id" => 1,
            "category_id" => 2,
            "document_id" => null,
            "template_id" => 1,
            "doc_type_id" => 1,
            "status_id" => 0,
        ];
    }

    public function dealUpdateData()
    {
        return [
            "m_user_id" => 1,
            "m_user_company_id" => 1,
            "m_user_company_id" => 1,
            "m_user_type_id" => 0,
            "company_id" => 1,
            "category_id" => 1,
            "document_id" => 1,
            "template_id" => 1,
            "doc_type_id" => 1,
            "status_id" => 0,
        ];
    }

    public function dealInsertData()
    {
        return [
            "m_user_id" => 1,
            "m_user_company_id" => 1,
            "m_user_company_id" => 1,
            "m_user_type_id" => 0,
            "company_id" => 1,
            "category_id" => 1,
            "document_id" => null,
            "template_id" => 1,
            "doc_type_id" => 1,
            "status_id" => 0,
        ];
    }

    public function contractUpdateData()
    {
        return [
            "m_user_id" => 1,
            "m_user_company_id" => 1,
            "m_user_company_id" => 1,
            "m_user_type_id" => 0,
            "company_id" => 1,
            "category_id" => 0,
            "document_id" => 1,
            "template_id" => 1,
            "doc_type_id" => 1,
            "status_id" => 0,
        ];
    }

    public function contractInsertData()
    {
        return [
            "m_user_id" => 1,
            "m_user_company_id" => 1,
            "m_user_company_id" => 1,
            "m_user_type_id" => 0,
            "company_id" => 1,
            "category_id" => 0,
            "document_id" => null,
            "template_id" => 1,
            "doc_type_id" => 1,
            "status_id" => 0,
        ];
    }

    public function getObject()
    {
        return $this->documentSaveService = new DocumentSaveService($this->documentRepositoryMock, $this->docConst);
    }
        
}

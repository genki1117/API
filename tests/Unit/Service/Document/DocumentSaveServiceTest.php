<?php

namespace Tests\Unit\Service\Document;

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
    private $documentRepositoryMock;

    public function setUp(): void
    {
        parent::setUp();
        $this->documentRepositoryMock = \Mockery::mock(DocumentSaveRepositoryInterface::class);
        $this->docConst = new DocumentConst;
        $this->documentSaveService = new DocumentSaveService($this->documentRepositoryMock, $this->docConst);
    }

    /** @test */
    public function saveDocumentTest()
    {
        $this->documentRepositoryMock->shouldReceive('contractInsert')->once()->andReturn(true);
        $this->documentRepositoryMock->shouldReceive('contractUpdate')->once()->andReturn(true);
        $this->documentRepositoryMock->shouldReceive('dealInsert')->once()->andReturn(true);
        $this->documentRepositoryMock->shouldReceive('dealUpdate')->once()->andReturn(true);
        $this->documentRepositoryMock->shouldReceive('internalInsert')->once()->andReturn(true);
        $this->documentRepositoryMock->shouldReceive('internalUpdate')->once()->andReturn(true);
        $this->documentRepositoryMock->shouldReceive('archiveInsert')->once()->andReturn(true);
        $this->documentRepositoryMock->shouldReceive('archiveUpdate')->once()->andReturn(true);
        $this->documentRepositoryMock->shouldReceive('getBeforOrAfterUpdateContract->getUpdateDocument')->once()->andReturn(true);
        $this->documentRepositoryMock->shouldReceive('getBeforOrAfterUpdateDeal->getUpdateDocument')->once()->andReturn(true);
        $this->documentRepositoryMock->shouldReceive('getBeforOrAfterUpdateInternal->getUpdateDocument')->once()->andReturn(true);
        $this->documentRepositoryMock->shouldReceive('getBeforOrAfterUpdateArchive->getUpdateDocument')->once()->andReturn(true);
        $this->documentRepositoryMock->shouldReceive('getUpdateLog')->once()->andReturn(true);
        
        $documentSaveService = new DocumentSaveService($this->documentRepositoryMock, $this->docConst);


        /**
         * 契約書類新規登録テスト
         */
        $dataContractInsert = [
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
            "select_sign_user" => [
                [
                    "group_array" => [["group_id" => 1]],
                    "user_id" => 1,
                    "family_name" => "sei",
                    "first_name" => "mei",
                    "email" => "test@test.com",
                    "wf_sort" => 1
                ],
                [
                    "group_array" => [["group_id" => 2]],
                    "user_id" => 2,
                    "family_name" => "sei2",
                    "first_name" => "mei2",
                    "email" => "test2@test.com",
                    "wf_sort" => 2
                ],
                
            ],
            "select_sign_guest_user" => [
                [
                    "group_array" => [["group_id" => 3]],
                    "user_id" => 3,
                    "family_name" => "sei3",
                    "first_name" => "mei3",
                    "email" => "test3@test.com",
                    "wf_sort" => 3
                ],
                [
                    "group_array" => [["group_id" => 4]],
                    "user_id" => 4,
                    "family_name" => "sei4",
                    "first_name" => "mei4",
                    "email" => "test4@test.com",
                    "wf_sort" => 4
                ],
                
            ],
            "create_user" => 1,
            "create_datetime" => "2022-10-10",
            "update_user" => null,
            "update_datetime" => null
        ];
        $contractInsertResutl = $documentSaveService->saveDocument($dataContractInsert);
        $this->assertTrue($contractInsertResutl);

        /**
         * 契約書類更新テスト
         */
        $dataContractUpdate = [
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
            "select_sign_user" => [
                [
                    "group_array" => [["group_id" => 1]],
                    "user_id" => 1,
                    "family_name" => "sei",
                    "first_name" => "mei",
                    "email" => "test@test.com",
                    "wf_sort" => 1
                ],
                [
                    "group_array" => [["group_id" => 2]],
                    "user_id" => 2,
                    "family_name" => "sei2",
                    "first_name" => "mei2",
                    "email" => "test2@test.com",
                    "wf_sort" => 2
                ],
                
            ],
            "select_sign_guest_user" => [
                [
                    "group_array" => [["group_id" => 3]],
                    "user_id" => 3,
                    "family_name" => "sei3",
                    "first_name" => "mei3",
                    "email" => "test3@test.com",
                    "wf_sort" => 3
                ],
                [
                    "group_array" => [["group_id" => 4]],
                    "user_id" => 4,
                    "family_name" => "sei4",
                    "first_name" => "mei4",
                    "email" => "test4@test.com",
                    "wf_sort" => 4
                ],
                
            ],
            "create_user" => 1,
            "create_datetime" => "2022-10-10",
            "update_user" => null,
            "update_datetime" => null
        ];
        $contractUpdateResutl = $documentSaveService->saveDocument($dataContractUpdate);
        $this->assertTrue($contractUpdateResutl);

        /**
         * 取引書類新規登録テスト
         */
        $dataDealInsert = [
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
            "select_sign_user" => [
                [
                    "group_array" => [["group_id" => 1]],
                    "user_id" => 1,
                    "family_name" => "sei",
                    "first_name" => "mei",
                    "email" => "test@test.com",
                    "wf_sort" => 1
                ],
                [
                    "group_array" => [["group_id" => 2]],
                    "user_id" => 2,
                    "family_name" => "sei2",
                    "first_name" => "mei2",
                    "email" => "test2@test.com",
                    "wf_sort" => 2
                ],
                
            ],
            "select_sign_guest_user" => [
                [
                    "group_array" => [["group_id" => 3]],
                    "user_id" => 3,
                    "family_name" => "sei3",
                    "first_name" => "mei3",
                    "email" => "test3@test.com",
                    "wf_sort" => 3
                ],
                [
                    "group_array" => [["group_id" => 4]],
                    "user_id" => 4,
                    "family_name" => "sei4",
                    "first_name" => "mei4",
                    "email" => "test4@test.com",
                    "wf_sort" => 4
                ],
                
            ],
            "create_user" => 1,
            "create_datetime" => "2022-10-10",
            "update_user" => null,
            "update_datetime" => null
        ];
        $dealInsertResutl = $documentSaveService->saveDocument($dataDealInsert);
        $this->assertTrue($dealInsertResutl);

        /**
         * 取引書類更新テスト
         */
        $dataDealUpdate = [
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
            "select_sign_user" => [
                [
                    "group_array" => [["group_id" => 1]],
                    "user_id" => 1,
                    "family_name" => "sei",
                    "first_name" => "mei",
                    "email" => "test@test.com",
                    "wf_sort" => 1
                ],
                [
                    "group_array" => [["group_id" => 2]],
                    "user_id" => 2,
                    "family_name" => "sei2",
                    "first_name" => "mei2",
                    "email" => "test2@test.com",
                    "wf_sort" => 2
                ],
                
            ],
            "select_sign_guest_user" => [
                [
                    "group_array" => [["group_id" => 3]],
                    "user_id" => 3,
                    "family_name" => "sei3",
                    "first_name" => "mei3",
                    "email" => "test3@test.com",
                    "wf_sort" => 3
                ],
                [
                    "group_array" => [["group_id" => 4]],
                    "user_id" => 4,
                    "family_name" => "sei4",
                    "first_name" => "mei4",
                    "email" => "test4@test.com",
                    "wf_sort" => 4
                ],
                
            ],
            "create_user" => 1,
            "create_datetime" => "2022-10-10",
            "update_user" => null,
            "update_datetime" => null
        ];
        $dealUpdateResutl = $documentSaveService->saveDocument($dataDealUpdate);
        $this->assertTrue($dealUpdateResutl);


        /**
         * 社内書類新規登録テスト
         */
        $dataInternalInsert = [
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
            "select_sign_user" => [
                [
                    "group_array" => [["group_id" => 1]],
                    "user_id" => 1,
                    "family_name" => "sei",
                    "first_name" => "mei",
                    "email" => "test@test.com",
                    "wf_sort" => 1
                ],
                [
                    "group_array" => [["group_id" => 2]],
                    "user_id" => 2,
                    "family_name" => "sei2",
                    "first_name" => "mei2",
                    "email" => "test2@test.com",
                    "wf_sort" => 2
                ],
                
            ],
            "select_sign_guest_user" => [
                [
                    "group_array" => [["group_id" => 3]],
                    "user_id" => 3,
                    "family_name" => "sei3",
                    "first_name" => "mei3",
                    "email" => "test3@test.com",
                    "wf_sort" => 3
                ],
                [
                    "group_array" => [["group_id" => 4]],
                    "user_id" => 4,
                    "family_name" => "sei4",
                    "first_name" => "mei4",
                    "email" => "test4@test.com",
                    "wf_sort" => 4
                ],
                
            ],
            "create_user" => 1,
            "create_datetime" => "2022-10-10",
            "update_user" => null,
            "update_datetime" => null
        ];
        $internalInsertResutl = $documentSaveService->saveDocument($dataInternalInsert);
        $this->assertTrue($internalInsertResutl);

        /**
         * 社内書類更新テスト
         */
        $dataInternalUpdate = [
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
            "select_sign_user" => [
                [
                    "group_array" => [["group_id" => 1]],
                    "user_id" => 1,
                    "family_name" => "sei",
                    "first_name" => "mei",
                    "email" => "test@test.com",
                    "wf_sort" => 1
                ],
                [
                    "group_array" => [["group_id" => 2]],
                    "user_id" => 2,
                    "family_name" => "sei2",
                    "first_name" => "mei2",
                    "email" => "test2@test.com",
                    "wf_sort" => 2
                ],
                
            ],
            "select_sign_guest_user" => [
                [
                    "group_array" => [["group_id" => 3]],
                    "user_id" => 3,
                    "family_name" => "sei3",
                    "first_name" => "mei3",
                    "email" => "test3@test.com",
                    "wf_sort" => 3
                ],
                [
                    "group_array" => [["group_id" => 4]],
                    "user_id" => 4,
                    "family_name" => "sei4",
                    "first_name" => "mei4",
                    "email" => "test4@test.com",
                    "wf_sort" => 4
                ],
                
            ],
            "create_user" => 1,
            "create_datetime" => "2022-10-10",
            "update_user" => null,
            "update_datetime" => null
        ];
        $internalUpdateResutl = $documentSaveService->saveDocument($dataInternalUpdate);
        $this->assertTrue($internalUpdateResutl);


        /**
         * 登録書類新規登録テスト
         */
        $dataArchiveInsert = [
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
            "select_sign_user" => [
                [
                    "group_array" => [["group_id" => 1]],
                    "user_id" => 1,
                    "family_name" => "sei",
                    "first_name" => "mei",
                    "email" => "test@test.com",
                    "wf_sort" => 1
                ],
                [
                    "group_array" => [["group_id" => 2]],
                    "user_id" => 2,
                    "family_name" => "sei2",
                    "first_name" => "mei2",
                    "email" => "test2@test.com",
                    "wf_sort" => 2
                ],
                
            ],
            "select_sign_guest_user" => null,
            "create_user" => 1,
            "create_datetime" => "2022-10-10",
            "update_user" => null,
            "update_datetime" => null
        ];
        $archiveInsertResutl = $documentSaveService->saveDocument($dataArchiveInsert);
        $this->assertTrue($archiveInsertResutl);

        /**
         * 登録書類更新テスト
         */
        $dataArchiveUpdate = [
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
            "select_sign_user" => [
                [
                    "group_array" => [["group_id" => 1]],
                    "user_id" => 1,
                    "family_name" => "sei",
                    "first_name" => "mei",
                    "email" => "test@test.com",
                    "wf_sort" => 1
                ],
                [
                    "group_array" => [["group_id" => 2]],
                    "user_id" => 2,
                    "family_name" => "sei2",
                    "first_name" => "mei2",
                    "email" => "test2@test.com",
                    "wf_sort" => 2
                ],
                
            ],
            "select_sign_guest_user" => null,
            "create_user" => 1,
            "create_datetime" => "2022-10-10",
            "update_user" => null,
            "update_datetime" => null
        ];
        $archiveUpdateResutl = $documentSaveService->saveDocument($dataArchiveUpdate);
        $this->assertTrue($archiveUpdateResutl);


    }
}

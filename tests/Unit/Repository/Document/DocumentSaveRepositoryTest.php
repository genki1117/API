<?php

namespace Tests\Unit\Repository\Document;

use App\Accessers\DB\Document\DocumentArchive;
use App\Accessers\DB\Document\DocumentContract;
use App\Accessers\DB\Document\DocumentDeal;
use App\Accessers\DB\Document\DocumentInternal;
use App\Accessers\DB\Document\DocumentPermissionArchive;
use App\Accessers\DB\Document\DocumentPermissionContract;
use App\Accessers\DB\Document\DocumentPermissionInternal;
use App\Accessers\DB\Document\DocumentPermissionTransaction;
use App\Accessers\DB\Document\DocumentStorageContract;
use App\Accessers\DB\Document\DocumentStorageTransaction;
use App\Accessers\DB\Document\DocumentStorageInternal;
use App\Accessers\DB\Document\DocumentStorageArchive;
use App\Accessers\DB\Document\DocumentWorkFlow;
use App\Accessers\DB\Log\System\LogDocAccess;
use App\Accessers\DB\Log\System\LogDocOperation;
use App\Domain\Entities\Document\Document;
use App\Domain\Repositories\Document\DocumentSaveRepository;
use PHPUnit\Framework\TestCase;

class DocumentSaveRepositoryTest extends TestCase
{
    private $docContract;
    private $docDeal;
    private $docInternal;
    private $docArchive;
    private $docPermissionContract;
    private $docPermissionTransaction;
    private $docPermissionInternal;
    private $docPermissionArchive;
    private $docStorageContract;
    private $docStorageTransaction;
    private $docStorageInternal;
    private $docStorageArchive;
    private $documentWorkFlow;
    private $logDocAccess;
    private $logDocOperation;

    public function setUp(): void
    {
        $this-> docContractMock              = \Mockery::mock(DocumentContract::class);
        $this-> docDealMock                  = \Mockery::mock(DocumentDeal::class);
        $this-> docInternalMock              = \Mockery::mock(DocumentInternal::class);
        $this-> docArchiveMock               = \Mockery::mock(DocumentArchive::class);
        $this-> docPermissionContractMock     = \Mockery::mock(DocumentPermissionContract::class);
        $this-> docPermissionTransactionMock = \Mockery::mock(DocumentPermissionTransaction::class);
        $this-> docPermissionInternalMock    = \Mockery::mock(DocumentPermissionInternal::class);
        $this-> docPermissionArchiveMock     = \Mockery::mock(DocumentPermissionArchive::class);
        $this-> docStorageContractMock       = \Mockery::mock(DocumentStorageContract::class);
        $this-> docStorageTransactionMock    = \Mockery::mock(DocumentStorageTransaction::class);
        $this-> docStorageInternalMock       = \Mockery::mock(DocumentStorageInternal::class);
        $this-> docStorageArchiveMock        = \Mockery::mock(DocumentStorageArchive::class);
        $this-> documentWorkFlowMock         = \Mockery::mock(DocumentWorkFlow::class);
        $this-> logDocAccessMock             = \Mockery::mock(LogDocAccess::class);
        $this-> logDocOperationMock          = \Mockery::mock(logDocOperation::class);
    }

    /** @test
     * 登録正常テスト
     */
    public function documentInsertTest ()
    {
        $this->docContractMock->shouldReceive('insert')->once()->andReturn(1);
        $this->docDealMock->shouldReceive('insert')->once()->andReturn(1);
        $this->docInternalMock->shouldReceive('insert')->once()->andReturn(1);
        $this->docArchiveMock->shouldReceive('insert')->once()->andReturn(1);
        $this->docPermissionContractMock->shouldReceive('insert')->once()->andReturn(1);
        $this->docPermissionTransactionMock->shouldReceive('insert')->once()->andReturn(1);
        $this->docPermissionInternalMock->shouldReceive('insert')->once()->andReturn(1);
        $this->docPermissionArchiveMock->shouldReceive('insert')->once()->andReturn(1);
        $this->docStorageContractMock->shouldReceive('insert')->once()->andReturn(1);
        $this->docStorageTransactionMock->shouldReceive('insert')->once()->andReturn(1);
        $this->docStorageInternalMock->shouldReceive('insert')->once()->andReturn(1);
        $this->docStorageArchiveMock->shouldReceive('insert')->once()->andReturn(1);
        $this->documentWorkFlowMock->shouldReceive('insertContract')->once()->AndReturn(1);
        $this->documentWorkFlowMock->shouldReceive('insertDeal')->once()->AndReturn(1);
        $this->documentWorkFlowMock->shouldReceive('insertInternal')->once()->AndReturn(1);
        $this->documentWorkFlowMock->shouldReceive('insertArchive')->once()->AndReturn(1);
        $this->logDocAccessMock->shouldReceive('insert')->andReturn(1);
        $this->logDocOperationMock->shouldReceive('insert')->andReturn(1);

        $documentSaveRepository = new DocumentSaveRepository(
            $this-> docContractMock,
            $this-> docDealMock,
            $this-> docInternalMock,
            $this-> docArchiveMock,
            $this-> docPermissionContractMock,
            $this-> docPermissionTransactionMock,
            $this-> docPermissionInternalMock,
            $this-> docPermissionArchiveMock,
            $this-> docStorageContractMock,
            $this-> docStorageTransactionMock,
            $this-> docStorageInternalMock,
            $this-> docStorageArchiveMock,
            $this->documentWorkFlowMock,
            $this->logDocAccessMock,
            $this->logDocOperationMock
        );

        /**
         * 契約書類
         * ホスト署名者2
         * ゲスト署名者2
         */
        $dataContract_2_2 = [
            "m_user_id" => 1,
            "m_user_company_id" => 1,
            "m_user_company_id" => 1,
            "m_user_type_id" => 0,
            "company_id" => 1,
            "category_id" => 0,
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

        

        /**
         * 契約書類
         * ホスト署名者1
         * ゲスト署名者0
         */
        $dataContract_1_0 = [
            "m_user_id" => 1,
            "m_user_company_id" => 1,
            "m_user_company_id" => 1,
            "m_user_type_id" => 0,
            "company_id" => 1,
            "category_id" => 0,
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
                
            ],
            "select_sign_guest_user" => null,
            "create_user" => 1,
            "create_datetime" => "2022-10-10",
            "update_user" => null,
            "update_datetime" => null
        ];

        /**
         * 契約書類
         * ホスト署名者1
         * ゲスト署名者0
         */
        $dataContract_2_0 = [
            "m_user_id" => 1,
            "m_user_company_id" => 1,
            "m_user_company_id" => 1,
            "m_user_type_id" => 0,
            "company_id" => 1,
            "category_id" => 0,
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
                    "family_name" => "sei",
                    "first_name" => "mei",
                    "email" => "test@test.com",
                    "wf_sort" => 2
                ],
                
            ],
            "select_sign_guest_user" => null,
            "create_user" => 1,
            "create_datetime" => "2022-10-10",
            "update_user" => null,
            "update_datetime" => null
        ];

        /**
         * 取引書類
         * ホスト署名者2
         * ゲスト署名者2
         */
        $dataDeal_2_2 = [
            "m_user_id" => 1,
            "m_user_company_id" => 1,
            "m_user_company_id" => 1,
            "m_user_type_id" => 0,
            "company_id" => 1,
            "category_id" => 1, // 取引書類
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

        /**
         * 取引書類
         * ホスト署名者1
         * ゲスト署名者0
         */
        $dataDeal_1_0 = [
            "m_user_id" => 1,
            "m_user_company_id" => 1,
            "m_user_company_id" => 1,
            "m_user_type_id" => 0,
            "company_id" => 1,
            "category_id" => 1, // 取引書類
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
            ],
            "select_sign_guest_user" => null,
            "create_user" => 1,
            "create_datetime" => "2022-10-10",
            "update_user" => null,
            "update_datetime" => null
        ];

        /**
         * 取引書類
         * ホスト署名者2
         * ゲスト署名者0
         */
        $dataDeal_2_0 = [
            "m_user_id" => 1,
            "m_user_company_id" => 1,
            "m_user_company_id" => 1,
            "m_user_type_id" => 0,
            "company_id" => 1,
            "category_id" => 1, // 取引書類
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

        /**
         * 社内書類
         * ホスト署名者2
         * ゲスト署名者2
         */
        $dataInternal_2_2 = [
            "m_user_id" => 1,
            "m_user_company_id" => 1,
            "m_user_company_id" => 1,
            "m_user_type_id" => 0,
            "company_id" => 1,
            "category_id" => 2, // 社内書類
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


        /**
         * 社内書類
         * ホスト署名者1
         * ゲスト署名者0
         */
        $dataInternal_1_0 = [
            "m_user_id" => 1,
            "m_user_company_id" => 1,
            "m_user_company_id" => 1,
            "m_user_type_id" => 0,
            "company_id" => 1,
            "category_id" => 2, // 取引書類
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
            ],
            "select_sign_guest_user" => null,
            "create_user" => 1,
            "create_datetime" => "2022-10-10",
            "update_user" => null,
            "update_datetime" => null
        ];

        /**
         * 社内書類
         * ホスト署名者2
         * ゲスト署名者0
         */
        $dataInternal_2_0 = [
            "m_user_id" => 1,
            "m_user_company_id" => 1,
            "m_user_company_id" => 1,
            "m_user_type_id" => 0,
            "company_id" => 1,
            "category_id" => 2, // 取引書類
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

        /**
         * 登録書類
         * ホスト署名者2
         * ゲスト署名者0
         */
        $dataArchive_2_0 = [
            "m_user_id" => 1,
            "m_user_company_id" => 1,
            "m_user_company_id" => 1,
            "m_user_type_id" => 0,
            "company_id" => 1,
            "category_id" => 3, // 社内書類
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

        

        /**
         * 契約書類登録テスト
         */
        $contractInsertResult_2_2 = $documentSaveRepository->contractInsert($dataContract_2_2);
        $contractInsertResult_1_0 = $documentSaveRepository->contractInsert($dataContract_1_0);
        $contractInsertResult_2_0 = $documentSaveRepository->contractInsert($dataContract_2_0);
        $this->assertTrue($contractInsertResult_2_2);
        $this->assertTrue($contractInsertResult_1_0);
        $this->assertTrue($contractInsertResult_2_0);

        /**
         * 取引書類登録テスト
         */
        $dealInsertResult_2_2 = $documentSaveRepository->dealInsert($dataDeal_2_2);
        $dealInsertResult_1_0 = $documentSaveRepository->dealInsert($dataDeal_1_0);
        $dealInsertResult_2_0 = $documentSaveRepository->dealInsert($dataDeal_2_0);
        $this->assertTrue($dealInsertResult_2_2);
        $this->assertTrue($dealInsertResult_1_0);
        $this->assertTrue($dealInsertResult_2_0);

        /**
         * 社内書類登録テスト
         */
        $internalInsertResult_2_2 = $documentSaveRepository->internalInsert($dataInternal_2_2);
        $internalInsertResult_1_0 = $documentSaveRepository->internalInsert($dataInternal_1_0);
        $internalInsertResult_2_0 = $documentSaveRepository->internalInsert($dataInternal_2_0);
        $this->assertTrue($dealInsertResult_2_2);
        $this->assertTrue($dealInsertResult_1_0);
        $this->assertTrue($dealInsertResult_2_0);

        /**
         * 登録書類登録テスト
         */
        $archiveInsertResult_2_0 = $documentSaveRepository->archiveInsert($dataArchive_2_0);
        $this->assertTrue($archiveInsertResult_2_0);

    }

    /**
     * @test
     * 更新正常テスト
     */
    public function documentUpdateTest ()
    {
        $this->docContractMock->shouldReceive('update')->once()->andReturn(1);
        $this->docDealMock->shouldReceive('update')->once()->andReturn(1);
        $this->docInternalMock->shouldReceive('update')->once()->andReturn(1);
        $this->docArchiveMock->shouldReceive('update')->once()->andReturn(1);
        $this->docPermissionContractMock->shouldReceive('update')->once()->andReturn(1);
        $this->docPermissionTransactionMock->shouldReceive('update')->once()->andReturn(1);
        $this->docPermissionInternalMock->shouldReceive('update')->once()->andReturn(1);
        $this->docPermissionArchiveMock->shouldReceive('update')->once()->andReturn(1);
        $this->docStorageContractMock->shouldReceive('update')->once()->andReturn(1);
        $this->docStorageTransactionMock->shouldReceive('update')->once()->andReturn(1);
        $this->docStorageInternalMock->shouldReceive('update')->once()->andReturn(1);
        $this->docStorageArchiveMock->shouldReceive('update')->once()->andReturn(1);
        $this->documentWorkFlowMock->shouldReceive('insertContract')->once()->AndReturn(1);
        $this->documentWorkFlowMock->shouldReceive('insertDeal')->once()->AndReturn(1);
        $this->documentWorkFlowMock->shouldReceive('insertInternal')->once()->AndReturn(1);
        $this->documentWorkFlowMock->shouldReceive('insertArchive')->once()->AndReturn(1);
        $this->logDocAccessMock->shouldReceive('insert')->andReturn(1);
        $this->logDocOperationMock->shouldReceive('insert')->andReturn(1);

        $documentSaveRepository = new DocumentSaveRepository(
            $this-> docContractMock,
            $this-> docDealMock,
            $this-> docInternalMock,
            $this-> docArchiveMock,
            $this-> docPermissionContractMock,
            $this-> docPermissionTransactionMock,
            $this-> docPermissionInternalMock,
            $this-> docPermissionArchiveMock,
            $this-> docStorageContractMock,
            $this-> docStorageTransactionMock,
            $this-> docStorageInternalMock,
            $this-> docStorageArchiveMock,
            $this->documentWorkFlowMock,
            $this->logDocAccessMock,
            $this->logDocOperationMock
        );


        /**
         * 契約書類更新
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
            "select_sign_user" => null,
            "select_sign_guest_user" => null,
            "update_user" => 1,
            "update_datetime" => "2022-10-10"
        ];

        /**
         * 取引書類更新
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
            "select_sign_user" => null,
            "select_sign_guest_user" => null,
            "update_user" => 1,
            "update_datetime" => "2022-10-10"
        ];

        /**
         * 社内書類更新
         */
        $dataInternalUpdate = [
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
            "select_sign_user" => null,
            "select_sign_guest_user" => null,
            "update_user" => 1,
            "update_datetime" => "2022-10-10"
        ];

        /**
         * 登録書類更新
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
            "select_sign_user" => null,
            "select_sign_guest_user" => null,
            "update_user" => 1,
            "update_datetime" => "2022-10-10"
        ];

        $contractUpdateResult = $documentSaveRepository->contractUpdate($dataContractUpdate);
        $dealUpdateResult     = $documentSaveRepository->dealUpdate($dataDealUpdate);
        $internalUpdateResult = $documentSaveRepository->internalUpdate($dataInternalUpdate);
        $archiveUpdateResult  = $documentSaveRepository->archiveUpdate($dataArchiveUpdate);
        $this->assertTrue($contractUpdateResult);
        $this->assertTrue($dealUpdateResult);
        $this->assertTrue($internalUpdateResult);
        $this->assertTrue($archiveUpdateResult);
    }
}

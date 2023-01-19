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
use Exception;
use PHPUnit\Framework\TestCase;

class DocumentSaveRepositoryTest extends TestCase
{
    private $docContractMock;
    private $docDealMock;
    private $docInternalMock;
    private $docArchiveMock;
    private $docPermissionContractMock;
    private $docPermissionTransactionMock;
    private $docPermissionInternalMock;
    private $docPermissionArchiveMock;
    private $docStorageContractMock;
    private $docStorageTransactionMock;
    private $docStorageInternalMock;
    private $docStorageArchiveMock;
    private $documentWorkFlowMock;
    private $logDocAccessMock;
    private $logDocOperationMock;
    private $documentSaveRepository;

    public function setUp(): void
    {
        $this->docContractMock              = \Mockery::mock(DocumentContract::class);
        $this->docDealMock                  = \Mockery::mock(DocumentDeal::class);
        $this->docInternalMock              = \Mockery::mock(DocumentInternal::class);
        $this->docArchiveMock               = \Mockery::mock(DocumentArchive::class);
        $this->docPermissionContractMock     = \Mockery::mock(DocumentPermissionContract::class);
        $this->docPermissionTransactionMock = \Mockery::mock(DocumentPermissionTransaction::class);
        $this->docPermissionInternalMock    = \Mockery::mock(DocumentPermissionInternal::class);
        $this->docPermissionArchiveMock     = \Mockery::mock(DocumentPermissionArchive::class);
        $this->docStorageContractMock       = \Mockery::mock(DocumentStorageContract::class);
        $this->docStorageTransactionMock    = \Mockery::mock(DocumentStorageTransaction::class);
        $this->docStorageInternalMock       = \Mockery::mock(DocumentStorageInternal::class);
        $this->docStorageArchiveMock        = \Mockery::mock(DocumentStorageArchive::class);
        $this->documentWorkFlowMock         = \Mockery::mock(DocumentWorkFlow::class);
        $this->logDocAccessMock             = \Mockery::mock(LogDocAccess::class);
        $this->logDocOperationMock          = \Mockery::mock(logDocOperation::class);
        $this->documentSaveRepository       = new DocumentSaveRepository(

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
    }
    public function tearDown(): void
    {
        parent::tearDown();
        \Mockery::close();
    }

    /**
     * @test
     * 契約書類
     * ホスト署名者2
     * ゲスト署名者2
     * 
     * @return void
     */
    public function contractInsert_test_1 ()
    {
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
        $this->docContractMock->shouldReceive('insert')->once()->andReturn(1);
        $this->docPermissionContractMock->shouldReceive('insert')->once()->andReturn(1);
        $this->docStorageContractMock->shouldReceive('insert')->once()->andReturn(1);
        $this->documentWorkFlowMock->shouldReceive('insertContract')->times(4)->AndReturn(4);

        $this->assertTrue($this->documentSaveRepository->contractInsert($dataContract_2_2));

    }

    /**
     * @test
     * 契約書類
     * ホスト署名者2
     * ゲスト署名者2
     * 署名者取得失敗
     * 
     * @return void
     */
    public function contractInsert_test_2 ()
    {
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
        $this->docContractMock->shouldReceive('insert')->once()->andReturn(1);
        $this->docPermissionContractMock->shouldReceive('insert')->once()->andReturn(1);
        $this->docStorageContractMock->shouldReceive('insert')->once()->andReturn(1);
        $this->documentWorkFlowMock->shouldReceive('insertContract')->once()->AndReturn(0);

        $this->expectException(Exception::class);
        $this->assertTrue($this->documentSaveRepository->contractInsert($dataContract_2_2));

    }

    /**
     * @test
     * 契約書類
     * ホスト署名者1
     * ゲスト署名者0
     * 
     * @return void
     */
    public function contractInsert_test_3 ()
    {
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
        
        $this->docContractMock->shouldReceive('insert')->once()->andReturn(1);
        $this->docPermissionContractMock->shouldReceive('insert')->once()->andReturn(1);
        $this->docStorageContractMock->shouldReceive('insert')->once()->andReturn(1);
        $this->documentWorkFlowMock->shouldReceive('insertContract')->once()->AndReturn(3);

        $this->assertTrue($this->documentSaveRepository->contractInsert($dataContract_1_0));

    }

    /**
     * @test
     * 契約書類
     * ホスト署名者1
     * ゲスト署名者0
     * 署名者取得失敗
     * 
     * @return void
     */
    public function contractInsert_test_4 ()
    {
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
        
        $this->docContractMock->shouldReceive('insert')->once()->andReturn(1);
        $this->docPermissionContractMock->shouldReceive('insert')->once()->andReturn(1);
        $this->docStorageContractMock->shouldReceive('insert')->once()->andReturn(1);
        $this->documentWorkFlowMock->shouldReceive('insertContract')->once()->AndReturn(0);

        $this->expectException(Exception::class);
        $this->assertTrue($this->documentSaveRepository->contractInsert($dataContract_1_0));

    }

    /**
     * @test
     * 契約書類
     * ホスト署名者2
     * ゲスト署名者0
     * 
     * @return void
     */
    public function contractInsert_test_5 ()
    {

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
        
        $this->docContractMock->shouldReceive('insert')->once()->andReturn(1);
        $this->docPermissionContractMock->shouldReceive('insert')->once()->andReturn(1);
        $this->docStorageContractMock->shouldReceive('insert')->once()->andReturn(1);
        $this->documentWorkFlowMock->shouldReceive('insertContract')->twice()->AndReturn(2);

        $this->assertTrue($this->documentSaveRepository->contractInsert($dataContract_2_0));

    }

    /**
     * @test
     * 契約書類
     * ホスト署名者2
     * ゲスト署名者0
     * 署名者取得失敗
     * @return void
     */
    public function contractInsert_test_6 ()
    {

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
        
        $this->docContractMock->shouldReceive('insert')->once()->andReturn(1);
        $this->docPermissionContractMock->shouldReceive('insert')->once()->andReturn(1);
        $this->docStorageContractMock->shouldReceive('insert')->once()->andReturn(1);
        $this->documentWorkFlowMock->shouldReceive('insertContract')->once()->AndReturn(0);

        $this->expectException(Exception::class);
        $this->assertTrue($this->documentSaveRepository->contractInsert($dataContract_2_0));

    }

    /**
     * @test
     * 契約書登録失敗
     * 
     * @return void
     */
    public function contractInsert_test_7 ()
    {

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
        
        $this->docContractMock->shouldReceive('insert')->once()->andReturn(0);

        $this->expectException(Exception::class);
        $this->assertTrue($this->documentSaveRepository->contractInsert($dataContract_2_0));

    }
    
    /**
     * @test
     * 契約書類閲覧権限登録失敗
     * 
     * @return void
     */
    public function contractInsert_test_8 ()
    {

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
        
        $this->docContractMock->shouldReceive('insert')->once()->andReturn(1);
        $this->docPermissionContractMock->shouldReceive('insert')->once()->andReturn(0);

        $this->expectException(Exception::class);
        $this->assertTrue($this->documentSaveRepository->contractInsert($dataContract_2_0));

    }

    /**
     * @test
     * 契約書類容量登録失敗
     * 
     * @return void
     */
    public function contractInsert_test_9 ()
    {

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
        
        $this->docContractMock->shouldReceive('insert')->once()->andReturn(1);
        $this->docPermissionContractMock->shouldReceive('insert')->once()->andReturn(1);
        $this->docStorageContractMock->shouldReceive('insert')->once()->andReturn(0);
        $this->documentWorkFlowMock->shouldReceive('insertContract')->once()->AndReturn(2);

        $this->expectException(Exception::class);
        $this->assertTrue($this->documentSaveRepository->contractInsert($dataContract_2_0));

    }



    /**
     * @test
     * 取引書類
     * ホスト署名者2
     * ゲスト署名者2
     * 
     * @return void
     */
    public function dealInsert_test_1 ()
    {
        $dataDeal_2_2 = [
            "m_user_id" => 1,
            "m_user_company_id" => 1,
            "m_user_company_id" => 1,
            "m_user_type_id" => 0,
            "company_id" => 1,
            "category_id" => 1,
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
        $this->docDealMock->shouldReceive('insert')->once()->andReturn(1);
        $this->docPermissionTransactionMock->shouldReceive('insert')->once()->andReturn(1);
        $this->docStorageTransactionMock->shouldReceive('insert')->once()->andReturn(1);
        $this->documentWorkFlowMock->shouldReceive('insertDeal')->once()->AndReturn(4);

        $this->assertTrue($this->documentSaveRepository->dealInsert($dataDeal_2_2));

    }

    /**
     * @test
     * 取引書類
     * ホスト署名者2
     * ゲスト署名者2
     * 署名者取得失敗
     * 
     * @return void
     */
    public function dealInsert_test_2 ()
    {
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
        $this->docDealMock->shouldReceive('insert')->once()->andReturn(1);
        $this->docPermissionTransactionMock->shouldReceive('insert')->once()->andReturn(1);
        $this->docStorageTransactionMock->shouldReceive('insert')->once()->andReturn(1);
        $this->documentWorkFlowMock->shouldReceive('insertDeal')->once()->AndReturn(0);

        $this->expectException(Exception::class);
        $this->assertTrue($this->documentSaveRepository->dealInsert($dataDeal_2_2));

    }

    /**
     * @test
     * 取引書類
     * ホスト署名者1
     * ゲスト署名者0
     * 
     * @return void
     */
    public function dealInsert_test_3 ()
    {
        $dataDeal_1_0 = [
            "m_user_id" => 1,
            "m_user_company_id" => 1,
            "m_user_company_id" => 1,
            "m_user_type_id" => 0,
            "company_id" => 1,
            "category_id" => 1,
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
        
        $this->docDealMock->shouldReceive('insert')->once()->andReturn(1);
        $this->docPermissionTransactionMock->shouldReceive('insert')->once()->andReturn(1);
        $this->docStorageTransactionMock->shouldReceive('insert')->once()->andReturn(1);
        $this->documentWorkFlowMock->shouldReceive('insertDeal')->once()->AndReturn(1);

        $this->assertTrue($this->documentSaveRepository->dealInsert($dataDeal_1_0));

    }

    /**
     * @test
     * 取引書類
     * ホスト署名者1
     * ゲスト署名者0
     * 署名者取得失敗
     * 
     * @return void
     */
    public function dealInsert_test_4 ()
    {
        $dataDeal_1_0 = [
            "m_user_id" => 1,
            "m_user_company_id" => 1,
            "m_user_company_id" => 1,
            "m_user_type_id" => 0,
            "company_id" => 1,
            "category_id" => 1,
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
        
        $this->docDealMock->shouldReceive('insert')->once()->andReturn(1);
        $this->docPermissionTransactionMock->shouldReceive('insert')->once()->andReturn(1);
        $this->docStorageTransactionMock->shouldReceive('insert')->once()->andReturn(1);
        $this->documentWorkFlowMock->shouldReceive('insertDeal')->once()->AndReturn(0);

        $this->expectException(Exception::class);
        $this->assertTrue($this->documentSaveRepository->dealInsert($dataDeal_1_0));

    }

    /**
     * @test
     * 取引書類
     * ホスト署名者2
     * ゲスト署名者0
     * 
     * @return void
     */
    public function dealInsert_test_5 ()
    {

        $dataDeal_2_0 = [
            "m_user_id" => 1,
            "m_user_company_id" => 1,
            "m_user_company_id" => 1,
            "m_user_type_id" => 0,
            "company_id" => 1,
            "category_id" => 1,
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
        
        $this->docDealMock->shouldReceive('insert')->once()->andReturn(1);
        $this->docPermissionTransactionMock->shouldReceive('insert')->once()->andReturn(1);
        $this->docStorageTransactionMock->shouldReceive('insert')->once()->andReturn(1);
        $this->documentWorkFlowMock->shouldReceive('insertDeal')->once()->AndReturn(2);

        $this->assertTrue($this->documentSaveRepository->DealInsert($dataDeal_2_0));

    }

    /**
     * @test
     * 取引書類
     * ホスト署名者2
     * ゲスト署名者0
     * 署名者取得失敗
     * @return void
     */
    public function dealInsert_test_6 ()
    {

        $dataDeal_2_0 = [
            "m_user_id" => 1,
            "m_user_company_id" => 1,
            "m_user_company_id" => 1,
            "m_user_type_id" => 0,
            "company_id" => 1,
            "category_id" => 1,
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
        
        $this->docDealMock->shouldReceive('insert')->once()->andReturn(1);
        $this->docPermissionTransactionMock->shouldReceive('insert')->once()->andReturn(1);
        $this->docStorageTransactionMock->shouldReceive('insert')->once()->andReturn(1);
        $this->documentWorkFlowMock->shouldReceive('inserDeal')->once()->AndReturn(0);

        $this->expectException(Exception::class);
        $this->assertTrue($this->documentSaveRepository->dealInsert($dataDeal_2_0));

    }

    /**
     * @test
     * 取引書登録失敗
     * 
     * @return void
     */
    public function dealInsert_test_7 ()
    {

        $dataDeal_2_0 = [
            "m_user_id" => 1,
            "m_user_company_id" => 1,
            "m_user_company_id" => 1,
            "m_user_type_id" => 0,
            "company_id" => 1,
            "category_id" => 1,
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
        
        $this->docDealMock->shouldReceive('insert')->once()->andReturn(0);
        $this->docPermissionTransactionMock->shouldReceive('insert')->once()->andReturn(1);
        $this->docStorageTransactionMock->shouldReceive('insert')->once()->andReturn(1);
        $this->documentWorkFlowMock->shouldReceive('insertDeal')->once()->AndReturn(2);

        $this->expectException(Exception::class);
        $this->assertTrue($this->documentSaveRepository->dealInsert($dataDeal_2_0));

    }
    
    /**
     * @test
     * 取引書類閲覧権限登録失敗
     * 
     * @return void
     */
    public function dealInsert_test_8 ()
    {

        $dataDeal_2_0 = [
            "m_user_id" => 1,
            "m_user_company_id" => 1,
            "m_user_company_id" => 1,
            "m_user_type_id" => 0,
            "company_id" => 1,
            "category_id" => 1,
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
        
        $this->docDealMock->shouldReceive('insert')->once()->andReturn(1);
        $this->docPermissionTransactionMock->shouldReceive('insert')->once()->andReturn(0);
        $this->docStorageTransactionMock->shouldReceive('insert')->once()->andReturn(1);
        $this->documentWorkFlowMock->shouldReceive('insertDeal')->once()->AndReturn(2);

        $this->expectException(Exception::class);
        $this->assertTrue($this->documentSaveRepository->dealInsert($dataDeal_2_0));

    }

    /**
     * @test
     * 契約書類容量登録失敗
     * 
     * @return void
     */
    public function dealInsert_test_9 ()
    {

        $dataDeal_2_0 = [
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
        
        $this->docDealMock->shouldReceive('insert')->once()->andReturn(1);
        $this->docPermissionTransactionMock->shouldReceive('insert')->once()->andReturn(1);
        $this->docStorageTransactionMock->shouldReceive('insert')->once()->andReturn(0);
        $this->documentWorkFlowMock->shouldReceive('insertDeal')->once()->AndReturn(2);

        $this->expectException(Exception::class);
        $this->assertTrue($this->documentSaveRepository->dealInsert($dataDeal_2_0));

    }


    /**
     * @test
     * 社内書類
     * ホスト署名者1
     * ゲスト署名者0
     * 
     * @return void
     */
    public function internalInsert_test_1 ()
    {
        $dataInternal_1_0 = [
            "m_user_id" => 1,
            "m_user_company_id" => 1,
            "m_user_company_id" => 1,
            "m_user_type_id" => 0,
            "company_id" => 1,
            "category_id" => 2,
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
        
        $this->docInternalMock->shouldReceive('insert')->once()->andReturn(1);
        $this->docPermissionInternalMock->shouldReceive('insert')->once()->andReturn(1);
        $this->docStorageInternalMock->shouldReceive('insert')->once()->andReturn(1);
        $this->documentWorkFlowMock->shouldReceive('insertInternal')->once()->AndReturn(1);

        $this->assertTrue($this->documentSaveRepository->internalInsert($dataInternal_1_0));

    }

    /**
     * @test
     * 社内書類
     * ホスト署名者1
     * ゲスト署名者0
     * 署名者取得失敗
     * 
     * @return void
     */
    public function internalInsert_test_2 ()
    {
        $dataInternal_1_0 = [
            "m_user_id" => 1,
            "m_user_company_id" => 1,
            "m_user_company_id" => 1,
            "m_user_type_id" => 0,
            "company_id" => 1,
            "category_id" => 2,
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
        
        $this->docInternalMock->shouldReceive('insert')->once()->andReturn(1);
        $this->docPermissionInternalMock->shouldReceive('insert')->once()->andReturn(1);
        $this->docStorageInternalMock->shouldReceive('insert')->once()->andReturn(1);
        $this->documentWorkFlowMock->shouldReceive('insertInternal')->once()->AndReturn(0);

        $this->expectException(Exception::class);
        $this->assertTrue($this->documentSaveRepository->internalInsert($dataInternal_1_0));

    }

    /**
     * @test
     * 社内書類
     * ホスト署名者２
     * ゲスト署名者0
     * 
     * @return void
     */
    public function internalInsert_test_3 ()
    {
        $dataInternal_2_0 = [
            "m_user_id" => 1,
            "m_user_company_id" => 1,
            "m_user_company_id" => 1,
            "m_user_type_id" => 0,
            "company_id" => 1,
            "category_id" => 2,
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
        
        $this->docInternalMock->shouldReceive('insert')->once()->andReturn(1);
        $this->docPermissionInternalMock->shouldReceive('insert')->once()->andReturn(1);
        $this->docStorageInternalMock->shouldReceive('insert')->once()->andReturn(1);
        $this->documentWorkFlowMock->shouldReceive('insertInternal')->once()->AndReturn(2);

        $this->assertTrue($this->documentSaveRepository->internalInsert($dataInternal_2_0));

    }

    /**
     * @test
     * 社内書類
     * ホスト署名者２
     * ゲスト署名者0
     * 署名者取得失敗
     * @return void
     */
    public function internalInsert_test_4 ()
    {
        $dataInternal_2_0 = [
            "m_user_id" => 1,
            "m_user_company_id" => 1,
            "m_user_company_id" => 1,
            "m_user_type_id" => 0,
            "company_id" => 1,
            "category_id" => 2,
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
        
        $this->docInternalMock->shouldReceive('insert')->once()->andReturn(1);
        $this->docPermissionInternalMock->shouldReceive('insert')->once()->andReturn(1);
        $this->docStorageInternalMock->shouldReceive('insert')->once()->andReturn(1);
        $this->documentWorkFlowMock->shouldReceive('insertInternal')->once()->AndReturn(0);

        $this->expectException(Exception::class);
        $this->assertTrue($this->documentSaveRepository->internalInsert($dataInternal_2_0));

    }

    /**
     * @test
     * 社内書登録失敗
     * 
     * @return void
     */
    public function internalInsert_test_5 ()
    {

        $dataInternal_2_0 = [
            "m_user_id" => 1,
            "m_user_company_id" => 1,
            "m_user_company_id" => 1,
            "m_user_type_id" => 0,
            "company_id" => 1,
            "category_id" => 2,
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
        
        $this->docInternalMock->shouldReceive('insert')->once()->andReturn(0);
        $this->docPermissionInternalMock->shouldReceive('insert')->once()->andReturn(1);
        $this->docStorageInternalMock->shouldReceive('insert')->once()->andReturn(1);
        $this->documentWorkFlowMock->shouldReceive('insertInternal')->once()->AndReturn(2);

        $this->expectException(Exception::class);
        $this->assertTrue($this->documentSaveRepository->internalInsert($dataInternal_2_0));

    }

    /**
     * @test
     * 社内書類閲覧権限登録失敗
     * 
     * @return void
     */
    public function internalInsert_test_6 ()
    {

        $dataInternal_2_0 = [
            "m_user_id" => 1,
            "m_user_company_id" => 1,
            "m_user_company_id" => 1,
            "m_user_type_id" => 0,
            "company_id" => 1,
            "category_id" => 2,
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
        
        $this->docInternalMock->shouldReceive('insert')->once()->andReturn(1);
        $this->docPermissionInternalMock->shouldReceive('insert')->once()->andReturn(0);
        $this->docStorageInternalMock->shouldReceive('insert')->once()->andReturn(1);
        $this->documentWorkFlowMock->shouldReceive('insertInternal')->once()->AndReturn(2);

        $this->expectException(Exception::class);
        $this->assertTrue($this->documentSaveRepository->internalInsert($dataInternal_2_0));

    }

    /**
     * @test
     * 社内書類容量登録失敗
     * 
     * @return void
     */
    public function internalInsert_test_7 ()
    {

        $dataInternal_2_0 = [
            "m_user_id" => 1,
            "m_user_company_id" => 1,
            "m_user_company_id" => 1,
            "m_user_type_id" => 0,
            "company_id" => 1,
            "category_id" => 2,
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
        
        $this->docInternalMock->shouldReceive('insert')->once()->andReturn(1);
        $this->docPermissionInternalMock->shouldReceive('insert')->once()->andReturn(1);
        $this->docStorageInternalMock->shouldReceive('insert')->once()->andReturn(0);
        $this->documentWorkFlowMock->shouldReceive('insertInternal')->once()->AndReturn(2);

        $this->expectException(Exception::class);
        $this->assertTrue($this->documentSaveRepository->internalInsert($dataInternal_2_0));

    }



    /**
     * @test
     * 登録書類
     * ホスト署名者1
     * ゲスト署名者0
     * 
     * @return void
     */
    public function archiveInsert_test_1 ()
    {
        $dataArchive_1_0 = [
            "m_user_id" => 1,
            "m_user_company_id" => 1,
            "m_user_company_id" => 1,
            "m_user_type_id" => 0,
            "company_id" => 1,
            "category_id" => 3,
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
        
        $this->docArchiveMock->shouldReceive('insert')->once()->andReturn(1);
        $this->docPermissionArchiveMock->shouldReceive('insert')->once()->andReturn(1);
        $this->docStorageArchiveMock->shouldReceive('insert')->once()->andReturn(1);
        $this->documentWorkFlowMock->shouldReceive('insertArchive')->once()->AndReturn(1);

        $this->assertTrue($this->documentSaveRepository->archiveInsert($dataArchive_1_0));

    }

    /**
     * @test
     * 登録書類
     * ホスト署名者1
     * ゲスト署名者0
     * 署名者取得失敗
     * 
     * @return void
     */
    public function archiveInsert_test_2 ()
    {
        $dataArchive_1_0 = [
            "m_user_id" => 1,
            "m_user_company_id" => 1,
            "m_user_company_id" => 1,
            "m_user_type_id" => 0,
            "company_id" => 1,
            "category_id" => 3,
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
        
        $this->docArchiveMock->shouldReceive('insert')->once()->andReturn(1);
        $this->docPermissionArchiveMock->shouldReceive('insert')->once()->andReturn(1);
        $this->docStorageArchiveMock->shouldReceive('insert')->once()->andReturn(1);
        $this->documentWorkFlowMock->shouldReceive('insertArchive')->once()->AndReturn(0);

        $this->expectException(Exception::class);
        $this->assertTrue($this->documentSaveRepository->archiveInsert($dataArchive_1_0));

    }

    /**
     * @test
     * 登録書類
     * ホスト署名者２
     * ゲスト署名者0
     * 
     * @return void
     */
    public function archiveInsert_test_3 ()
    {
        $dataArchive_2_0 = [
            "m_user_id" => 1,
            "m_user_company_id" => 1,
            "m_user_company_id" => 1,
            "m_user_type_id" => 0,
            "company_id" => 1,
            "category_id" => 3,
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
        
        $this->docArchiveMock->shouldReceive('insert')->once()->andReturn(1);
        $this->docPermissionArchiveMock->shouldReceive('insert')->once()->andReturn(1);
        $this->docStorageArchiveMock->shouldReceive('insert')->once()->andReturn(1);
        $this->documentWorkFlowMock->shouldReceive('insertArchive')->once()->AndReturn(2);

        $this->assertTrue($this->documentSaveRepository->archiveInsert($dataArchive_2_0));

    }

    /**
     * @test
     * 取引書類
     * ホスト署名者２
     * ゲスト署名者0
     * 署名者取得失敗
     * @return void
     */
    public function archiveInsert_test_4 ()
    {
        $dataArchive_2_0 = [
            "m_user_id" => 1,
            "m_user_company_id" => 1,
            "m_user_company_id" => 1,
            "m_user_type_id" => 0,
            "company_id" => 1,
            "category_id" => 3,
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
        
        $this->docArchiveMock->shouldReceive('insert')->once()->andReturn(1);
        $this->docPermissionArchiveMock->shouldReceive('insert')->once()->andReturn(1);
        $this->docStorageArchiveMock->shouldReceive('insert')->once()->andReturn(1);
        $this->documentWorkFlowMock->shouldReceive('insertArchive')->once()->AndReturn(0);

        $this->expectException(Exception::class);
        $this->assertTrue($this->documentSaveRepository->archiveInsert($dataArchive_2_0));

    }

    /**
     * @test
     * 取引書登録失敗
     * 
     * @return void
     */
    public function archiveInsert_test_5 ()
    {

        $dataArchive_2_0 = [
            "m_user_id" => 1,
            "m_user_company_id" => 1,
            "m_user_company_id" => 1,
            "m_user_type_id" => 0,
            "company_id" => 1,
            "category_id" => 3,
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
        
        $this->docArchiveMock->shouldReceive('insert')->once()->andReturn(0);
        $this->docPermissionArchiveMock->shouldReceive('insert')->once()->andReturn(1);
        $this->docStorageArchiveMock->shouldReceive('insert')->once()->andReturn(1);
        $this->documentWorkFlowMock->shouldReceive('insertArchive')->once()->AndReturn(2);

        $this->expectException(Exception::class);
        $this->assertTrue($this->documentSaveRepository->archiveInsert($dataArchive_2_0));

    }

    /**
     * @test
     * 取引書類閲覧権限登録失敗
     * 
     * @return void
     */
    public function archiveInsert_test_6 ()
    {

        $dataArchive_2_0 = [
            "m_user_id" => 1,
            "m_user_company_id" => 1,
            "m_user_company_id" => 1,
            "m_user_type_id" => 0,
            "company_id" => 1,
            "category_id" => 3,
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
        
        $this->docArchiveMock->shouldReceive('insert')->once()->andReturn(1);
        $this->docPermissionArchiveMock->shouldReceive('insert')->once()->andReturn(0);
        $this->docStorageArchiveMock->shouldReceive('insert')->once()->andReturn(1);
        $this->documentWorkFlowMock->shouldReceive('insertArchive')->once()->AndReturn(2);

        $this->expectException(Exception::class);
        $this->assertTrue($this->documentSaveRepository->archiveInsert($dataArchive_2_0));

    }

    /**
     * @test
     * 登録書類容量登録失敗
     * 
     * @return void
     */
    public function archiveInsert_test_7 ()
    {

        $dataArchive_2_0 = [
            "m_user_id" => 1,
            "m_user_company_id" => 1,
            "m_user_company_id" => 1,
            "m_user_type_id" => 0,
            "company_id" => 1,
            "category_id" => 3,
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
        
        $this->docArchiveMock->shouldReceive('insert')->once()->andReturn(1);
        $this->docPermissionArchiveMock->shouldReceive('insert')->once()->andReturn(1);
        $this->docStorageArchiveMock->shouldReceive('insert')->once()->andReturn(0);
        $this->documentWorkFlowMock->shouldReceive('insertArchive')->once()->AndReturn(2);

        $this->expectException(Exception::class);
        $this->assertTrue($this->documentSaveRepository->archiveInsert($dataArchive_2_0));

    }


    /**
     * @test
     * 契約書類更新
     * 
     * @return void
     */
    public function contractUpdate_test_success()
    {
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
        $this->docContractMock->shouldReceive('update')->once()->andReturn(1);
        $this->docPermissionContractMock->shouldReceive('update')->once()->andReturn(1);
        $this->docStorageContractMock->shouldReceive('update')->once()->andReturn(1);

        $this->assertTrue($this->documentSaveRepository->contractUpdate($dataContractUpdate));
    }



    /**
     * @test
     * 契約書類更新
     * 契約書類登録失敗
     * @return void
     */
    public function contractUpdate_test_1()
    {
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
        $this->docContractMock->shouldReceive('update')->once()->andReturn(0);
        $this->docPermissionContractMock->shouldReceive('update')->once()->andReturn(1);
        $this->docStorageContractMock->shouldReceive('update')->once()->andReturn(1);

        $this->expectException(Exception::class);
        $this->documentSaveRepository->contractUpdate($dataContractUpdate);
    }
    
    /**
     * @test
     * 契約書類更新
     * 契約書類閲覧権限登録失敗
     * @return void
     */
    public function contractUpdate_test_2()
    {
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
        $this->docContractMock->shouldReceive('update')->once()->andReturn(1);
        $this->docPermissionContractMock->shouldReceive('update')->once()->andReturn(0);
        $this->docStorageContractMock->shouldReceive('update')->once()->andReturn(1);

        $this->expectException(Exception::class);
        $this->documentSaveRepository->contractUpdate($dataContractUpdate);
    }

    /**
     * @test
     * 契約書類更新
     * 契約書類容量登録失敗
     * @return void
     */
    public function contractUpdate_test_3()
    {
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
        $this->docContractMock->shouldReceive('update')->once()->andReturn(1);
        $this->docPermissionContractMock->shouldReceive('update')->once()->andReturn(1);
        $this->docStorageContractMock->shouldReceive('update')->once()->andReturn(0);

        $this->expectException(Exception::class);
        $this->documentSaveRepository->contractUpdate($dataContractUpdate);
    }

    /**
     * @test
     * 取引書類更新
     * 
     * @return void
     */
    public function dealUpdate_test_success()
    {
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
        $this->docDealMock->shouldReceive('update')->once()->andReturn(1);
        $this->docPermissionTransactionMock->shouldReceive('update')->once()->andReturn(1);
        $this->docStorageTransactionMock->shouldReceive('update')->once()->andReturn(1);

        $this->assertTrue($this->documentSaveRepository->dealUpdate($dataDealUpdate));
    }
    /**
     * @test
     * 取引書類更新
     * 取引書類登録失敗
     * @return void
     */
    public function dealUpdate_test_1()
    {
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
        $this->docDealMock->shouldReceive('update')->once()->andReturn(0);
        $this->docPermissionTransactionMock->shouldReceive('update')->once()->andReturn(1);
        $this->docStorageTransactionMock->shouldReceive('update')->once()->andReturn(1);

        $this->expectException(Exception::class);
        $this->documentSaveRepository->dealUpdate($dataDealUpdate);
    }
    
    /**
     * @test
     * 取引書類更新
     * 取引書類閲覧権限登録失敗
     * @return void
     */
    public function dealUpdate_test_2()
    {
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
        $this->docDealMock->shouldReceive('update')->once()->andReturn(1);
        $this->docPermissionTransactionMock->shouldReceive('update')->once()->andReturn(0);
        $this->docStorageTransactionMock->shouldReceive('update')->once()->andReturn(1);

        $this->expectException(Exception::class);
        $this->documentSaveRepository->dealUpdate($dataDealUpdate);
    }

    /**
     * @test
     * 取引書類更新
     * 取引書類容量登録失敗
     * @return void
     */
    public function dealUpdate_test_3()
    {
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
        $this->docDealMock->shouldReceive('update')->once()->andReturn(1);
        $this->docPermissionTransactionMock->shouldReceive('update')->once()->andReturn(1);
        $this->docStorageTransactionMock->shouldReceive('update')->once()->andReturn(0);

        $this->expectException(Exception::class);
        $this->documentSaveRepository->dealUpdate($dataDealUpdate);
    }

    /**
     * @test
     * 社内書類更新
     * 
     * @return void
     */
    public function internalUpdate_test_success()
    {
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
            "select_sign_user" => null,
            "select_sign_guest_user" => null,
            "update_user" => 1,
            "update_datetime" => "2022-10-10"
        ];
        $this->docInternalMock->shouldReceive('update')->once()->andReturn(1);
        $this->docPermissionInternalMock->shouldReceive('update')->once()->andReturn(1);
        $this->docStorageInternalMock->shouldReceive('update')->once()->andReturn(1);

        $this->assertTrue($this->documentSaveRepository->internalUpdate($dataInternalUpdate));
    }

    /**
     * @test
     * 社内書類更新
     * 社内書類登録失敗
     * @return void
     */
    public function internalUpdate_test_1()
    {
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
            "select_sign_user" => null,
            "select_sign_guest_user" => null,
            "update_user" => 1,
            "update_datetime" => "2022-10-10"
        ];
        $this->docInternalMock->shouldReceive('update')->once()->andReturn(0);
        $this->docPermissionInternalMock->shouldReceive('update')->once()->andReturn(1);
        $this->docStorageInternalMock->shouldReceive('update')->once()->andReturn(1);

        $this->expectException(Exception::class);
        $this->documentSaveRepository->internalUpdate($dataInternalUpdate);
    }
    
    /**
     * @test
     * 社内書類更新
     * 社内書類閲覧権限登録失敗
     * @return void
     */
    public function internalUpdate_test_2()
    {
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
            "select_sign_user" => null,
            "select_sign_guest_user" => null,
            "update_user" => 1,
            "update_datetime" => "2022-10-10"
        ];
        $this->docInternalMock->shouldReceive('update')->once()->andReturn(1);
        $this->docPermissionInternalMock->shouldReceive('update')->once()->andReturn(0);
        $this->docStorageInternalMock->shouldReceive('update')->once()->andReturn(1);

        $this->expectException(Exception::class);
        $this->documentSaveRepository->internalUpdate($dataInternalUpdate);
    }

    /**
     * @test
     * 社内書類更新
     * 社内書類容量登録失敗
     * @return void
     */
    public function internalUpdate_test_3()
    {
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
            "select_sign_user" => null,
            "select_sign_guest_user" => null,
            "update_user" => 1,
            "update_datetime" => "2022-10-10"
        ];
        $this->docInternalMock->shouldReceive('update')->once()->andReturn(1);
        $this->docPermissionInternalMock->shouldReceive('update')->once()->andReturn(1);
        $this->docStorageInternalMock->shouldReceive('update')->once()->andReturn(0);

        $this->expectException(Exception::class);
        $this->documentSaveRepository->internalUpdate($dataInternalUpdate);
    }

    /**
     * @test
     * 登録書類更新
     * @return void
     */
    public function archiveUpdate_test_success()
    {
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
        $this->docArchiveMock->shouldReceive('update')->once()->andReturn(1);
        $this->docPermissionArchiveMock->shouldReceive('update')->once()->andReturn(1);
        $this->docStorageArchiveMock->shouldReceive('update')->once()->andReturn(1);

        $this->assertTrue($this->documentSaveRepository->archiveUpdate($dataArchiveUpdate));
    }


    /**
     * @test
     * 登録書類更新
     * 登録書類登録失敗
     * @return void
     */
    public function archiveUpdate_test_1()
    {
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
        $this->docArchiveMock->shouldReceive('update')->once()->andReturn(0);
        $this->docPermissionArchiveMock->shouldReceive('update')->once()->andReturn(1);
        $this->docStorageArchiveMock->shouldReceive('update')->once()->andReturn(1);

        $this->expectException(Exception::class);
        $this->documentSaveRepository->archiveUpdate($dataArchiveUpdate);
    }
    
    /**
     * @test
     * 登録書類更新
     * 登録書類閲覧権限登録失敗
     * @return void
     */
    public function archiveUpdate_test_2()
    {
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
        $this->docArchiveMock->shouldReceive('update')->once()->andReturn(1);
        $this->docPermissionArchiveMock->shouldReceive('update')->once()->andReturn(0);
        $this->docStorageArchiveMock->shouldReceive('update')->once()->andReturn(1);

        $this->expectException(Exception::class);
        $this->documentSaveRepository->archiveUpdate($dataArchiveUpdate);
    }

    /**
     * @test
     * 登録書類更新
     * 登録書類容量登録失敗
     * @return void
     */
    public function archiveUpdate_test_3()
    {
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
        $this->docArchiveMock->shouldReceive('update')->once()->andReturn(1);
        $this->docPermissionArchiveMock->shouldReceive('update')->once()->andReturn(1);
        $this->docStorageArchiveMock->shouldReceive('update')->once()->andReturn(0);

        $this->expectException(Exception::class);
        $this->documentSaveRepository->archiveUpdate($dataArchiveUpdate);
    }

}
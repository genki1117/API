<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Document\DocumentGetListRequest;
use App\Http\Responses\Document\DocumentGetListResponse;
// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiDocumentGetListTest extends TestCase
{    
    // データ1件のみをチェックする
    public function testGetList()
    {
        $request = [
            "m_use" => [
                "company_id" => 1,
                "user_id" => 1
            ],
            "condition" => [
                "search_input" => "test",
                "status_id" => 0,
                "category_id" => 0,
                "register_type_id" => 1,
                "title" => "test",
                "contract_name" => "",
                "amount" => [
                    "from" => 25000,
                    "to" => 250
                ],
                "product_name"=> "test",
                "document_id" => 1,
                "doc_no" => "test123",
                "ref_doc_no" => "test123",
                "doc_info" => [
                    "title" => "title",
                    "content" => "amount"
                ],
                "create_datetime" => [
                    "from" => "2022-11-01",
                    "to" => "2022-12-15"
                ],
                "contract_start_date" => [
                    "from" => "2022-11-01",
                    "to" => "2022-12-15"
                ],
                "contract_end_date" => [
                    "from" => "2022-11-01",
                    "to" => "2022-12-15"
                ],
                "conc_date" => [
                    "from" => "2022-11-01",
                    "to" => "2022-12-15"
                ],
                "effective_date" => [
                    "from" => "2022-11-01",
                    "to" => "2022-12-15"
                ],
                "remarks" => "test",
                "app_user_id" => 1,
                "app_user_id_guest" => 1,
                "view_permission_user_id" => 1,
                "counter_party_name" => "test",
                "issue_date" => [
                    "from" => "2022-11-01",
                    "to" => "2022-12-15"
                ],
                "expiry_date" => [
                    "from" => "2022-11-01",
                    "to" => "2022-12-15"
                ],
                "payment_date" => [
                    "from" => "2022-11-01",
                    "to" => "2022-12-15"
                ],
                "transaction_date" => [
                    "from" => "2022-11-01",
                    "to" => "2022-12-15"
                ],
                "download_date" => [
                    "from" => "2022-11-01",
                    "to" => "2022-12-15"
                ],
                "doc_type_id" => 1,
                "doc_create_date" => [
                    "from" => "2022-11-01",
                    "to" => "2022-12-15"
                ],
                "sign_finish_date" => [
                    "from" => "2022-11-01",
                    "to" => "2022-12-15"
                ],
                "scan_doc_flg" => 0,
                "timestamp_user" => 1,
                "currency_id" => 1,
                "content" => "amount"
            ],
            "cindition" => [
                "currency_id" => 1
            ],
            "sort" => [
                "column_name" => "document_id",
                "sort_type" => "asc"
            ],
            "page" => [
                "disp_count" => 10,
                "disp_page" => 0
            ]
        ];
        $response = $this->post('/document/list', $request);
        $responseData = json_decode($response->content(), true);
        $this->assertIsArray($responseData);
        $this->assertArrayHasKey('data', $responseData);
        $this->assertArrayHasKey('contents', $responseData['data']);
        $this->assertArrayHasKey('total', $responseData);
        $this->assertArrayHasKey('per_page', $responseData);
        $this->assertArrayHasKey('current_page', $responseData);
        $this->assertEquals(1, $responseData['data']['contents'][0]['document_id']);
        $this->assertEquals(1, $responseData['data']['contents'][0]['category_id']);
        $this->assertEquals("2022-11-15", $responseData['data']['contents'][0]['transaction_date']);
        $this->assertEquals(1, $responseData['data']['contents'][0]['doc_type_id']);
        $this->assertEquals("test123456", $responseData['data']['contents'][0]['title']);
        $this->assertEquals("2500.000000", $responseData['data']['contents'][0]['amount']);
        $this->assertEquals(1, $responseData['data']['contents'][0]['currency_id']);
        $this->assertEquals(0, $responseData['data']['contents'][0]['status_id']);
        $this->assertEquals(1670415861, $responseData['data']['contents'][0]['UNIX_TIMESTAMP(t_document_contract.update_datetime)']);
        $this->assertEquals(1, $responseData['data']['contents'][0]['app_status']);
        $this->assertEquals("test", $responseData['data']['contents'][0]['create_user']);
        $this->assertEquals("1 testData,test api", $responseData['data']['contents'][0]['counter_party_name']);
        $this->assertEquals(1, $responseData['total']);
        $this->assertEquals(0, $responseData['per_page']);
        $this->assertEquals(10, $responseData['current_page']);
    }

    public function testRequired_category_id()
    {
        $request = [
            "m_use" => [
                "company_id" => 1,
                "user_id" => 1
            ],
            "condition" => [
                "category_id" => ""
            ],
            "sort" => [
                "column_name" => "document_id",
                "sort_type" => "asc"
            ],
            "page" => [
                "disp_count" => 1,
                "disp_page_count" => 1
            ]
        ];
        $response = $this->post('/document/list', $request);
        $this->withoutExceptionHandling();
        $response->assertStatus(500);
    }

    public function testNumber_category_id()
    {
        $request = [
            "m_use" => [
                "company_id" => 1,
                "user_id" => 1
            ],
            "condition" => [
                "category_id" => 1
            ],
            "sort" => [
                "column_name" => "document_id",
                "sort_type" => "asc"
            ],
            "page" => [
                "disp_count" => 1,
                "disp_page_count" => 1
            ]
        ];
        $response = $this->post('/document/list', $request);
        $this->withoutExceptionHandling();
        $response->assertStatus(500);
    }

    public function testString_search_input()
    {
        $request = [
            "m_use" => [
                "company_id" => 1,
                "user_id" => 1
            ],
            "condition" => [
                "category_id" => 1,
                "search_input" => "テスト"
            ],
            "sort" => [
                "column_name" => "document_id",
                "sort_type" => "asc"
            ],
            "page" => [
                "disp_count" => 1,
                "disp_page_count" => 1
            ]
        ];
        $response = $this->post('/document/list', $request);
        $this->withoutExceptionHandling();
        $response->assertStatus(500);
    }

    public function testString_transaction_date()
    {
        $request = [
            "m_use" => [
                "company_id" => 1,
                "user_id" => 1
            ],
            "condition" => [
                "category_id" => 1,
                "transaction_date" => [
                    "from" => 1669569218,
                    "to" => 1669669218
                ],
            ],
            "sort" => [
                "column_name" => "document_id",
                "sort_type" => "asc"
            ],
            "page" => [
                "disp_count" => 1,
                "disp_page_count" => 1
            ]
        ];
        $response = $this->post('/document/list', $request);
        $this->withoutExceptionHandling();
        $response->assertStatus(400);
    }

    public function testFromTo_transaction_date()
    {
        $request = [
            "m_use" => [
                "company_id" => 1,
                "user_id" => 1
            ],
            "condition" => [
                "category_id" => 1,
                "transaction_date" => [
                    "from" => "2022-11-01",
                    "to" => "2022-12-15"
                ],
            ],
            "sort" => [
                "column_name" => "document_id",
                "sort_type" => "asc"
            ],
            "page" => [
                "disp_count" => 1,
                "disp_page_count" => 1
            ]
        ];
        $response = $this->post('/document/list', $request);
        $this->withoutExceptionHandling();
        $response->assertStatus(500);
    }

    public function testNumber_status_id()
    {
        $request = [
            "m_use" => [
                "company_id" => 1,
                "user_id" => 1
            ],
            "condition" => [
                "category_id" => 1,
                "status_id" => 1,
            ],
            "sort" => [
                "column_name" => "document_id",
                "sort_type" => "asc"
            ],
            "page" => [
                "disp_count" => 1,
                "disp_page_count" => 1
            ]
        ];
        $response = $this->post('/document/list', $request);
        $this->withoutExceptionHandling();
        $response->assertStatus(500);
    }

    public function testNumber_doc_type_id()
    {
        $request = [
            "m_use" => [
                "company_id" => 1,
                "user_id" => 1
            ],
            "condition" => [
                "category_id" => 1,
                "doc_type_id" => 1,
            ],
            "sort" => [
                "column_name" => "document_id",
                "sort_type" => "asc"
            ],
            "page" => [
                "disp_count" => 1,
                "disp_page_count" => 1
            ]
        ];
        $response = $this->post('/document/list', $request);
        $this->withoutExceptionHandling();
        $response->assertStatus(500);
    }

    public function testString_title()
    {
        $request = [
            "m_use" => [
                "company_id" => 1,
                "user_id" => 1
            ],
            "condition" => [
                "category_id" => 1,
                "title" => "タイトル",
            ],
            "sort" => [
                "column_name" => "document_id",
                "sort_type" => "asc"
            ],
            "page" => [
                "disp_count" => 1,
                "disp_page_count" => 1
            ]
        ];
        $response = $this->post('/document/list', $request);
        $this->withoutExceptionHandling();
        $response->assertStatus(500);
    }

    public function testMax_title()
    {
        $request = [
            "m_use" => [
                "company_id" => 1,
                "user_id" => 1
            ],
            "condition" => [
                "category_id" => 1,
                "title" => "テストタイトル",
            ],
            "sort" => [
                "column_name" => "document_id",
                "sort_type" => "asc"
            ],
            "page" => [
                "disp_count" => 1,
                "disp_page_count" => 1
            ]
        ];
        $response = $this->post('/document/list', $request);
        $this->withoutExceptionHandling();
        $response->assertStatus(500);
    }

    public function testNumber_amount()
    {
        $request = [
            "m_use" => [
                "company_id" => 1,
                "user_id" => 1
            ],
            "condition" => [
                "category_id" => 1,
                "amount" => [
                    "from" => 250,
                    "to" => 25000
                ],
            ],
            "sort" => [
                "column_name" => "document_id",
                "sort_type" => "asc"
            ],
            "page" => [
                "disp_count" => 1,
                "disp_page_count" => 1
            ]
        ];
        $response = $this->post('/document/list', $request);
        $this->withoutExceptionHandling();
        $response->assertStatus(400);
    }

    public function testFromTo_amount()
    {
        $request = [
            "m_use" => [
                "company_id" => 1,
                "user_id" => 1
            ],
            "condition" => [
                "category_id" => 1,
                "amount" => [
                    "from" => 25000,
                    "to" => 250
                ],
            ],
            "sort" => [
                "column_name" => "document_id",
                "sort_type" => "asc"
            ],
            "page" => [
                "disp_count" => 1,
                "disp_page_count" => 1
            ]
        ];
        $response = $this->post('/document/list', $request);
        $this->withoutExceptionHandling();
        $response->assertStatus(500);
    }

    public function testNumber_currency_id()
    {
        $request = [
            "m_use" => [
                "company_id" => 1,
                "user_id" => 1
            ],
            "condition" => [
                "category_id" => 1,
                "currency_id" => 1
            ],
            "sort" => [
                "column_name" => "document_id",
                "sort_type" => "asc"
            ],
            "page" => [
                "disp_count" => 1,
                "disp_page_count" => 1
            ]
        ];
        $response = $this->post('/document/list', $request);
        $this->withoutExceptionHandling();
        $response->assertStatus(500);
    }

    public function testString_product_name()
    {
        $request = [
            "m_use" => [
                "company_id" => 1,
                "user_id" => 1
            ],
            "condition" => [
                "category_id" => 1,
                "product_name" => "テスト名",
            ],
            "sort" => [
                "column_name" => "document_id",
                "sort_type" => "asc"
            ],
            "page" => [
                "disp_count" => 1,
                "disp_page_count" => 1
            ]
        ];
        $response = $this->post('/document/list', $request);
        $this->withoutExceptionHandling();
        $response->assertStatus(500);
    }

    public function testMax_product_name()
    {
        $request = [
            "m_use" => [
                "company_id" => 1,
                "user_id" => 1
            ],
            "condition" => [
                "category_id" => 1,
                "product_name" => "テスト名",
            ],
            "sort" => [
                "column_name" => "document_id",
                "sort_type" => "asc"
            ],
            "page" => [
                "disp_count" => 1,
                "disp_page_count" => 1
            ]
        ];
        $response = $this->post('/document/list', $request);
        $this->withoutExceptionHandling();
        $response->assertStatus(500);
    }

    public function testNumber_document_id()
    {
        $request = [
            "m_use" => [
                "company_id" => 1,
                "user_id" => 1
            ],
            "condition" => [
                "category_id" => 1,
                "document_id" => 1
            ],
            "sort" => [
                "column_name" => "document_id",
                "sort_type" => "asc"
            ],
            "page" => [
                "disp_count" => 1,
                "disp_page_count" => 1
            ]
        ];
        $response = $this->post('/document/list', $request);
        $this->withoutExceptionHandling();
        $response->assertStatus(500);
    }

    public function testString_counter_party_name()
    {
        $request = [
            "m_use" => [
                "company_id" => 1,
                "user_id" => 1
            ],
            "condition" => [
                "category_id" => 1,
                "counter_party_name" => "テスト",
            ],
            "sort" => [
                "column_name" => "document_id",
                "sort_type" => "asc"
            ],
            "page" => [
                "disp_count" => 1,
                "disp_page_count" => 1
            ]
        ];
        $response = $this->post('/document/list', $request);
        $this->withoutExceptionHandling();
        $response->assertStatus(500);
    }

    public function testMax_counter_party_name()
    {
        $request = [
            "m_use" => [
                "company_id" => 1,
                "user_id" => 1
            ],
            "condition" => [
                "category_id" => 1,
                "counter_party_name" => "テスト",
            ],
            "sort" => [
                "column_name" => "document_id",
                "sort_type" => "asc"
            ],
            "page" => [
                "disp_count" => 1,
                "disp_page_count" => 1
            ]
        ];
        $response = $this->post('/document/list', $request);
        $this->withoutExceptionHandling();
        $response->assertStatus(500);
    }

    public function testString_doc_info()
    {
        $request = [
            "m_use" => [
                "company_id" => 1,
                "user_id" => 1
            ],
            "condition" => [
                "category_id" => 1,
                "doc_info" => [
                    "from" => "A",
                    "to" => "B"
                ],
            ],
            "sort" => [
                "column_name" => "document_id",
                "sort_type" => "asc"
            ],
            "page" => [
                "disp_count" => 1,
                "disp_page_count" => 1
            ]
        ];
        $response = $this->post('/document/list', $request);
        $this->withoutExceptionHandling();
        $response->assertStatus(500);
    }

    public function testNumber_app_user_id()
    {
        $request = [
            "m_use" => [
                "company_id" => 1,
                "user_id" => 1
            ],
            "condition" => [
                "category_id" => 1,
                "app_user_id" => 1
            ],
            "sort" => [
                "column_name" => "document_id",
                "sort_type" => "asc"
            ],
            "page" => [
                "disp_count" => 1,
                "disp_page_count" => 1
            ]
        ];
        $response = $this->post('/document/list', $request);
        $this->withoutExceptionHandling();
        $response->assertStatus(500);
    }

    public function testNumber_app_user_id_guest()
    {
        $request = [
            "m_use" => [
                "company_id" => 1,
                "user_id" => 1
            ],
            "condition" => [
                "category_id" => 1,
                "app_user_id_guest" => 1
            ],
            "sort" => [
                "column_name" => "document_id",
                "sort_type" => "asc"
            ],
            "page" => [
                "disp_count" => 1,
                "disp_page_count" => 1
            ]
        ];
        $response = $this->post('/document/list', $request);
        $this->withoutExceptionHandling();
        $response->assertStatus(500);
    }

    public function testNumber_view_permission_user_id()
    {
        $request = [
            "m_use" => [
                "company_id" => 1,
                "user_id" => 1
            ],
            "condition" => [
                "category_id" => 1,
                "view_permission_user_id" => 1
            ],
            "sort" => [
                "column_name" => "document_id",
                "sort_type" => "asc"
            ],
            "page" => [
                "disp_count" => 1,
                "disp_page_count" => 1
            ]
        ];
        $response = $this->post('/document/list', $request);
        $this->withoutExceptionHandling();
        $response->assertStatus(500);
    }

    public function testString_create_datetime()
    {
        $request = [
            "m_use" => [
                "company_id" => 1,
                "user_id" => 1
            ],
            "condition" => [
                "category_id" => 1,
                "create_datetime" => [
                    "from" => 1669569218,
                    "to" => 1669669218
                ],
            ],
            "sort" => [
                "column_name" => "document_id",
                "sort_type" => "asc"
            ],
            "page" => [
                "disp_count" => 1,
                "disp_page_count" => 1
            ]
        ];
        $response = $this->post('/document/list', $request);
        $this->withoutExceptionHandling();
        $response->assertStatus(400);
    }

    public function testFromTo_create_datetime()
    {
        $request = [
            "m_use" => [
                "company_id" => 1,
                "user_id" => 1
            ],
            "condition" => [
                "category_id" => 1,
                "create_datetime" => [
                    "from" => "2022-11-01",
                    "to" => "2022-12-15"
                ],
            ],
            "sort" => [
                "column_name" => "document_id",
                "sort_type" => "asc"
            ],
            "page" => [
                "disp_count" => 1,
                "disp_page_count" => 1
            ]
        ];
        $response = $this->post('/document/list', $request);
        $this->withoutExceptionHandling();
        $response->assertStatus(500);
    }

    public function testString_contract_start_date()
    {
        $request = [
            "m_use" => [
                "company_id" => 1,
                "user_id" => 1
            ],
            "condition" => [
                "category_id" => 1,
                "contract_start_date" => [
                    "from" => 1669569218,
                    "to" => 1669669218
                ],
            ],
            "sort" => [
                "column_name" => "document_id",
                "sort_type" => "asc"
            ],
            "page" => [
                "disp_count" => 1,
                "disp_page_count" => 1
            ]
        ];
        $response = $this->post('/document/list', $request);
        $this->withoutExceptionHandling();
        $response->assertStatus(400);
    }

    public function testFromTo_contract_start_date()
    {
        $request = [
            "m_use" => [
                "company_id" => 1,
                "user_id" => 1
            ],
            "condition" => [
                "category_id" => 1,
                "contract_start_date" => [
                    "from" => "2022-11-01",
                    "to" => "2022-12-15"
                ],
            ],
            "sort" => [
                "column_name" => "document_id",
                "sort_type" => "asc"
            ],
            "page" => [
                "disp_count" => 1,
                "disp_page_count" => 1
            ]
        ];
        $response = $this->post('/document/list', $request);
        $this->withoutExceptionHandling();
        $response->assertStatus(500);
    }

    public function testString_contract_end_date()
    {
        $request = [
            "m_use" => [
                "company_id" => 1,
                "user_id" => 1
            ],
            "condition" => [
                "category_id" => 1,
                "contract_end_date" => [
                    "from" => 1669569218,
                    "to" => 1669669218
                ],
            ],
            "sort" => [
                "column_name" => "document_id",
                "sort_type" => "asc"
            ],
            "page" => [
                "disp_count" => 1,
                "disp_page_count" => 1
            ]
        ];
        $response = $this->post('/document/list', $request);
        $this->withoutExceptionHandling();
        $response->assertStatus(400);
    }

    public function testFromTo_contract_end_date()
    {
        $request = [
            "m_use" => [
                "company_id" => 1,
                "user_id" => 1
            ],
            "condition" => [
                "category_id" => 1,
                "contract_end_date" => [
                    "from" => "2022-11-01",
                    "to" => "2022-12-15"
                ],
            ],
            "sort" => [
                "column_name" => "document_id",
                "sort_type" => "asc"
            ],
            "page" => [
                "disp_count" => 1,
                "disp_page_count" => 1
            ]
        ];
        $response = $this->post('/document/list', $request);
        $this->withoutExceptionHandling();
        $response->assertStatus(500);
    }

    public function testString_conc_date()
    {
        $request = [
            "m_use" => [
                "company_id" => 1,
                "user_id" => 1
            ],
            "condition" => [
                "category_id" => 1,
                "conc_date" => [
                    "from" => 1669569218,
                    "to" => 1669669218
                ],
            ],
            "sort" => [
                "column_name" => "document_id",
                "sort_type" => "asc"
            ],
            "page" => [
                "disp_count" => 1,
                "disp_page_count" => 1
            ]
        ];
        $response = $this->post('/document/list', $request);
        $this->withoutExceptionHandling();
        $response->assertStatus(400);
    }

    public function testFromTo_conc_date()
    {
        $request = [
            "m_use" => [
                "company_id" => 1,
                "user_id" => 1
            ],
            "condition" => [
                "category_id" => 1,
                "conc_date" => [
                    "from" => "2022-11-01",
                    "to" => "2022-12-15"
                ],
            ],
            "sort" => [
                "column_name" => "document_id",
                "sort_type" => "asc"
            ],
            "page" => [
                "disp_count" => 1,
                "disp_page_count" => 1
            ]
        ];
        $response = $this->post('/document/list', $request);
        $this->withoutExceptionHandling();
        $response->assertStatus(500);
    }

    public function testString_effective_date()
    {
        $request = [
            "m_use" => [
                "company_id" => 1,
                "user_id" => 1
            ],
            "condition" => [
                "category_id" => 1,
                "effective_date" => [
                    "from" => 1669569218,
                    "to" => 1669669218
                ],
            ],
            "sort" => [
                "column_name" => "document_id",
                "sort_type" => "asc"
            ],
            "page" => [
                "disp_count" => 1,
                "disp_page_count" => 1
            ]
        ];
        $response = $this->post('/document/list', $request);
        $this->withoutExceptionHandling();
        $response->assertStatus(400);
    }

    public function testFromTo_effective_date()
    {
        $request = [
            "m_use" => [
                "company_id" => 1,
                "user_id" => 1
            ],
            "condition" => [
                "category_id" => 1,
                "effective_date" => [
                    "from" => "2022-11-01",
                    "to" => "2022-12-15"
                ],
            ],
            "sort" => [
                "column_name" => "document_id",
                "sort_type" => "asc"
            ],
            "page" => [
                "disp_count" => 1,
                "disp_page_count" => 1
            ]
        ];
        $response = $this->post('/document/list', $request);
        $this->withoutExceptionHandling();
        $response->assertStatus(500);
    }

    public function testString_issue_date()
    {
        $request = [
            "m_use" => [
                "company_id" => 1,
                "user_id" => 1
            ],
            "condition" => [
                "category_id" => 1,
                "issue_date" => [
                    "from" => 1669569218,
                    "to" => 1669669218
                ],
            ],
            "sort" => [
                "column_name" => "document_id",
                "sort_type" => "asc"
            ],
            "page" => [
                "disp_count" => 1,
                "disp_page_count" => 1
            ]
        ];
        $response = $this->post('/document/list', $request);
        $this->withoutExceptionHandling();
        $response->assertStatus(400);
    }

    public function testFromTo_issue_date()
    {
        $request = [
            "m_use" => [
                "company_id" => 1,
                "user_id" => 1
            ],
            "condition" => [
                "category_id" => 1,
                "issue_date" => [
                    "from" => "2022-11-01",
                    "to" => "2022-12-15"
                ],
            ],
            "sort" => [
                "column_name" => "document_id",
                "sort_type" => "asc"
            ],
            "page" => [
                "disp_count" => 1,
                "disp_page_count" => 1
            ]
        ];
        $response = $this->post('/document/list', $request);
        $this->withoutExceptionHandling();
        $response->assertStatus(500);
    }

    public function testString_expiry_date()
    {
        $request = [
            "m_use" => [
                "company_id" => 1,
                "user_id" => 1
            ],
            "condition" => [
                "category_id" => 1,
                "expiry_date" => [
                    "from" => 1669569218,
                    "to" => 1669669218
                ],
            ],
            "sort" => [
                "column_name" => "document_id",
                "sort_type" => "asc"
            ],
            "page" => [
                "disp_count" => 1,
                "disp_page_count" => 1
            ]
        ];
        $response = $this->post('/document/list', $request);
        $this->withoutExceptionHandling();
        $response->assertStatus(400);
    }

    public function testFromTo_expiry_date()
    {
        $request = [
            "m_use" => [
                "company_id" => 1,
                "user_id" => 1
            ],
            "condition" => [
                "category_id" => 1,
                "expiry_date" => [
                    "from" => "2022-11-01",
                    "to" => "2022-12-15"
                ],
            ],
            "sort" => [
                "column_name" => "document_id",
                "sort_type" => "asc"
            ],
            "page" => [
                "disp_count" => 1,
                "disp_page_count" => 1
            ]
        ];
        $response = $this->post('/document/list', $request);
        $this->withoutExceptionHandling();
        $response->assertStatus(500);
    }

    public function testString_payment_date()
    {
        $request = [
            "m_use" => [
                "company_id" => 1,
                "user_id" => 1
            ],
            "condition" => [
                "category_id" => 1,
                "payment_date" => [
                    "from" => 1669569218,
                    "to" => 1669669218
                ],
            ],
            "sort" => [
                "column_name" => "document_id",
                "sort_type" => "asc"
            ],
            "page" => [
                "disp_count" => 1,
                "disp_page_count" => 1
            ]
        ];
        $response = $this->post('/document/list', $request);
        $this->withoutExceptionHandling();
        $response->assertStatus(400);
    }

    public function testFromTo_payment_date()
    {
        $request = [
            "m_use" => [
                "company_id" => 1,
                "user_id" => 1
            ],
            "condition" => [
                "category_id" => 1,
                "payment_date" => [
                    "from" => "2022-11-01",
                    "to" => "2022-12-15"
                ],
            ],
            "sort" => [
                "column_name" => "document_id",
                "sort_type" => "asc"
            ],
            "page" => [
                "disp_count" => 1,
                "disp_page_count" => 1
            ]
        ];
        $response = $this->post('/document/list', $request);
        $this->withoutExceptionHandling();
        $response->assertStatus(500);
    }

    public function testString_doc_no()
    {
        $request = [
            "m_use" => [
                "company_id" => 1,
                "user_id" => 1
            ],
            "condition" => [
                "category_id" => 1,
                "doc_no" => "テスト書類番号",
            ],
            "sort" => [
                "column_name" => "document_id",
                "sort_type" => "asc"
            ],
            "page" => [
                "disp_count" => 1,
                "disp_page_count" => 1
            ]
        ];
        $response = $this->post('/document/list', $request);
        $this->withoutExceptionHandling();
        $response->assertStatus(500);
    }

    public function testMax_doc_no()
    {
        $request = [
            "m_use" => [
                "company_id" => 1,
                "user_id" => 1
            ],
            "condition" => [
                "category_id" => 1,
                "doc_no" => "テスト書類番号",
            ],
            "sort" => [
                "column_name" => "document_id",
                "sort_type" => "asc"
            ],
            "page" => [
                "disp_count" => 1,
                "disp_page_count" => 1
            ]
        ];
        $response = $this->post('/document/list', $request);
        $this->withoutExceptionHandling();
        $response->assertStatus(500);
    }

    public function testString_ref_doc_no()
    {
        $request = [
            "m_use" => [
                "company_id" => 1,
                "user_id" => 1
            ],
            "condition" => [
                "category_id" => 1,
                "ref_doc_no" => "テストref_doc_no",
            ],
            "sort" => [
                "column_name" => "document_id",
                "sort_type" => "asc"
            ],
            "page" => [
                "disp_count" => 1,
                "disp_page_count" => 1
            ]
        ];
        $response = $this->post('/document/list', $request);
        $this->withoutExceptionHandling();
        $response->assertStatus(500);
    }

    public function testMax_ref_doc_no()
    {
        $request = [
            "m_use" => [
                "company_id" => 1,
                "user_id" => 1
            ],
            "condition" => [
                "category_id" => 1,
                "ref_doc_no" => "テストref_doc_no",
            ],
            "sort" => [
                "column_name" => "document_id",
                "sort_type" => "asc"
            ],
            "page" => [
                "disp_count" => 1,
                "disp_page_count" => 1
            ]
        ];
        $response = $this->post('/document/list', $request);
        $this->withoutExceptionHandling();
        $response->assertStatus(500);
    }

    public function testString_content()
    {
        $request = [
            "m_use" => [
                "company_id" => 1,
                "user_id" => 1
            ],
            "condition" => [
                "category_id" => 1,
                "content" => "テスト",
            ],
            "sort" => [
                "column_name" => "document_id",
                "sort_type" => "asc"
            ],
            "page" => [
                "disp_count" => 1,
                "disp_page_count" => 1
            ]
        ];
        $response = $this->post('/document/list', $request);
        $this->withoutExceptionHandling();
        $response->assertStatus(500);
    }

    public function testMax_content()
    {
        $request = [
            "m_use" => [
                "company_id" => 1,
                "user_id" => 1
            ],
            "condition" => [
                "category_id" => 1,
                "content" => "テスト",
            ],
            "sort" => [
                "column_name" => "document_id",
                "sort_type" => "asc"
            ],
            "page" => [
                "disp_count" => 1,
                "disp_page_count" => 1
            ]
        ];
        $response = $this->post('/document/list', $request);
        $this->withoutExceptionHandling();
        $response->assertStatus(500);
    }

    public function testString_doc_create_date()
    {
        $request = [
            "m_use" => [
                "company_id" => 1,
                "user_id" => 1
            ],
            "condition" => [
                "category_id" => 1,
                "doc_create_date" => [
                    "from" => 1669569218,
                    "to" => 1669669218
                ],
            ],
            "sort" => [
                "column_name" => "document_id",
                "sort_type" => "asc"
            ],
            "page" => [
                "disp_count" => 1,
                "disp_page_count" => 1
            ]
        ];
        $response = $this->post('/document/list', $request);
        $this->withoutExceptionHandling();
        $response->assertStatus(400);
    }

    public function testFromTo_doc_create_date()
    {
        $request = [
            "m_use" => [
                "company_id" => 1,
                "user_id" => 1
            ],
            "condition" => [
                "category_id" => 1,
                "doc_create_date" => [
                    "from" => "2022-11-01",
                    "to" => "2022-12-15"
                ],
            ],
            "sort" => [
                "column_name" => "document_id",
                "sort_type" => "asc"
            ],
            "page" => [
                "disp_count" => 1,
                "disp_page_count" => 1
            ]
        ];
        $response = $this->post('/document/list', $request);
        $this->withoutExceptionHandling();
        $response->assertStatus(500);
    }

    public function testString_sign_finish_date()
    {
        $request = [
            "m_use" => [
                "company_id" => 1,
                "user_id" => 1
            ],
            "condition" => [
                "category_id" => 1,
                "sign_finish_date" => [
                    "from" => 1669569218,
                    "to" => 1669669218
                ],
            ],
            "sort" => [
                "column_name" => "document_id",
                "sort_type" => "asc"
            ],
            "page" => [
                "disp_count" => 1,
                "disp_page_count" => 1
            ]
        ];
        $response = $this->post('/document/list', $request);
        $this->withoutExceptionHandling();
        $response->assertStatus(400);
    }

    public function testFromTo_sign_finish_date()
    {
        $request = [
            "m_use" => [
                "company_id" => 1,
                "user_id" => 1
            ],
            "condition" => [
                "category_id" => 1,
                "sign_finish_date" => [
                    "from" => "2022-11-01",
                    "to" => "2022-12-15"
                ],
            ],
            "sort" => [
                "column_name" => "document_id",
                "sort_type" => "asc"
            ],
            "page" => [
                "disp_count" => 1,
                "disp_page_count" => 1
            ]
        ];
        $response = $this->post('/document/list', $request);
        $this->withoutExceptionHandling();
        $response->assertStatus(500);
    }

    public function testNumber_scan_doc_flg()
    {
        $request = [
            "m_use" => [
                "company_id" => 1,
                "user_id" => 1
            ],
            "condition" => [
                "category_id" => 1,
                "scan_doc_flg" => 1
            ],
            "sort" => [
                "column_name" => "document_id",
                "sort_type" => "asc"
            ],
            "page" => [
                "disp_count" => 1,
                "disp_page_count" => 1
            ]
        ];
        $response = $this->post('/document/list', $request);
        $this->withoutExceptionHandling();
        $response->assertStatus(500);
    }

    public function testNumber_timestamp_user()
    {
        $request = [
            "m_use" => [
                "company_id" => 1,
                "user_id" => 1
            ],
            "condition" => [
                "category_id" => 1,
                "timestamp_user" => 1
            ],
            "sort" => [
                "column_name" => "document_id",
                "sort_type" => "asc"
            ],
            "page" => [
                "disp_count" => 1,
                "disp_page_count" => 1
            ]
        ];
        $response = $this->post('/document/list', $request);
        $this->withoutExceptionHandling();
        $response->assertStatus(500);
    }

    public function testString_remarks()
    {
        $request = [
            "m_use" => [
                "company_id" => 1,
                "user_id" => 1
            ],
            "condition" => [
                "category_id" => 1,
                "remarks" => "テスト備考",
            ],
            "sort" => [
                "column_name" => "document_id",
                "sort_type" => "asc"
            ],
            "page" => [
                "disp_count" => 1,
                "disp_page_count" => 1
            ]
        ];
        $response = $this->post('/document/list', $request);
        $this->withoutExceptionHandling();
        $response->assertStatus(500);
    }

    public function testMax_remarks()
    {
        $request = [
            "m_use" => [
                "company_id" => 1,
                "user_id" => 1
            ],
            "condition" => [
                "category_id" => 1,
                "remarks" => "テスト備考",
            ],
            "sort" => [
                "column_name" => "document_id",
                "sort_type" => "asc"
            ],
            "page" => [
                "disp_count" => 1,
                "disp_page_count" => 1
            ]
        ];
        $response = $this->post('/document/list', $request);
        $this->withoutExceptionHandling();
        $response->assertStatus(500);
    }

    public function testString_download_date()
    {
        $request = [
            "m_use" => [
                "company_id" => 1,
                "user_id" => 1
            ],
            "condition" => [
                "category_id" => 1,
                "download_date" => [
                    "from" => 1669569218,
                    "to" => 1669669218
                ],
            ],
            "sort" => [
                "column_name" => "document_id",
                "sort_type" => "asc"
            ],
            "page" => [
                "disp_count" => 1,
                "disp_page_count" => 1
            ]
        ];
        $response = $this->post('/document/list', $request);
        $this->withoutExceptionHandling();
        $response->assertStatus(400);
    }

    public function testFromTo_download_date()
    {
        $request = [
            "m_use" => [
                "company_id" => 1,
                "user_id" => 1
            ],
            "condition" => [
                "category_id" => 1,
                "download_date" => [
                    "from" => "2022-11-01",
                    "to" => "2022-12-15"
                ],
            ],
            "sort" => [
                "column_name" => "document_id",
                "sort_type" => "asc"
            ],
            "page" => [
                "disp_count" => 1,
                "disp_page_count" => 1
            ]
        ];
        $response = $this->post('/document/list', $request);
        $this->withoutExceptionHandling();
        $response->assertStatus(500);
    }

    public function testString_column_name()
    {
        $request = [
            "m_use" => [
                "company_id" => 1,
                "user_id" => 1
            ],
            "condition" => [
                "category_id" => 1,
            ],
            "sort" => [
                "column_name" => "document_id",
                "sort_type" => "asc"
            ],
            "page" => [
                "disp_count" => 1,
                "disp_page_count" => 1
            ]
        ];
        $response = $this->post('/document/list', $request);
        $this->withoutExceptionHandling();
        $response->assertStatus(500);
    }

    public function testInArray_column_name()
    {
        $request = [
            "m_use" => [
                "company_id" => 1,
                "user_id" => 1
            ],
            "condition" => [
                "category_id" => 1,
            ],
            "sort" => [
                "column_name" => "document_id",
                "sort_type" => "asc"
            ],
            "page" => [
                "disp_count" => 1,
                "disp_page_count" => 1
            ]
        ];
        $response = $this->post('/document/list', $request);
        $this->withoutExceptionHandling();
        $response->assertStatus(500);
    }

    public function testString_sort_type()
    {
        $request = [
            "m_use" => [
                "company_id" => 1,
                "user_id" => 1
            ],
            "condition" => [
                "category_id" => 1,
            ],
            "sort" => [
                "column_name" => "document_id",
                "sort_type" => "asc"
            ],
            "page" => [
                "disp_count" => 1,
                "disp_page_count" => 1
            ]
        ];
        $response = $this->post('/document/list', $request);
        $this->withoutExceptionHandling();
        $response->assertStatus(500);
    }

    public function testInArray_sort_type()
    {
        $request = [
            "m_use" => [
                "company_id" => 1,
                "user_id" => 1
            ],
            "condition" => [
                "category_id" => 1,
            ],
            "sort" => [
                "column_name" => "document_id",
                "sort_type" => "asc"
            ],
            "page" => [
                "disp_count" => 1,
                "disp_page_count" => 1
            ]
        ];
        $response = $this->post('/document/list', $request);
        $this->withoutExceptionHandling();
        $response->assertStatus(500);
    }

    public function testNumber_disp_page_count()
    {
        $request = [
            "m_use" => [
                "company_id" => 1,
                "user_id" => 1
            ],
            "condition" => [
                "category_id" => 1,
            ],
            "sort" => [
                "column_name" => "document_id",
                "sort_type" => "asc"
            ],
            "page" => [
                "disp_count" => 1,
                "disp_page_count" => 1
            ]
        ];
        $response = $this->post('/document/list', $request);
        $this->withoutExceptionHandling();
        $response->assertStatus(500);
    }

    public function testNumber_disp_count()
    {
        $request = [
            "m_use" => [
                "company_id" => 1,
                "user_id" => 1
            ],
            "condition" => [
                "category_id" => 1,
            ],
            "sort" => [
                "column_name" => "document_id",
                "sort_type" => "asc"
            ],
            "page" => [
                "disp_count" => 1,
                "disp_page_count" => 1
            ]
        ];
        $response = $this->post('/document/list', $request);
        $this->withoutExceptionHandling();
        $response->assertStatus(500);
    }
}

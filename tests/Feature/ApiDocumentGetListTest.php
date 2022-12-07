<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiDocumentGetListTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_the_application_returns_a_successful_response()
    {
        $request = [
            "m_use" => [
                "company_id" => 1,
                "user_id" => 1
            ],
            "condition" => [
                "search_input" => "テスト",
                "status_id" => 0,
                "category_id" => 2,
                "register_type_id" => 1,
                "title" => "テスト",
                "contract_name" => "",
                "amount" => [
                    "from" => 250,
                    "to" => 25000
                ],
                "product_name"=> "テスト",
                "document_id" => 1,
                "doc_no" => "テスト123",
                "ref_doc_no" => "テスト123",
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
                "remarks" => "テストデータ",
                "app_user_id" => 1,
                "app_user_id_guest" => 1,
                "view_permission_user_id" => 1,
                "counter_party_name" => "テスト",
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
                "disp_count" => 1,
                "disp_page_count" => 1
            ]
        ];
        $this->withoutExceptionHandling();
        $response = $this->post('/document/list', $request);
        $response->assertStatus(200);
    }

    public function testRequired_category_id()
    {
        $request = [
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
        $this->withoutExceptionHandling();
        $response = $this->post('/document/list', $request);
        $response->assertStatus(200);
    }

    public function testNumber_category_id()
    {
        $request = [
            "condition" => [
                "category_id" => "B"
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
        $this->withoutExceptionHandling();
        $response = $this->post('/document/list', $request);
        $response->assertStatus(200);
    }

    public function testString_search_input()
    {
        $request = [
            "condition" => [
                "category_id" => 1,
                "search_input" => 1
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
        $this->withoutExceptionHandling();
        $response = $this->post('/document/list', $request);
        $response->assertStatus(200);
    }

    public function testString_transaction_date()
    {
        $request = [
            "condition" => [
                "category_id" => 1,
                "search_input" => "テスト",
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
        $this->withoutExceptionHandling();
        $response = $this->post('/document/list', $request);
        $response->assertStatus(200);
    }

    public function testFromTo_transaction_date()
    {
        $request = [
            "condition" => [
                "category_id" => 1,
                "search_input" => "テスト",
                "transaction_date" => [
                    "from" => "2022-12-15",
                    "to" => "2022-11-01"
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
        $this->withoutExceptionHandling();
        $response = $this->post('/document/list', $request);
        $response->assertStatus(200);
    }

    public function testNumber_status_id()
    {
        $request = [
            "condition" => [
                "category_id" => 1,
                "status_id" => "A",
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
        $this->withoutExceptionHandling();
        $response = $this->post('/document/list', $request);
        $response->assertStatus(200);
    }

    public function testNumber_doc_type_id()
    {
        $request = [
            "condition" => [
                "category_id" => 1,
                "doc_type_id" => "A",
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
        $this->withoutExceptionHandling();
        $response = $this->post('/document/list', $request);
        $response->assertStatus(200);
    }

    public function testString_title()
    {
        $request = [
            "condition" => [
                "category_id" => 1,
                "title" => 1,
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
        $this->withoutExceptionHandling();
        $response = $this->post('/document/list', $request);
        $response->assertStatus(200);
    }

    public function testMax_title()
    {
        $request = [
            "condition" => [
                "category_id" => 1,
                "title" => "1aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa
                1aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
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
        $this->withoutExceptionHandling();
        $response = $this->post('/document/list', $request);
        $response->assertStatus(200);
    }

    public function testNumber_amount()
    {
        $request = [
            "condition" => [
                "category_id" => 1,
                "amount" => [
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
        $this->withoutExceptionHandling();
        $response = $this->post('/document/list', $request);
        $response->assertStatus(200);
    }

    public function testFromTo_amount()
    {
        $request = [
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
        $this->withoutExceptionHandling();
        $response = $this->post('/document/list', $request);
        $response->assertStatus(200);
    }

    public function testNumber_currency_id()
    {
        $request = [
            "condition" => [
                "category_id" => 1,
                "currency_id" => "A"
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
        $this->withoutExceptionHandling();
        $response = $this->post('/document/list', $request);
        $response->assertStatus(200);
    }

    public function testString_product_name()
    {
        $request = [
            "condition" => [
                "category_id" => 1,
                "product_name" => 1,
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
        $this->withoutExceptionHandling();
        $response = $this->post('/document/list', $request);
        $response->assertStatus(200);
    }

    public function testMax_product_name()
    {
        $request = [
            "condition" => [
                "category_id" => 1,
                "product_name" => "1aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa
                1aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
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
        $this->withoutExceptionHandling();
        $response = $this->post('/document/list', $request);
        $response->assertStatus(200);
    }

    public function testNumber_document_id()
    {
        $request = [
            "condition" => [
                "category_id" => 1,
                "document_id" => "A"
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
        $this->withoutExceptionHandling();
        $response = $this->post('/document/list', $request);
        $response->assertStatus(200);
    }

    public function testString_counter_party_name()
    {
        $request = [
            "condition" => [
                "category_id" => 1,
                "counter_party_name" => 1,
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
        $this->withoutExceptionHandling();
        $response = $this->post('/document/list', $request);
        $response->assertStatus(200);
    }

    public function testMax_counter_party_name()
    {
        $request = [
            "condition" => [
                "category_id" => 1,
                "counter_party_name" => "1aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa
                1aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
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
        $this->withoutExceptionHandling();
        $response = $this->post('/document/list', $request);
        $response->assertStatus(200);
    }

    public function testString_doc_info()
    {
        $request = [
            "condition" => [
                "category_id" => 1,
                "doc_info" => 1,
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
        $this->withoutExceptionHandling();
        $response = $this->post('/document/list', $request);
        $response->assertStatus(200);
    }

    public function testNumber_app_user_id()
    {
        $request = [
            "condition" => [
                "category_id" => 1,
                "app_user_id" => "A"
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
        $this->withoutExceptionHandling();
        $response = $this->post('/document/list', $request);
        $response->assertStatus(200);
    }

    public function testNumber_app_user_id_guest()
    {
        $request = [
            "condition" => [
                "category_id" => 1,
                "app_user_id_guest" => "A"
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
        $this->withoutExceptionHandling();
        $response = $this->post('/document/list', $request);
        $response->assertStatus(200);
    }

    public function testNumber_view_permission_user_id()
    {
        $request = [
            "condition" => [
                "category_id" => 1,
                "view_permission_user_id" => "A"
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
        $this->withoutExceptionHandling();
        $response = $this->post('/document/list', $request);
        $response->assertStatus(200);
    }

    public function testString_create_datetime()
    {
        $request = [
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
        $this->withoutExceptionHandling();
        $response = $this->post('/document/list', $request);
        $response->assertStatus(200);
    }

    public function testFromTo_create_datetime()
    {
        $request = [
            "condition" => [
                "category_id" => 1,
                "create_datetime" => [
                    "from" => "2022-12-15",
                    "to" => "2022-11-01"
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
        $this->withoutExceptionHandling();
        $response = $this->post('/document/list', $request);
        $response->assertStatus(200);
    }

    public function testString_contract_start_date()
    {
        $request = [
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
        $this->withoutExceptionHandling();
        $response = $this->post('/document/list', $request);
        $response->assertStatus(200);
    }

    public function testFromTo_contract_start_date()
    {
        $request = [
            "condition" => [
                "category_id" => 1,
                "contract_start_date" => [
                    "from" => "2022-12-15",
                    "to" => "2022-11-01"
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
        $this->withoutExceptionHandling();
        $response = $this->post('/document/list', $request);
        $response->assertStatus(200);
    }

    public function testString_contract_end_date()
    {
        $request = [
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
        $this->withoutExceptionHandling();
        $response = $this->post('/document/list', $request);
        $response->assertStatus(200);
    }

    public function testFromTo_contract_end_date()
    {
        $request = [
            "condition" => [
                "category_id" => 1,
                "contract_end_date" => [
                    "from" => "2022-12-15",
                    "to" => "2022-11-01"
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
        $this->withoutExceptionHandling();
        $response = $this->post('/document/list', $request);
        $response->assertStatus(200);
    }

    public function testString_conc_date()
    {
        $request = [
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
        $this->withoutExceptionHandling();
        $response = $this->post('/document/list', $request);
        $response->assertStatus(200);
    }

    public function testFromTo_conc_date()
    {
        $request = [
            "condition" => [
                "category_id" => 1,
                "conc_date" => [
                    "from" => "2022-12-15",
                    "to" => "2022-11-01"
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
        $this->withoutExceptionHandling();
        $response = $this->post('/document/list', $request);
        $response->assertStatus(200);
    }

    public function testString_effective_date()
    {
        $request = [
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
        $this->withoutExceptionHandling();
        $response = $this->post('/document/list', $request);
        $response->assertStatus(200);
    }

    public function testFromTo_effective_date()
    {
        $request = [
            "condition" => [
                "category_id" => 1,
                "effective_date" => [
                    "from" => "2022-12-15",
                    "to" => "2022-11-01"
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
        $this->withoutExceptionHandling();
        $response = $this->post('/document/list', $request);
        $response->assertStatus(200);
    }

    public function testString_issue_date()
    {
        $request = [
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
        $this->withoutExceptionHandling();
        $response = $this->post('/document/list', $request);
        $response->assertStatus(200);
    }

    public function testFromTo_issue_date()
    {
        $request = [
            "condition" => [
                "category_id" => 1,
                "issue_date" => [
                    "from" => "2022-12-15",
                    "to" => "2022-11-01"
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
        $this->withoutExceptionHandling();
        $response = $this->post('/document/list', $request);
        $response->assertStatus(200);
    }

    public function testString_expiry_date()
    {
        $request = [
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
        $this->withoutExceptionHandling();
        $response = $this->post('/document/list', $request);
        $response->assertStatus(200);
    }

    public function testFromTo_expiry_date()
    {
        $request = [
            "condition" => [
                "category_id" => 1,
                "expiry_date" => [
                    "from" => "2022-12-15",
                    "to" => "2022-11-01"
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
        $this->withoutExceptionHandling();
        $response = $this->post('/document/list', $request);
        $response->assertStatus(200);
    }

    public function testString_payment_date()
    {
        $request = [
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
        $this->withoutExceptionHandling();
        $response = $this->post('/document/list', $request);
        $response->assertStatus(200);
    }

    public function testFromTo_payment_date()
    {
        $request = [
            "condition" => [
                "category_id" => 1,
                "payment_date" => [
                    "from" => "2022-12-15",
                    "to" => "2022-11-01"
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
        $this->withoutExceptionHandling();
        $response = $this->post('/document/list', $request);
        $response->assertStatus(200);
    }

    public function testString_doc_no()
    {
        $request = [
            "condition" => [
                "category_id" => 1,
                "doc_no" => 1,
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
        $this->withoutExceptionHandling();
        $response = $this->post('/document/list', $request);
        $response->assertStatus(200);
    }

    public function testMax_doc_no()
    {
        $request = [
            "condition" => [
                "category_id" => 1,
                "doc_no" => "1aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa
                あああああ1aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
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
        $this->withoutExceptionHandling();
        $response = $this->post('/document/list', $request);
        $response->assertStatus(200);
    }

    public function testString_ref_doc_no()
    {
        $request = [
            "condition" => [
                "category_id" => 1,
                "ref_doc_no" => 1,
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
        $this->withoutExceptionHandling();
        $response = $this->post('/document/list', $request);
        $response->assertStatus(200);
    }

    public function testMax_ref_doc_no()
    {
        $request = [
            "condition" => [
                "category_id" => 1,
                "ref_doc_no" => "1aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa
                あああああ1aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
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
        $this->withoutExceptionHandling();
        $response = $this->post('/document/list', $request);
        $response->assertStatus(200);
    }

    public function testString_content()
    {
        $request = [
            "condition" => [
                "category_id" => 1,
                "content" => 1,
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
        $this->withoutExceptionHandling();
        $response = $this->post('/document/list', $request);
        $response->assertStatus(200);
    }

    public function testMax_content()
    {
        $request = [
            "condition" => [
                "category_id" => 1,
                "content" => "1aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa
                あああああ1aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
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
        $this->withoutExceptionHandling();
        $response = $this->post('/document/list', $request);
        $response->assertStatus(200);
    }

    public function testString_doc_create_date()
    {
        $request = [
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
        $this->withoutExceptionHandling();
        $response = $this->post('/document/list', $request);
        $response->assertStatus(200);
    }

    public function testFromTo_doc_create_date()
    {
        $request = [
            "condition" => [
                "category_id" => 1,
                "doc_create_date" => [
                    "from" => "2022-12-15",
                    "to" => "2022-11-01"
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
        $this->withoutExceptionHandling();
        $response = $this->post('/document/list', $request);
        $response->assertStatus(200);
    }

    public function testString_sign_finish_date()
    {
        $request = [
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
        $this->withoutExceptionHandling();
        $response = $this->post('/document/list', $request);
        $response->assertStatus(200);
    }

    public function testFromTo_sign_finish_date()
    {
        $request = [
            "condition" => [
                "category_id" => 1,
                "sign_finish_date" => [
                    "from" => "2022-12-15",
                    "to" => "2022-11-01"
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
        $this->withoutExceptionHandling();
        $response = $this->post('/document/list', $request);
        $response->assertStatus(200);
    }

    public function testNumber_scan_doc_flg()
    {
        $request = [
            "condition" => [
                "category_id" => 1,
                "scan_doc_flg" => "A"
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
        $this->withoutExceptionHandling();
        $response = $this->post('/document/list', $request);
        $response->assertStatus(200);
    }

    public function testNumber_timestamp_user()
    {
        $request = [
            "condition" => [
                "category_id" => 1,
                "timestamp_user" => "A"
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
        $this->withoutExceptionHandling();
        $response = $this->post('/document/list', $request);
        $response->assertStatus(200);
    }

    public function testString_remarks()
    {
        $request = [
            "condition" => [
                "category_id" => 1,
                "remarks" => 1,
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
        $this->withoutExceptionHandling();
        $response = $this->post('/document/list', $request);
        $response->assertStatus(200);
    }

    public function testMax_remarks()
    {
        $request = [
            "condition" => [
                "category_id" => 1,
                "remarks" => "1aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa
                あああああ1aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
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
        $this->withoutExceptionHandling();
        $response = $this->post('/document/list', $request);
        $response->assertStatus(200);
    }

    public function testString_download_date()
    {
        $request = [
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
        $this->withoutExceptionHandling();
        $response = $this->post('/document/list', $request);
        $response->assertStatus(200);
    }

    public function testFromTo_download_date()
    {
        $request = [
            "condition" => [
                "category_id" => 1,
                "download_date" => [
                    "from" => "2022-12-15",
                    "to" => "2022-11-01"
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
        $this->withoutExceptionHandling();
        $response = $this->post('/document/list', $request);
        $response->assertStatus(200);
    }

    public function testString_column_name()
    {
        $request = [
            "condition" => [
                "category_id" => 1,
            ],
            "sort" => [
                "column_name" => 1,
                "sort_type" => "asc"
            ],
            "page" => [
                "disp_count" => 1,
                "disp_page_count" => 1
            ]
        ];
        $this->withoutExceptionHandling();
        $response = $this->post('/document/list', $request);
        $response->assertStatus(200);
    }

    public function testInArray_column_name()
    {
        $request = [
            "condition" => [
                "category_id" => 1,
            ],
            "sort" => [
                "column_name" => "AAA",
                "sort_type" => "asc"
            ],
            "page" => [
                "disp_count" => 1,
                "disp_page_count" => 1
            ]
        ];
        $this->withoutExceptionHandling();
        $response = $this->post('/document/list', $request);
        $response->assertStatus(200);
    }

    public function testString_sort_type()
    {
        $request = [
            "condition" => [
                "category_id" => 1,
            ],
            "sort" => [
                "column_name" => "document_id",
                "sort_type" => 1
            ],
            "page" => [
                "disp_count" => 1,
                "disp_page_count" => 1
            ]
        ];
        $this->withoutExceptionHandling();
        $response = $this->post('/document/list', $request);
        $response->assertStatus(200);
    }

    public function testInArray_sort_type()
    {
        $request = [
            "condition" => [
                "category_id" => 1,
            ],
            "sort" => [
                "column_name" => "document_id",
                "sort_type" => "BBB"
            ],
            "page" => [
                "disp_count" => 1,
                "disp_page_count" => 1
            ]
        ];
        $this->withoutExceptionHandling();
        $response = $this->post('/document/list', $request);
        $response->assertStatus(200);
    }

    public function testNumber_disp_page_count()
    {
        $request = [
            "condition" => [
                "category_id" => 1,
            ],
            "sort" => [
                "column_name" => "document_id",
                "sort_type" => "asc"
            ],
            "page" => [
                "disp_count" => "A",
                "disp_page_count" => 1
            ]
        ];
        $this->withoutExceptionHandling();
        $response = $this->post('/document/list', $request);
        $response->assertStatus(200);
    }

    public function testNumber_disp_count()
    {
        $request = [
            "condition" => [
                "category_id" => 1,
            ],
            "sort" => [
                "column_name" => "document_id",
                "sort_type" => "asc"
            ],
            "page" => [
                "disp_count" => 1,
                "disp_page_count" => "A"
            ]
        ];
        $this->withoutExceptionHandling();
        $response = $this->post('/document/list', $request);
        $response->assertStatus(200);
    }
}

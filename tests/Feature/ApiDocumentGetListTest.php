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
                "disp_page" => 1
            ]
        ];
        $this->withoutExceptionHandling();
        $response = $this->post('/document/list', $request);
        $response->assertStatus(200);
    }
}

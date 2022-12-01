<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest_Validation extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_validation()
    {
        $request = [
            "document_id" => 1,
            "category_id" => 3,
            "company_id" => 1,
            "user_id" => 1,
            "update_datetime" => 3878470431
        ];
        $this->withoutExceptionHandling();
        $response = $this->post('/document/delete', $request);
        $response->assertStatus(200);
    }
}

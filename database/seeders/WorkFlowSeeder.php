<?php

namespace Database\Seeders;

use Carbon\CarbonImmutable;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class WorkFlowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('t_document_workflow')->insert([
            [
                "company_id" => 1,
                "category_id" => 0,
                "document_id" => 1,
                "app_user_id" => 1,
                "wf_sort" => 0,
            ],
        ]);
        \DB::table('t_document_workflow')->insert([
            [
                "company_id" => 1,
                "category_id" => 0,
                "document_id" => 1,
                "app_user_id" => 2,
                "wf_sort" => 1,
            ],
        ]);
        \DB::table('t_document_workflow')->insert([
            [
                "company_id" => 1,
                "category_id" => 0,
                "document_id" => 1,
                "app_user_id" => 3,
                "wf_sort" => 2,
            ],
        ]);

        \DB::table('t_document_workflow')->insert([
            [
                "company_id" => 1,
                "category_id" => 1,
                "document_id" => 2,
                "app_user_id" => 1,
                "wf_sort" => 0,
            ],
        ]);
        \DB::table('t_document_workflow')->insert([
            [
                "company_id" => 1,
                "category_id" => 1,
                "document_id" => 2,
                "app_user_id" => 2,
                "wf_sort" => 1,
            ],
        ]);
        \DB::table('t_document_workflow')->insert([
            [
                "company_id" => 1,
                "category_id" => 1,
                "document_id" => 2,
                "app_user_id" => 3,
                "wf_sort" => 2,
            ],
        ]);


        \DB::table('t_document_workflow')->insert([
            [
                "company_id" => 2,
                "category_id" => 2,
                "document_id" => 3,
                "app_user_id" => 4,
                "wf_sort" => 0,
            ],
        ]);
        \DB::table('t_document_workflow')->insert([
            [
                "company_id" => 2,
                "category_id" => 2,
                "document_id" => 3,
                "app_user_id" => 5,
                "wf_sort" => 1,
            ],
        ]);
        \DB::table('t_document_workflow')->insert([
            [
                "company_id" => 2,
                "category_id" => 2,
                "document_id" => 3,
                "app_user_id" => 6,
                "wf_sort" => 2,
            ],
        ]);


        \DB::table('t_document_workflow')->insert([
            [
                "company_id" => 2,
                "category_id" => 3,
                "document_id" => 4,
                "app_user_id" => 5,
                "wf_sort" => 0,
            ],
        ]);
        \DB::table('t_document_workflow')->insert([
            [
                "company_id" => 2,
                "category_id" => 3,
                "document_id" => 4,
                "app_user_id" => 6,
                "wf_sort" => 1,
            ],
        ]);
        
    }
}

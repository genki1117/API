<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_document_workflow', function (Blueprint $table) {
            $sql = "CREATE TABLE t_document_workflow (
                company_id bigint  not null comment 'Company ID'
                ,category_id int  not null comment 'Category ID'
                ,document_id bigint  not null comment 'Document ID'
                ,workflow_id bigint   comment 'Workflow ID'
                ,app_user_id bigint  not null comment 'Approve User ID'
                ,app_status int   comment 'Approve Status'
                ,app_date date   comment 'Approve Date'
                ,wf_sort int  not null comment 'Workflow Sort Order'
                ,create_user bigint   comment 'Create User'
                ,create_datetime timestamp   NULL DEFAULT CURRENT_TIMESTAMP comment 'Create Date'
                ,update_user bigint   comment 'Update User'
                ,update_datetime timestamp   NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP comment 'Update Date'
                ,delete_user bigint   comment 'Delete User'
                ,delete_datetime timestamp   comment 'Delete Date'
                , constraint idx_t_document_workflow primary key (company_id,document_id,category_id,app_user_id,wf_sort)
            ) comment '書類ワークフロー' ";            
            DB::connection()->getPdo()->exec($sql);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_document_workflow');
    }
};

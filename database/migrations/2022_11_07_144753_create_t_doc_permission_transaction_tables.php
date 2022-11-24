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
        Schema::table('t_doc_permission_transaction', function (Blueprint $table) {
            $sql = "CREATE TABLE t_doc_permission_transaction (
                document_id bigint  not null comment 'Document ID'
                ,company_id bigint  not null comment 'Company ID'
                ,user_id bigint  not null comment 'User ID'
                ,create_user bigint   comment 'Create User'
                ,create_datetime timestamp   NULL DEFAULT CURRENT_TIMESTAMP comment 'Create Date'
                ,update_user bigint   comment 'Update User'
                ,update_datetime timestamp   NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP comment 'Update Date'
                ,delete_user bigint   comment 'Delete User'
                ,delete_datetime timestamp   comment 'Delete Date'
                , constraint idx_t_document_permission primary key (document_id,user_id)
            ) comment '取引書類閲覧権限' ";            
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
        Schema::dropIfExists('t_doc_permission_transaction');
    }
};

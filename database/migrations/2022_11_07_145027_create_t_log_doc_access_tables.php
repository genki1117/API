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
        Schema::table('t_log_doc_access', function (Blueprint $table) {
            $sql = "CREATE TABLE t_log_doc_access (
                log_id bigint unsigned AUTO_INCREMENT not null comment 'Log ID'
                ,company_id bigint  not null comment 'Company ID'
                ,category_id int   comment 'Category ID'
                ,document_id bigint  not null comment 'Document ID'
                ,access_user bigint  not null comment 'Access User ID'
                ,user_type int   comment 'User Type'
                ,access_datetime timestamp   comment 'Access Datetime'
                ,ip_address varchar(15)   comment 'IP Address'
                ,access_content varchar(255)   comment 'Access Content'
                ,create_user bigint   comment 'Create User'
                ,create_datetime timestamp   NULL DEFAULT CURRENT_TIMESTAMP comment 'Create Date'
                ,delete_user bigint   comment 'Delete User'
                ,delete_datetime timestamp  comment 'Delete Date'
                , constraint idx_t_log_doc_access primary key (log_id,company_id,category_id,document_id)
            ) comment 'アクセスログ' ";            
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
        Schema::dropIfExists('t_log_doc_access');
    }
};

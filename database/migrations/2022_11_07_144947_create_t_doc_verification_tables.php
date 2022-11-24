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
        Schema::table('t_doc_verification', function (Blueprint $table) {
            $sql = "CREATE TABLE t_doc_verification (
                company_id bigint  not null comment 'Company ID'
                ,category_id int  not null comment 'Category ID'
                ,veri_id bigint unsigned AUTO_INCREMENT not null comment 'Verification ID'
                ,veri_user bigint   comment 'Verification User'
                ,veri_date date   comment 'Verification Date'
                ,veri_status int   comment 'Verification Status'
                ,total_files int   comment 'Total Files'
                ,total_pages int   comment 'Total Pages'
                ,create_user bigint   comment 'Create User'
                ,create_datetime timestamp   NULL DEFAULT CURRENT_TIMESTAMP comment 'Create Date'
                ,update_user bigint   comment 'Update User'
                ,update_datetime timestamp   NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP comment 'Update Date'
                ,delete_user bigint   comment 'Delete User'
                ,delete_datetime timestamp   comment 'Delete Date'
                , constraint idx_t_document_verification primary key (veri_id,company_id,category_id)
            ) comment '書類一括検証' ";            
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
        Schema::dropIfExists('t_doc_verification');
    }
};

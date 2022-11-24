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
        Schema::table('t_doc_verification_file', function (Blueprint $table) {
            $sql = "CREATE TABLE t_doc_verification_file (
                document_id bigint  not null comment 'Document ID'
                ,company_id bigint  not null comment 'Company ID'
                ,category_id int  not null comment 'Category ID'
                ,veri_id bigint  not null comment 'Verification ID'
                ,veri_rslt_flg boolean not null DEFAULT 1 comment 'Verification Result'
                ,veri_dpi boolean   comment 'Verification DPI'
                ,veri_color_depth boolean   comment 'Verification Color Depth'
                ,veri_timestamp boolean   comment 'Verification Timestamp'
                ,veri_metadata boolean   comment 'Verification Metadata'
                ,veri_hash boolean   comment 'Verification Hash'
                ,veri_sign boolean   comment 'Verification Sign'
                ,veri_mutual_checks boolean   comment 'Verification Mutual Checks'
                ,create_user bigint   comment 'Create User'
                ,create_datetime timestamp   NULL DEFAULT CURRENT_TIMESTAMP comment 'Create Date'
                ,update_user bigint   comment 'Update User'
                ,update_datetime timestamp   NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP comment 'Update Date'
                ,delete_user bigint   comment 'Delete User'
                ,delete_datetime timestamp   comment 'Delete Date'
                , constraint idx_t_document_verification primary key (document_id,company_id,category_id)
            ) comment '書類一括検証ファイル' ";            
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
        Schema::dropIfExists('t_doc_verification_file');
    }
};

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
        Schema::table('m_iden_veri_document', function (Blueprint $table) {
            $sql = "CREATE TABLE m_iden_veri_document (
                identity_doc_id bigint unsigned AUTO_INCREMENT not null comment 'Identity Verification Document ID'
                ,identity_doc_code varchar(100)   comment 'Identity Verification Document Code'
                ,identity_doc_label varchar(100)   comment 'Identity Verification Document Label'
                ,effe_start_date date   comment 'Effective Start Date'
                ,effe_end_date date   comment 'Effective End Date'
                ,create_user bigint   comment 'Create User'
                ,create_datetime timestamp   NULL DEFAULT CURRENT_TIMESTAMP comment 'Create Date'
                ,update_user bigint   comment 'Update User'
                ,update_datetime timestamp   NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP comment 'Update Date'
                ,delete_user bigint   comment 'Delete User'
                ,delete_datetime timestamp   comment 'Delete Date'
                , constraint idx_m_iden_veri_document primary key (identity_doc_id)
            ) comment '本人確認書類マスタ' ";            
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
        Schema::dropIfExists('m_iden_veri_document');
    }
};

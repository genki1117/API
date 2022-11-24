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
        Schema::table('m_user_role', function (Blueprint $table) {
            $sql = "CREATE TABLE m_user_role (
				company_id bigint  not null comment 'Company ID'
				,user_id bigint  not null comment 'User ID'
				,admin_role boolean not null DEFAULT 0 comment 'Administrator Role'
				,template_set_role boolean not null DEFAULT 1 comment 'Template Setting Role'
				,workflow_set_role boolean not null DEFAULT 1 comment 'Workflow Setting Role'
				,master_set_role boolean not null DEFAULT 0 comment 'Master Setting Role'
				,archive_func_role boolean not null DEFAULT 0 comment 'Archive Function Role'
				,ts_role boolean not null DEFAULT 0 comment 'Timestamp Role'
				,bulk_ts_role boolean not null DEFAULT 0 comment 'Bulk Timestamp Role'
				,bulk_veri_func_role boolean not null DEFAULT 0 comment 'Bulk Verification Function Role'
				,cont_doc_app_role boolean not null DEFAULT 0 comment 'Contract Document Approve Role'
				,deal_doc_app_role boolean not null DEFAULT 0 comment 'Deal Document Approve Role'
				,int_doc_app_role boolean not null DEFAULT 0 comment 'Internal Document Approve Role'
				,arch_doc_app_role boolean not null DEFAULT 0 comment 'Archive Document Approve Role'
				,use_cert_role boolean not null DEFAULT 0 comment 'Use Certificate Role'
				,effe_start_date date   comment 'Effective Start Date'
				,effe_end_date date   comment 'Effective End Date'
				,create_user bigint   comment 'Create User'
				,create_datetime timestamp   NULL DEFAULT CURRENT_TIMESTAMP comment 'Create Date'
				,update_user bigint   comment 'Update User'
				,update_datetime timestamp   NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP comment 'Update Date'
				,delete_user bigint   comment 'Delete User'
				,delete_datetime timestamp   comment 'Delete Date'
				, constraint idx_m_user_role primary key (company_id,user_id)
			) comment 'ユーザ権限マスタ' ";			
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
        Schema::dropIfExists('m_user_role');
    }
};

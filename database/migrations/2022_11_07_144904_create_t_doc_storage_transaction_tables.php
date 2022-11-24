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
        Schema::table('t_doc_storage_transaction', function (Blueprint $table) {
            $sql = "CREATE TABLE t_doc_storage_transaction (
				document_id bigint  not null comment 'Document ID'
				,company_id bigint  not null comment 'Company ID'
				,template_id int   comment 'Template ID'
				,doc_type_id int  not null comment 'Document Type ID'
				,file_name varchar(255)   comment 'File Name'
				,file_size decimal(12,2)   comment 'File Size'
				,file_path varchar(300)   comment 'File Path'
				,file_hash varchar(32)   comment 'File Hash'
				,file_prot_flg boolean not null DEFAULT 1 comment 'File Protection Flag'
				,file_prot_pw_flg boolean not null DEFAULT 1 comment 'File Protection Password Flag'
				,file_timestamp_flg boolean not null DEFAULT 1 comment 'File Timestamp Flag'
				,file_sign boolean not null DEFAULT 1 comment 'File Sign'
				,width int   comment 'Width'
				,height int   comment 'Height'
				,dpi int   comment 'DPI'
				,color_depth int   comment 'Color Depth'
				,pdf_type varchar(5)   comment 'PDF Type'
				,pdf_version varchar(4)   comment 'PDF Version'
				,sign_position json   comment 'Sign Position'
				,total_pages int   comment 'Total Pages'
				,create_user bigint   comment 'Create User'
				,create_datetime timestamp   NULL DEFAULT CURRENT_TIMESTAMP comment 'Create Date'
				,update_user bigint   comment 'Update User'
				,update_datetime timestamp   NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP comment 'Update Date'
				,delete_user bigint   comment 'Delete User'
				,delete_datetime timestamp   comment 'Delete Date'
				, constraint idx_t_document_storage primary key (document_id,company_id)
			) comment '取引書類容量' ";			
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
        Schema::dropIfExists('t_doc_storage_transaction');
    }
};

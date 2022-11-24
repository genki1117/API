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
        Schema::table('t_document_contract', function (Blueprint $table) {
            $sql = "CREATE TABLE t_document_contract (
				document_id bigint unsigned AUTO_INCREMENT not null comment 'Document ID'
				,company_id bigint  not null comment 'Company ID'
				,category_id int  not null comment 'Function ID'
				,template_id int   comment 'Template ID'
				,doc_type_id int   comment 'Document Type ID'
				,status_id int   comment 'Status ID'
				,cont_start_date date   comment 'Contract Start Date'
				,cont_end_date date   comment 'Contract End Date'
				,conc_date date   comment 'Conclusion Date'
				,effective_date date   comment 'Effective Date'
				,cancel_date date   comment 'Cancel date'
				,expiry_date date   comment 'Expiry Date'
				,doc_no varchar(30)   comment 'Document Number'
				,ref_doc_no json   comment 'Reference Document Number'
				,product_name varchar(255)   comment 'Product Name'
				,title varchar(255)   comment 'Title'
				,amount decimal(24,6)   comment 'Amount'
				,currency_id bigint   comment 'Currency ID'
				,counter_party_id bigint   comment 'Counter Party ID'
				,remarks text   comment 'Remarks'
				,doc_info json   comment 'Document Info'
				,sign_level bit(1)   comment 'Sign Level'
				,create_user bigint   comment 'Create User'
				,create_datetime timestamp   NULL DEFAULT CURRENT_TIMESTAMP comment 'Create Date'
				,update_user bigint   comment 'Update User'
				,update_datetime timestamp   NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP comment 'Update Date'
				,delete_user bigint   comment 'Delete User'
                ,delete_datetime timestamp   comment 'Delete Date'
				, constraint idx_t_document primary key (document_id,company_id)
				, constraint idx_t_document_temp unique (document_id,company_id,template_id)
			) comment '契約書類' ";			
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
        Schema::dropIfExists('t_document_contract');
    }
};

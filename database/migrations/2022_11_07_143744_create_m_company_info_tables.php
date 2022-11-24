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
        Schema::table('m_company_info', function (Blueprint $table) {
            $sql = "CREATE TABLE m_company_info (
				company_id bigint unsigned AUTO_INCREMENT not null comment 'Company ID'
				,company_name varchar(255)   comment 'Company Name'
				,company_name_kana varchar(255)   comment 'Company Name Kana'
				,tax_id varchar(13)   comment 'Tax Id'
				,effe_tax_start_date date   comment 'Effective TAX Start Date'
				,effe_tax_end_date date   comment 'Effective TAX End Date'
				,country varchar(3)  DEFAULT 'JPY' comment 'Country'
				,company_branch varchar(255)   comment 'Company Branch'
				,zip_code varchar(7)   comment 'Zip Code'
				,address varchar(255)   comment 'Address'
				,tel varchar(13)   comment 'Tel'
				,contract_start_date date   comment 'Contract Start Date'
				,contract_end_date date   comment 'Contract End Date'
				,contract_service varchar(255)   comment 'Contract Service'
				,contractor varchar(255)   comment 'Contractor'
				,invoice_department varchar(255)   comment 'Invoice Department'
				,invoice_attention varchar(255)   comment 'Invoice Attention'
				,domain varchar(50)   comment 'Domain'
				,effe_start_date date   comment 'Effective Start Date'
				,effe_end_date date   comment 'Effective End Date'
				,create_user bigint   comment 'Create User'
				,create_datetime timestamp   NULL DEFAULT CURRENT_TIMESTAMP comment 'Create Date'
				,update_user bigint   comment 'Update User'
				,update_datetime timestamp   NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP comment 'Update Date'
				,delete_user bigint   comment 'Delete User'
                ,delete_datetime timestamp   comment 'Delete Date'
				, constraint idx_m_company_info primary key (company_id)
			) comment '会社マスタ情報' ";			
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
        Schema::dropIfExists('m_company_info');
    }
};

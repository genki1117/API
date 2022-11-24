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
        Schema::table('t_cert_application', function (Blueprint $table) {
            $sql = "CREATE TABLE t_cert_application (
                company_id bigint  not null comment 'Company ID'
                ,user_id bigint  not null comment 'User ID'
                ,app_id bigint unsigned AUTO_INCREMENT not null comment 'Applicant ID'
                ,app_date date  not null comment 'Applicant Date'
                ,certificate_type bigint   comment 'Certificate Type'
                ,app_status bigint   comment 'Applicant Status'
                ,tax_id varchar(13)   comment 'Tax ID'
                ,company_name varchar(255)   comment 'Company Name'
                ,company_name_kana varchar(255)   comment 'Company Name Kana'
                ,company_branch varchar(255)   comment 'Company Branch'
                ,company_dept varchar(255)   comment 'Company Deparrtment'
                ,app_name varchar(255)   comment 'Applicant Name'
                ,app_name_kana varchar(255)   comment 'Applicant Name Kana'
                ,app_email varchar(255)   comment 'Applicant Email'
                ,app_credentials varchar(255)   comment 'Applicant Credentials'
                ,iden_veri_doc_type bigint   comment 'Identity Verification Document Type'
                ,create_user bigint   comment 'Create User'
                ,create_datetime timestamp   NULL DEFAULT CURRENT_TIMESTAMP comment 'Create Date'
                ,update_user bigint   comment 'Update User'
                ,update_datetime timestamp   NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP comment 'Update Date'
                ,delete_user bigint   comment 'Delete User'
                ,delete_datetime timestamp   comment 'Delete Date'
                , constraint idx_t_cert_application primary key (app_id,company_id,user_id)
                , constraint idx_t_cert_application_date unique (company_id,user_id)
            ) comment '証明書申請情報' ";
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
        Schema::dropIfExists('t_cert_application');
    }
};

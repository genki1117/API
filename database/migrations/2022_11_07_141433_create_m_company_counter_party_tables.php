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
        Schema::table('m_company_counter_party', function (Blueprint $table) {
            $sql = "CREATE TABLE m_company_counter_party (
                company_id bigint  not null comment 'Company ID'
                ,counter_party_id bigint unsigned AUTO_INCREMENT not null comment 'Counter Party ID'
                ,counter_party_name varchar(255)   comment 'Counter Party Name'
                ,counter_party_name_kana varchar(255)   comment 'Counter Party Name Kana'
                ,counter_party_tax_id varchar(13)   comment 'Counter Party Tax Id'
                ,counter_party_country varchar(3)   comment 'Counter Party Country'
                ,counter_party_branch varchar(255)   comment 'Counter Party Branch'
                ,counter_party_zip_code varchar(7)   comment 'Counter Party Zip Code'
                ,counter_party_address varchar(255)   comment 'Counter Party Address'
                ,counter_party_tel varchar(13)   comment 'Counter Party Tel'
                ,effe_start_date date   comment 'Effective Start Date'
                ,effe_end_date date   comment 'Effective End Date'
                ,create_user bigint   comment 'Create User'
                ,create_datetime timestamp   NULL DEFAULT CURRENT_TIMESTAMP comment 'Create Date'
                ,update_user bigint   comment 'Update User'
                ,update_datetime timestamp   NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP comment 'Update Date'
                ,delete_user bigint   comment 'Delete User'
                ,delete_datetime timestamp   comment 'Delete Date'
                , constraint idx_m_company_counter_party primary key (counter_party_id,company_id)
            ) comment '相手先マスタ' ";            
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
        Schema::dropIfExists('m_company_counter_party');
    }
};

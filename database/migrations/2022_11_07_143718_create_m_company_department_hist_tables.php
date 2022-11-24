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
        Schema::table('m_company_department_hist', function (Blueprint $table) {
            $sql = "CREATE TABLE m_company_department_hist (
                history_id bigint unsigned AUTO_INCREMENT not null comment 'History ID'
                ,company_id bigint  not null comment 'Company ID'
                ,department_id bigint  not null comment 'Department ID'
                ,department_name varchar(255)   comment 'Department Name'
                ,parent_id bigint   comment 'Parent ID'
                ,effe_start_date date   comment 'Effective Start Date'
                ,effe_end_date date   comment 'Effective End Date'
                ,create_user bigint   comment 'Create User'
                ,create_datetime timestamp   comment 'Create Date'
                ,update_user bigint   comment 'Update User'
                ,update_datetime timestamp   comment 'Update Date'
                ,delete_user bigint   comment 'Delete User'
                ,delete_datetime timestamp   comment 'Delete Date'
                , constraint idx_m_company_department primary key (history_id,company_id,department_id)
            ) comment '部署マスタ履歴' ";            
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
        Schema::dropIfExists('m_company_department_hist');
    }
};

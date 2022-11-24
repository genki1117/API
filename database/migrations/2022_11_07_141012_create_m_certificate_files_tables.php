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
        Schema::table('m_certificate_files', function (Blueprint $table) {
            $sql = "CREATE TABLE m_certificate_files (
                user_id bigint unsigned AUTO_INCREMENT not null comment 'User ID'
                ,cert_type bit(1)   comment 'Certificate Type'
                ,cert_file_name varchar(255)   comment 'Certificate File Name'
                ,cert_file blob   comment 'Certificate File'
                ,cert_rgst_date date   comment 'Certificate Regist Date'
                ,cert_exp_date date   comment 'Certificate Expire Date'
                ,create_user bigint   comment 'Create User'
                ,create_datetime timestamp   NULL DEFAULT CURRENT_TIMESTAMP comment 'Create Date'
                ,update_user bigint   comment 'Update User'
                ,update_datetime timestamp   NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP comment 'Update Date'
                ,delete_user bigint   comment 'Delete User'
                ,delete_datetime timestamp   comment 'Delete Date'
                , constraint idx_m_certificate_files primary key (user_id)
            ) comment '電子証明書管理マスタ' ";            
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
        Schema::dropIfExists('m_certificate_files');
    }
};

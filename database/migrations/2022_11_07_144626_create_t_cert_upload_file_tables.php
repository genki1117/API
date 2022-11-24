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
        Schema::table('t_cert_upload_file', function (Blueprint $table) {
            $sql = "CREATE TABLE t_cert_upload_file (
                comapny_id bigint  not null comment 'Company ID'
                ,user_id bigint  not null comment 'User ID'
                ,applicant_id bigint  not null comment 'Applicant ID'
                ,up_doc_type bit(1)   comment 'Upload Document Type'
                ,up_file_name varchar(255)   comment 'Upload File Name'
                ,up_file blob   comment 'Upload File'
                ,create_user bigint   comment 'Create User'
                ,create_datetime timestamp   NULL DEFAULT CURRENT_TIMESTAMP comment 'Create Date'
                ,update_user bigint   comment 'Update User'
                ,update_datetime timestamp   NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP comment 'Update Date'
                ,delete_user bigint   comment 'Delete User'
                ,delete_datetime timestamp   comment 'Delete Date'
                , constraint idx_t_cert_upload_file primary key (comapny_id,user_id,applicant_id)
            ) comment '本人確認書類アップロードファイル' ";            
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
        Schema::dropIfExists('t_cert_upload_file');
    }
};

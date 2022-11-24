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
        Schema::table('t_download_file', function (Blueprint $table) {
            $sql = "CREATE TABLE t_download_file (
                company_id bigint  not null comment 'Company ID'
                ,user_id bigint  not null comment 'User ID'
                ,dl_file_id bigint  not null comment 'Download File ID'
                ,dl_file_name varchar(255)   comment 'Download File Name'
                ,dl_file_path varchar(1000)   comment 'Download File Path'
                ,dl_file_size bigint   comment 'Download File Size'
                ,create_user bigint   comment 'Create User'
                ,create_datetime timestamp   NULL DEFAULT CURRENT_TIMESTAMP comment 'Create Date'
                ,update_user bigint   comment 'Update User'
                ,update_datetime timestamp   NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP comment 'Update Date'
                ,delete_user bigint   comment 'Delete User'
                ,delete_datetime timestamp   comment 'Delete Date'
                , constraint idx_download_file primary key (company_id,user_id,dl_file_id)
            ) comment 'ダウンロードURL' ";            
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
        Schema::dropIfExists('t_download_file');
    }
};

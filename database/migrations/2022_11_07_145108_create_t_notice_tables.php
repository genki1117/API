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
        Schema::table('t_notice', function (Blueprint $table) {
            $sql = "CREATE TABLE t_notice (
                notice_id bigint unsigned AUTO_INCREMENT not null comment 'Notice ID'
                ,company_id bigint  not null comment 'Company ID'
                ,disp_start_date date   comment 'Display Start Date'
                ,disp_end_date date   comment 'Display End Date'
                ,title varchar(255)   comment 'Title'
                ,content text   comment 'Content'
                ,create_user bigint   comment 'Create User'
                ,create_datetime timestamp   NULL DEFAULT CURRENT_TIMESTAMP comment 'Create Date'
                ,update_user bigint   comment 'Update User'
                ,update_datetime timestamp   NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP comment 'Update Date'
                ,delete_user bigint   comment 'Delete User'
                ,delete_datetime timestamp   comment 'Delete Date'
                , constraint idx_notice_id primary key (notice_id)
            ) comment 'お知らせ管理' ";            
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
        Schema::dropIfExists('t_notice');
    }
};

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
        Schema::table('t_system_mail', function (Blueprint $table) {
            $sql = "CREATE TABLE t_system_mail (
                system_mail_id bigint unsigned AUTO_INCREMENT not null comment 'System Mail ID'
                ,send_date date   comment 'Send Date'
                ,mail_to json   comment 'Mail To'
                ,title varchar(255)   comment 'Mail Title'
                ,content text   comment 'Mail Content'
                ,create_user bigint   comment 'Create User'
                ,create_datetime timestamp   NULL DEFAULT CURRENT_TIMESTAMP comment 'Create Date'
                ,update_user bigint   comment 'Update User'
                ,update_datetime timestamp   NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP comment 'Update Date'
                ,delete_user bigint   comment 'Delete User'
                ,delete_datetime timestamp   comment 'Delete Date'
                , constraint idx_sys_notice_id primary key (system_mail_id)
            ) comment 'システムメール' ";            
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
        Schema::dropIfExists('t_system_mail');
    }
};

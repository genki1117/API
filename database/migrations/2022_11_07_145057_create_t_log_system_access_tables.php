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
        Schema::table('t_log_system_access', function (Blueprint $table) {
            $sql = "CREATE TABLE t_log_system_access (
                log_id bigint unsigned AUTO_INCREMENT not null comment 'Log ID'
                ,company_id bigint  not null comment 'Company ID'
                ,user_id bigint  not null comment 'User ID'
                ,user_name varchar(255)   comment 'User Name'
                ,ip_address varchar(15)   comment 'IP Address'
                ,access_datetime timestamp   comment 'Access Datetime'
                ,access_type bit(1)   comment 'Access Type'
                ,access_func_name varchar(255)   comment 'Access Function Name'
                ,action json   comment 'Action'
                ,create_user bigint   comment 'Create User'
                ,create_datetime timestamp   NULL DEFAULT CURRENT_TIMESTAMP comment 'Create Date'
                ,delete_user bigint   comment 'Delete User'
                ,delete_datetime timestamp   comment 'Delete Date'
                , constraint idx_t_log_system_access primary key (log_id,company_id,user_id)
            ) comment 'システムアクセスログ' ";            
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
        Schema::dropIfExists('t_log_system_access');
    }
};

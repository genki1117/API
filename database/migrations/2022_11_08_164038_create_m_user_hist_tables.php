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
        Schema::table('m_user_hist', function (Blueprint $table) {
			$sql = "CREATE TABLE m_user_hist (
				history_id bigint unsigned AUTO_INCREMENT not null comment 'History ID'
				,company_id bigint  not null comment 'Company ID'
				,user_id bigint  not null comment 'User ID'
				,user_type_id int   comment 'User Type ID'
				,email varchar(255)  not null comment 'Email'
				,family_name varchar(50)   comment 'Family Name'
				,first_name varchar(50)   comment 'First Name'
				,full_name varchar(150)   comment 'Full Name'
				,family_name_kana varchar(100)   comment 'Family Name Kana'
				,first_name_kana varchar(100)   comment 'First Name Kana'
				,full_name_kana varchar(255)   comment 'Full Name Kana'
				,tel varchar(15)   comment 'Tel'
				,mobile_number varchar(15)   comment 'Mobile Number'
				,language varchar(2)   comment 'Language'
				,profile_image longblob   comment 'Profile Image'
				,guest_company_name varchar(255)   comment 'Guest Company Name'
				,group_array json   comment 'Group Array'
				,position varchar(50)   comment 'Position'
				,active_flg bit(1)   comment 'Active Flg'
				,active_date date   comment 'Active Date'
				,password varchar(256)   comment 'Password'
				,effe_start_date date   comment 'Effective Start Date'
				,effe_end_date date   comment 'Effective End Date'
				,create_user bigint   comment 'Create User'
				,create_datetime timestamp   NULL DEFAULT CURRENT_TIMESTAMP comment 'Create Date'
				,update_user bigint   comment 'Update User'
				,update_datetime timestamp   NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP comment 'Update Date'
				,delete_user bigint   comment 'Delete User'
				,delete_datetime timestamp   comment 'Delete Datetime'
				, constraint idx_m_user primary key (history_id,company_id,user_id)
			) comment 'ユーザマスタ履歴' ";						
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
        Schema::dropIfExists('m_user_hist');
    }
};

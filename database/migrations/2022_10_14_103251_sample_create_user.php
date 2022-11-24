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
        Schema::table('user', function (Blueprint $table) {
            $sql = "CREATE TABLE user (
                id INT unsigned AUTO_INCREMENT not null comment 'ID'
                , mail_address VARCHAR(256) not null comment 'メールアドレス'
                , password VARCHAR(256) not null comment 'パスワード'
                , is_deleted INT comment '削除フラグ'
                , constraint audits_PKC primary key (id)
            ) comment 'ユーザー' ";
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
        //
    }
};

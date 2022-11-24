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
        Schema::table('temp_token', function (Blueprint $table) {
            $sql = "CREATE TABLE temp_token (
                token varchar(255)  not null comment 'Token'
                ,type varchar(100)   comment 'Type'
                ,data json   comment 'Data'
                ,expiry_date timestamp   comment 'Expiry Date'
                ,delete_user bigint   comment 'Delete User'
                ,delete_datetime timestamp   comment 'Delete Datetime'
            ) comment 'トークン' ";            
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
        Schema::dropIfExists('temp_token');
    }
};

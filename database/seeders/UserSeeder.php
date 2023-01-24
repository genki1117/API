<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Carbon\CarbonImmutable;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('m_user')->insert([
            [
                'company_id' => 1,
                'user_id' => 1,
                'user_type_id' => 0,
                'email' => 'host1@email.com',
                'family_name' => '大化',
                'first_name' => 'テスト',
                'full_name' => '大化　テスト',
                'family_name_kana' => 'タイカ',
                'first_name_kana' => 'テスト',
                'full_name_kana' => 'タイカ　テスト',
                'tel' => '0123456789',
                'mobile_number' => '0123456789',
                'language' => 'ja',
                'profile_image' => null,
                'company_counter_party_id' => null,
                'group_array' => '[1]',
                'position' => '社員',
                'active_flg' => 1,
                'active_date' => null,
                'password' => Hash::make('taika'),
                'effe_start_date' => null,
                'effe_end_date' => null,
                'create_user' => 1,
                'create_datetime' => CarbonImmutable::now(),
                'update_user' => 1,
                'update_datetime' => CarbonImmutable::now(),
                'delete_user' => null,
                'delete_datetime' => null,
            ],
       ]);
       
    }
}

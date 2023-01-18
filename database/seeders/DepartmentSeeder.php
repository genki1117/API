<?php

namespace Database\Seeders;

use Carbon\CarbonImmutable;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('m_company_department')->insert([
            [
                'company_id' => 1,
                'department_id' => 1,
                'department_name' => '人事部',
                'parent_id' => null,
                'effe_start_date' => null,
                'effe_end_date' => null,
                'create_user' => 1,
                'create_datetime' => null,
                'update_user' => null,
                'update_datetime' => null,
                'delete_user' => null,
                'delete_datetime' => null
            ],
            [
                'company_id' => 1,
                'department_id' => 2,
                'department_name' => '経理部',
                'parent_id' => null,
                'effe_start_date' => null,
                'effe_end_date' => null,
                'create_user' => 1,
                'create_datetime' => null,
                'update_user' => null,
                'update_datetime' => null,
                'delete_user' => null,
                'delete_datetime' => null
            ],
            [
                'company_id' => 1,
                'department_id' => 3,
                'department_name' => '総務部',
                'parent_id' => null,
                'effe_start_date' => null,
                'effe_end_date' => null,
                'create_user' => 1,
                'create_datetime' => null,
                'update_user' => null,
                'update_datetime' => null,
                'delete_user' => null,
                'delete_datetime' => null
            ],
            [
                'company_id' => 1,
                'department_id' => 4,
                'department_name' => '採用部',
                'parent_id' => 1,
                'effe_start_date' => null,
                'effe_end_date' => null,
                'create_user' => 1,
                'create_datetime' => null,
                'update_user' => null,
                'update_datetime' => null,
                'delete_user' => null,
                'delete_datetime' => null
            ],
            [
                'company_id' => 1,
                'department_id' => 5,
                'department_name' => '研修部',
                'parent_id' => 1,
                'effe_start_date' => null,
                'effe_end_date' => null,
                'create_user' => 1,
                'create_datetime' => null,
                'update_user' => null,
                'update_datetime' => null,
                'delete_user' => null,
                'delete_datetime' => null
            ],
            [
                'company_id' => 1,
                'department_id' => 6,
                'department_name' => '新人研修部',
                'parent_id' => 5,
                'effe_start_date' => null,
                'effe_end_date' => null,
                'create_user' => 1,
                'create_datetime' => null,
                'update_user' => null,
                'update_datetime' => null,
                'delete_user' => null,
                'delete_datetime' => null
            ],
            [
                'company_id' => 1,
                'department_id' => 7,
                'department_name' => '経理部1課',
                'parent_id' => 2,
                'effe_start_date' => null,
                'effe_end_date' => null,
                'create_user' => 1,
                'create_datetime' => null,
                'update_user' => null,
                'update_datetime' => null,
                'delete_user' => null,
                'delete_datetime' => null
            ],
            [
                'company_id' => 1,
                'department_id' => 8,
                'department_name' => '経理部2課',
                'parent_id' => null,
                'effe_start_date' => null,
                'effe_end_date' => null,
                'create_user' => 1,
                'create_datetime' => null,
                'update_user' => null,
                'update_datetime' => null,
                'delete_user' => null,
                'delete_datetime' => null
            ],
            [
                'company_id' => 1,
                'department_id' => 9,
                'department_name' => '総務部1',
                'parent_id' => 3,
                'effe_start_date' => null,
                'effe_end_date' => null,
                'create_user' => 1,
                'create_datetime' => null,
                'update_user' => null,
                'update_datetime' => null,
                'delete_user' => null,
                'delete_datetime' => null
            ],
            [
                'company_id' => 1,
                'department_id' => 10,
                'department_name' => '総務部2',
                'parent_id' => 3,
                'effe_start_date' => null,
                'effe_end_date' => null,
                'create_user' => 1,
                'create_datetime' => null,
                'update_user' => null,
                'update_datetime' => null,
                'delete_user' => null,
                'delete_datetime' => null
            ],
            [
                'company_id' => 1,
                'department_id' => 11,
                'department_name' => '総務部2-1',
                'parent_id' => 3,
                'effe_start_date' => null,
                'effe_end_date' => null,
                'create_user' => 1,
                'create_datetime' => null,
                'update_user' => null,
                'update_datetime' => null,
                'delete_user' => null,
                'delete_datetime' => null
            ],
            [
                'company_id' => 1,
                'department_id' => 12,
                'department_name' => '総務部2-2',
                'parent_id' => 3,
                'effe_start_date' => null,
                'effe_end_date' => null,
                'create_user' => 1,
                'create_datetime' => null,
                'update_user' => null,
                'update_datetime' => null,
                'delete_user' => null,
                'delete_datetime' => null
            ],
            [
                'company_id' => 1,
                'department_id' => 13,
                'department_name' => '総務部2-2-1',
                'parent_id' => 12,
                'effe_start_date' => null,
                'effe_end_date' => null,
                'create_user' => 1,
                'create_datetime' => null,
                'update_user' => null,
                'update_datetime' => null,
                'delete_user' => null,
                'delete_datetime' => null
            ],
        ]);

        \DB::table('m_user')->insert([
            [
                'company_id' => 1,
                'user_id' => 1,
                'user_type_id' => 0,
                'email' => 'test@test.jp',
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

       \DB::table('m_user')->insert([
        [
            'company_id' => 1,
            'user_id' => 2,
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

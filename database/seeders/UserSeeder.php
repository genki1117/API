<?php

namespace Database\Seeders;

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
        \DB::table('m_company_info')->insert([
            [
                'company_id' => 1,
                'company_name' => 'テスト会社',
                'company_name_kana' => 'テストガイシャ',
                'tax_id' => null,
                'effe_tax_start_date' => null,
                'effe_tax_end_date' => null,
                'country' => 'JA',
                'company_branch' => null,
                'zip_code' => '9999999',
                'address' => 'テスト住所',
                'tel' => '0123456789',
                'contract_start_date' => null,
                'contract_end_date' => null,
                'contract_service' => 'テスト',
                'contractor' => 'テスト',
                'invoice_department' => null,
                'invoice_attention' => null,
                'domain' => null,
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
        \DB::table('m_company_department')->insert([
            [
                'company_id' => 1,
                'department_id' => 1,
                'department_name' => 1,
                'parent_id' => 1,
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
       \DB::table('m_user_role')->insert([
            [
                'company_id' => 1,
                'user_id' => 1,
                'admin_role' => true,
                'template_set_role' => true,
                'workflow_set_role' => true,
                'master_set_role' => true,
                'archive_func_role' => true,
                'ts_role' => true,
                'bulk_ts_role' => true,
                'bulk_veri_func_role' => true,
                'cont_doc_app_role' => true,
                'deal_doc_app_role' => true,
                'int_doc_app_role' => true,
                'arch_doc_app_role' => true,
                'use_cert_role' => true,
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

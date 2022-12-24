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
    }
}

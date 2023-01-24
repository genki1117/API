<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\CarbonImmutable;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TempTokenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('temp_token')->insert([
            'token' => 'test-token-1',
            'type' => '承諾依頼',
            'data' => json_encode([
                'document_id' => 1,
                'category_id' => 0,
                'company_id' => 1
            ]),
            'expiry_date' => '2023-01-20 00:00:00'
        ]);

        DB::table('temp_token')->insert([
            'token' => 'test-token-2',
            'type' => 'test-type2',
            'data' => json_encode([
                'document_id' => 2,
                'category_id' => 0,
                'company_id' => 1
            ]),
            'expiry_date' => '2023-01-21 00:00:00'
        ]);

        DB::table('temp_token')->insert([
            'token' => 'test-token-3',
            'type' => '承諾依頼',
            'data' => json_encode([
                'document_id' => 3,
                'category_id' => 0,
                'company_id' => 1
            ]),
            'expiry_date' => '2023-01-22 00:00:00'
        ]);

        DB::table('temp_token')->insert([
            'token' => 'test-token-4',
            'type' => 'test-type4',
            'data' => json_encode([
                'document_id' => 4,
                'category_id' => 1,
                'company_id' => 1
            ]),
            'expiry_date' => '2023-01-23 00:00:00'
        ]);

        DB::table('temp_token')->insert([
            'token' => 'test-token-5',
            'type' => 'test-type5',
            'data' => json_encode([
                'document_id' => 5,
                'category_id' => 2,
                'company_id' => 1
            ]),
            'expiry_date' => '2023-01-24 00:00:00'
        ]);

        DB::table('temp_token')->insert([
            'token' => 'test-token-6',
            'type' => '承諾依頼',
            'data' => json_encode([
                'document_id' => 6,
                'category_id' => 3,
                'company_id' => 1
            ]),
            'expiry_date' => '2023-01-25 00:00:00'
        ]);

        DB::table('temp_token')->insert([
            'token' => 'test-token-7',
            'type' => 'test-type7',
            'data' => json_encode([
                'document_id' => 7,
                'category_id' => 0,
                'company_id' => 1
            ]),
            'expiry_date' => '2023-01-26 00:00:00'
        ]);

        DB::table('temp_token')->insert([
            'token' => 'test-token-8',
            'type' => 'test-type8',
            'data' => json_encode([
                'document_id' => 8,
                'category_id' => 1,
                'company_id' => 1
            ]),
            'expiry_date' => '2023-01-27 00:00:00'
        ]);

        DB::table('temp_token')->insert([
            'token' => 'test-token-9',
            'type' => '承諾依頼',
            'data' => json_encode([
                'document_id' => 9,
                'category_id' => 2,
                'company_id' => 1
            ]),
            'expiry_date' => '2023-01-28 00:00:00'
        ]);

        DB::table('temp_token')->insert([
            'token' => 'test-token-10',
            'type' => '承諾依頼',
            'data' => json_encode([
                'document_id' => 10,
                'category_id' => 3,
                'company_id' => 1
            ]),
            'expiry_date' => '2023-01-29 00:00:00'
        ]);
    }
}

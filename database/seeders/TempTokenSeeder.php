<?php

namespace Database\Seeders;

use Carbon\CarbonImmutable;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TempTokenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('temp_token')->insert([
            [
                'token'           => 'test-token-1',
                'type'            => 'test-type-1',
                'expiry_date'     => '2023-10-10',
                'delete_user'     => null,
                'delete_datetime' => null
            ],
        ]);
    }
}

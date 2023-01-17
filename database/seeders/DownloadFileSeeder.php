<?php

namespace Database\Seeders;

use Carbon\CarbonImmutable;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DownloadFileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('t_download_file')->insert([
            [
                'company_id'      => 1,
                'user_id'         => 1,
                'dl_file_id'      => 1,
                'dl_file_name'    => '8a1182c882b5fe86af15c61bba718eea_t.jpg',
                'dl_file_path'    => '/var/www/html/testImageFile/test/8a1182c882b5fe86af15c61bba718eea_t.jpg',
                'dl_file_size'    => 1,
                'create_user'     => 1,
                'create_datetime' => CarbonImmutable::now(),
                'update_user'     => 1,
                'update_datetime' => CarbonImmutable::now(),
                'delete_user'     => null,
                'delete_datetime' => null

            ],
        ]);
    }
}

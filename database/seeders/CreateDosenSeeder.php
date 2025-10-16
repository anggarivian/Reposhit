<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class CreateDosenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
            DB::table('dosens')->insert([
            [
                'id' => 1,
                'nama' => 'Dr. Ir. Hj. Endah Lisarini, SE., MM',
                'nip' => '0427106502',
                'kontak' => '081234567890',
                'jurusan_id' => 1,
                'status' => 1,
            ],
            [
                'id' => 2,
                'nama' => 'Rosda Malia, SP., M.Si',
                'nip' => '0405037203',
                'kontak' => '081234567891',
                'jurusan_id' => 2,
                'status' => 1,
            ],
        ]);
    }
}
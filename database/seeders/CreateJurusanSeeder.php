<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class CreateJurusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('jurusans')->insert([
        ['id' => 1, 'nama_jurusan' => 'Agribisnis', 'kode_jurusan' => 'AGR'],
        ['id' => 2, 'nama_jurusan' => 'Agroteknologi', 'kode_jurusan' => 'AGT'],
        ['id' => 3, 'nama_jurusan' => 'program go japan', 'kode_jurusan' => 'PGP'],
        ['id' => 4, 'nama_jurusan' => 'rekognisi pembelajaran lampau', 'kode_jurusan' => 'RPL'],
    ]);
    }
}

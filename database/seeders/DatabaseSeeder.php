<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CreateJurusanSeeder::class);
        $this->call(CreateDosenSeeder::class);
        $this->call(CreateRolesSeeder::class);
        $this->call(CreateUsersSeeder::class);
    }
}

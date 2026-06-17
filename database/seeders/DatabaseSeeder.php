<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Menjalankan seed untuk database aplikasi.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
        ]);
    }
}

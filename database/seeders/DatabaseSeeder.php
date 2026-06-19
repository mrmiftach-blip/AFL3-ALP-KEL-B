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
            StudyProgramSeeder::class,
            UserSeeder::class,
            JobPostingSeeder::class,
            ApplicationSeeder::class,
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Buat 1 akun admin tetap (updateOrCreate agar tidak duplikat jika dijalankan ulang)
        User::updateOrCreate(
            ['email' => 'bmkg26@riau.com'],
            [
                'name'     => 'Admin',
                'password' => bcrypt('1234'),
            ]
        );
    }
}

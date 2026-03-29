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
        User::withTrashed()->updateOrCreate(
            ['email' => 'admin@footfacemedical.test'],
            [
                'name' => 'Admin',
                'surname' => 'User',
                'phone' => '+37120000001',
                'password' => 'Adm!n-9Kf2Qp7L',
                'role' => 'admin',
                'deleted_at' => null,
            ]
        );

        User::withTrashed()->updateOrCreate(
            ['email' => 'podolog@footfacemedical.test'],
            [
                'name' => 'Podologs',
                'surname' => 'User',
                'phone' => '+37120000002',
                'password' => 'Podolog-7Vd4!mQ2',
                'role' => 'podolog',
                'deleted_at' => null,
            ]
        );
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminAndSupirSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@gmail.com',
            'role' => 'Admin',
            'password' => Hash::make('admin')
        ]);
        User::create([
            'name' => 'Supir',
            'email' => 'supir@gmail.com',
            'role' => 'Supir',
            'password' => Hash::make('supir')
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'email' => 'frendi@gmail.com',
            'name' => 'Frendi',
            'password' => Hash::make('password'),
            'status' => 'aktif',
            'avatar' => 'https://avataaars.io',
            'phone' => '089525458522',
            'role_id' => 4
        ]);
        User::create([
            'email' => 'asha@gmail.com',
            'name' => 'Asha',
            'password' => Hash::make('password'),
            'status' => 'aktif',
            'avatar' => 'https://avataaars.io',
            'phone' => '081234567890',
            'role_id' => 5
        ]);
        User::create([
            'email' => 'test1@gmail.com',
            'name' => 'Test 1',
            'password' => Hash::make('password'),
            'status' => 'aktif',
            'avatar' => 'https://avataaars.io',
            'phone' => '081234567891',
            'role_id' => 6
        ]);
    }
}

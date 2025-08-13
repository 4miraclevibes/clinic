<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\UserDetail;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'userDetails' => [
                    'name' => 'admin',
                    'no_hp' => '081234567890',
                    'alamat' => 'Jl. Admin No. 1',
                    'role' => 'admin',
                ]
            ],
            [
                'name' => 'user',
                'email' => 'user@example.com',
                'password' => Hash::make('password'),
                'userDetails' => [
                    'name' => 'user',
                    'no_hp' => '081234567890',
                    'alamat' => 'Jl. User No. 1',
                    'role' => 'user',
                ],
            ],
            [
                'name' => 'doctor',
                'email' => 'doctor@example.com',
                'password' => Hash::make('password'),
                'userDetails' => [
                    'name' => 'doctor',
                    'no_hp' => '081234567890',
                    'alamat' => 'Jl. Doctor No. 1',
                    'role' => 'doctor',
                ]
            ]
        ];

        foreach ($users as $userData) {
            // Pisahkan data user dari userDetails
            $userDetails = $userData['userDetails'];
            unset($userData['userDetails']);

            // Buat user terlebih dahulu
            $user = User::create($userData);

            // Buat user detail dengan user_id yang baru dibuat
            UserDetail::create([
                'user_id' => $user->id,
                'name' => $userDetails['name'],
                'no_hp' => $userDetails['no_hp'],
                'alamat' => $userDetails['alamat'],
                'role' => $userDetails['role'],
            ]);
        }
    }
}

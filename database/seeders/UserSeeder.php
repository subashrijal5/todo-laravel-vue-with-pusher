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
        $users = [
            ['name' => 'admin admin', 'email' => 'admin@gmail.com', 'password' => Hash::make('123456')],
            ['name' => 'user', 'email' => 'user@gmail.com',  'password' => Hash::make('123456')]
        ];
        foreach ($users as  $user) {
            User::create($user);
        }
    }
}

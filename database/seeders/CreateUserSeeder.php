<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CreateUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Manager',
                'email' => 'manager@mail.com',
                'level' => '1',
                'password' => bcrypt('123'),
            ],

            [
                'name' => 'Admin',
                'email' => 'admin@mail.com',
                'level' => '2',
                'password' => bcrypt('123'),
            ]
            ];

           foreach($users as $user){
                User::create($user);
           };
    }
}

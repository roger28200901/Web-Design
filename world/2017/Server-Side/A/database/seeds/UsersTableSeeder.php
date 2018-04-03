<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'username' => 'admin',
            'password' => 'adminpass',
            'role' => 'ADMIN'
        ]);
        User::create([
            'username' => 'user1',
            'password' => 'user1pass',
            'role' => 'USER'
        ]);
        User::create([
            'username' => 'user2',
            'password' => 'user2pass',
            'role' => 'USER'
        ]);
    }
}

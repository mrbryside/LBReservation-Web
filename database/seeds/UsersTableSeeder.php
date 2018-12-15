<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'Firstname' => 'Library',
            'Lastname' => 'Admin',
            'Phone' => '0990000000',
            'email' => 'admin@gmail.com',
            'StudentID' => 'admin',
            'status' => 1,
            'password' => bcrypt(123456),
        ]);
    }
}

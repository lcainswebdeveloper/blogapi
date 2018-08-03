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
        \DB::table('users')->insert([
            'name' => 'Test User',
            'email' => 'test@test.co.uk',
            'password' => bcrypt('123456'), // secret
            'remember_token' => str_random(10)
        ]);
    }
}

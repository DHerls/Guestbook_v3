<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Dan Herlihy',
            'username' => 'DHerls',
            'password' => bcrypt('password'),
            'admin' => true
        ]);

        DB::table('users')->insert([
            'name' => 'John Doe',
            'username' => 'employee',
            'password' => bcrypt('password'),
        ]);
    }
}

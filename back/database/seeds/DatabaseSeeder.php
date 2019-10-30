<?php

use Illuminate\Database\Seeder;
use database\seeds\RolsTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
        	'name' => 'admin',
        	'emaphp il' => 'admin@mail.com',
        	'role_id' => 1,
        	'password' => password_hash(12345678, PASSWORD_DEFAULT),
        ]);

        DB::table('roles')->insert([
            'id' => 1,
            'name' => 'admin'
        ]);

        DB::table('roles')->insert([
            'id' => 2,
            'name' => 'guest'
        ]);
    }
}

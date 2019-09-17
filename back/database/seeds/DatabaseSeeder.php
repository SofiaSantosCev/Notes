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
        // $this->call(UsersTableSeeder::class);
        DB::table('users')->insert([
        	'name' => 'admin',
        	'email' => 'admin@mail.com',
        	'rol_id' => 1,
        	'password' => password_hash(12345678, PASSWORD_DEFAULT),
        ]);

        DB::table('rols')->insert([
            'id' => 1,
            'name' => 'admin'
        ]);

        DB::table('rols')->insert([
            'id' => 2,
            'name' => 'guest'
        ]);
    }
}

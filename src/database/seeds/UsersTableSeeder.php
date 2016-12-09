<?php

namespace Larafolio\database\seeds;

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
            'name'     => 'admin',
            'email'    => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);
    }
}

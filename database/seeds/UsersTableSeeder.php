<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert(
            [
                'name'=>'admin',
                'email'=>'test@test.com',
                'email_verified_at'=> new DateTime('now'),
                'password'=>password_hash('secret',PASSWORD_DEFAULT),
            ]
        );
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'first_name'=>'super',
            'last_name'=>'admin',
            'email'=>'super_admin@mail.com',
            'password'=>bcrypt('123456789'),

        ]);

        $user->attachRole('super_admin');
    }
}

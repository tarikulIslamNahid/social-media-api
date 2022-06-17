<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\User::create([
            'first_name' => 'Tarikul Islam',
            'last_name' => 'Nahid',
            'slug' => 'tarikul-islam-nahid',
            'email' => 'tarikul@gmail.com',
            'password' => Hash::make('12121212'),
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
        DB::table('users')->insert([
            'name' => 'Erfan',
            'email' => 'test@mail.com',
            'password' => Hash::make('123456'),
            'is_admin' => true,
            'credit' => 100,
            'phone' => '09366223096',
            'city' => 'Tehran',
            'address' => 'Saadat Abad',
        ]);
    }
}

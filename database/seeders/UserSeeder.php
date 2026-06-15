<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
{
    // Admin User
    DB::table('users')->insert([
        'first_name' => 'Erwin',
        'last_name' => 'Victorio',
        'middle_name' => 'E',
        'NickName' => 'Erwin',
        'role' => '1', // Admin role id
        'username' => "admin",
        'department' => 'administrator',
        'password' => Hash::make('admin123456')
    ]);

    // Cashier User
    DB::table('users')->insert([
        'first_name' => 'Jane',
        'last_name' => 'Doe',
        'middle_name' => 'M',
        'NickName' => 'Jane',
        'role' => '2', // Binago ko ang role ginawa kong '2' para maiba sa Admin
        'username' => 'cashier01',
        'department' => 'Accounting Department', // Pwede mo rin itong palitan ng 'Treasury' o 'Finance' depende sa tawag sa inyo
        'password' => Hash::make('cashier123456')
    ]);
}
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AfterSalesAccountSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->updateOrInsert(
            ['username' => 'aftersales01'],
            [
                'first_name' => 'After',
                'last_name' => 'Sales',
                'middle_name' => 'A',
                'NickName' => 'After Sales',
                'role' => '4',
                'department' => 'After Sales',
                'password' => Hash::make('aftersales123456'),
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
    }
}

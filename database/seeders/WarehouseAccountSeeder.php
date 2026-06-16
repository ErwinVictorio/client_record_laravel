<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class WarehouseAccountSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->updateOrInsert(
            ['username' => 'warehouse01'],
            [
                'first_name' => 'Warehouse',
                'last_name' => 'Admin',
                'middle_name' => 'W',
                'NickName' => 'Warehouse',
                'role' => '5',
                'department' => 'Warehouse',
                'password' => Hash::make('warehouse123456'),
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
    }
}

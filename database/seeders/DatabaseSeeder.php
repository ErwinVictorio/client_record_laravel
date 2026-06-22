<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\ClientSeeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            ClientSeeder::class,
            AfterSalesAccountSeeder::class,
            WarehouseAccountSeeder::class,
            AfterSalesRecordSeeder::class,
        ]);
    }
}

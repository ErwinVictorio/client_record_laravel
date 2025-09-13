<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();
        $totalRecords = 1;

        for ($i = 0; $i < $totalRecords; $i++) {
            DB::table('clients')->insert([
                'company_name' => $faker->company,
                'contact_number' => $faker->phoneNumber,
                'email' => $faker->unique()->safeEmail,
                'address' => $faker->address,
                'contact_person' => $faker->name,
                'contact_number_person' => $faker->phoneNumber,
                'salesman_id' => 5
            ]);

            // Show progress
            if ($i % 5 == 0) {
                $this->command->info('Seeded ' . ($i + 1) . ' of ' . $totalRecords . ' records');
            }
        }

        $this->command->info('All user records have been seeded successfully!');
}
}
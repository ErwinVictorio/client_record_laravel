<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class autoPartsRecords extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $faker = \Faker\Factory::create();
        $totalRecords = 1000;

        for($i = 0; $i < $totalRecords; $i++){
            DB::table('client_record_for_maintenance_and_repairs')->insert([
              'company_name' => $faker->company,
              'address' => $faker->address,
              'email' => $faker->email,
              'bank_account_number' => $faker->iban(),
              'contact_number' => $faker->phoneNumber(),
              'contact_person' => $faker->name(),
              'contact_number_person' => $faker->name(),
              'job_order_number' => $faker->uuid(),
               'salesmanId' => 5
            ]);

            if ($i % 5 == 0) {
               $this->command->info('Seeded ' . ($i + 1) . ' of ' . $totalRecords . ' records');
            }
              $this->command->info('All user records have been seeded successfully!');
        }
    }
}

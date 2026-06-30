<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RepairAndMaintenanceRecordSeeder extends Seeder
{
    public function run(): void
    {
        $faker = \Faker\Factory::create();

        $salesmanIds = DB::table('users')
            ->where('role', 3)
            ->pluck('id')
            ->all();

        if (empty($salesmanIds)) {
            $fallbackSalesmanId = DB::table('users')->insertGetId([
                'first_name' => 'Seed',
                'last_name' => 'Salesman',
                'middle_name' => 'R',
                'NickName' => 'Seed Sales',
                'username' => 'seed_salesman_' . now()->format('YmdHis'),
                'password' => Hash::make('password'),
                'role' => 3,
                'department' => 'Sales Department',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $salesmanIds = [$fallbackSalesmanId];
        }

        $records = [];

        for ($i = 1; $i <= 100; $i++) {
            $records[] = [
                'company_name' => $faker->company(),
                'address' => $faker->address(),
                'email' => $faker->unique()->safeEmail(),
                'bank_account_number' => $faker->optional()->iban(),
                'contact_number' => $faker->numerify('09#########'),
                'contact_person' => $faker->name(),
                'contact_number_person' => $faker->numerify('09#########'),
                'serial_number' => strtoupper($faker->bothify('SN-####-????')),
                'date_sold' => $faker->dateTimeBetween('-3 years', 'now')->format('Y-m-d'),
                'salesmanId' => $faker->randomElement($salesmanIds),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('client_record_for_maintenance_and_repairs')->insert($records);

        $this->command?->info('Seeded 100 repair and maintenance records successfully.');
    }
}

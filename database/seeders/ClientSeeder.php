<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();
        $totalRecords = 1000;
        $batchSize = 100;

        $salesmanIds = DB::table('users')
            ->where('role', '3')
            ->pluck('id')
            ->all();

        if (empty($salesmanIds)) {
            $salesmanIds = DB::table('users')->pluck('id')->all();
        }

        if (empty($salesmanIds)) {
            $this->command?->warn('No users found. Please seed users before clients.');
            return;
        }

        $statuses = ['Pending', 'For Approval', 'Sold'];
        $hasVehicleSpecifications = Schema::hasColumn('clients', 'vehicle_specifications');
        $hasYearModel = Schema::hasColumn('clients', 'year_model');
        $records = [];

        for ($i = 1; $i <= $totalRecords; $i++) {
            $status = $faker->randomElement($statuses);
            $vehicle = [
                'brand' => $faker->randomElement(['Toyota', 'Komatsu', 'Mitsubishi', 'TCM', 'Heli', 'Hangcha']),
                'model' => strtoupper($faker->bothify('??-###')),
                'loading_capacity' => $faker->randomElement(['1.5 tons', '2 tons', '2.5 tons', '3 tons', '5 tons']),
                'lifting_height' => $faker->randomElement(['3 meters', '4.5 meters', '5 meters', '6 meters']),
                'mast_type' => $faker->randomElement(['Duplex', 'Triplex', 'Full Free Lift']),
                'power_type' => $faker->randomElement(['Diesel', 'Gasoline', 'LPG', 'Electric']),
                'tire' => $faker->randomElement(['Pneumatic', 'Solid', 'Cushion']),
                'fork_length' => $faker->randomElement(['920 mm', '1070 mm', '1220 mm', '1520 mm']),
                'attachment' => $faker->randomElement(['N/A', 'Side Shifter', 'Fork Positioner', 'Clamp']),
            ];

            $record = [
                'company_name' => $faker->company,
                'contact_number' => $faker->phoneNumber,
                'email' => $faker->unique()->safeEmail,
                'address' => $faker->address,
                'salesList_no' => $status === 'Pending' ? null : 'SL-' . $faker->unique()->numerify('######'),
                'contact_person' => $faker->name,
                'contact_number_person' => $faker->phoneNumber,
                'bank_account_number' => $status === 'Pending' ? null : $faker->bankAccountNumber,
                'item_name' => $vehicle['brand'],
                'model_number' => $vehicle['model'],
                'specification' => implode(', ', [
                    $vehicle['loading_capacity'],
                    $vehicle['lifting_height'],
                    $vehicle['mast_type'],
                    $vehicle['power_type'],
                ]),
                'quantity' => $faker->numberBetween(1, 5),
                'salesman_id' => $faker->randomElement($salesmanIds),
                'status' => $status,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if ($hasVehicleSpecifications) {
                $record['vehicle_specifications'] = json_encode([$vehicle]);
            }

            if ($hasYearModel) {
                $record['year_model'] = (string) $faker->numberBetween(2018, 2026);
            }

            $records[] = $record;

            if (count($records) === $batchSize) {
                DB::table('clients')->insert($records);
                $records = [];
                $this->command?->info('Seeded ' . $i . ' of ' . $totalRecords . ' client records.');
            }
        }

        if (! empty($records)) {
            DB::table('clients')->insert($records);
        }

        $this->command?->info('All client records have been seeded successfully!');
    }
}

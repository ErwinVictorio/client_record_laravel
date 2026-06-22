<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AfterSalesRecordSeeder extends Seeder
{
    public function run(): void
    {
        $faker = \Faker\Factory::create();
        $now = now();

        $userId = $this->getAfterSalesUserId();
        $soldClientIds = $this->getSoldClientIds($faker, 100);
        $maintenanceJobOrders = $this->getMaintenanceJobOrders($faker, 100);

        for ($i = 1; $i <= 100; $i++) {
            DB::table('after_sales_records')->updateOrInsert(
                [
                    'service_type' => 'PMS',
                    'job_order_number' => 'PMS-JO-' . str_pad((string) $i, 4, '0', STR_PAD_LEFT),
                ],
                [
                    'client_id' => $faker->randomElement($soldClientIds),
                    'user_id' => $userId,
                    'warranty_type' => $faker->randomElement(['UNDER WARRANTY', 'OUT OF WARRANTY']),
                    'pms_number' => 'PMS-' . str_pad((string) $i, 4, '0', STR_PAD_LEFT),
                    'job_order_date' => $faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
                    'description' => $faker->randomElement([
                        'Preventive maintenance service inspection',
                        'Engine oil and filter replacement',
                        'Hydraulic system inspection and adjustment',
                        'Battery and electrical system check',
                        'Brake, tire, and safety inspection',
                    ]),
                    'remarks' => $faker->optional(0.8)->sentence(8),
                    'updated_at' => $now,
                    'created_at' => $now,
                ]
            );
        }

        for ($i = 1; $i <= 100; $i++) {
            DB::table('after_sales_records')->updateOrInsert(
                [
                    'service_type' => 'Other',
                    'job_order_number' => $maintenanceJobOrders[$i - 1],
                ],
                [
                    'client_id' => null,
                    'user_id' => $userId,
                    'warranty_type' => null,
                    'pms_number' => null,
                    'job_order_date' => $faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
                    'description' => $faker->randomElement([
                        'Customer requested unit diagnosis',
                        'Replacement parts verification',
                        'Repair follow-up and service monitoring',
                        'General service support request',
                        'Non-PMS maintenance coordination',
                    ]),
                    'remarks' => $faker->optional(0.8)->sentence(8),
                    'updated_at' => $now,
                    'created_at' => $now,
                ]
            );
        }

        $this->command?->info('Seeded 100 PMS records and 100 Other records successfully.');
    }

    private function getAfterSalesUserId(): int
    {
        $userId = DB::table('users')->where('role', '4')->value('id');

        if ($userId) {
            return (int) $userId;
        }

        return (int) DB::table('users')->insertGetId([
            'first_name' => 'After',
            'last_name' => 'Sales',
            'middle_name' => 'A',
            'NickName' => 'AfterSales',
            'username' => 'seed_aftersales_' . now()->format('YmdHis'),
            'password' => Hash::make('aftersales123456'),
            'role' => '4',
            'department' => 'After Sales',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function getSoldClientIds($faker, int $minimum): array
    {
        $clientIds = DB::table('clients')
            ->where('status', 'Sold')
            ->whereNotNull('salesList_no')
            ->pluck('id')
            ->all();

        if (count($clientIds) >= $minimum) {
            return $clientIds;
        }

        $salesmanId = $this->getSalesmanId();
        $recordsToCreate = $minimum - count($clientIds);

        for ($i = 1; $i <= $recordsToCreate; $i++) {
            $clientIds[] = DB::table('clients')->insertGetId([
                'company_name' => $faker->company(),
                'contact_number' => $faker->numerify('09#########'),
                'email' => 'seed_sold_client_' . now()->format('YmdHis') . '_' . $i . '@example.com',
                'address' => $faker->address(),
                'salesList_no' => 'SL-SEED-' . now()->format('YmdHis') . '-' . str_pad((string) $i, 4, '0', STR_PAD_LEFT),
                'contact_person' => $faker->name(),
                'contact_number_person' => $faker->numerify('09#########'),
                'bank_account_number' => $faker->optional()->iban(),
                'item_name' => $faker->randomElement(['Toyota Forklift', 'Komatsu Forklift', 'Mitsubishi Forklift']),
                'model_number' => strtoupper($faker->bothify('MDL-###??')),
                'specification' => $faker->randomElement(['2.5 tons Diesel', '3 tons Electric', '5 tons LPG']),
                'quantity' => $faker->numberBetween(1, 3),
                'salesman_id' => $salesmanId,
                'status' => 'Sold',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return $clientIds;
    }

    private function getSalesmanId(): int
    {
        $salesmanId = DB::table('users')->where('role', '3')->value('id');

        if ($salesmanId) {
            return (int) $salesmanId;
        }

        return (int) DB::table('users')->insertGetId([
            'first_name' => 'Seed',
            'last_name' => 'Salesman',
            'middle_name' => 'S',
            'NickName' => 'Seed Sales',
            'username' => 'seed_salesman_' . now()->format('YmdHis'),
            'password' => Hash::make('password'),
            'role' => '3',
            'department' => 'Sales Department',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function getMaintenanceJobOrders($faker, int $minimum): array
    {
        $jobOrders = DB::table('client_record_for_maintenance_and_repairs')
            ->whereNotNull('job_order_number')
            ->limit($minimum)
            ->pluck('job_order_number')
            ->all();

        if (count($jobOrders) >= $minimum) {
            return $jobOrders;
        }

        $salesmanId = $this->getSalesmanId();
        $recordsToCreate = $minimum - count($jobOrders);

        for ($i = 1; $i <= $recordsToCreate; $i++) {
            $jobOrderNumber = 'JO-SEED-' . now()->format('YmdHis') . '-' . str_pad((string) $i, 4, '0', STR_PAD_LEFT);

            DB::table('client_record_for_maintenance_and_repairs')->insert([
                'company_name' => $faker->company(),
                'address' => $faker->address(),
                'email' => 'seed_maintenance_' . now()->format('YmdHis') . '_' . $i . '@example.com',
                'bank_account_number' => $faker->optional()->iban(),
                'contact_number' => $faker->numerify('09#########'),
                'contact_person' => $faker->name(),
                'contact_number_person' => $faker->numerify('09#########'),
                'job_order_number' => $jobOrderNumber,
                'serial_number' => strtoupper($faker->bothify('SN-####-????')),
                'date_sold' => $faker->dateTimeBetween('-3 years', 'now')->format('Y-m-d'),
                'salesmanId' => $salesmanId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $jobOrders[] = $jobOrderNumber;
        }

        return $jobOrders;
    }
}

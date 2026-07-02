<?php

use App\Livewire\AfterSales\Dashboard;
use App\Livewire\Modals\AfterSalesEditRecord;
use App\Models\AfterSalesRecord;
use App\Models\ClientRecordForMaintenanceAndRepair;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

uses(RefreshDatabase::class);

function createAfterSalesTestUser(string $username = 'after_sales_flow'): User
{
    $userId = DB::table('users')->insertGetId([
        'first_name' => 'After',
        'last_name' => 'Sales',
        'middle_name' => 'A',
        'NickName' => 'After Sales',
        'username' => $username,
        'password' => bcrypt('password'),
        'role' => 4,
        'department' => 'After Sales',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return User::findOrFail($userId);
}

function createPendingMaintenanceRecord(string $companyName, User $salesman, ?string $jobOrderNumber = null): ClientRecordForMaintenanceAndRepair
{
    return ClientRecordForMaintenanceAndRepair::create([
        'company_name' => $companyName,
        'address' => 'Maintenance Address',
        'email' => strtolower(str_replace(' ', '-', $companyName)).'@example.test',
        'contact_number' => '09123456789',
        'contact_person' => 'Maintenance Contact',
        'contact_number_person' => '09987654321',
        'job_order_number' => $jobOrderNumber,
        'serial_number' => 'SERIAL-PRIMARY',
        'vehicle_specifications' => [
            [
                'brand' => 'Toyota',
                'model' => '8FD30',
                'serial_or_plate_number' => 'SERIAL-PRIMARY',
                'loading_capacity' => '3 tons',
            ],
            [
                'brand' => 'Komatsu',
                'model' => 'FD25',
                'serial_or_plate_number' => 'PLATE-SECONDARY',
            ],
        ],
        'salesmanId' => $salesman->id,
    ]);
}

it('updates every editable MSD field instead of deleting the record', function () {
    $userId = DB::table('users')->insertGetId([
        'first_name' => 'MSD',
        'last_name' => 'Admin',
        'middle_name' => 'A',
        'NickName' => 'MSD Admin',
        'username' => 'msd_admin',
        'password' => bcrypt('password'),
        'role' => 4,
        'department' => 'After Sales',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $clientId = DB::table('clients')->insertGetId([
        'company_name' => 'Test Client',
        'contact_number' => '09123456789',
        'email' => 'client@example.test',
        'address' => 'Test Address',
        'salesList_no' => 'SC-001',
        'contact_person' => 'Test Person',
        'contact_number_person' => '09123456780',
        'salesman_id' => $userId,
        'status' => 'Sold',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $record = AfterSalesRecord::create([
        'client_id' => $clientId,
        'user_id' => $userId,
        'service_type' => 'PMS',
        'change_type' => 'WITHOUT CHANGE',
        'warranty_type' => 'UNDER WARRANTY',
        'pms_number' => '1',
        'job_order_number' => 'JO-001',
        'job_order_date' => '2026-06-01',
        'description' => 'Old description',
        'remarks' => 'Old remarks',
    ]);

    $modal = app(AfterSalesEditRecord::class);
    $modal->mount($record->id);

    expect($modal->recordId)->toBe($record->id)
        ->and($modal->changeType)->toBe('WITHOUT CHANGE')
        ->and($modal->description)->toBe('Old description')
        ->and($modal->remarks)->toBe('Old remarks');

    $modal->changeType = 'WITH CHANGE';
    $modal->warrantyType = 'UNDER WARRANTY';
    $modal->pmsNumber = '2';
    $modal->jobOrderNumber = 'JO-002';
    $modal->jobOrderDate = '2026-06-29';
    $modal->description = 'Updated description';
    $modal->remarks = 'Updated remarks';
    $modal->updateRecord();

    expect($modal->recordId)->toBe($record->id);

    expect($record->fresh())
        ->change_type->toBe('WITH CHANGE')
        ->warranty_type->toBe('OUT OF WARRANTY')
        ->pms_number->toBe('2')
        ->job_order_number->toBe('JO-002')
        ->description->toBe('Updated description')
        ->remarks->toBe('Updated remarks');
});

it('automatically sets ASAP records with change to out of warranty', function () {
    $component = app(Dashboard::class);
    $component->section = 'asap';
    $component->warranty_type = 'UNDER WARRANTY';

    $component->updatedChangeType('WITH CHANGE');

    expect($component->warranty_type)->toBe('OUT OF WARRANTY');
});

it('does not force warranty type for without change or Other records', function () {
    $component = app(Dashboard::class);
    $component->section = 'asap';
    $component->warranty_type = 'UNDER WARRANTY';

    $component->updatedChangeType('WITHOUT CHANGE');

    expect($component->warranty_type)->toBe('UNDER WARRANTY');

    $component->section = 'other';
    $component->updatedChangeType('WITH CHANGE');

    expect($component->warranty_type)->toBe('UNDER WARRANTY');
});

it('requires the new change type when saving an MSD record', function () {
    $component = app(Dashboard::class);

    expect(fn () => $component->save())
        ->toThrow(ValidationException::class);
});

it('saves PMS and Other service types for both ASAP and maintenance sources', function () {
    $afterSalesUser = createAfterSalesTestUser('service_type_combinations');
    $this->actingAs($afterSalesUser);

    $clientId = DB::table('clients')->insertGetId([
        'company_name' => 'ASAP Combination Client',
        'contact_number' => '09123456789',
        'email' => 'asap-combination@example.test',
        'address' => 'ASAP Address',
        'salesList_no' => 'SC-COMBINATION',
        'contact_person' => 'ASAP Contact',
        'contact_number_person' => '09123456780',
        'salesman_id' => $afterSalesUser->id,
        'status' => 'Sold',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    foreach (['PMS', 'Other'] as $index => $serviceType) {
        $component = app(Dashboard::class);
        $component->section = 'asap';
        $component->selectedClientId = $clientId;
        $component->service_type = $serviceType;
        $component->change_type = 'WITHOUT CHANGE';
        $component->pms_number = $serviceType === 'PMS' ? '1' : 'must be cleared';
        $component->job_order_number = 'ASAP-COMBO-'.($index + 1);
        $component->save();
    }

    foreach (['PMS', 'Other'] as $index => $serviceType) {
        $maintenanceRecord = createPendingMaintenanceRecord(
            'Maintenance Combination '.($index + 1),
            $afterSalesUser
        );

        $component = app(Dashboard::class);
        $component->section = 'other';
        $component->selectedMaintenanceRecordId = $maintenanceRecord->id;
        $component->service_type = $serviceType;
        $component->change_type = 'WITHOUT CHANGE';
        $component->pms_number = $serviceType === 'PMS' ? '2' : 'must be cleared';
        $component->job_order_number = 'MAINTENANCE-COMBO-'.($index + 1);
        $component->save();
    }

    expect(AfterSalesRecord::where('job_order_number', 'ASAP-COMBO-1')->firstOrFail())
        ->client_id->toBe($clientId)
        ->service_type->toBe('PMS')
        ->pms_number->toBe('1')
        ->and(AfterSalesRecord::where('job_order_number', 'ASAP-COMBO-2')->firstOrFail())
        ->client_id->toBe($clientId)
        ->service_type->toBe('Other')
        ->pms_number->toBe('must be cleared')
        ->and(AfterSalesRecord::where('job_order_number', 'MAINTENANCE-COMBO-1')->firstOrFail())
        ->client_id->toBeNull()
        ->service_type->toBe('PMS')
        ->pms_number->toBe('2')
        ->and(AfterSalesRecord::where('job_order_number', 'MAINTENANCE-COMBO-2')->firstOrFail())
        ->client_id->toBeNull()
        ->service_type->toBe('Other')
        ->pms_number->toBe('must be cleared');
});

it('keeps the PMS number when the service type changes to Other', function () {
    $component = app(Dashboard::class);
    $component->pms_number = '3';

    $component->updatedServiceType('Other');

    expect($component->pms_number)->toBe('3');
});

it('searches ASAP and Other records by client name or JO number', function () {
    $userId = DB::table('users')->insertGetId([
        'first_name' => 'Search',
        'last_name' => 'Tester',
        'middle_name' => 'A',
        'NickName' => 'Search Tester',
        'username' => 'search_tester',
        'password' => bcrypt('password'),
        'role' => 4,
        'department' => 'After Sales',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $clientId = DB::table('clients')->insertGetId([
        'company_name' => 'Alpha Equipment Corporation',
        'contact_number' => '09123456789',
        'email' => 'alpha@example.test',
        'address' => 'Alpha Address',
        'salesList_no' => 'SC-ALPHA',
        'contact_person' => 'Alpha Contact',
        'contact_number_person' => '09123456780',
        'salesman_id' => $userId,
        'status' => 'Sold',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    DB::table('client_record_for_maintenance_and_repairs')->insert([
        'company_name' => 'Beta Industrial Services',
        'address' => 'Beta Address',
        'email' => 'beta@example.test',
        'contact_number' => '09111111111',
        'contact_person' => 'Beta Contact',
        'contact_number_person' => '09222222222',
        'job_order_number' => 'OTHER-JO-456',
        'salesmanId' => $userId,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $asapRecord = AfterSalesRecord::create([
        'client_id' => $clientId,
        'user_id' => $userId,
        'service_type' => 'PMS',
        'change_type' => 'WITH CHANGE',
        'pms_number' => '1',
        'job_order_number' => 'ASAP-JO-123',
    ]);

    $otherRecord = AfterSalesRecord::create([
        'user_id' => $userId,
        'service_type' => 'Other',
        'change_type' => 'WITHOUT CHANGE',
        'job_order_number' => 'OTHER-JO-456',
    ]);

    $component = app(Dashboard::class);
    $component->jobOrderSearch = 'Alpha Equipment';
    $asapByClient = $component->render()->getData()['records']->pluck('id');

    $component->jobOrderSearch = 'ASAP-JO-123';
    $asapByJobOrder = $component->render()->getData()['records']->pluck('id');

    $component->section = 'other';
    $component->service_type = 'Other';
    $component->jobOrderSearch = 'Beta Industrial';
    $otherByClient = $component->render()->getData()['records']->pluck('id');

    expect($asapByClient)->toContain($asapRecord->id)
        ->and($asapByJobOrder)->toContain($asapRecord->id)
        ->and($otherByClient)->toContain($otherRecord->id);
});

it('paginates ASAP and Other records independently', function () {
    $afterSalesUser = createAfterSalesTestUser('independent_pagination');
    $clientId = DB::table('clients')->insertGetId([
        'company_name' => 'Paginated ASAP Client',
        'contact_number' => '09123456789',
        'email' => 'paginated-asap@example.test',
        'address' => 'Pagination Address',
        'salesList_no' => 'SC-PAGINATION',
        'contact_person' => 'Pagination Contact',
        'contact_number_person' => '09123456780',
        'salesman_id' => $afterSalesUser->id,
        'status' => 'Sold',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    foreach (range(1, 12) as $number) {
        AfterSalesRecord::create([
            'client_id' => $clientId,
            'user_id' => $afterSalesUser->id,
            'service_type' => 'Other',
            'change_type' => 'WITHOUT CHANGE',
            'job_order_number' => 'ASAP-PAGE-'.$number,
        ]);

        AfterSalesRecord::create([
            'user_id' => $afterSalesUser->id,
            'service_type' => 'Other',
            'change_type' => 'WITHOUT CHANGE',
            'job_order_number' => 'OTHER-PAGE-'.$number,
        ]);
    }

    $component = app(Dashboard::class);
    $asapRecords = $component->render()->getData()['records'];

    $component->section = 'other';
    $otherRecords = $component->render()->getData()['records'];

    expect($asapRecords->getPageName())->toBe('asapPage')
        ->and($asapRecords->count())->toBe(10)
        ->and($asapRecords->total())->toBe(12)
        ->and($otherRecords->getPageName())->toBe('otherPage')
        ->and($otherRecords->count())->toBe(10)
        ->and($otherRecords->total())->toBe(12);
});

it('searches pending maintenance records by company and excludes records with a JO', function () {
    $afterSalesUser = createAfterSalesTestUser('pending_company_search');
    $pendingRecord = createPendingMaintenanceRecord('Alpha Logistics Corporation', $afterSalesUser);
    $assignedRecord = createPendingMaintenanceRecord('Alpha Logistics Corporation', $afterSalesUser, 'JO-ASSIGNED');

    $component = app(Dashboard::class);
    $component->section = 'other';
    $component->service_type = 'Other';
    $component->maintenanceCompanySearch = 'Alpha Logistics';
    $component->searchMaintenanceCompany();

    $searchResults = $component->render()->getData()['maintenanceSearchResults']->pluck('id');

    expect($searchResults)
        ->toContain($pendingRecord->id)
        ->not->toContain($assignedRecord->id);
});

it('assigns one JO to a pending maintenance record containing multiple vehicles', function () {
    $afterSalesUser = createAfterSalesTestUser('assign_multi_vehicle_jo');
    $maintenanceRecord = createPendingMaintenanceRecord('Multi Vehicle Company', $afterSalesUser);
    $this->actingAs($afterSalesUser);

    $component = app(Dashboard::class);
    $component->section = 'other';
    $component->service_type = 'Other';
    $component->change_type = 'WITHOUT CHANGE';
    $component->selectedMaintenanceRecordId = $maintenanceRecord->id;
    $component->job_order_number = 'JO-MULTI-001';
    $component->job_order_date = '2026-06-30';
    $component->save();

    $afterSalesRecord = AfterSalesRecord::where('job_order_number', 'JO-MULTI-001')->firstOrFail();

    expect($maintenanceRecord->fresh())
        ->job_order_number->toBe('JO-MULTI-001')
        ->vehicle_specifications->toHaveCount(2)
        ->and($afterSalesRecord->maintenance_record_id)->toBe($maintenanceRecord->id)
        ->and($afterSalesRecord->maintenanceRecord->vehicle_specifications)->toHaveCount(2);
});

it('rejects a maintenance record that received a JO before save', function () {
    $afterSalesUser = createAfterSalesTestUser('stale_pending_record');
    $maintenanceRecord = createPendingMaintenanceRecord('Stale Company', $afterSalesUser, 'JO-ALREADY-ASSIGNED');
    $this->actingAs($afterSalesUser);

    $component = app(Dashboard::class);
    $component->section = 'other';
    $component->service_type = 'Other';
    $component->change_type = 'WITH CHANGE';
    $component->selectedMaintenanceRecordId = $maintenanceRecord->id;
    $component->job_order_number = 'JO-SHOULD-NOT-SAVE';
    $component->save();

    expect($component->getErrorBag()->has('selectedMaintenanceRecordId'))->toBeTrue()
        ->and(AfterSalesRecord::where('job_order_number', 'JO-SHOULD-NOT-SAVE')->exists())->toBeFalse()
        ->and($maintenanceRecord->fresh()->job_order_number)->toBe('JO-ALREADY-ASSIGNED');
});

it('keeps an edited Other JO synchronized with its maintenance record', function () {
    $afterSalesUser = createAfterSalesTestUser('edit_other_jo');
    $maintenanceRecord = createPendingMaintenanceRecord('Editable Company', $afterSalesUser, 'JO-OLD');
    $afterSalesRecord = AfterSalesRecord::create([
        'maintenance_record_id' => $maintenanceRecord->id,
        'user_id' => $afterSalesUser->id,
        'service_type' => 'Other',
        'change_type' => 'WITHOUT CHANGE',
        'job_order_number' => 'JO-OLD',
    ]);
    $this->actingAs($afterSalesUser);

    $modal = app(AfterSalesEditRecord::class);
    $modal->mount($afterSalesRecord->id);
    $modal->jobOrderNumber = 'JO-UPDATED';
    $modal->updateRecord();

    expect($afterSalesRecord->fresh()->job_order_number)->toBe('JO-UPDATED')
        ->and($maintenanceRecord->fresh()->job_order_number)->toBe('JO-UPDATED');
});

<?php

use App\Livewire\AfterSales\Dashboard;
use App\Models\AfterSalesRecord;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

uses(RefreshDatabase::class);

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

    $component = app(Dashboard::class);
    $component->editRecord($record->id);

    expect($component->editingRecordId)->toBe($record->id)
        ->and($component->change_type)->toBe('WITHOUT CHANGE');

    $component->change_type = 'WITH CHANGE';
    $component->warranty_type = 'OUT OF WARRANTY';
    $component->pms_number = '2';
    $component->job_order_number = 'JO-002';
    $component->job_order_date = '2026-06-29';
    $component->description = 'Updated description';
    $component->remarks = 'Updated remarks';
    $component->save();

    expect($component->editingRecordId)->toBeNull();

    expect($record->fresh())
        ->change_type->toBe('WITH CHANGE')
        ->warranty_type->toBe('OUT OF WARRANTY')
        ->pms_number->toBe('2')
        ->job_order_number->toBe('JO-002')
        ->description->toBe('Updated description')
        ->remarks->toBe('Updated remarks');
});

it('requires the new change type when saving an MSD record', function () {
    $component = app(Dashboard::class);

    expect(fn () => $component->save())
        ->toThrow(ValidationException::class);
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

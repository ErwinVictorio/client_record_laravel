<?php

use App\Livewire\Modals\CreateRepairAndMaintenaceRecord;
use App\Livewire\Modals\EditRepairAndMaintence;
use App\Livewire\Salesman\RepairAndMaintence;
use App\Models\ClientRecordForMaintenanceAndRepair;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;

uses(RefreshDatabase::class);

function createSalesmanForRepairAndMaintenance(string $username = 'salesman_repair_test'): User
{
    $userId = DB::table('users')->insertGetId([
        'first_name' => 'Sales',
        'last_name' => 'Executive',
        'middle_name' => 'A',
        'NickName' => 'Salesman',
        'username' => $username,
        'password' => bcrypt('password'),
        'role' => 2,
        'department' => 'Sales',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return User::findOrFail($userId);
}

function createMaintenanceRecordForSalesman(User $salesman, ?string $jobOrderNumber): ClientRecordForMaintenanceAndRepair
{
    return ClientRecordForMaintenanceAndRepair::create([
        'company_name' => 'Repair Client',
        'address' => 'Test Address',
        'email' => 'repair@example.test',
        'contact_number' => '09123456789',
        'job_order_number' => $jobOrderNumber,
        'serial_number' => 'SN-001',
        'date_sold' => '2026-06-30',
        'contact_person' => 'Test Contact',
        'contact_number_person' => '09987654321',
        'salesmanId' => $salesman->id,
    ]);
}

it('does not show JO controls on the salesman repair and maintenance page', function () {
    $salesman = createSalesmanForRepairAndMaintenance();
    createMaintenanceRecordForSalesman($salesman, 'EXISTING-JO-001');

    Livewire::actingAs($salesman)
        ->test(RepairAndMaintence::class)
        ->assertDontSee('Search JO Number')
        ->assertDontSee('Job Order Number');
});

it('searches maintenance records by company name and serial or plate number', function () {
    $salesman = createSalesmanForRepairAndMaintenance();
    $companyMatch = createMaintenanceRecordForSalesman($salesman, null);
    $companyMatch->update(['company_name' => 'Acme Equipment Corporation']);

    $serialMatch = createMaintenanceRecordForSalesman($salesman, null);
    $serialMatch->update([
        'company_name' => 'Different Client',
        'serial_number' => 'PRIMARY-001',
        'vehicle_specifications' => [
            [
                'brand' => 'Toyota',
                'model' => '8FD30',
                'serial_or_plate_number' => 'PRIMARY-001',
            ],
            [
                'brand' => 'Komatsu',
                'model' => 'FD25',
                'serial_or_plate_number' => 'PLATE-SECONDARY-987',
            ],
        ],
    ]);

    $otherSalesman = createSalesmanForRepairAndMaintenance('other_salesman_repair_test');
    $hiddenRecord = createMaintenanceRecordForSalesman($otherSalesman, null);
    $hiddenRecord->update(['company_name' => 'Acme Hidden Record']);

    Livewire::actingAs($salesman)
        ->test(RepairAndMaintence::class)
        ->assertSee('Search by company name or serial number')
        ->set('search', 'Acme Equipment')
        ->assertSee('Acme Equipment Corporation')
        ->assertDontSee('Different Client')
        ->assertDontSee('Acme Hidden Record')
        ->set('search', 'PLATE-SECONDARY-987')
        ->assertSee('Different Client')
        ->assertDontSee('Acme Equipment Corporation');
});

it('allows a salesman maintenance record to be created without a JO number', function () {
    $salesman = createSalesmanForRepairAndMaintenance();

    Livewire::actingAs($salesman)
        ->test(CreateRepairAndMaintenaceRecord::class, ['managesJobOrderNumber' => false])
        ->assertDontSee('Job Order No.')
        ->assertSee('Vehicle Specifications')
        ->assertSee('Serial Number / Plate Number')
        ->set('company_name', 'New Repair Client')
        ->set('address', 'New Address')
        ->set('email', 'new-repair@example.test')
        ->set('contact_number', '09111111111')
        ->set('date_sold', '2026-06-30')
        ->set('contact_person', 'New Contact')
        ->set('contact_number_person', '09222222222')
        ->set('vehicles.0.brand', 'Toyota')
        ->set('vehicles.0.model', '8FD30')
        ->set('vehicles.0.serial_or_plate_number', 'SN-NEW-001')
        ->set('vehicles.0.loading_capacity', '3 tons')
        ->call('addVehicle')
        ->set('vehicles.1.brand', 'Komatsu')
        ->set('vehicles.1.model', 'FD25')
        ->set('vehicles.1.serial_or_plate_number', 'PLATE-002')
        ->call('create_client_for_maintenance')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('client_record_for_maintenance_and_repairs', [
        'company_name' => 'New Repair Client',
        'job_order_number' => null,
        'serial_number' => 'SN-NEW-001',
        'salesmanId' => $salesman->id,
    ]);

    $record = ClientRecordForMaintenanceAndRepair::where('company_name', 'New Repair Client')->firstOrFail();

    expect($record->vehicle_specifications)
        ->toHaveCount(2)
        ->and($record->vehicle_specifications[0]['brand'])->toBe('Toyota')
        ->and($record->vehicle_specifications[0]['serial_or_plate_number'])->toBe('SN-NEW-001')
        ->and($record->vehicle_specifications[1]['serial_or_plate_number'])->toBe('PLATE-002');
});

it('adds and removes dynamic vehicles while always keeping one entry', function () {
    $salesman = createSalesmanForRepairAndMaintenance();
    $component = Livewire::actingAs($salesman)
        ->test(CreateRepairAndMaintenaceRecord::class, ['managesJobOrderNumber' => false]);

    expect($component->get('vehicles'))->toHaveCount(1);

    $component->call('addVehicle');
    expect($component->get('vehicles'))->toHaveCount(2);

    $component->call('removeVehicle', 1);
    expect($component->get('vehicles'))->toHaveCount(1);

    $component->call('removeVehicle', 0);
    expect($component->get('vehicles'))->toHaveCount(1);
});

it('requires identifying fields for every maintenance vehicle', function () {
    $salesman = createSalesmanForRepairAndMaintenance();

    Livewire::actingAs($salesman)
        ->test(CreateRepairAndMaintenaceRecord::class, ['managesJobOrderNumber' => false])
        ->call('create_client_for_maintenance')
        ->assertHasErrors([
            'vehicles.0.brand' => 'required',
            'vehicles.0.model' => 'required',
            'vehicles.0.serial_or_plate_number' => 'required',
        ]);
});

it('preserves an existing JO number when a salesman edits a maintenance record', function () {
    $salesman = createSalesmanForRepairAndMaintenance();
    $record = createMaintenanceRecordForSalesman($salesman, 'EXISTING-JO-002');

    Livewire::actingAs($salesman)
        ->test(EditRepairAndMaintence::class, [
            'recordId' => $record->id,
            'managesJobOrderNumber' => false,
        ])
        ->assertDontSee('Job Order Number.')
        ->set('company_name', 'Updated Repair Client')
        ->call('updateRecord')
        ->assertHasNoErrors();

    expect($record->fresh())
        ->company_name->toBe('Updated Repair Client')
        ->job_order_number->toBe('EXISTING-JO-002');
});

it('keeps JO required in shared modal contexts that still manage it', function () {
    $salesman = createSalesmanForRepairAndMaintenance();

    Livewire::actingAs($salesman)
        ->test(CreateRepairAndMaintenaceRecord::class)
        ->assertSee('Job Order No.')
        ->set('company_name', 'Shared Context Client')
        ->set('address', 'Shared Address')
        ->set('email', 'shared@example.test')
        ->set('contact_number', '09333333333')
        ->set('contact_person', 'Shared Contact')
        ->set('contact_number_person', '09444444444')
        ->call('create_client_for_maintenance')
        ->assertHasErrors(['job_order_number' => 'required']);
});

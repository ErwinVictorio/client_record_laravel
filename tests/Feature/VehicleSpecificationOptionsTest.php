<?php

use App\Livewire\Modals\CreateRepairAndMaintenaceRecord;
use App\Livewire\Modals\ClientStatusUpdate;
use App\Livewire\Modals\EditRepairAndMaintence;
use App\Models\ClientRecordForMaintenanceAndRepair;
use App\Models\clients;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;

uses(RefreshDatabase::class);

function createVehicleOptionsSalesman(string $username): User
{
    $id = DB::table('users')->insertGetId([
        'first_name' => 'Vehicle',
        'last_name' => 'Tester',
        'middle_name' => 'A',
        'NickName' => 'Vehicle Tester',
        'username' => $username,
        'password' => bcrypt('password'),
        'role' => 2,
        'department' => 'Sales',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return User::findOrFail($id);
}

function fillVehicleOptionsCreateForm($component): void
{
    $component
        ->set('company_name', 'Vehicle Options Client')
        ->set('address', 'Test Address')
        ->set('email', 'vehicle-options@example.test')
        ->set('contact_number', '09123456789')
        ->set('contact_person', 'Test Contact')
        ->set('contact_number_person', '09987654321')
        ->set('vehicles.0.brand', 'Toyota')
        ->set('vehicles.0.model', '8FD30')
        ->set('vehicles.0.serial_or_plate_number', 'SERIAL-OPTIONS-1');
}

it('requires manual values when Other is selected', function () {
    $salesman = createVehicleOptionsSalesman('vehicle_options_required');
    $component = Livewire::actingAs($salesman)
        ->test(CreateRepairAndMaintenaceRecord::class, ['managesJobOrderNumber' => false]);

    fillVehicleOptionsCreateForm($component);

    $component
        ->set('vehicles.0.mast_type_selection', 'Other')
        ->set('vehicles.0.power_type_selection', 'Other')
        ->set('vehicles.0.tire_selection', 'Other')
        ->call('create_client_for_maintenance')
        ->assertHasErrors([
            'vehicles.0.mast_type_other' => 'required_if',
            'vehicles.0.power_type_other' => 'required_if',
            'vehicles.0.tire_other' => 'required_if',
        ]);
});

it('stores custom Other values without temporary selection fields', function () {
    $salesman = createVehicleOptionsSalesman('vehicle_options_custom');
    $component = Livewire::actingAs($salesman)
        ->test(CreateRepairAndMaintenaceRecord::class, ['managesJobOrderNumber' => false]);

    fillVehicleOptionsCreateForm($component);

    $component
        ->set('vehicles.0.mast_type_selection', 'Other')
        ->set('vehicles.0.mast_type_other', 'Triplex')
        ->set('vehicles.0.power_type_selection', 'Other')
        ->set('vehicles.0.power_type_other', 'LPG')
        ->set('vehicles.0.tire_selection', 'Other')
        ->set('vehicles.0.tire_other', 'Foam Filled')
        ->call('create_client_for_maintenance')
        ->assertHasNoErrors();

    $vehicle = ClientRecordForMaintenanceAndRepair::latest('id')->firstOrFail()->vehicle_specifications[0];

    expect($vehicle['mast_type'])->toBe('Triplex')
        ->and($vehicle['power_type'])->toBe('LPG')
        ->and($vehicle['tire'])->toBe('Foam Filled')
        ->and($vehicle)->not->toHaveKeys([
            'mast_type_selection',
            'mast_type_other',
            'power_type_selection',
            'power_type_other',
            'tire_selection',
            'tire_other',
        ]);
});

it('loads existing custom values as Other and can replace them with dropdown values', function () {
    $salesman = createVehicleOptionsSalesman('vehicle_options_edit');
    $record = ClientRecordForMaintenanceAndRepair::create([
        'company_name' => 'Existing Custom Vehicle',
        'address' => 'Test Address',
        'email' => 'existing-options@example.test',
        'contact_number' => '09123456789',
        'contact_person' => 'Test Contact',
        'contact_number_person' => '09987654321',
        'serial_number' => 'SERIAL-EDIT-1',
        'vehicle_specifications' => [[
            'brand' => 'Toyota',
            'model' => '8FD30',
            'serial_or_plate_number' => 'SERIAL-EDIT-1',
            'mast_type' => 'Triplex',
            'power_type' => 'LPG',
            'tire' => 'Foam Filled',
        ]],
        'salesmanId' => $salesman->id,
    ]);

    Livewire::actingAs($salesman)
        ->test(EditRepairAndMaintence::class, [
            'recordId' => $record->id,
            'managesJobOrderNumber' => false,
        ])
        ->assertSet('vehicles.0.mast_type_selection', 'Other')
        ->assertSet('vehicles.0.mast_type_other', 'Triplex')
        ->assertSet('vehicles.0.power_type_selection', 'Other')
        ->assertSet('vehicles.0.power_type_other', 'LPG')
        ->assertSet('vehicles.0.tire_selection', 'Other')
        ->assertSet('vehicles.0.tire_other', 'Foam Filled')
        ->set('vehicles.0.mast_type_selection', 'ZM')
        ->set('vehicles.0.power_type_selection', 'Diesel')
        ->set('vehicles.0.tire_selection', 'Solid')
        ->call('updateRecord')
        ->assertHasNoErrors();

    $vehicle = $record->fresh()->vehicle_specifications[0];

    expect($vehicle['mast_type'])->toBe('ZM')
        ->and($vehicle['power_type'])->toBe('Diesel')
        ->and($vehicle['tire'])->toBe('Solid')
        ->and($vehicle)->not->toHaveKey('mast_type_selection');
});

it('uses the same dropdown and custom-value behavior during client status update', function () {
    $salesman = createVehicleOptionsSalesman('vehicle_options_status');
    $clientId = DB::table('clients')->insertGetId([
        'company_name' => 'Status Vehicle Client',
        'contact_number' => '09123456789',
        'email' => 'status-options@example.test',
        'address' => 'Test Address',
        'salesList_no' => 'SL-OPTIONS-1',
        'contact_person' => 'Test Contact',
        'contact_number_person' => '09987654321',
        'supporting_document_path' => 'supporting-documents/existing.pdf',
        'supporting_document_paths' => json_encode(['supporting-documents/existing.pdf']),
        'vehicle_specifications' => json_encode([[
            'brand' => 'Toyota',
            'model' => '8FD30',
            'mast_type' => 'Duplex',
            'power_type' => 'Gasoline',
            'tire' => 'Polyurethane',
        ]]),
        'salesman_id' => $salesman->id,
        'status' => 'For Approval',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    Livewire::actingAs($salesman)
        ->test(ClientStatusUpdate::class, ['clientId' => $clientId])
        ->assertSet('vehicles.0.mast_type_selection', 'Other')
        ->assertSet('vehicles.0.mast_type_other', 'Duplex')
        ->assertSet('vehicles.0.power_type_selection', 'Other')
        ->assertSet('vehicles.0.power_type_other', 'Gasoline')
        ->assertSet('vehicles.0.tire_selection', 'Other')
        ->assertSet('vehicles.0.tire_other', 'Polyurethane')
        ->set('vehicles.0.mast_type_selection', 'ZSM')
        ->set('vehicles.0.power_type_selection', 'Electric-Li')
        ->set('vehicles.0.tire_selection', 'Pneumatic')
        ->call('change_status')
        ->assertHasNoErrors();

    $vehicle = clients::findOrFail($clientId)->vehicle_specifications[0];

    expect($vehicle['mast_type'])->toBe('ZSM')
        ->and($vehicle['power_type'])->toBe('Electric-Li')
        ->and($vehicle['tire'])->toBe('Pneumatic')
        ->and($vehicle)->not->toHaveKey('power_type_selection');
});

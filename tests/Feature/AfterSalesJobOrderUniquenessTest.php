<?php

use App\Livewire\AfterSales\Dashboard;
use App\Livewire\Modals\AfterSalesEditRecord;
use App\Models\AfterSalesRecord;
use App\Models\ClientRecordForMaintenanceAndRepair;
use App\Models\clients;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;

uses(RefreshDatabase::class);

function createJoUniquenessUser(string $username): User
{
    $id = DB::table('users')->insertGetId([
        'first_name' => 'MSD',
        'last_name' => 'Admin',
        'middle_name' => 'A',
        'NickName' => 'MSD',
        'username' => $username,
        'password' => bcrypt('password'),
        'role' => 4,
        'department' => 'After Sales',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return User::findOrFail($id);
}

function createJoUniquenessClient(User $user, string $email = 'jo-client@example.test'): clients
{
    return clients::create([
        'company_name' => 'JO Client',
        'contact_number' => '09123456789',
        'email' => $email,
        'address' => 'Test Address',
        'salesList_no' => 'SL-JO-001',
        'contact_person' => 'Contact Person',
        'contact_number_person' => '09987654321',
        'salesman_id' => $user->id,
        'status' => 'Sold',
    ]);
}

function createJoUniquenessAfterSalesRecord(User $user, clients $client, string $jobOrderNumber): AfterSalesRecord
{
    return AfterSalesRecord::create([
        'client_id' => $client->id,
        'user_id' => $user->id,
        'service_type' => 'Other',
        'change_type' => 'WITH CHARGE',
        'warranty_type' => 'OUT OF WARRANTY',
        'job_order_number' => $jobOrderNumber,
    ]);
}

it('shows a live warning for a case-insensitive duplicate JO number', function () {
    $user = createJoUniquenessUser('jo_live_warning');
    $client = createJoUniquenessClient($user);
    createJoUniquenessAfterSalesRecord($user, $client, 'JO-260801');

    Livewire::actingAs($user)
        ->test(Dashboard::class)
        ->set('job_order_number', '  jo-260801  ')
        ->assertSet('jobOrderNumberTaken', true)
        ->assertHasErrors(['job_order_number'])
        ->assertSee('already in use');
});

it('blocks a JO number already assigned to a maintenance record', function () {
    $user = createJoUniquenessUser('jo_maintenance_duplicate');
    $client = createJoUniquenessClient($user);

    ClientRecordForMaintenanceAndRepair::create([
        'company_name' => 'Maintenance Client',
        'address' => 'Maintenance Address',
        'email' => 'maintenance-duplicate@example.test',
        'contact_number' => '09111111111',
        'job_order_number' => 'JO-MAINT-001',
        'contact_person' => 'Maintenance Contact',
        'contact_number_person' => '09222222222',
        'salesmanId' => $user->id,
    ]);

    $component = Livewire::actingAs($user)->test(Dashboard::class)
        ->set('selectedClientId', $client->id)
        ->set('service_type', 'Other')
        ->set('change_type', 'WITH CHARGE')
        ->set('warranty_type', 'OUT OF WARRANTY')
        ->set('job_order_number', 'JO-MAINT-001')
        ->call('save')
        ->assertHasErrors(['job_order_number'])
        ->assertSee('already in use');

    expect(AfterSalesRecord::count())->toBe(0);
});

it('saves an available JO number', function () {
    $user = createJoUniquenessUser('jo_available');
    $client = createJoUniquenessClient($user);

    Livewire::actingAs($user)->test(Dashboard::class)
        ->set('selectedClientId', $client->id)
        ->set('service_type', 'Other')
        ->set('change_type', 'WITH CHARGE')
        ->set('warranty_type', 'OUT OF WARRANTY')
        ->set('job_order_number', 'JO-AVAILABLE-001')
        ->call('save')
        ->assertHasNoErrors();

    expect(AfterSalesRecord::where('job_order_number', 'JO-AVAILABLE-001')->exists())->toBeTrue();
});

it('allows an MSD record to retain its own JO but blocks another record JO', function () {
    $user = createJoUniquenessUser('jo_edit_check');
    $client = createJoUniquenessClient($user);
    $record = createJoUniquenessAfterSalesRecord($user, $client, 'JO-OWN-001');
    createJoUniquenessAfterSalesRecord($user, $client, 'JO-OTHER-001');

    Livewire::actingAs($user)
        ->test(AfterSalesEditRecord::class, ['recordId' => $record->id])
        ->call('updateRecord')
        ->assertHasNoErrors();

    Livewire::actingAs($user)
        ->test(AfterSalesEditRecord::class, ['recordId' => $record->id])
        ->set('jobOrderNumber', 'JO-OTHER-001')
        ->call('updateRecord')
        ->assertHasErrors(['jobOrderNumber']);

    expect($record->fresh()->job_order_number)->toBe('JO-OWN-001');
});

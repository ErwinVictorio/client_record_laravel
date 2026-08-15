<?php

use App\Livewire\Modals\ClientInfo;
use App\Models\clients;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;

uses(RefreshDatabase::class);

function createClientAwaitingApproval(array $overrides = []): clients
{
    $salesmanId = DB::table('users')->insertGetId([
        'first_name' => 'Sales',
        'last_name' => 'Agent',
        'middle_name' => 'A',
        'NickName' => 'Agent',
        'username' => 'approval_salesman_'.uniqid(),
        'password' => bcrypt('password'),
        'role' => 2,
        'department' => 'Sales',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return clients::create(array_merge([
        'company_name' => 'Approval Client',
        'contact_number' => '09123456789',
        'email' => uniqid().'@example.test',
        'address' => 'Test Address',
        'contact_person' => 'Test Person',
        'contact_number_person' => '09987654321',
        'salesman_id' => $salesmanId,
        'item_name' => 'Existing Item',
        'model_number' => 'MODEL-1',
        'quantity' => 3,
        'specification' => 'Existing specification',
        'status' => 'For Approval',
    ], $overrides));
}

it('marks an approval client as sold without changing existing product columns', function () {
    $client = createClientAwaitingApproval(['rejection_reason' => 'Old reason']);

    Livewire::test(ClientInfo::class, ['clientId' => $client->id])
        ->call('markAsSold')
        ->assertHasNoErrors()
        ->assertDispatched('clients-updated');

    $client->refresh();

    expect($client->status)->toBe('Sold')
        ->and($client->rejection_reason)->toBeNull()
        ->and($client->item_name)->toBe('Existing Item')
        ->and($client->model_number)->toBe('MODEL-1')
        ->and($client->quantity)->toBe(3)
        ->and($client->specification)->toBe('Existing specification');
});

it('asks for confirmation before marking a client as sold', function () {
    $client = createClientAwaitingApproval();

    Livewire::test(ClientInfo::class, ['clientId' => $client->id])
        ->call('openSoldConfirmation')
        ->assertSet('showSoldConfirmation', true)
        ->assertSee('Are you sure you want to mark this client as Sold?');

    expect($client->fresh()->status)->toBe('For Approval');
});

it('shows client and vehicle details before approval', function () {
    $client = createClientAwaitingApproval([
        'year_model' => '2026',
        'vehicle_specifications' => [[
            'brand' => 'Toyota Forklift',
            'model' => '8FD30',
            'engine' => 'Diesel',
            'loading_capacity' => '3 Tons',
        ]],
    ]);

    Livewire::test(ClientInfo::class, ['clientId' => $client->id])
        ->assertSee('Client Information')
        ->assertSee('Approval Client')
        ->assertSee('Vehicle / Product Information')
        ->assertSee('Toyota Forklift')
        ->assertSee('8FD30')
        ->assertSee('3 Tons')
        ->assertSee('Sales Agent');
});

it('requires a reason before rejecting a client', function () {
    $client = createClientAwaitingApproval();

    Livewire::test(ClientInfo::class, ['clientId' => $client->id])
        ->call('rejectClient')
        ->assertHasErrors(['rejectionReason' => 'required']);

    expect($client->fresh()->status)->toBe('For Approval');
});

it('returns a rejected client to pending and saves the reason', function () {
    $client = createClientAwaitingApproval();

    Livewire::test(ClientInfo::class, ['clientId' => $client->id])
        ->set('rejectionReason', 'Missing signed supporting document.')
        ->call('rejectClient')
        ->assertHasNoErrors()
        ->assertDispatched('clients-updated');

    $client->refresh();

    expect($client->status)->toBe('Pending')
        ->and($client->rejection_reason)->toBe('Missing signed supporting document.')
        ->and($client->item_name)->toBe('Existing Item');
});

it('does not process a client that is no longer for approval', function () {
    $client = createClientAwaitingApproval(['status' => 'Sold']);

    Livewire::test(ClientInfo::class, ['clientId' => $client->id])
        ->call('markAsSold')
        ->assertSee('This client is no longer awaiting approval.');

    expect($client->fresh()->status)->toBe('Sold');
});

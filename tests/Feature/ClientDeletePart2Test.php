<?php

use App\Livewire\Modals\ClientDeletePart2;
use App\Models\clients;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;

uses(RefreshDatabase::class);

function createDeletableClient(): clients
{
    $salesmanId = DB::table('users')->insertGetId([
        'first_name' => 'Delete',
        'last_name' => 'Tester',
        'middle_name' => 'T',
        'NickName' => 'Tester',
        'username' => 'delete_tester_'.uniqid(),
        'password' => bcrypt('password'),
        'role' => 2,
        'department' => 'Sales',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return clients::create([
        'company_name' => 'Delete Test Company',
        'contact_number' => '09123456789',
        'email' => uniqid().'@example.test',
        'address' => 'Test Address',
        'contact_person' => 'Test Person',
        'contact_number_person' => '09987654321',
        'salesman_id' => $salesmanId,
        'status' => 'Pending',
    ]);
}

it('loads the selected client into the confirmation modal', function () {
    $client = createDeletableClient();

    Livewire::test(ClientDeletePart2::class)
        ->dispatch('open-delete-modal', clientId: $client->id)
        ->assertSet('clientId', $client->id)
        ->assertSet('company_name', 'Delete Test Company')
        ->assertSee('Permanently delete this client?')
        ->assertSee('Delete Test Company');
});

it('deletes the client and requests a clean modal close', function () {
    $client = createDeletableClient();

    Livewire::test(ClientDeletePart2::class)
        ->dispatch('open-delete-modal', clientId: $client->id)
        ->call('destroyClient')
        ->assertDispatched('hide-client-delete-modal')
        ->assertNotDispatched('clients-updated');

    expect(clients::find($client->id))->toBeNull();
});

it('shows an error when the client no longer exists', function () {
    Livewire::test(ClientDeletePart2::class)
        ->set('clientId', 999999)
        ->call('destroyClient')
        ->assertSee('Client record not found. It may have already been deleted.')
        ->assertNotDispatched('hide-client-delete-modal');
});

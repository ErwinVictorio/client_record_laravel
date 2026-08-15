<?php

use App\Livewire\Modals\DeleteClient;
use App\Models\clients;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;

uses(RefreshDatabase::class);

function createAdminDeletableClient(): clients
{
    $salesmanId = DB::table('users')->insertGetId([
        'first_name' => 'Admin',
        'last_name' => 'Delete Tester',
        'middle_name' => 'T',
        'NickName' => 'Tester',
        'username' => 'admin_delete_tester_'.uniqid(),
        'password' => bcrypt('password'),
        'role' => 2,
        'department' => 'Sales',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return clients::create([
        'company_name' => 'Admin Delete Company',
        'contact_number' => '09123456789',
        'email' => uniqid().'@example.test',
        'address' => 'Test Address',
        'contact_person' => 'Test Person',
        'contact_number_person' => '09987654321',
        'salesman_id' => $salesmanId,
        'status' => 'Pending',
    ]);
}

it('shows the selected client in the admin delete confirmation', function () {
    $client = createAdminDeletableClient();

    Livewire::test(DeleteClient::class, ['clientId' => $client->id])
        ->assertSet('company_name', 'Admin Delete Company')
        ->assertSee('Permanently delete this client?')
        ->assertSee('Admin Delete Company');
});

it('deletes the admin client and requests the modal close', function () {
    $client = createAdminDeletableClient();

    Livewire::test(DeleteClient::class, ['clientId' => $client->id])
        ->call('destroyClient')
        ->assertDispatched('hide-delete-client-modal');

    expect(clients::find($client->id))->toBeNull();
});

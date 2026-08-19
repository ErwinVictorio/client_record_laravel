<?php

use App\Livewire\Admin\Pages\DataCleanup;
use App\Models\AfterSalesRecord;
use App\Models\clients;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

uses(RefreshDatabase::class);

function createCleanupUser(string $username, int $role = 1): User
{
    $id = DB::table('users')->insertGetId([
        'first_name' => 'Cleanup',
        'last_name' => 'Admin',
        'middle_name' => 'A',
        'NickName' => 'Admin',
        'username' => $username,
        'password' => bcrypt('password'),
        'role' => $role,
        'department' => 'Admin',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return User::findOrFail($id);
}

function createCleanupClient(User $salesman, string $name, string $status, string $createdAt, ?string $document = null): clients
{
    $client = clients::create([
        'company_name' => $name,
        'contact_number' => '09123456789',
        'email' => str($name)->slug().uniqid().'@example.test',
        'address' => 'Test Address',
        'contact_person' => 'Test Person',
        'contact_number_person' => '09987654321',
        'salesman_id' => $salesman->id,
        'status' => $status,
        'supporting_document_path' => $document,
        'supporting_document_paths' => $document ? [$document] : null,
    ]);

    $client->timestamps = false;
    $client->created_at = $createdAt;
    $client->updated_at = $createdAt;
    $client->save();

    return $client;
}

it('permanently deletes only selected clients that still match the active filters', function () {
    Storage::fake('public');

    $admin = createCleanupUser('cleanup_admin');
    $oldPending = createCleanupClient($admin, 'Old Pending Client', 'Pending', '2026-01-15 10:00:00', 'documents/old-pending.pdf');
    $oldSold = createCleanupClient($admin, 'Old Sold Client', 'Sold', '2026-01-20 10:00:00');
    $recentPending = createCleanupClient($admin, 'Recent Pending Client', 'Pending', '2026-07-01 10:00:00');
    Storage::disk('public')->put('documents/old-pending.pdf', 'test');

    $afterSales = AfterSalesRecord::create([
        'client_id' => $oldPending->id,
        'user_id' => $admin->id,
        'service_type' => 'PMS',
        'job_order_number' => 'JO-CLEANUP-1',
    ]);

    Livewire::actingAs($admin)
        ->test(DataCleanup::class)
        ->set('startDate', '2026-01-01')
        ->set('endDate', '2026-03-31')
        ->set('status', 'Pending')
        ->call('applyFilters')
        ->assertSee('Old Pending Client')
        ->assertDontSee('Old Sold Client')
        ->set('selectedIds', [$oldPending->id, $oldSold->id, $recentPending->id])
        ->set('confirmationText', 'DELETE')
        ->call('permanentlyDeleteSelected')
        ->assertHasNoErrors()
        ->assertDispatched('hide-permanent-cleanup-modal');

    expect(clients::find($oldPending->id))->toBeNull()
        ->and(clients::find($oldSold->id))->not->toBeNull()
        ->and(clients::find($recentPending->id))->not->toBeNull()
        ->and($afterSales->fresh()->client_id)->toBeNull();

    Storage::disk('public')->assertMissing('documents/old-pending.pdf');
});

it('requires the exact permanent deletion confirmation text', function () {
    $admin = createCleanupUser('cleanup_confirmation_admin');
    $client = createCleanupClient($admin, 'Confirmation Client', 'Pending', '2026-01-15 10:00:00');

    Livewire::actingAs($admin)
        ->test(DataCleanup::class)
        ->set('startDate', '2026-01-01')
        ->set('endDate', '2026-03-31')
        ->set('status', 'Pending')
        ->call('applyFilters')
        ->set('selectedIds', [$client->id])
        ->set('confirmationText', 'delete')
        ->call('permanentlyDeleteSelected')
        ->assertHasErrors(['confirmationText' => 'in']);

    expect(clients::find($client->id))->not->toBeNull();
});

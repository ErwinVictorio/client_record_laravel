<?php

use App\Livewire\Modals\DuplicateClientRecord;
use App\Models\clients;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;

uses(RefreshDatabase::class);

function createRepeatUnitSalesman(string $username): User
{
    $id = DB::table('users')->insertGetId([
        'first_name' => 'Sales',
        'last_name' => 'Agent',
        'middle_name' => 'A',
        'NickName' => 'Agent',
        'username' => $username,
        'password' => bcrypt('password'),
        'role' => 2,
        'department' => 'Sales',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return User::findOrFail($id);
}

function createRepeatUnitClient(User $salesman, array $overrides = []): clients
{
    return clients::create(array_merge([
        'company_name' => 'Repeat Unit Corp',
        'contact_number' => '09123456789',
        'email' => 'repeat-unit@example.test',
        'address' => 'Old Client Address',
        'contact_person' => 'Purchasing Lead',
        'contact_number_person' => '09987654321',
        'bank_account_number' => '1234567890',
        'salesman_id' => $salesman->id,
        'item_name' => 'Forklift',
        'model_number' => 'FD30',
        'year_model' => '2026',
        'quantity' => 1,
        'specification' => 'Diesel automatic',
        'vehicle_specifications' => [['brand' => 'Toyota', 'model' => 'FD30']],
        'supporting_document_path' => 'documents/old.pdf',
        'supporting_document_paths' => ['documents/old.pdf'],
        'status' => 'Sold',
    ], $overrides));
}

it('creates a new pending unit record from a salesmans client without copying vehicle details or documents', function () {
    $salesman = createRepeatUnitSalesman('repeat_unit_owner');
    $sourceClient = createRepeatUnitClient($salesman);

    Livewire::actingAs($salesman)
        ->test(DuplicateClientRecord::class, ['clientId' => $sourceClient->id])
        ->call('validateAndConfirm')
        ->assertHasNoErrors()
        ->assertSet('showConfirmation', true)
        ->call('createNewUnitRecord')
        ->assertHasNoErrors()
        ->assertDispatched('hide-duplicate-client-record-modal-'.$sourceClient->id);

    $newClient = clients::query()
        ->where('id', '!=', $sourceClient->id)
        ->where('salesman_id', $salesman->id)
        ->firstOrFail();

    expect($newClient->company_name)->toBe($sourceClient->company_name)
        ->and($newClient->address)->toBe($sourceClient->address)
        ->and($newClient->email)->toBe($sourceClient->email)
        ->and($newClient->contact_number)->toBe($sourceClient->contact_number)
        ->and($newClient->contact_person)->toBe($sourceClient->contact_person)
        ->and($newClient->contact_number_person)->toBe($sourceClient->contact_number_person)
        ->and($newClient->bank_account_number)->toBe($sourceClient->bank_account_number)
        ->and($newClient->status)->toBe('Pending')
        ->and($newClient->salesList_no)->toBeNull()
        ->and($newClient->item_name)->toBeNull()
        ->and($newClient->model_number)->toBeNull()
        ->and($newClient->year_model)->toBeNull()
        ->and($newClient->quantity)->toBeNull()
        ->and($newClient->specification)->toBeNull()
        ->and($newClient->vehicle_specifications)->toBeNull()
        ->and($newClient->supporting_document_path)->toBeNull()
        ->and($newClient->supporting_document_paths)->toBeNull();
});

it('keeps the original salesman when super admin creates a new unit record', function () {
    $salesman = createRepeatUnitSalesman('repeat_unit_admin_owner');
    $superAdmin = createRepeatUnitSalesman('repeat_unit_super_admin');
    $sourceClient = createRepeatUnitClient($salesman, ['email' => 'admin-repeat-unit@example.test']);

    Livewire::actingAs($superAdmin)
        ->test(DuplicateClientRecord::class, [
            'clientId' => $sourceClient->id,
            'context' => 'super-admin',
        ])
        ->call('createNewUnitRecord')
        ->assertHasNoErrors();

    $newClient = clients::query()
        ->where('id', '!=', $sourceClient->id)
        ->where('email', $sourceClient->email)
        ->firstOrFail();

    expect($newClient->salesman_id)->toBe($salesman->id)
        ->and($newClient->status)->toBe('Pending');
});

it('prevents a salesman from duplicating another salesmans client', function () {
    $owner = createRepeatUnitSalesman('repeat_unit_actual_owner');
    $otherSalesman = createRepeatUnitSalesman('repeat_unit_other_salesman');
    $sourceClient = createRepeatUnitClient($owner, ['email' => 'blocked-repeat-unit@example.test']);

    Livewire::actingAs($otherSalesman)
        ->test(DuplicateClientRecord::class, ['clientId' => $sourceClient->id])
        ->assertForbidden();
});

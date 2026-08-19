<?php

use App\Livewire\Modals\ClientCreate;
use App\Models\clients;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;

uses(RefreshDatabase::class);

function createDuplicateTestSalesman(string $username): User
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

function createDuplicateTestClient(User $salesman): clients
{
    return clients::create([
        'company_name' => 'Repeat Client Corporation',
        'contact_number' => '09123456789',
        'email' => 'repeat-client@example.test',
        'address' => 'Test Address',
        'contact_person' => 'Contact Person',
        'contact_number_person' => '09987654321',
        'salesman_id' => $salesman->id,
        'status' => 'Pending',
    ]);
}

function duplicateClientFormData(): array
{
    return [
        'CompanyName' => 'Repeat Client Corporation',
        'contact_person' => 'Contact Person',
        'contact_person_number' => '09987654321',
        'address' => 'Test Address',
        'email' => 'repeat-client@example.test',
        'contact_number' => '09123456789',
    ];
}

it('allows a salesman to register the same client repeatedly', function () {
    $salesman = createDuplicateTestSalesman('repeat_owner');
    createDuplicateTestClient($salesman);

    $component = Livewire::actingAs($salesman)->test(ClientCreate::class);

    foreach (duplicateClientFormData() as $field => $value) {
        $component->set($field, $value);
    }

    $component
        ->call('validateAndConfirm')
        ->assertHasNoErrors()
        ->assertSet('showConfirmation', true)
        ->call('createClient')
        ->assertHasNoErrors();

    expect(clients::where('salesman_id', $salesman->id)
        ->where('email', 'repeat-client@example.test')
        ->count())->toBe(2);
});

it('blocks a matching client owned by another salesman', function () {
    $owner = createDuplicateTestSalesman('original_owner');
    $otherSalesman = createDuplicateTestSalesman('other_salesman');
    createDuplicateTestClient($owner);

    $component = Livewire::actingAs($otherSalesman)->test(ClientCreate::class);

    foreach (duplicateClientFormData() as $field => $value) {
        $component->set($field, $value);
    }

    $component
        ->call('validateAndConfirm')
        ->assertSet('showConfirmation', false)
        ->assertSee('The client is already taken by another salesman!');

    expect(clients::count())->toBe(1);
});

it('allows another company to reuse an existing email and contact number', function () {
    $owner = createDuplicateTestSalesman('contact_owner');
    $otherSalesman = createDuplicateTestSalesman('contact_other_salesman');
    createDuplicateTestClient($owner);

    $component = Livewire::actingAs($otherSalesman)->test(ClientCreate::class);

    foreach (duplicateClientFormData() as $field => $value) {
        $component->set($field, $value);
    }

    $component
        ->set('CompanyName', 'Different Client Corporation')
        ->call('validateAndConfirm')
        ->assertHasNoErrors()
        ->assertSet('showConfirmation', true);
});

it('allows only letters separated by single spaces in a company name', function (string $companyName) {
    $salesman = createDuplicateTestSalesman('valid_company_name');

    $component = Livewire::actingAs($salesman)->test(ClientCreate::class);

    foreach (duplicateClientFormData() as $field => $value) {
        $component->set($field, $value);
    }

    $component
        ->set('CompanyName', $companyName)
        ->call('validateAndConfirm')
        ->assertHasNoErrors(['CompanyName']);
})->with([
    'ordinary name' => 'ABC Company',
    'spaced abbreviation' => 'A S A P',
    'unicode letters' => 'Compania Espanola',
]);

it('rejects invalid spacing or non-letters in a company name', function (string $companyName) {
    $salesman = createDuplicateTestSalesman('invalid_company_name');

    $component = Livewire::actingAs($salesman)->test(ClientCreate::class);

    foreach (duplicateClientFormData() as $field => $value) {
        $component->set($field, $value);
    }

    $component
        ->set('CompanyName', $companyName)
        ->call('validateAndConfirm')
        ->assertHasErrors(['CompanyName' => 'regex']);
})->with([
    'leading space' => ' ABC Company',
    'trailing space' => 'ABC Company ',
    'double space' => 'ABC  Company',
    'number' => 'Company 123',
    'symbol' => 'J&T Express',
]);

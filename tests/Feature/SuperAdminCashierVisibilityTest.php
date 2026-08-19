<?php

use App\Livewire\SuperAdmin\Page\Cashier;
use App\Models\clients;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;

uses(RefreshDatabase::class);

function createCashierVisibilityUser(): User
{
    $id = DB::table('users')->insertGetId([
        'first_name' => 'Cashier',
        'last_name' => 'Tester',
        'middle_name' => 'T',
        'NickName' => 'Tester',
        'username' => 'cashier_visibility_'.uniqid(),
        'password' => bcrypt('password'),
        'role' => 1,
        'department' => 'Admin',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return User::findOrFail($id);
}

function createCashierVisibilityClient(User $user, string $name, string $status): clients
{
    return clients::create([
        'company_name' => $name,
        'contact_number' => '09123456789',
        'email' => str($name)->slug().uniqid().'@example.test',
        'address' => 'Test Address',
        'contact_person' => 'Test Person',
        'contact_number_person' => '09987654321',
        'salesman_id' => $user->id,
        'status' => $status,
    ]);
}

it('shows only for approval and sold clients on the super admin cashier page', function () {
    $user = createCashierVisibilityUser();
    createCashierVisibilityClient($user, 'Hidden Pending Client', 'Pending');
    createCashierVisibilityClient($user, 'Visible Approval Client', 'For Approval');
    createCashierVisibilityClient($user, 'Visible Sold Client', 'Sold');

    Livewire::actingAs($user)
        ->test(Cashier::class)
        ->assertSet('countedAprove', 1)
        ->assertSet('counttedSoldClient', 1)
        ->assertSee('Visible Approval Client')
        ->assertSee('Visible Sold Client')
        ->assertDontSee('Hidden Pending Client')
        ->set('clientSearch', 'Pending')
        ->call('applySearch')
        ->assertDontSee('Hidden Pending Client');
});

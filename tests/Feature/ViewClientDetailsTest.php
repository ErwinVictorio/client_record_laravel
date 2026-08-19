<?php

use App\Livewire\Modals\ViewClientDetails;
use App\Livewire\SalesMan\SalesManPage;
use App\Livewire\SuperAdmin\ManageClient\CreateFinishVehicle;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;

uses(RefreshDatabase::class);

function createViewDetailsUser(string $username, int $role = 2): User
{
    $id = DB::table('users')->insertGetId([
        'first_name' => 'Vehicle',
        'last_name' => 'Owner',
        'middle_name' => 'A',
        'NickName' => 'Owner',
        'username' => $username,
        'password' => bcrypt('password'),
        'role' => $role,
        'department' => 'Sales',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return User::findOrFail($id);
}

function createViewDetailsClient(User $owner): int
{
    return DB::table('clients')->insertGetId([
        'company_name' => 'Complete Vehicle Client',
        'contact_number' => '09123456789',
        'email' => 'complete-vehicle@example.test',
        'address' => 'Complete Test Address',
        'salesList_no' => 'SL-VIEW-001',
        'contact_person' => 'Contact Person',
        'contact_number_person' => '09987654321',
        'bank_account_number' => 'BANK-123',
        'supporting_document_path' => 'supporting-documents/document.pdf',
        'supporting_document_paths' => json_encode([
            'supporting-documents/document.pdf',
            'supporting-documents/vehicle.jpg',
        ]),
        'vehicle_specifications' => json_encode([
            [
                'brand' => 'Toyota',
                'model' => '8FD30',
                'engine' => '1DZ-II',
                'engine_series' => 'DZ Series',
                'mast_type' => 'Triplex',
                'power_type' => 'LPG',
                'tire' => 'Foam Filled',
            ],
            [
                'brand' => 'Komatsu',
                'model' => 'FD25',
                'engine' => '4D94E',
                'engine_series' => 'E Series',
                'mast_type' => 'ZM',
                'power_type' => 'Diesel',
                'tire' => 'Solid',
            ],
        ]),
        'salesman_id' => $owner->id,
        'status' => 'For Approval',
        'rejection_reason' => 'Please complete the requirements.',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}

it('shows complete client, vehicle, rejection, and document information to the owner', function () {
    $owner = createViewDetailsUser('view_details_owner');
    $clientId = createViewDetailsClient($owner);

    Livewire::actingAs($owner)
        ->test(ViewClientDetails::class, ['clientId' => $clientId])
        ->assertSee('Complete Vehicle Client')
        ->assertSee('Vehicle #1')
        ->assertSee('Vehicle #2')
        ->assertSee('1DZ-II')
        ->assertSee('DZ Series')
        ->assertSee('Triplex')
        ->assertSee('LPG')
        ->assertSee('Foam Filled')
        ->assertSee('4D94E')
        ->assertSee('Please complete the requirements.')
        ->assertSee('View Document 1')
        ->assertSee('View Image 2');
});

it('does not expose another salesman client through the modal component', function () {
    $owner = createViewDetailsUser('view_details_original_owner');
    $otherSalesman = createViewDetailsUser('view_details_other_salesman');
    $clientId = createViewDetailsClient($owner);

    Livewire::actingAs($otherSalesman)
        ->test(ViewClientDetails::class, ['clientId' => $clientId])
        ->assertDontSee('Complete Vehicle Client')
        ->assertSee('do not have permission');
});

it('allows an admin to view every client through the modal component', function () {
    $owner = createViewDetailsUser('view_details_admin_client_owner', 3);
    $admin = createViewDetailsUser('view_details_admin', 1);
    $clientId = createViewDetailsClient($owner);

    Livewire::actingAs($admin)
        ->test(ViewClientDetails::class, ['clientId' => $clientId])
        ->assertSee('Complete Vehicle Client')
        ->assertSee('Vehicle #1')
        ->assertDontSee('do not have permission');
});

it('allows a super admin to view every client through the modal component', function () {
    $owner = createViewDetailsUser('view_details_super_admin_client_owner', 3);
    $superAdmin = createViewDetailsUser('view_details_super_admin', 0);
    $clientId = createViewDetailsClient($owner);

    Livewire::actingAs($superAdmin)
        ->test(ViewClientDetails::class, ['clientId' => $clientId])
        ->assertSee('Complete Vehicle Client')
        ->assertSee('Vehicle #1')
        ->assertDontSee('do not have permission');
});

it('adds View Info actions to the salesman and super admin client tables', function () {
    $salesman = createViewDetailsUser('view_details_salesman_page');
    createViewDetailsClient($salesman);

    Livewire::actingAs($salesman)
        ->test(SalesManPage::class)
        ->assertSee('View Info');

    $superAdmin = createViewDetailsUser('view_details_super_admin_page', 1);
    createViewDetailsClient($superAdmin);

    Livewire::actingAs($superAdmin)
        ->test(CreateFinishVehicle::class)
        ->assertSee('View Info');
});

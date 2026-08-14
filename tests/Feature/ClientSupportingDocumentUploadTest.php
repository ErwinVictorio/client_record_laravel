<?php

use App\Livewire\Modals\ClientStatusUpdate;
use App\Models\clients;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

uses(RefreshDatabase::class);

function createDocumentUploadClient(): clients
{
    $salesmanId = DB::table('users')->insertGetId([
        'first_name' => 'Upload',
        'last_name' => 'Tester',
        'middle_name' => 'A',
        'NickName' => 'Uploader',
        'username' => 'document_uploader',
        'password' => bcrypt('password'),
        'role' => 2,
        'department' => 'Sales',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $clientId = DB::table('clients')->insertGetId([
        'company_name' => 'Upload Client',
        'contact_number' => '09123456789',
        'email' => 'upload-client@example.test',
        'address' => 'Test Address',
        'salesList_no' => 'SL-UPLOAD-001',
        'contact_person' => 'Contact Person',
        'contact_number_person' => '09987654321',
        'salesman_id' => $salesmanId,
        'status' => 'For Approval',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return clients::findOrFail($clientId);
}

it('accepts both image and PDF supporting documents', function () {
    Storage::fake('public');
    $client = createDocumentUploadClient();

    Livewire::test(ClientStatusUpdate::class, ['clientId' => $client->id])
        ->set('supporting_docs.0', UploadedFile::fake()->image('vehicle-photo.jpg'))
        ->call('addSupportingDocument')
        ->set('supporting_docs.1', UploadedFile::fake()->create('sales-document.pdf', 100, 'application/pdf'))
        ->set('vehicles.0.brand', 'Toyota')
        ->set('vehicles.0.model', '8FD30')
        ->call('change_status')
        ->assertHasNoErrors();

    $paths = $client->fresh()->supporting_document_paths;

    expect($paths)->toHaveCount(2);
    Storage::disk('public')->assertExists($paths[0]);
    Storage::disk('public')->assertExists($paths[1]);
});

it('rejects files that are neither images nor PDFs', function () {
    Storage::fake('public');
    $client = createDocumentUploadClient();

    Livewire::test(ClientStatusUpdate::class, ['clientId' => $client->id])
        ->set('supporting_docs.0', UploadedFile::fake()->create('notes.txt', 10, 'text/plain'))
        ->set('vehicles.0.brand', 'Toyota')
        ->set('vehicles.0.model', '8FD30')
        ->call('change_status')
        ->assertHasErrors(['supporting_docs.0']);
});

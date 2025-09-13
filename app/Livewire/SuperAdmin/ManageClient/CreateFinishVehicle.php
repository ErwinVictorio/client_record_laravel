<?php

namespace App\Livewire\SuperAdmin\ManageClient;

use Livewire\Component;
use App\Models\clients;
use Livewire\WithPagination;

class CreateFinishVehicle extends Component
{

    use WithPagination;

    public function render()
    {

         $ClientRecord = clients::select(
            'salesList_no',
            'company_name',
             'address',
              'email',
              'contact_number',
              'status',
              'created_at',
               'id'
            )->orderBy('created_at','desc')->paginate(20);

        return view('livewire.super-admin.manage-client.create-finish-vehicle',[
            'ClientRecord' => $ClientRecord
        ]);
    }
}

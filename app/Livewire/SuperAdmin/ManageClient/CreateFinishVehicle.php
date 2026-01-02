<?php

namespace App\Livewire\SuperAdmin\ManageClient;

use Livewire\Component;
use App\Models\clients;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

class CreateFinishVehicle extends Component
{

    use WithPagination;
    public $countedPending, $countedSold, $searchQuery = '', $clientSearch = '', $totalPending = '';


    public function mount()
    {
        $user = Auth::user();

        $this->countedPending = $user->clients->where('status', 'For Approval')->count();
        $this->countedSold = $user->clients->where('status', 'Sold')->count();
        $this->totalPending = $user->clients->where('status', 'Pending')->count();
    }

    public function render()
    {
        //  Get the current user Login Id
        $user = Auth::user();
        $ClientRecord = clients::select(
            'salesList_no',
            'company_name',
            'address',
            'email',
            'contact_number',
            'status',
            'created_at',
            'bank_account_number',
            'id'
        )
            ->where('salesman_id', $user->id)
            ->orderBy('created_at', 'desc')->paginate(20);


        return view('livewire.super-admin.manage-client.create-finish-vehicle', [
            'ClientRecord' => $ClientRecord
        ]);
    }
}

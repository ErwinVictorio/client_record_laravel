<?php

namespace App\Livewire\Modals;

use Livewire\Component;
use App\Models\clients;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;


class ClientStatusUpdate extends Component
{
    public $clientId;

    #[Validate('required')]
    public $SelectedStatus;

    #[Validate('required')]
    public $salesList_no;


    #[Validate('nullable')]
    public $bank_account_number;


    public function change_status()
    {

        $this->validate(); // validate selected status

        $client = clients::find($this->clientId); // get the client base on Id


        if ($client) { // check if we have client or the result is not found
            $client->status = $this->SelectedStatus;
            $client->salesList_no =  $this->salesList_no;
            $client->bank_account_number = $this->bank_account_number;
            $client->save(); // to save the changes


            // Flash success message
            session()->flash('success', 'Client status updated successfully.');
        } else {
            // Flash error message if client not found
            session()->flash('error', 'Client not found.');
        }
    }

    public function render()
    {
        return view('livewire.modals.client-status-update', [
            'clientId' => $this->clientId
        ]);
    }
}

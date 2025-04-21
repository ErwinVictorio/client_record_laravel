<?php

namespace App\Livewire\Modals;

use Livewire\Component;
use App\Models\clients;

class DeleteClient extends Component
{

    public $clientId,$companyName;


    public function delete_client(){

        clients::where('id', $this->clientId)->delete();
        session()->flash('success','successfully Deleted');
    }

    public function mount($clientId){ // get the company name and render to the confirmation modal

        $this->companyName = clients::findOrFail($clientId)->value('company_name');
    }

    public function render()                
    {
        return view('livewire.modals.delete-client');
    }
}

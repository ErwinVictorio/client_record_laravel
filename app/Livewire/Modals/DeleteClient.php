<?php

namespace App\Livewire\Modals;

use Livewire\Component;
use App\Models\clients;

class DeleteClient extends Component
{

    public $clientId,$company_name;

    public function mount($clientId){

        $this->clientId = $clientId;

        // find client base on id
        $company_name = clients::findOrFail($clientId)->company_name;
        
        $this->company_name = $company_name;
    }

    public function destroyClient()
    {
        $deleted = clients::where('id', $this->clientId)->delete();

        if ($deleted) {
            session()->flash('success','Client Is Successfully Deleted!');
            $this->dispatch('clients-updated');
            return;
        }

        session()->flash('error','No Record Found');
    }




    public function render()
    {
        return view('livewire.modals.delete-client');
    }
}

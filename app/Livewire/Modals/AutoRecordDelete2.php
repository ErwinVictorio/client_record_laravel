<?php

namespace App\Livewire\Modals;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\CreateRecordForAutoRepair;

class AutoRecordDelete2 extends Component
{

    public $company_name,$clientId;

    #[On('open-delete-modal')]
    public function setClient($clientId){

        $this->clientId = $clientId;
        $this->company_name = CreateRecordForAutoRepair::findOrFail($clientId)->company_name;
    }

    // to delete auto records
    public function destroyClient(){
        CreateRecordForAutoRepair::findOrFail($this->clientId)->delete();
          session()->flash('success','Successfully Deleted');
          $this->dispatch('refresh-page');
    }



    public function render()
    {
        return view('livewire.modals.auto-record-delete2');
    }
}

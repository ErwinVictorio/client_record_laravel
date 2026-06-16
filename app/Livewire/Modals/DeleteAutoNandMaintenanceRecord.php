<?php

namespace App\Livewire\Modals;

use Livewire\Component;
use App\Models\ClientRecordForMaintenanceAndRepair;
use Livewire\Attributes\On;
class DeleteAutoNandMaintenanceRecord extends Component
{

    public $company_name,$clientId;

    #[On('open-delete-modal')]
    public function SetClient($clientId){
        $this->clientId = $clientId;
        $this->company_name = ClientRecordForMaintenanceAndRepair::findOrFail($clientId)->company_name;
    }

    public function destroyClient(){
        ClientRecordForMaintenanceAndRepair::findOrFail($this->clientId)->delete();
        session()->flash('success','Successfully Deleted');
         $this->dispatch('maintenance-records-updated');
    }
    

    public function render()
    {
        return view('livewire.modals.delete-auto-nand-maintenance-record');
    }
}

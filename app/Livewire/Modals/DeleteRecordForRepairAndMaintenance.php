<?php

namespace App\Livewire\Modals;

use Livewire\Component;
use App\Models\ClientRecordForMaintenanceAndRepair;

class DeleteRecordForRepairAndMaintenance extends Component
{
    public $recordId,$company_name;

    public function mount($recordId){
        $this->recordId = $recordId;
        $this->company_name = ClientRecordForMaintenanceAndRepair::findOrFail($recordId)->value('company_name'); // to display  the company namen into modal delete comfirmation
    }

    public function delete_Record_maintenance(){
        ClientRecordForMaintenanceAndRepair::where('id', $this->recordId)
        ->delete();

        session()->flash('success','Record is Successfully Deleted');
    }
    public function render()
    {
        return view('livewire.modals.delete-record-for-repair-and-maintenance');
    }
}

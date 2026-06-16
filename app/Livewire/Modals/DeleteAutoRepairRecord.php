<?php

namespace App\Livewire\Modals;

use Livewire\Component;
use App\Models\CreateRecordForAutoRepair;

class DeleteAutoRepairRecord extends Component
{
    public $recordId,$company_name;

    public function mount($recordId){

        $this->recordId = $recordId;
        // get the cord base on id And get only comapny_name using value
        $this->company_name = CreateRecordForAutoRepair::findOrFail($this->recordId)->value('company_name');
    }

    public function delete_Record_AutoRepair(){
        CreateRecordForAutoRepair::where('id', $this->recordId)
        ->delete();

        session()->flash("success",'Record is Successfully Deleted');
        $this->dispatch('auto-repair-records-updated');
    }

    public function render()
    {
        return view('livewire.modals.delete-auto-repair-record');
    }
}

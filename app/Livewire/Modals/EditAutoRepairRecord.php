<?php

namespace App\Livewire\Modals;

use Livewire\Component;
use App\Models\CreateRecordForAutoRepair;
use Livewire\Attributes\Validate;

class EditAutoRepairRecord extends Component
{
    public $recordId;

    #[Validate('required|string')] public $company_name;
    #[Validate('required')] public $address;
    #[Validate('required|email')] public $email;
    #[Validate('required')] public $contact_number;
    #[Validate('required')] public $stock_out_number;
    #[Validate('required')] public $contact_person;
    #[Validate('required')] public $contact_number_person;
    #[Validate('nullable')] public $bank_account_number;

    public function mount($recordId){
        $this->recordId = $recordId;
        $record = CreateRecordForAutoRepair::findOrFail($recordId);

        $this->company_name = $record->company_name;
        $this->email = $record->email;
        $this->address = $record->address;
        $this->contact_number = $record->contact_number;
        $this->stock_out_number = $record->stock_out_number;
        $this->contact_number_person = $record->contact_number_person;
        $this->bank_account_number = $record->bank_account_number;
        $this->contact_person = $record->contact_person;
    }

    public function updateAutoRepair(){
        $this->validate();

        CreateRecordForAutoRepair::where('id', $this->recordId)
        ->update([
            'company_name' => $this->company_name,
            'address' => $this->address,
            'contact_number' => $this->contact_number,
            'email' => $this->email,
            'stock_out_number' => $this->stock_out_number,
            'contact_person' => $this->contact_person,
            'contact_number_person' => $this->contact_number_person,
            'bank_account_number' => $this->bank_account_number
        ]);
      
     session()->flash('success','Record is Successfully Updated');
     $this->dispatch('auto-repair-records-updated');
    }
    public function render()
    {
        return view('livewire.modals.edit-auto-repair-record');
    }
}

<?php

namespace App\Livewire\Modals;

use Livewire\Component;
use App\Models\ClientRecordForMaintenanceAndRepair;
use Livewire\Attributes\Validate;

class EditRepairAndMaintence extends Component
{
    public $recordId;


    #[Validate('required|string')] public $company_name;
    #[Validate('required')] public $address;
    #[Validate('required|email')] public $email;
    #[Validate('required')] public $contact_number;
    #[Validate('required')] public $job_order_number;
    #[Validate('required')] public $contact_person;
    #[Validate('required')] public $contact_number_person;
    #[Validate('nullable')] public $bank_account_number;

    public function mount($recordId){

        $this->recordId = $recordId;
        $record = ClientRecordForMaintenanceAndRepair::findOrFail($recordId);

        $this->company_name = $record->company_name;
        $this->contact_person = $record->contact_person;
        $this->email = $record->email;
        $this->address = $record->address;
        $this->contact_number = $record->contact_number;
        $this->job_order_number = $record->job_order_number;
        $this->contact_number_person = $record->contact_number_person;
        $this->bank_account_number = $record->bank_account_number;
    }


    public function updateRecord(){
        $this->validate();

        // update the record
        ClientRecordForMaintenanceAndRepair::where('id', $this->recordId)
        ->update([
           'company_name' => $this->company_name,
            'address' => $this->address,
             'contact_number' => $this->contact_number,
             'email' => $this->email,
             'job_order_number' => $this->job_order_number,
              'bank_account_number' => $this->bank_account_number,
              'contact_number_person' => $this->contact_number_person,
              'contact_person' => $this->contact_person
        ]);

        session()->flash('success','Record is Successfully Updated!');
    }

    public function render()
    {
        return view('livewire.modals.edit-repair-and-maintence');
    }
}

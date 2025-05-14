<?php

namespace App\Livewire\Modals;

use Livewire\Component;
use Livewire\Attributes\Validate;
use App\Models\ClientRecordForMaintenanceAndRepair;

class CreateRepairAndMaintenaceRecord extends Component
{

    #[Validate('required|string')] public $company_name;
    #[Validate('required')] public $address;
    #[Validate('required|email')] public $email;
    #[Validate('required')] public $contact_number;
    #[Validate('required')] public $job_order_number;
    #[Validate('required')] public $contact_person;
    #[Validate('required')] public $contact_number_person;
    #[Validate('nullable')] public $bank_account_number;

    public function create_client_for_maintenance(){
      $validated = $this->validate();

       ClientRecordForMaintenanceAndRepair::create($validated);

       session()->flash('success', 'New Record is Successfully');
        $this->reset();
       $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.modals.create-repair-and-maintenace-record');
    }
}

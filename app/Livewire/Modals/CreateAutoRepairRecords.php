<?php

namespace App\Livewire\Modals;

use Livewire\Attributes\Validate;
use Livewire\Component;
use App\Models\CreateRecordForAutoRepair;


class CreateAutoRepairRecords extends Component
{
    #[Validate('required|string')] public $company_name;
    #[Validate('required')] public $address;
    #[Validate('required|email')] public $email;
    #[Validate('required')] public $contact_number;
    #[Validate('required')] public $stock_out_number;
    #[Validate('required')] public $contact_person;
    #[Validate('required')] public $contact_number_person;
    #[Validate('nullable')] public $bank_account_number;

    public function create_client(){
     $validated = $this->validate();

      CreateRecordForAutoRepair::create($validated);
      session()->flash('success','New Record is Successfully Created');
    }

    public function render()
    {
        return view('livewire.modals.create-auto-repair-records');
    }
}

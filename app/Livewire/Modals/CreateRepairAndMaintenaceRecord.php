<?php

namespace App\Livewire\Modals;

use Livewire\Component;
use Livewire\Attributes\Validate;
use App\Models\ClientRecordForMaintenanceAndRepair;
use Illuminate\Support\Facades\Auth;

class CreateRepairAndMaintenaceRecord extends Component
{

    public $salesmanId;
    #[Validate('required|string')] public $company_name;
    #[Validate('required')] public $address;
    #[Validate('required|email')] public $email;
    #[Validate('required')] public $contact_number;
    #[Validate('required')] public $job_order_number;
    #[Validate('required')] public $contact_person;
    #[Validate('required')] public $contact_number_person;
    #[Validate('nullable')] public $bank_account_number;
    

    public function create_client_for_maintenance():void{
       $this->validate();

       $this->salesmanId = Auth::id();


       ClientRecordForMaintenanceAndRepair::create([
         'company_name' => $this->company_name,
         'address' => $this->address,
          'email' => $this->email,
          'contact_number' => $this->contact_number,
          'job_order_number' => $this->job_order_number,
          'contact_person' => $this->contact_person,
          'contact_number_person' => $this->contact_number_person,
          'bank_account_number' => $this->bank_account_number,
          'salesmanId' => $this->salesmanId
       ]);


       session()->flash('success', 'New Record is Successfully Created');
        $this->reset();
       $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.modals.create-repair-and-maintenace-record');
    }
}

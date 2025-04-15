<?php

namespace App\Livewire\Modals;

use Livewire\Component;
use Livewire\Attributes\Validate;
use App\Models\clients;
class ClientCreate extends Component
{

    #[Validate('required')] public $contact_person_number;
    #[Validate('required|string')] public $contact_person;
    #[Validate('required|email|unique:clients,email')] public $email;
    #[Validate('required')] public $address;
    #[Validate('required|string')] public $CompanyName;
    #[Validate('nullable')] public $bank_Account_number;
    #[Validate('required')] public $contact_number;
 //    public $userId = session('userId');
 
     public function create_client(){
       $this->validate();
       
      clients::create([
         'company_name' => $this->CompanyName,
         'address' => $this->address,
         'email' => $this->email,
         'contact_person' => $this->contact_person,
         'contact_number_person' => $this->contact_person_number,
         'bank_account_number' => $this->bank_Account_number,
         'contact_number' => $this->contact_number,
         'salesman_id' => session('userId')
      ]);
 
      session()->flash('success', 'New Client is Successfully Created');
      $this->reset();   
     }

    public function render()
    {
        return view('livewire.modals.client-create');
    }
}

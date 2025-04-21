<?php

namespace App\Livewire\Modals;

use Livewire\Component;
use Livewire\Attributes\Validate;
use App\Models\clients;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ClientCreate extends Component
{

    #[Validate('required')] public $contact_person_number;
    #[Validate('required|string')] public $contact_person;
    #[Validate('required|email|email')] public $email;
    #[Validate('required')] public $address;
    #[Validate('required|string')] public $CompanyName;
    #[Validate('nullable')] public $bank_Account_number;
    #[Validate('required')] public $contact_number;
 //    public $userId = session('userId');
 
     public function create_client(){
       $this->validate();

    // Get The id of the autheticated user
     $currentSalesman = Auth::id();

       // Check if the client with this email already exists
    $existingClient = Clients::where('email', $this->email)->first();

    if ($existingClient) {
        // If the client is already assigned to a different salesman
        if ($existingClient->salesman_id != $currentSalesman) {
            session()->flash('error',"The client is already taken!");
            return;
        } else {
            // Optional: if the same salesman tries to add the same client again
            session()->flash('error',"You have already added this client.");
            return;
        }
    }

       
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

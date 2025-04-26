<?php

namespace App\Livewire\Modals;

use Livewire\Component;
use App\Models\clients;
use Livewire\Attributes\Validate;

class EditClientInfo extends Component
{
    public $clientId;

    #[Validate('required')]
     public $CompanyName,$address,$email,$contact_person,$contact_person_number,$contact_number,$bank_Account_number;

     
     public function mount($clientId){
        $this->clientId = $clientId;
        // Get the client info base on ClientId
        $client = clients::findOrFail($clientId);

        $this->CompanyName = $client->company_name;
        $this->address = $client->address;
        $this->email = $client->email;
        $this->contact_number = $client->contact_number;
        $this->contact_person_number = $client->contact_number_person;
        $this->bank_Account_number = $client->bank_account_number;
        $this->contact_person = $client->contact_person;
     }


     public function update_clientInfo(){

        clients::where('id', $this->clientId)
        ->update([
           'company_name' => $this->CompanyName,
           'address' => $this->address,
           'email' => $this->email,
           'contact_number' => $this->contact_number,
           'contact_number_person' => $this->contact_person_number,
           'bank_account_number' => $this->bank_Account_number,
           'contact_person' => $this->contact_person
        ]);

        session()->flash('success',"$this->CompanyName is successfully updated");

     }

    public function render()
    {
        return view('livewire.modals.edit-client-info');
    }
}

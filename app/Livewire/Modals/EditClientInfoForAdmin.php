<?php

namespace App\Livewire\Modals;

use Livewire\Component;
use App\Models\clients;
use Livewire\Attributes\Validate;

class EditClientInfoForAdmin extends Component
{
    public $clientId;
    public bool $isPersonalClient = false;

    #[Validate('required')] public $company_name;
    #[Validate('nullable|string|max:255')] public $first_name;
    #[Validate('nullable|string|max:255')] public $middle_name;
    #[Validate('nullable|string|max:255')] public $last_name;
    #[Validate('required')] public $contact_number;
    #[Validate('required|email')] public $email;
    #[Validate('required')] public $address;
    #[Validate('required')] public $contact_person;
    #[Validate('required')] public $contact_number_person;
    #[Validate('nullable')] public $bank_account_number;
    #[Validate('nullable')] public $item_name;
    #[Validate('nullable')] public $model_number;
    #[Validate('nullable')] public $specification;
    #[Validate('nullable')] public $quantity;

    public function mount($clientId){
      $this->clientId = $clientId;

      $client = clients::findOrFail($clientId);
      
      $this->company_name = $client->company_name;
      $this->first_name = $client->first_name;
      $this->middle_name = $client->middle_name;
      $this->last_name = $client->last_name;
      $this->isPersonalClient = filled($client->first_name) || filled($client->middle_name) || filled($client->last_name);
      $this->contact_number = $client->contact_number;
      $this->email = $client->email;
      $this->address = $client->address;
      $this->contact_person = $client->contact_person;
      $this->contact_number_person = $client->contact_number_person;
      $this->item_name = $client->item_name;
      $this->model_number = $client->model_number;
      $this->specification = $client->specification;
      $this->quantity = $client->quantity;
      $this->bank_account_number = $client->bank_account_number;
    }

    public function updateClientInfo(){
     
      $this->validate();

      if ($this->isPersonalClient) {
          $this->validate([
              'first_name' => 'required|string|max:255',
              'middle_name' => 'nullable|string|max:255',
              'last_name' => 'required|string|max:255',
          ]);
      }

      $personalName = trim(implode(' ', array_filter([
          $this->first_name,
          $this->middle_name,
          $this->last_name,
      ], fn ($name) => filled($name))));

      clients::where('id', $this->clientId)
      ->update([
          'company_name' => $this->company_name,
          'first_name' => filled($this->first_name) ? trim($this->first_name) : null,
          'middle_name' => filled($this->middle_name) ? trim($this->middle_name) : null,
          'last_name' => filled($this->last_name) ? trim($this->last_name) : null,
          'contact_number' => $this->contact_number,
          'email' => $this->email,
          'address' => $this->address,
          'contact_person' => $this->isPersonalClient && $personalName !== '' ? $personalName : $this->contact_person,
          'contact_number_person' => $this->contact_number_person,
          'item_name' => $this->item_name,
          'model_number' => $this->model_number,
          'specification' => $this->specification,
          'quantity' => $this->quantity,
          'bank_account_number' => $this->bank_account_number
      ]);

      session()->flash('success', 'Client details have been successfully updated.');
      $this->dispatch('clients-updated');

    }

    public function render()
    {
        return view('livewire.modals.edit-client-info-for-admin');
    }
}

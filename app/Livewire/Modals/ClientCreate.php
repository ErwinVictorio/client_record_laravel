<?php

namespace App\Livewire\Modals;

use Livewire\Attributes\Rule;
use Livewire\Component;
use App\Models\clients;
use Illuminate\Support\Facades\Auth;

class ClientCreate extends Component
{
    #[Rule('required')] public $contact_person_number;
    #[Rule('required|string')] public $contact_person;
    #[Rule('required|email')] public $email;
    #[Rule('required')] public $address;
    #[Rule('required|string')] public $CompanyName;
    #[Rule('nullable')] public $bank_Account_number;
    #[Rule('required')] public $contact_number;

    public bool $showConfirmation = false;

    public function validateAndConfirm(): void
    {
        $this->validate();

        $existingClient = clients::where('email', $this->email)->first();
        $currentSalesman = Auth::id();

        if ($existingClient) {
            if ($existingClient->salesman_id !== $currentSalesman) {
                session()->flash('error', "The client is already taken!");
                return;
            } else {
                session()->flash('error', "You have already added this client.");
                return;
            }
        }

        $this->showConfirmation = true;
    }

    public function createClient(): void
    {
        $currentSalesman = Auth::id();

        Clients::create([
            'company_name' => $this->CompanyName,
            'address' => $this->address,
            'email' => $this->email,
            'contact_person' => $this->contact_person,
            'contact_number_person' => $this->contact_person_number,
            'bank_account_number' => $this->bank_Account_number,
            'contact_number' => $this->contact_number,
            'salesman_id' => $currentSalesman,
        ]);

        session()->flash('success', 'New Client is Successfully Created');

        $this->reset([
            'CompanyName',
            'address',
            'email',
            'contact_person',
            'contact_person_number',
            'bank_Account_number',
            'contact_number',
            'showConfirmation',
        ]);

        $this->dispatch('close-modal'); // optional: to control modal externally
    }

    public function render()
    {
        return view('livewire.modals.client-create');
    }
}


<?php

namespace App\Livewire\Modals;

use Livewire\Attributes\Rule;
use Livewire\Component;
use App\Models\Clients; // Siguraduhing tugma ang case ng Model mo (karaniwang capitalized 'Clients')
use Illuminate\Support\Facades\Auth;

class ClientCreate extends Component
{
    // Basic Client Properties gamit ang Form Attributes
    #[Rule('required|string')] public $CompanyName;
    #[Rule('required')] public $address;
    #[Rule('required|email')] public $email;
    #[Rule('required|numeric')] public $contact_number;
    #[Rule('required|string')] public $contact_person;
    #[Rule('required|numeric')] public $contact_person_number;
    #[Rule('nullable')] public $bank_Account_number;

    public bool $showConfirmation = false;

    public function validateAndConfirm(): void
    {
        // I-run muna ang standard properties validation (galing sa #[Rule])
        $this->validate();

        // Linisin ang Company Name para sa checking ng duplicate
        $companyNameCleaned = strtoupper(str_replace(' ', '', trim($this->CompanyName)));
      
        // Check kung kinuha na ng ibang salesman ang kliyente na ito gamit ang company name
        $existingClient = Clients::whereRaw("REPLACE(UPPER(company_name), ' ', '') = ?", [$companyNameCleaned])->first();
        $currentSalesman = Auth::id();

        if ($existingClient) {
            if ($existingClient->salesman_id !== $currentSalesman) {
                session()->flash('error', "The client is already taken!");
                return;
            } 
        }

        $this->showConfirmation = true;
    }       

    public function createClient(): void
    {
        $currentSalesman = Auth::id();

        // I-save ang primary Client information
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
        $this->dispatch('clients-updated');

        // I-reset ang mga public properties pabalik sa default state
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
        $this->dispatch('close-modal'); 
    }

    public function render()
    {
        return view('livewire.modals.client-create');
    }
}

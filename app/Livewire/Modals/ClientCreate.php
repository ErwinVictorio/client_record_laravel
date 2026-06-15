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

    // Dynamic Vehicles Array
    public array $vehicles = [];

    public bool $showConfirmation = false;

    public function mount(): void
    {
        // Mag-initialize ng isang empty array para sa unang sasakyan pagkabukas ng modal
        $this->addVehicle();
    }

    public function addVehicle(): void
    {
        $this->vehicles[] = [
            'brand' => '',
            'model' => '',
            'loading_capacity' => '',
            'lifting_height' => '',
            'mast_type' => '',
            'power_type' => '',
            'tire' => '',
            'fork_length' => '',
            'attachment' => ''
        ];
    }

    public function removeVehicle(int $index): void
    {
        // Siguraduhing hindi mabubura lahat, iwanan ang kahit isa
        if (count($this->vehicles) > 1) {
            unset($this->vehicles[$index]);
            $this->vehicles = array_values($this->vehicles); // Para ma-reset ang array index key (0, 1, 2...)
        }
    }

    public function validateAndConfirm(): void
    {
        // I-run muna ang standard properties validation (galing sa #[Rule])
        $this->validate();

        // I-validate naman ang dynamic array fields
        $this->validate([
            'vehicles.*.brand' => 'required|string',
            'vehicles.*.model' => 'required|string',
        ], [
            'vehicles.*.brand.required' => 'The brand field is required.',
            'vehicles.*.model.required' => 'The model field is required.',
        ]);

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

        // 1. I-save ang primary Client information
        $client = Clients::create([
            'company_name' => $this->CompanyName,
            'address' => $this->address,
            'email' => $this->email,
            'contact_person' => $this->contact_person,
            'contact_number_person' => $this->contact_person_number,
            'bank_account_number' => $this->bank_Account_number,
            'contact_number' => $this->contact_number,
            'salesman_id' => $currentSalesman,
        ]);

        // 2. I-loop at i-save ang bawat input na sasakyan gamit ang relationship ng Client model
        foreach ($this->vehicles as $vehicle) {
            $client->vehicles()->create([
                'brand'            => $vehicle['brand'],
                'model'            => $vehicle['model'],
                'loading_capacity' => $vehicle['loading_capacity'],
                'lifting_height'   => $vehicle['lifting_height'],
                'mast_type'        => $vehicle['mast_type'],
                'power_type'       => $vehicle['power_type'],
                'tire'             => $vehicle['tire'],
                'fork_length'      => $vehicle['fork_length'],
                'attachment'       => $vehicle['attachment'],
            ]);
        }

        session()->flash('success', 'New Client and Vehicle Specifications are Successfully Created');

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
        
        // I-clear ang array at bigyan muli ng isang default empty dynamic row
        $this->vehicles = [];
        $this->addVehicle();

        $this->dispatch('close-modal'); 
    }

    public function render()
    {
        return view('livewire.modals.client-create');
    }
}
<?php

namespace App\Livewire\Modals;

use Livewire\Component;
use App\Models\Clients;
use App\Models\Suffix;
use Illuminate\Support\Facades\Auth;

class ClientCreate extends Component
{
    // Toggle Indicator (Default: corporate)
    public string $client_type = 'corporate';

    // Corporate Specific Fields
    public $CompanyName;
    public $suffix = '';
    public $contact_person;
    public $contact_person_number;

    // Personal Specific Fields
    public $first_name;
    public $middle_name;
    public $last_name;

    // Shared Fields
    public $address;
    public $email;
    public $contact_number;
    public $bank_Account_number;

    public bool $showConfirmation = false;

    /**
     * Dynamic Validation Rules based on Selected Client Type
     */
    protected function rules(): array
    {
        if ($this->client_type === 'personal') {
            return [
                'first_name'          => 'required|string|max:255',
                'middle_name'         => 'nullable|string|max:255',
                'last_name'           => 'required|string|max:255',
                'address'             => 'required|string',
                'email'               => 'required|email',
                'contact_number'      => 'required|numeric',
                'bank_Account_number' => 'nullable',
            ];
        }

        return [
            'CompanyName'           => 'required|string|max:255',
            'suffix'                => 'nullable|string|exists:company_suffix,suffix',
            'contact_person'        => 'required|string',
            'contact_person_number' => 'required|numeric',
            'address'               => 'required|string',
            'email'                 => 'required|email',
            'contact_number'        => 'required|numeric',
            'bank_Account_number'   => 'nullable',
        ];
    }

    /**
     * Helper to get full name string for display/checking
     */
    private function getFormattedPersonalName(): string
    {
        $names = array_filter([
            trim($this->first_name),
            trim($this->middle_name),
            trim($this->last_name)
        ]);

        return implode(' ', $names);
    }

    public function validateAndConfirm(): void
    {
        // Dynamic Validation
        $this->validate();

        $currentSalesman = Auth::id();

        // Check Duplicates depending on Client Type
        if ($this->client_type === 'personal') {
            $fullNameCleaned = strtoupper(str_replace(' ', '', $this->getFormattedPersonalName()));

            $existingClient = Clients::whereRaw(
                "REPLACE(UPPER(CONCAT(IFNULL(first_name, ''), IFNULL(middle_name, ''), IFNULL(last_name, ''))), ' ', '') = ?",
                [$fullNameCleaned]
            )->first();
        } else {
            $companyNameCleaned = strtoupper(str_replace(' ', '', trim($this->CompanyName)));

            $existingClientQuery = Clients::whereRaw(
                "REPLACE(UPPER(company_name), ' ', '') = ?",
                [$companyNameCleaned]
            );

            if ($this->suffix) {
                $existingClientQuery->where('suffix', $this->suffix);
            } else {
                $existingClientQuery->whereNull('suffix');
            }

            $existingClient = $existingClientQuery->first();
        }

        if ($existingClient) {
            if ($existingClient->salesman_id !== $currentSalesman) {
                session()->flash('error', "The client is already taken by another salesman!");
            } else {
                session()->flash('error', "The client already exists!");
            }
            return;
        }

        $this->showConfirmation = true;
    }

    public function createClient(): void
    {
        $currentSalesman = Auth::id();

        if ($this->client_type === 'personal') {
            $fullName = $this->getFormattedPersonalName();

            Clients::create([
                'company_name'          => 'N/A', // Personal clients don't have a company name
                'first_name'            => trim($this->first_name),
                'middle_name'           => $this->middle_name ? trim($this->middle_name) : null,
                'last_name'             => trim($this->last_name),
                'contact_person'        => $fullName, // Mismong client na ang contact person
                'contact_number_person' => $this->contact_number,
                'address'               => $this->address,
                'email'                 => $this->email,
                'contact_number'        => $this->contact_number,
                'bank_account_number'   => $this->bank_Account_number,
                'salesman_id'           => $currentSalesman,
            ]);
        } else {
            Clients::create([
                'company_name'          => trim($this->CompanyName),
                'suffix'                => $this->suffix ?: null,
                'first_name'            => null,
                'middle_name'           => null,
                'last_name'             => null,
                'contact_person'        => trim($this->contact_person),
                'contact_number_person' => $this->contact_person_number,
                'address'               => $this->address,
                'email'                 => $this->email,
                'contact_number'        => $this->contact_number,
                'bank_account_number'   => $this->bank_Account_number,
                'salesman_id'           => $currentSalesman,
            ]);
        }

        session()->flash('success', 'New Client is Successfully Created');
        $this->dispatch('clients-updated');

        // Reset all public properties
        $this->reset([
            'client_type',
            'CompanyName',
            'suffix',
            'first_name',
            'middle_name',
            'last_name',
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
        return view('livewire.modals.client-create', [
            'suffixes' => Suffix::query()->orderBy('suffix')->get(),
        ]);
    }
}

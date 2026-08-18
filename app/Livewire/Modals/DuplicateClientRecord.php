<?php

namespace App\Livewire\Modals;

use App\Models\clients;
use App\Models\Suffix;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DuplicateClientRecord extends Component
{
    public int $clientId;
    public string $context = 'salesman';
    public string $client_type = 'corporate';

    public $CompanyName;
    public $suffix = '';
    public $contact_person;
    public $contact_person_number;

    public $first_name;
    public $middle_name;
    public $last_name;

    public $address;
    public $email;
    public $contact_number;
    public $bank_Account_number;

    public bool $showConfirmation = false;

    public function mount(int $clientId, string $context = 'salesman'): void
    {
        $this->clientId = $clientId;
        $this->context = $context;

        $client = $this->findSourceClient();
        $this->fillFromClient($client);
    }

    protected function rules(): array
    {
        if ($this->client_type === 'personal') {
            return [
                'first_name' => 'required|string|max:255',
                'middle_name' => 'nullable|string|max:255',
                'last_name' => 'required|string|max:255',
                'address' => 'required|string',
                'email' => 'required|email',
                'contact_number' => 'required|numeric',
                'bank_Account_number' => 'nullable',
            ];
        }

        return [
            'CompanyName' => 'required|string|max:255',
            'suffix' => 'nullable|string|exists:company_suffix,suffix',
            'contact_person' => 'required|string',
            'contact_person_number' => 'required|numeric',
            'address' => 'required|string',
            'email' => 'required|email',
            'contact_number' => 'required|numeric',
            'bank_Account_number' => 'nullable',
        ];
    }

    public function validateAndConfirm(): void
    {
        $this->validate();
        $this->showConfirmation = true;
    }

    public function createNewUnitRecord(): void
    {
        $this->validate();

        $sourceClient = $this->findSourceClient();
        $salesmanId = $this->context === 'super-admin'
            ? $sourceClient->salesman_id
            : Auth::id();

        if ($this->client_type === 'personal') {
            $fullName = $this->getFormattedPersonalName();

            clients::create([
                'company_name' => 'N/A',
                'suffix' => null,
                'first_name' => trim($this->first_name),
                'middle_name' => filled($this->middle_name) ? trim($this->middle_name) : null,
                'last_name' => trim($this->last_name),
                'contact_person' => $fullName,
                'contact_number_person' => $this->contact_number,
                'address' => $this->address,
                'email' => $this->email,
                'contact_number' => $this->contact_number,
                'bank_account_number' => $this->bank_Account_number,
                'salesman_id' => $salesmanId,
                'status' => 'Pending',
            ]);
        } else {
            clients::create([
                'company_name' => trim($this->CompanyName),
                'suffix' => $this->suffix ?: null,
                'first_name' => null,
                'middle_name' => null,
                'last_name' => null,
                'contact_person' => trim($this->contact_person),
                'contact_number_person' => $this->contact_person_number,
                'address' => $this->address,
                'email' => $this->email,
                'contact_number' => $this->contact_number,
                'bank_account_number' => $this->bank_Account_number,
                'salesman_id' => $salesmanId,
                'status' => 'Pending',
            ]);
        }

        $this->showConfirmation = false;
        $this->dispatch('hide-duplicate-client-record-modal-'.$this->clientId);
    }

    private function findSourceClient(): clients
    {
        $client = clients::query()->whereKey($this->clientId)->firstOrFail();

        if ($this->context !== 'super-admin' && (int) $client->salesman_id !== (int) Auth::id()) {
            abort(403);
        }

        return $client;
    }

    private function fillFromClient(clients $client): void
    {
        $this->client_type = filled($client->first_name) || filled($client->middle_name) || filled($client->last_name)
            ? 'personal'
            : 'corporate';

        $this->CompanyName = $client->company_name === 'N/A' ? null : $client->company_name;
        $this->suffix = $client->suffix ?: '';
        $this->first_name = $client->first_name;
        $this->middle_name = $client->middle_name;
        $this->last_name = $client->last_name;
        $this->address = $client->address;
        $this->email = $client->email;
        $this->contact_number = $client->contact_number;
        $this->contact_person = $client->contact_person;
        $this->contact_person_number = $client->contact_number_person;
        $this->bank_Account_number = $client->bank_account_number;
    }

    private function getFormattedPersonalName(): string
    {
        return implode(' ', array_filter([
            trim($this->first_name),
            filled($this->middle_name) ? trim($this->middle_name) : null,
            trim($this->last_name),
        ]));
    }

    public function render()
    {
        return view('livewire.modals.duplicate-client-record', [
            'suffixes' => Suffix::query()->orderBy('suffix')->get(),
        ]);
    }
}

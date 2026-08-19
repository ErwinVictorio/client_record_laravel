<?php

namespace App\Livewire\Modals;

use Livewire\Component;
use App\Models\Clients;
use App\Models\Suffix;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClientCreate extends Component
{
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
            'CompanyName'           => [
                'required',
                'string',
                'max:255',
                'regex:/^\p{L}+(?: \p{L}+)*$/u',
            ],
            'suffix'                => 'nullable|string|exists:company_suffix,suffix',
            'contact_person'        => 'required|string',
            'contact_person_number' => 'required|numeric',
            'address'               => 'required|string',
            'email'                 => 'required|email',
            'contact_number'        => 'required|numeric',
            'bank_Account_number'   => 'nullable',
        ];
    }

    private function getFormattedPersonalName(): string
    {
        $names = array_filter([
            trim($this->first_name),
            trim($this->middle_name),
            trim($this->last_name),
        ]);

        return implode(' ', $names);
    }

    private function normalizeBaseName($name): string
    {
        $name = (string) $name;
        $upperName = function_exists('mb_strtoupper')
            ? mb_strtoupper($name, 'UTF-8')
            : strtoupper($name);

        return preg_replace('/[^\p{L}\p{N}]+/u', '', $upperName) ?? '';
    }

    private function findDuplicateClient()
    {
        if ($this->client_type === 'personal') {
            $normalizedBaseName = $this->normalizeBaseName($this->getFormattedPersonalName());

            $baseNameCondition = "REGEXP_REPLACE(
                UPPER(CONCAT(
                    COALESCE(first_name, ''),
                    COALESCE(middle_name, ''),
                    COALESCE(last_name, '')
                )),
                '[^[:alnum:]]',
                ''
            ) = ?";
        } else {
            $normalizedBaseName = $this->normalizeBaseName($this->CompanyName);

            // Suffix is deliberately ignored here.
            $baseNameCondition = "REGEXP_REPLACE(
                UPPER(COALESCE(company_name, '')),
                '[^[:alnum:]]',
                ''
            ) = ?";
        }

        $otherSalesmenClients = Clients::query()
            ->where('salesman_id', '!=', Auth::id());

        // SQLite does not provide MySQL's REGEXP_REPLACE function used in production.
        if (DB::connection()->getDriverName() === 'sqlite') {
            return $otherSalesmenClients->get()->first(function (Clients $client) use ($normalizedBaseName) {
                $clientName = $this->client_type === 'personal'
                    ? implode(' ', array_filter([$client->first_name, $client->middle_name, $client->last_name]))
                    : $client->company_name;

                return $this->normalizeBaseName($clientName) === $normalizedBaseName;
            });
        }

        return $otherSalesmenClients
            ->whereRaw($baseNameCondition, [$normalizedBaseName])
            ->first();
    }

    private function hasDuplicateClient(): bool
    {
        $existingClient = $this->findDuplicateClient();

        if (! $existingClient) {
            return false;
        }

        session()->flash('error', 'The client is already taken by another salesman!');

        return true;
    }

    public function validateAndConfirm(): void
    {
        $this->validate();

        if ($this->hasDuplicateClient()) {
            return;
        }

        $this->showConfirmation = true;
    }

    public function createClient(): void
    {
        // Prevent direct Livewire requests from bypassing duplicate checks.
        $this->validate();

        if ($this->hasDuplicateClient()) {
            $this->showConfirmation = false;
            return;
        }

        $currentSalesman = Auth::id();

        if ($this->client_type === 'personal') {
            $fullName = $this->getFormattedPersonalName();

            Clients::create([
                'company_name'          => 'N/A',
                'suffix'                => null,
                'first_name'            => trim($this->first_name),
                'middle_name'           => $this->middle_name ? trim($this->middle_name) : null,
                'last_name'             => trim($this->last_name),
                'contact_person'        => $fullName,
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

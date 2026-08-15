<?php

namespace App\Livewire\Modals;

use App\Models\clients;
use Livewire\Component;

class DeleteClient extends Component
{
    public $clientId;

    public $company_name;

    public function mount($clientId): void
    {
        $client = clients::findOrFail($clientId);

        $this->clientId = $client->id;
        $this->company_name = $client->display_name;
    }

    public function destroyClient(): void
    {
        $client = clients::find($this->clientId);

        if (! $client) {
            session()->flash('error', 'Client record not found. It may have already been deleted.');

            return;
        }

        $client->delete();

        // The browser refreshes the table only after Bootstrap finishes hiding
        // the modal, preventing Livewire from removing it before its backdrop.
        $this->dispatch('hide-delete-client-modal');
    }

    public function render()
    {
        return view('livewire.modals.delete-client');
    }
}

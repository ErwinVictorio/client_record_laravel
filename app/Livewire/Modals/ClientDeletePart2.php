<?php

namespace App\Livewire\Modals;

use App\Models\clients;
use Livewire\Attributes\On;
use Livewire\Component;

class ClientDeletePart2 extends Component
{
    public $clientId;

    public $company_name;

    #[On('open-delete-modal')]
    public function setClient($clientId): void
    {
        $client = clients::find($clientId);

        $this->resetErrorBag();
        session()->forget(['success', 'error']);
        $this->clientId = $client?->id;
        $this->company_name = $client?->display_name;

        if (! $client) {
            session()->flash('error', 'The selected client could not be found.');
        }
    }

    public function destroyClient(): void
    {
        $client = clients::find($this->clientId);

        if (! $client) {
            session()->flash('error', 'Client record not found. It may have already been deleted.');

            return;
        }

        $client->delete();

        $this->dispatch('hide-client-delete-modal');
    }

    public function render()
    {
        return view('livewire.modals.client-delete-part2');
    }
}

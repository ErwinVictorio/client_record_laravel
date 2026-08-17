<?php

namespace App\Livewire\Modals;

use App\Models\clients;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Locked;
use Livewire\Component;

class ViewClientDetails extends Component
{
    #[Locked]
    public int $clientId;

    public function mount(int $clientId): void
    {
        $this->clientId = $clientId;
    }

    public function render()
    {
        $client = clients::with('salesman')
            ->whereKey($this->clientId)
            ->where('salesman_id', Auth::id())
            ->first();

        return view('livewire.modals.view-client-details', [
            'client' => $client,
        ]);
    }
}

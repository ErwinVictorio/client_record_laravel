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
        $user = Auth::user();

        $clientQuery = clients::with('salesman')
            ->whereKey($this->clientId);

        // Admins can inspect every client; other roles remain owner-scoped.
        if (! $user || ! in_array((int) $user->role, [0, 1], true)) {
            $clientQuery->where('salesman_id', $user?->id);
        }

        $client = $clientQuery->first();

        return view('livewire.modals.view-client-details', [
            'client' => $client,
        ]);
    }
}

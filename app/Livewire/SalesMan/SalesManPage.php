<?php

namespace App\Livewire\SalesMan;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class SalesManPage extends Component
{
    use WithPagination;

    public $countedPending, $countedSold;

    public function mount()
    {
        $user = Auth::user();

        $this->countedPending = $user->clients->where('status', 'Pending')->count();
        $this->countedSold = $user->clients->where('status', 'Sold')->count();
    }

    public function render()
    {
        $user = Auth::user();

        $clients = $user->clients()->paginate(9); 

        return view('livewire.sales-man.sales-man-page', [
            'clients' => $clients,
        ]);
    }
}

<?php

namespace App\Livewire\SalesMan;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;


class SalesManPage extends Component
{
    use WithPagination;

    public $countedPending, $countedSold,$searchQuery= '' ,$clientSearch = '',$totalPending = '';

    public function mount()
    {
        $this->loadTotals();
    }

    public function loadTotals()
    {
        $user = Auth::user();

        $this->countedPending = $user->clients->where('status', 'For Approval')->count();
        $this->countedSold = $user->clients->where('status', 'Sold')->count();
        $this->totalPending = $user->clients->where('status', 'Pending')->count();
    }

    #[On('clients-updated')]
    public function refreshClients()
    {
        $this->loadTotals();
        $this->resetPage();
    }

    public function applySearch(){
        
        $this->searchQuery = $this->clientSearch;
        $this->resetPage();
    }

    public function render()
    {
        $user = Auth::user();
        $search  = '%' . $this->searchQuery . '%';

           $clients = $user->clients()->where(function($query) use ($search){
            $query->where('company_name','like',$search);
            $query->orwhere('status', 'like', $search);
            $query->orwhere('salesList_no', 'like', $search);
            $query->orwhere('email', 'like', $search);
        })
         ->orderBy('created_at', 'desc')
         ->paginate(20);

        
        return view('livewire.sales-man.sales-man-page', [
            'clients' => $clients,
        ]);
    }
}

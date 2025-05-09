<?php

namespace App\Livewire\SalesMan;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class SalesManPage extends Component
{
    use WithPagination;

    public $countedPending, $countedSold,$searchQuery= '' ,$clientSearch = '';

    public function mount()
    {
        $user = Auth::user();

        $this->countedPending = $user->clients->where('status', 'Pending')->count();
        $this->countedSold = $user->clients->where('status', 'Sold')->count();
    }

    public function applySearch(){
        
        $this->searchQuery = $this->clientSearch;
        $this->resetPage();
    }

    public function render()
    {
        $user = Auth::user();
        $search  = '%' . $this->searchQuery . '%';

        $clients = $user->clients()->where(function ($query) use ($search){
            $query->where('company_name','like',$search);
            $query->orwhere('status', 'like', $search);
        })->paginate(5);

        return view('livewire.sales-man.sales-man-page', [
            'clients' => $clients,
        ]);
    }
}

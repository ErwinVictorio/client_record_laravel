<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\clients;
use Livewire\WithPagination;


class Dashboard extends Component
{
    use WithPagination;

    public $totalClient,$totalPending,$totalSold;

    public $ClientSearch = '';
    public $searchQuery = '';

    public function applySearch()
    {
        $this->searchQuery = $this->ClientSearch;
        $this->resetPage();
    }
    
 
    public function mount(){
         $this->totalClient = clients::count();
         $this->totalPending = clients::where('status', 'Pending')->count();
         $this->totalSold = clients::where('status', 'Sold')->count();
    }

    public function render()
    {
        $search = '%' . $this->searchQuery . '%';
    
        $clientList = clients::with('salesman')
            ->where(function ($query) use ($search) {
                $query->where('company_name', 'like', $search)
                      ->orWhere('email', 'like', $search)
                      ->orWhere('contact_number', 'like', $search)
                      ->orWhere('status', 'like', $search);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);
    
        return view('livewire.admin.dashboard', [
            'clientList' => $clientList
        ]);
    }
    
}

<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\clients;
use Livewire\WithPagination;


class Dashboard extends Component
{
    use WithPagination;

    public $totalClient,$totalPending,$totalSold = '', $totalApproval = '';

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
         $this->totalApproval = clients::where('status', 'For Approval')->count();
    }

    public function render()
    {
        $search = '%' . $this->searchQuery . '%';
    
        $clientList = clients::with('salesman')             
            ->where(function ($query) use ($search) {
                $query->where('company_name', 'like', $search)
                      ->orWhere('email', 'like', $search)
                      ->orWhere('contact_number', 'like', $search)
                      ->orWhere('status', 'like', $search)
                       ->orWhereHas('salesman', function ($q) use ($search) {
                     $q->where('first_name', 'like', $search)
                    ->orWhere('last_name', 'like', $search)
                    ->orWhere('department', 'like', $search); // Optional depending on your needs
              });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);
    
        return view('livewire.admin.dashboard', [
            'clientList' => $clientList
        ]);
    }
    
}

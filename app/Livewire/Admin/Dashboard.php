<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\clients;

class Dashboard extends Component
{
    public $totalClient,$totalPending,$totalSold,$totalApproved,$clientList;

    public function mount(){
        $this->clientList = clients::with('salesman')->select([
            'company_name',
             'id',
             'address',
            'contact_number',
             'email',
             'status',
             'salesman_id'
         ])->get();
 
         $this->totalClient = clients::count();
         $this->totalPending = clients::where('status', 'Pending')->count();
         $this->totalSold = clients::where('status', 'Sold')->count();
         $this->totalApproved = clients::where('status', 'Approve')->count();
    }

    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}

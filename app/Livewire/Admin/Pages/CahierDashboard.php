<?php

namespace App\Livewire\Admin\Pages;

use Livewire\Component;
use App\Models\clients;
use Illuminate\Support\Facades\DB;

class CahierDashboard extends Component
{

    public $clients,$countedAprove,$counttedSoldClient;

    public function mount(){
        $this->clients = clients::with(['salesman'])
        ->whereIn('status', ['Sold', 'Approve']) // 👈 Only these statuses
        ->select([
            'id',
            'company_name',
            'address',
            'contact_number',
             'contact_person',
            'email',
            'status',
            'salesman_id'
        ])
        ->get();
    

        $clientStatusCount = clients::selectRaw('status, COUNT(*) as total')
         ->whereIn('status',['Approve', 'Sold'])
         ->groupBy('status')
         ->pluck('total', 'status');

         $this->countedAprove = $clientStatusCount['Approve'] ?? 0;
         $this->counttedSoldClient = $clientStatusCount['Sold'] ?? 0;
    }

    public function render()
    {
        return view('livewire.admin.pages.cahier-dashboard');
    }
}

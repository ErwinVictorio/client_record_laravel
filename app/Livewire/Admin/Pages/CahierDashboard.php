<?php

namespace App\Livewire\Admin\Pages;

use Livewire\Component;
use App\Models\clients;

class CahierDashboard extends Component
{

    public function render()
    {
        // query the  clients has Approve status
        $clienAproved = clients::whereIn('status', ['Sold','Approve'])->get();
        // count the the Pending Client
        $clientStatusCount = clients::selectRaw('status, COUNT(*) as total')
         ->whereIn('status',['Pending', 'Sold'])
         ->groupBy('status')
         ->pluck('total', 'status');

        return view('livewire.admin.pages.cahier-dashboard',[
             'cliets' => $clienAproved,
             'countedPending' => $clientStatusCount['Pending'] ?? 0,
             'counttedSoldClient' => $clientStatusCount['Sold'] ?? 0
        ]);
    }
}

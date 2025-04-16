<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\clients;


class Dashboard extends Component
{
    public function render()
    {
        $clientList = clients::select([
           'company_name',
            'id',
            'address',
           'contact_number',
            'email',
            'status'
        ])->get();

        $totalClient = clients::count();
        $totalPendingClient = clients::where('status', 'Pending')->count();
        $totalSold = clients::where('status', 'Sold')->count();
        $totalApproved = clients::where('status', 'Approve')->count();
        return view('livewire.admin.dashboard',[
            'clientList' => $clientList,
             'totalClient' => $totalClient,
              'totalPending' => $totalPendingClient,
               'totalSold' => $totalSold,
                'totalApproved' => $totalApproved
        ]);
    }
}

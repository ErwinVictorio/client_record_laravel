<?php

namespace App\Livewire\Modals;

use Livewire\Component;
use App\Models\User;
use App\Models\clients;

class ViewSalesmanInfo extends Component
{
    public $salesmanID;


    public function render()
    {
        $salesmanIfo = User::find($this->salesmanID);

        // GET THE TOTAL PENDING CLIENT
        $Pending = $salesmanIfo->Clients->where('status', 'Pending')->count();
        // GET THE TOTAL SOLD CLIENT
        $SoldClient = $salesmanIfo->Clients->where('status', 'Sold')->count();
        // GET THE TOTAL CLIENT
        $totalClient = $salesmanIfo->Clients->count();


        // Get the percentage
        $pendingPecent = 0;
        $soldPercent = 0;

        if ($totalClient > 0) {
            $pendingPecent = round(($Pending / $totalClient) * 100);
            $soldPercent =  round(($SoldClient / $totalClient) * 100);
        }


        return view('livewire.modals.view-salesman-info',[
            'salesmanID' => $this->salesmanID,
             'salesmanInfo' => $salesmanIfo,
             'Pending' => $Pending,
             'SoldClient' => $SoldClient,
             'totalClient' => $totalClient,
             'PendingPercent' => $pendingPecent,
             'soldPercent' => $soldPercent
        ]);
    }
}

<?php

namespace App\Livewire\Salesman;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\ClientRecordForMaintenanceAndRepair;
class RepairAndMaintence extends Component
{

 
    use WithPagination;
    
    public function render()
    {
       $records = ClientRecordForMaintenanceAndRepair::where('salesmanId', Auth::id())->paginate(10);

        return view('livewire.sales-man.repair-and-maintence',[
           'records' => $records
        ]);
    }
}
    
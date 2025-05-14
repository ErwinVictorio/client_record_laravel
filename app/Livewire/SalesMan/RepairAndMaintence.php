<?php

namespace App\Livewire\Salesman;

use Livewire\Component;
use App\Models\ClientRecordForMaintenanceAndRepair;
use Livewire\WithPagination;

class RepairAndMaintence extends Component
{

 
    use WithPagination;
    
    public function render()
    {
       $records = ClientRecordForMaintenanceAndRepair::paginate(10);
        return view('livewire.sales-man.repair-and-maintence',[
           'records' => $records
        ]);
    }
}
    
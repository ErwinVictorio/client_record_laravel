<?php

namespace App\Livewire\Admin\Pages;

use Livewire\Component;
use App\Models\ClientRecordForMaintenanceAndRepair;
use Livewire\WithPagination;

class AutoRepairAndMaintenance extends Component
{

    use WithPagination;

    public function render()
    {
        $records = ClientRecordForMaintenanceAndRepair::paginate(1);

        return view('livewire.admin.pages.auto-repair-and-maintenance',[
            'records' => $records
        ]);
    }
}

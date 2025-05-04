<?php

namespace App\Livewire\Admin\Pages;

use Livewire\Component;
use App\Models\CreateRecordForAutoRepair;
use Livewire\WithPagination;

class AutoRepairRecords extends Component
{
  
    use WithPagination;
    
    public function render()
    {
        $records = CreateRecordForAutoRepair::paginate(10);
        return view('livewire.admin.pages.auto-repair-records',[
            'records' => $records
        ]);
    }
}

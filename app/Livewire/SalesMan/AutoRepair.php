<?php

namespace App\Livewire\SalesMan;

use Livewire\Component;
use App\Models\CreateRecordForAutoRepair;
use Livewire\WithPagination;

class AutoRepair extends Component
{

    public function render()
    {
      $records = CreateRecordForAutoRepair::paginate();

        return view('livewire.sales-man.auto-repair',[
          'records' => $records
        ]);
    }
}

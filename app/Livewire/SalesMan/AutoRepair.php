<?php

namespace App\Livewire\SalesMan;

use Livewire\Component;
use App\Models\CreateRecordForAutoRepair;
use Livewire\WithPagination;

class AutoRepair extends Component
{
  use WithPagination;

    public function render()
    {
      $records = CreateRecordForAutoRepair::paginate(10);

        return view('livewire.sales-man.auto-repair',[
          'records' => $records
        ]);
    }
}

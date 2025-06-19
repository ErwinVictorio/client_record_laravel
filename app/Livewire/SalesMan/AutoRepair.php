<?php

namespace App\Livewire\SalesMan;

use Livewire\Component;
use App\Models\CreateRecordForAutoRepair;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;


class AutoRepair extends Component
{
  use WithPagination;

    public function render()
    {
      $records = CreateRecordForAutoRepair::where('salesmanId', Auth::id())
      ->orderBy('created_at', 'desc')
      ->paginate(20);

        return view('livewire.sales-man.auto-repair',[
          'records' => $records
        ]);
    }
}

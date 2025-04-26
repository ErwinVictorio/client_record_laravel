<?php

namespace App\Livewire\SalesMan;

use Livewire\Component;
use App\Models\CreateRecordForAutoRepair;

class AutoRepair extends Component
{
    public $records;

    public function mount(){
      $this->records = CreateRecordForAutoRepair::select(
        'company_name',
        'contact_number',
         'email',
         'address',
         'bank_account_number',
         'contact_person',
         'contact_number_person',
         'stock_out_number',
         'id'
      )->get();
    }

    public function render()
    {
        return view('livewire.sales-man.auto-repair');
    }
}

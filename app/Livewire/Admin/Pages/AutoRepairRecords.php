<?php

namespace App\Livewire\Admin\Pages;

use Livewire\Component;
use App\Models\CreateRecordForAutoRepair;

class AutoRepairRecords extends Component
{
    public $records;

    public function mount(){
        $this->records = CreateRecordForAutoRepair::select(
            'company_name',
             'stock_out_number',
             'contact_number',
             'contact_person',
              'contact_number_person',
              'email',
              'bank_account_number',
              'address'
        )->get();;
    }
    public function render()
    {
        return view('livewire.admin.pages.auto-repair-records');
    }
}

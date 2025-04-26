<?php

namespace App\Livewire\Salesman;

use Livewire\Component;
use App\Models\ClientRecordForMaintenanceAndRepair;

class RepairAndMaintence extends Component
{

    public $records;

    public function mount(){

        $this->records = ClientRecordForMaintenanceAndRepair::select(
            'company_name',
            'contact_number',
             'email',
             'address',
             'bank_account_number',
             'contact_person',
             'contact_number_person',
             'job_order_number',
             'id'
        )->get();
    }

    public function render()
    {
        return view('livewire.sales-man.repair-and-maintence');
    }
}
    
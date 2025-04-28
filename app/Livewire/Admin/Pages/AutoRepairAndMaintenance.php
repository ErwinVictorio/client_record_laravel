<?php

namespace App\Livewire\Admin\Pages;

use Livewire\Component;
use App\Models\ClientRecordForMaintenanceAndRepair;
class AutoRepairAndMaintenance extends Component
{
    public $records;

    public function mount(){
        $this->records = ClientRecordForMaintenanceAndRepair::select(
            'company_name',
             'job_order_number',
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
        return view('livewire.admin.pages.auto-repair-and-maintenance');
    }
}

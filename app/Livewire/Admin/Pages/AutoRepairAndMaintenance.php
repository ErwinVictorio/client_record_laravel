<?php

namespace App\Livewire\Admin\Pages;

use Livewire\Component;
use App\Models\ClientRecordForMaintenanceAndRepair;
use Livewire\WithPagination;

class AutoRepairAndMaintenance extends Component
{

    use WithPagination;
    public $searchQuery = '', $clientSearch = '';


    public function ApplySearch(){
        $this->searchQuery = $this->clientSearch;
        $this->resetPage();

    }

    public function render()
    {
        $search = '%' . $this->searchQuery . '%';


        $records = ClientRecordForMaintenanceAndRepair::where(function($query) use ($search){
          $query->where('company_name', 'like',$search);
          $query->orwhere('email', 'like',$search);
          $query->orwhere('job_order_number','like',$search);
        })->paginate(10);

        return view('livewire.admin.pages.auto-repair-and-maintenance',[
            'records' => $records
        ]);
    }
}

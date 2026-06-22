<?php

namespace App\Livewire\SuperAdmin\Page;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Models\ClientRecordForMaintenanceAndRepair;

class AutoRepareAndMaintenanceRecords extends Component
{

   use WithPagination;
    public $searchQuery = '', $clientSearch = '';


    public function ApplySearch(){
        $this->searchQuery = $this->clientSearch;
        $this->resetPage();
    }

    #[On('maintenance-records-updated')]
    public function refreshRecords()
    {
        $this->resetPage();
    }

    public function render()
    {
        $search = '%' . $this->searchQuery . '%';
        $records = ClientRecordForMaintenanceAndRepair::where(function($query) use ($search){
          $query->where('company_name', 'like',$search);
          $query->orwhere('email', 'like',$search);
          $query->orwhere('job_order_number','like',$search);
          $query->orwhere('serial_number','like',$search);
        })->paginate(20);

        return view('livewire.super-admin.page.auto-repare-and-maintenance-records',[
            'records' => $records
        ]);
    }

}

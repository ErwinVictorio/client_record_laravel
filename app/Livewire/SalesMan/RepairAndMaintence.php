<?php

namespace App\Livewire\Salesman;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\ClientRecordForMaintenanceAndRepair;
use Livewire\Attributes\On;
class RepairAndMaintence extends Component
{

 
    use WithPagination;

    public $jobOrderSearch = '';

    public function ApplySearch()
    {
       $this->resetPage();
    }

    #[On('maintenance-records-updated')]
    public function refreshRecords()
    {
       $this->resetPage();
    }
    
    public function render()
    {
       $search = '%' . $this->jobOrderSearch . '%';

       $records = ClientRecordForMaintenanceAndRepair::where('salesmanId', Auth::id())
       ->when($this->jobOrderSearch, function ($query) use ($search) {
          $query->where('job_order_number', 'like', $search);
       })
       ->orderBy('created_at','desc')
       ->paginate(10);

        return view('livewire.sales-man.repair-and-maintence',[
           'records' => $records
        ]);
    }
}
    

<?php

namespace App\Livewire\Admin\Pages;

use Livewire\Component;
use App\Models\CreateRecordForAutoRepair;
use Livewire\Attributes\On;
use Livewire\WithPagination;

class AutoRepairRecords extends Component
{
  
    use WithPagination;

      public $searchQuery = '', $clientSearch = '';

    public function ApplySearch(){
        $this->searchQuery = $this->clientSearch;
        $this->resetPage();
    }

    #[On('auto-repair-records-updated')]
    public function refreshRecords()
    {
        $this->resetPage();
    }

    public function render()
    {
             $search = '%' . $this->searchQuery . '%';

        $records = CreateRecordForAutoRepair::where(function($query) use ($search){
          $query->where('company_name', 'like',$search);
          $query->orwhere('email', 'like',$search);
          $query->orwhere('stock_out_number','like',$search);
        })->paginate(20);

        return view('livewire.admin.pages.auto-repair-records',[
            'records' => $records
        ]);
    }
}

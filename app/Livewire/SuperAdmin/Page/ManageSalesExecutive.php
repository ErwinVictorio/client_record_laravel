<?php

namespace App\Livewire\SuperAdmin\Page;

use Livewire\Component;
use App\Models\User;
use Livewire\Attributes\On;
use Livewire\WithPagination;

class ManageSalesExecutive extends Component
{

     public $searchQuery = '',$salesManSearch = '';

    use WithPagination;

    public function ApplySearch(){

        $this->searchQuery = $this->salesManSearch;
        $this->resetPage();
    }   

    #[On('salesmen-updated')]
    public function refreshSalesmen()
    {
        $this->resetPage();
    }


    public function render()
    {
        $search = '%' . $this->searchQuery . '%';

        $salesman = User::where('role','3')->where(function($query) use ($search){
          $query->where('first_name','like',$search);
          $query->orwhere('department','like',$search);
          $query->orwhere('last_name','like',$search);
        })->paginate(20);
                                      

        return view('livewire.super-admin.page.manage-sales-executive',[
            'salesman' => $salesman
        ]);
    }
}

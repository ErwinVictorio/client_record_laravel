<?php

namespace App\Livewire\Admin\Pages;

use Livewire\Component;
use App\Models\User;
use Livewire\WithPagination;

class ManageSalesMan extends Component

{

    public $searchQuery = '',$salesManSearch = '';

    use WithPagination;

    public function ApplySearch(){

        $this->searchQuery = $this->salesManSearch;
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
                                      

        return view('livewire.admin.pages.manage-sales-man',[
            'salesman' => $salesman
        ]);
    }
}

<?php

namespace App\Livewire\Admin\Pages;

use Livewire\Component;
use App\Models\User;

class ManageSalesMan extends Component

{
    public $salesman;
   
    public function mount(){
    $this->salesman = User::where('role','3')->select(
        'id',
        'first_name',
         'last_name',
         'middle_name',
         'department',
          'NickName'
        )->get();
   }

 
    public function render()
    {
        return view('livewire.admin.pages.manage-sales-man');
    }
}

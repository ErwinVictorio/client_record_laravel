<?php

namespace App\Livewire\Admin\Pages;

use Livewire\Component;
use App\Models\User;

class ManageSalesMan extends Component
{

 
    public function render()
    {
        $salesman = User::where('role','3')->select(
            'first_name',
             'last_name',
             'middle_name',
             'username',
             'department',
            )->get();

        return view('livewire.admin.pages.manage-sales-man',[

            'salesman' => $salesman
        ]);
    }
}

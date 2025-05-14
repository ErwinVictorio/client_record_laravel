<?php

namespace App\Livewire\Admin\Pages;

use Livewire\Component;
use App\Models\User;
use Livewire\WithPagination;

class ManageSalesMan extends Component

{

    use WithPagination;

    public function render()
    {
        $salesman = User::where('role','3')->paginate(2);
        return view('livewire.admin.pages.manage-sales-man',[
            'salesman' => $salesman
        ]);
    }
}

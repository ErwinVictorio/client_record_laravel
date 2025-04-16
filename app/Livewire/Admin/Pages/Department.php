<?php

namespace App\Livewire\Admin\Pages;

use Livewire\Component;
use App\Models\Department as departments;

class Department extends Component
{

    public function render()
    {
        $departmentList = departments::select('department_name','id')->get();;

        return view('livewire.admin.pages.department',[

            'departmentList' => $departmentList
        ]);
    }
}

<?php

namespace App\Livewire\SuperAdmin\Page;
use App\Models\Department;
use Livewire\Component;

class ManageDepartment extends Component
{

   public $departmentList;

    public function mount()
    {
        $this->departmentList = Department::select('department_name', 'id')->get();
    }


    public function render()
    {
        return view('livewire.super-admin.page.manage-department');
    }
}

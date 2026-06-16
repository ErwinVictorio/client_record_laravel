<?php

namespace App\Livewire\SuperAdmin\Page;
use App\Models\Department;
use Livewire\Component;
use Livewire\Attributes\On;

class ManageDepartment extends Component
{

   public $departmentList;

    public function mount()
    {
        $this->loadDepartments();
    }

    #[On('departments-updated')]
    public function loadDepartments()
    {
        $this->departmentList = Department::select('department_name', 'id')->get();
    }


    public function render()
    {
        return view('livewire.super-admin.page.manage-department');
    }
}

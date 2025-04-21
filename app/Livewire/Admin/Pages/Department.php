<?php

namespace App\Livewire\Admin\Pages;

use Livewire\Component;
use App\Models\Department as departments;

class Department extends Component
{
    public $departmentList;

    public function mount()
    {
        $this->departmentList = departments::select('department_name', 'id')->get();
    }

    
    public function render()
    {
        return view('livewire.admin.pages.department');
    }
}

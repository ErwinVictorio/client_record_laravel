<?php

namespace App\Livewire\Modals;

use Livewire\Attributes\Validate;
use Livewire\Component;
use App\Models\Department;

class CreateDepartment extends Component
{
    #[Validate('required')] public $department_name;

    public function Create_Department(){
       $validated = $this->validate();

        Department::create($validated);
        session()->flash('success','New Department is Successfully Created');
    }
    public function render()
    {
        return view('livewire.modals.create-department');
    }
}

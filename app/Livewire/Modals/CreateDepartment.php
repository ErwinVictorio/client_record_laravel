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

    //  check if the department is already exist 
       $departmentExist = Department::select('department_name')->where('department_name',$this->department_name)->first();

       if($departmentExist){
         session()->flash('error',"$this->department_name is alredy exist");
         return;
       }
       

        Department::create($validated);
        session()->flash('success','New Department is Successfully Created');
        $this->dispatch('departments-updated');
        
        $this->reset('department_name');
    }
    public function render()
    {
        return view('livewire.modals.create-department');
    }
}

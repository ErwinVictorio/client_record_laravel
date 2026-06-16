<?php

namespace App\Livewire\Modals;

use Livewire\Attributes\Validate;
use Livewire\Component;
use App\Models\Department;

class EditDepartment extends Component
{
    public $department_id;

    #[Validate('required')]
    public $department_name;

    public function mount($department_id){

       $this->department_id = $department_id;
        //find Department By id
        $department = Department::findOrFail($department_id);
       $this->department_name = $department->department_name;
    }


    public function Update_Department(){
        $this->validate();

        Department::where('id', $this->department_id)
        ->update(['department_name' => $this->department_name]);

        session()->flash('success',"Department is Successfully Updated");
        $this->dispatch('departments-updated');
    }

    public function render()
    {
        return view('livewire.modals.edit-department');
    }
}

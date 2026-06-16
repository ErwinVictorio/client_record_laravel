<?php

namespace App\Livewire\Modals;

use Livewire\Component;
use App\Models\Department;

class DeleteDepartment extends Component
{
    public $department_id,$department_name;

    public function mount($department_id){

        $this->department_id = $department_id;
        //find Department
        $department_name = Department::findOrFail($department_id)->department_name;

        $this->department_name = $department_name;
    }

    public function delete_department(){

        Department::where('id', $this->department_id)->delete();
        session()->flash('success','Successfully Deleted');
        $this->dispatch('departments-updated');
    }
    public function render()
    {
        return view('livewire.modals.delete-department');
    }
}

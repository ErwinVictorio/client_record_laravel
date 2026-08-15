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

    public function delete_department(): void
    {
        $department = Department::find($this->department_id);

        if (! $department) {
            session()->flash('error', 'Department not found. It may have already been deleted.');
            return;
        }

        $department->delete();
        $this->dispatch('hide-delete-department-modal-'.$this->department_id);
    }
    public function render()
    {
        return view('livewire.modals.delete-department');
    }
}

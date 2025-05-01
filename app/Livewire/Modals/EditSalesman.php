<?php

namespace App\Livewire\Modals;

use Livewire\Component;
use App\Models\Department;
use App\Models\User;
use Livewire\Attributes\Validate;

class EditSalesman extends Component
{
    public $salesmanID;
    #[Validate('required')]
    public $first_name, $last_name, $middle_name, $username, $department,$departments;

   
    // Render the data into modal blade
    public function mount($salesmanID){ // past the data into modal

        $this->salesmanID = $salesmanID;
        // find the user
        $user = User::findOrFail($salesmanID);

        $this->first_name = $user->first_name;
        $this->last_name = $user->last_name;
        $this->middle_name = $user->middle_name;
        $this->username = $user->username;
        $this->department = $user->department;

        // Get all the departments
        $this->departments = Department::all();
    }


    public function UpdateSalesman(){
       $this->validate();

       User::where('id', $this->salesmanID)->update([
         'first_name' => $this->first_name,
         'last_name' => $this->last_name,
         'middle_name' => $this->middle_name,
         'username' => $this->username,
          'department' => $this->department
       ]);

       session()->flash('success','Salesman updated successfully.');
    }

    
    public function render()
    {
        return view('livewire.modals.edit-salesman');
    }
}

<?php

namespace App\Livewire\Modals;

use Livewire\Component;
use Livewire\Attributes\Validate;
use App\Models\User;
use App\Models\Department;

class CreateSalesman extends Component
{
    #[Validate('required|min:8|confirmed')] public $password;
    #[Validate('required|min:4')] public $username;
  
    public $departments;

    #[Validate('required')]
    public $first_name,$last_name,$department,$password_confirmation,$middle_name,$role = 3,$NickName;


  public function mount()
    {
        $this->departments = Department::select('department_name')->get();
    }

    public function create_salesman(){
        
       $validated = $this->validate();
        User::create($validated);
        
        session()->flash('success','Successfully Created New Salesman');
        $this->dispatch('salesmen-updated');
        $this->reset([
            'first_name',
            'last_name',
            'middle_name',
            'NickName',
            'username',
            'department',
            'password',
            'password_confirmation',
        ]);
        $this->role = 3;
    }

    public function render()
    {
        return view('livewire.modals.create-salesman');
    }
}

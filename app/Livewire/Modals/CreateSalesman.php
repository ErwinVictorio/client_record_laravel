<?php

namespace App\Livewire\Modals;

use Livewire\Component;
use Livewire\Attributes\Validate;
use App\Models\User;

class CreateSalesman extends Component
{

    
    #[Validate('required|min:8|confirmed')]
    public $password;

    #[Validate('required|min:4')]
    public $username;

    #[Validate('required')]
    public $first_name,$last_name,$department,$password_confirmation,$middle_name,$role = 3;


    public function create_salesman(){
        
       $validated = $this->validate();

        User::create($validated);
        
        session()->flash('success','Successfully Created New Salesman');
    }

    public function render()
    {
        return view('livewire.modals.create-salesman');
    }
}

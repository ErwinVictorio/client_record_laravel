<?php

namespace App\Livewire\Modals;

use App\Models\Department;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;

class CreateAccount extends Component
{
    public $first_name, $last_name, $middle_name, $NickName, $username, $role, $department, $password;

    protected $rules = [
        'first_name' => 'required|min:2',
        'last_name'  => 'required|min:2',
        'username'   => 'required|unique:users,username',
        'role'       => 'required',
        'department' => 'required',
        'password'   => 'required|min:6',
        'middle_name' => 'required'
    ];



    public function save()
    {
        $this->validate();

        User::create([
            'first_name'  => $this->first_name,
            'last_name'   => $this->last_name,
            'middle_name' => $this->middle_name,
            'NickName'    => $this->NickName,
            'username'    => $this->username,
            'role'        => $this->role,
            'department'  => $this->department,
            'password'    => Hash::make($this->password),
        ]);

        session()->flash('message', 'New account created successfully!');
    }

    public function render()
    {
        return view('livewire.modals.create-account', [
            'departmentList' =>  Department::select('department_name')->get()
        ]);
    }
}

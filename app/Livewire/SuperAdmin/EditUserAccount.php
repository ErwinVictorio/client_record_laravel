<?php

namespace App\Livewire\SuperAdmin;

use App\Models\User;
use App\Models\Department; // Import Department model
use Livewire\Component;
use Illuminate\Support\Facades\Hash; // Import Hash para sa password
use Illuminate\Validation\Rule; // Import Rule para sa unique validation

class EditUserAccount extends Component
{
    public $userId, $first_name, $middle_name, $last_name, $NickName, $department, $username, $role, $password;

    public function mount($id)
    {
        $user = User::findOrFail($id);

        $this->userId = $user->id;
        $this->first_name = $user->first_name;
        $this->middle_name = $user->middle_name;
        $this->last_name = $user->last_name;
        $this->NickName = $user->NickName;
        $this->department = $user->department;
        $this->username = $user->username;
        $this->role = $user->role;
        // Huwag i-mount ang password para manatiling blanko sa simula
    }

    public function update()
    {
        // 1. Validation
        $this->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'username'   => [
                'required', 
                'string', 
                'max:255', 
                Rule::unique('users', 'username')->ignore($this->userId)
            ],
            'department' => 'required',
            'role'       => 'required',
            'password'   => 'nullable|min:8', // Nullable para pwedeng hindi baguhin
        ]);

        try {
            $user = User::findOrFail($this->userId);

            // 2. Update Basic Information
            $user->update([
                'first_name'  => $this->first_name,
                'middle_name' => $this->middle_name,
                'last_name'   => $this->last_name,
                'NickName'    => $this->NickName,
                'department'  => $this->department,
                'username'    => $this->username,
                'role'        => $this->role,
            ]);

            // 3. Update Password kung may input
            if (!empty($this->password)) {
                $user->update([
                    'password' => Hash::make($this->password)
                ]);
            }

            // 4. Success Message and Redirect
            session()->flash('message', 'Account for ' . $this->first_name . ' has been updated successfully.');
            
            // Palitan ang 'superAdmin.accounts' sa tamang route name ng listahan mo
            return redirect()->route('accountSetting.view'); 

        } catch (\Exception $e) {
            session()->flash('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.super-admin.edit-user-account', [
            'departmentList' => Department::orderBy('department_name', 'asc')->get()
        ]);
    }
}
<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Logout extends Component
{

    // logic for the logout component
    public function logout(){
        Auth::logout();

        return redirect()->route('login.view');
    }

    public function render()
    {
        return view('livewire.auth.logout');
    }
}

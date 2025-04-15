<?php

namespace App\Livewire\SalesMan;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;


class SalesManPage extends Component
{

    public function changeStatus(){

    }

    public function render()
    {
        // Get the Authentecated user
       $currentUser = Auth::user();
        return view('livewire.sales-man.sales-man-page',[
            'clients' => $currentUser->Clients,// get all the client realted to current authentecated user
        ]);
    }
} 

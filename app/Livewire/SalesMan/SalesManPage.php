<?php

namespace App\Livewire\SalesMan;

use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;


class SalesManPage extends Component
{

    public $countedPending,$countedSold,$clients,$totalApprove;

    public function mount(){
          // Get the Authentecated user
         $currentUser = Auth::user();
         $this->countedPending = $currentUser->Clients->where('status', 'Pending')->count();
         $this->countedSold = $currentUser->Clients->where('status', 'Sold')->count();
         $this->totalApprove = $currentUser->Clients->where('status','Approve')->count();
         $this->clients = $currentUser->Clients;
        }

    public function render()
    {
        return view('livewire.sales-man.sales-man-page');
    }
} 

<?php

namespace App\Livewire\Modals;

use Livewire\Component;
use App\Models\User;


class DeleteSalesman extends Component
{
    public $salesmanID;

    public function onDeleteSalesman(){
       $salesman = User::findOrFail($this->salesmanID);
       $salesman->delete();
       session()->flash('success','salesman is successfully deleted!');
       $this->dispatch('salesmen-updated');
    }

    public function render()
    {
        $name = User::where('id', $this->salesmanID)->select('first_name','last_name')->first();

        return view('livewire.modals.delete-salesman',[
            'salesmanID' => $this->salesmanID,
             'name' => $name
        ]);
    }
}

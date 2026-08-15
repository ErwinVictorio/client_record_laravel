<?php

namespace App\Livewire\Modals;

use Livewire\Component;
use App\Models\User;


class DeleteSalesman extends Component
{
    public $salesmanID;

    public function onDeleteSalesman(): void
    {
        $salesman = User::find($this->salesmanID);

        if (! $salesman) {
            session()->flash('error', 'Salesman record not found. It may have already been deleted.');
            return;
        }

        $salesman->delete();
        $this->dispatch('hide-delete-salesman-modal-'.$this->salesmanID);
    }

    public function render()
    {
        $name = User::where('id', $this->salesmanID)->select('first_name', 'last_name')->first();

        return view('livewire.modals.delete-salesman',[
            'salesmanID' => $this->salesmanID,
             'name' => $name
        ]);
    }
}

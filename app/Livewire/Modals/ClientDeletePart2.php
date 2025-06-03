<?php

namespace App\Livewire\Modals;

use App\Models\clients;
use Livewire\Attributes\On;
use Livewire\Component;

class ClientDeletePart2 extends Component
{

     public $clientId, $company_name;

        #[On('open-delete-modal')]
        public function setClient($clientId)
        {
            $this->clientId = $clientId;
            $this->company_name = clients::findOrFail($clientId)->company_name;
        }


    public function destroyClient()
    {
        clients::where('id', $this->clientId)->delete();
        session()->flash('success','Client successfully deleted!');
        $this->dispatch('refresh-page'); // You can listen to this from parent to refresh client list
    }

    public function render()
    {
        return view('livewire.modals.client-delete-part2');
    }
}

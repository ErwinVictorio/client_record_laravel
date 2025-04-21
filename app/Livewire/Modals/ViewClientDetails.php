<?php

namespace App\Livewire\Modals;

use Livewire\Component;
use App\Models\clients;

class ViewClientDetails extends Component
{

    public $clientId;

    public function render()
    {
        $findClient = clients::select(
            [
                'contact_person',
                 'contact_number_person',
                  'bank_account_number',
                   'item_name',
                   'specification',
                    'quantity',
                    'model_number',
                    'address'
            ])
         ->where('id', $this->clientId)->get();;
        
  

        return view('livewire.modals.view-client-details',[
           'findClient' => $findClient
        ]);
    }
}

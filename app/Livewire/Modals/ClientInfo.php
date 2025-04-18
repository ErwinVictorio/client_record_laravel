<?php

namespace App\Livewire\Modals;

use Livewire\Component;
use Livewire\Attributes\Validate;
use App\Models\clients;

class ClientInfo extends Component
{

    #[Validate('required')]
    public $itemName,$modelNumber,$Specification,$Quantity;

    public $clientId,$status ='Sold';

    public function soldForm(){
       $this->validate();
        
       $client = clients::find($this->clientId);

       if ($client) {
          $client->update([
           'item_name' => $this->itemName,
            'model_number' => $this->modelNumber,
            'quantity' => $this->Quantity,
             'specification' => $this->Specification,
             'status' => $this->status               
         ]);

         $client->save();

         session()->flash('success','Item successfully marked as sold! All details have been saved!');
       }
    
    }

    public function render()
    {
        return view('livewire.modals.client-info',[

        ]);
    }
}

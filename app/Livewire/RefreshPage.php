<?php

namespace App\Livewire;

use Livewire\Component;

class RefreshPage extends Component
{
    public $color = 'text-primary';

    public function refreshPage($color = 'text-primary'){
         $this->color = $color;
        return redirect(request()->header('Referer'));
    }

    public function render()
    {
        return view('livewire.refresh-page');
    }
}

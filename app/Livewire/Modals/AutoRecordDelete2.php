<?php

namespace App\Livewire\Modals;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\CreateRecordForAutoRepair;

class AutoRecordDelete2 extends Component
{

    public $company_name,$clientId;

    #[On('open-delete-modal')]
    public function setClient($clientId): void
    {
        $record = CreateRecordForAutoRepair::find($clientId);

        session()->forget(['success', 'error']);
        $this->clientId = $record?->id;
        $this->company_name = $record?->company_name;

        if (! $record) {
            session()->flash('error', 'The selected auto repair record could not be found.');
        }
    }

    // to delete auto records
    public function destroyClient(): void
    {
        $record = CreateRecordForAutoRepair::find($this->clientId);

        if (! $record) {
            session()->flash('error', 'Auto repair record not found. It may have already been deleted.');
            return;
        }

        $record->delete();
        $this->dispatch('hide-auto-record-delete-modal');
    }



    public function render()
    {
        return view('livewire.modals.auto-record-delete2');
    }
}

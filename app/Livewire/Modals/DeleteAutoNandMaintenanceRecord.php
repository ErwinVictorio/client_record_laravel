<?php

namespace App\Livewire\Modals;

use Livewire\Component;
use App\Models\ClientRecordForMaintenanceAndRepair;
use Livewire\Attributes\On;
class DeleteAutoNandMaintenanceRecord extends Component
{

    public $company_name,$clientId;

    #[On('open-delete-modal')]
    public function SetClient($clientId): void
    {
        $record = ClientRecordForMaintenanceAndRepair::find($clientId);

        session()->forget(['success', 'error']);
        $this->clientId = $record?->id;
        $this->company_name = $record?->company_name;

        if (! $record) {
            session()->flash('error', 'The selected maintenance record could not be found.');
        }
    }

    public function destroyClient(): void
    {
        $record = ClientRecordForMaintenanceAndRepair::find($this->clientId);

        if (! $record) {
            session()->flash('error', 'Maintenance record not found. It may have already been deleted.');
            return;
        }

        $record->delete();
        $this->dispatch('hide-auto-maintenance-delete-modal');
    }
    

    public function render()
    {
        return view('livewire.modals.delete-auto-nand-maintenance-record');
    }
}

<?php

namespace App\Livewire\Modals;

use App\Models\ClientRecordForMaintenanceAndRepair;
use Livewire\Attributes\Locked;
use Livewire\Component;

class EditRepairAndMaintence extends Component
{
    public $recordId;

    #[Locked]
    public bool $managesJobOrderNumber = true;

    public $company_name;

    public $address;

    public $email;

    public $contact_number;

    public $job_order_number;

    public $serial_number;

    public $date_sold;

    public $contact_person;

    public $contact_number_person;

    public $bank_account_number;

    protected function rules(): array
    {
        return [
            'company_name' => 'required|string',
            'address' => 'required',
            'email' => 'required|email',
            'contact_number' => 'required',
            'job_order_number' => $this->managesJobOrderNumber ? 'required' : 'nullable',
            'serial_number' => 'nullable|string',
            'date_sold' => 'nullable|date',
            'contact_person' => 'required',
            'contact_number_person' => 'required',
            'bank_account_number' => 'nullable',
        ];
    }

    public function mount($recordId, bool $managesJobOrderNumber = true)
    {

        $this->recordId = $recordId;
        $this->managesJobOrderNumber = $managesJobOrderNumber;
        $record = ClientRecordForMaintenanceAndRepair::findOrFail($recordId);

        $this->company_name = $record->company_name;
        $this->contact_person = $record->contact_person;
        $this->email = $record->email;
        $this->address = $record->address;
        $this->contact_number = $record->contact_number;
        $this->job_order_number = $record->job_order_number;
        $this->serial_number = $record->serial_number;
        $this->date_sold = $record->date_sold?->format('Y-m-d');
        $this->contact_number_person = $record->contact_number_person;
        $this->bank_account_number = $record->bank_account_number;
    }

    public function updateRecord()
    {
        $this->validate();

        // update the record
        $updatedValues = [
            'company_name' => $this->company_name,
            'address' => $this->address,
            'contact_number' => $this->contact_number,
            'email' => $this->email,
            'serial_number' => $this->serial_number,
            'date_sold' => $this->date_sold ?: null,
            'bank_account_number' => $this->bank_account_number,
            'contact_number_person' => $this->contact_number_person,
            'contact_person' => $this->contact_person,
        ];

        if ($this->managesJobOrderNumber) {
            $updatedValues['job_order_number'] = $this->job_order_number;
        }

        ClientRecordForMaintenanceAndRepair::where('id', $this->recordId)
            ->update($updatedValues);

        session()->flash('success', 'Record is Successfully Updated!');
        $this->dispatch('maintenance-records-updated');
    }

    public function render()
    {
        return view('livewire.modals.edit-repair-and-maintence');
    }
}

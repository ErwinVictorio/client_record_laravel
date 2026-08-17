<?php

namespace App\Livewire\Modals;

use App\Livewire\Concerns\ManagesVehicleSpecificationOptions;
use App\Models\ClientRecordForMaintenanceAndRepair;
use Livewire\Attributes\Locked;
use Livewire\Component;

class EditRepairAndMaintence extends Component
{
    use ManagesVehicleSpecificationOptions;

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

    public array $vehicles = [];

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
            'vehicles' => 'array',
            'vehicles.*.brand' => 'required|string',
            'vehicles.*.model' => 'required|string',
            'vehicles.*.engine' => 'nullable|string',
            'vehicles.*.engine_series' => 'nullable|string',
            'vehicles.*.serial_or_plate_number' => 'required|string',
            'vehicles.*.loading_capacity' => 'nullable|string',
            'vehicles.*.lifting_height' => 'nullable|string',
            'vehicles.*.mast_type' => 'nullable|string',
            'vehicles.*.power_type' => 'nullable|string',
            'vehicles.*.tire' => 'nullable|string',
            'vehicles.*.fork_length' => 'nullable|string',
            'vehicles.*.attachment' => 'nullable|string',
            ...$this->vehicleSpecificationOptionRules(),
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
        $this->vehicles = array_map(
            fn (array $vehicle) => $this->withVehicleSpecificationSelections($vehicle),
            is_array($record->vehicle_specifications) ? $record->vehicle_specifications : []
        );
    }

    public function addVehicle(): void
    {
        $this->vehicles[] = $this->withVehicleSpecificationSelections([
            'brand' => '',
            'model' => '',
            'engine' => '',
            'engine_series' => '',
            'serial_or_plate_number' => '',
            'loading_capacity' => '',
            'lifting_height' => '',
            'mast_type' => '',
            'power_type' => '',
            'tire' => '',
            'fork_length' => '',
            'attachment' => '',
        ]);
    }

    public function removeVehicle(int $index): void
    {
        unset($this->vehicles[$index]);
        $this->vehicles = array_values($this->vehicles);
    }

    public function updateRecord()
    {
        $this->validate();

        $vehiclesForStorage = $this->vehiclesForStorage($this->vehicles);

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
            'vehicle_specifications' => $vehiclesForStorage,
        ];

        if (count($vehiclesForStorage) > 0) {
            $updatedValues['serial_number'] = $vehiclesForStorage[0]['serial_or_plate_number'];
        }

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
        return view('livewire.modals.edit-repair-and-maintence', $this->vehicleSpecificationViewData());
    }
}

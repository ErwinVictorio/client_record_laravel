<?php

namespace App\Livewire\Modals;

use App\Models\ClientRecordForMaintenanceAndRepair;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Locked;
use Livewire\Component;

class CreateRepairAndMaintenaceRecord extends Component
{
    public $salesmanId;

    #[Locked]
    public bool $managesJobOrderNumber = true;

    public $company_name;

    public $address;

    public $email;

    public $contact_number;

    public $job_order_number;

    public $date_sold;

    public array $vehicles = [];

    public $contact_person;

    public $contact_number_person;

    public $bank_account_number;

    public function mount(bool $managesJobOrderNumber = true): void
    {
        $this->managesJobOrderNumber = $managesJobOrderNumber;
        $this->addVehicle();
    }

    public function addVehicle(): void
    {
        $this->vehicles[] = [
            'brand' => '',
            'model' => '',
            'serial_or_plate_number' => '',
            'loading_capacity' => '',
            'lifting_height' => '',
            'mast_type' => '',
            'power_type' => '',
            'tire' => '',
            'fork_length' => '',
            'attachment' => '',
        ];
    }

    public function removeVehicle(int $index): void
    {
        if (count($this->vehicles) <= 1) {
            return;
        }

        unset($this->vehicles[$index]);
        $this->vehicles = array_values($this->vehicles);
    }

    protected function rules(): array
    {
        return [
            'company_name' => 'required|string',
            'address' => 'required',
            'email' => 'required|email',
            'contact_number' => 'required|numeric',
            'job_order_number' => $this->managesJobOrderNumber ? 'required' : 'nullable',
            'date_sold' => 'nullable|date',
            'vehicles' => 'required|array|min:1',
            'vehicles.*.brand' => 'required|string',
            'vehicles.*.model' => 'required|string',
            'vehicles.*.serial_or_plate_number' => 'required|string',
            'vehicles.*.loading_capacity' => 'nullable|string',
            'vehicles.*.lifting_height' => 'nullable|string',
            'vehicles.*.mast_type' => 'nullable|string',
            'vehicles.*.power_type' => 'nullable|string',
            'vehicles.*.tire' => 'nullable|string',
            'vehicles.*.fork_length' => 'nullable|string',
            'vehicles.*.attachment' => 'nullable|string',
            'contact_person' => 'required',
            'contact_number_person' => 'required|numeric',
            'bank_account_number' => 'nullable',
        ];
    }

    public function create_client_for_maintenance(): void
    {
        $this->validate();

        $this->salesmanId = Auth::id();
        $firstVehicle = $this->vehicles[0];

        ClientRecordForMaintenanceAndRepair::create([
            'company_name' => $this->company_name,
            'address' => $this->address,
            'email' => $this->email,
            'contact_number' => $this->contact_number,
            'job_order_number' => $this->managesJobOrderNumber ? $this->job_order_number : null,
            'serial_number' => $firstVehicle['serial_or_plate_number'],
            'date_sold' => $this->date_sold ?: null,
            'vehicle_specifications' => $this->vehicles,
            'contact_person' => $this->contact_person,
            'contact_number_person' => $this->contact_number_person,
            'bank_account_number' => $this->bank_account_number,
            'salesmanId' => $this->salesmanId,
        ]);

        session()->flash('success', 'New Record is Successfully Created');
        $this->dispatch('maintenance-records-updated');
        $this->reset([
            'salesmanId',
            'company_name',
            'address',
            'email',
            'contact_number',
            'job_order_number',
            'date_sold',
            'vehicles',
            'contact_person',
            'contact_number_person',
            'bank_account_number',
        ]);
        $this->addVehicle();
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.modals.create-repair-and-maintenace-record');
    }
}

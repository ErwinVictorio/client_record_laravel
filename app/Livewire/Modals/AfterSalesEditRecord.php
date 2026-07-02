<?php

namespace App\Livewire\Modals;

use App\Livewire\AfterSales\Dashboard;
use App\Models\AfterSalesRecord;
use App\Models\ClientRecordForMaintenanceAndRepair;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Locked;
use Livewire\Component;

class AfterSalesEditRecord extends Component
{
    #[Locked]
    public $recordId = null;

    #[Locked]
    public $clientId = null;

    #[Locked]
    public $maintenanceRecordId = null;

    public $serviceType = '';

    public $changeType = '';

    public $warrantyType = '';

    public $pmsNumber = '';

    public $jobOrderNumber = '';

    public $jobOrderDate = '';

    public $description = '';

    public $remarks = '';

    public function mount(int $recordId): void
    {
        $record = AfterSalesRecord::findOrFail($recordId);

        $this->recordId = $record->id;
        $this->clientId = $record->client_id;
        $this->maintenanceRecordId = $record->maintenance_record_id
            ?: ($record->client_id ? null : ClientRecordForMaintenanceAndRepair::where('job_order_number', $record->job_order_number)->value('id'));
        $this->serviceType = $record->service_type;
        $this->changeType = $record->change_type ?? '';
        $this->warrantyType = $record->warranty_type ?? '';
        $this->pmsNumber = $record->pms_number ?? '';
        $this->jobOrderNumber = $record->job_order_number;
        $this->jobOrderDate = $record->job_order_date?->format('Y-m-d') ?? '';
        $this->description = $record->description ?? '';
        $this->remarks = $record->remarks ?? '';

        if ($this->clientId && $this->changeType === 'WITH CHANGE') {
            $this->warrantyType = 'OUT OF WARRANTY';
        }

    }

    public function updatedChangeType($value): void
    {
        if ($this->clientId && $value === 'WITH CHANGE') {
            $this->warrantyType = 'OUT OF WARRANTY';
            $this->resetValidation('warrantyType');
        }
    }

    public function updatedServiceType($value): void
    {
        if (! in_array($value, ['PMS', 'Other'], true)) {
            $this->pmsNumber = '';
            $this->resetValidation('pmsNumber');
        }
    }

    public function updateRecord(): void
    {
        $record = AfterSalesRecord::findOrFail($this->recordId);
        $isAsapRecord = (bool) $record->client_id;

        if ($isAsapRecord && $this->changeType === 'WITH CHANGE') {
            $this->warrantyType = 'OUT OF WARRANTY';
        }

        $rules = [
            'serviceType' => 'required|in:PMS,Other',
            'changeType' => 'required|in:WITH CHANGE,WITHOUT CHANGE',
            'warrantyType' => 'nullable|in:UNDER WARRANTY,OUT OF WARRANTY',
            'jobOrderNumber' => 'required|min:2',
            'jobOrderDate' => 'nullable|date',
            'description' => 'nullable|string',
            'remarks' => 'nullable|string',
        ];

        if ($this->serviceType === 'PMS') {
            $rules['pmsNumber'] = 'required|min:1';
        }

        $this->validate($rules, [
            'serviceType.required' => 'Please select a service type.',
            'changeType.required' => 'Please select a type.',
            'warrantyType.in' => 'Please select a valid warranty type.',
            'pmsNumber.required' => 'Please enter the Number of PMS.',
            'jobOrderNumber.required' => 'Please enter the JO Number.',
        ]);

        $values = [
            'service_type' => $this->serviceType,
            'change_type' => $this->changeType,
            'warranty_type' => $isAsapRecord ? ($this->warrantyType ?: null) : null,
            'pms_number' => in_array($this->serviceType, ['PMS', 'Other'], true)
                ? ($this->pmsNumber !== '' ? $this->pmsNumber : null)
                : null,
            'job_order_number' => trim($this->jobOrderNumber),
            'job_order_date' => $this->jobOrderDate ?: null,
            'description' => $this->description,
            'remarks' => $this->remarks,
        ];

        $updated = $isAsapRecord
            ? $this->updateAsapRecord($record, $values)
            : $this->updateMaintenanceRecord($record, $values);

        if (! $updated) {
            $this->addError('jobOrderNumber', 'This Repair & Maintenance record is no longer available for editing.');

            return;
        }

        $message = $isAsapRecord
            ? 'MSD record updated successfully.'
            : 'MSD record and Repair & Maintenance JO updated successfully.';

        $this->dispatch('msd-record-updated', message: $message)->to(Dashboard::class);
        $this->dispatch('hide-msd-edit-modal');
    }

    private function updateAsapRecord(AfterSalesRecord $record, array $values): bool
    {
        $record->update($values);

        return true;
    }

    private function updateMaintenanceRecord(AfterSalesRecord $record, array $values): bool
    {
        return DB::transaction(function () use ($record, $values): bool {
            $lockedRecord = AfterSalesRecord::lockForUpdate()->findOrFail($record->id);
            $maintenanceRecord = ClientRecordForMaintenanceAndRepair::whereKey($this->maintenanceRecordId)
                ->lockForUpdate()
                ->first();

            if (! $maintenanceRecord) {
                return false;
            }

            if (
                $lockedRecord->maintenance_record_id
                && (int) $lockedRecord->maintenance_record_id !== (int) $maintenanceRecord->id
            ) {
                return false;
            }

            if (filled($maintenanceRecord->job_order_number) && $maintenanceRecord->job_order_number !== $lockedRecord->job_order_number) {
                return false;
            }

            $maintenanceRecord->update(['job_order_number' => $values['job_order_number']]);
            $lockedRecord->update([...$values, 'maintenance_record_id' => $maintenanceRecord->id]);

            return true;
        });
    }

    public function render()
    {
        return view('livewire.modals.after-sales-edit-record', [
            'client' => $this->clientId ? \App\Models\clients::find($this->clientId) : null,
            'maintenanceRecord' => $this->maintenanceRecordId
                ? ClientRecordForMaintenanceAndRepair::find($this->maintenanceRecordId)
                : null,
        ]);
    }
}

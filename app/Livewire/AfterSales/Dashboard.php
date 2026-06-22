<?php

namespace App\Livewire\AfterSales;

use App\Models\AfterSalesRecord;
use App\Models\ClientRecordForMaintenanceAndRepair;
use App\Models\clients;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Dashboard extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $saleControlNo = '';
    public $jobOrderSearch = '';
    public $section = 'asap';
    public $selectedClientId = null;
    public $service_type = 'PMS';
    public $warranty_type = '';
    public $selectedMaintenanceRecordId = null;
    public $pms_number = '';
    public $job_order_number = '';
    public $job_order_date = '';
    public $description = '';
    public $remarks = '';
    public $noticeType = '';
    public $noticeMessage = '';

    public function setSection(string $section)
    {
        if (!in_array($section, ['asap', 'other'], true)) {
            return;
        }

        $this->section = $section;
        $this->service_type = $section === 'asap' ? 'PMS' : 'Other';
        $this->warranty_type = '';
        $this->clearNotice();
        $this->resetErrorBag();
        $this->resetPage();

        if ($section === 'other') {
            $this->selectedClientId = null;
            $this->selectedMaintenanceRecordId = null;
            $this->saleControlNo = '';
            $this->pms_number = '';
        }
    }

    public function updatedJobOrderNumber()
    {
        if ($this->section === 'other') {
            $this->selectedMaintenanceRecordId = null;
        }
    }

    public function searchSaleControl()
    {
        $this->clearNotice();
        $this->resetErrorBag();

        $this->validate([
            'saleControlNo' => 'required|string',
        ], [
            'saleControlNo.required' => 'Please enter a Sale Control No.',
        ]);

        $saleControlNo = trim($this->saleControlNo);
        $client = clients::where('salesList_no', $saleControlNo)->first();



        if (!$client) {
            $this->selectedClientId = null;
            $this->showNotice('danger', 'No client/unit found for this Sale Control No.');
            return;
        }

        if ($client->status !== 'Sold') {
            $this->selectedClientId = null;
            $this->showNotice('danger', 'Client/unit found, but status is "' . $client->status . '". PMS requires status Sold.');
            return;
        }

        $this->selectedClientId = $client->id;
        $this->showNotice('success', 'Sold unit found. You can now add MSD job information.');
    }

    public function searchMaintenanceJobOrder()
    {
        $this->clearNotice();
        $this->resetErrorBag();

        $this->validate([
            'job_order_number' => 'required|min:2',
        ], [
            'job_order_number.required' => 'Please enter the JO Number.',
        ]);

        $record = ClientRecordForMaintenanceAndRepair::where('job_order_number', trim($this->job_order_number))->first();

        if (!$record) {
            $this->selectedMaintenanceRecordId = null;
            $this->addError('job_order_number', 'JO Number not found in Repair & Maintenance records.');
            $this->showNotice('danger', 'No Repair & Maintenance record found for this JO Number.');
            return;
        }

        $this->selectedMaintenanceRecordId = $record->id;
        $this->showNotice('success', 'Repair & Maintenance JO found. You can now save this MSD record.');
    }

    public function save()
    {
        $this->clearNotice();
        $this->resetErrorBag();

        $this->service_type = $this->section === 'asap' ? 'PMS' : 'Other';

        $rules = [
            'service_type' => 'required|in:PMS,Other',
            'warranty_type' => 'nullable|in:UNDER WARRANTY,OUT OF WARRANTY',
            'job_order_number' => 'required|min:2',
            'job_order_date' => 'nullable|date',
            'description' => 'nullable|string',
            'remarks' => 'nullable|string',
        ];

        if ($this->service_type === 'PMS') {
            $rules['selectedClientId'] = 'required|exists:clients,id';
            $rules['pms_number'] = 'required|min:1';
        } else {
            $rules['selectedMaintenanceRecordId'] = 'required|exists:client_record_for_maintenance_and_repairs,id';
        }

        $this->validate($rules, [
            'selectedClientId.required' => 'Please search and select a sold unit before saving a PMS record.',
            'selectedClientId.exists' => 'The selected sold unit no longer exists.',
            'selectedMaintenanceRecordId.required' => 'Please search an existing Repair & Maintenance JO Number before saving.',
            'selectedMaintenanceRecordId.exists' => 'The selected Repair & Maintenance JO record no longer exists.',
            'pms_number.required' => 'Please enter the Number of PMS.',
            'warranty_type.in' => 'Please select a valid warranty type.',
            'job_order_number.required' => 'Please enter the JO Number.',
        ]);

        if ($this->section === 'other') {
            $maintenanceRecordExists = ClientRecordForMaintenanceAndRepair::whereKey($this->selectedMaintenanceRecordId)
                ->where('job_order_number', trim($this->job_order_number))
                ->exists();

            if (!$maintenanceRecordExists) {
                $this->selectedMaintenanceRecordId = null;
                $this->addError('job_order_number', 'Please search this JO Number again before saving.');
                $this->showNotice('danger', 'The searched JO Number does not match the current JO Number.');
                return;
            }
        }

        AfterSalesRecord::create([
            'client_id' => $this->section === 'asap' ? $this->selectedClientId : null,
            'user_id' => Auth::id(),
            'service_type' => $this->service_type,
            'warranty_type' => $this->section === 'asap' ? ($this->warranty_type ?: null) : null,
            'pms_number' => $this->service_type === 'PMS' ? $this->pms_number : null,
            'job_order_number' => $this->job_order_number,
            'job_order_date' => $this->job_order_date ?: null,
            'description' => $this->description,
            'remarks' => $this->remarks,
        ]);

        $this->reset(['pms_number', 'warranty_type', 'job_order_number', 'job_order_date', 'description', 'remarks', 'selectedMaintenanceRecordId']);
        $this->showNotice('success', 'MSD record saved successfully.');
    }

    public function deleteRecord(int $recordId)
    {
        $this->clearNotice();
        $this->resetErrorBag();

        AfterSalesRecord::findOrFail($recordId)->delete();

        $this->showNotice('success', 'MSD record deleted successfully.');
    }

    private function showNotice($type, $message)
    {
        $this->noticeType = $type;
        $this->noticeMessage = $message;
    }

    private function clearNotice()
    {
        $this->noticeType = '';
        $this->noticeMessage = '';
    }

    public function render()
    {
        $selectedClient = $this->selectedClientId
            ? clients::with('salesman')->find($this->selectedClientId)
            : null;

        $selectedMaintenanceRecord = $this->selectedMaintenanceRecordId
            ? ClientRecordForMaintenanceAndRepair::find($this->selectedMaintenanceRecordId)
            : null;

        $records = AfterSalesRecord::with(['client.salesman', 'user'])
            ->when($this->section === 'asap', function ($query) {
                $query->where('service_type', 'PMS');
            })
            ->when($this->section === 'other', function ($query) {
                $query->where('service_type', 'Other');
            })
            ->when($this->jobOrderSearch, function ($query) {
                $query->where('job_order_number', 'like', '%' . $this->jobOrderSearch . '%');
            })
            ->latest()
            ->paginate(10);

        $maintenanceRecordsByJobOrder = collect();

        if ($this->section === 'other') {
            $maintenanceRecordsByJobOrder = ClientRecordForMaintenanceAndRepair::whereIn(
                'job_order_number',
                $records->getCollection()->pluck('job_order_number')->filter()->unique()
            )->get()->keyBy('job_order_number');
        }

        return view('livewire.after-sales.dashboard', [
            'selectedClient' => $selectedClient,
            'selectedMaintenanceRecord' => $selectedMaintenanceRecord,
            'maintenanceRecordsByJobOrder' => $maintenanceRecordsByJobOrder,
            'records' => $records,
        ]);
    }
}

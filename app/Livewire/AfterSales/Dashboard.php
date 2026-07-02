<?php

namespace App\Livewire\AfterSales;

use App\Models\AfterSalesRecord;
use App\Models\ClientRecordForMaintenanceAndRepair;
use App\Models\clients;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
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

    public $service_type = '';

    public $change_type = '';

    public $warranty_type = '';

    public $selectedMaintenanceRecordId = null;

    public $maintenanceCompanySearch = '';

    public bool $maintenanceSearchPerformed = false;

    public $pms_number = '';

    public $job_order_number = '';

    public $job_order_date = '';

    public $description = '';

    public $remarks = '';

    public $noticeType = '';

    public $noticeMessage = '';

    public function setSection(string $section)
    {
        if (! in_array($section, ['asap', 'other'], true)) {
            return;
        }

        $this->section = $section;
        $this->service_type = '';
        $this->warranty_type = '';
        $this->clearNotice();
        $this->resetErrorBag();
        $this->resetPage('asapPage');
        $this->resetPage('otherPage');

        if ($section === 'other') {
            $this->selectedClientId = null;
            $this->selectedMaintenanceRecordId = null;
            $this->maintenanceCompanySearch = '';
            $this->maintenanceSearchPerformed = false;
            $this->saleControlNo = '';
            $this->pms_number = '';
        }
    }

    public function updatedMaintenanceCompanySearch()
    {
        if ($this->section === 'other') {
            $this->selectedMaintenanceRecordId = null;
            $this->maintenanceSearchPerformed = false;
        }
    }

    public function updatedJobOrderSearch()
    {
        $this->resetPage($this->section === 'asap' ? 'asapPage' : 'otherPage');
    }

    public function updatedChangeType($value)
    {
        if ($this->section === 'asap' && $value === 'WITH CHANGE') {
            $this->warranty_type = 'OUT OF WARRANTY';
            $this->resetValidation('warranty_type');
        }
    }

    public function updatedServiceType($value)
    {
        if (! in_array($value, ['PMS', 'Other'], true)) {
            $this->pms_number = '';
            $this->resetValidation('pms_number');
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

        if (! $client) {
            $this->selectedClientId = null;
            $this->showNotice('danger', 'No client/unit found for this Sale Control No.');

            return;
        }

        if ($client->status !== 'Sold') {
            $this->selectedClientId = null;
            $this->showNotice('danger', 'Client/unit found, but status is "'.$client->status.'". PMS requires status Sold.');

            return;
        }

        $this->selectedClientId = $client->id;
        $this->showNotice('success', 'Sold unit found. You can now add MSD job information.');
    }

    public function searchMaintenanceCompany()
    {
        $this->clearNotice();
        $this->resetErrorBag();
        $this->selectedMaintenanceRecordId = null;

        $this->validate([
            'maintenanceCompanySearch' => 'required|string|min:2',
        ], [
            'maintenanceCompanySearch.required' => 'Please enter a company name.',
            'maintenanceCompanySearch.min' => 'Please enter at least 2 characters.',
        ]);

        $this->maintenanceCompanySearch = trim($this->maintenanceCompanySearch);
        $this->maintenanceSearchPerformed = true;
    }

    public function selectMaintenanceRecord(int $recordId)
    {
        $this->clearNotice();
        $this->resetErrorBag();

        $record = $this->pendingMaintenanceRecords()
            ->whereKey($recordId)
            ->first();

        if (! $record) {
            $this->selectedMaintenanceRecordId = null;
            $this->addError('selectedMaintenanceRecordId', 'This Repair & Maintenance record is no longer pending.');
            $this->showNotice('danger', 'This record already has a JO Number or no longer exists.');

            return;
        }

        $this->selectedMaintenanceRecordId = $record->id;
        $this->showNotice('success', 'Pending Repair & Maintenance record selected. Enter its JO Number to continue.');
    }

    public function save()
    {
        $this->clearNotice();
        $this->resetErrorBag();
        if ($this->section === 'asap' && $this->change_type === 'WITH CHANGE') {
            $this->warranty_type = 'OUT OF WARRANTY';
        }

        $rules = [
            'service_type' => 'required|in:PMS,Other',
            'change_type' => 'required|in:WITH CHANGE,WITHOUT CHANGE',
            'warranty_type' => 'nullable|in:UNDER WARRANTY,OUT OF WARRANTY',
            'job_order_number' => 'required|min:2',
            'job_order_date' => 'nullable|date',
            'description' => 'nullable|string',
            'remarks' => 'nullable|string',
        ];

        if ($this->section === 'asap') {
            $rules['selectedClientId'] = 'required|exists:clients,id';
        } else {
            $rules['selectedMaintenanceRecordId'] = 'required|exists:client_record_for_maintenance_and_repairs,id';
        }

        if ($this->service_type === 'PMS') {
            $rules['pms_number'] = 'required|min:1';
        }

        $this->validate($rules, [
            'selectedClientId.required' => 'Please search and select a sold unit before saving a PMS record.',
            'selectedClientId.exists' => 'The selected sold unit no longer exists.',
            'selectedMaintenanceRecordId.required' => 'Please search by company and select a pending Repair & Maintenance record before saving.',
            'selectedMaintenanceRecordId.exists' => 'The selected Repair & Maintenance JO record no longer exists.',
            'pms_number.required' => 'Please enter the Number of PMS.',
            'change_type.required' => 'Please select a type.',
            'change_type.in' => 'Please select a valid type.',
            'warranty_type.in' => 'Please select a valid warranty type.',
            'job_order_number.required' => 'Please enter the JO Number.',
        ]);

        $values = [
            'client_id' => $this->section === 'asap' ? $this->selectedClientId : null,
            'service_type' => $this->service_type,
            'change_type' => $this->change_type,
            'warranty_type' => $this->section === 'asap' ? ($this->warranty_type ?: null) : null,
            'pms_number' => in_array($this->service_type, ['PMS', 'Other'], true)
                ? ($this->pms_number !== '' ? $this->pms_number : null)
                : null,
            'job_order_number' => trim($this->job_order_number),
            'job_order_date' => $this->job_order_date ?: null,
            'description' => $this->description,
            'remarks' => $this->remarks,
        ];

        if ($this->section === 'other') {
            $message = $this->saveOtherRecord($values);

            if (! $message) {
                return;
            }
        } else {
            AfterSalesRecord::create([
                ...$values,
                'user_id' => Auth::id(),
            ]);
            $message = 'MSD record saved successfully.';
        }

        $this->resetForm();
        $this->showNotice('success', $message);

    }

    private function saveOtherRecord(array $values): ?string
    {
        $saved = DB::transaction(function () use ($values): bool {
            $maintenanceRecordQuery = ClientRecordForMaintenanceAndRepair::whereKey($this->selectedMaintenanceRecordId)
                ->where(function ($query) {
                    $query->whereNull('job_order_number')
                        ->orWhere('job_order_number', '');
                })
                ->lockForUpdate();

            $maintenanceRecord = $maintenanceRecordQuery->first();

            if (! $maintenanceRecord) {
                return false;
            }

            $maintenanceRecord->update([
                'job_order_number' => $values['job_order_number'],
            ]);

            $values['maintenance_record_id'] = $maintenanceRecord->id;

            AfterSalesRecord::create([
                ...$values,
                'user_id' => Auth::id(),
            ]);

            return true;
        });

        if (! $saved) {
            $this->selectedMaintenanceRecordId = null;
            $this->addError('selectedMaintenanceRecordId', 'This Repair & Maintenance record is no longer available for JO assignment.');
            $this->showNotice('danger', 'The selected record changed or already has a JO Number. Search by company again.');

            return null;
        }

        return 'MSD record saved and JO Number assigned successfully.';
    }

    #[On('msd-record-updated')]
    public function recordUpdated(string $message): void
    {
        $this->showNotice('success', $message);
    }

    private function resetForm()
    {
        $this->reset([
            'change_type',
            'service_type',
            'warranty_type',
            'pms_number',
            'job_order_number',
            'job_order_date',
            'description',
            'remarks',
            'selectedMaintenanceRecordId',
            'maintenanceCompanySearch',
            'maintenanceSearchPerformed',
        ]);
    }

    private function pendingMaintenanceRecords()
    {
        return ClientRecordForMaintenanceAndRepair::query()
            ->where(function ($query) {
                $query->whereNull('job_order_number')
                    ->orWhere('job_order_number', '');
            });
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

        $maintenanceSearchResults = collect();

        if (
            $this->section === 'other'
            && $this->maintenanceSearchPerformed
            && filled($this->maintenanceCompanySearch)
        ) {
            $maintenanceSearchResults = $this->pendingMaintenanceRecords()
                ->where('company_name', 'like', '%'.trim($this->maintenanceCompanySearch).'%')
                ->latest()
                ->limit(10)
                ->get();
        }

        $records = AfterSalesRecord::with(['client.salesman', 'user', 'maintenanceRecord'])
            ->when($this->section === 'asap', function ($query) {
                $query->whereNotNull('client_id');
            })
            ->when($this->section === 'other', function ($query) {
                $query->whereNull('client_id');
            })
            ->when($this->jobOrderSearch, function ($query) {
                $search = trim($this->jobOrderSearch);

                $query->where(function ($searchQuery) use ($search) {
                    $searchQuery->where('job_order_number', 'like', '%'.$search.'%');

                    if ($this->section === 'asap') {
                        $searchQuery->orWhereHas('client', function ($clientQuery) use ($search) {
                            $clientQuery->where('company_name', 'like', '%'.$search.'%');
                        });
                    } else {
                        $searchQuery->orWhereHas('maintenanceRecord', function ($maintenanceQuery) use ($search) {
                            $maintenanceQuery->where('company_name', 'like', '%'.$search.'%');
                        })->orWhereIn(
                            'job_order_number',
                            ClientRecordForMaintenanceAndRepair::query()
                                ->select('job_order_number')
                                ->where('company_name', 'like', '%'.$search.'%')
                        );
                    }
                });
            })
            ->latest()
            ->paginate(10, ['*'], $this->section === 'asap' ? 'asapPage' : 'otherPage');

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
            'maintenanceSearchResults' => $maintenanceSearchResults,
            'maintenanceRecordsByJobOrder' => $maintenanceRecordsByJobOrder,
            'records' => $records,
        ]);
    }
}

<?php

namespace App\Livewire\AfterSales;

use App\Models\AfterSalesRecord;
use App\Models\ClientRecordForMaintenanceAndRepair;
use App\Models\clients;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

    public $editingRecordId = null;

    public $noticeType = '';

    public $noticeMessage = '';

    public function setSection(string $section)
    {
        if (! in_array($section, ['asap', 'other'], true)) {
            return;
        }

        $this->cancelEdit();
        $this->section = $section;
        $this->service_type = $section === 'asap' ? 'PMS' : 'Other';
        $this->warranty_type = '';
        $this->clearNotice();
        $this->resetErrorBag();
        $this->resetPage();

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
        if ($this->section === 'other' && ! $this->editingRecordId) {
            $this->selectedMaintenanceRecordId = null;
            $this->maintenanceSearchPerformed = false;
        }
    }

    public function updatedJobOrderSearch()
    {
        $this->resetPage();
    }

    public function updatedChangeType($value)
    {
        if ($this->section === 'asap' && $value === 'WITH CHANGE') {
            $this->warranty_type = 'OUT OF WARRANTY';
            $this->resetValidation('warranty_type');
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

        $this->service_type = $this->section === 'asap' ? 'PMS' : 'Other';

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

        if ($this->service_type === 'PMS') {
            $rules['selectedClientId'] = 'required|exists:clients,id';
            $rules['pms_number'] = 'required|min:1';
        } else {
            $rules['selectedMaintenanceRecordId'] = 'required|exists:client_record_for_maintenance_and_repairs,id';
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
            'pms_number' => $this->service_type === 'PMS' ? $this->pms_number : null,
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
        } elseif ($this->editingRecordId) {
            AfterSalesRecord::findOrFail($this->editingRecordId)->update($values);
            $message = 'MSD record updated successfully.';
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
            $afterSalesRecord = $this->editingRecordId
                ? AfterSalesRecord::lockForUpdate()->findOrFail($this->editingRecordId)
                : null;

            if ($afterSalesRecord && $afterSalesRecord->service_type !== 'Other') {
                return false;
            }

            if (
                $afterSalesRecord?->maintenance_record_id
                && (int) $afterSalesRecord->maintenance_record_id !== (int) $this->selectedMaintenanceRecordId
            ) {
                return false;
            }

            $maintenanceRecordQuery = ClientRecordForMaintenanceAndRepair::whereKey($this->selectedMaintenanceRecordId)
                ->lockForUpdate();

            if (! $afterSalesRecord) {
                $maintenanceRecordQuery->where(function ($query) {
                    $query->whereNull('job_order_number')
                        ->orWhere('job_order_number', '');
                });
            }

            $maintenanceRecord = $maintenanceRecordQuery->first();

            if (! $maintenanceRecord) {
                return false;
            }

            if (
                $afterSalesRecord
                && filled($maintenanceRecord->job_order_number)
                && $maintenanceRecord->job_order_number !== $afterSalesRecord->job_order_number
            ) {
                return false;
            }

            $maintenanceRecord->update([
                'job_order_number' => $values['job_order_number'],
            ]);

            $values['maintenance_record_id'] = $maintenanceRecord->id;

            if ($afterSalesRecord) {
                $afterSalesRecord->update($values);
            } else {
                AfterSalesRecord::create([
                    ...$values,
                    'user_id' => Auth::id(),
                ]);
            }

            return true;
        });

        if (! $saved) {
            $this->selectedMaintenanceRecordId = null;
            $this->addError('selectedMaintenanceRecordId', 'This Repair & Maintenance record is no longer available for JO assignment.');
            $this->showNotice('danger', 'The selected record changed or already has a JO Number. Search by company again.');

            return null;
        }

        return $this->editingRecordId
            ? 'MSD record and Repair & Maintenance JO updated successfully.'
            : 'MSD record saved and JO Number assigned successfully.';
    }

    public function editRecord(int $recordId)
    {
        $this->clearNotice();
        $this->resetErrorBag();

        $record = AfterSalesRecord::findOrFail($recordId);
        $recordSection = $record->service_type === 'PMS' ? 'asap' : 'other';

        if ($recordSection !== $this->section) {
            return;
        }

        $this->editingRecordId = $record->id;
        $this->selectedClientId = $record->client_id;
        $this->service_type = $record->service_type;
        $this->change_type = $record->change_type ?? '';
        $this->warranty_type = $record->warranty_type ?? '';

        if ($recordSection === 'asap' && $this->change_type === 'WITH CHANGE') {
            $this->warranty_type = 'OUT OF WARRANTY';
        }

        $this->pms_number = $record->pms_number ?? '';
        $this->job_order_number = $record->job_order_number;
        $this->job_order_date = $record->job_order_date?->format('Y-m-d') ?? '';
        $this->description = $record->description ?? '';
        $this->remarks = $record->remarks ?? '';
        $this->saleControlNo = $record->client?->salesList_no ?? '';
        $this->selectedMaintenanceRecordId = $recordSection === 'other'
            ? ($record->maintenance_record_id
                ?: ClientRecordForMaintenanceAndRepair::where('job_order_number', $record->job_order_number)->value('id'))
            : null;
        $this->maintenanceCompanySearch = $recordSection === 'other'
            ? ClientRecordForMaintenanceAndRepair::whereKey($this->selectedMaintenanceRecordId)->value('company_name') ?? ''
            : '';
        $this->maintenanceSearchPerformed = false;

        $this->dispatch('msd-record-editing');
    }

    public function cancelEdit()
    {
        $this->resetForm();
        $this->clearNotice();
        $this->resetErrorBag();
    }

    private function resetForm()
    {
        $this->reset([
            'change_type',
            'warranty_type',
            'pms_number',
            'job_order_number',
            'job_order_date',
            'description',
            'remarks',
            'editingRecordId',
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
            && ! $this->editingRecordId
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
                $query->where('service_type', 'PMS');
            })
            ->when($this->section === 'other', function ($query) {
                $query->where('service_type', 'Other');
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
            'maintenanceSearchResults' => $maintenanceSearchResults,
            'maintenanceRecordsByJobOrder' => $maintenanceRecordsByJobOrder,
            'records' => $records,
        ]);
    }
}

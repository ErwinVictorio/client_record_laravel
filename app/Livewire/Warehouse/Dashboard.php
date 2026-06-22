<?php

namespace App\Livewire\Warehouse;

use App\Models\AfterSalesRecord;
use App\Models\ClientRecordForMaintenanceAndRepair;
use App\Models\clients;
use App\Models\CreateRecordForAutoRepair;
use Livewire\Component;
use Livewire\WithPagination;

class Dashboard extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $activeTab = 'clients';
    public $clientSearch = '';
    public $autoRepairSearch = '';
    public $maintenanceSearch = '';
    public $pmsRecordSearch = '';
    public $otherRecordSearch = '';
    public $editingRemarksRecordId = null;
    public $editingRemarks = '';
    public $noticeType = '';
    public $noticeMessage = '';

    public function setTab($tab)
    {
        $this->activeTab = $tab;
        $this->clearNotice();
        $this->resetPage();
    }

    public function updatingClientSearch()
    {
        $this->resetPage();
    }

    public function updatingAutoRepairSearch()
    {
        $this->resetPage();
    }

    public function updatingMaintenanceSearch()
    {
        $this->resetPage();
    }

    public function updatingPmsRecordSearch()
    {
        $this->resetPage('pmsRecordsPage');
    }

    public function updatingOtherRecordSearch()
    {
        $this->resetPage('otherRecordsPage');
    }

    public function editRemarks(int $recordId)
    {
        $this->clearNotice();

        $record = AfterSalesRecord::findOrFail($recordId);

        $this->editingRemarksRecordId = $record->id;
        $this->editingRemarks = $record->remarks ?? '';

        $this->dispatch('show-warehouse-remarks-modal', remarks: $this->editingRemarks);
    }

    public function saveRemarks()
    {
        $this->clearNotice();

        $this->validate([
            'editingRemarksRecordId' => 'required|exists:after_sales_records,id',
            'editingRemarks' => 'nullable|string',
        ]);

        AfterSalesRecord::whereKey($this->editingRemarksRecordId)->update([
            'remarks' => $this->editingRemarks ?: null,
        ]);

        $this->editingRemarksRecordId = null;
        $this->editingRemarks = '';
        $this->showNotice('success', 'Remarks updated successfully.');
        $this->dispatch('hide-warehouse-remarks-modal');
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
        $clientSearch = '%' . $this->clientSearch . '%';
        $autoRepairSearch = '%' . $this->autoRepairSearch . '%';
        $maintenanceSearch = '%' . $this->maintenanceSearch . '%';
        $pmsRecordSearch = '%' . $this->pmsRecordSearch . '%';
        $otherRecordSearch = '%' . $this->otherRecordSearch . '%';

        $clients = clients::with('salesman')
            ->where(function ($query) use ($clientSearch) {
                $query->where('company_name', 'like', $clientSearch)
                    ->orWhere('salesList_no', 'like', $clientSearch)
                    ->orWhere('contact_number', 'like', $clientSearch)
                    ->orWhere('status', 'like', $clientSearch);
            })
            ->latest()
            ->paginate(10, ['*'], 'clientsPage');

        $autoRepairRecords = CreateRecordForAutoRepair::where(function ($query) use ($autoRepairSearch) {
                $query->where('company_name', 'like', $autoRepairSearch)
                    ->orWhere('stock_out_number', 'like', $autoRepairSearch)
                    ->orWhere('email', 'like', $autoRepairSearch);
            })
            ->latest()
            ->paginate(10, ['*'], 'autoRepairPage');

        $maintenanceRecords = ClientRecordForMaintenanceAndRepair::where(function ($query) use ($maintenanceSearch) {
                $query->where('company_name', 'like', $maintenanceSearch)
                    ->orWhere('job_order_number', 'like', $maintenanceSearch)
                    ->orWhere('email', 'like', $maintenanceSearch);
            })
            ->latest()
            ->paginate(10, ['*'], 'maintenancePage');

        $pmsRecords = AfterSalesRecord::with('client')
            ->where('service_type', 'PMS')
            ->where(function ($query) use ($pmsRecordSearch) {
                $query->where('job_order_number', 'like', $pmsRecordSearch)
                    ->orWhere('description', 'like', $pmsRecordSearch)
                    ->orWhere('remarks', 'like', $pmsRecordSearch)
                    ->orWhereHas('client', function ($clientQuery) use ($pmsRecordSearch) {
                        $clientQuery->where('company_name', 'like', $pmsRecordSearch)
                            ->orWhere('salesList_no', 'like', $pmsRecordSearch)
                            ->orWhere('item_name', 'like', $pmsRecordSearch);
                    });
            })
            ->latest()
            ->paginate(10, ['*'], 'pmsRecordsPage');

        $otherRecords = AfterSalesRecord::where('service_type', 'Other')
            ->where(function ($query) use ($otherRecordSearch) {
                $query->where('job_order_number', 'like', $otherRecordSearch)
                    ->orWhere('description', 'like', $otherRecordSearch)
                    ->orWhere('remarks', 'like', $otherRecordSearch);
            })
            ->latest()
            ->paginate(10, ['*'], 'otherRecordsPage');

        $otherMaintenanceRecordsByJobOrder = ClientRecordForMaintenanceAndRepair::whereIn(
            'job_order_number',
            $otherRecords->getCollection()->pluck('job_order_number')->filter()->unique()
        )->get()->keyBy('job_order_number');

        return view('livewire.warehouse.dashboard', [
            'totalSoldClients' => clients::where('status', 'Sold')->count(),
            'totalAutoRepairRecords' => CreateRecordForAutoRepair::count(),
            'totalMaintenanceRecords' => ClientRecordForMaintenanceAndRepair::count(),
            'totalPmsRecords' => AfterSalesRecord::where('service_type', 'PMS')->count(),
            'totalOtherRecords' => AfterSalesRecord::where('service_type', 'Other')->count(),
            'clients' => $clients,
            'autoRepairRecords' => $autoRepairRecords,
            'maintenanceRecords' => $maintenanceRecords,
            'pmsRecords' => $pmsRecords,
            'otherRecords' => $otherRecords,
            'otherMaintenanceRecordsByJobOrder' => $otherMaintenanceRecordsByJobOrder,
        ]);
    }
}

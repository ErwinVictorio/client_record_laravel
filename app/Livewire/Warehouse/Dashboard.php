<?php

namespace App\Livewire\Warehouse;

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

    public function setTab($tab)
    {
        $this->activeTab = $tab;
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

    public function render()
    {
        $clientSearch = '%' . $this->clientSearch . '%';
        $autoRepairSearch = '%' . $this->autoRepairSearch . '%';
        $maintenanceSearch = '%' . $this->maintenanceSearch . '%';

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

        return view('livewire.warehouse.dashboard', [
            'totalClients' => clients::count(),
            'totalSoldClients' => clients::where('status', 'Sold')->count(),
            'totalAutoRepairRecords' => CreateRecordForAutoRepair::count(),
            'totalMaintenanceRecords' => ClientRecordForMaintenanceAndRepair::count(),
            'clients' => $clients,
            'autoRepairRecords' => $autoRepairRecords,
            'maintenanceRecords' => $maintenanceRecords,
        ]);
    }
}

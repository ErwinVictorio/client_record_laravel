<?php

namespace App\Livewire\Salesman;

use App\Models\ClientRecordForMaintenanceAndRepair;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class RepairAndMaintence extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    #[On('maintenance-records-updated')]
    public function refreshRecords()
    {
        $this->resetPage();
    }

    public function render()
    {
        $search = trim($this->search);

        $records = ClientRecordForMaintenanceAndRepair::where('salesmanId', Auth::id())
            ->when($search, function ($query) use ($search) {
                $query->where(function ($searchQuery) use ($search) {
                    $searchQuery->where('company_name', 'like', '%'.$search.'%')
                        ->orWhere('serial_number', 'like', '%'.$search.'%')
                        ->orWhere('vehicle_specifications', 'like', '%'.$search.'%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.sales-man.repair-and-maintence', [
            'records' => $records,
        ]);
    }
}

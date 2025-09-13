<?php

namespace App\Livewire\SuperAdmin\Page;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\CreateRecordForAutoRepair;
class AutoPartsRecord extends Component
{

    use WithPagination;

    public $searchQuery = '', $clientSearch = '';

    public function ApplySearch()
    {
        $this->searchQuery = $this->clientSearch;
        $this->resetPage();
    }

    public function render()
    {
        $search = '%' . $this->searchQuery . '%';

        $records = CreateRecordForAutoRepair::where(function ($query) use ($search) {
            $query->where('company_name', 'like', $search);
            $query->orwhere('email', 'like', $search);
            $query->orwhere('stock_out_number', 'like', $search);
        })->paginate(20);

        return view('livewire.super-admin.page.auto-parts-record', [
            'records' => $records
        ]);
    }
}

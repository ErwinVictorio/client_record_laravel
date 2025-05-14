<?php

namespace App\Livewire\Admin\Pages;

use Livewire\Component;
use App\Models\clients;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

class CahierDashboard extends Component
{

    public $clients,$countedAprove,$counttedSoldClient,$searchQuery = '',$clientSearch = '';

    use WithPagination;

    public function mount(){
        $clientStatusCount = clients::selectRaw('status, COUNT(*) as total')
         ->whereIn('status',['Approve', 'Sold'])
         ->groupBy('status')
         ->pluck('total', 'status');

         $this->countedAprove = $clientStatusCount['Approve'] ?? 0;
         $this->counttedSoldClient = $clientStatusCount['Sold'] ?? 0;
    }


    public function applySearch(){
        $this->searchQuery = $this->clientSearch;
        $this->resetPage();
    }

    public function render()
    {
        $search  = '%' . $this->searchQuery . '%';

        $clientList = clients::with(['salesman'])
        ->whereIn('status', ['Sold', 'Approve'])
        ->where(function($query) use ($search){
            $query->where('company_name', 'like', $search);
            $query->orwhere('email', 'like' ,$search);
            $query->orwhere('address', 'like', $search);
            $query->orwhere('status', 'like' ,$search);
        })
        ->select([
            'id',
            'company_name',
            'address',
            'contact_number',
             'contact_person',
            'email',
            'status',
            'salesman_id',
            'salesList_no'
        ])
        ->paginate(5);

        return view('livewire.admin.pages.cahier-dashboard',[
            'clientList' => $clientList
        ]);
    }
}

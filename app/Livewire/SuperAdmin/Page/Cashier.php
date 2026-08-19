<?php

namespace App\Livewire\SuperAdmin\Page;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use App\Models\clients;
class Cashier extends Component
{

 public $clients,$countedAprove,$counttedSoldClient,$searchQuery = '',$clientSearch = '';

    use WithPagination;

    public function mount(): void
    {
        $this->loadCounts();
    }

    private function loadCounts(): void
    {
        $clientStatusCount = clients::selectRaw('status, COUNT(*) as total')
            ->whereIn('status', ['For Approval', 'Sold'])
            ->groupBy('status')
            ->pluck('total', 'status');

        $this->countedAprove = $clientStatusCount['For Approval'] ?? 0;
        $this->counttedSoldClient = $clientStatusCount['Sold'] ?? 0;
    }


    public function applySearch(){
        $this->searchQuery = $this->clientSearch;
        $this->resetPage();
    }

    #[On('clients-updated')]
    public function refreshClients(): void
    {
        $this->loadCounts();
        $this->resetPage();
    }

    public function render()
    {
        $search  = '%' . $this->searchQuery . '%';

        $clientList = clients::with(['salesman'])
          ->whereIn('status', ['For Approval', 'Sold'])
          ->where(function($query) use ($search){
            $query->where('company_name', 'like',$search)
             ->orWhere('status','like',$search)
              ->orWhere('address','like',$search)
             ->orWhere('contact_number','like',$search)
             ->orWhere('salesList_no','like',$search)
             ->orWhere('email','like',$search)
             ->orWhereHas('salesman',function ($q) use ($search){
                $q->where('department', 'like', $search)
                ->orWhere('first_name', 'like',$search)
                ->orWhere('last_name','like', $search);
             });

          })->orderBy('created_at','desc')
            ->paginate(20);


        return view('livewire.super-admin.page.cashier',[
            'clientList' => $clientList
        ]);
    }
    
}

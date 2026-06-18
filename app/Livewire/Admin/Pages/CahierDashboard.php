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
         ->whereIn('status',['For Approval', 'Sold'])
         ->groupBy('status')
         ->pluck('total', 'status');

         $this->countedAprove = $clientStatusCount['For Approval'] ?? 0;
         $this->counttedSoldClient = $clientStatusCount['Sold'] ?? 0;
    }


    public function applySearch(){
        $this->searchQuery = $this->clientSearch;
        $this->resetPage();
    }

    public function render()
    {
        $search  = '%' . $this->searchQuery . '%';

        $clientList = clients::with(['salesman'])->whereIn('status', ['For Approval','Sold'])
          ->where( function($query) use ($search){
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

        $clientList->getCollection()->transform(function ($client) {
            $client->supporting_documents = $this->normalizeDocumentPaths(
                $client->supporting_document_paths,
                $client->supporting_document_path
            );

            return $client;
        });


        return view('livewire.admin.pages.cahier-dashboard',[
            'clientList' => $clientList
        ]);
    }

    private function normalizeDocumentPaths($documentPaths, $legacyDocumentPath = null): array
    {
        if (is_string($documentPaths)) {
            $documentPaths = json_decode($documentPaths, true);
        }

        $paths = is_array($documentPaths) ? $documentPaths : [];

        if ($legacyDocumentPath && ! in_array($legacyDocumentPath, $paths, true)) {
            array_unshift($paths, $legacyDocumentPath);
        }

        return array_values(array_filter($paths));
    }
}

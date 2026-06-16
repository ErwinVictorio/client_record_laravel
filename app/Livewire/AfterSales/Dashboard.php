<?php

namespace App\Livewire\AfterSales;

use App\Models\AfterSalesRecord;
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
    public $selectedClientId = null;
    public $service_type = 'PMS';
    public $pms_number = '';
    public $job_order_number = '';
    public $job_order_date = '';
    public $description = '';
    public $noticeType = '';
    public $noticeMessage = '';

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
        $this->showNotice('success', 'Sold unit found. You can now add PMS or Other job information.');
    }

    public function save()
    {
        $this->clearNotice();
        $this->resetErrorBag();

        $rules = [
            'service_type' => 'required|in:PMS,Other',
            'job_order_number' => 'required|min:2',
            'job_order_date' => 'nullable|date',
            'description' => 'nullable|string',
        ];

        if ($this->service_type === 'PMS') {
            $rules['selectedClientId'] = 'required|exists:clients,id';
            $rules['pms_number'] = 'required|min:1';
        }

        $this->validate($rules, [
            'selectedClientId.required' => 'Please search and select a sold unit before saving a PMS record.',
            'selectedClientId.exists' => 'The selected sold unit no longer exists.',
            'pms_number.required' => 'Please enter the Number of PMS.',
            'job_order_number.required' => 'Please enter the JO Number.',
        ]);

        AfterSalesRecord::create([
            'client_id' => $this->selectedClientId,
            'user_id' => Auth::id(),
            'service_type' => $this->service_type,
            'pms_number' => $this->service_type === 'PMS' ? $this->pms_number : null,
            'job_order_number' => $this->job_order_number,
            'job_order_date' => $this->job_order_date ?: null,
            'description' => $this->description,
        ]);

        $this->reset(['pms_number', 'job_order_number', 'job_order_date', 'description']);
        $this->showNotice('success', 'After Sales record saved successfully.');
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

        $records = AfterSalesRecord::with(['client.salesman', 'user'])
            ->when($this->jobOrderSearch, function ($query) {
                $query->where('job_order_number', 'like', '%' . $this->jobOrderSearch . '%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.after-sales.dashboard', [
            'selectedClient' => $selectedClient,
            'records' => $records,
        ]);
    }
}

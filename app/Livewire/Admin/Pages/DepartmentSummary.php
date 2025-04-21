<?php

namespace App\Livewire\Admin\Pages;
use Illuminate\Support\Facades\DB;

use Livewire\Component;

class DepartmentSummary extends Component
{

    public $departmentSummary;
    public function mount(){

        $this->departmentSummary = DB::table('clients')
        ->join('users','clients.salesman_id', '=', 'users.id')
        ->select(
            'users.department',
            DB::raw("SUM(CASE WHEN clients.status = 'Pending' THEN 1 ELSE 0 END) as Total_pending "),
            DB::raw("SUM(CASE WHEN clients.status = 'Sold' THEN 1 ELSE 0 END) as Total_sold "),
            DB::raw("SUM(CASE WHEN clients.status = 'Approve' THEN 1 ELSE 0 END) as Total_approve ")
        )->groupBy('users.department')->get();
    }

    public function render()
    {
        return view('livewire.admin.pages.department-summary');
    }
}

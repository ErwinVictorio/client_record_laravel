<?php

namespace App\Livewire\Modals;

use Livewire\Attributes\Validate;
use Livewire\Component;
use App\Models\CreateRecordForAutoRepair;
use Illuminate\Support\Facades\Auth;


class CreateAutoRepairRecords extends Component
{
    #[Validate('required|string')] public $company_name;
    #[Validate('required')] public $address;
    #[Validate('required|email')] public $email;
    #[Validate('required|numeric')] public $contact_number;
    #[Validate('required')] public $stock_out_number;
    #[Validate('required')] public $contact_person;
    #[Validate('required|numeric')] public $contact_number_person;
    #[Validate('nullable')] public $bank_account_number;

    public function create_client(){
     $this->validate();


      // $salesman = User::select('id','first_name','last_name')->where('id', Auth::id())->first();

       $companyName = strtoupper(str_replace(' ','',trim($this->company_name)));

        $existingClient = CreateRecordForAutoRepair::whereRaw("REPLACE(UPPER(company_name), ' ', '') = ?", [$companyName])->first();
        // kunin yong currenrt user na naka login
        $currentSalesman = Auth::id();

        if ($existingClient) {
            if ((int) $existingClient->salesmanId !== $currentSalesman) {
                session()->flash('error', "The client is already taken!");
                return;
            } 
        }
      

            

      CreateRecordForAutoRepair::create([
        'company_name' => $this->company_name,
        'address' => $this->address,
        'email' => $this->email,
        'contact_number' => $this->contact_number,
        'stock_out_number' => $this->stock_out_number,
        'contact_person' =>$this->contact_person,
        'contact_number_person' => $this->contact_number_person,
        'bank_account_number' => $this->bank_account_number,
        'salesmanId' => Auth::id() // to get the current salesman na naka login 
      ]);
      session()->flash('success','New Record is Successfully Created');
      $this->dispatch('auto-repair-records-updated');
      $this->reset();
    }

    public function render()
    {
        return view('livewire.modals.create-auto-repair-records');
    }
}

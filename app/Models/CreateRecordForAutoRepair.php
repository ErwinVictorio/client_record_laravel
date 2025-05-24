<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreateRecordForAutoRepair extends Model
{
    //

    public function user(){
        return $this->belongsTo(User::class);
    }

    protected $fillable =[
       'company_name',
       'address',
       'email',
       'bank_account_number',
       'contact_number',
       'contact_person',
       'contact_number_person',
       'stock_out_number',
       'salesmanId'
    ];
}

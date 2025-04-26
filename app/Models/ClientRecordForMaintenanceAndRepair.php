<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientRecordForMaintenanceAndRepair extends Model
{
    //
    protected $fillable = [

        'company_name',
        'address',
        'email',
        'bank_account_number',
        'contact_number',
        'contact_person',
        'contact_number_person',
        'job_order_number'
    ];
}

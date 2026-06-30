<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientRecordForMaintenanceAndRepair extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function afterSalesRecords()
    {
        return $this->hasMany(AfterSalesRecord::class, 'maintenance_record_id');
    }

    protected $table = 'client_record_for_maintenance_and_repairs';

    //
    protected $fillable = [
        'company_name',
        'address',
        'email',
        'bank_account_number',
        'contact_number',
        'contact_person',
        'contact_number_person',
        'job_order_number',
        'serial_number',
        'date_sold',
        'vehicle_specifications',
        'salesmanId',
    ];

    protected $casts = [
        'date_sold' => 'date',
        'vehicle_specifications' => 'array',
    ];
}

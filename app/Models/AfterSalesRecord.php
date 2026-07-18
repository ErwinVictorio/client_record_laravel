<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AfterSalesRecord extends Model
{
    protected $fillable = [
        'client_id',
        'maintenance_record_id',
        'user_id',
        'service_type',
        'change_type',
        'warranty_type',
        'pms_number',
        'job_order_number',
        'job_order_date',
        'description',
        'remarks',
        'salesList_no',
    ];

    protected $casts = [
        'job_order_date' => 'date',
    ];

    public function client()
    {
        return $this->belongsTo(clients::class, 'client_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function maintenanceRecord()
    {
        return $this->belongsTo(ClientRecordForMaintenanceAndRepair::class, 'maintenance_record_id');
    }
}

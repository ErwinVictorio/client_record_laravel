<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AfterSalesRecord extends Model
{
    protected $fillable = [
        'client_id',
        'user_id',
        'service_type',
        'warranty_type',
        'pms_number',
        'job_order_number',
        'job_order_date',
        'description',
        'remarks',
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
}

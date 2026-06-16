<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class clients extends Model
{
    //
    public function salesman(){
        
        return $this->belongsTo(User::class);
    }

    protected $table = 'clients';

    protected $fillable = [
        'company_name',
        'contact_number',
        'email',
        'address',
         'contact_person',
         'contact_number_person',
         'bank_account_number',
         'supporting_document_path',
         'supporting_document_paths',
         'vehicle_specifications',
         'salesman_id',
         'item_name',
         'model_number',
         'year_model',
         'quantity',
         'specification',
         'status'
    ];

    protected $casts = [
        'supporting_document_paths' => 'array',
        'vehicle_specifications' => 'array',
    ];


}

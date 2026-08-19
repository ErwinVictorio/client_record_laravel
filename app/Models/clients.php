<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class clients extends Model
{
    /**
     * The name to show for either kind of client.
     *
     * Personal clients have their name split across the three name columns,
     * while corporate clients use company_name. Keeping that decision in the
     * model prevents views from showing values such as "N/A John Doe".
     */
    public function getDisplayNameAttribute(): string
    {
        $personalName = trim(implode(' ', array_filter([
            $this->first_name,
            $this->middle_name,
            $this->last_name,
        ], fn ($name) => $name !== null && trim((string) $name) !== '')));

        if ($personalName !== '') {
            return $personalName;
        }

        $companyName = trim(implode(' ', array_filter([
            $this->company_name,
            $this->suffix,
        ], fn ($value) => $value !== null && trim((string) $value) !== '')));

        return $companyName ?: ($this->contact_person ?: 'N/A');
    }

    public function salesman()
    {

        return $this->belongsTo(User::class);
    }

    public function afterSalesRecords()
    {
        return $this->hasMany(AfterSalesRecord::class, 'client_id');
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
        'status',
        'rejection_reason',
        'warehouse_remarks',
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
    ];

    protected $casts = [
        'supporting_document_paths' => 'array',
        'vehicle_specifications' => 'array',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Suffix extends Model
{
    //
    protected $table = 'company_suffix';

    protected $fillable = [
        'suffix',
    ];
}

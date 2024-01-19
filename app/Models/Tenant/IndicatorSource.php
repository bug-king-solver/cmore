<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class IndicatorSource extends Pivot
{
    use HasFactory;
    
    protected $fillable = [
        'reference',
    ];
}


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgencyService extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'base_price',
        'price_type',
        'vat_rate',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'base_price' => 'decimal:2',
            'vat_rate' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }
}

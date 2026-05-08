<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuoteItem extends Model
{
    protected $fillable = [
        'quote_id',
        'agency_service_id',
        'description',
        'quantity',
        'unit_price',
        'discount',
        'vat_rate',
        'line_total',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
            'unit_price' => 'decimal:2',
            'discount' => 'decimal:2',
            'vat_rate' => 'decimal:2',
            'line_total' => 'decimal:2',
        ];
    }

    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(AgencyService::class, 'agency_service_id');
    }

    public function netAmount(): float
    {
        return max(0, ((float) $this->quantity * (float) $this->unit_price) - (float) $this->discount);
    }

    public function vatAmount(): float
    {
        return $this->netAmount() * ((float) $this->vat_rate / 100);
    }
}

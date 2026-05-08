<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quote extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'client_id',
        'number',
        'issued_at',
        'valid_until',
        'status',
        'subtotal',
        'vat_total',
        'total',
        'notes',
        'sent_at',
        'accepted_at',
    ];

    protected function casts(): array
    {
        return [
            'issued_at' => 'date',
            'valid_until' => 'date',
            'subtotal' => 'decimal:2',
            'vat_total' => 'decimal:2',
            'total' => 'decimal:2',
            'sent_at' => 'datetime',
            'accepted_at' => 'datetime',
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(QuoteItem::class);
    }

    public function recalculate(): void
    {
        $subtotal = $this->items->sum(fn (QuoteItem $item) => $item->netAmount());
        $vat = $this->items->sum(fn (QuoteItem $item) => $item->vatAmount());

        $this->forceFill([
            'subtotal' => $subtotal,
            'vat_total' => $vat,
            'total' => $subtotal + $vat,
        ])->save();
    }
}

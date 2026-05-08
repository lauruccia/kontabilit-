<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contract extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'client_id',
        'quote_id',
        'contract_template_id',
        'number',
        'title',
        'description',
        'starts_at',
        'ends_at',
        'auto_renewal',
        'duration_months',
        'one_time_amount',
        'monthly_fee',
        'annual_fee',
        'payment_terms',
        'terms',
        'rendered_content',
        'status',
        'public_token',
        'public_token_expires_at',
        'sent_at',
        'signed_at',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'date',
            'ends_at' => 'date',
            'auto_renewal' => 'boolean',
            'one_time_amount' => 'decimal:2',
            'monthly_fee' => 'decimal:2',
            'annual_fee' => 'decimal:2',
            'public_token_expires_at' => 'datetime',
            'sent_at' => 'datetime',
            'signed_at' => 'datetime',
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(ContractTemplate::class, 'contract_template_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(ContractItem::class);
    }

    public function signature(): HasOne
    {
        return $this->hasOne(ContractSignature::class);
    }

    public function isSigned(): bool
    {
        return (bool) $this->signed_at || in_array($this->status, ['signed', 'active'], true);
    }

    public function publicUrl(): string
    {
        return route('public.contracts.show', $this->public_token);
    }
}

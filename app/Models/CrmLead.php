<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CrmLead extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'client_id',
        'assigned_to',
        'company_name',
        'contact_name',
        'email',
        'phone',
        'source',
        'status',
        'priority',
        'estimated_value',
        'next_follow_up_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'estimated_value' => 'decimal:2',
            'next_follow_up_at' => 'date',
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function opportunities(): HasMany
    {
        return $this->hasMany(CrmOpportunity::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(CrmActivity::class);
    }

    public function displayName(): string
    {
        return $this->company_name ?: $this->contact_name;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Hash;

class OtpCode extends Model
{
    protected $fillable = [
        'contract_id',
        'channel',
        'recipient',
        'code_hash',
        'attempts',
        'max_attempts',
        'expires_at',
        'verified_at',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'verified_at' => 'datetime',
        ];
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function verify(string $code): bool
    {
        if ($this->verified_at || $this->expires_at->isPast() || $this->attempts >= $this->max_attempts) {
            return false;
        }

        $this->increment('attempts');

        if (! Hash::check($code, $this->code_hash)) {
            return false;
        }

        $this->forceFill(['verified_at' => now()])->save();

        return true;
    }
}

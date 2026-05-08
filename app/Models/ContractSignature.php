<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContractSignature extends Model
{
    protected $fillable = [
        'contract_id',
        'otp_code_id',
        'email',
        'signed_at',
        'ip_address',
        'user_agent',
        'otp_verified',
        'document_hash',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'signed_at' => 'datetime',
            'otp_verified' => 'boolean',
            'metadata' => 'array',
        ];
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function otpCode(): BelongsTo
    {
        return $this->belongsTo(OtpCode::class);
    }
}

<?php

namespace App\Services;

use App\Mail\ContractOtpMail;
use App\Models\Contract;
use App\Models\ContractSignature;
use App\Models\OtpCode;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class OtpSignatureService
{
    public function preparePublicToken(Contract $contract): Contract
    {
        $contract->forceFill([
            'public_token' => $contract->public_token ?: Str::random(64),
            'public_token_expires_at' => now()->addDays(30),
            'status' => 'awaiting_signature',
            'sent_at' => now(),
        ])->save();

        return $contract;
    }

    public function sendOtp(Contract $contract): OtpCode
    {
        $code = (string) random_int(100000, 999999);
        $otp = OtpCode::create([
            'contract_id' => $contract->id,
            'channel' => 'email',
            'recipient' => $contract->client->email,
            'code_hash' => Hash::make($code),
            'max_attempts' => (int) config('contracts.otp_max_attempts', 5),
            'expires_at' => now()->addMinutes((int) config('contracts.otp_ttl_minutes', 10)),
        ]);

        Mail::to($contract->client->email)->send(new ContractOtpMail($contract, $code));

        return $otp;
    }

    public function sign(Contract $contract, string $code, string $ip, ?string $userAgent): bool
    {
        $otp = $contract->hasMany(OtpCode::class)->latest()->first();

        if (! $otp || ! $otp->verify($code)) {
            return false;
        }

        $hash = hash('sha256', $contract->number.'|'.$contract->rendered_content.'|'.now()->toIso8601String());

        ContractSignature::create([
            'contract_id' => $contract->id,
            'otp_code_id' => $otp->id,
            'email' => $otp->recipient,
            'signed_at' => now(),
            'ip_address' => $ip,
            'user_agent' => $userAgent,
            'otp_verified' => true,
            'document_hash' => $hash,
            'metadata' => ['channel' => 'email'],
        ]);

        $contract->forceFill([
            'status' => 'signed',
            'signed_at' => now(),
        ])->save();

        return true;
    }
}

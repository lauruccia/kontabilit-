<x-mail::message>
# Codice OTP

Usa questo codice per firmare il contratto {{ $contract->number }}:

<x-mail::panel>
{{ $code }}
</x-mail::panel>

Il codice scade tra {{ config('contracts.otp_ttl_minutes') }} minuti.

{{ config('app.name') }}
</x-mail::message>

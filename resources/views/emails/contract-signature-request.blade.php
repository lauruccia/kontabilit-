<x-mail::message>
# Contratto da firmare

Il contratto {{ $contract->number }} è pronto per la firma elettronica tramite OTP email.

<x-mail::button :url="$contract->publicUrl()">
Apri contratto
</x-mail::button>

{{ config('app.name') }}
</x-mail::message>

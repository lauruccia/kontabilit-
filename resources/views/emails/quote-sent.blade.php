<x-mail::message>
# Preventivo {{ $quote->number }}

Il preventivo è disponibile nella tua area cliente.

Totale: € {{ number_format((float) $quote->total, 2, ',', '.') }}

{{ config('app.name') }}
</x-mail::message>

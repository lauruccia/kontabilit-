<?php

namespace App\Services;

use App\Models\Contract;

class ContractTemplateRenderer
{
    public function render(Contract $contract, string $content): string
    {
        $client = $contract->client;
        $services = $contract->items->pluck('description')->join(', ');

        $values = [
            '{{nome_cliente}}' => $client->contact_name,
            '{{azienda_cliente}}' => $client->company_name ?? $client->contact_name,
            '{{partita_iva}}' => $client->vat_number ?? '',
            '{{codice_fiscale}}' => $client->tax_code ?? '',
            '{{indirizzo_cliente}}' => collect([$client->address, $client->postal_code, $client->city, $client->province])->filter()->join(' '),
            '{{data_contratto}}' => optional($contract->starts_at)->format('d/m/Y') ?? now()->format('d/m/Y'),
            '{{numero_contratto}}' => $contract->number,
            '{{servizi}}' => $services,
            '{{importo_totale}}' => number_format((float) $contract->one_time_amount, 2, ',', '.'),
            '{{canone_mensile}}' => number_format((float) $contract->monthly_fee, 2, ',', '.'),
            '{{durata}}' => $contract->duration_months ? $contract->duration_months.' mesi' : '',
        ];

        return str_replace(array_keys($values), array_values($values), $content);
    }
}

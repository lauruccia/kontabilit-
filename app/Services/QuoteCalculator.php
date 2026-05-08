<?php

namespace App\Services;

class QuoteCalculator
{
    public function totals(array $items): array
    {
        $subtotal = 0;
        $vatTotal = 0;
        $normalized = [];

        foreach ($items as $item) {
            $quantity = (float) ($item['quantity'] ?? 1);
            $unitPrice = (float) ($item['unit_price'] ?? 0);
            $discount = (float) ($item['discount'] ?? 0);
            $vatRate = (float) ($item['vat_rate'] ?? 22);
            $net = max(0, ($quantity * $unitPrice) - $discount);
            $vat = $net * ($vatRate / 100);

            $normalized[] = $item + ['line_total' => round($net + $vat, 2)];
            $subtotal += $net;
            $vatTotal += $vat;
        }

        return [
            'items' => $normalized,
            'subtotal' => round($subtotal, 2),
            'vat_total' => round($vatTotal, 2),
            'total' => round($subtotal + $vatTotal, 2),
        ];
    }
}

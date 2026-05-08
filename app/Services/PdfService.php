<?php

namespace App\Services;

use App\Models\Contract;
use App\Models\Payment;
use App\Models\Quote;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class PdfService
{
    public function quote(Quote $quote): Response
    {
        return Pdf::loadView('pdf.quote', ['quote' => $quote->load('client', 'items')])
            ->download($quote->number.'.pdf');
    }

    public function contract(Contract $contract): Response
    {
        return Pdf::loadView('pdf.contract', ['contract' => $contract->load('client', 'items', 'signature')])
            ->download($contract->number.'.pdf');
    }

    public function payment(Payment $payment): Response
    {
        return Pdf::loadView('pdf.payment', ['payment' => $payment->load('client', 'contract', 'quote')])
            ->download($payment->number.'.pdf');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Contract;
use App\Models\Payment;
use App\Models\Quote;
use App\Services\ActivityLogger;
use App\Services\NumberGenerator;
use App\Services\PdfService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $payments = Payment::with('client')->latest()->paginate(15);
        return view('admin.payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(NumberGenerator $numbers): View
    {
        return view('admin.payments.create', [
            'payment' => new Payment(['number' => $numbers->payment(), 'due_date' => now()->addDays(15)]),
            'clients' => Client::orderBy('company_name')->get(),
            'contracts' => Contract::orderByDesc('id')->get(),
            'quotes' => Quote::orderByDesc('id')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ActivityLogger $logger): RedirectResponse
    {
        $payment = Payment::create($this->validated($request));
        $this->syncInstallments($payment, (int) $request->integer('installments', 1));
        $logger->log('payment.created', $payment);

        return redirect()->route('admin.payments.show', $payment)->with('status', 'Pagamento creato.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment): View
    {
        return view('admin.payments.show', ['payment' => $payment->load('client', 'contract', 'quote', 'installments')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment): View
    {
        return view('admin.payments.edit', [
            'payment' => $payment,
            'clients' => Client::orderBy('company_name')->get(),
            'contracts' => Contract::orderByDesc('id')->get(),
            'quotes' => Quote::orderByDesc('id')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment, ActivityLogger $logger): RedirectResponse
    {
        $payment->update($this->validated($request));
        $logger->log('payment.updated', $payment);

        return redirect()->route('admin.payments.show', $payment)->with('status', 'Pagamento aggiornato.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment): RedirectResponse
    {
        $payment->delete();
        return redirect()->route('admin.payments.index')->with('status', 'Pagamento archiviato.');
    }

    public function markPaid(Payment $payment, ActivityLogger $logger): RedirectResponse
    {
        $payment->forceFill(['status' => 'paid', 'paid_amount' => $payment->amount, 'paid_at' => now()])->save();
        $payment->installments()->update(['status' => 'paid', 'paid_at' => now()]);
        $logger->log('payment.received', $payment);

        return back()->with('status', 'Pagamento segnato come incassato.');
    }

    public function receipt(Payment $payment, PdfService $pdf)
    {
        return $pdf->payment($payment);
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'quote_id' => ['nullable', 'exists:quotes,id'],
            'contract_id' => ['nullable', 'exists:contracts,id'],
            'number' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:deposit,balance,installment,recurring'],
            'method' => ['required', 'in:manual,stripe'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'paid_amount' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'in:unpaid,partial,paid,expired,refunded'],
            'due_date' => ['nullable', 'date'],
        ]);
    }

    private function syncInstallments(Payment $payment, int $count): void
    {
        $count = max(1, $count);
        $amount = round((float) $payment->amount / $count, 2);
        for ($i = 1; $i <= $count; $i++) {
            $payment->installments()->create([
                'sequence' => $i,
                'amount' => $amount,
                'due_date' => $payment->due_date?->copy()->addMonths($i - 1),
                'status' => $payment->status === 'paid' ? 'paid' : 'unpaid',
                'paid_at' => $payment->status === 'paid' ? now() : null,
            ]);
        }
    }
}

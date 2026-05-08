<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\QuoteSentMail;
use App\Models\AgencyService;
use App\Models\Client;
use App\Models\Quote;
use App\Services\ActivityLogger;
use App\Services\NumberGenerator;
use App\Services\PdfService;
use App\Services\QuoteCalculator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class QuoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $quotes = Quote::with('client')->latest()->paginate(15);
        return view('admin.quotes.index', compact('quotes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(NumberGenerator $numbers): View
    {
        return view('admin.quotes.create', [
            'quote' => new Quote(['number' => $numbers->quote(), 'issued_at' => now(), 'valid_until' => now()->addDays(15)]),
            'clients' => Client::orderBy('company_name')->get(),
            'services' => AgencyService::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, QuoteCalculator $calculator, ActivityLogger $logger): RedirectResponse
    {
        $data = $this->validated($request);
        $totals = $calculator->totals($data['items']);

        $quote = Quote::create($data + [
            'subtotal' => $totals['subtotal'],
            'vat_total' => $totals['vat_total'],
            'total' => $totals['total'],
        ]);
        $quote->items()->createMany($totals['items']);
        $logger->log('quote.created', $quote);

        return redirect()->route('admin.quotes.show', $quote)->with('status', 'Preventivo creato.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Quote $quote): View
    {
        return view('admin.quotes.show', ['quote' => $quote->load('client', 'items.service')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Quote $quote): View
    {
        return view('admin.quotes.edit', [
            'quote' => $quote->load('items'),
            'clients' => Client::orderBy('company_name')->get(),
            'services' => AgencyService::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Quote $quote, QuoteCalculator $calculator, ActivityLogger $logger): RedirectResponse
    {
        $data = $this->validated($request);
        $totals = $calculator->totals($data['items']);
        $quote->update($data + ['subtotal' => $totals['subtotal'], 'vat_total' => $totals['vat_total'], 'total' => $totals['total']]);
        $quote->items()->delete();
        $quote->items()->createMany($totals['items']);
        $logger->log('quote.updated', $quote);

        return redirect()->route('admin.quotes.show', $quote)->with('status', 'Preventivo aggiornato.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quote $quote): RedirectResponse
    {
        $quote->delete();
        return redirect()->route('admin.quotes.index')->with('status', 'Preventivo archiviato.');
    }

    public function pdf(Quote $quote, PdfService $pdf)
    {
        return $pdf->quote($quote);
    }

    public function send(Quote $quote, ActivityLogger $logger): RedirectResponse
    {
        Mail::to($quote->client->email)->send(new QuoteSentMail($quote));
        $quote->forceFill(['status' => 'sent', 'sent_at' => now()])->save();
        $logger->log('quote.sent', $quote);

        return back()->with('status', 'Preventivo inviato via email.');
    }

    private function validated(Request $request): array
    {
        $request->merge([
            'items' => collect($request->input('items', []))
                ->filter(fn (array $item) => filled($item['description'] ?? null))
                ->values()
                ->all(),
        ]);

        return $request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'number' => ['required', 'string', 'max:255'],
            'issued_at' => ['required', 'date'],
            'valid_until' => ['nullable', 'date'],
            'status' => ['required', 'in:draft,sent,accepted,rejected,expired'],
            'notes' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.agency_service_id' => ['nullable', 'exists:agency_services,id'],
            'items.*.description' => ['required', 'string', 'max:255'],
            'items.*.quantity' => ['required', 'numeric', 'min:0.01'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
            'items.*.discount' => ['nullable', 'numeric', 'min:0'],
            'items.*.vat_rate' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);
    }
}

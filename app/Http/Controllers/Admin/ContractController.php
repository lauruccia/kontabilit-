<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ContractSignatureRequestMail;
use App\Models\AgencyService;
use App\Models\Client;
use App\Models\Contract;
use App\Models\ContractTemplate;
use App\Models\Quote;
use App\Services\ActivityLogger;
use App\Services\ContractTemplateRenderer;
use App\Services\NumberGenerator;
use App\Services\OtpSignatureService;
use App\Services\PdfService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $contracts = Contract::with('client')->latest()->paginate(15);
        return view('admin.contracts.index', compact('contracts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(NumberGenerator $numbers): View
    {
        return view('admin.contracts.create', [
            'contract' => new Contract(['number' => $numbers->contract(), 'starts_at' => now(), 'duration_months' => 12]),
            'clients' => Client::orderBy('company_name')->get(),
            'templates' => ContractTemplate::where('is_active', true)->orderBy('name')->get(),
            'services' => AgencyService::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ContractTemplateRenderer $renderer, ActivityLogger $logger): RedirectResponse
    {
        $data = $this->validated($request);
        $contract = Contract::create($data);
        $contract->items()->createMany($data['items']);
        $contract->load('client', 'items');
        $templateContent = optional($contract->template)->content ?: $contract->terms;
        $contract->forceFill(['rendered_content' => $renderer->render($contract, $templateContent ?? '')])->save();
        $logger->log('contract.created', $contract);

        return redirect()->route('admin.contracts.show', $contract)->with('status', 'Contratto creato.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Contract $contract): View
    {
        return view('admin.contracts.show', ['contract' => $contract->load('client', 'items.service', 'signature')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contract $contract): View|RedirectResponse
    {
        if ($contract->isSigned()) {
            return redirect()->route('admin.contracts.show', $contract)->with('status', 'I contratti firmati non sono modificabili.');
        }

        return view('admin.contracts.edit', [
            'contract' => $contract->load('items'),
            'clients' => Client::orderBy('company_name')->get(),
            'templates' => ContractTemplate::where('is_active', true)->orderBy('name')->get(),
            'services' => AgencyService::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contract $contract, ContractTemplateRenderer $renderer, ActivityLogger $logger): RedirectResponse
    {
        abort_if($contract->isSigned(), 423);
        $data = $this->validated($request);
        $contract->update($data);
        $contract->items()->delete();
        $contract->items()->createMany($data['items']);
        $contract->load('client', 'items');
        $templateContent = optional($contract->template)->content ?: $contract->terms;
        $contract->forceFill(['rendered_content' => $renderer->render($contract, $templateContent ?? '')])->save();
        $logger->log('contract.updated', $contract);

        return redirect()->route('admin.contracts.show', $contract)->with('status', 'Contratto aggiornato.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contract $contract): RedirectResponse
    {
        abort_if($contract->isSigned(), 423);
        $contract->delete();
        return redirect()->route('admin.contracts.index')->with('status', 'Contratto archiviato.');
    }

    public function pdf(Contract $contract, PdfService $pdf)
    {
        return $pdf->contract($contract);
    }

    public function send(Contract $contract, OtpSignatureService $signatureService, ActivityLogger $logger): RedirectResponse
    {
        $signatureService->preparePublicToken($contract);
        Mail::to($contract->client->email)->send(new ContractSignatureRequestMail($contract));
        $logger->log('contract.sent', $contract, ['public_url' => $contract->publicUrl()]);

        return back()->with('status', 'Contratto inviato al cliente.');
    }

    public function duplicate(Contract $contract, NumberGenerator $numbers): RedirectResponse
    {
        $copy = $contract->replicate(['number', 'public_token', 'public_token_expires_at', 'sent_at', 'signed_at', 'status']);
        $copy->number = $numbers->contract();
        $copy->status = 'draft';
        $copy->save();
        foreach ($contract->items as $item) {
            $copy->items()->create($item->only(['agency_service_id', 'description', 'quantity', 'unit_price', 'vat_rate', 'billing_type', 'line_total']));
        }

        return redirect()->route('admin.contracts.edit', $copy)->with('status', 'Contratto duplicato.');
    }

    public function fromQuote(Quote $quote, NumberGenerator $numbers): RedirectResponse
    {
        $contract = Contract::create([
            'client_id' => $quote->client_id,
            'quote_id' => $quote->id,
            'number' => $numbers->contract(),
            'title' => 'Contratto da '.$quote->number,
            'starts_at' => now(),
            'duration_months' => 12,
            'one_time_amount' => $quote->total,
            'status' => 'draft',
            'terms' => 'Condizioni contrattuali standard da completare.',
            'rendered_content' => 'Contratto generato dal preventivo '.$quote->number,
        ]);
        foreach ($quote->items as $item) {
            $contract->items()->create([
                'agency_service_id' => $item->agency_service_id,
                'description' => $item->description,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'vat_rate' => $item->vat_rate,
                'billing_type' => 'one_time',
                'line_total' => $item->line_total,
            ]);
        }

        return redirect()->route('admin.contracts.edit', $contract);
    }

    private function validated(Request $request): array
    {
        $request->merge([
            'items' => collect($request->input('items', []))
                ->filter(fn (array $item) => filled($item['description'] ?? null))
                ->values()
                ->all(),
        ]);

        $data = $request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'contract_template_id' => ['nullable', 'exists:contract_templates,id'],
            'number' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date'],
            'auto_renewal' => ['nullable', 'boolean'],
            'duration_months' => ['nullable', 'integer', 'min:1'],
            'one_time_amount' => ['nullable', 'numeric', 'min:0'],
            'monthly_fee' => ['nullable', 'numeric', 'min:0'],
            'annual_fee' => ['nullable', 'numeric', 'min:0'],
            'payment_terms' => ['nullable', 'string', 'max:255'],
            'terms' => ['nullable', 'string'],
            'status' => ['required', 'in:draft,sent,awaiting_signature,signed,active,suspended,completed,cancelled'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.agency_service_id' => ['nullable', 'exists:agency_services,id'],
            'items.*.description' => ['required', 'string', 'max:255'],
            'items.*.quantity' => ['required', 'numeric', 'min:0.01'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
            'items.*.vat_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'items.*.billing_type' => ['required', 'in:one_time,monthly,annual'],
        ]);
        $data['auto_renewal'] = $request->boolean('auto_renewal');
        foreach ($data['items'] as &$item) {
            $item['line_total'] = round((float) $item['quantity'] * (float) $item['unit_price'] * (1 + ((float) $item['vat_rate'] / 100)), 2);
        }
        return $data;
    }
}

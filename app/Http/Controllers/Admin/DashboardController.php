<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Contract;
use App\Models\CrmActivity;
use App\Models\CrmLead;
use App\Models\CrmOpportunity;
use App\Models\Payment;
use App\Models\Reminder;
use App\Models\Quote;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $paidByMonth = Payment::where('status', 'paid')
            ->whereNotNull('paid_at')
            ->where('paid_at', '>=', now()->subMonths(5)->startOfMonth())
            ->selectRaw("strftime('%Y-%m', paid_at) as month, sum(paid_amount) as total")
            ->groupBy('month')
            ->pluck('total', 'month');

        $monthlyRevenue = collect(range(5, 0))
            ->map(fn (int $offset) => now()->subMonths($offset)->format('Y-m'))
            ->map(fn (string $month) => (float) ($paidByMonth[$month] ?? 0));

        $maxRevenue = max(1, $monthlyRevenue->max());

        return view('dashboard', [
            'stats' => [
                ['label' => 'Clienti', 'value' => Client::count(), 'trend' => 'Schede attive', 'tone' => 'blue'],
                ['label' => 'Lead CRM', 'value' => CrmLead::whereNotIn('status', ['won', 'lost'])->count(), 'trend' => 'Pipeline', 'tone' => 'blue'],
                ['label' => 'Contratti attivi', 'value' => Contract::whereIn('status', ['signed', 'active'])->count(), 'trend' => 'In corso', 'tone' => 'emerald'],
                ['label' => 'Scadenze 30 giorni', 'value' => Reminder::whereBetween('due_date', [now(), now()->addDays(30)])->where('status', '!=', 'completed')->count(), 'trend' => 'Priorita', 'tone' => 'rose'],
                ['label' => 'Incassato', 'value' => '€ '.number_format((float) Payment::where('status', 'paid')->sum('paid_amount'), 2, ',', '.'), 'trend' => 'Totale', 'tone' => 'emerald'],
                ['label' => 'Da incassare', 'value' => '€ '.number_format((float) Payment::whereIn('status', ['unpaid', 'partial'])->sum('amount'), 2, ',', '.'), 'trend' => 'Aperto', 'tone' => 'slate'],
            ],
            'quickActions' => [
                ['label' => 'Nuovo cliente', 'route' => 'admin.clients.create', 'description' => 'Aggiungi anagrafica e dati fiscali'],
                ['label' => 'Nuovo lead CRM', 'route' => 'admin.crm.leads.create', 'description' => 'Registra una nuova opportunita'],
                ['label' => 'Nuovo preventivo', 'route' => 'admin.quotes.create', 'description' => 'Prepara una proposta cliente'],
                ['label' => 'Carica documento', 'route' => 'admin.documents.create', 'description' => 'Archivia file cliente o interno'],
            ],
            'recentClients' => Client::latest()->limit(5)->get(),
            'crmPipeline' => CrmLead::query()->selectRaw('status, count(*) as count, sum(estimated_value) as value')->groupBy('status')->get()->keyBy('status'),
            'crmActivities' => CrmActivity::with('lead', 'assignee')->where('status', 'open')->orderBy('due_at')->limit(5)->get(),
            'openOpportunitiesValue' => CrmOpportunity::where('status', 'open')->sum('amount'),
            'openPayments' => Payment::with('client')->whereIn('status', ['unpaid', 'partial'])->orderBy('due_date')->limit(5)->get(),
            'upcomingDeadlines' => Reminder::with('client')->orderBy('due_date')->limit(5)->get(),
            'monthlyRevenue' => $monthlyRevenue->map(fn (float $value) => [
                'label' => $value > 0 ? '€ '.number_format($value, 0, ',', '.') : '€ 0',
                'height' => max(8, round(($value / $maxRevenue) * 100)),
            ]),
            'contractsByStatus' => Contract::query()->selectRaw('status, count(*) as aggregate')->groupBy('status')->pluck('aggregate', 'status')->all() ?: ['draft' => 0],
        ]);
    }
}

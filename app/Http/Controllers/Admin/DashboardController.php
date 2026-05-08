<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('dashboard', [
            'stats' => [
                ['label' => 'Clienti totali', 'value' => 0, 'trend' => 'Pronto per STEP 2', 'tone' => 'blue'],
                ['label' => 'Contratti attivi', 'value' => 0, 'trend' => 'Modulo contratti STEP 4', 'tone' => 'emerald'],
                ['label' => 'In attesa firma', 'value' => 0, 'trend' => 'Firma OTP STEP 5', 'tone' => 'amber'],
                ['label' => 'Contratti scaduti', 'value' => 0, 'trend' => 'Scadenze STEP 8', 'tone' => 'rose'],
                ['label' => 'Pagamenti incassati', 'value' => '€ 0,00', 'trend' => 'Stripe STEP 7', 'tone' => 'emerald'],
                ['label' => 'Pagamenti sospesi', 'value' => '€ 0,00', 'trend' => 'Rate e saldo STEP 7', 'tone' => 'slate'],
            ],
            'recentActivities' => [
                'Setup Laravel 12 completato',
                'Autenticazione Breeze installata',
                'Ruoli Admin, Operatore e Cliente configurati',
            ],
            'upcomingDeadlines' => [
                'Nessuna scadenza registrata',
            ],
            'monthlyRevenue' => [0, 0, 0, 0, 0, 0],
            'contractsByStatus' => [
                'Bozza' => 0,
                'Inviato' => 0,
                'Firmato' => 0,
                'Attivo' => 0,
            ],
        ]);
    }
}

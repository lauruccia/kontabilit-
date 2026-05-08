<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Client;
use App\Models\AgencyService;
use App\Models\Contract;
use App\Models\ContractTemplate;
use App\Models\CrmActivity;
use App\Models\CrmLead;
use App\Models\CrmOpportunity;
use App\Models\Payment;
use App\Models\Quote;
use App\Models\Reminder;
use App\Models\Setting;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $permissions = [
            'view dashboard',
            'manage clients',
            'manage services',
            'manage quotes',
            'manage contracts',
            'manage payments',
            'manage documents',
            'manage crm',
            'manage users',
            'manage settings',
            'view client area',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        $superadminRole = Role::findOrCreate('superadmin');
        $adminRole = Role::findOrCreate('admin');
        $secretaryRole = Role::findOrCreate('secretary');
        $agentRole = Role::findOrCreate('agent');
        $operatorRole = Role::findOrCreate('operator');
        $clientRole = Role::findOrCreate('client');

        $superadminRole->syncPermissions($permissions);
        $adminRole->syncPermissions($permissions);
        $secretaryRole->syncPermissions([
            'view dashboard',
            'manage clients',
            'manage quotes',
            'manage payments',
            'manage documents',
            'manage crm',
        ]);
        $agentRole->syncPermissions([
            'view dashboard',
            'manage clients',
            'manage quotes',
            'manage crm',
        ]);
        $operatorRole->syncPermissions([
            'view dashboard',
            'manage clients',
            'manage services',
            'manage quotes',
            'manage contracts',
            'manage payments',
            'manage documents',
            'manage crm',
        ]);
        $clientRole->syncPermissions(['view client area']);

        $admin = User::updateOrCreate(
            ['email' => 'admin@agency.test'],
            ['name' => 'Admin Gruppo Kosmos', 'phone' => '+39 06 0000 0001', 'job_title' => 'Admin operativo', 'is_active' => true, 'password' => Hash::make('password')]
        );
        $admin->assignRole($adminRole);

        $superadmin = User::updateOrCreate(
            ['email' => 'superadmin@agency.test'],
            ['name' => 'Superadmin Gruppo Kosmos', 'phone' => '+39 06 0000 0000', 'job_title' => 'Superadmin', 'is_active' => true, 'password' => Hash::make('password')]
        );
        $superadmin->assignRole($superadminRole);

        $operator = User::updateOrCreate(
            ['email' => 'operator@agency.test'],
            ['name' => 'Operatore Interno', 'phone' => '+39 06 0000 0002', 'job_title' => 'Operatore backoffice', 'is_active' => true, 'password' => Hash::make('password')]
        );
        $operator->assignRole($operatorRole);

        $secretary = User::updateOrCreate(
            ['email' => 'segreteria@agency.test'],
            ['name' => 'Segreteria Kosmos', 'phone' => '+39 06 0000 0003', 'job_title' => 'Segreteria amministrativa', 'is_active' => true, 'password' => Hash::make('password')]
        );
        $secretary->assignRole($secretaryRole);

        $agent = User::updateOrCreate(
            ['email' => 'agente@agency.test'],
            ['name' => 'Agente Commerciale', 'phone' => '+39 06 0000 0004', 'job_title' => 'Sales agent', 'is_active' => true, 'password' => Hash::make('password')]
        );
        $agent->assignRole($agentRole);

        $demoClient = Client::updateOrCreate(
            ['email' => 'cliente@example.test'],
            [
                'type' => 'company',
                'company_name' => 'Demo Srl',
                'contact_name' => 'Cliente Demo',
                'phone' => '+39 02 0000 0000',
                'vat_number' => '12345678901',
                'tax_code' => '12345678901',
                'pec' => 'demo@pec.example.test',
                'sdi' => '0000000',
                'address' => 'Via Roma 10',
                'city' => 'Milano',
                'province' => 'MI',
                'postal_code' => '20100',
                'status' => 'active',
                'internal_notes' => 'Cliente demo per verificare area riservata e flussi contrattuali.',
                'activated_at' => now(),
            ]
        );

        $client = User::updateOrCreate(
            ['email' => 'cliente@example.test'],
            ['client_id' => $demoClient->id, 'name' => 'Cliente Demo', 'phone' => '+39 02 0000 0000', 'job_title' => 'Cliente', 'is_active' => true, 'password' => Hash::make('password')]
        );
        $client->assignRole($clientRole);

        collect([
            ['name' => 'Sito web', 'price_type' => 'one_time', 'base_price' => 2500, 'description' => 'Progettazione e sviluppo sito web corporate responsive.'],
            ['name' => 'E-commerce', 'price_type' => 'one_time', 'base_price' => 5500, 'description' => 'Store online completo con catalogo prodotti, checkout e integrazioni.'],
            ['name' => 'Hosting', 'price_type' => 'annual', 'base_price' => 240, 'description' => 'Hosting gestito con backup, SSL e monitoraggio base.'],
            ['name' => 'Manutenzione', 'price_type' => 'monthly', 'base_price' => 180, 'description' => 'Aggiornamenti tecnici, piccole modifiche e controllo sicurezza.'],
            ['name' => 'SEO', 'price_type' => 'monthly', 'base_price' => 450, 'description' => 'Ottimizzazione organica, report e miglioramento contenuti.'],
            ['name' => 'Social media', 'price_type' => 'monthly', 'base_price' => 600, 'description' => 'Piano editoriale, creativita e pubblicazione contenuti.'],
            ['name' => 'Advertising', 'price_type' => 'monthly', 'base_price' => 700, 'description' => 'Gestione campagne paid con fee agency escluso budget media.'],
            ['name' => 'Consulenza', 'price_type' => 'one_time', 'base_price' => 120, 'description' => 'Consulenza strategica o tecnica su base oraria.'],
            ['name' => 'Sviluppo software', 'price_type' => 'one_time', 'base_price' => 8000, 'description' => 'Analisi, sviluppo e manutenzione di applicazioni custom.'],
            ['name' => 'Altro', 'price_type' => 'one_time', 'base_price' => 0, 'description' => 'Voce personalizzabile per esigenze non standard.'],
        ])->each(fn (array $service) => AgencyService::updateOrCreate(
            ['name' => $service['name']],
            $service + ['vat_rate' => 22, 'is_active' => true]
        ));

        $template = ContractTemplate::updateOrCreate(
            ['name' => 'Contratto sito web standard'],
            [
                'category' => 'Siti web',
                'content' => "Contratto n. {{numero_contratto}}\n\nTra la Web Agency e {{azienda_cliente}} rappresentata da {{nome_cliente}}, con P.IVA {{partita_iva}}.\n\nServizi inclusi: {{servizi}}.\n\nImporto una tantum: euro {{importo_totale}}. Canone mensile: euro {{canone_mensile}}. Durata: {{durata}}.\n\nIl cliente accetta condizioni, privacy e modalita operative indicate.",
                'is_active' => true,
            ]
        );

        $quote = Quote::updateOrCreate(
            ['number' => 'PREV-2026-00001'],
            [
                'client_id' => $demoClient->id,
                'issued_at' => now(),
                'valid_until' => now()->addDays(15),
                'status' => 'sent',
                'subtotal' => 2500,
                'vat_total' => 550,
                'total' => 3050,
                'notes' => 'Preventivo demo generato dal seed.',
                'sent_at' => now(),
            ]
        );
        $quote->items()->updateOrCreate(
            ['description' => 'Sito web corporate'],
            ['agency_service_id' => AgencyService::where('name', 'Sito web')->value('id'), 'quantity' => 1, 'unit_price' => 2500, 'discount' => 0, 'vat_rate' => 22, 'line_total' => 3050]
        );

        $contract = Contract::updateOrCreate(
            ['number' => 'CTR-2026-00001'],
            [
                'client_id' => $demoClient->id,
                'quote_id' => $quote->id,
                'contract_template_id' => $template->id,
                'title' => 'Realizzazione sito web Demo Srl',
                'description' => 'Contratto demo per realizzazione sito web.',
                'starts_at' => now(),
                'ends_at' => now()->addYear(),
                'auto_renewal' => true,
                'duration_months' => 12,
                'one_time_amount' => 3050,
                'monthly_fee' => 180,
                'annual_fee' => 0,
                'payment_terms' => 'Acconto 50%, saldo alla pubblicazione',
                'terms' => $template->content,
                'rendered_content' => str_replace(
                    ['{{numero_contratto}}', '{{azienda_cliente}}', '{{nome_cliente}}', '{{partita_iva}}', '{{servizi}}', '{{importo_totale}}', '{{canone_mensile}}', '{{durata}}'],
                    ['CTR-2026-00001', 'Demo Srl', 'Cliente Demo', '12345678901', 'Sito web corporate', '3.050,00', '180,00', '12 mesi'],
                    $template->content
                ),
                'status' => 'draft',
            ]
        );
        $contract->items()->updateOrCreate(
            ['description' => 'Sito web corporate'],
            ['agency_service_id' => AgencyService::where('name', 'Sito web')->value('id'), 'quantity' => 1, 'unit_price' => 2500, 'vat_rate' => 22, 'billing_type' => 'one_time', 'line_total' => 3050]
        );

        Payment::updateOrCreate(
            ['number' => 'PAY-2026-00001'],
            ['client_id' => $demoClient->id, 'quote_id' => $quote->id, 'contract_id' => $contract->id, 'type' => 'deposit', 'method' => 'manual', 'amount' => 1525, 'paid_amount' => 0, 'status' => 'unpaid', 'due_date' => now()->addDays(7)]
        );

        Reminder::updateOrCreate(
            ['title' => 'Rinnovo hosting Demo Srl'],
            ['client_id' => $demoClient->id, 'type' => 'hosting_renewal', 'due_date' => now()->addMonth(), 'status' => 'upcoming', 'notes' => 'Verificare rinnovo hosting e dominio.']
        );

        $lead = CrmLead::updateOrCreate(
            ['email' => 'lead@azienda.test'],
            [
                'assigned_to' => $agent->id,
                'company_name' => 'Azienda Lead Spa',
                'contact_name' => 'Mario Rossi',
                'phone' => '+39 06 1111 2222',
                'source' => 'Sito Gruppo Kosmos',
                'status' => 'qualified',
                'priority' => 'high',
                'estimated_value' => 8500,
                'next_follow_up_at' => now()->addDays(3),
                'notes' => 'Interessato a CRM, sito web e supporto advertising.',
            ]
        );

        $opportunity = CrmOpportunity::updateOrCreate(
            ['title' => 'Progetto digital growth Azienda Lead'],
            [
                'crm_lead_id' => $lead->id,
                'assigned_to' => $agent->id,
                'stage' => 'proposal',
                'amount' => 8500,
                'probability' => 60,
                'expected_close_at' => now()->addWeeks(3),
                'status' => 'open',
                'notes' => 'Preparare proposta completa con sito e marketing.',
            ]
        );

        CrmActivity::updateOrCreate(
            ['title' => 'Follow-up proposta CRM'],
            [
                'crm_lead_id' => $lead->id,
                'crm_opportunity_id' => $opportunity->id,
                'assigned_to' => $agent->id,
                'created_by' => $admin->id,
                'type' => 'call',
                'due_at' => now()->addDays(2)->setTime(10, 30),
                'status' => 'open',
                'notes' => 'Verificare budget e tempi decisionali.',
            ]
        );

        collect([
            'agency_name' => 'Web Agency',
            'agency_email' => 'admin@agency.test',
            'brand_color' => '#0f172a',
            'pdf_header' => 'Web Agency - Contratti e preventivi',
            'pdf_footer' => 'Documento generato automaticamente',
            'standard_terms' => 'Condizioni standard della web agency.',
            'privacy_policy' => 'Privacy policy demo.',
            'otp_ttl_minutes' => 10,
            'otp_max_attempts' => 5,
        ])->each(fn ($value, $key) => Setting::updateOrCreate(['key' => $key], ['group' => 'agency', 'value' => $value]));
    }
}

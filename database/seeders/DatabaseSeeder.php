<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Client;
use App\Models\AgencyService;
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
            'manage settings',
            'view client area',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        $adminRole = Role::findOrCreate('admin');
        $operatorRole = Role::findOrCreate('operator');
        $clientRole = Role::findOrCreate('client');

        $adminRole->syncPermissions($permissions);
        $operatorRole->syncPermissions([
            'view dashboard',
            'manage clients',
            'manage services',
            'manage quotes',
            'manage contracts',
            'manage payments',
            'manage documents',
        ]);
        $clientRole->syncPermissions(['view client area']);

        $admin = User::updateOrCreate(
            ['email' => 'admin@agency.test'],
            ['name' => 'Admin Web Agency', 'password' => Hash::make('password')]
        );
        $admin->assignRole($adminRole);

        $operator = User::updateOrCreate(
            ['email' => 'operator@agency.test'],
            ['name' => 'Operatore Interno', 'password' => Hash::make('password')]
        );
        $operator->assignRole($operatorRole);

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
            ['client_id' => $demoClient->id, 'name' => 'Cliente Demo', 'password' => Hash::make('password')]
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
    }
}

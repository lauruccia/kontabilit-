<?php

namespace Tests\Feature;

use App\Models\Document;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SmokePagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_pages_render_without_server_errors(): void
    {
        $this->seed();

        $admin = User::where('email', 'admin@agency.test')->firstOrFail();

        $paths = [
            '/dashboard',
            '/admin/clients',
            '/admin/clients/1',
            '/admin/crm/leads',
            '/admin/crm/leads/1',
            '/admin/users',
            '/admin/users/1',
            '/admin/services',
            '/admin/services/1',
            '/admin/quotes',
            '/admin/quotes/1',
            '/admin/contracts',
            '/admin/contracts/1',
            '/admin/contract-templates',
            '/admin/contract-templates/1',
            '/admin/payments',
            '/admin/payments/1',
            '/admin/documents',
            '/admin/reminders',
            '/admin/messages',
            '/admin/settings',
        ];

        foreach ($paths as $path) {
            $this->actingAs($admin)->get($path)->assertOk();
        }
    }

    public function test_client_dashboard_renders_without_server_errors(): void
    {
        $this->seed();

        $client = User::where('email', 'cliente@example.test')->firstOrFail();

        $this->actingAs($client)->get('/client/dashboard')->assertOk();
    }

    public function test_document_download_is_authorized(): void
    {
        $this->seed();
        Storage::fake('local');
        Storage::put('documents/test.txt', 'ok');

        $admin = User::where('email', 'admin@agency.test')->firstOrFail();
        $document = Document::create([
            'client_id' => 1,
            'uploaded_by' => $admin->id,
            'name' => 'test.txt',
            'type' => 'internal',
            'visibility' => 'internal',
            'path' => 'documents/test.txt',
            'mime_type' => 'text/plain',
            'size' => 2,
        ]);

        $this->actingAs($admin)->get("/admin/documents/{$document->id}/download")->assertOk();
    }
}

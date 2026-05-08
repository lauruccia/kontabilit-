<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function edit(): View
    {
        return view('admin.settings.edit', [
            'settings' => Setting::pluck('value', 'key'),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'agency_name' => ['required', 'string', 'max:255'],
            'agency_email' => ['required', 'email', 'max:255'],
            'brand_color' => ['nullable', 'string', 'max:20'],
            'pdf_header' => ['nullable', 'string'],
            'pdf_footer' => ['nullable', 'string'],
            'standard_terms' => ['nullable', 'string'],
            'privacy_policy' => ['nullable', 'string'],
            'stripe_public_key' => ['nullable', 'string'],
            'stripe_secret_key' => ['nullable', 'string'],
            'otp_ttl_minutes' => ['required', 'integer', 'min:1', 'max:60'],
            'otp_max_attempts' => ['required', 'integer', 'min:1', 'max:10'],
        ]);

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['group' => 'agency', 'value' => $value]);
        }

        return back()->with('status', 'Impostazioni aggiornate.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Services\ActivityLogger;
use App\Services\OtpSignatureService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicContractSignatureController extends Controller
{
    public function show(string $token): View
    {
        $contract = $this->contract($token);
        return view('public.contract-signature.show', compact('contract'));
    }

    public function requestOtp(Request $request, string $token, OtpSignatureService $service, ActivityLogger $logger): RedirectResponse
    {
        $contract = $this->contract($token);
        $request->validate(['accepted_terms' => ['accepted']]);
        $service->sendOtp($contract);
        $logger->log('otp.generated', $contract, ['email' => $contract->client->email]);

        return back()->with('otp_sent', true)->with('status', 'OTP inviato via email.');
    }

    public function verify(Request $request, string $token, OtpSignatureService $service, ActivityLogger $logger): RedirectResponse
    {
        $contract = $this->contract($token);
        $data = $request->validate(['otp' => ['required', 'digits:6']]);

        if (! $service->sign($contract, $data['otp'], $request->ip(), $request->userAgent())) {
            return back()->withErrors(['otp' => 'OTP non valido o scaduto.'])->with('otp_sent', true);
        }

        $logger->log('contract.signed', $contract);

        return redirect()->route('public.contracts.show', $token)->with('status', 'Contratto firmato correttamente.');
    }

    private function contract(string $token): Contract
    {
        return Contract::with('client', 'items', 'signature')
            ->where('public_token', $token)
            ->where('public_token_expires_at', '>', now())
            ->firstOrFail();
    }
}

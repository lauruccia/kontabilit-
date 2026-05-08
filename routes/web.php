<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AgencyServiceController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\ContractController;
use App\Http\Controllers\Admin\ContractTemplateController;
use App\Http\Controllers\Admin\CrmLeadController;
use App\Http\Controllers\Admin\DocumentController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\QuoteController;
use App\Http\Controllers\Admin\ReminderController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\ClientAreaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicContractSignatureController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth', 'verified', 'role:admin|operator'])
    ->name('dashboard');

Route::middleware(['auth', 'verified', 'role:admin|operator'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('clients', ClientController::class);
        Route::post('crm/activities/{activity}/complete', [CrmLeadController::class, 'completeActivity'])->name('crm.activities.complete');
        Route::resource('crm/leads', CrmLeadController::class)->names('crm.leads');
        Route::resource('users', UserManagementController::class);
        Route::resource('services', AgencyServiceController::class)->parameters([
            'services' => 'service',
        ]);
        Route::get('quotes/{quote}/pdf', [QuoteController::class, 'pdf'])->name('quotes.pdf');
        Route::post('quotes/{quote}/send', [QuoteController::class, 'send'])->name('quotes.send');
        Route::post('quotes/{quote}/convert', [ContractController::class, 'fromQuote'])->name('quotes.convert');
        Route::resource('quotes', QuoteController::class);

        Route::get('contracts/{contract}/pdf', [ContractController::class, 'pdf'])->name('contracts.pdf');
        Route::post('contracts/{contract}/send', [ContractController::class, 'send'])->name('contracts.send');
        Route::post('contracts/{contract}/duplicate', [ContractController::class, 'duplicate'])->name('contracts.duplicate');
        Route::resource('contracts', ContractController::class);
        Route::resource('contract-templates', ContractTemplateController::class);

        Route::post('payments/{payment}/paid', [PaymentController::class, 'markPaid'])->name('payments.paid');
        Route::get('payments/{payment}/receipt', [PaymentController::class, 'receipt'])->name('payments.receipt');
        Route::resource('payments', PaymentController::class);

        Route::get('documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
        Route::resource('documents', DocumentController::class)->except(['edit', 'update']);

        Route::post('reminders/{reminder}/complete', [ReminderController::class, 'complete'])->name('reminders.complete');
        Route::resource('reminders', ReminderController::class);
        Route::resource('messages', MessageController::class)->only(['index', 'create', 'store', 'show', 'destroy']);
        Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
        Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
    });

Route::get('/sign/contracts/{token}', [PublicContractSignatureController::class, 'show'])->name('public.contracts.show');
Route::post('/sign/contracts/{token}/otp', [PublicContractSignatureController::class, 'requestOtp'])
    ->middleware('throttle:3,1')
    ->name('public.contracts.otp');
Route::post('/sign/contracts/{token}/verify', [PublicContractSignatureController::class, 'verify'])
    ->middleware('throttle:8,1')
    ->name('public.contracts.verify');

Route::middleware(['auth', 'verified', 'role:client'])
    ->prefix('client')
    ->name('client.')
    ->group(function () {
        Route::get('dashboard', [ClientAreaController::class, 'dashboard'])->name('dashboard');
        Route::post('messages', [ClientAreaController::class, 'storeMessage'])->name('messages.store');
        Route::post('documents', [ClientAreaController::class, 'uploadDocument'])->name('documents.store');
        Route::get('documents/{document}/download', [ClientAreaController::class, 'downloadDocument'])->name('documents.download');
    });

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->foreignId('quote_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('contract_template_id')->nullable()->constrained()->nullOnDelete();
            $table->string('number')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('starts_at')->nullable();
            $table->date('ends_at')->nullable();
            $table->boolean('auto_renewal')->default(false);
            $table->unsignedInteger('duration_months')->nullable();
            $table->decimal('one_time_amount', 12, 2)->default(0);
            $table->decimal('monthly_fee', 12, 2)->default(0);
            $table->decimal('annual_fee', 12, 2)->default(0);
            $table->string('payment_terms')->nullable();
            $table->longText('terms')->nullable();
            $table->longText('rendered_content')->nullable();
            $table->string('status')->default('draft')->index();
            $table->string('public_token', 80)->nullable()->unique();
            $table->timestamp('public_token_expires_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('signed_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};

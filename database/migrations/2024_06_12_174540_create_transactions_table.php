<?php

declare(strict_types=1);

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
        Schema::create('transactions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('debit_id')->constrained('accounts');
            $table->foreignId('credit_id')->constrained('accounts');
            $table->float('value');
            $table->text('text');
            $table->date('timestamp');
            $table->foreignId('claim_id')->nullable()->constrained('transactions');
            $table->string('group_uid')->nullable();
            $table->foreignId('person_id')->nullable()->constrained('people');
            $table->string('currency');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};

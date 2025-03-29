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
        Schema::create('streaks', function (Blueprint $table): void {
            $table->id();
            $table->integer('day');
            $table->date('first');
            $table->date('last')->nullable();
            $table->string('name');
            $table->float('value');
            $table->string('currency');
            $table->foreignId('debit_id')->constrained('accounts');
            $table->foreignId('credit_id')->constrained('accounts');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('streaks');
    }
};

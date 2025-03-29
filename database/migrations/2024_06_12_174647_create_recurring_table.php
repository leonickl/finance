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
        Schema::create('recurring', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('account_id')->constrained();
            $table->integer('year');
            $table->integer('month');
            $table->boolean('jump');
            $table->boolean('finished');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recurring');
    }
};

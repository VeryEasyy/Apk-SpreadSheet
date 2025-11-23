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
        Schema::create('report__locks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sheets_id')->constrained('report__sheets')->onDelete('cascade');
            $table->string('cell', 10);
            $table->foreignId('locked_by')->constrained('users')->onDelete('cascade');
            $table->timestamp('locked_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report__locks');
    }
};

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
        Schema::create('language_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('language_id')->constrained()->onDelete('cascade');
            $table->string('level')->nullable();       // es. base, intermedio, avanzato, madrelingua
            $table->string('certificate')->nullable(); // es. TOEFL, DELE, etc.
            $table->timestamps();

            $table->unique(['user_id', 'language_id']); // per evitare duplicati
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('language_user');
    }
};

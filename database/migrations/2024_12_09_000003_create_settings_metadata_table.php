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
        Schema::create('settings_metadata', function (Blueprint $table) {
            $table->id()->comment('Rekord azonosító');
            $table->string('key')->unique()->comment('Kulcs');
            $table->string('label')->comment('Emberi olvasható címke'); // Emberi olvasható címke.
            $table->text('description')->nullable()->comment('Beállítás részletes leírása'); // Beállítás részletes leírása.
            $table->enum('type', ['string', 'integer', 'boolean', 'json'])->default('string')->comment('Érték típusa'); // Érték típusa.
            $table->enum('level', ['application', 'company'])->default('application')->comment('Szint'); // Szint.
            $table->boolean('is_required')->default(false)->comment('Kötelező-e'); // Kötelező-e.
            $table->text('default_value')->nullable()->comment('Alapértelmezett érték'); // Alapértelmezett érték.
            $table->text('validation_rules')->nullable()->comment('Validációs szabályok (JSON formátumban)'); // Validációs szabályok (JSON formátumban).
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings_metadata');
    }
};

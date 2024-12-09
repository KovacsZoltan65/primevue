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
            $table->id();
            $table->string('key')->unique();
            $table->string('type')->default('string'); // Pl. string, boolean, integer.
            $table->text('description')->nullable();
            $table->text('default_value')->nullable();
            $table->enum('scope', ['application', 'company', 'both'])->default('both');
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

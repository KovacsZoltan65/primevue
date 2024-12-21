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
        Schema::create('company_settings', function (Blueprint $table) {
            $table->id()->comment('Rekord azonosító');
            $table->foreignId('company_id')->constrained()->onDelete('cascade')->comment('Cég azonosító');
            $table->string('key')->comment('Kulcs');
            $table->text('value')->nullable()->comment('Érték');
            $table->enum('active', [0,1])->default(1)->index()->comment('Aktív');
            $table->timestamps();

            $table->unique(['company_id', 'key']); // Egyedi cég + kulcs páros.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_settings');
    }
};

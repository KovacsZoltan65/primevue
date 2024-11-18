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
        Schema::create('settings', function (Blueprint $table) {
            $table->id()->comment('Rekord azonosító');
            
            $table->string('name')->comment('Beállítás neve');
            $table->string('default_value')->nullable()->comment('Beállítás alap értéke');
            $table->boolean('is_active')->default(true)->comment('Aktív jelző');
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};

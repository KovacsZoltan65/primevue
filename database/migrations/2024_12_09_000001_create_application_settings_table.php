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
        Schema::create('app_settings', function (Blueprint $table) {
            $table->id()->comment('Rekord azonosító');
            $table->string('key')->unique()->comment('Kulcs');
            $table->text('value')->nullable()->comment('Érték');
            $table->enum('active', [0,1])->default(1)->index()->comment('Aktív');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_settings');
    }
};

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
        Schema::create('cities', function (Blueprint $table) {
            $table->id()->comment('Rekord azonosító');
            
            $table->integer('region_id')->comment('Régió azonosító');
            $table->integer('country_id')->comment('Ország azonosító');
            $table->decimal('latitude', 10, 2)->comment('Szélesség');
            $table->decimal('longitude', 10, 2)->comment('Hosszúság');
            $table->string('name', 255)->comment('Név');
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
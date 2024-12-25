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
        Schema::create('hierarchy', function (Blueprint $table) {
            $table->id()->comment('Rekord azonosító');
            $table->unsignedBigInteger('parent_id')->comment('Szülő entitás ID');
            $table->unsignedBigInteger('child_id')->comment('Gyermek entitás ID');
            
            $table->timestamps();
            //$table->softDeletes()->comment('Lágy törlés dátuma');

            $table->foreign('parent_id')->references('id')->on('entities')->cascadeOnDelete()->comment('Szülő entitás törlés esetén cascadelés.');
            $table->foreign('child_id')->references('id')->on('entities')->cascadeOnDelete()->comment('Gyermek entitás törlés esetén cascadelés.');

            $table->unique(['parent_id', 'child_id'], 'unique_parent_child')->comment('Egyediség biztosítása a szülő-gyermek kapcsolatokra.');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hierarchy');
    }
};

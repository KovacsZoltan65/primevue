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
        Schema::create('entity_rel', function (Blueprint $table) {
            $table->id()->comment('Rekord azonosító');
            $table->unsignedBigInteger('parent_id')->comment('Szülő entitás ID');
            $table->unsignedBigInteger('child_id')->comment('Gyermek entitás ID');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('parent_id')->references('id')->on('entities')->onDelete('cascade');
            $table->foreign('child_id')->references('id')->on('entities')->onDelete('cascade');

            $table->unique(['parent_id', 'child_id'], 'unique_parent_child');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entity_rel');
    }
};

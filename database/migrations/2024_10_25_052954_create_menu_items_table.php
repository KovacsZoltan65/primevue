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
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id()->comment('Rekord azonosító');
            $table->string('label')->comment('Felirat');
            $table->string('icon')->default('')->comment('Ikon');
            $table->string('url')->nullable()->comment('URL');
            $table->integer('default_weight')->default(0)->comment('Alapértelmezett súly');
            $table->unsignedBigInteger('parent_id')->nullable()->comment('Szülő menü azonosítója');
            $table->timestamps();
            
            $table->foreign('parent_id')->references('id')->on('menu_items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};

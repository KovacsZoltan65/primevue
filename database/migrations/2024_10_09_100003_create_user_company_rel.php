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
        Schema::create('user_company_rel', function(Blueprint $table) {
            $table->id()->comment('Rekord azonosító');

            $table->unsignedBigInteger('user_id')->index()->comment('Személy azonosító. A kapcsolódó Persons azonosítója.');
            $table->unsignedBigInteger('company_id')->index()->comment('Cég azonosító. A kapcsolódó Companies azonosítója.');

            // Egyedi kulcs a párosításokra
            $table->unique(['user_id', 'company_id'], 'user_company_unique');

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();

            $table->timestamps();
            $table->softDeletes()->comment('Lágy törlés dátuma');
        });

        //Schema::create('company_entity', function(Blueprint $table) {
        //    $table->id()->comment('Rekord azonosító');

        //    $table->unsignedBigInteger('company_id')->comment('Cég azonosító. A kapcsolódó Companies azonosítója.');
        //    $table->unsignedBigInteger('entity_id')->unique()->comment('Entitás azonosító. A kapcsolódó Entities azonosítója.');

        //    $table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();
        //    $table->foreign('entity_id')->references('id')->on('entities')->cascadeOnDelete();

        //    $table->timestamps();
        //    $table->softDeletes();
        //});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_company_rel');
        //Schema::dropIfExists('company_entity');
    }
};

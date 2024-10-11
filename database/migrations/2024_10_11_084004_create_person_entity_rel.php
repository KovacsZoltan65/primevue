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
        Schema::create('person_company', function(Blueprint $table) {
            $table->id()->comment('Rekord azonosító');
            
            $table->unsignedBigInteger('person_id')->comment('Személy azonosító. A kapcsolódó Persons azonosítója.');
            $table->unsignedBigInteger('company_id')->comment('Cég azonosító. A kapcsolódó Companies azonosítója.');
            
            $table->foreign('person_id')->references('id')->on('persons')->cascadeOnDelete();
            $table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();
            
            $table->timestamps();
            $table->softDeletes();
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
        Schema::dropIfExists('person_company');
        Schema::dropIfExists('company_entity');
    }
};

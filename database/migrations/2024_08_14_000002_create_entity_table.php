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
        Schema::create('entities', function (Blueprint $table) {
            $table->id()->comment('Rekord azonosító');
            
            $table->string('name', 255)->comment('Dolgozó neve');
            $table->string('email', 255)->comment('Dolgozó email címe');
            
            $table->unsignedSmallInteger('person_id')->comment('Személy azonosító');
            
            $table->unsignedBigInteger('company_id')->comment('Cég azonosító');
            $table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();
            
            //$table->integer('active')->default(1)->index()->comment('Aktív');
            $table->enum('active', [0,1])->default(1)->index()->comment('Aktív');
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entities');
    }
};

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
            
            $table->string('name', 255)->comment('Név');
            $table->string('email', 255)->comment('Email cím');
            $table->timestamp('start_date')->comment('Belépés dátuma');
            $table->timestamp('end_date')->comment('Kilépés dátuma');
            $table->timestamp('last_export')->nullable()->comment('Utoldó export');
            
            $table->unsignedBigInteger('company_id')->comment('Cég azonosító.');

            $table->enum('active', [0,1])->default(1)->index()->comment('Aktív');

            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entities');
    }

};
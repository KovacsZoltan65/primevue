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
        Schema::create('setting_company_rel', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('settings_id')->comment('Beállítás azonosítója');
            $table->unsignedBigInteger('companies_id')->comment('Cég azonosítója');
            $table->string('value')->comment('Beállítás értéke');
            
            $table->foreign('settings_id')->references('id')->on('settings')->cascadeOnDelete();
            $table->foreign('companies_id')->references('id')->on('companies')->cascadeOnDelete();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setting_company_rel');
    }
};

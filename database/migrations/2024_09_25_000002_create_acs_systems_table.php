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
        Schema::create('acs_systems', function (Blueprint $table) {
            $table->increments('id')->comment('Rekord azonosító');

            $table->string('name', 255)->unique()->comment('ACS rendszer neve');

            $table->boolean('active')->default(1)->index()->comment('Aktív');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acs_systems');
    }
};

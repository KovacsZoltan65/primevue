<?php

use App\Models\City;
use App\Models\Country;
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
        Schema::create('companies', function (Blueprint $table) {
            $table->id()->comment('Rekord azonosító');
            $table->string('name')->comment('Név');

            //$table->string('country')->comment('Ország');
            $table->foreignIdFor(Country::class)->constrained()->cascadeOnDelete();
            //$table->string('city')->comment('Város');
            $table->foreignIdFor(City::class)->constrained()->cascadeOnDelete();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};

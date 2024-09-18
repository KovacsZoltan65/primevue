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
            $table->string('name')->index()->comment('Név');

            $table->unsignedSmallInteger('country_id')->comment('Ország azonosító. A kapcsolódó ország azonosítója.');
            $table->unsignedSmallInteger('region_id')->comment('Megye / Régió azonosító. A kapcsolódó megye / régió azonosítója.');

            //$table->foreignIdFor(Country::class)->constrained()->cascadeOnDelete();
            //$table->foreign('country_id')->references('id')->on('countries')->cascadeOnDelete();
            //$table->foreignIdFor(City::class)->constrained()->cascadeOnDelete();
            //$table->foreign('city_id')->references('id')->on('cities')->cascadeOnDelete();


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

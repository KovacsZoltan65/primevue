<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Adatbázistábla létrehozása.
     *
     * Ez a migráció létrehozza a régiók tábláját, amely az országok régióit tárolja.
     * A régiók táblázata a következő oszlopokat tartalmazza:
     * - id: a régió egyedi azonosítója
     * - név: a régió neve
     * - kód: a régió kódja
     * - country_id: az országok tábla idegen kulcsa
     * - aktív: egy logikai érték, amely jelzi, hogy a régió aktív-e vagy sem
     *
     * @return érvénytelen
     */
    public function up()
    {
        Schema::create('regions', function (Blueprint $table) {
            $table->id()->comment('Rekord azonosító. Egyedi azonosító a rekordhoz.');
            $table->string('name', 255)->index()->comment('Név. A régió neve.');
            $table->string('code', 10)->index()->comment('Kód. A régió kódja.');

            $table->unsignedBigInteger('country_id')->comment('Ország azonosító. A kapcsolódó ország azonosítója.');

            $table->integer('active')->default(1)->comment('Aktív.')->index();

            $table->foreign('country_id')->references('id')->on('countries')->cascadeOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('regions');
    }
};

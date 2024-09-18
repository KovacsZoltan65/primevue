<?php

use App\Models\Country;
use App\Models\Region;
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
        Schema::create('cities', function (Blueprint $table) {
            
            /**
             * Rekord azonosító. Egyedi azonosító a rekordhoz.
             *
             * @var int
             */
            $table->id()
                ->comment('Rekord azonosító. Egyedi azonosító a rekordhoz.');

            /**
             * Szélesség. A város szélességi koordinátája.
             *
             * @var float|null
             */
            $table->decimal('latitude', 10, 2)
                ->nullable()
                ->comment('Szélesség. A város szélességi koordinátája.');

            /**
             * Hosszúság. A város hosszúsági koordinátája.
             *
             * @var float|null
             */
            $table->decimal('longitude', 10, 2)
                ->nullable()
                ->comment('Hosszúság. A város hosszúsági koordinátája.');

            /**
             * Név. A város neve.
             *
             * @var string
             */
            $table->string('name', 255)
                ->index()
                ->comment('Név. A város neve.');

            /**
             * Az ország, amelyhez a város tartozik.
             * A kapcsolatot a country_id mező tartja.
             */
            //$table->foreignIdFor(Country::class)->constrained()->cascadeOnDelete();
            $table->foreign('country_id')
                ->references('id')
                ->on('countries')
                ->cascadeOnDelete();

            /**
             * A régió, amelyhez a város tartozik.
             * A kapcsolatot a region_id mező tartja.
             */
            //$table->foreignIdFor(Region::class)->constrained()->cascadeOnDelete();
            $table->foreign('region_id')
                ->references('id')
                ->on('regions')
                ->cascadeOnDelete();

            /**
             * Aktív. 0 érték esetén a város inaktív.
             *
             * @var int
             */
            $table->integer('active')
                ->default(1)
                ->index()
                ->comment('Aktív. 0 érték esetén a város inaktív.');

            /**
             * A rekord létrehozásának időpontja.
             *
             * @var \Illuminate\Support\Carbon
             */
            $table->timestamps();

            /**
             * A rekord törlésének időpontja.
             * A softDeletes() segítségével a rekordok nem kerülnek
             * fizikailag törlésre, hanem csak az deleted_at mezőbe
             * kerül az aktuális időpont.
             *
             * @var \Illuminate\Support\Carbon
             */
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};

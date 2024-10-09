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
            $table->string('name', 255)->collation('utf8mb3_general_ci')->comment('Cégnév');
            $table->string('directory', 255)->collation('utf8mb3_general_ci')->comment('Könyvtár');

            $table->string('registration_number', 255)->collation('utf8mb3_general_ci')->comment('Regisztrációs szám');
            $table->string('tax_id', 255)->collation('utf8mb3_general_ci')->comment('Adószám');

            $table->unsignedSmallInteger('country_id')->comment('Ország azonosító. A kapcsolódó ország azonosítója.');
            $table->unsignedSmallInteger('city_id')->comment('Város azonosító. A kapcsolódó megye / régió azonosítója.');
            $table->string('address', 255)->collation('utf8mb3_general_ci')->comment('Cím');

            $table->integer('active')->default(1)->comment('Aktív.')->index();

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

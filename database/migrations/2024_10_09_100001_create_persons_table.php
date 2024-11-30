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
        Schema::create('persons', function (Blueprint $table) {
            $table->id()->comment('Rekord azonosító');

            $table->string('name')->index()->collation('utf8mb3_general_ci')->comment('Név');
            $table->string('email')->index()->collation('utf8mb3_general_ci')->comment('E-mail cím');
            $table->string('password')->collation('utf8mb3_general_ci')->comment('Jelszó');
            $table->string('language', 5)->collation('utf8mb3_general_ci')->default('hu')->comment('Nyelv');

            $table->date('birthdate')->nullable()->comment('Születési dátum');
            
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
        Schema::dropIfExists('persons');
    }
};

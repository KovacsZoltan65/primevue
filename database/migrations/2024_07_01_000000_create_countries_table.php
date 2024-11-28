<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id()->comment('Rekord azonosító');
            $table->string('name', 255)->index()->unique('name')->comment('Név');
            $table->string('code', 10)->index()->comment('Kód');
            
            //$table->integer('active')->default(1)->index()->comment('Aktív');
            $table->enum('active', [0,1])->default(1)->index()->comment('Aktív');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('countries');
    }
};

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
        Schema::create('regions', function (Blueprint $table) {
            $table->increments('id')->comment('Rekord azonosító');
            $table->string('name', 255)->comment('Név');
            $table->string('code', 10)->comment('Kód');
            $table->unsignedSmallInteger('country_id');
            $table->integer('active')->default(1)->comment('Aktív')->index();

            $table->index(['country_id', 'name'], 'country_name');
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

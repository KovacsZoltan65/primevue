<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('entity_calendars', function(Blueprint $table) {
            $table->increments('id');

			$table->unsignedBigInteger('entity_id')->index()->comment('Entitás azonosító');
			$table->unsignedBigInteger('calendar_id')->index()->comment('Naptár azonosító');

            $table->timestamps();
			$table->softDeletes()->comment('Lágy törlés dátuma');

			// Egyedi kulcs az entity_id + calendar_id párosra, hogy ne legyen duplikáció
			$table->unique(['entity_id', 'calendar_id']);

			// Külső kulcsok (foreign key constraints)
			$table->foreign('calendar_id')->references('id')->on('calendars')->cascadeOnDelete();
			$table->foreign('entity_id')->references('id')->on('entities')->cascadeOnDelete();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('entity_calendars');
	}
};

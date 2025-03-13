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

			$table->unsignedBigInteger('calendar_id')->index()->comment('Naptár azonosító');

            $table->timestamps();
			$table->softDeletes()->comment('Lágy törlés dátuma');

			$table->foreign('calendar_id')->references('id')->on('calendars')->cascadeOnDelete();
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

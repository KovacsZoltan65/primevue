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
		Schema::create('entities_shifts', function(Blueprint $table) {
            $table->increments('id');

			



			

            $table->timestamps();
			$table->softDeletes()->comment('Lágy törlés dátuma');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('entities_shifts');
	}
};

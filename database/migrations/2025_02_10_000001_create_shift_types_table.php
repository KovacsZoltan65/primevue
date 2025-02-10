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
		Schema::create('shift_types', function(Blueprint $table) {
            $table->increments('id');

			$table->unsignedBigInteger('company_id')->index()->comment('Cég azonosító.');
			$table->string('code', 10)->index()->comment('Kód');
			$table->string('name', 255)->index()->unique('name')->comment('Név');

			$table->time('trunk_time_start')->comment('Törzsidő kezdés');
			$table->time('trunk_time_end')->comment('Törzsidő vége');

			$table->time('edge_time_start')->comment('Peremidő kezdés');
			$table->time('edge_time_end')->comment('Peremidő vége');

			$table->boolean('active')->default(1)->index()->comment('Aktív');

			$table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();

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
		Schema::drop('shift_types');
	}
};

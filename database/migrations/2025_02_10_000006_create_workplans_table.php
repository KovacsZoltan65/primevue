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
		Schema::create('workplans', function(Blueprint $table) {
            $table->increments('id')->comment('Rekord azonosító');
			$table->string('name', 255)->unique()->collation('utf8mb3_unicode_ci')->comment('Munkarend neve');
			
			$table->unsignedBigInteger('company_id')->index()->comment('Cég azonosító.');
			$table->unsignedInteger('acs_id')->nullable()->comment('Beléptető rendszer használata');

			$table->boolean('active')->default(1)->index()->comment('Aktív');

            $table->timestamps();
			$table->softDeletes()->comment('Lágy törlés dátuma');

			$table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();
			$table->foreign('acs_id')->references('id')->on('acs_systems')->cascadeOnDelete();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('workplans');
	}
};

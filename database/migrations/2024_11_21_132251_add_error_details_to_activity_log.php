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
        /*
        Schema::connection(config('activitylog.database_connection'))
            ->table(config('activitylog.table_name'), function (Blueprint $table) {

            $table->string('route')->nullable()->after('properties'); // Az útvonal
            $table->string('url')->nullable()->after('route');       // A teljes URL
            $table->string('user_agent')->nullable()->after('url');  // A böngésző információi
            $table->uuid('unique_error_id')->nullable()->after('user_agent')->index(); // Egyedi hibaazonosító

        });
        */
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        /*
        Schema::connection(config('activitylog.database_connection'))
            ->table(config('activitylog.table_name'), function (Blueprint $table) {

            $table->dropColumn('route');
            $table->dropColumn('url');
            $table->dropColumn('user_agent');
            $table->dropColumn('unique_error_id');

        });
        */
    }
};

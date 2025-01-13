<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSourceDomainColumnToHQActivityLogTable extends Migration
{
    public function up()
    {
        Schema::connection(config('activitylog.database_connection'))->table('hq_activity_log', function (Blueprint $table) {
            $table->uuid('source_domain')->nullable()->after('batch_uuid');
        });
    }

    public function down()
    {
        Schema::connection(config('activitylog.database_connection'))->table('hq_activity_log', function (Blueprint $table) {
            $table->dropColumn('source_domain');
        });
    }
}

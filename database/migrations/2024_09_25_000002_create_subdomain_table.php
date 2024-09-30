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
        Schema::create('subdomains', function (Blueprint $table) {
            $table->increments('id')->comment('Rekord azonosító');
            $table->string('subdomain')->collation('utf8mb3_general_ci')->index()->comment('Példány');
            $table->string('url')->collation('utf8mb3_general_ci')->comment('Példány url-je');
            $table->string('name')->collation('utf8mb3_general_ci')->index()->comment('Aldomain neve');
            $table->string('db_host', 125)->collation('utf8mb3_general_ci')->default('localhost')->comment('Adatbázis szerver címe');
            $table->integer('db_port')->default(3306)->comment('Adatbázis port');
            $table->string('db_name')->collation('utf8mb3_general_ci')->comment('Adatbázis neve');
            $table->string('db_user')->collation('utf8mb3_general_ci')->comment('Adatbázis felhasználó');
            $table->string('db_password')->collation('utf8mb3_general_ci')->comment('Adatbázis jelszó');
            
            $table->enum('notification', [0,1])->default(1)->comment('Értesítés');
            
            $table->unsignedInteger('state_id')->default(1)->comment('Állapot azonosító');
            $table->foreign('state_id')->references('id')->on('subdomain_states')->cascadeOnDelete();
            
            $table->enum('is_mirror', ['0','1'])->default(0)->comment('Tükör adatbázis');
            
            $table->enum('sso', ['0','1'])->default(0)->comment('SSO');
            
            $table->unsignedBigInteger('acs_id')->default(0)->comment('Beléptető rendszer használata');
            //$table->foreign('acs_id')->references('id')->on('access_control_system')->cascadeOnDelete();
            
            $table->enum('active', [0,1])->default(1)->index()->comment('Aktív');
            
            $table->timestamp('last_export')->nullable()->comment('Utoldó export');
            
            $table->timestamps();
            $table->softDeletes();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subdomains');
    }
};

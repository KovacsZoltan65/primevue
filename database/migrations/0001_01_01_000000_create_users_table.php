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
        Schema::create('users', function (Blueprint $table) {
            $table->id()->comment('Rekord azonosító');
            $table->string('name')->comment('Felhasználó neve');
            $table->string('email')->unique()->comment('Felhasználó emali címe');
            $table->timestamp('email_verified_at')->nullable()->comment('Email ellenőrizve ekkor');
            $table->string('password')->comment('Jelszó');
            $table->string('language', 5)->default('hu')->comment('Nyelv');
            $table->date('birthdate')->nullable()->comment('Születési dátum');
            $table->rememberToken()->comment('Emlékeztető');
            $table->boolean('active')->default(1)->comment('Aktív jelző');
            $table->foreignId('current_team_id')->nullable();
            $table->string('profile_photo_path', 2048)->nullable();

            $table->timestamps();
            $table->softDeletes()->comment('Lágy törlés dátuma');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};

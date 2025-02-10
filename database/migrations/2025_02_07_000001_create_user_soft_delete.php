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
        Schema::create('user_soft_delete_trigger', function (Blueprint $table) {
            DB::unprepared('CREATE TRIGGER after_user_soft_delete AFTER UPDATE ON users FOR EACH ROW
            BEGIN
                IF OLD.deleted_at IS NULL AND NEW.deleted_at IS NOT NULL THEN
                    #UPDATE entities SET deleted_at = NOW() WHERE user_id = NEW.id;
                END IF
            END');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_soft_delete');
    }
};

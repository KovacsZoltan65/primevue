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
        Schema::create('entity_soft_delete', function (Blueprint $table) {
            \DB::unprepared('CREATE TRIGGER after_entities_soft_delete AFTER UPDATE ON entities FOR EACH ROW
             BEGIN
                 IF OLD.deleted_at IS NULL AND NEW.deleted_at IS NOT NULL THEN
                     #UPDATE hierarchy SET deleted_at = NOW() WHERE parent_id = NEW.id OR child_id = NEW.id;
                 END IF;
             END');
        });
        */
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entity_soft_delete');
    }
};

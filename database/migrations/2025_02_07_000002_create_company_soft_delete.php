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
        Schema::create('company_soft_delete', function (Blueprint $table) {
            \DB::unprepared('CREATE TRIGGER after_companies_soft_delete AFTER UPDATE ON companies FOR EACH ROW
            BEGIN
                IF OLD.deleted_at IS NULL AND NEW.deleted_at IS NOT NULL THEN
                    #UPDATE entities SET deleted_at = NOW() WHERE company_id = NEW.id;
                END IF;
            END');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_soft_delete');
    }
};

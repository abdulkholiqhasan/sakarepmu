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
        if (! Schema::hasTable('media')) {
            return;
        }

        // If the column exists and is not a UUID, drop it and re-create as uuid.
        Schema::table('media', function (Blueprint $table) {
            if (Schema::hasColumn('media', 'user_id')) {
                // Dropping a column and re-adding is simpler than altering types and
                // avoids requiring doctrine/dbal.
                $table->dropColumn('user_id');
            }
        });

        Schema::table('media', function (Blueprint $table) {
            $table->uuid('user_id')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('media')) {
            return;
        }

        Schema::table('media', function (Blueprint $table) {
            if (Schema::hasColumn('media', 'user_id')) {
                $table->dropColumn('user_id');
            }
        });

        Schema::table('media', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->index();
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

/**
 * Stores `media` rows on Supabase Postgres while the default connection (e.g. Neon) holds users, etc.
 *
 * - Run: php artisan migrate  (migration batches are recorded on the default DB; DDL runs on Supabase)
 * - uploaded_by matches users.id on the primary DB — no cross-database foreign key
 * - If you already have `media` on the primary DB from an older migration, copy rows to Supabase then drop
 *   the old table when ready (not automated here).
 */
return new class extends Migration {
    private function supabaseConfigured(): bool
    {
        $c = config('database.connections.supabase');

        return ! empty($c['url']) || ! empty($c['host']);
    }

    public function up(): void
    {
        if (! $this->supabaseConfigured()) {
            Log::warning('Skipping Supabase media migration: set DB_SUPABASE_URL or DB_SUPABASE_HOST (+ DB_SUPABASE_* credentials).');

            return;
        }

        if (Schema::connection('supabase')->hasTable('media')) {
            return;
        }

        Schema::connection('supabase')->create('media', function (Blueprint $table) {
            $table->id();
            $table->string('filename');
            $table->string('original_name');
            $table->string('path');
            $table->string('disk')->default('public');
            $table->string('mime_type');
            $table->bigInteger('size');
            /** Same numeric id as users.id on the primary DB (no FK across databases). */
            $table->unsignedBigInteger('uploaded_by');
            $table->index('uploaded_by');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        if (! $this->supabaseConfigured()) {
            return;
        }

        Schema::connection('supabase')->dropIfExists('media');
    }
};

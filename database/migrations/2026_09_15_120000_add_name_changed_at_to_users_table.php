<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration penambahan kolom name_changed_at pada tabel users.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'name_changed_at')) {
                $table->timestamp('name_changed_at')->nullable()->after('name');
            }
        });
    }

    /**
     * Balikkan migration (rollback).
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'name_changed_at')) {
                $table->dropColumn('name_changed_at');
            }
        });
    }
};

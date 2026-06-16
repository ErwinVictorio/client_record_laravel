<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            if (!Schema::hasColumn('clients', 'supporting_document_paths')) {
                $table->json('supporting_document_paths')->nullable()->after('supporting_document_path');
            }
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            if (Schema::hasColumn('clients', 'supporting_document_paths')) {
                $table->dropColumn('supporting_document_paths');
            }
        });
    }
};

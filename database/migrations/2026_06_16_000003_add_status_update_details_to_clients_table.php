<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            if (!Schema::hasColumn('clients', 'supporting_document_path')) {
                $table->string('supporting_document_path')->nullable()->after('bank_account_number');
            }

            if (!Schema::hasColumn('clients', 'vehicle_specifications')) {
                $table->json('vehicle_specifications')->nullable()->after('supporting_document_path');
            }
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            if (Schema::hasColumn('clients', 'supporting_document_path')) {
                $table->dropColumn('supporting_document_path');
            }

            if (Schema::hasColumn('clients', 'vehicle_specifications')) {
                $table->dropColumn('vehicle_specifications');
            }
        });
    }
};

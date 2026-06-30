<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('client_record_for_maintenance_and_repairs', function (Blueprint $table) {
            $table->json('vehicle_specifications')->nullable()->after('date_sold');
        });
    }

    public function down(): void
    {
        Schema::table('client_record_for_maintenance_and_repairs', function (Blueprint $table) {
            $table->dropColumn('vehicle_specifications');
        });
    }
};

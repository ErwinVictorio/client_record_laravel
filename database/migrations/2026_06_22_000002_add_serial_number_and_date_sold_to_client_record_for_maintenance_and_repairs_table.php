<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('client_record_for_maintenance_and_repairs', function (Blueprint $table) {
            $table->string('serial_number')->nullable()->after('job_order_number');
            $table->date('date_sold')->nullable()->after('serial_number');
        });
    }

    public function down(): void
    {
        Schema::table('client_record_for_maintenance_and_repairs', function (Blueprint $table) {
            $table->dropColumn(['serial_number', 'date_sold']);
        });
    }
};

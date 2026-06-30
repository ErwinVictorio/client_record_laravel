<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('after_sales_records', function (Blueprint $table) {
            $table->foreignId('maintenance_record_id')
                ->nullable()
                ->after('client_id')
                ->constrained('client_record_for_maintenance_and_repairs')
                ->nullOnDelete();
        });

        DB::table('after_sales_records')
            ->where('service_type', 'Other')
            ->whereNull('maintenance_record_id')
            ->orderBy('id')
            ->each(function ($afterSalesRecord): void {
                $maintenanceRecordId = DB::table('client_record_for_maintenance_and_repairs')
                    ->where('job_order_number', $afterSalesRecord->job_order_number)
                    ->value('id');

                if ($maintenanceRecordId) {
                    DB::table('after_sales_records')
                        ->where('id', $afterSalesRecord->id)
                        ->update(['maintenance_record_id' => $maintenanceRecordId]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('after_sales_records', function (Blueprint $table) {
            $table->dropConstrainedForeignId('maintenance_record_id');
        });
    }
};

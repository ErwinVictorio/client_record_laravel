<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('client_record_for_maintenance_and_repairs', function (Blueprint $table) {
            $table->string('job_order_number')->nullable()->change();
        });
    }

    public function down(): void
    {
        DB::table('client_record_for_maintenance_and_repairs')
            ->whereNull('job_order_number')
            ->update(['job_order_number' => '']);

        Schema::table('client_record_for_maintenance_and_repairs', function (Blueprint $table) {
            $table->string('job_order_number')->nullable(false)->change();
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $duplicate = DB::table('after_sales_records')
            ->selectRaw('LOWER(TRIM(job_order_number)) AS normalized_job_order_number, COUNT(*) AS total')
            ->whereNotNull('job_order_number')
            ->where('job_order_number', '!=', '')
            ->groupBy('normalized_job_order_number')
            ->havingRaw('COUNT(*) > 1')
            ->first();

        if ($duplicate) {
            throw new \RuntimeException(
                "Cannot add the unique JO Number index because '{$duplicate->normalized_job_order_number}' is already used {$duplicate->total} times. Resolve legacy duplicates first."
            );
        }

        Schema::table('after_sales_records', function (Blueprint $table) {
            $table->unique('job_order_number', 'after_sales_records_job_order_number_unique');
        });
    }

    public function down(): void
    {
        Schema::table('after_sales_records', function (Blueprint $table) {
            $table->dropUnique('after_sales_records_job_order_number_unique');
        });
    }
};

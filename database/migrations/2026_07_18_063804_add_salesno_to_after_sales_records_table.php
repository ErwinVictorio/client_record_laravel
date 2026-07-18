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
        Schema::table('after_sales_records', function (Blueprint $table) {
            $table->string('salesList_no')->nullable()->after('job_order_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('after_sales_records', function (Blueprint $table) {
            //
        });
    }
};

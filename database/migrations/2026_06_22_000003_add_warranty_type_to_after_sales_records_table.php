<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('after_sales_records', function (Blueprint $table) {
            $table->string('warranty_type')->nullable()->after('service_type');
        });
    }

    public function down(): void
    {
        Schema::table('after_sales_records', function (Blueprint $table) {
            $table->dropColumn('warranty_type');
        });
    }
};

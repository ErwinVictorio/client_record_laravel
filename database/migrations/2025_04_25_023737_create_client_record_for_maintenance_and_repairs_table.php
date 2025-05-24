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
        Schema::create('client_record_for_maintenance_and_repairs', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('address');
            $table->string('email');
            $table->string('bank_account_number')->nullable();
            $table->string('contact_number');
            $table->string('contact_person');
            $table->string('contact_number_person');
            $table->string('job_order_number');
            $table->foreignId('salesmanId')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_record_for_maintenance_and_repairs');
    }
};

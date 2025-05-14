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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('contact_number');
            $table->string('email')->unique();
            $table->string('address');
            $table->string('salesList_no')->nullable(); // new Add;
            $table->string('contact_person');
            $table->string('contact_number_person');
            $table->string('bank_account_number')->nullable();
            $table->string('item_name')->nullable();
            $table->string('model_number')->nullable();
            $table->string('specification')->nullable();
            $table->integer('quantity')->nullable();
            $table->foreignId('salesman_id')->constrained('users')->onDelete('cascade'); 
            $table->string('status')->default('Pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};

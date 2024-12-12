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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_id');
            $table->decimal('loan_amount', 15, 2);
            $table->decimal('interest_rate', 5, 2)->nullable();
            $table->unsignedInteger('loan_term')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected']);
            $table->decimal('monthly_installment', 15, 2)->nullable();
            // $table->timestamp('rejected_at')->nullable();
            // $table->timestamp('approved_at')->nullable();


            $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};

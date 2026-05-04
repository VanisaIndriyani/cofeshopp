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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('invoice')->unique();
            $table->foreignId('table_id')->constrained('tables')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('created_by_user_id')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();

            $table->string('customer_name');
            $table->text('customer_note')->nullable();

            $table->string('status')->default('pending');

            $table->unsignedInteger('subtotal')->default(0);
            $table->decimal('tax_percent', 5, 2)->default(0);
            $table->unsignedInteger('tax_amount')->default(0);
            $table->decimal('service_percent', 5, 2)->default(0);
            $table->unsignedInteger('service_amount')->default(0);
            $table->unsignedInteger('grand_total')->default(0);

            $table->timestamp('ordered_at')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

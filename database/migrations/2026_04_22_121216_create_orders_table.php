<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('order_number')->unique();

            // Shipping
            $table->string('full_name');
            $table->string('phone');
            $table->string('address');
            $table->string('city');

            // KuickPay
            $table->string('consumer_number')->nullable();
            $table->string('tran_auth_id')->nullable();
            $table->string('payment_method')->default('kuickpay');
            $table->string('payment_status')->default('pending');

            // Amounts
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('shipping', 10, 2)->default(150);
            $table->decimal('total',    10, 2)->default(0);

            // Status
            $table->string('status')->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

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
            $table->unsignedBigInteger('orders_descriptions_id');
            $table->unsignedBigInteger('store_id');
            $table->json('order');
            $table->integer('total_price');
            $table->integer('bonus')->default(1);
            $table->tinyInteger('is_payed')->default(false);
            $table->timestamps();
            $table->foreign('orders_descriptions_id')
                ->references('id')
                ->on('orders_descriptions');
            $table->foreign('store_id')
                ->references('store_id')
                ->on('stores');
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

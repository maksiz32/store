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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description', 5000)->nullable();
            $table->unsignedBigInteger('store_id')->nullable();
            $table->tinyInteger('is_show_nav')->default(1);
            $table->timestamps();
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
        Schema::dropIfExists('categories');
    }
};

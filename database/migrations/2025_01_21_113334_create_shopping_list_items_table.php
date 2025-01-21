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
        Schema::create('shopping_list_items', function (Blueprint $table) {
            $table->id();
            $table->foreignUlid('shopping_list_id');
            $table->string('description');
            $table->unsignedSmallInteger('quantity')->default(1);
            $table->boolean('isPicked')->default(false);
            $table->unsignedMediumInteger('order')->nullable();
            $table->timestamp('last_reset_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shopping_list_items');
    }
};

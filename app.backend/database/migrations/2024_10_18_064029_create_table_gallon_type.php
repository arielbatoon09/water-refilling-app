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
        Schema::create('gallon_types', function (Blueprint $table) {
            $table->id();
            $table->string('gallon_size');
            $table->integer('gallon_price');
            $table->string('gallon_image');
            $table->integer('delivery_fee');
            $table->integer('flag');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_gallon_type');
    }
};

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
        Schema::create('refills', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned();
            $table->json('gallon_details');
            $table->string('delivery_type');
            $table->string('mop');
            $table->string('delivery_date');
            $table->integer('t_refill_fee');
            $table->integer('t_delivery_fee');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_refilling');
    }
};

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
        Schema::create('payment_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained()->onDelete('cascade');
            // Optional: Make it nullable so SET NULL works
            $table->unsignedBigInteger('cart_item_id')->nullable();
            $table->timestamps();
            // Re-add foreign key without cascade delete
            $table->foreign('cart_item_id')->references('id')->on('cart_items')->onDelete('set null');
        });
    }

    public function down(): void
    {
        
    }

};

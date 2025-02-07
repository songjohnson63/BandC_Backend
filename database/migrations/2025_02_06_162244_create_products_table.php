<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID field
            $table->string('name');
            $table->string('brand');
            $table->text('description')->nullable();
            $table->string('volume')->nullable();
            $table->text('key_ingredient')->nullable();
            $table->decimal('ori_price', 10, 2)->nullable(); // Original price with decimal support
            $table->decimal('price', 10, 2)->nullable(); // Current price with decimal support
            $table->string('img')->nullable(); // Nullable img field
            $table->timestamps(); // Created at and Updated at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

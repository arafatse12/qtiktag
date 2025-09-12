<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('gtin_mappings', function (Blueprint $t) {
            $t->id();

            $t->foreignId('product_id')
              ->constrained('products')
              ->cascadeOnUpdate()
              ->restrictOnDelete();

            $t->string('product_no', 32)->index();
            $t->string('order_no', 64)->index();
            $t->string('season', 32)->nullable()->index();
            $t->string('color_code', 32)->nullable();
            $t->string('size_code', 16)->nullable();

            $t->string('gtin14', 14)->index();
            $t->string('gtin16', 100)->unique(); // fixed length for GTIN-16
            $t->string('quantity', 100)->nullable();
            $t->string('qr_path');
            $t->string('barcode_path')->nullable();

            $t->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('gtin_mappings');
    }
};

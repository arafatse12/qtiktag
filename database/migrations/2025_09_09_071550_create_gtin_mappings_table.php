<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('gtin_mappings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();

            $table->string('product_no')->nullable();
            $table->string('order_no')->nullable();
            $table->string('season')->nullable();
            $table->string('color_code')->nullable();
            $table->string('size_code')->nullable();

            $table->string('gtin14')->nullable()->index();
            $table->string('gtin16')->nullable()->index();
            $table->unsignedInteger('quantity')->default(1);

            $table->string('qr_path')->nullable();
            $table->string('barcode_path')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gtin_mappings');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('products', function (Blueprint $t) {
            $t->id();
            $t->string('product_no', 32)->unique();
            $t->string('name')->nullable();
            $t->string('description')->nullable();

            $t->string('season', 32)->nullable();
            $t->string('customer_group', 64)->nullable();
            $t->string('construction_type', 64)->nullable();

            $t->string('gtin14', 14)->nullable();
            $t->string('qr_base_url')->default('https://qr.hmgroup.com/01');
            $t->string('qr_url')->nullable();

            $t->string('supplier_code', 64)->nullable();
            $t->string('supplier_name')->nullable();

            $t->timestamps();

            $t->index('season');
        });
    }

    public function down(): void {
        Schema::dropIfExists('products');
    }
};

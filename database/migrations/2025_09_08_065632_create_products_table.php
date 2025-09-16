<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // Core identity
            $table->string('product_no')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('season')->nullable();
            $table->string('customer_group')->nullable();
            $table->string('construction_type')->nullable();

            // Mirrors for quick search
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('sku_code')->nullable();
            $table->string('barcode')->nullable();
            $table->string('gtin14')->nullable()->index();
            $table->string('qr_base_url')->nullable();
            $table->string('qr_url')->nullable();
            $table->string('shop_url')->nullable();

            // Manufacturing scalars (also present inside JSON)
            $table->string('supplier_code')->nullable();
            $table->string('supplier_name')->nullable();
            $table->string('factory')->nullable();
            $table->string('address')->nullable();
            $table->string('origin')->nullable();
            $table->string('batch')->nullable();
            $table->date('manufactured_on')->nullable();
            $table->date('packaged_on')->nullable();
            $table->string('publisher')->nullable();
            $table->string('info_updated_at_pretty')->nullable();

            // Images
            $table->string('image')->nullable();
            $table->string('image_logo')->nullable();
            $table->string('image_hero')->nullable();
            $table->string('image_footer')->nullable();
            $table->string('gear_image_url')->nullable();

            // Narrative blocks
            $table->longText('production_process')->nullable();
            $table->longText('environmental_considerations')->nullable();
            $table->longText('worker_wellbeing')->nullable();

            // JSON blocks
            $table->json('identity_json')->nullable();
            $table->json('manufacturing_json')->nullable();
            $table->json('materials_json')->nullable();
            $table->json('custody_json')->nullable();
            $table->json('usage_json')->nullable();
            $table->json('certs_json')->nullable();
            $table->json('sustain_json')->nullable();
            $table->json('impact_json')->nullable();

            $table->timestamps();

            $table->index(['season', 'customer_group']);
            $table->index('construction_type');
            $table->index('sku_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

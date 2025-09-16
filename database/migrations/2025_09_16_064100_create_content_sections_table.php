<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_content_sections_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('content_sections', function (Blueprint $table) {
            $table->id();
            $table->string('name');            // Human title e.g. "Manufacturing"
            $table->string('slug')->unique();  // e.g. "manufacturing"
            $table->json('content');           // <-- dynamic JSON blocks
            $table->boolean('published')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('content_sections');
    }
};


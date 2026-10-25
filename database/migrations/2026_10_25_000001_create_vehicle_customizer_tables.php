<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['mobil', 'motor']);
            $table->string('brand');
            $table->string('name');
            $table->string('slug')->unique();
            $table->unsignedSmallInteger('year');
            $table->unsignedBigInteger('base_price');
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('modification_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('icon')->nullable();
            $table->unsignedTinyInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('modification_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('modification_categories')->cascadeOnDelete();
            $table->enum('vehicle_type', ['mobil', 'motor', 'semua'])->default('semua');
            $table->string('name');
            $table->string('slug')->unique();
            $table->unsignedBigInteger('price')->default(0);
            $table->string('color')->nullable();
            $table->string('preview_key')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('builds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->unsignedBigInteger('total_price');
            $table->string('status')->default('draft');
            $table->timestamps();
        });

        Schema::create('build_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('build_id')->constrained()->cascadeOnDelete();
            $table->foreignId('modification_item_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('price');
            $table->timestamps();
            $table->unique(['build_id', 'modification_item_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('build_items');
        Schema::dropIfExists('builds');
        Schema::dropIfExists('modification_items');
        Schema::dropIfExists('modification_categories');
        Schema::dropIfExists('vehicles');
    }
};

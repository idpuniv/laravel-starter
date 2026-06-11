<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {

            $table->id();

            // ===== IDENTIFICATION =====
            $table->string('slug')->unique(); // identifiant unique
            $table->string('label')->nullable();
            $table->string('icon')->nullable();

            // ===== POSITION =====
            $table->enum('type', ['sidebar', 'navbar'])->index();
            $table->string('menu_type')->default('link'); 
            // link | dropdown | search | logout

            $table->string('position')->nullable();
            // utile pour navbar (left/right)

            // ===== ROUTING =====
            $table->string('route')->nullable();
            $table->string('url')->nullable();

            // ===== HIERARCHIE =====
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('menus')
                ->cascadeOnDelete();

            $table->integer('order')->default(0)->index();

            // ===== SECURITY =====
            $table->string('permission')->nullable()->index();

            // ===== FLAGS =====
            $table->boolean('is_active')->default(true)->index();
            $table->boolean('is_visible')->default(true)->index();

            // ===== EXTENSIBLE =====
            $table->json('options')->nullable(); // config custom
            $table->json('badge')->nullable();   // badge UI (count, color...)

            // ===== LARAVEL =====
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
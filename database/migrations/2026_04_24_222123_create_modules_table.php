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
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->string('slug', 100)->unique();
            $table->string('label')->nullable();
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index('slug');
            $table->index('is_active');
            $table->index('order');
        });

        // Add module_id column to permissions table
        Schema::table('permissions', function (Blueprint $table) {
            $table->foreignId('module_id')
                ->nullable()
                ->after('id')
                ->constrained('modules')
                ->onDelete('set null');

            $table->string('module_slug', 100)->nullable()->after('module_id');
            
            // Ajouter label à permissions
            $table->string('label')->nullable()->after('name');

            $table->index('module_id');
            $table->index('module_slug');
        });

        // Ajouter la colonne label à la table roles
        Schema::table('roles', function (Blueprint $table) {
            $table->string('label')->nullable()->after('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Supprimer la colonne label de roles
        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn('label');
        });

        // Supprimer les colonnes de permissions
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropForeign(['module_id']);
            $table->dropColumn(['module_id', 'module_slug', 'label']);
        });
        
        // Supprimer la table modules
        Schema::dropIfExists('modules');
    }
};
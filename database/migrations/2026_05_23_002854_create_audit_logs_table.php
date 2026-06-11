<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            
            // Acteur
            $table->unsignedBigInteger('actor_id')->nullable();
            $table->string('actor_type')->nullable();
            $table->string('system_user')->nullable(); // 'system', 'queue', 'scheduler'
            
            // Action
            $table->string('event'); // create, update, delete, login, logout, export, import
            $table->string('event_outcome')->default('success'); // success, failure
            
            // Cible
            $table->string('target_type')->nullable();
            $table->unsignedBigInteger('target_id')->nullable();
            $table->string('target_identifier')->nullable();
            
            // Contexte métier
            $table->string('context_type')->nullable();
            $table->unsignedBigInteger('context_id')->nullable();
            
            // Avant/Après
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            
            // HTTP
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('url', 2000)->nullable();
            $table->string('http_method', 10)->nullable();
            $table->string('referrer', 2000)->nullable();
            
            $table->timestamp('created_at')->useCurrent();
            
            // Index
            $table->index(['actor_id', 'actor_type']);
            $table->index(['target_type', 'target_id']);
            $table->index('event');
            $table->index('event_outcome');
            $table->index('created_at');
            $table->index('ip_address');
            $table->index('system_user');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
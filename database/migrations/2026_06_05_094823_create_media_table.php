<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | POLYMORPHIC RELATION
            |--------------------------------------------------------------------------
            | Allows media to be attached to any model (User, Post, Product, etc.)
            */
            $table->morphs('mediable');

            /*
            |--------------------------------------------------------------------------
            | MEDIA CLASSIFICATION
            |--------------------------------------------------------------------------
            | role       : Business meaning (avatar, logo, document, etc.)
            | collection : Logical grouping for UI purposes (gallery, slider, etc.)
            */
            $table->string('role')->index();
            $table->string('collection')->nullable()->index();

            /*
            |--------------------------------------------------------------------------
            | MEDIA TYPE
            |--------------------------------------------------------------------------
            | Defines the general file category (image, video, document, etc.)
            */
            $table->string('type')->index();

            /*
            |--------------------------------------------------------------------------
            | STORAGE INFORMATION
            |--------------------------------------------------------------------------
            | disk  : Storage disk (public, s3, etc.)
            | path  : File path inside the disk
            | thumb : Optional thumbnail path for optimized display
            */
            $table->string('disk')->default('public');
            $table->string('path');
            $table->string('thumb_path')->nullable();

            /*
            |--------------------------------------------------------------------------
            | FILE METADATA
            |--------------------------------------------------------------------------
            | filename  : Original or stored filename
            | extension : File extension (jpg, pdf, etc.)
            | mime_type : MIME type for HTTP responses
            | size      : File size in bytes
            */
            $table->string('filename')->nullable();
            $table->string('extension', 20)->nullable();
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size')->nullable();

            /*
            |--------------------------------------------------------------------------
            | STATE MANAGEMENT
            |--------------------------------------------------------------------------
            | is_current : Marks the active media for single-role usage
            */
            $table->boolean('is_current')->default(false)->index();

            /*
            |--------------------------------------------------------------------------
            | CUSTOM DATA
            |--------------------------------------------------------------------------
            | Flexible JSON column for extensibility (filters, AI tags, etc.)
            */
            $table->json('metadata')->nullable();

            /*
            |--------------------------------------------------------------------------
            | SORTING
            |--------------------------------------------------------------------------
            | Controls ordering in galleries or collections
            */
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | INDEXES
            |--------------------------------------------------------------------------
            | Optimized queries for polymorphic + role + collection filtering
            */
            $table->index(['mediable_type', 'mediable_id', 'role']);
            $table->index(['mediable_type', 'mediable_id', 'collection']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
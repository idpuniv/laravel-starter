<?php

namespace App\Observers;

use App\Models\Media;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaObserver
{
    /**
     * Handle pre-create logic (default values + metadata).
     */
    public function creating(Media $media): void
    {
        $media->disk ??= config('filesystems.default');

        if (blank($media->path)) {
            return;
        }

        $media->filename ??= pathinfo($media->path, PATHINFO_BASENAME);

        $media->extension ??= Str::lower(
            pathinfo($media->path, PATHINFO_EXTENSION)
        );

        $media->type ??= match ($media->extension) {
            'jpg', 'jpeg', 'png', 'gif', 'webp', 'svg' => 'image',
            'pdf', 'doc', 'docx', 'xls', 'xlsx' => 'document',
            'mp4', 'avi', 'mov' => 'video',
            'mp3', 'wav', 'ogg' => 'audio',
            default => 'file',
        };

        try {
            if (Storage::disk($media->disk)->exists($media->path)) {
                $media->mime_type ??= Storage::disk($media->disk)->mimeType($media->path);
                $media->size ??= Storage::disk($media->disk)->size($media->path);
            }
        } catch (\Throwable) {
            // fail silently
        }
    }

    /**
     * Handle single-role logic (avatar, logo, etc.)
     */
    public function saving(Media $media): void
    {
        if (!$media->is_current) {
            return;
        }

        if (!in_array($media->role, Media::SINGLE_ROLES)) {
            return;
        }

        Media::where('mediable_type', $media->mediable_type)
            ->where('mediable_id', $media->mediable_id)
            ->where('role', $media->role)
            ->where('id', '!=', $media->id)
            ->update(['is_current' => false]);
    }

    /**
     * Handle file deletion.
     */
    public function deleting(Media $media): void
    {

        try {
            $disk = $media->disk ?? 'public';

            if ($media->path) {
                Storage::disk($disk)->delete($media->path);
            }

            if (!empty($media->thumb_path)) {
                Storage::disk($disk)->delete($media->thumb_path);
            }
        } catch (\Throwable) {
            // ignore
        }
    }
}

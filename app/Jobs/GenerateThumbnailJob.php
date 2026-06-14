<?php

namespace App\Jobs;

use App\Models\Media;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Format;

class GenerateThumbnailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    public function __construct(public Media $media) {}

    public function handle(): void
    {
        if (!$this->media->path) {
            return;
        }

        $fullPath  = Storage::disk($this->media->disk)->path($this->media->path);
        $thumbPath = 'thumbs/' . $this->media->path;

        $manager = ImageManager::usingDriver(Driver::class);
        $encoded = $manager->decodePath($fullPath)
            ->scaleDown(width: 150)
            ->encodeUsingFormat(Format::JPEG, quality: 85);

        Storage::disk($this->media->disk)->put($thumbPath, (string) $encoded);

        $this->media->update(['thumb_path' => $thumbPath]);
    }
}
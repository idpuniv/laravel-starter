<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MediaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
    }

    /** @test */
    public function it_creates_media_and_fills_metadata_automatically(): void
    {
        $user = User::factory()->create();

        $media = $user->media()->create([
            'role' => 'avatar',
            'path' => 'uploads/test.jpg',
            'is_current' => true,
        ]);

        $this->assertNotNull($media->type);
        $this->assertNotNull($media->extension);

        $this->assertEquals('image', $media->type);
        $this->assertEquals('jpg', $media->extension);
    }

    /** @test */
    public function it_ensures_only_one_current_media_per_single_role(): void
    {
        $user = User::factory()->create();

        $first = $user->media()->create([
            'role' => 'avatar',
            'path' => 'uploads/a.jpg',
            'is_current' => true,
        ]);

        $second = $user->media()->create([
            'role' => 'avatar',
            'path' => 'uploads/b.jpg',
            'is_current' => true,
        ]);

        $this->assertFalse($first->fresh()->is_current);
        $this->assertTrue($second->is_current);

        $this->assertEquals(
            1,
            $user->media()->current()->count()
        );
    }

    /** @test */
    public function it_allows_multiple_media_for_gallery_roles(): void
    {
        $user = User::factory()->create();

        $user->media()->create([
            'role' => 'gallery',
            'path' => 'uploads/1.jpg',
        ]);

        $user->media()->create([
            'role' => 'gallery',
            'path' => 'uploads/2.jpg',
        ]);

        $this->assertEquals(
            2,
            $user->media()->where('role', 'gallery')->count()
        );
    }

    /** @test */
    public function it_deletes_media_from_database_and_triggers_observer(): void
    {
        $user = User::factory()->create();

        $media = $user->media()->create([
            'role' => 'avatar',
            'path' => 'uploads/test.jpg',
        ]);

        $this->assertDatabaseHas('media', [
            'id' => $media->id,
        ]);

        $media->delete();

        $this->assertDatabaseMissing('media', [
            'id' => $media->id,
        ]);
    }

    /** @test */
    public function it_filters_current_media(): void
    {
        $user = User::factory()->create();

        $user->media()->create([
            'role' => 'avatar',
            'path' => 'uploads/a.jpg',
            'is_current' => false,
        ]);

        $current = $user->media()->create([
            'role' => 'avatar',
            'path' => 'uploads/b.jpg',
            'is_current' => true,
        ]);

        $this->assertEquals(1, $user->media()->current()->count());
        $this->assertTrue($current->is_current);
    }

    /** @test */
    public function it_filters_by_role(): void
    {
        $user = User::factory()->create();

        $user->media()->create([
            'role' => 'avatar',
            'path' => 'uploads/a.jpg',
        ]);

        $this->assertEquals(
            1,
            $user->media()->role('avatar')->count()
        );
    }
}
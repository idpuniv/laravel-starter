<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Jobs\GenerateThumbnailJob;
use App\Support\Flash;
use App\Support\Breadcrumb;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index()
    {
        Breadcrumb::add('Articles', 'admin.posts.index');
        
        $posts = Post::with(['user', 'category'])->latest()->paginate(15);
        
        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        try {
            Breadcrumb::add('Articles', 'admin.posts.index')
                      ->add('Créer');

            return view('admin.posts.create', [
                'categories' => $this->categories(),
                'tags' => $this->tags(),
            ]);
        } catch (\Throwable $e) {
            Log::error($e);

            return back()->with(
                Flash::ERROR,
                __('messages.operation_failed')
            );
        }
    }

    public function store(StorePostRequest $request)
    {
        try {
            $data = $request->validated();
            $data['user_id'] = $request->user()->id;
            $data['slug'] = $data['slug'] ?? Str::slug($data['title']);

            $post = Post::create(Arr::except($data, ['tags', 'featured_image', 'gallery']));

            // Gérer l'image à la une
            if ($request->hasFile('featured_image')) {
                $media = $this->uploadImage($post, $request->file('featured_image'), 'featured');
                GenerateThumbnailJob::dispatch($media);
            }

            // Gérer la galerie d'images
            if ($request->hasFile('gallery')) {
                foreach ($request->file('gallery') as $file) {
                    $media = $this->uploadImage($post, $file, 'gallery');
                    GenerateThumbnailJob::dispatch($media);
                }
            }

            $post->tags()->sync($request->validated('tags', []));

            return redirect()
                ->route('admin.posts.show', $post->id)
                ->with(Flash::SUCCESS, __('messages.created'));

        } catch (\Throwable $e) {
            Log::error($e);

            return back()
                ->withInput()
                ->with(Flash::ERROR, __('messages.create_failed'));
        }
    }

    public function show(Post $post)
    {
        try {
            Breadcrumb::add('Articles', 'admin.posts.index')
                      ->add($post->title);

            return view('admin.posts.show', [
                'post' => $post->load(['category', 'user', 'tags', 'comments', 'media']),
            ]);
        } catch (\Throwable $e) {
            Log::error($e);

            return redirect()
                ->route('admin.posts.index')
                ->with(Flash::ERROR, __('messages.not_found'));
        }
    }

    public function edit(Post $post)
    {
        try {
            Breadcrumb::add('Articles', 'admin.posts.index')
                      ->add($post->title, 'admin.posts.show', $post)
                      ->add('Modifier');

            return view('admin.posts.edit', [
                'post' => $post->load('tags', 'media'),
                'categories' => $this->categories(),
                'tags' => $this->tags(),
            ]);
        } catch (\Throwable $e) {
            Log::error($e);

            return redirect()
                ->route('admin.posts.index')
                ->with(Flash::ERROR, __('messages.operation_failed'));
        }
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        try {
            $data = $request->validated();
            $data['slug'] = $data['slug'] ?? Str::slug($data['title']);

            $post->update(Arr::except($data, ['tags', 'featured_image', 'gallery']));

            // Gérer l'image à la une
            if ($request->hasFile('featured_image')) {
                // Supprimer l'ancienne image à la une
                $oldFeatured = $post->media()->where('role', 'featured')->first();
                if ($oldFeatured) {
                    $oldFeatured->delete();
                }
                $media = $this->uploadImage($post, $request->file('featured_image'), 'featured');
                GenerateThumbnailJob::dispatch($media);
            }

            // Gérer la galerie d'images (ajout de nouvelles images)
            if ($request->hasFile('gallery')) {
                foreach ($request->file('gallery') as $file) {
                    $media = $this->uploadImage($post, $file, 'gallery');
                    GenerateThumbnailJob::dispatch($media);
                }
            }

            $post->tags()->sync($request->validated('tags', []));

            return redirect()
                ->route('admin.posts.index')
                ->with(Flash::SUCCESS, __('messages.updated'));

        } catch (\Throwable $e) {
            Log::error($e);

            return back()
                ->withInput()
                ->with(Flash::ERROR, __('messages.update_failed'));
        }
    }

    public function destroy(Post $post)
    {
        try {
            $post->delete();

            return redirect()
                ->route('admin.posts.index')
                ->with(Flash::SUCCESS, __('messages.deleted'));

        } catch (\Throwable $e) {
            Log::error($e);

            return redirect()
                ->route('admin.posts.index')
                ->with(Flash::ERROR, __('messages.delete_failed'));
        }
    }

    private function uploadImage(Post $post, $file, string $role)
    {
        $path = $file->store('posts/images', 'public');
        
        return $post->media()->create([
            'path' => $path,
            'role' => $role,
            'disk' => 'public',
            'is_current' => $role === 'featured',
        ]);
    }

    private function categories()
    {
        return Category::orderBy('name')->get();
    }

    private function tags()
    {
        return Tag::orderBy('name')->get();
    }
}
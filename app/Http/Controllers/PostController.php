<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Support\Flash;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Post::class, 'post');
    }

    public function index()
    {
        return view('admin.posts.index');
    }

    public function create()
    {
        try {
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

            $post = Post::create(Arr::except($data, ['tags']));
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
            return view('admin.posts.show', [
                'post' => $post->load(['category', 'user', 'tags', 'comments']),
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
            return view('admin.posts.edit', [
                'post' => $post->load('tags'),
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

            $post->update(Arr::except($data, ['tags']));
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

    private function categories()
    {
        return Category::orderBy('name')->get();
    }

    private function tags()
    {
        return Tag::orderBy('name')->get();
    }
}

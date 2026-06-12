<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Models\Tag;
use App\Support\Flash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TagController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Tag::class, 'tag');
    }

    public function index()
    {
        return view('admin.tags.index');
    }

    public function create()
    {
        return view('admin.tags.create');
    }

    public function store(StoreTagRequest $request)
    {
        try {
            $data = $request->validated();
            $data['slug'] = $data['slug'] ?? Str::slug($data['name']);

            $tag = Tag::create($data);

            return redirect()
                ->route('admin.tags.show', $tag->id)
                ->with(Flash::SUCCESS, __('messages.created'));

        } catch (\Throwable $e) {
            Log::error($e);

            return back()
                ->withInput()
                ->with(Flash::ERROR, __('messages.create_failed'));
        }
    }

    public function show(Tag $tag)
    {
        try {
            return view('admin.tags.show', [
                'tag' => $tag,
            ]);
        } catch (\Throwable $e) {
            Log::error($e);

            return redirect()
                ->route('admin.tags.index')
                ->with(Flash::ERROR, __('messages.not_found'));
        }
    }

    public function edit(Tag $tag)
    {
        return view('admin.tags.edit', [
            'tag' => $tag,
        ]);
    }

    public function update(UpdateTagRequest $request, Tag $tag)
    {
        try {
            $data = $request->validated();
            $data['slug'] = $data['slug'] ?? Str::slug($data['name']);

            $tag->update($data);

            return redirect()
                ->route('admin.tags.index')
                ->with(Flash::SUCCESS, __('messages.updated'));

        } catch (\Throwable $e) {
            Log::error($e);

            return back()
                ->withInput()
                ->with(Flash::ERROR, __('messages.update_failed'));
        }
    }

    public function destroy(Tag $tag)
    {
        try {
            $tag->delete();

            return redirect()
                ->route('admin.tags.index')
                ->with(Flash::SUCCESS, __('messages.deleted'));

        } catch (\Throwable $e) {
            Log::error($e);

            return redirect()
                ->route('admin.tags.index')
                ->with(Flash::ERROR, __('messages.delete_failed'));
        }
    }
}

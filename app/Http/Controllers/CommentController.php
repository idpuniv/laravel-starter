<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Models\Comment;
use App\Support\Flash;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Comment::class, 'comment');
    }

    public function index()
    {
        return view('admin.comments.index');
    }

    public function create()
    {
        return view('admin.comments.create');
    }

    public function store(StoreCommentRequest $request)
    {
        try {
            $data = $request->validated();
            $data['user_id'] = $request->user()->id;

            $comment = Comment::create($data);

            return redirect()
                ->route('admin.comments.show', $comment->id)
                ->with(Flash::SUCCESS, __('messages.created'));

        } catch (\Throwable $e) {
            Log::error($e);

            return back()
                ->withInput()
                ->with(Flash::ERROR, __('messages.create_failed'));
        }
    }

    public function show(Comment $comment)
    {
        try {
            return view('admin.comments.show', [
                'comment' => $comment->load(['user', 'post', 'parent']),
            ]);
        } catch (\Throwable $e) {
            Log::error($e);

            return redirect()
                ->route('admin.comments.index')
                ->with(Flash::ERROR, __('messages.not_found'));
        }
    }

    public function edit(Comment $comment)
    {
        try {
            return view('admin.comments.edit', [
                'comment' => $comment->load(['user', 'post']),
            ]);
        } catch (\Throwable $e) {
            Log::error($e);

            return redirect()
                ->route('admin.comments.index')
                ->with(Flash::ERROR, __('messages.operation_failed'));
        }
    }

    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        try {
            $data = $request->validated();
            $data['edited_at'] = now();

            $comment->update($data);

            return redirect()
                ->route('admin.comments.index')
                ->with(Flash::SUCCESS, __('messages.updated'));

        } catch (\Throwable $e) {
            Log::error($e);

            return back()
                ->withInput()
                ->with(Flash::ERROR, __('messages.update_failed'));
        }
    }

    public function destroy(Comment $comment)
    {
        try {
            $comment->delete();

            return redirect()
                ->route('admin.comments.index')
                ->with(Flash::SUCCESS, __('messages.deleted'));

        } catch (\Throwable $e) {
            Log::error($e);

            return redirect()
                ->route('admin.comments.index')
                ->with(Flash::ERROR, __('messages.delete_failed'));
        }
    }
}

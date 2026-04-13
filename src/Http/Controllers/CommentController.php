<?php

namespace SagorRoy\Comments\Http\Controllers;

use SagorRoy\Comments\Http\Requests\StoreCommentRequest;
use SagorRoy\Comments\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class CommentController extends Controller
{
    public function store(StoreCommentRequest $request): JsonResponse|RedirectResponse
    {
        Comment::create([
            'user_id' => auth()->id(),
            'commentable_type' => $request->input('commentable_type'),
            'commentable_id' => $request->input('commentable_id'),
            'parent_id' => $request->input('parent_id'),
            'content' => $request->input('content'),
            'guest_name' => $request->input('guest_name'),
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => trans('comments::comments.posted_success'),
            ]);
        }

        return back();
    }

    public function destroy(Comment $comment): JsonResponse|RedirectResponse
    {
        if (auth()->check() && auth()->id() === $comment->user_id) {
            $comment->delete();
            
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => trans('comments::comments.deleted_success'),
                ]);
            }
        }

        if (request()->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => trans('comments::comments.unauthorized'),
            ], 403);
        }

        return back();
    }
}

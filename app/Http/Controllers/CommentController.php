<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        return response()->json($request->user()->comments);
    }

    public function store(Request $request)
    {
        $comment = new Comment();
        $comment->user_id = $request->user()->id;
        $comment->commentable_type = $request->input('commentable_type');
        $comment->commentable_id = $request->input('commentable_id');
        $comment->content = $request->input('content');
        $comment->save();
    }

    public function update(Request $request)
    {
        $comment = Comment::find($request->integer('id'));
        $comment->content = $request->input('content');
        $comment->save();
    }

    public function destroy(Request $request)
    {
        $comment = Comment::find($request->integer('id'));
        $comment->delete();
    }
}

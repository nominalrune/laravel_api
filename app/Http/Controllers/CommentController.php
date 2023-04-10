<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use Log;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        // Log::debug("CommentController@store", ['kind' => $request->input('kind')]);
        $comment = new Comment();
        $comment->user_id = $request->user()->id;
        $comment->commentable_type = $request->input('commentable_type');
        $comment->commentable_id = $request->input('commentable_id');
        $comment->body = $request->input('body');
        $comment->save();
    }
    public function update(Request $request)
    {
        $comment = Comment::find($request->integer('id'));
        $comment->body = $request->input('body');
        $comment->save();
    }
    public function destroy(Request $request)
    {
        $comment = Comment::find($request->integer('id'));
        $comment->delete();
    }

}

<?php
namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller {
    public function store(Request $request, Post $post) {
        $data = $request->validate([
            'content' => 'required|string',
        ]);

        $comment = auth('api')->user()->comments()->create([
            'content' => $data['content'],
            'post_id' => $post->id,
        ]);

        return response()->json($comment->load('user'), 201);
    }
}
<?php
namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class LikeController extends Controller {
    public function store(Request $request, Post $post) {
        $user = auth('api')->user();

        // Check if already liked
        if ($user->likes()->where('post_id', $post->id)->exists()) {
            return response()->json(['message' => 'Post already liked'], 400);
        }

        $user->likes()->attach($post->id);
        return response()->json(['message' => 'Post liked'], 201);
    }

    public function destroy(Request $request, Post $post) {
        $user = auth('api')->user();

        // Check if not liked
        if (!$user->likes()->where('post_id', $post->id)->exists()) {
            return response()->json(['message' => 'Post not liked'], 400);
        }

        $user->likes()->detach($post->id);
        return response()->json(['message' => 'Post unliked'], 200);
    }
}
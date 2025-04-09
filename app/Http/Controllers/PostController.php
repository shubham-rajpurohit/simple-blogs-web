<?php
namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostController extends Controller {
    public function index() {
        $posts = Post::with('user')->orderBy('created_at', 'DESC')->paginate(10); // Latest posts first
        return response()->json($posts);
    }

    public function store(Request $request) {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $post = auth('api')->user()->posts()->create($data);
        return response()->json($post, 201);
    }

    public function show($id): JsonResponse
    {
        $post = Post::with([
            'user',
            'comments' => function ($query) {
                $query->with('user')->orderBy('created_at', 'DESC'); // Latest comments first
            },
            'likes'
        ])->findOrFail($id);
        $userId = auth('api')->id();

        return response()->json([
            'id' => $post->id,
            'title' => $post->title,
            'content' => $post->content,
            'user' => $post->user,
            'created_at' => $post->created_at,
            'comments' => $post->comments,
            'likes' => $post->likes,
            'like_count' => $post->likes()->count(),
            'comment_count' => $post->comments()->count(),
            'liked_by_me' => $userId ? $post->likes()->where('user_id', $userId)->exists() : false,
        ]);
    }

    public function like(Request $request, $id): JsonResponse
    {
        $post = Post::findOrFail($id);
        $userId = auth()->id();

        if ($post->likes()->where('user_id', $userId)->exists()) {
            $post->likes()->detach($userId);
            $message = 'Post unliked';
        } else {
            $post->likes()->attach($userId);
            $message = 'Post liked';
        }

        return response()->json([
            'status' => 'success',
            'message' => $message,
            'like_count' => $post->likes()->count(),
        ]);
    }
    
    public function update(Request $request, Post $post) {
        $this->authorize('update', $post);
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $post->update($data);
        return response()->json($post);
    }

    public function myPosts(Request $request): JsonResponse
    {
        $posts = Post::where('user_id', auth()->id())
            ->with(['user', 'comments.user', 'likes'])
            ->paginate(10);
        return response()->json($posts);
    }

    public function destroy(Post $post) {
        $this->authorize('delete', $post);
        $post->delete();
        return response()->json(null, 204);
    }
}
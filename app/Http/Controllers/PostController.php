<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\DeletePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use App\Services\DummyJson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::paginate(10);

        $transformedPosts = $posts->map(function ($post) {
            $dummyPostData = DummyJson::get($post->dummy_post_id);

            return [
                'id' => $post->id,
                'title' => $dummyPostData->title,
                'author' => $post->user->name,
                'body' => str($dummyPostData->body)->limit(128),
            ];
        });

        return response()->json([
            'posts' => $transformedPosts,
            'pagination' => [
                'current_page' => $posts->currentPage(),
                'per_page' => $posts->perPage(),
                'total' => $posts->total(),
            ]
        ]);
    }

    public function show(string $id)
    {
        $post = Post::find($id);

        if ( ! $post) {
            return response()->json([
                'error' => 'Post not found',
            ], 404);
        }

        $dummyPost = DummyJson::get($post->dummy_post_id);

        if (! $dummyPost) {
            return response()->json([
                'error' => 'Something went wrong',
            ]);
        }

        return response()->json([
            'id' => $post->id,
            'user_id' => $post->user_id,
            'title' => $dummyPost->title,
            'body' => $dummyPost->body,
            'created_at' => $post->created_at,
            'updated_at' => $post->updated_at,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreatePostRequest $request)
    {
        $data = $request->validated();

        $dummyPost = DummyJson::create([
            'title' => $data['title'],
            'body' => $data['body'],
            'userId' => $request->user()->id,
        ]);

        if (! $dummyPost) {
            return response()->json([
                'error' => 'Something went wrong',
            ]);
        }

        $dummyPostId = $dummyPost->id;

        $post = $request->user()->posts()->create([
            'dummy_post_id' => rand(0, $dummyPostId - 1),
        ]);

        return response()->json([
            'id' => $post->id,
            'user_id' => $post->user_id,
            'title' => $data['title'],
            'body' => $data['body'],
            'created_at' => $post->created_at,
            'updated_at' => $post->updated_at,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, string $id)
    {
        $data = $request->validated();

        $post = Post::find($id);

        if ( ! $post) {
            return response()->json([
                'error' => 'Post not found',
            ], 404);
        }

        $dummyPost = DummyJson::update([
            'title' => $data['title'],
            'body' => $data['body'],
        ], $post->dummy_post_id);

        if (! $dummyPost) {
            return response()->json([
                'error' => 'Something went wrong',
            ]);
        }

        return response()->json([
            'id' => $post->id,
            'user_id' => $post->user_id,
            'title' => $data['title'],
            'body' => $data['body'],
            'created_at' => $post->created_at,
            'updated_at' => $post->updated_at,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeletePostRequest $request, string $id)
    {
        $post = Post::find($id);

        if ( ! $post) {
            return response()->json([
                'error' => 'Post not found',
            ], 404);
        }

        $isDeleted = DummyJson::delete($post->dummy_post_id);

        if (! $isDeleted) {
            return response()->json([
                'error' => 'Something went wrong',
            ]);
        }

        $post->delete();

        return response()->json();
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\DeletePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Services\PostService;

class PostController extends Controller
{
    protected $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function index()
    {
        $result = $this->postService->getPaginatedPosts();

        return response()->json($result);
    }

    public function show(string $id)
    {
        $post = $this->postService->getPostWithDummyData($id);

        if (!$post) {
            return response()->json([
                'error' => 'Post not found',
            ], 404);
        }

        return response()->json($post);
    }

    public function store(CreatePostRequest $request)
    {
        $data = $request->validated();

        $post = $this->postService->createPost($data, $request->user()->id);

        if (!$post) {
            return response()->json([
                'error' => 'Something went wrong',
            ]);
        }

        return response()->json($post);
    }

    public function update(UpdatePostRequest $request, string $id)
    {
        $data = $request->validated();

        $post = $this->postService->updatePost($id, $data);

        if (!$post) {
            return response()->json([
                'error' => 'Post not found',
            ], 404);
        }

        return response()->json($post);
    }

    public function destroy(DeletePostRequest $request, string $id)
    {
        $deleted = $this->postService->deletePost($id);

        if (!$deleted) {
            return response()->json([
                'error' => 'Post not found',
            ], 404);
        }

        return response()->json();
    }
}

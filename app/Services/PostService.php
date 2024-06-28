<?php

namespace App\Services;

use App\Models\Post;
use App\Services\DummyJson;

class PostService
{
    public function getPaginatedPosts(int $perPage = 10)
    {
        $posts = Post::paginate($perPage);

        $transformedPosts = $posts->map(function ($post) {
            $dummyPostData = DummyJson::get($post->dummy_post_id);

            return [
                'id' => $post->id,
                'title' => $dummyPostData->title,
                'author' => $post->user->name,
                'body' => str($dummyPostData->body)->limit(128),
            ];
        });

        return [
            'posts' => $transformedPosts,
            'pagination' => [
                'current_page' => $posts->currentPage(),
                'per_page' => $posts->perPage(),
                'total' => $posts->total(),
            ],
        ];
    }

    public function getPostWithDummyData(string $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return null;
        }

        $dummyPost = DummyJson::get($post->dummy_post_id);

        if (!$dummyPost) {
            return null;
        }

        return [
            'id' => $post->id,
            'user_id' => $post->user_id,
            'title' => $dummyPost->title,
            'body' => $dummyPost->body,
            'created_at' => $post->created_at,
            'updated_at' => $post->updated_at,
        ];
    }

    public function createPost(array $data, $userId)
    {
        $dummyPost = DummyJson::create([
            'title' => $data['title'],
            'body' => $data['body'],
            'userId' => $userId,
        ]);

        if (!$dummyPost) {
            return null;
        }

        $dummyPostId = $dummyPost->id;

        return Post::create([
            'dummy_post_id' => rand(0, $dummyPostId - 1),
            'user_id' => $userId,
        ]);
    }

    public function updatePost(string $id, array $data)
    {
        $post = Post::find($id);

        if (!$post) {
            return null;
        }

        $dummyPost = DummyJson::update([
            'title' => $data['title'],
            'body' => $data['body'],
        ], $post->dummy_post_id);

        if (!$dummyPost) {
            return null;
        }

        return $post;
    }

    public function deletePost(string $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return null;
        }

        $isDeleted = DummyJson::delete($post->dummy_post_id);

        if (!$isDeleted) {
            return null;
        }

        $post->delete();

        return true;
    }
}

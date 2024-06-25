<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class DummyJson
{
    public static function get($id) : null|object
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->get('https://dummyjson.com/posts/' . $id);

        if (! $response->successful()) {
            return null;
        }

        return $response->object();
    }

    public static function create(array $data) : null|object
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post('https://dummyjson.com/posts/add', $data);

        if (! $response->successful()) {
            return null;
        }

        return $response->object();
    }

    public static function update(array $data, $id) : null|object
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->put('https://dummyjson.com/posts/' . $id, $data);

        if (! $response->successful()) {
            return null;
        }

        return $response->object();
    }

    public static function delete($id) : bool
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->delete('https://dummyjson.com/posts/' . $id);

        if (! $response->successful()) {
            return false;
        }

        return true;
    }
}

<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class DummyJson
{
    protected static function getBaseUrl()
    {
        return config('dummyjson.base_url');
    }

    public static function get($id) : null|object
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->get(self::getBaseUrl() . $id);

        if (! $response->successful()) {
            return null;
        }

        return $response->object();
    }

    public static function create(array $data) : null|object
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post(self::getBaseUrl() . 'add', $data);

        if (! $response->successful()) {
            return null;
        }

        return $response->object();
    }

    public static function update(array $data, $id) : null|object
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->put(self::getBaseUrl() . $id, $data);

        if (! $response->successful()) {
            return null;
        }

        return $response->object();
    }

    public static function delete($id) : bool
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->delete(self::getBaseUrl() . $id);

        if (! $response->successful()) {
            return false;
        }

        return true;
    }
}

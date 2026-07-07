<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class HscodeDataService
{
    private string $baseUrl;
    private const CACHE_KEY = 'hscode_data';

    public function __construct()
    {
        $this->baseUrl = config('services.native_api.url');
    }

    // Ambil semua data
    public function getAll(): array
    {
        return Cache::remember(self::CACHE_KEY, now()->addHours(1), function () {
            $response = Http::get("{$this->baseUrl}/data");

            if ($response->failed()) {
                return [];
            }

            return $response->json('data') ?? [];
        });
    }

    // Ambil satu data by rf
    public function findByRf(string $rf): array|null
    {
        $response = Http::get("{$this->baseUrl}/data/{$rf}");

        if ($response->failed()) {
            return null;
        }

        return $response->json('data');
    }
}

<?php
// filepath: /Users/robbys/WebProjects/Plojek_Laravel/pkl-surat-tugas/tests/TestCase.php

namespace Tests;

use Tests\CreatesApplication;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * ✅ Setup method - Clear cache before each test
     */
    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
    }

    /**
     * ✅ Helper: Assert cache exists
     */
    protected function assertCacheHas(string $key): void
    {
        $this->assertTrue(
            Cache::has($key),
            "Cache key [{$key}] does not exist."
        );
    }

    /**
     * ✅ Helper: Assert cache missing
     */
    protected function assertCacheMissing(string $key): void
    {
        $this->assertFalse(
            Cache::has($key),
            "Cache key [{$key}] still exists."
        );
    }

    /**
     * ✅ Helper: Get cache value
     */
    protected function getCacheValue(string $key)
    {
        return Cache::get($key);
    }
}
<?php

namespace CaiqueBispo\Itau\Tests\Unit;

use CaiqueBispo\Itau\Auth\MemoryTokenStorage;
use PHPUnit\Framework\TestCase;

class MemoryTokenStorageTest extends TestCase
{
    public function test_set_and_get(): void
    {
        $storage = new MemoryTokenStorage();
        $storage->set('key1', ['access_token' => 'abc123'], 600);

        $this->assertEquals('abc123', $storage->get('key1')['access_token']);
    }

    public function test_get_returns_null_for_missing_key(): void
    {
        $storage = new MemoryTokenStorage();
        $this->assertNull($storage->get('nonexistent'));
    }

    public function test_expired_token_returns_null(): void
    {
        $storage = new MemoryTokenStorage();
        $storage->set('key1', ['access_token' => 'abc123'], -1);

        $this->assertNull($storage->get('key1'));
    }

    public function test_overwrite_existing_key(): void
    {
        $storage = new MemoryTokenStorage();
        $storage->set('key1', ['access_token' => 'first'], 600);
        $storage->set('key1', ['access_token' => 'second'], 600);

        $this->assertEquals('second', $storage->get('key1')['access_token']);
    }
}

<?php

namespace CaiqueBispo\Itau\Auth;

use CaiqueBispo\Itau\Contracts\TokenStorageInterface;

class MemoryTokenStorage implements TokenStorageInterface
{
    /** @var array<string, array{data: array, expires_at: int}> */
    private array $tokens = [];

    public function get(string $key): ?array
    {
        if (! isset($this->tokens[$key])) {
            return null;
        }

        if ($this->tokens[$key]['expires_at'] <= time()) {
            unset($this->tokens[$key]);

            return null;
        }

        return $this->tokens[$key]['data'];
    }

    public function set(string $key, array $data, int $ttl): void
    {
        $this->tokens[$key] = [
            'data' => $data,
            'expires_at' => time() + $ttl,
        ];
    }
}

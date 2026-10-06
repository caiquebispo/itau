<?php

namespace CaiqueBispo\Itau\Contracts;

interface TokenStorageInterface
{
    public function get(string $key): ?array;

    public function set(string $key, array $data, int $ttl): void;
}

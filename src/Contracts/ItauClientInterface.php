<?php

namespace CaiqueBispo\Itau\Contracts;

use CaiqueBispo\Itau\Http\AccountContext;

interface ItauClientInterface
{
    public function get(string $path, array $query = []): array;

    public function post(string $path, array $data = [], array $query = []): array;

    public function put(string $path, array $data = [], array $query = []): array;

    public function patch(string $path, array $data = [], array $query = []): array;

    public function delete(string $path, array $data = [], array $query = []): array;

    public function context(): AccountContext;
}

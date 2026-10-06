<?php

namespace CaiqueBispo\Itau\Tests;

use CaiqueBispo\Itau\Contracts\ItauClientInterface;
use CaiqueBispo\Itau\Http\AccountContext;
use CaiqueBispo\Itau\ItauConfig;

class MockClient implements ItauClientInterface
{
    public array $calls = [];

    public array $nextResponse = [];

    private AccountContext $context;

    public function __construct(?AccountContext $context = null)
    {
        $this->context = $context ?? AccountContext::fromConfig(
            new ItauConfig([
                'environment' => 'sandbox',
                'contas' => [
                    'principal' => [
                        'client_id' => 'test-client-id',
                        'client_secret' => 'test-client-secret',
                        'agencia' => '0001',
                        'conta_corrente' => '12345',
                        'chave_pix' => 'chave-test@email.com',
                    ],
                ],
            ]),
            'principal',
        );
    }

    public function respondWith(array $response): void
    {
        $this->nextResponse = $response;
    }

    public function get(string $path, array $query = []): array
    {
        return $this->record('GET', $path, query: $query);
    }

    public function post(string $path, array $data = [], array $query = []): array
    {
        return $this->record('POST', $path, $data, $query);
    }

    public function put(string $path, array $data = [], array $query = []): array
    {
        return $this->record('PUT', $path, $data, $query);
    }

    public function patch(string $path, array $data = [], array $query = []): array
    {
        return $this->record('PATCH', $path, $data, $query);
    }

    public function delete(string $path, array $data = [], array $query = []): array
    {
        return $this->record('DELETE', $path, $data, $query);
    }

    public function context(): AccountContext
    {
        return $this->context;
    }

    public function lastCall(): ?array
    {
        return end($this->calls) ?: null;
    }

    public function callCount(): int
    {
        return count($this->calls);
    }

    private function record(string $method, string $path, array $data = [], array $query = []): array
    {
        $this->calls[] = [
            'method' => $method,
            'path' => $path,
            'data' => $data,
            'query' => $query,
        ];

        return $this->nextResponse;
    }
}

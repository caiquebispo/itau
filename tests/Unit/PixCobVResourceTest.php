<?php

namespace CaiqueBispo\Itau\Tests\Unit;

use CaiqueBispo\Itau\Resources\Pix\PixCobVResource;
use CaiqueBispo\Itau\Tests\MockClient;
use PHPUnit\Framework\TestCase;

class PixCobVResourceTest extends TestCase
{
    private MockClient $client;

    private PixCobVResource $resource;

    protected function setUp(): void
    {
        $this->client = new MockClient();
        $this->client->respondWith(['txid' => 'cobv-txid-123']);
        $this->resource = new PixCobVResource($this->client);
    }

    public function test_criar(): void
    {
        $this->resource->criar('cobv-txid-123', ['valor' => ['original' => '50.00']]);
        $call = $this->client->lastCall();

        $this->assertEquals('PUT', $call['method']);
        $this->assertEquals('cobv/cobv-txid-123', $call['path']);
    }

    public function test_consultar(): void
    {
        $this->resource->consultar('cobv-txid-123');
        $call = $this->client->lastCall();

        $this->assertEquals('GET', $call['method']);
        $this->assertEquals('cobv/cobv-txid-123', $call['path']);
    }

    public function test_revisar(): void
    {
        $this->resource->revisar('cobv-txid-123', ['status' => 'REMOVIDA_PELO_USUARIO_RECEBEDOR']);
        $call = $this->client->lastCall();

        $this->assertEquals('PATCH', $call['method']);
        $this->assertEquals('cobv/cobv-txid-123', $call['path']);
    }

    public function test_listar(): void
    {
        $this->resource->listar(['inicio' => '2025-01-01T00:00:00Z']);
        $call = $this->client->lastCall();

        $this->assertEquals('GET', $call['method']);
        $this->assertEquals('cobv', $call['path']);
    }
}

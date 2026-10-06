<?php

namespace CaiqueBispo\Itau\Tests\Unit;

use CaiqueBispo\Itau\Resources\Pix\PixLoteCobVResource;
use CaiqueBispo\Itau\Tests\MockClient;
use PHPUnit\Framework\TestCase;

class PixLoteCobVResourceTest extends TestCase
{
    private MockClient $client;

    private PixLoteCobVResource $resource;

    protected function setUp(): void
    {
        $this->client = new MockClient();
        $this->client->respondWith(['id' => 'lote-001']);
        $this->resource = new PixLoteCobVResource($this->client);
    }

    public function test_criar(): void
    {
        $this->resource->criar('lote-001', ['cobsv' => []]);
        $call = $this->client->lastCall();

        $this->assertEquals('PUT', $call['method']);
        $this->assertEquals('lotecobv/lote-001', $call['path']);
    }

    public function test_consultar(): void
    {
        $this->resource->consultar('lote-001');
        $call = $this->client->lastCall();

        $this->assertEquals('GET', $call['method']);
        $this->assertEquals('lotecobv/lote-001', $call['path']);
    }

    public function test_revisar(): void
    {
        $this->resource->revisar('lote-001', ['cobsv' => []]);
        $call = $this->client->lastCall();

        $this->assertEquals('PATCH', $call['method']);
        $this->assertEquals('lotecobv/lote-001', $call['path']);
    }

    public function test_listar(): void
    {
        $this->resource->listar();
        $call = $this->client->lastCall();

        $this->assertEquals('GET', $call['method']);
        $this->assertEquals('lotecobv', $call['path']);
    }
}

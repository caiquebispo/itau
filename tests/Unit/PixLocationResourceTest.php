<?php

namespace CaiqueBispo\Itau\Tests\Unit;

use CaiqueBispo\Itau\Resources\Pix\PixLocationResource;
use CaiqueBispo\Itau\Tests\MockClient;
use PHPUnit\Framework\TestCase;

class PixLocationResourceTest extends TestCase
{
    private MockClient $client;

    private PixLocationResource $resource;

    protected function setUp(): void
    {
        $this->client = new MockClient();
        $this->client->respondWith(['id' => 1]);
        $this->resource = new PixLocationResource($this->client);
    }

    public function test_criar(): void
    {
        $this->resource->criar(['tipoCob' => 'cob']);
        $call = $this->client->lastCall();

        $this->assertEquals('POST', $call['method']);
        $this->assertEquals('loc', $call['path']);
    }

    public function test_consultar(): void
    {
        $this->resource->consultar(1);
        $call = $this->client->lastCall();

        $this->assertEquals('GET', $call['method']);
        $this->assertEquals('loc/1', $call['path']);
    }

    public function test_listar(): void
    {
        $this->resource->listar();
        $call = $this->client->lastCall();

        $this->assertEquals('GET', $call['method']);
        $this->assertEquals('loc', $call['path']);
    }

    public function test_desvincular(): void
    {
        $this->resource->desvincular(1);
        $call = $this->client->lastCall();

        $this->assertEquals('DELETE', $call['method']);
        $this->assertEquals('loc/1/txid', $call['path']);
    }

    public function test_qrcode(): void
    {
        $this->resource->qrcode(1);
        $call = $this->client->lastCall();

        $this->assertEquals('GET', $call['method']);
        $this->assertEquals('loc/1/qrcode', $call['path']);
    }
}

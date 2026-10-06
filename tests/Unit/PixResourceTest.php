<?php

namespace CaiqueBispo\Itau\Tests\Unit;

use CaiqueBispo\Itau\Resources\Pix\PixResource;
use CaiqueBispo\Itau\Tests\MockClient;
use PHPUnit\Framework\TestCase;

class PixResourceTest extends TestCase
{
    private MockClient $client;

    private PixResource $resource;

    protected function setUp(): void
    {
        $this->client = new MockClient();
        $this->client->respondWith(['endToEndId' => 'E00000000202401011234abc']);
        $this->resource = new PixResource($this->client);
    }

    public function test_consultar(): void
    {
        $this->resource->consultar('E00000000202401011234abc');
        $call = $this->client->lastCall();

        $this->assertEquals('GET', $call['method']);
        $this->assertEquals('pix/E00000000202401011234abc', $call['path']);
    }

    public function test_listar(): void
    {
        $this->resource->listar(['inicio' => '2025-01-01T00:00:00Z', 'fim' => '2025-12-31T23:59:59Z']);
        $call = $this->client->lastCall();

        $this->assertEquals('GET', $call['method']);
        $this->assertEquals('pix', $call['path']);
    }

    public function test_devolver(): void
    {
        $this->resource->devolver('E00000000202401011234abc', 'dev-001', ['valor' => '10.00']);
        $call = $this->client->lastCall();

        $this->assertEquals('PUT', $call['method']);
        $this->assertEquals('pix/E00000000202401011234abc/devolucao/dev-001', $call['path']);
    }

    public function test_consultar_devolucao(): void
    {
        $this->resource->consultarDevolucao('E00000000202401011234abc', 'dev-001');
        $call = $this->client->lastCall();

        $this->assertEquals('GET', $call['method']);
        $this->assertEquals('pix/E00000000202401011234abc/devolucao/dev-001', $call['path']);
    }
}

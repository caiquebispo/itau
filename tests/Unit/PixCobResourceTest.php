<?php

namespace CaiqueBispo\Itau\Tests\Unit;

use CaiqueBispo\Itau\Resources\Pix\PixCobResource;
use CaiqueBispo\Itau\Tests\MockClient;
use PHPUnit\Framework\TestCase;

class PixCobResourceTest extends TestCase
{
    private MockClient $client;

    private PixCobResource $resource;

    protected function setUp(): void
    {
        $this->client = new MockClient();
        $this->client->respondWith(['txid' => 'fc9a4366b6e6474aa044']);
        $this->resource = new PixCobResource($this->client);
    }

    public function test_criar(): void
    {
        $this->resource->criar(['calendario' => ['expiracao' => 3600], 'valor' => ['original' => '10.00']]);
        $call = $this->client->lastCall();

        $this->assertEquals('POST', $call['method']);
        $this->assertEquals('cob', $call['path']);
    }

    public function test_criar_com_txid(): void
    {
        $this->resource->criarComTxid('fc9a4366b6e6474aa044', ['valor' => ['original' => '10.00']]);
        $call = $this->client->lastCall();

        $this->assertEquals('PUT', $call['method']);
        $this->assertEquals('cob/fc9a4366b6e6474aa044', $call['path']);
    }

    public function test_consultar(): void
    {
        $this->resource->consultar('fc9a4366b6e6474aa044');
        $call = $this->client->lastCall();

        $this->assertEquals('GET', $call['method']);
        $this->assertEquals('cob/fc9a4366b6e6474aa044', $call['path']);
    }

    public function test_revisar(): void
    {
        $this->resource->revisar('fc9a4366b6e6474aa044', ['status' => 'REMOVIDA_PELO_USUARIO_RECEBEDOR']);
        $call = $this->client->lastCall();

        $this->assertEquals('PATCH', $call['method']);
        $this->assertEquals('cob/fc9a4366b6e6474aa044', $call['path']);
    }

    public function test_listar(): void
    {
        $this->resource->listar(['inicio' => '2025-01-01T00:00:00Z', 'fim' => '2025-12-31T23:59:59Z']);
        $call = $this->client->lastCall();

        $this->assertEquals('GET', $call['method']);
        $this->assertEquals('cob', $call['path']);
    }
}

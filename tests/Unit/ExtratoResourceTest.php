<?php

namespace CaiqueBispo\Itau\Tests\Unit;

use CaiqueBispo\Itau\Resources\Extrato\ExtratoResource;
use CaiqueBispo\Itau\Tests\MockClient;
use PHPUnit\Framework\TestCase;

class ExtratoResourceTest extends TestCase
{
    private MockClient $client;

    private ExtratoResource $resource;

    protected function setUp(): void
    {
        $this->client = new MockClient();
        $this->client->respondWith(['saldo' => 15000.00]);
        $this->resource = new ExtratoResource($this->client);
    }

    public function test_saldo(): void
    {
        $result = $this->resource->saldo();
        $call = $this->client->lastCall();

        $this->assertEquals('GET', $call['method']);
        $this->assertEquals('saldo', $call['path']);
        $this->assertEquals(15000.00, $result['saldo']);
    }

    public function test_extrato(): void
    {
        $this->resource->extrato(['dataInicio' => '2025-01-01', 'dataFim' => '2025-01-31']);
        $call = $this->client->lastCall();

        $this->assertEquals('GET', $call['method']);
        $this->assertEquals('extrato', $call['path']);
    }

    public function test_extrato_detalhado(): void
    {
        $this->resource->extratoDetalhado(['dataInicio' => '2025-01-01']);
        $call = $this->client->lastCall();

        $this->assertEquals('GET', $call['method']);
        $this->assertEquals('extrato-detalhado', $call['path']);
    }
}

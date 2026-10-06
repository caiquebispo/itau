<?php

namespace CaiqueBispo\Itau\Tests\Unit;

use CaiqueBispo\Itau\Resources\Cobranca\CobrancaResource;
use CaiqueBispo\Itau\Tests\MockClient;
use PHPUnit\Framework\TestCase;

class CobrancaResourceTest extends TestCase
{
    private MockClient $client;

    private CobrancaResource $resource;

    protected function setUp(): void
    {
        $this->client = new MockClient();
        $this->client->respondWith(['nosso_numero' => '12345678']);
        $this->resource = new CobrancaResource($this->client);
    }

    public function test_registrar(): void
    {
        $this->resource->registrar(['valor' => 100.00]);
        $call = $this->client->lastCall();

        $this->assertEquals('POST', $call['method']);
        $this->assertEquals('boletos', $call['path']);
        $this->assertEquals(['valor' => 100.00], $call['data']);
    }

    public function test_consultar(): void
    {
        $this->resource->consultar('12345678');
        $call = $this->client->lastCall();

        $this->assertEquals('GET', $call['method']);
        $this->assertEquals('boletos/12345678', $call['path']);
    }

    public function test_listar(): void
    {
        $this->resource->listar(['id_beneficiario' => '00123', 'codigo_carteira' => '109']);
        $call = $this->client->lastCall();

        $this->assertEquals('GET', $call['method']);
        $this->assertEquals('boletos', $call['path']);
        $this->assertEquals(['id_beneficiario' => '00123', 'codigo_carteira' => '109'], $call['query']);
    }

    public function test_alterar(): void
    {
        $this->resource->alterar('12345678', ['data_vencimento' => '2025-01-31']);
        $call = $this->client->lastCall();

        $this->assertEquals('PATCH', $call['method']);
        $this->assertEquals('boletos/12345678', $call['path']);
    }

    public function test_baixar(): void
    {
        $this->resource->baixar('12345678');
        $call = $this->client->lastCall();

        $this->assertEquals('PATCH', $call['method']);
        $this->assertEquals('boletos/12345678/baixa', $call['path']);
    }

    public function test_consultar_qr_code(): void
    {
        $this->resource->consultarQrCode('12345678');
        $call = $this->client->lastCall();

        $this->assertEquals('GET', $call['method']);
        $this->assertEquals('boletos/12345678/qrcode', $call['path']);
    }
}

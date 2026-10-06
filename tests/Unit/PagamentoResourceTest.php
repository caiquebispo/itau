<?php

namespace CaiqueBispo\Itau\Tests\Unit;

use CaiqueBispo\Itau\Resources\Pagamento\PagamentoResource;
use CaiqueBispo\Itau\Tests\MockClient;
use PHPUnit\Framework\TestCase;

class PagamentoResourceTest extends TestCase
{
    private MockClient $client;

    private PagamentoResource $resource;

    protected function setUp(): void
    {
        $this->client = new MockClient();
        $this->client->respondWith(['id' => '12345']);
        $this->resource = new PagamentoResource($this->client);
    }

    public function test_transferir(): void
    {
        $this->resource->transferir(['valor' => 1000]);
        $call = $this->client->lastCall();

        $this->assertEquals('POST', $call['method']);
        $this->assertEquals('transferencias', $call['path']);
    }

    public function test_consultar_transferencia(): void
    {
        $this->resource->consultarTransferencia('12345');
        $call = $this->client->lastCall();

        $this->assertEquals('GET', $call['method']);
        $this->assertEquals('transferencias/12345', $call['path']);
    }

    public function test_listar_transferencias(): void
    {
        $this->resource->listarTransferencias();
        $call = $this->client->lastCall();

        $this->assertEquals('GET', $call['method']);
        $this->assertEquals('transferencias', $call['path']);
    }

    public function test_pagar_boleto(): void
    {
        $this->resource->pagarBoleto(['codigoBarras' => '23793381286000']);
        $call = $this->client->lastCall();

        $this->assertEquals('POST', $call['method']);
        $this->assertEquals('boletos', $call['path']);
    }

    public function test_consultar_pagamento_boleto(): void
    {
        $this->resource->consultarPagamentoBoleto('12345');
        $call = $this->client->lastCall();

        $this->assertEquals('GET', $call['method']);
        $this->assertEquals('boletos/12345', $call['path']);
    }

    public function test_listar_pagamentos_boletos(): void
    {
        $this->resource->listarPagamentosBoletos();
        $call = $this->client->lastCall();

        $this->assertEquals('GET', $call['method']);
        $this->assertEquals('boletos', $call['path']);
    }

    public function test_pagar_darf(): void
    {
        $this->resource->pagarDarf(['codigoReceita' => '0190']);
        $call = $this->client->lastCall();

        $this->assertEquals('POST', $call['method']);
        $this->assertEquals('darf', $call['path']);
    }

    public function test_pagar_gps(): void
    {
        $this->resource->pagarGps(['codigoPagamento' => '2100']);
        $call = $this->client->lastCall();

        $this->assertEquals('POST', $call['method']);
        $this->assertEquals('gps', $call['path']);
    }

    public function test_pagar_gare(): void
    {
        $this->resource->pagarGare(['codigoRecolhimento' => '123']);
        $call = $this->client->lastCall();

        $this->assertEquals('POST', $call['method']);
        $this->assertEquals('gare', $call['path']);
    }

    public function test_pagar_tributo(): void
    {
        $this->resource->pagarTributo(['codigoBarras' => '858000000']);
        $call = $this->client->lastCall();

        $this->assertEquals('POST', $call['method']);
        $this->assertEquals('tributos', $call['path']);
    }

    public function test_consultar_lote(): void
    {
        $this->resource->consultarLote('lote-001');
        $call = $this->client->lastCall();

        $this->assertEquals('GET', $call['method']);
        $this->assertEquals('lotes/lote-001', $call['path']);
    }

    public function test_cancelar_pagamento(): void
    {
        $this->resource->cancelarPagamento('12345');
        $call = $this->client->lastCall();

        $this->assertEquals('POST', $call['method']);
        $this->assertEquals('pagamentos/12345/cancelar', $call['path']);
    }
}

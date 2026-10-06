<?php

namespace CaiqueBispo\Itau\Tests\Unit;

use CaiqueBispo\Itau\Itau;
use CaiqueBispo\Itau\ItauConfig;
use CaiqueBispo\Itau\Resources\Cobranca\CobrancaResource;
use CaiqueBispo\Itau\Resources\Extrato\ExtratoResource;
use CaiqueBispo\Itau\Resources\Pagamento\PagamentoResource;
use CaiqueBispo\Itau\Resources\Pix\PixCobResource;
use CaiqueBispo\Itau\Resources\Pix\PixCobVResource;
use CaiqueBispo\Itau\Resources\Pix\PixLocationResource;
use CaiqueBispo\Itau\Resources\Pix\PixLoteCobVResource;
use CaiqueBispo\Itau\Resources\Pix\PixResource;
use CaiqueBispo\Itau\Resources\Pix\PixWebhookResource;
use PHPUnit\Framework\TestCase;

class ItauTest extends TestCase
{
    private function makeConfig(): ItauConfig
    {
        return new ItauConfig([
            'environment' => 'sandbox',
            'contas' => [
                'principal' => [
                    'client_id' => 'test-id',
                    'client_secret' => 'test-secret',
                    'agencia' => '0001',
                    'conta_corrente' => '12345',
                ],
                'filial' => [
                    'client_id' => 'filial-id',
                    'client_secret' => 'filial-secret',
                    'agencia' => '0002',
                    'conta_corrente' => '67890',
                ],
            ],
        ]);
    }

    public function test_make_factory(): void
    {
        $itau = Itau::make([
            'environment' => 'sandbox',
            'contas' => [
                'principal' => [
                    'client_id' => 'id',
                    'client_secret' => 'secret',
                ],
            ],
        ]);

        $this->assertInstanceOf(Itau::class, $itau);
    }

    public function test_constructor(): void
    {
        $itau = new Itau($this->makeConfig());
        $this->assertInstanceOf(Itau::class, $itau);
    }

    public function test_conta_retorna_nova_instancia(): void
    {
        $itau = new Itau($this->makeConfig());
        $filial = $itau->conta('filial');

        $this->assertInstanceOf(Itau::class, $filial);
        $this->assertNotSame($itau, $filial);
    }

    public function test_resource_cobrancas(): void
    {
        $this->assertInstanceOf(CobrancaResource::class, (new Itau($this->makeConfig()))->cobrancas());
    }

    public function test_resource_pix_cob(): void
    {
        $this->assertInstanceOf(PixCobResource::class, (new Itau($this->makeConfig()))->pixCob());
    }

    public function test_resource_pix_cobv(): void
    {
        $this->assertInstanceOf(PixCobVResource::class, (new Itau($this->makeConfig()))->pixCobV());
    }

    public function test_resource_pix_lote_cobv(): void
    {
        $this->assertInstanceOf(PixLoteCobVResource::class, (new Itau($this->makeConfig()))->pixLoteCobV());
    }

    public function test_resource_pix(): void
    {
        $this->assertInstanceOf(PixResource::class, (new Itau($this->makeConfig()))->pix());
    }

    public function test_resource_pix_webhook(): void
    {
        $this->assertInstanceOf(PixWebhookResource::class, (new Itau($this->makeConfig()))->pixWebhook());
    }

    public function test_resource_pix_location(): void
    {
        $this->assertInstanceOf(PixLocationResource::class, (new Itau($this->makeConfig()))->pixLocation());
    }

    public function test_resource_pagamentos(): void
    {
        $this->assertInstanceOf(PagamentoResource::class, (new Itau($this->makeConfig()))->pagamentos());
    }

    public function test_resource_extratos(): void
    {
        $this->assertInstanceOf(ExtratoResource::class, (new Itau($this->makeConfig()))->extratos());
    }

    public function test_config_accessor(): void
    {
        $config = $this->makeConfig();
        $itau = new Itau($config);
        $this->assertSame($config, $itau->config());
    }
}

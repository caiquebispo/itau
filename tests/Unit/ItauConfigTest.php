<?php

namespace CaiqueBispo\Itau\Tests\Unit;

use CaiqueBispo\Itau\Enums\Environment;
use CaiqueBispo\Itau\Exceptions\ContaNaoConfiguradaException;
use CaiqueBispo\Itau\ItauConfig;
use PHPUnit\Framework\TestCase;

class ItauConfigTest extends TestCase
{
    private function makeConfig(array $overrides = []): ItauConfig
    {
        return new ItauConfig(array_merge([
            'environment' => 'sandbox',
            'contas' => [
                'principal' => [
                    'client_id' => 'test-client-id',
                    'client_secret' => 'test-client-secret',
                    'agencia' => '0001',
                    'conta_corrente' => '12345',
                    'chave_pix' => 'chave@test.com',
                    'certificado' => [
                        'path' => '/certs/cobranca.crt',
                        'senha' => 'senha-cobranca',
                    ],
                    'pagamento' => [
                        'client_id' => 'pag-client-id',
                        'client_secret' => 'pag-client-secret',
                        'certificado' => [
                            'path' => '/certs/pagamento.crt',
                            'senha' => 'senha-pagamento',
                        ],
                    ],
                    'extrato' => [
                        'client_id' => 'ext-client-id',
                        'client_secret' => 'ext-client-secret',
                        'certificado' => [
                            'path' => '/certs/extrato.crt',
                            'senha' => 'senha-extrato',
                        ],
                    ],
                ],
                'filial' => [
                    'client_id' => 'filial-client-id',
                    'client_secret' => 'filial-secret',
                    'agencia' => '0002',
                    'conta_corrente' => '67890',
                ],
            ],
            'default' => 'principal',
            'timeout' => 30,
            'retry' => ['times' => 3, 'sleep' => 500],
            'webhook' => ['secret' => 'webhook-secret'],
        ], $overrides));
    }

    public function test_environment(): void
    {
        $config = $this->makeConfig();
        $this->assertEquals(Environment::Sandbox, $config->environment());
    }

    public function test_producao_environment(): void
    {
        $config = $this->makeConfig(['environment' => 'producao']);
        $this->assertEquals(Environment::Producao, $config->environment());
    }

    public function test_default_conta(): void
    {
        $this->assertEquals('principal', $this->makeConfig()->defaultConta());
    }

    public function test_client_id(): void
    {
        $this->assertEquals('test-client-id', $this->makeConfig()->clientId('principal'));
    }

    public function test_client_secret(): void
    {
        $this->assertEquals('test-client-secret', $this->makeConfig()->clientSecret('principal'));
    }

    public function test_agencia(): void
    {
        $this->assertEquals('0001', $this->makeConfig()->agencia('principal'));
    }

    public function test_conta_corrente(): void
    {
        $this->assertEquals('12345', $this->makeConfig()->contaCorrente('principal'));
    }

    public function test_chave_pix(): void
    {
        $this->assertEquals('chave@test.com', $this->makeConfig()->chavePix('principal'));
    }

    public function test_certificado_padrao(): void
    {
        $config = $this->makeConfig();
        $this->assertEquals('/certs/cobranca.crt', $config->certificadoPath('principal'));
        $this->assertEquals('senha-cobranca', $config->certificadoSenha('principal'));
    }

    public function test_pagamento_credenciais_isoladas(): void
    {
        $config = $this->makeConfig();
        $this->assertEquals('pag-client-id', $config->pagamentoClientId('principal'));
        $this->assertEquals('pag-client-secret', $config->pagamentoClientSecret('principal'));
        $this->assertEquals('/certs/pagamento.crt', $config->pagamentoCertificadoPath('principal'));
        $this->assertEquals('senha-pagamento', $config->pagamentoCertificadoSenha('principal'));
    }

    public function test_extrato_credenciais_isoladas(): void
    {
        $config = $this->makeConfig();
        $this->assertEquals('ext-client-id', $config->extratoClientId('principal'));
        $this->assertEquals('ext-client-secret', $config->extratoClientSecret('principal'));
        $this->assertEquals('/certs/extrato.crt', $config->extratoCertificadoPath('principal'));
        $this->assertEquals('senha-extrato', $config->extratoCertificadoSenha('principal'));
    }

    public function test_pagamento_fallback_para_padrao(): void
    {
        $config = $this->makeConfig();
        $this->assertEquals('filial-client-id', $config->pagamentoClientId('filial'));
        $this->assertEquals('filial-secret', $config->pagamentoClientSecret('filial'));
    }

    public function test_extrato_fallback_para_padrao(): void
    {
        $config = $this->makeConfig();
        $this->assertEquals('filial-client-id', $config->extratoClientId('filial'));
        $this->assertEquals('filial-secret', $config->extratoClientSecret('filial'));
    }

    public function test_conta_nao_configurada(): void
    {
        $this->expectException(ContaNaoConfiguradaException::class);
        $this->makeConfig()->conta('inexistente');
    }

    public function test_timeout(): void
    {
        $this->assertEquals(30, $this->makeConfig()->timeout());
    }

    public function test_retry(): void
    {
        $config = $this->makeConfig();
        $this->assertEquals(3, $config->retryTimes());
        $this->assertEquals(500, $config->retrySleep());
    }

    public function test_webhook_secret(): void
    {
        $this->assertEquals('webhook-secret', $this->makeConfig()->webhookSecret());
    }
}

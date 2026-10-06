<?php

namespace CaiqueBispo\Itau\Tests\Unit;

use CaiqueBispo\Itau\Enums\Environment;
use CaiqueBispo\Itau\Http\AccountContext;
use CaiqueBispo\Itau\ItauConfig;
use PHPUnit\Framework\TestCase;

class AccountContextTest extends TestCase
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
                    'chave_pix' => 'chave@test.com',
                    'certificado' => [
                        'path' => '/certs/cobranca.crt',
                        'senha' => 'senha-cobranca',
                    ],
                    'pagamento' => [
                        'client_id' => 'pag-id',
                        'client_secret' => 'pag-secret',
                        'certificado' => [
                            'path' => '/certs/pagamento.crt',
                            'senha' => 'senha-pag',
                        ],
                    ],
                    'extrato' => [
                        'client_id' => 'ext-id',
                        'client_secret' => 'ext-secret',
                        'certificado' => [
                            'path' => '/certs/extrato.crt',
                            'senha' => 'senha-ext',
                        ],
                    ],
                ],
            ],
        ]);
    }

    public function test_from_config(): void
    {
        $context = AccountContext::fromConfig($this->makeConfig(), 'principal');

        $this->assertEquals('principal', $context->slug);
        $this->assertEquals('test-id', $context->clientId);
        $this->assertEquals('test-secret', $context->clientSecret);
        $this->assertEquals('0001', $context->agencia);
        $this->assertEquals('12345', $context->contaCorrente);
        $this->assertEquals('chave@test.com', $context->chavePix);
        $this->assertEquals('/certs/cobranca.crt', $context->certificadoPath);
        $this->assertEquals(Environment::Sandbox, $context->environment);
    }

    public function test_for_pagamento_usa_credenciais_isoladas(): void
    {
        $context = AccountContext::forPagamento($this->makeConfig(), 'principal');

        $this->assertEquals('pag-id', $context->clientId);
        $this->assertEquals('pag-secret', $context->clientSecret);
        $this->assertEquals('/certs/pagamento.crt', $context->certificadoPath);
        $this->assertEquals('senha-pag', $context->certificadoSenha);
        $this->assertNull($context->chavePix);
    }

    public function test_for_extrato_usa_credenciais_isoladas(): void
    {
        $context = AccountContext::forExtrato($this->makeConfig(), 'principal');

        $this->assertEquals('ext-id', $context->clientId);
        $this->assertEquals('ext-secret', $context->clientSecret);
        $this->assertEquals('/certs/extrato.crt', $context->certificadoPath);
        $this->assertEquals('senha-ext', $context->certificadoSenha);
    }

    public function test_with_environment(): void
    {
        $context = AccountContext::fromConfig($this->makeConfig(), 'principal');
        $prod = $context->withEnvironment(Environment::Producao);

        $this->assertEquals(Environment::Producao, $prod->environment);
        $this->assertEquals('principal', $prod->slug);
        $this->assertNotSame($context, $prod);
    }
}

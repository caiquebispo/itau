<?php

namespace CaiqueBispo\Itau\Tests\Unit;

use CaiqueBispo\Itau\Enums\Environment;
use PHPUnit\Framework\TestCase;

class EnvironmentEnumTest extends TestCase
{
    public function test_sandbox_oauth_url(): void
    {
        $this->assertEquals('https://devportal.itau.com.br/api/jwt', Environment::Sandbox->oauthUrl());
    }

    public function test_producao_oauth_url(): void
    {
        $this->assertEquals('https://sts.itau.com.br/api/oauth/token', Environment::Producao->oauthUrl());
    }

    public function test_sandbox_pix_url(): void
    {
        $this->assertStringContainsString('pix_recebimentos_ext_v2', Environment::Sandbox->pixBaseUrl());
    }

    public function test_producao_pix_url(): void
    {
        $this->assertEquals('https://secure.api.itau/pix_recebimentos/v2', Environment::Producao->pixBaseUrl());
    }

    public function test_sandbox_cobranca_url(): void
    {
        $this->assertStringContainsString('cash_management_ext_v2', Environment::Sandbox->cobrancaBaseUrl());
    }

    public function test_producao_cobranca_url(): void
    {
        $this->assertEquals('https://secure.api.itau/boletoscash/v2', Environment::Producao->cobrancaBaseUrl());
    }

    public function test_sandbox_pagamento_url(): void
    {
        $this->assertStringContainsString('pagamentos_ext_v2', Environment::Sandbox->pagamentoBaseUrl());
    }

    public function test_sandbox_extrato_url(): void
    {
        $this->assertStringContainsString('extrato_conta_corrente_ext_v2', Environment::Sandbox->extratoBaseUrl());
    }

    public function test_from_string(): void
    {
        $this->assertEquals(Environment::Sandbox, Environment::from('sandbox'));
        $this->assertEquals(Environment::Producao, Environment::from('producao'));
    }
}

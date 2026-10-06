<?php

namespace CaiqueBispo\Itau\Enums;

enum Environment: string
{
    case Sandbox = 'sandbox';
    case Producao = 'producao';

    public function oauthUrl(): string
    {
        return match ($this) {
            self::Sandbox => 'https://devportal.itau.com.br/api/jwt',
            self::Producao => 'https://sts.itau.com.br/api/oauth/token',
        };
    }

    public function pixBaseUrl(): string
    {
        return match ($this) {
            self::Sandbox => 'https://devportal.itau.com.br/sandboxapi/pix_recebimentos_ext_v2/v2',
            self::Producao => 'https://secure.api.itau/pix_recebimentos/v2',
        };
    }

    public function cobrancaBaseUrl(): string
    {
        return match ($this) {
            self::Sandbox => 'https://devportal.itau.com.br/sandboxapi/cash_management_ext_v2/v2',
            self::Producao => 'https://secure.api.itau/boletoscash/v2',
        };
    }

    public function pagamentoBaseUrl(): string
    {
        return match ($this) {
            self::Sandbox => 'https://devportal.itau.com.br/sandboxapi/pagamentos_ext_v2/v2',
            self::Producao => 'https://secure.api.itau/pagamentos/v2',
        };
    }

    public function extratoBaseUrl(): string
    {
        return match ($this) {
            self::Sandbox => 'https://devportal.itau.com.br/sandboxapi/extrato_conta_corrente_ext_v2/v2',
            self::Producao => 'https://secure.api.itau/extrato_conta_corrente/v2',
        };
    }
}

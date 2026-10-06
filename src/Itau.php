<?php

namespace CaiqueBispo\Itau;

use CaiqueBispo\Itau\Auth\OAuthTokenManager;
use CaiqueBispo\Itau\Contracts\ItauClientInterface;
use CaiqueBispo\Itau\Enums\Environment;
use CaiqueBispo\Itau\Http\AccountContext;
use CaiqueBispo\Itau\Http\ItauClient;
use CaiqueBispo\Itau\Resources\Cobranca\CobrancaResource;
use CaiqueBispo\Itau\Resources\Extrato\ExtratoResource;
use CaiqueBispo\Itau\Resources\Pagamento\PagamentoResource;
use CaiqueBispo\Itau\Resources\Pix\PixCobResource;
use CaiqueBispo\Itau\Resources\Pix\PixCobVResource;
use CaiqueBispo\Itau\Resources\Pix\PixLocationResource;
use CaiqueBispo\Itau\Resources\Pix\PixLoteCobVResource;
use CaiqueBispo\Itau\Resources\Pix\PixResource;
use CaiqueBispo\Itau\Resources\Pix\PixWebhookResource;

class Itau
{
    private ItauConfig $config;

    private OAuthTokenManager $tokenManager;

    private string $contaSlug;

    private ?Environment $environmentOverride = null;

    public function __construct(ItauConfig $config, ?OAuthTokenManager $tokenManager = null)
    {
        $this->config = $config;
        $this->tokenManager = $tokenManager ?? new OAuthTokenManager();
        $this->contaSlug = $config->defaultConta();
    }

    public static function make(array $config): static
    {
        return new static(new ItauConfig($config));
    }

    public function conta(string $slug): static
    {
        $clone = clone $this;
        $clone->contaSlug = $slug;

        return $clone;
    }

    public function environment(Environment $environment): static
    {
        $clone = clone $this;
        $clone->environmentOverride = $environment;

        return $clone;
    }

    // --- Cobrança + PIX (certificado padrão) ---

    public function cobrancas(): CobrancaResource
    {
        return new CobrancaResource($this->cobrancaClient());
    }

    public function pixCob(): PixCobResource
    {
        return new PixCobResource($this->pixClient());
    }

    public function pixCobV(): PixCobVResource
    {
        return new PixCobVResource($this->pixClient());
    }

    public function pixLoteCobV(): PixLoteCobVResource
    {
        return new PixLoteCobVResource($this->pixClient());
    }

    public function pix(): PixResource
    {
        return new PixResource($this->pixClient());
    }

    public function pixWebhook(): PixWebhookResource
    {
        return new PixWebhookResource($this->pixClient());
    }

    public function pixLocation(): PixLocationResource
    {
        return new PixLocationResource($this->pixClient());
    }

    // --- Pagamento (certificado + token próprios) ---

    public function pagamentos(): PagamentoResource
    {
        return new PagamentoResource($this->pagamentoClient());
    }

    // --- Extrato (certificado + token próprios) ---

    public function extratos(): ExtratoResource
    {
        return new ExtratoResource($this->extratoClient());
    }

    // --- Accessors ---

    public function config(): ItauConfig
    {
        return $this->config;
    }

    // --- Client factories (isolados por módulo) ---

    private function cobrancaClient(): ItauClientInterface
    {
        $env = $this->resolveEnvironment();
        $context = AccountContext::fromConfig($this->config, $this->contaSlug, $env);

        return new ItauClient($context, $env->cobrancaBaseUrl(), $this->tokenManager);
    }

    private function pixClient(): ItauClientInterface
    {
        $env = $this->resolveEnvironment();
        $context = AccountContext::fromConfig($this->config, $this->contaSlug, $env);

        return new ItauClient($context, $env->pixBaseUrl(), $this->tokenManager);
    }

    private function pagamentoClient(): ItauClientInterface
    {
        $env = $this->resolveEnvironment();
        $context = AccountContext::forPagamento($this->config, $this->contaSlug, $env);

        return new ItauClient($context, $env->pagamentoBaseUrl(), $this->tokenManager);
    }

    private function extratoClient(): ItauClientInterface
    {
        $env = $this->resolveEnvironment();
        $context = AccountContext::forExtrato($this->config, $this->contaSlug, $env);

        return new ItauClient($context, $env->extratoBaseUrl(), $this->tokenManager);
    }

    private function resolveEnvironment(): Environment
    {
        return $this->environmentOverride ?? $this->config->environment();
    }
}

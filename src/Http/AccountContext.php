<?php

namespace CaiqueBispo\Itau\Http;

use CaiqueBispo\Itau\Enums\Environment;
use CaiqueBispo\Itau\ItauConfig;

class AccountContext
{
    public function __construct(
        public readonly string $slug,
        public readonly string $clientId,
        public readonly string $clientSecret,
        public readonly ?string $agencia,
        public readonly ?string $contaCorrente,
        public readonly ?string $chavePix,
        public readonly ?string $certificadoPath,
        public readonly ?string $certificadoSenha,
        public readonly Environment $environment,
        public readonly ItauConfig $config,
    ) {}

    public static function fromConfig(ItauConfig $config, string $slug, ?Environment $environment = null): static
    {
        $env = $environment ?? $config->environment();

        return new static(
            slug: $slug,
            clientId: $config->clientId($slug),
            clientSecret: $config->clientSecret($slug),
            agencia: $config->agencia($slug),
            contaCorrente: $config->contaCorrente($slug),
            chavePix: $config->chavePix($slug),
            certificadoPath: $config->certificadoPath($slug),
            certificadoSenha: $config->certificadoSenha($slug),
            environment: $env,
            config: $config,
        );
    }

    public static function forPagamento(ItauConfig $config, string $slug, ?Environment $environment = null): static
    {
        $env = $environment ?? $config->environment();

        return new static(
            slug: $slug,
            clientId: $config->pagamentoClientId($slug),
            clientSecret: $config->pagamentoClientSecret($slug),
            agencia: $config->agencia($slug),
            contaCorrente: $config->contaCorrente($slug),
            chavePix: null,
            certificadoPath: $config->pagamentoCertificadoPath($slug),
            certificadoSenha: $config->pagamentoCertificadoSenha($slug),
            environment: $env,
            config: $config,
        );
    }

    public static function forExtrato(ItauConfig $config, string $slug, ?Environment $environment = null): static
    {
        $env = $environment ?? $config->environment();

        return new static(
            slug: $slug,
            clientId: $config->extratoClientId($slug),
            clientSecret: $config->extratoClientSecret($slug),
            agencia: $config->agencia($slug),
            contaCorrente: $config->contaCorrente($slug),
            chavePix: null,
            certificadoPath: $config->extratoCertificadoPath($slug),
            certificadoSenha: $config->extratoCertificadoSenha($slug),
            environment: $env,
            config: $config,
        );
    }

    public function withEnvironment(Environment $environment): static
    {
        return new static(
            slug: $this->slug,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            agencia: $this->agencia,
            contaCorrente: $this->contaCorrente,
            chavePix: $this->chavePix,
            certificadoPath: $this->certificadoPath,
            certificadoSenha: $this->certificadoSenha,
            environment: $environment,
            config: $this->config,
        );
    }

    public function withConta(string $slug): static
    {
        return static::fromConfig($this->config, $slug, $this->environment);
    }
}

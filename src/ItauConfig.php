<?php

namespace CaiqueBispo\Itau;

use CaiqueBispo\Itau\Enums\Environment;
use CaiqueBispo\Itau\Exceptions\ContaNaoConfiguradaException;

class ItauConfig
{
    private Environment $environment;

    private array $contas;

    private string $default;

    private int $timeout;

    private int $retryTimes;

    private int $retrySleep;

    private ?string $webhookSecret;

    public function __construct(array $config)
    {
        $this->environment = Environment::from($config['environment'] ?? 'sandbox');
        $this->contas = $config['contas'] ?? [];
        $this->default = $config['default'] ?? array_key_first($this->contas) ?? 'principal';
        $this->timeout = $config['timeout'] ?? 30;
        $this->retryTimes = $config['retry']['times'] ?? 3;
        $this->retrySleep = $config['retry']['sleep'] ?? 500;
        $this->webhookSecret = $config['webhook']['secret'] ?? null;
    }

    public function environment(): Environment
    {
        return $this->environment;
    }

    public function defaultConta(): string
    {
        return $this->default;
    }

    public function conta(string $slug): array
    {
        if (! isset($this->contas[$slug])) {
            throw ContaNaoConfiguradaException::make($slug);
        }

        return $this->contas[$slug];
    }

    public function clientId(string $slug): string
    {
        return $this->conta($slug)['client_id'];
    }

    public function clientSecret(string $slug): string
    {
        return $this->conta($slug)['client_secret'];
    }

    public function agencia(string $slug): ?string
    {
        return $this->conta($slug)['agencia'] ?? null;
    }

    public function contaCorrente(string $slug): ?string
    {
        return $this->conta($slug)['conta_corrente'] ?? null;
    }

    public function chavePix(string $slug): ?string
    {
        return $this->conta($slug)['chave_pix'] ?? null;
    }

    // --- Certificado padrão (Cobrança + PIX) ---

    public function certificadoPath(string $slug): ?string
    {
        return $this->conta($slug)['certificado']['path'] ?? null;
    }

    public function certificadoSenha(string $slug): ?string
    {
        return $this->conta($slug)['certificado']['senha'] ?? null;
    }

    // --- Credenciais isoladas para Pagamento + Extrato ---

    public function pagamentoClientId(string $slug): ?string
    {
        $conta = $this->conta($slug);

        return $conta['pagamento']['client_id'] ?? $conta['client_id'];
    }

    public function pagamentoClientSecret(string $slug): ?string
    {
        $conta = $this->conta($slug);

        return $conta['pagamento']['client_secret'] ?? $conta['client_secret'];
    }

    public function pagamentoCertificadoPath(string $slug): ?string
    {
        $conta = $this->conta($slug);

        return $conta['pagamento']['certificado']['path']
            ?? $conta['certificado']['path']
            ?? null;
    }

    public function pagamentoCertificadoSenha(string $slug): ?string
    {
        $conta = $this->conta($slug);

        return $conta['pagamento']['certificado']['senha']
            ?? $conta['certificado']['senha']
            ?? null;
    }

    public function extratoClientId(string $slug): ?string
    {
        $conta = $this->conta($slug);

        return $conta['extrato']['client_id'] ?? $conta['pagamento']['client_id'] ?? $conta['client_id'];
    }

    public function extratoClientSecret(string $slug): ?string
    {
        $conta = $this->conta($slug);

        return $conta['extrato']['client_secret'] ?? $conta['pagamento']['client_secret'] ?? $conta['client_secret'];
    }

    public function extratoCertificadoPath(string $slug): ?string
    {
        $conta = $this->conta($slug);

        return $conta['extrato']['certificado']['path']
            ?? $conta['pagamento']['certificado']['path']
            ?? $conta['certificado']['path']
            ?? null;
    }

    public function extratoCertificadoSenha(string $slug): ?string
    {
        $conta = $this->conta($slug);

        return $conta['extrato']['certificado']['senha']
            ?? $conta['pagamento']['certificado']['senha']
            ?? $conta['certificado']['senha']
            ?? null;
    }

    public function timeout(): int
    {
        return $this->timeout;
    }

    public function retryTimes(): int
    {
        return $this->retryTimes;
    }

    public function retrySleep(): int
    {
        return $this->retrySleep;
    }

    public function webhookSecret(): ?string
    {
        return $this->webhookSecret;
    }
}

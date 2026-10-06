<?php

namespace CaiqueBispo\Itau\Webhook;

class WebhookPayload
{
    public function __construct(
        private readonly array $data,
    ) {}

    public static function fromJson(string $json): static
    {
        $data = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException('JSON inválido: ' . json_last_error_msg());
        }

        return new static($data);
    }

    public static function fromArray(array $data): static
    {
        return new static($data);
    }

    public function isPix(): bool
    {
        return isset($this->data['pix']) || isset($this->data['pixRecebidos']);
    }

    public function isCobranca(): bool
    {
        return isset($this->data['codigoBarras'])
            || isset($this->data['nossoNumero'])
            || isset($this->data['boleto']);
    }

    public function tipo(): string
    {
        if ($this->isPix()) {
            return 'pix';
        }

        if ($this->isCobranca()) {
            return 'cobranca';
        }

        return 'desconhecido';
    }

    public function toArray(): array
    {
        return $this->data;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->data[$key] ?? $default;
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->data);
    }

    public function pixRecebidos(): array
    {
        return $this->data['pix'] ?? $this->data['pixRecebidos'] ?? [];
    }
}

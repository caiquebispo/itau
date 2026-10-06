<?php

namespace CaiqueBispo\Itau\Webhook;

use CaiqueBispo\Itau\Exceptions\WebhookAuthorizationException;

class WebhookHandler
{
    /** @var array<string, array<callable>> */
    private array $listeners = [];

    public function __construct(
        private readonly ?string $secret = null,
    ) {}

    public function on(string $event, callable $listener): static
    {
        $this->listeners[$event][] = $listener;

        return $this;
    }

    public function handle(string $body, ?string $signature = null): WebhookPayload
    {
        if ($this->secret) {
            $this->verify($body, $signature);
        }

        $payload = WebhookPayload::fromJson($body);

        $this->dispatch($payload->tipo(), $payload);
        $this->dispatch('*', $payload);

        return $payload;
    }

    private function verify(string $body, ?string $signature): void
    {
        if (! $signature) {
            throw new WebhookAuthorizationException('Assinatura ausente no webhook.');
        }

        $expected = hash_hmac('sha256', $body, $this->secret);

        if (! hash_equals($expected, $signature)) {
            throw new WebhookAuthorizationException('Assinatura inválida no webhook.');
        }
    }

    private function dispatch(string $event, WebhookPayload $payload): void
    {
        foreach ($this->listeners[$event] ?? [] as $listener) {
            $listener($payload);
        }
    }
}

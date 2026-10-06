<?php

namespace CaiqueBispo\Itau\Exceptions;

use Exception;

class ItauException extends Exception
{
    public function __construct(
        string $message,
        public readonly int $statusCode = 0,
        public readonly ?string $codigoErro = null,
        public readonly array $erros = [],
        public readonly array $responseBody = [],
    ) {
        parent::__construct($message, $statusCode);
    }

    public static function fromResponse(int $statusCode, array $body): static
    {
        $mensagem = $body['mensagem']
            ?? $body['message']
            ?? $body['titulo']
            ?? $body['detail']
            ?? "Itaú API error (HTTP {$statusCode})";

        $erros = $body['campos'] ?? $body['erros'] ?? $body['errors'] ?? [];

        return new static(
            message: $mensagem,
            statusCode: $statusCode,
            codigoErro: $body['codigo'] ?? $body['code'] ?? null,
            erros: $erros,
            responseBody: $body,
        );
    }

    public function isUnauthorized(): bool
    {
        return $this->statusCode === 401;
    }

    public function isNotFound(): bool
    {
        return $this->statusCode === 404;
    }

    public function isForbidden(): bool
    {
        return $this->statusCode === 403;
    }

    public function isValidationError(): bool
    {
        return $this->statusCode === 400 || $this->statusCode === 422;
    }

    public function isRateLimited(): bool
    {
        return $this->statusCode === 429;
    }
}

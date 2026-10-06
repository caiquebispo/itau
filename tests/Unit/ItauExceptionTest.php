<?php

namespace CaiqueBispo\Itau\Tests\Unit;

use CaiqueBispo\Itau\Exceptions\ItauException;
use PHPUnit\Framework\TestCase;

class ItauExceptionTest extends TestCase
{
    public function test_from_response(): void
    {
        $exception = ItauException::fromResponse(400, [
            'mensagem' => 'Campo inválido',
            'codigo' => 'ITAU001',
            'campos' => [['campo' => 'valor', 'mensagem' => 'Valor inválido']],
        ]);

        $this->assertEquals('Campo inválido', $exception->getMessage());
        $this->assertEquals(400, $exception->statusCode);
        $this->assertEquals('ITAU001', $exception->codigoErro);
        $this->assertCount(1, $exception->erros);
    }

    public function test_from_response_fallback_message(): void
    {
        $exception = ItauException::fromResponse(500, []);
        $this->assertTrue(str_contains($exception->getMessage(), 'HTTP 500'));
    }

    public function test_is_unauthorized(): void
    {
        $exception = new ItauException('Não autorizado', 401);
        $this->assertTrue($exception->isUnauthorized());
    }

    public function test_is_not_found(): void
    {
        $exception = new ItauException('Não encontrado', 404);
        $this->assertTrue($exception->isNotFound());
    }

    public function test_is_forbidden(): void
    {
        $exception = new ItauException('Proibido', 403);
        $this->assertTrue($exception->isForbidden());
    }

    public function test_is_validation_error(): void
    {
        $exception = new ItauException('Erro de validação', 400);
        $this->assertTrue($exception->isValidationError());

        $exception422 = new ItauException('Unprocessable', 422);
        $this->assertTrue($exception422->isValidationError());
    }

    public function test_is_rate_limited(): void
    {
        $exception = new ItauException('Rate limited', 429);
        $this->assertTrue($exception->isRateLimited());
    }

    public function test_response_body(): void
    {
        $body = ['mensagem' => 'Erro', 'detalhes' => 'Info'];
        $exception = ItauException::fromResponse(422, $body);
        $this->assertEquals($body, $exception->responseBody);
    }
}

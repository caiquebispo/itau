<?php

namespace CaiqueBispo\Itau\Tests\Unit;

use CaiqueBispo\Itau\Webhook\WebhookPayload;
use PHPUnit\Framework\TestCase;

class WebhookPayloadTest extends TestCase
{
    public function test_from_json(): void
    {
        $payload = WebhookPayload::fromJson('{"pix":[{"valor":"10.00"}]}');
        $this->assertTrue($payload->isPix());
    }

    public function test_from_array(): void
    {
        $payload = WebhookPayload::fromArray(['pix' => [['valor' => '10.00']]]);
        $this->assertTrue($payload->isPix());
    }

    public function test_invalid_json(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        WebhookPayload::fromJson('invalid json');
    }

    public function test_is_pix(): void
    {
        $payload = WebhookPayload::fromArray(['pix' => [['endToEndId' => 'E123']]]);
        $this->assertTrue($payload->isPix());
        $this->assertFalse($payload->isCobranca());
    }

    public function test_is_cobranca(): void
    {
        $payload = WebhookPayload::fromArray(['nossoNumero' => '12345678']);
        $this->assertTrue($payload->isCobranca());
        $this->assertFalse($payload->isPix());
    }

    public function test_to_array(): void
    {
        $data = ['pix' => [['valor' => '10.00']]];
        $payload = WebhookPayload::fromArray($data);
        $this->assertEquals($data, $payload->toArray());
    }

    public function test_get(): void
    {
        $payload = WebhookPayload::fromArray(['chave' => 'valor']);
        $this->assertEquals('valor', $payload->get('chave'));
        $this->assertEquals('default', $payload->get('inexistente', 'default'));
    }

    public function test_has(): void
    {
        $payload = WebhookPayload::fromArray(['chave' => 'valor']);
        $this->assertTrue($payload->has('chave'));
        $this->assertFalse($payload->has('inexistente'));
    }

    public function test_pix_recebidos(): void
    {
        $pix = [['endToEndId' => 'E123', 'valor' => '10.00']];
        $payload = WebhookPayload::fromArray(['pix' => $pix]);
        $this->assertEquals($pix, $payload->pixRecebidos());
    }

    public function test_pix_recebidos_empty(): void
    {
        $payload = WebhookPayload::fromArray(['boleto' => []]);
        $this->assertEquals([], $payload->pixRecebidos());
    }
}

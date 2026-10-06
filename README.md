# Itaú PHP SDK

PHP SDK para as APIs do Itaú Unibanco — Cobrança (Boletos), PIX, Pagamentos e Extratos com suporte multi-conta e credenciais isoladas por módulo.

## Requisitos

- PHP 8.2+
- Guzzle 7.0+

## Instalação

```bash
composer require caiquebispo/itau
```

## Configuração

### PHP Puro

```php
use CaiqueBispo\Itau\Itau;

$itau = Itau::make([
    'environment' => 'sandbox', // sandbox, producao

    'contas' => [
        'principal' => [
            'client_id'      => 'seu-client-id',
            'client_secret'  => 'seu-client-secret',
            'agencia'        => '0001',
            'conta_corrente' => '12345',
            'chave_pix'      => 'sua-chave-pix@email.com',

            // Certificado para Cobrança + PIX
            'certificado' => [
                'path'  => '/caminho/para/cobranca.crt',
                'senha' => 'senha-do-certificado',
            ],

            // Credenciais ISOLADAS para Pagamento (certificado + token próprios)
            'pagamento' => [
                'client_id'     => 'pagamento-client-id',
                'client_secret' => 'pagamento-client-secret',
                'certificado' => [
                    'path'  => '/caminho/para/pagamento.crt',
                    'senha' => 'senha-pagamento',
                ],
            ],

            // Credenciais ISOLADAS para Extrato (certificado + token próprios)
            'extrato' => [
                'client_id'     => 'extrato-client-id',
                'client_secret' => 'extrato-client-secret',
                'certificado' => [
                    'path'  => '/caminho/para/extrato.crt',
                    'senha' => 'senha-extrato',
                ],
            ],
        ],
    ],

    'default' => 'principal',
    'timeout' => 30,

    'retry' => [
        'times' => 3,
        'sleep' => 500,
    ],

    'webhook' => [
        'secret' => 'seu-webhook-secret',
    ],
]);
```

> **Importante:** As APIs de Pagamento e Extrato do Itaú exigem certificado e token próprios, isolados das APIs de Cobrança/PIX. Se não informar credenciais separadas, o SDK usa as credenciais padrão como fallback.

### Laravel

Publique o arquivo de configuração:

```bash
php artisan vendor:publish --tag=itau-config
```

Configure no `.env`:

```env
# Cobrança + PIX
ITAU_ENVIRONMENT=sandbox
ITAU_CLIENT_ID=seu-client-id
ITAU_CLIENT_SECRET=seu-client-secret
ITAU_AGENCIA=0001
ITAU_CONTA_CORRENTE=12345
ITAU_CHAVE_PIX=sua-chave-pix@email.com
ITAU_CERTIFICADO_PATH=
ITAU_CERTIFICADO_SENHA=

# Pagamento (certificado + token isolados)
ITAU_PAGAMENTO_CLIENT_ID=
ITAU_PAGAMENTO_CLIENT_SECRET=
ITAU_PAGAMENTO_CERTIFICADO_PATH=
ITAU_PAGAMENTO_CERTIFICADO_SENHA=

# Extrato (certificado + token isolados)
ITAU_EXTRATO_CLIENT_ID=
ITAU_EXTRATO_CLIENT_SECRET=
ITAU_EXTRATO_CERTIFICADO_PATH=
ITAU_EXTRATO_CERTIFICADO_SENHA=

ITAU_TIMEOUT=30
ITAU_RETRY_TIMES=3
ITAU_RETRY_SLEEP=500
ITAU_WEBHOOK_SECRET=
```

## Multi-Conta

```php
// PHP Puro
$principal = $itau->cobrancas()->listar();
$filial    = $itau->conta('filial')->cobrancas()->listar();

// Laravel
use CaiqueBispo\Itau\Laravel\Facades\Itau;

$principal = Itau::cobrancas()->listar();
$filial    = Itau::conta('filial')->cobrancas()->listar();
```

## Ambientes

```php
use CaiqueBispo\Itau\Enums\Environment;

$itau->environment(Environment::Producao)->cobrancas()->listar();

// Laravel
Itau::environment(Environment::Producao)->cobrancas()->listar();
```

## Cobrança (Boletos)

```php
// Registrar boleto
$boleto = $itau->cobrancas()->registrar([
    'etapa_processo_boleto' => 'efetivacao',
    'beneficiario' => [
        'id_beneficiario' => '0001123456-8',
    ],
    'dado_boleto' => [
        'codigo_carteira' => 109,
        'valor_total_titulo' => '00000000010000',
        'codigo_especie' => '01',
        'data_emissao' => '2025-01-01',
        'pagador' => [
            'nome_pessoa' => 'João da Silva',
            'tipo_pessoa' => 'F',
            'numero_cadastro_pessoa_fisica' => '12345678901',
            'logradouro' => 'Rua Exemplo, 100',
            'bairro' => 'Centro',
            'cidade' => 'São Paulo',
            'uf' => 'SP',
            'cep' => '01000000',
        ],
        'dados_individuais_boleto' => [
            [
                'numero_nosso_numero' => '00000001',
                'data_vencimento' => '2025-01-31',
                'valor_titulo' => '00000000010000',
            ],
        ],
    ],
]);

// Consultar boleto
$boleto = $itau->cobrancas()->consultar('12345678');

// Listar boletos
$lista = $itau->cobrancas()->listar([
    'id_beneficiario' => '0001123456-8',
    'codigo_carteira' => '109',
    'nosso_numero' => '00000001',
]);

// Alterar boleto
$itau->cobrancas()->alterar('12345678', [
    'data_vencimento' => '2025-02-28',
]);

// Baixar boleto
$itau->cobrancas()->baixar('12345678');

// Consultar QR Code do boleto
$qr = $itau->cobrancas()->consultarQrCode('12345678');
```

### Laravel

```php
use CaiqueBispo\Itau\Laravel\Facades\Itau;

$boleto = Itau::cobrancas()->registrar([...]);
$lista  = Itau::cobrancas()->listar(['id_beneficiario' => '0001123456-8']);
Itau::cobrancas()->baixar('12345678');
```

## PIX — Cobrança Imediata (Cob)

```php
// Criar cobrança (txid gerado pelo PSP)
$cob = $itau->pixCob()->criar([
    'calendario' => ['expiracao' => 3600],
    'devedor' => ['cpf' => '12345678901', 'nome' => 'João da Silva'],
    'valor' => ['original' => '100.00'],
    'chave' => 'sua-chave-pix@email.com',
]);

// Criar com txid definido
$cob = $itau->pixCob()->criarComTxid('txid-personalizado-123', [
    'calendario' => ['expiracao' => 3600],
    'valor' => ['original' => '50.00'],
    'chave' => 'sua-chave-pix@email.com',
]);

// Consultar
$cob = $itau->pixCob()->consultar('txid-personalizado-123');

// Revisar
$itau->pixCob()->revisar('txid-personalizado-123', ['valor' => ['original' => '75.00']]);

// Listar
$lista = $itau->pixCob()->listar([
    'inicio' => '2025-01-01T00:00:00Z',
    'fim'    => '2025-12-31T23:59:59Z',
]);
```

## PIX — Cobrança com Vencimento (CobV)

```php
$cobv = $itau->pixCobV()->criar('txid-cobv-123', [
    'calendario' => ['dataDeVencimento' => '2025-01-31', 'validadeAposVencimento' => 30],
    'devedor' => ['cpf' => '12345678901', 'nome' => 'João da Silva'],
    'valor' => ['original' => '200.00'],
    'chave' => 'sua-chave-pix@email.com',
]);

$cobv = $itau->pixCobV()->consultar('txid-cobv-123');
$itau->pixCobV()->revisar('txid-cobv-123', ['status' => 'REMOVIDA_PELO_USUARIO_RECEBEDOR']);
$lista = $itau->pixCobV()->listar(['inicio' => '2025-01-01T00:00:00Z', 'fim' => '2025-12-31T23:59:59Z']);
```

## PIX — Lote CobV

```php
$lote = $itau->pixLoteCobV()->criar('lote-001', ['cobsv' => [...]]);
$lote = $itau->pixLoteCobV()->consultar('lote-001');
$lista = $itau->pixLoteCobV()->listar();
```

## PIX — Recebidos e Devoluções

```php
$pix = $itau->pix()->consultar('E00000000202401011234abc');
$lista = $itau->pix()->listar(['inicio' => '2025-01-01T00:00:00Z', 'fim' => '2025-01-31T23:59:59Z']);

// Devolução
$dev = $itau->pix()->devolver('E00000000202401011234abc', 'devolucao-001', ['valor' => '10.00']);
$dev = $itau->pix()->consultarDevolucao('E00000000202401011234abc', 'devolucao-001');
```

## PIX — Webhook

```php
$itau->pixWebhook()->criar('sua-chave-pix@email.com', ['webhookUrl' => 'https://seusite.com/webhook/pix']);
$webhook = $itau->pixWebhook()->consultar('sua-chave-pix@email.com');
$lista = $itau->pixWebhook()->listar();
$itau->pixWebhook()->excluir('sua-chave-pix@email.com');
```

## PIX — Location (QR Code)

```php
$loc = $itau->pixLocation()->criar(['tipoCob' => 'cob']);
$loc = $itau->pixLocation()->consultar(1);
$lista = $itau->pixLocation()->listar();
$qr = $itau->pixLocation()->qrcode(1);
$itau->pixLocation()->desvincular(1);
```

## Pagamentos (Token + Certificado Isolados)

```php
// Transferência
$transf = $itau->pagamentos()->transferir([...]);
$transf = $itau->pagamentos()->consultarTransferencia('12345');
$lista  = $itau->pagamentos()->listarTransferencias();

// Pagar boleto
$itau->pagamentos()->pagarBoleto([...]);
$itau->pagamentos()->consultarPagamentoBoleto('12345');

// Guias e tributos
$itau->pagamentos()->pagarDarf([...]);
$itau->pagamentos()->pagarGps([...]);
$itau->pagamentos()->pagarGare([...]);
$itau->pagamentos()->pagarTributo([...]);

// Lote e cancelamento
$lote = $itau->pagamentos()->consultarLote('lote-001');
$itau->pagamentos()->cancelarPagamento('12345');
```

### Laravel

```php
use CaiqueBispo\Itau\Laravel\Facades\Itau;

Itau::pagamentos()->transferir([...]);
Itau::pagamentos()->pagarBoleto([...]);
```

## Extratos (Token + Certificado Isolados)

```php
$saldo = $itau->extratos()->saldo();
$extrato = $itau->extratos()->extrato(['dataInicio' => '2025-01-01', 'dataFim' => '2025-01-31']);
$detalhado = $itau->extratos()->extratoDetalhado(['dataInicio' => '2025-01-01']);
```

### Laravel

```php
use CaiqueBispo\Itau\Laravel\Facades\Itau;

$saldo   = Itau::extratos()->saldo();
$extrato = Itau::extratos()->extrato(['dataInicio' => '2025-01-01', 'dataFim' => '2025-01-31']);
```

## Webhook Handler

### PHP Puro

```php
use CaiqueBispo\Itau\Webhook\WebhookHandler;
use CaiqueBispo\Itau\Webhook\WebhookPayload;

$handler = new WebhookHandler('seu-webhook-secret');

$handler->on('pix', function (WebhookPayload $payload) {
    foreach ($payload->pixRecebidos() as $pix) {
        echo "PIX recebido: {$pix['endToEndId']} - R$ {$pix['valor']}\n";
    }
});

$handler->on('cobranca', function (WebhookPayload $payload) {
    $dados = $payload->toArray();
    echo "Boleto atualizado: {$dados['nossoNumero']}\n";
});

$body      = file_get_contents('php://input');
$signature = $_SERVER['HTTP_X_WEBHOOK_SIGNATURE'] ?? null;

try {
    $payload = $handler->handle($body, $signature);
} catch (\CaiqueBispo\Itau\Exceptions\WebhookAuthorizationException $e) {
    http_response_code(401);
    echo $e->getMessage();
}
```

### Laravel

No `AppServiceProvider`:

```php
use CaiqueBispo\Itau\Webhook\WebhookHandler;
use CaiqueBispo\Itau\Webhook\WebhookPayload;

public function boot(): void
{
    $handler = app(WebhookHandler::class);

    $handler->on('pix', function (WebhookPayload $payload) {
        foreach ($payload->pixRecebidos() as $pix) {
            \Log::info("PIX recebido: {$pix['endToEndId']}", $pix);
        }
    });

    $handler->on('cobranca', function (WebhookPayload $payload) {
        \Log::info('Boleto atualizado', $payload->toArray());
    });
}
```

Na rota:

```php
Route::post('/webhook/itau', function (Request $request) {
    $handler = app(WebhookHandler::class);

    $payload = $handler->handle(
        $request->getContent(),
        $request->header('X-Webhook-Signature'),
    );

    return response()->json(['ok' => true]);
});
```

## Tratamento de Erros

```php
use CaiqueBispo\Itau\Exceptions\ItauException;
use CaiqueBispo\Itau\Exceptions\AuthenticationException;
use CaiqueBispo\Itau\Exceptions\ContaNaoConfiguradaException;

try {
    $boleto = $itau->cobrancas()->registrar([...]);
} catch (AuthenticationException $e) {
    echo "Erro OAuth: {$e->getMessage()}";
} catch (ContaNaoConfiguradaException $e) {
    echo $e->getMessage();
} catch (ItauException $e) {
    echo "Erro: {$e->getMessage()}\n";
    echo "HTTP Status: {$e->statusCode}\n";
    echo "Código: {$e->codigoErro}\n";

    if ($e->isUnauthorized()) { /* 401 */ }
    if ($e->isForbidden()) { /* 403 */ }
    if ($e->isNotFound()) { /* 404 */ }
    if ($e->isValidationError()) { /* 400 ou 422 */ }
    if ($e->isRateLimited()) { /* 429 */ }

    print_r($e->responseBody);
}
```

## Certificado mTLS

O Itaú exige certificado digital (.crt + .key) em produção. Sandbox não requer mTLS.

- Token de autenticação expira em **300 segundos (5 minutos)** — o SDK gerencia renovação automática
- Certificado válido por 365 dias

```php
'certificado' => [
    'path'  => '/caminho/para/certificado.crt',
    'senha' => 'senha-da-chave-privada',
],
```

## Particularidades do Itaú

- **Token curto:** 5 minutos de validade (renovação automática pelo SDK)
- **Headers automáticos:** `x-itau-apikey`, `x-itau-flowID` e `x-itau-correlationID` são injetados em toda requisição
- **Credenciais isoladas:** Pagamento e Extrato exigem certificado e token próprios, separados de Cobrança/PIX
- **Sandbox simplificado:** não valida dados reais, não usa OAuth/mTLS

## Licença

MIT

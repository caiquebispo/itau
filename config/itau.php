<?php

return [
    'environment' => env('ITAU_ENVIRONMENT', 'sandbox'),

    'contas' => [
        'principal' => [
            'client_id' => env('ITAU_CLIENT_ID'),
            'client_secret' => env('ITAU_CLIENT_SECRET'),
            'agencia' => env('ITAU_AGENCIA'),
            'conta_corrente' => env('ITAU_CONTA_CORRENTE'),
            'chave_pix' => env('ITAU_CHAVE_PIX'),

            'certificado' => [
                'path' => env('ITAU_CERTIFICADO_PATH'),
                'senha' => env('ITAU_CERTIFICADO_SENHA'),
            ],

            // Credenciais isoladas para Pagamento (certificado + token próprios)
            'pagamento' => [
                'client_id' => env('ITAU_PAGAMENTO_CLIENT_ID'),
                'client_secret' => env('ITAU_PAGAMENTO_CLIENT_SECRET'),
                'certificado' => [
                    'path' => env('ITAU_PAGAMENTO_CERTIFICADO_PATH'),
                    'senha' => env('ITAU_PAGAMENTO_CERTIFICADO_SENHA'),
                ],
            ],

            // Credenciais isoladas para Extrato (certificado + token próprios)
            'extrato' => [
                'client_id' => env('ITAU_EXTRATO_CLIENT_ID'),
                'client_secret' => env('ITAU_EXTRATO_CLIENT_SECRET'),
                'certificado' => [
                    'path' => env('ITAU_EXTRATO_CERTIFICADO_PATH'),
                    'senha' => env('ITAU_EXTRATO_CERTIFICADO_SENHA'),
                ],
            ],
        ],
    ],

    'default' => env('ITAU_DEFAULT_CONTA', 'principal'),

    'timeout' => (int) env('ITAU_TIMEOUT', 30),

    'retry' => [
        'times' => (int) env('ITAU_RETRY_TIMES', 3),
        'sleep' => (int) env('ITAU_RETRY_SLEEP', 500),
    ],

    'webhook' => [
        'secret' => env('ITAU_WEBHOOK_SECRET'),
    ],
];

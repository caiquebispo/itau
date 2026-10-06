<?php

namespace CaiqueBispo\Itau\Http;

use CaiqueBispo\Itau\Auth\OAuthTokenManager;
use CaiqueBispo\Itau\Contracts\ItauClientInterface;
use CaiqueBispo\Itau\Exceptions\ItauException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;

class ItauClient implements ItauClientInterface
{
    private Client $http;

    private OAuthTokenManager $tokenManager;

    public function __construct(
        private readonly AccountContext $accountContext,
        private readonly string $baseUrl,
        ?OAuthTokenManager $tokenManager = null,
    ) {
        $this->tokenManager = $tokenManager ?? new OAuthTokenManager();

        $options = [
            'base_uri' => rtrim($baseUrl, '/') . '/',
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
            'timeout' => $this->accountContext->config->timeout(),
        ];

        if ($this->accountContext->certificadoPath) {
            $senha = $this->accountContext->certificadoSenha;
            $options['cert'] = $senha
                ? [$this->accountContext->certificadoPath, $senha]
                : $this->accountContext->certificadoPath;
        }

        $this->http = new Client($options);
    }

    public function get(string $path, array $query = []): array
    {
        return $this->request('GET', $path, query: $query);
    }

    public function post(string $path, array $data = [], array $query = []): array
    {
        return $this->request('POST', $path, $data, $query);
    }

    public function put(string $path, array $data = [], array $query = []): array
    {
        return $this->request('PUT', $path, $data, $query);
    }

    public function patch(string $path, array $data = [], array $query = []): array
    {
        return $this->request('PATCH', $path, $data, $query);
    }

    public function delete(string $path, array $data = [], array $query = []): array
    {
        return $this->request('DELETE', $path, $data, $query);
    }

    public function context(): AccountContext
    {
        return $this->accountContext;
    }

    private function request(string $method, string $path, array $data = [], array $query = []): array
    {
        $token = $this->tokenManager->getToken(
            $this->accountContext->clientId,
            $this->accountContext->clientSecret,
            $this->accountContext->environment,
            $this->accountContext->certificadoPath,
            $this->accountContext->certificadoSenha,
        );

        $options = [
            'headers' => [
                'Authorization' => "Bearer {$token}",
                'x-itau-apikey' => $this->accountContext->clientId,
                'x-itau-flowID' => $this->accountContext->clientId . 'd7',
                'x-itau-correlationID' => $this->generateCorrelationId(),
            ],
            'query' => $query ?: null,
        ];

        if (! empty($data)) {
            $options['json'] = $data;
        }

        $attempts = $this->accountContext->config->retryTimes();
        $sleep = $this->accountContext->config->retrySleep();
        $lastException = null;

        for ($i = 0; $i < $attempts; $i++) {
            try {
                $response = $this->http->request($method, $path, array_filter($options));
                $contents = $response->getBody()->getContents();

                return $contents ? (json_decode($contents, true) ?? []) : [];
            } catch (ConnectException $e) {
                $lastException = $e;
                if ($i < $attempts - 1) {
                    usleep($sleep * 1000);
                }
            } catch (RequestException $e) {
                if (! $e->hasResponse()) {
                    throw new ItauException($e->getMessage(), 0);
                }

                $status = $e->getResponse()->getStatusCode();
                $body = json_decode($e->getResponse()->getBody()->getContents(), true) ?? [];

                throw ItauException::fromResponse($status, $body);
            }
        }

        throw new ItauException(
            "Falha de conexão após {$attempts} tentativas: " . $lastException?->getMessage(),
            0,
        );
    }

    private function generateCorrelationId(): string
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        );
    }
}

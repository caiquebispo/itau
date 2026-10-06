<?php

namespace CaiqueBispo\Itau\Auth;

use CaiqueBispo\Itau\Contracts\TokenStorageInterface;
use CaiqueBispo\Itau\Enums\Environment;
use CaiqueBispo\Itau\Exceptions\AuthenticationException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class OAuthTokenManager
{
    private Client $http;

    private TokenStorageInterface $storage;

    public function __construct(?TokenStorageInterface $storage = null)
    {
        $this->storage = $storage ?? new MemoryTokenStorage();
        $this->http = new Client(['timeout' => 15]);
    }

    public function getToken(
        string $clientId,
        string $clientSecret,
        Environment $environment,
        ?string $certPath = null,
        ?string $certSenha = null,
        string $scope = '',
    ): string {
        $cacheKey = md5("{$clientId}:{$environment->value}:{$scope}:{$certPath}");

        $cached = $this->storage->get($cacheKey);
        if ($cached) {
            return $cached['access_token'];
        }

        $token = $this->requestToken($clientId, $clientSecret, $environment, $certPath, $certSenha, $scope);

        $ttl = ($token['expires_in'] ?? 300) - 30;
        $this->storage->set($cacheKey, $token, max($ttl, 10));

        return $token['access_token'];
    }

    private function requestToken(
        string $clientId,
        string $clientSecret,
        Environment $environment,
        ?string $certPath,
        ?string $certSenha,
        string $scope,
    ): array {
        $url = $environment->oauthUrl();

        $params = [
            'grant_type' => 'client_credentials',
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
        ];

        if ($scope) {
            $params['scope'] = $scope;
        }

        $options = [
            'form_params' => $params,
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
        ];

        if ($certPath) {
            $options['cert'] = $certSenha ? [$certPath, $certSenha] : $certPath;
        }

        try {
            $response = $this->http->post($url, $options);
            $body = json_decode($response->getBody()->getContents(), true);

            if (empty($body['access_token'])) {
                throw new AuthenticationException('Resposta OAuth sem access_token', 401);
            }

            return $body;
        } catch (RequestException $e) {
            $status = $e->hasResponse() ? $e->getResponse()->getStatusCode() : 0;
            $body = $e->hasResponse()
                ? (json_decode($e->getResponse()->getBody()->getContents(), true) ?? [])
                : [];

            throw AuthenticationException::fromResponse($status, array_merge($body, [
                'mensagem' => $body['error_description'] ?? $body['mensagem'] ?? 'Falha na autenticação OAuth Itaú',
            ]));
        }
    }
}

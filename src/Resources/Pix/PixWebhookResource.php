<?php

namespace CaiqueBispo\Itau\Resources\Pix;

use CaiqueBispo\Itau\Resources\Resource;

class PixWebhookResource extends Resource
{
    public function criar(string $chave, array $dados): array
    {
        return $this->client->put("webhook/{$chave}", $dados);
    }

    public function consultar(string $chave): array
    {
        return $this->client->get("webhook/{$chave}");
    }

    public function excluir(string $chave): array
    {
        return $this->client->delete("webhook/{$chave}");
    }

    public function listar(array $query = []): array
    {
        return $this->client->get('webhook', $query);
    }
}

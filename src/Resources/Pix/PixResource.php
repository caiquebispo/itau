<?php

namespace CaiqueBispo\Itau\Resources\Pix;

use CaiqueBispo\Itau\Resources\Resource;

class PixResource extends Resource
{
    public function consultar(string $e2eId): array
    {
        return $this->client->get("pix/{$e2eId}");
    }

    public function listar(array $query = []): array
    {
        return $this->client->get('pix', $query);
    }

    public function devolver(string $e2eId, string $id, array $dados): array
    {
        return $this->client->put("pix/{$e2eId}/devolucao/{$id}", $dados);
    }

    public function consultarDevolucao(string $e2eId, string $id): array
    {
        return $this->client->get("pix/{$e2eId}/devolucao/{$id}");
    }
}

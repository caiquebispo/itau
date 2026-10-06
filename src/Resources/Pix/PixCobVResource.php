<?php

namespace CaiqueBispo\Itau\Resources\Pix;

use CaiqueBispo\Itau\Resources\Resource;

class PixCobVResource extends Resource
{
    public function criar(string $txid, array $dados): array
    {
        return $this->client->put("cobv/{$txid}", $dados);
    }

    public function consultar(string $txid): array
    {
        return $this->client->get("cobv/{$txid}");
    }

    public function revisar(string $txid, array $dados): array
    {
        return $this->client->patch("cobv/{$txid}", $dados);
    }

    public function listar(array $query = []): array
    {
        return $this->client->get('cobv', $query);
    }
}

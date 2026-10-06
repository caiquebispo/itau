<?php

namespace CaiqueBispo\Itau\Resources\Pix;

use CaiqueBispo\Itau\Resources\Resource;

class PixLocationResource extends Resource
{
    public function criar(array $dados): array
    {
        return $this->client->post('loc', $dados);
    }

    public function consultar(int $id): array
    {
        return $this->client->get("loc/{$id}");
    }

    public function listar(array $query = []): array
    {
        return $this->client->get('loc', $query);
    }

    public function desvincular(int $id): array
    {
        return $this->client->delete("loc/{$id}/txid");
    }

    public function qrcode(int $id): array
    {
        return $this->client->get("loc/{$id}/qrcode");
    }
}

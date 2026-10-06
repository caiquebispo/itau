<?php

namespace CaiqueBispo\Itau\Resources\Pix;

use CaiqueBispo\Itau\Resources\Resource;

class PixLoteCobVResource extends Resource
{
    public function criar(string $id, array $dados): array
    {
        return $this->client->put("lotecobv/{$id}", $dados);
    }

    public function consultar(string $id): array
    {
        return $this->client->get("lotecobv/{$id}");
    }

    public function revisar(string $id, array $dados): array
    {
        return $this->client->patch("lotecobv/{$id}", $dados);
    }

    public function listar(array $query = []): array
    {
        return $this->client->get('lotecobv', $query);
    }
}

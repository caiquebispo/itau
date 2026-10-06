<?php

namespace CaiqueBispo\Itau\Resources\Pix;

use CaiqueBispo\Itau\Resources\Resource;

class PixCobResource extends Resource
{
    public function criar(array $dados): array
    {
        return $this->client->post('cob', $dados);
    }

    public function criarComTxid(string $txid, array $dados): array
    {
        return $this->client->put("cob/{$txid}", $dados);
    }

    public function consultar(string $txid): array
    {
        return $this->client->get("cob/{$txid}");
    }

    public function revisar(string $txid, array $dados): array
    {
        return $this->client->patch("cob/{$txid}", $dados);
    }

    public function listar(array $query = []): array
    {
        return $this->client->get('cob', $query);
    }
}

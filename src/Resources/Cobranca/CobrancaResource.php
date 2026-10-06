<?php

namespace CaiqueBispo\Itau\Resources\Cobranca;

use CaiqueBispo\Itau\Resources\Resource;

class CobrancaResource extends Resource
{
    public function registrar(array $dados): array
    {
        return $this->client->post('boletos', $dados);
    }

    public function consultar(string $id): array
    {
        return $this->client->get("boletos/{$id}");
    }

    public function listar(array $query = []): array
    {
        return $this->client->get('boletos', $query);
    }

    public function alterar(string $id, array $dados): array
    {
        return $this->client->patch("boletos/{$id}", $dados);
    }

    public function baixar(string $id, array $dados = []): array
    {
        return $this->client->patch("boletos/{$id}/baixa", $dados);
    }

    public function consultarQrCode(string $id): array
    {
        return $this->client->get("boletos/{$id}/qrcode");
    }
}

<?php

namespace CaiqueBispo\Itau\Resources\Extrato;

use CaiqueBispo\Itau\Resources\Resource;

class ExtratoResource extends Resource
{
    public function saldo(array $query = []): array
    {
        return $this->client->get('saldo', $query);
    }

    public function extrato(array $query = []): array
    {
        return $this->client->get('extrato', $query);
    }

    public function extratoDetalhado(array $query = []): array
    {
        return $this->client->get('extrato-detalhado', $query);
    }
}

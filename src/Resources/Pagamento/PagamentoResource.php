<?php

namespace CaiqueBispo\Itau\Resources\Pagamento;

use CaiqueBispo\Itau\Resources\Resource;

class PagamentoResource extends Resource
{
    public function transferir(array $dados): array
    {
        return $this->client->post('transferencias', $dados);
    }

    public function consultarTransferencia(string $id): array
    {
        return $this->client->get("transferencias/{$id}");
    }

    public function listarTransferencias(array $query = []): array
    {
        return $this->client->get('transferencias', $query);
    }

    public function pagarBoleto(array $dados): array
    {
        return $this->client->post('boletos', $dados);
    }

    public function consultarPagamentoBoleto(string $id): array
    {
        return $this->client->get("boletos/{$id}");
    }

    public function listarPagamentosBoletos(array $query = []): array
    {
        return $this->client->get('boletos', $query);
    }

    public function pagarDarf(array $dados): array
    {
        return $this->client->post('darf', $dados);
    }

    public function pagarGps(array $dados): array
    {
        return $this->client->post('gps', $dados);
    }

    public function pagarGare(array $dados): array
    {
        return $this->client->post('gare', $dados);
    }

    public function pagarTributo(array $dados): array
    {
        return $this->client->post('tributos', $dados);
    }

    public function consultarLote(string $id): array
    {
        return $this->client->get("lotes/{$id}");
    }

    public function cancelarPagamento(string $id, array $dados = []): array
    {
        return $this->client->post("pagamentos/{$id}/cancelar", $dados);
    }
}

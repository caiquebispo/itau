<?php

namespace CaiqueBispo\Itau\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \CaiqueBispo\Itau\Itau conta(string $slug)
 * @method static \CaiqueBispo\Itau\Itau environment(\CaiqueBispo\Itau\Enums\Environment $environment)
 * @method static \CaiqueBispo\Itau\Resources\Cobranca\CobrancaResource cobrancas()
 * @method static \CaiqueBispo\Itau\Resources\Pix\PixCobResource pixCob()
 * @method static \CaiqueBispo\Itau\Resources\Pix\PixCobVResource pixCobV()
 * @method static \CaiqueBispo\Itau\Resources\Pix\PixLoteCobVResource pixLoteCobV()
 * @method static \CaiqueBispo\Itau\Resources\Pix\PixResource pix()
 * @method static \CaiqueBispo\Itau\Resources\Pix\PixWebhookResource pixWebhook()
 * @method static \CaiqueBispo\Itau\Resources\Pix\PixLocationResource pixLocation()
 * @method static \CaiqueBispo\Itau\Resources\Pagamento\PagamentoResource pagamentos()
 * @method static \CaiqueBispo\Itau\Resources\Extrato\ExtratoResource extratos()
 *
 * @see \CaiqueBispo\Itau\Itau
 */
class Itau extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'itau';
    }
}

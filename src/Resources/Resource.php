<?php

namespace CaiqueBispo\Itau\Resources;

use CaiqueBispo\Itau\Contracts\ItauClientInterface;

abstract class Resource
{
    public function __construct(
        protected readonly ItauClientInterface $client,
    ) {}

    protected function contaSlug(): string
    {
        return $this->client->context()->slug;
    }
}

<?php

namespace CaiqueBispo\Itau\Exceptions;

use InvalidArgumentException;

class ContaNaoConfiguradaException extends InvalidArgumentException
{
    public static function make(string $slug): static
    {
        return new static(
            "Conta [{$slug}] não está configurada em config/itau.php → contas."
        );
    }
}

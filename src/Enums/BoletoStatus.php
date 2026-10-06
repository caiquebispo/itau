<?php

namespace CaiqueBispo\Itau\Enums;

enum BoletoStatus: string
{
    case Emitido = 'EMITIDO';
    case Registrado = 'REGISTRADO';
    case Liquidado = 'LIQUIDADO';
    case Baixado = 'BAIXADO';
    case EmCartorio = 'EM_CARTORIO';
    case Protestado = 'PROTESTADO';
}

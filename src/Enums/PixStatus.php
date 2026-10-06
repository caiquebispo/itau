<?php

namespace CaiqueBispo\Itau\Enums;

enum PixStatus: string
{
    case Ativa = 'ATIVA';
    case Concluida = 'CONCLUIDA';
    case RemovidaPeloPsp = 'REMOVIDA_PELO_PSP';
    case RemovidaPeloUsuarioRecebedor = 'REMOVIDA_PELO_USUARIO_RECEBEDOR';
}

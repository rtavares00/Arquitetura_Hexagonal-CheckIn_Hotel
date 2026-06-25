<?php

namespace Tavares\Hotel\Domain\Enum;

enum Status : string
{
    case Ocupado = 'O';
    case Manutencao = 'M';
    case Disponivel = 'D';
}
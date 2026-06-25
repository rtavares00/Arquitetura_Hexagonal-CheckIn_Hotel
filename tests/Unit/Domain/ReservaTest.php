<?php

use Tavares\Hotel\Domain\Reserva;
use Tavares\Hotel\Domain\Quarto;
use Tavares\Hotel\Domain\Enum\Status;
use Tavares\Hotel\Domain\VO\Hospede;
use Tavares\Hotel\Domain\Exception\DatasInvalidasException;

function novaReserva(string $entrada, string $saida): Reserva
{
    return new Reserva(
        new Hospede('529.982.247-25'),
        new Quarto(1, Status::Disponivel),
        new DateTime($entrada),
        new DateTime($saida),
    );
}

/*
|--------------------------------------------------------------------------
| __construct
|--------------------------------------------------------------------------
*/

test('cria uma reserva com hóspede, quarto, entrada e saída', function () {
    $reserva = novaReserva('2026-06-25', '2026-06-28');

    expect($reserva)->toBeInstanceOf(Reserva::class);
});

/*
|--------------------------------------------------------------------------
| validação de datas
|--------------------------------------------------------------------------
*/

test('cria a reserva quando a saída é posterior à entrada', function () {
    expect(fn () => novaReserva('2026-06-25', '2026-06-28'))
        ->not->toThrow(DatasInvalidasException::class);
});

test('cria a reserva quando a saída é igual à entrada', function () {
    expect(fn () => novaReserva('2026-06-25', '2026-06-25'))
        ->not->toThrow(DatasInvalidasException::class);
});

test('lança DatasInvalidasException quando a saída é anterior à entrada', function () {
    expect(fn () => novaReserva('2026-06-28', '2026-06-25'))
        ->toThrow(DatasInvalidasException::class);
});

<?php

use Tavares\Hotel\Domain\Reserva;
use Tavares\Hotel\Domain\Quarto;
use Tavares\Hotel\Domain\Enum\Status;
use Tavares\Hotel\Domain\VO\Hospede;
use Tavares\Hotel\Domain\Exception\DatasInvalidasException;
use Tavares\Hotel\Domain\Exception\ReservaJaUtilizadaException;
use Tavares\Hotel\Domain\Exception\CheckinAntesDaEntradaException;
use Tavares\Hotel\Domain\Exception\QuartoIndisponivelException;

function novaReserva(
    string $entrada = '2026-06-25',
    string $saida = '2026-06-28',
    bool $utilizada = false,
    Status $quartoStatus = Status::Disponivel
): Reserva {
    return new Reserva(
        1,
        new Hospede('529.982.247-25'),
        new Quarto(101, $quartoStatus),
        new DateTime($entrada),
        new DateTime($saida),
        $utilizada,
    );
}

/*
|--------------------------------------------------------------------------
| __construct
|--------------------------------------------------------------------------
*/

test('cria uma reserva com hóspede, quarto e período', function () {
    expect(novaReserva())->toBeInstanceOf(Reserva::class);
});

test('cria a reserva quando a saída é posterior ou igual à entrada', function (string $saida) {
    expect(fn () => novaReserva(entrada: '2026-06-25', saida: $saida))
        ->not->toThrow(DatasInvalidasException::class);
})->with([
    'posterior' => '2026-06-28',
    'igual'     => '2026-06-25',
]);

test('lança DatasInvalidasException quando a saída é anterior à entrada', function () {
    expect(fn () => novaReserva(entrada: '2026-06-28', saida: '2026-06-25'))
        ->toThrow(DatasInvalidasException::class);
});

test('permite construir uma reserva já utilizada (estado válido, ex.: vinda do banco)', function () {
    $reserva = novaReserva(utilizada: true);

    expect($reserva->isUtilizada())->toBeTrue();
});

/*
|--------------------------------------------------------------------------
| getters
|--------------------------------------------------------------------------
*/

test('expõe o hóspede, o quarto e o período da reserva', function () {
    $hospede = new Hospede('529.982.247-25');
    $quarto = new Quarto(101, Status::Disponivel);
    $reserva = new Reserva(1, $hospede, $quarto, new DateTime('2026-06-25'), new DateTime('2026-06-28'), false);

    expect($reserva->hospede())->toBe($hospede)
        ->and($reserva->quarto())->toBe($quarto)
        ->and($reserva->entrada()->format('Y-m-d'))->toBe('2026-06-25')
        ->and($reserva->saida()->format('Y-m-d'))->toBe('2026-06-28');
});

/*
|--------------------------------------------------------------------------
| acionar (check-in)
|--------------------------------------------------------------------------
*/

test('acionar faz o check-in: ocupa o quarto, marca a reserva como utilizada e confirma o número do quarto', function () {
    $quarto = new Quarto(101, Status::Disponivel);
    $reserva = new Reserva(
        1,
        new Hospede('529.982.247-25'),
        $quarto,
        new DateTime('2026-06-25'),
        new DateTime('2026-06-28'),
        false,
    );

    $confirmacao = $reserva->acionar(new DateTime('2026-06-25'));

    expect($quarto->isOcupado())->toBeTrue()
        ->and($reserva->isUtilizada())->toBeTrue()
        ->and($confirmacao)->toContain('101');
});

test('acionar lança CheckinAntesDaEntradaException quando feito antes da data de entrada', function () {
    $reserva = novaReserva(entrada: '2026-06-25', saida: '2026-06-28');

    expect(fn () => $reserva->acionar(new DateTime('2026-06-24')))
        ->toThrow(CheckinAntesDaEntradaException::class);
});

test('acionar lança ReservaJaUtilizadaException quando a reserva já foi utilizada', function () {
    $reserva = novaReserva(utilizada: true);

    expect(fn () => $reserva->acionar(new DateTime('2026-06-25')))
        ->toThrow(ReservaJaUtilizadaException::class);
});

test('acionar lança QuartoIndisponivelException quando o quarto não está disponível', function (Status $status) {
    $reserva = novaReserva(quartoStatus: $status);

    expect(fn () => $reserva->acionar(new DateTime('2026-06-25')))
        ->toThrow(QuartoIndisponivelException::class);
})->with([
    'ocupado'    => Status::Ocupado,
    'manutenção' => Status::Manutencao,
]);

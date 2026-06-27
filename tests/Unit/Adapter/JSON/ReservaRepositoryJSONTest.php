<?php

use Tavares\Hotel\Adapter\JSON\ReservaRepositoryJSON;
use Tavares\Hotel\Adapter\JSON\QuartoRepositoryJSON;
use Tavares\Hotel\Adapter\Exception\ReservaNaoEncontradaException;
use Tavares\Hotel\Domain\Reserva;
use Tavares\Hotel\Domain\Quarto;
use Tavares\Hotel\Domain\Enum\Status;
use Tavares\Hotel\Domain\VO\Hospede;

const CAMINHO_RESERVAS = __DIR__ . '/../../../../data/reservas.json';

// Monta o repositório de reservas com o repositório de quartos (porta) injetado.
function repositorioDeReservas(): ReservaRepositoryJSON
{
    return new ReservaRepositoryJSON(new QuartoRepositoryJSON());
}

/*
| Preserva o arquivo de dados real: salvar() grava no reservas.json.
*/
beforeEach(function () {
    $this->backup = file_get_contents(CAMINHO_RESERVAS);
});

afterEach(function () {
    file_put_contents(CAMINHO_RESERVAS, $this->backup);
});

/*
|--------------------------------------------------------------------------
| buscar
|--------------------------------------------------------------------------
*/

test('buscar monta a reserva resolvendo hóspede e quarto a partir do JSON', function () {
    $reserva = repositorioDeReservas()->buscar(1);

    expect($reserva)->toBeInstanceOf(Reserva::class)
        ->and($reserva->id())->toBe(1)
        ->and($reserva->hospede()->cpf())->toBe('81470369222')
        ->and($reserva->quarto()->id())->toBe(101)
        ->and($reserva->entrada()->format('Y-m-d'))->toBe('2026-06-27')
        ->and($reserva->saida()->format('Y-m-d'))->toBe('2026-06-30')
        ->and($reserva->isUtilizada())->toBeFalse();
});

test('buscar lança ReservaNaoEncontradaException quando o id não existe', function () {
    expect(fn () => repositorioDeReservas()->buscar(999))
        ->toThrow(ReservaNaoEncontradaException::class);
});

/*
|--------------------------------------------------------------------------
| salvar
|--------------------------------------------------------------------------
*/

test('salvar grava as datas como string no formato do arquivo', function () {
    $reserva = repositorioDeReservas()->buscar(1);

    repositorioDeReservas()->salvar($reserva);

    $bruto = json_decode(file_get_contents(CAMINHO_RESERVAS), true)[0];
    expect($bruto['entrada'])->toBe('2026-06-27')
        ->and($bruto['saida'])->toBe('2026-06-30');
});

test('salvar não altera a quantidade de reservas do arquivo', function () {
    $reserva = repositorioDeReservas()->buscar(1);

    repositorioDeReservas()->salvar($reserva);

    $dados = json_decode(file_get_contents(CAMINHO_RESERVAS), true);
    expect($dados)->toHaveCount(12);
});

test('salvar lança ReservaNaoEncontradaException quando a reserva não existe', function () {
    $inexistente = new Reserva(
        999,
        new Hospede('529.982.247-25'),
        new Quarto(101, Status::Disponivel),
        new DateTime('2026-06-25'),
        new DateTime('2026-06-28'),
        false,
    );

    expect(fn () => repositorioDeReservas()->salvar($inexistente))
        ->toThrow(ReservaNaoEncontradaException::class);
});

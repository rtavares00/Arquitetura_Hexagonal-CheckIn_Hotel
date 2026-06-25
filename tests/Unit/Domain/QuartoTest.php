<?php

use Tavares\Hotel\Domain\Quarto;
use Tavares\Hotel\Domain\Enum\Status;

/*
|--------------------------------------------------------------------------
| __construct
|--------------------------------------------------------------------------
*/

test('cria um quarto com id e status', function () {
    $quarto = new Quarto(1, Status::Disponivel);

    expect($quarto)->toBeInstanceOf(Quarto::class);
});

/*
|--------------------------------------------------------------------------
| isOcupado
|--------------------------------------------------------------------------
*/

test('isOcupado retorna true quando o status é Ocupado', function () {
    $quarto = new Quarto(1, Status::Ocupado);

    expect($quarto->isOcupado())->toBeTrue();
});

test('isOcupado retorna false quando o status não é Ocupado', function (Status $status) {
    $quarto = new Quarto(1, $status);

    expect($quarto->isOcupado())->toBeFalse();
})->with([
    'manutenção' => Status::Manutencao,
    'disponível' => Status::Disponivel,
]);

/*
|--------------------------------------------------------------------------
| isEmManutencao
|--------------------------------------------------------------------------
*/

test('isEmManutencao retorna true quando o status é Manutencao', function () {
    $quarto = new Quarto(1, Status::Manutencao);

    expect($quarto->isEmManutencao())->toBeTrue();
});

test('isEmManutencao retorna false quando o status não é Manutencao', function (Status $status) {
    $quarto = new Quarto(1, $status);

    expect($quarto->isEmManutencao())->toBeFalse();
})->with([
    'ocupado' => Status::Ocupado,
    'disponível' => Status::Disponivel,
]);

/*
|--------------------------------------------------------------------------
| reservar
|--------------------------------------------------------------------------
*/

test('reservar marca o quarto como ocupado', function () {
    $quarto = new Quarto(1, Status::Disponivel);

    $quarto->reservar();

    expect($quarto->isOcupado())->toBeTrue();
});

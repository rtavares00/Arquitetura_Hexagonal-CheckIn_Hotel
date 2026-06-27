<?php

use Tavares\Hotel\Domain\Quarto;
use Tavares\Hotel\Domain\Enum\Status;
use Tavares\Hotel\Domain\Exception\QuartoIndisponivelException;

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
| id
|--------------------------------------------------------------------------
*/

test('id retorna o identificador do quarto', function () {
    $quarto = new Quarto(7, Status::Disponivel);

    expect($quarto->id())->toBe(7);
});

/*
|--------------------------------------------------------------------------
| status
|--------------------------------------------------------------------------
*/

test('status retorna o estado atual do quarto', function (Status $status) {
    $quarto = new Quarto(1, $status);

    expect($quarto->status())->toBe($status);
})->with([
    'disponível' => Status::Disponivel,
    'ocupado'    => Status::Ocupado,
    'manutenção' => Status::Manutencao,
]);

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
| ocupar
|--------------------------------------------------------------------------
*/

test('ocupar marca o quarto como ocupado quando ele está disponível', function () {
    $quarto = new Quarto(1, Status::Disponivel);

    $quarto->ocupar();

    expect($quarto->isOcupado())->toBeTrue();
});

test('ocupar lança QuartoIndisponivelException quando o quarto não está disponível', function (Status $status) {
    $quarto = new Quarto(1, $status);

    expect(fn () => $quarto->ocupar())->toThrow(QuartoIndisponivelException::class);
})->with([
    'ocupado'    => Status::Ocupado,
    'manutenção' => Status::Manutencao,
]);

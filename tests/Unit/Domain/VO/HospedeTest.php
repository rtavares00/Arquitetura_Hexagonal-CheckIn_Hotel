<?php

use Tavares\Hotel\Domain\VO\Hospede;
use Tavares\Hotel\Domain\VO\Exception\CpfInvalidoException;

/*
|--------------------------------------------------------------------------
| __construct
|--------------------------------------------------------------------------
*/

test('cria um hóspede quando o CPF é válido', function (string $cpf) {
    $hospede = new Hospede($cpf);

    expect($hospede)->toBeInstanceOf(Hospede::class);
})->with([
    'com máscara'  => '529.982.247-25',
    'sem máscara'  => '52998224725',
]);

test('lança CpfInvalidoException quando o CPF é inválido', function (string $cpf) {
    expect(fn () => new Hospede($cpf))->toThrow(CpfInvalidoException::class);
})->with([
    'dígito verificador incorreto' => '529.982.247-24',
    'sequência repetida'           => '111.111.111-11',
    'menos de 11 dígitos'          => '123',
    'mais de 11 dígitos'           => '529982247250',
    'vazio'                        => '',
]);

/*
|--------------------------------------------------------------------------
| cpf
|--------------------------------------------------------------------------
*/

test('cpf retorna o CPF normalizado (somente dígitos)', function (string $informado) {
    $hospede = new Hospede($informado);

    expect($hospede->cpf())->toBe('52998224725');
})->with([
    'com máscara' => '529.982.247-25',
    'sem máscara' => '52998224725',
]);

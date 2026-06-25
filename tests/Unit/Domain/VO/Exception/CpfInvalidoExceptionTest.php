<?php

use Tavares\Hotel\Domain\VO\Exception\CpfInvalidoException;

test('é uma exceção lançável', function () {
    expect(new CpfInvalidoException('123'))->toBeInstanceOf(Throwable::class);
});

test('compõe a mensagem incluindo o CPF informado', function () {
    $excecao = new CpfInvalidoException('111.111.111-11');

    expect($excecao->getMessage())->toBe('O CPF informado é inválido: 111.111.111-11');
});

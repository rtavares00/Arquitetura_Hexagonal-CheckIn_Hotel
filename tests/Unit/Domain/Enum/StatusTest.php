<?php

use Tavares\Hotel\Domain\Enum\Status;

test('possui os estados esperados com seus respectivos valores', function (Status $caso, string $valor) {
    expect($caso->value)->toBe($valor);
})->with([
    'ocupado'    => [Status::Ocupado, 'O'],
    'manutenção' => [Status::Manutencao, 'M'],
    'disponível' => [Status::Disponivel, 'D'],
]);

test('reconstrói o estado a partir do valor', function () {
    expect(Status::from('O'))->toBe(Status::Ocupado)
        ->and(Status::from('M'))->toBe(Status::Manutencao)
        ->and(Status::from('D'))->toBe(Status::Disponivel);
});

test('possui exatamente três estados', function () {
    expect(Status::cases())->toHaveCount(3);
});

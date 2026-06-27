<?php

use Tavares\Hotel\Adapter\JSON\QuartoRepositoryJSON;
use Tavares\Hotel\Adapter\Exception\QuartoNaoEncontradoException;
use Tavares\Hotel\Domain\Quarto;
use Tavares\Hotel\Domain\Enum\Status;

const CAMINHO_QUARTOS = __DIR__ . '/../../../../data/quartos.json';

/*
| Preserva o arquivo de dados real: faz backup antes e restaura depois de
| cada teste, já que salvar() grava no quartos.json.
*/
beforeEach(function () {
    $this->backup = file_get_contents(CAMINHO_QUARTOS);
});

afterEach(function () {
    file_put_contents(CAMINHO_QUARTOS, $this->backup);
});

/*
|--------------------------------------------------------------------------
| buscar
|--------------------------------------------------------------------------
*/

test('buscar retorna o quarto com o status correto', function (int $id, Status $status) {
    $quarto = (new QuartoRepositoryJSON())->buscar($id);

    expect($quarto->id())->toBe($id)
        ->and($quarto->status())->toBe($status);
})->with([
    'disponível' => [101, Status::Disponivel],
    'ocupado'    => [102, Status::Ocupado],
    'manutenção' => [103, Status::Manutencao],
]);

test('buscar lança QuartoNaoEncontradoException quando o id não existe', function () {
    expect(fn () => (new QuartoRepositoryJSON())->buscar(999))
        ->toThrow(QuartoNaoEncontradoException::class);
});

/*
|--------------------------------------------------------------------------
| salvar
|--------------------------------------------------------------------------
*/

test('salvar persiste o novo estado do quarto no arquivo', function () {
    $quarto = (new QuartoRepositoryJSON())->buscar(101);
    $quarto->ocupar();

    (new QuartoRepositoryJSON())->salvar($quarto);

    $relido = (new QuartoRepositoryJSON())->buscar(101);
    expect($relido->isOcupado())->toBeTrue();
});

test('salvar não altera a quantidade de quartos do arquivo', function () {
    $quarto = (new QuartoRepositoryJSON())->buscar(101);
    $quarto->ocupar();

    (new QuartoRepositoryJSON())->salvar($quarto);

    $dados = json_decode(file_get_contents(CAMINHO_QUARTOS), true);
    expect($dados)->toHaveCount(10);
});

test('salvar lança QuartoNaoEncontradoException quando o quarto não existe', function () {
    expect(fn () => (new QuartoRepositoryJSON())->salvar(new Quarto(999, Status::Disponivel)))
        ->toThrow(QuartoNaoEncontradoException::class);
});

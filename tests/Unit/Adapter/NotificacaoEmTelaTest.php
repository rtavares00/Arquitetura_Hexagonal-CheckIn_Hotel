<?php

use Tavares\Hotel\Adapter\NotificacaoEmTela;

test('confirmarCheckin exibe a confirmação com o número do quarto em tela', function () {
    ob_start();
    (new NotificacaoEmTela())->confirmarCheckin(101);
    $saida = ob_get_clean();

    expect($saida)->toContain('Notificação em Tela')
        ->and($saida)->toContain('Check-in confirmado. Quarto: 101');
});

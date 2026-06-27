<?php

use Tavares\Hotel\Adapter\NotificacaoEmTela;

test('notificarCliente exibe a mensagem informada em tela', function () {
    ob_start();
    (new NotificacaoEmTela())->notificarCliente('Check-in confirmado. Quarto: 101');
    $saida = ob_get_clean();

    expect($saida)->toContain('Notificação em Tela')
        ->and($saida)->toContain('Check-in confirmado. Quarto: 101');
});

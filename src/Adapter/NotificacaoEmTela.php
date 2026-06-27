<?php
namespace Tavares\Hotel\Adapter;

use Tavares\Hotel\Port\Notificador;


class NotificacaoEmTela implements Notificador{

    public function confirmarCheckin(int $numeroDoQuarto):void
    {
        echo "Notificação em Tela: \n\n";
        echo "Check-in confirmado. Quarto: {$numeroDoQuarto}";
        echo "\n\n";
    }
}

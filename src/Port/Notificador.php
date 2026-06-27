<?php
namespace Tavares\Hotel\Port;

interface Notificador{

    public function confirmarCheckin(int $numeroDoQuarto):void;
}

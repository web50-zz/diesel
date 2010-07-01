<?php
// NOTE: Инициализация базовой части административного интерфейса.
include_once('pub_base.php');

// NOTE: Префикс для вызова методов пользовательских данных
define('DI_CALL_PREFIX', PUB_PREFIX);

// NOTE: Инициализация интерфейса данных
include_once(DI_INIT);
?>

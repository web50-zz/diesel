<?php
// NOTE: Инициализация базовой части административного интерфейса.
include_once('pub_base.php');

// NOTE: Префикс для вызова методов пользовательских данных
define('UI_CALL_PREFIX', PUB_PREFIX);

// NOTE: Инициализация пользовательского интерфейса
include_once(PUB_INIT);
?>

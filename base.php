<?php
// NOTE: Start session
session_start();

// NOTE: Определяем базовый путь
$pathinfo = pathinfo(__FILE__);
define('BASE_PATH', $pathinfo['dirname'] . '/');

// NOTE: Инициализация переменных окружения
include_once(BASE_PATH . 'kernel/etc/environment.php');

// NOTE: Инициализация ядра
include_once(KERNEL_INIT);
?>

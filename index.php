<?php
// NOTE: Определяем базовый путь
$pathinfo = pathinfo(__FILE__);
define('BASE_PATH', $pathinfo['dirname'] . '/');

// NOTE: Инициализация переменных окружения
include_once(BASE_PATH . 'kernel/etc/environment.php');

// NOTE: Инициализация ядра
include_once(KERNEL_INIT);

/*
NOTE: Если что то из файлов в сторадже не найдено, то редирект mod_rewrite приедет сюда, 
и вот, если апач не нашел файл по прямой ссылке, то и искать 
мы не будем а сразу 404 и досвидос
if(preg_match('/^\/storage\//',$_SERVER['REQUEST_URI'])||
   preg_match('/^\/filestorage\//',$_SERVER['REQUEST_URI'])||
   preg_match('/\/storage\//',$_SERVER['REQUEST_URI'])
   )
{
header("HTTP/1.0 404 Not Found");
echo('Not Found');
exit;
}

// NOTE: Инициализация базовой части административного интерфейса.
include_once('pub_base.php');

// NOTE: Префикс для вызова методов пользовательских данных
define('UI_CALL_PREFIX', PUB_PREFIX);

// NOTE: Инициализация пользовательского интерфейса
include_once(PUB_INIT);
*/
?>

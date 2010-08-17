<?php
// NOTE: Инициализация базовой части CMS
define('AUTH_MODE', 'public');
define('AUTH_DI', 'user');
include_once('base.php');
authenticate::is_logged();
?>

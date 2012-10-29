<?php
/**
 * Front controller for default Minify implementation
 * 
 * DO NOT EDIT! Configure this utility via config.php and groupsConfig.php
 * 
 * @package Minify
 */

/* 9* SBIN Diesel patch */
if (isset($_GET['z'])) {
    // well need session saved files list 
	session_start();
	$z = $_GET['z']; 
	if(isset($_SESSION['paths'][$z]))
	{
		$_GET['f'] =  $_SESSION['paths'][$z];
	}
}
/* 9* end of SBIN diesel patch */


define('MINIFY_MIN_DIR', dirname(__FILE__));

// load default config
require MINIFY_MIN_DIR . '/config.php';
// load current config
if(is_file($_SERVER['DOCUMENT_ROOT'].'/etc/minify.cfg.php')){
	$etc_config = $_SERVER['DOCUMENT_ROOT'].'/etc/minify.cfg.php';
}else{
	$etc_config = $_SERVER['DOCUMENT_ROOT'].'/kernel/etc/conf/minify.cfg.php';
}
if(file_exists($etc_config))
{
	include_once $etc_config;
}
// setup include path
set_include_path($min_libPath . PATH_SEPARATOR . get_include_path());

require 'Minify.php';

Minify::$uploaderHoursBehind = $min_uploaderHoursBehind;
Minify::setCache(
    isset($min_cachePath) ? $min_cachePath : ''
    ,$min_cacheFileLocking
);

if ($min_documentRoot) {
    $_SERVER['DOCUMENT_ROOT'] = $min_documentRoot;
} elseif (0 === stripos(PHP_OS, 'win')) {
    Minify::setDocRoot(); // IIS may need help
}

$min_serveOptions['minifierOptions']['text/css']['symlinks'] = $min_symlinks;
if ($DISABLE_MINIFY == true)
{
	$min_serveOptions['minifiers'][Minify::TYPE_JS] = '';
}
if ($min_allowDebugFlag && isset($_GET['debug'])) {
    $min_serveOptions['debug'] = true;
}

if ($min_errorLogger) {
    require_once 'Minify/Logger.php';
    if (true === $min_errorLogger) {
        require_once 'FirePHP.php';
        Minify_Logger::setLogger(FirePHP::getInstance(true));
    } else {
        Minify_Logger::setLogger($min_errorLogger);
    }
}

// check for URI versioning
if (preg_match('/&\\d/', $_SERVER['QUERY_STRING'])) {
    $min_serveOptions['maxAge'] = 31536000;
}
if (isset($_GET['g'])) {
    // well need groups config
    $min_serveOptions['minApp']['groups'] = (require MINIFY_MIN_DIR . '/groupsConfig.php');
}
if (isset($_GET['f']) || isset($_GET['g'])) {
    // serve!   
	Minify::serve('MinApp', $min_serveOptions);
        
} elseif ($min_enableBuilder) {
    header('Location: builder/');
    exit();
} else {
    header("Location: /");
    exit();
}

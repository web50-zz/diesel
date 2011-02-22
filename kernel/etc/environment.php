<?php
/**
*	Set environment variables
*
* @author	Anthon S. Litvinenko <crazyfluger@gmail.com>
* @access	public
* @package	Fluger CMS
*/
error_reporting( E_ALL ^ E_NOTICE );
//error_reporting(0);

define ('CHARSET', 'UTF8');
define ('ENCODING', 'UTF-8');
//define ('LANG', 'en_EN');
define ('LANG', 'ru_RU');

// NOTE: Base path to CMS files
define ('KERNEL_PATH', BASE_PATH . 'kernel/');

// NOTE: Path to localization files
define ('LOCALES_PATH', KERNEL_PATH . 'var/locales/');

// NOTE: Path to configuration files
define ('CONF_PATH', KERNEL_PATH . 'etc/conf/');
define ('CONF_FEXT', '.cfg.php');
define ('CONF_ETC_PATH',BASE_PATH.'etc/');// for /etc config files if overload default required

// NOTE: Path to initialization files
define ('INIT_PATH', KERNEL_PATH . 'etc/init/');
define ('INIT_FEXT', '.init.php');

// NOTE: Path to meta-class files
define ('CLASS_PATH', KERNEL_PATH . 'etc/class/');
define ('CLASS_FEXT', '.class.php');

// NOTE: Path to library files
define ('LIB_PATH', KERNEL_PATH . 'var/lib/');
define ('LIB_FEXT', '.lib.php');

// NOTE: Path to store log and error files
define ('LOG_PATH', KERNEL_PATH . 'var/logs/');

// NOTE: 9* 05072010 Path to store THEMES template css js images
define ('THEMES_PATH', 'themes/');

// NOTE: Path to connector files
define ('CONNECTOR_PATH', KERNEL_PATH . 'var/connectors/');
define ('CONNECTOR_FEXT', '.connector.php');
define ('CONNECTOR_CLASS_PREFIX', 'connector_');

// NOTE: Path to user interaface files
define ('UI_PATH', KERNEL_PATH . 'var/ui/');
define ('RELATIVE_UI_PATH', 'kernel/var/ui/');
define ('UI_FEXT', '.ui.php');
define ('UI_CLASS_PREFIX', 'ui_');

// NOTE: Path to data interface files
define ('DI_PATH', KERNEL_PATH . 'var/di/');
define ('DI_FEXT', '.di.php');
define ('DI_CLASS_PREFIX', 'di_');

// NOTE: Kernel initialization file
define ('KERNEL_INIT', INIT_PATH . 'kernel' . INIT_FEXT);
// NOTE: User interface initialization file
define ('UI_INIT', INIT_PATH . 'ui' . INIT_FEXT);
// NOTE: Public user interface initialization file
define ('UI_PUB_INIT', INIT_PATH . 'ui_pub' . INIT_FEXT);
// NOTE: Data interface initialization file
define ('DI_INIT', INIT_PATH . 'di' . INIT_FEXT);

// NOTE: Administrator mode
define ('ADM_PREFIX', 'sys_');				// methods prefix

// NOTE: Public mode
define ('PUB_PREFIX', 'pub_');				// methods prefix
define ('PUB_TEMPLATE', 'default.html');		// default template
define ('PUB_INIT', INIT_PATH . 'site' . INIT_FEXT);	// Initialization file
?>

<?php
/**
*	Конфигурирование обработчика URI 
*
* @author	Anthon S. Litvinenko <a.litvinenko@web50.ru>
* @access	public
* @package	SBIN Diesel
* @since	2013-10-01
*/
$uri_configuration = array(
	'/xxx\/.*/' => INIT_PATH . 'admin' . INIT_FEXT,
	'/file\/.*/' => INIT_PATH . 'file' . INIT_FEXT,
	//'/.*/' => INSTANCES_PATH . 'wwwcore/www' . INIT_FEXT,
);
/*

RewriteRule	^xxx/$				adm_gui.php?ui=administrate&cll=workspace	[L,QSA]
RewriteRule	^xxx/(.*)/$			adm_$1.php					[L,QSA]
RewriteRule	^xxx/ui/([^/]+)/(.*)\.[a-z]+$	adm_gui.php?ui=$1&cll=$2			[L,QSA]
RewriteRule	^xxx/di/([^/]+)/(.*)\.[a-z]+$	adm_data.php?di=$1&cll=$2			[L,QSA]

RewriteRule	^files/$			file.php?di=fm_files&cll=get			[L,QSA]
RewriteRule	^ui/([^/]+)/(.*)\.[a-z]+$	pub_gui.php?ui=$1&cll=$2			[L,QSA]
# 9* 26032012 all cfg.php files
RewriteRule     (.*).cfg.php$	index.php		
# 9* 26032012 all files in etc
RewriteRule     ^etc/(.*)	index.php		
# 9* 26032012 all php files in instances
RewriteRule     ^instances/(.*)\.php	index.php	
# 9* 26032012 all directories in instances 
RewriteRule     ^instances/(.*)/$	index.php	
RewriteCond	%{REQUEST_FILENAME} !-f
RewriteCond	%{REQUEST_FILENAME} !-d
RewriteRule	^(.*)		index.php
*/
?>

CREATE TABLE `sys_user` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `multi_login` tinyint(1) unsigned NOT NULL,
  `login` varchar(32) NOT NULL COMMENT 'Login',
  `passw` varchar(64) NOT NULL COMMENT 'Password',
  `type` tinyint(1) unsigned NOT NULL COMMENT 'тип авторизации',
  `server` varchar(64) NOT NULL COMMENT 'LDAP server',
  `name` varchar(64) NOT NULL COMMENT 'User name',
  `email` varchar(64) NOT NULL COMMENT 'e-mail',
  `lang` varchar(5) NOT NULL COMMENT 'Users language',
  `hash` varchar(64) NOT NULL,
  `login_date` datetime NOT NULL,
  `remote_addr` varchar(32) NOT NULL,
  `created_datetime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `account_id` (`login`,`passw`),
  KEY `hash` (`hash`)
) ENGINE=MyISAM AUTO_INCREMENT=20718 DEFAULT CHARSET=utf8 COMMENT='System user'
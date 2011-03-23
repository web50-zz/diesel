CREATE TABLE `interface` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `reg_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'The date of registration',
  `exist` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'The flag of topicality',
  `type` varchar(8) NOT NULL DEFAULT '' COMMENT 'The type of interface',
  `name` varchar(255) NOT NULL COMMENT 'The name of interface',
  `human_name` varchar(255) NOT NULL COMMENT 'The human name of interface',
  PRIMARY KEY (`id`),
  KEY `exist` (`exist`),
  KEY `type_name` (`type`,`name`)
) ENGINE=MyISAM AUTO_INCREMENT=161 DEFAULT CHARSET=utf8
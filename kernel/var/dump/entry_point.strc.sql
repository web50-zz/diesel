CREATE TABLE `entry_point` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `reg_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'The date of registration',
  `exist` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'The flag of topicality',
  `interface_id` smallint(5) NOT NULL DEFAULT '0' COMMENT 'Foreign key',
  `name` varchar(255) NOT NULL COMMENT 'The name of entry point',
  `human_name` varchar(255) NOT NULL COMMENT 'The human name of entry point',
  PRIMARY KEY (`id`),
  KEY `exist` (`exist`),
  KEY `interface_id` (`interface_id`)
) ENGINE=MyISAM AUTO_INCREMENT=340 DEFAULT CHARSET=utf8
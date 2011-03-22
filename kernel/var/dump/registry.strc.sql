CREATE TABLE `registry` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL COMMENT 'Param name',
  `type` tinyint(2) unsigned NOT NULL COMMENT 'Param type',
  `value` text NOT NULL COMMENT 'Param value',
  `comment` varchar(255) NOT NULL COMMENT 'User comment',
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='Registry'
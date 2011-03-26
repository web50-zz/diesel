CREATE TABLE `system_menu` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `type` tinyint(2) unsigned NOT NULL,
  `text` varchar(32) NOT NULL,
  `icon` varchar(32) NOT NULL,
  `ui` varchar(32) NOT NULL,
  `ep` varchar(32) NOT NULL,
  `href` varchar(255) NOT NULL,
  `left` mediumint(8) unsigned NOT NULL,
  `right` mediumint(8) unsigned NOT NULL,
  `level` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `left` (`left`,`right`,`level`)
) ENGINE=MyISAM AUTO_INCREMENT=52 DEFAULT CHARSET=utf8
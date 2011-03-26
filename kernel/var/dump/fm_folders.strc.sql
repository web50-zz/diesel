CREATE TABLE `fm_folders` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `left` mediumint(8) unsigned NOT NULL default '0',
  `right` mediumint(8) unsigned NOT NULL default '0',
  `level` mediumint(8) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `left` (`left`,`right`,`level`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8
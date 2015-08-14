CREATE TABLE `fm_files` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `fm_folders_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `created_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `changed_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `title` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(128) NOT NULL DEFAULT '',
  `real_name` varchar(64) NOT NULL DEFAULT '',
  `comment` text NOT NULL,
  `type` varchar(32) NOT NULL DEFAULT '',
  `size` int(11) unsigned NOT NULL DEFAULT '0',
  `publication_date` datetime DEFAULT NULL,
  `edition_date` datetime DEFAULT NULL,
  `display_order` int(11) unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fm_folders_id` (`fm_folders_id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

CREATE TABLE `entry_point_group` (
  `entry_point_id` smallint(5) unsigned NOT NULL COMMENT 'The entry point ID',
  `group_id` smallint(5) unsigned NOT NULL COMMENT 'The group ID',
  UNIQUE KEY `entry_point_group` (`entry_point_id`,`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8
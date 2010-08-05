SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

DROP TABLE IF EXISTS `article`;
CREATE TABLE `article` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `release_date` date NOT NULL DEFAULT '0000-00-00',
  `title` varchar(255) NOT NULL DEFAULT '',
  `author` varchar(255) NOT NULL DEFAULT '',
  `source` varchar(255) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- --------------------------------------------------------

DROP TABLE IF EXISTS `fm_files`;
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
  `size` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fm_folders_id` (`fm_folders_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- --------------------------------------------------------

DROP TABLE IF EXISTS `fm_folders`;
CREATE TABLE `fm_folders` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `left` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `right` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `level` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `left` (`left`,`right`,`level`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `fm_folders` (`id`, `title`, `left`, `right`, `level`) VALUES
(1, 'Home', 1, 2, 1);
-- --------------------------------------------------------

DROP TABLE IF EXISTS `help`;
CREATE TABLE `help` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- --------------------------------------------------------

DROP TABLE IF EXISTS `news`;
CREATE TABLE `news` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `release_date` date NOT NULL,
  `title` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `source` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `author` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `content` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
-- --------------------------------------------------------

DROP TABLE IF EXISTS `structure`;
CREATE TABLE `structure` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `hidden` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'скрыть',
  `title` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(16) NOT NULL DEFAULT '',
  `uri` varchar(255) NOT NULL DEFAULT '',
  `redirect` varchar(255) NOT NULL DEFAULT '',
  `module` varchar(100) NOT NULL DEFAULT '',
  `params` varchar(255) NOT NULL DEFAULT '',
  `template` varchar(64) NOT NULL DEFAULT 'default',
  `private` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `auth_module` varchar(32) NOT NULL DEFAULT '',
  `left` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `right` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `level` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `mtitle` varchar(255) NOT NULL DEFAULT '',
  `mkeywords` varchar(255) NOT NULL DEFAULT '',
  `mdescr` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `site_part_id` (`left`,`right`,`level`),
  KEY `uri` (`uri`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `structure` (`id`, `hidden`, `title`, `name`, `uri`, `redirect`, `module`, `params`, `template`, `private`, `auth_module`, `left`, `right`, `level`, `mtitle`, `mkeywords`, `mdescr`) VALUES
(1, 0, 'home', 'home', '/', '', 'text', '', 'default.html', 0, '', 1, 2, 1, '', '', '');
-- --------------------------------------------------------

DROP TABLE IF EXISTS `structure_content`;
CREATE TABLE `structure_content` (
  `pid` mediumint(8) unsigned NOT NULL COMMENT 'Page ID',
  `cid` mediumint(8) unsigned NOT NULL COMMENT 'Content ID',
  `ui_name` varchar(32) NOT NULL COMMENT 'UI name',
  UNIQUE KEY `pid` (`pid`,`cid`,`ui_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Page Content Link';
-- --------------------------------------------------------

DROP TABLE IF EXISTS `sys_user`;
CREATE TABLE `sys_user` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(32) NOT NULL COMMENT 'Login',
  `multi_login` tinyint(1) UNSIGNED NOT NULL COMMENT 'Multi-Login',
  `passw` varchar(64) NOT NULL COMMENT 'Password',
  `name` varchar(64) NOT NULL COMMENT 'User name',
  `email` varchar(64) NOT NULL COMMENT 'e-mail',
  `lang` varchar(5) NOT NULL COMMENT 'Users language',
  `hash` varchar(64) NOT NULL,
  `login_date` datetime NOT NULL,
  `remote_addr` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `account_id` (`login`,`passw`),
  KEY `hash` (`hash`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='System user';

INSERT INTO `sys_user` (`id`, `login`, `passw`, `name`, `email`, `hash`, `login_date`, `remote_addr`) VALUES
(1, 'admin', '*4ACFE3202A5FF5CF467898FC58AAB1D615029441', 'Administrator', 'admin@local.host', '7d20278a063cd778909f3a54569f0982', '', '');
-- --------------------------------------------------------

DROP TABLE IF EXISTS `text`;
CREATE TABLE `text` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- --------------------------------------------------------

DROP TABLE IF EXISTS `interface`;
CREATE TABLE `interface` (
	`id` SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
	`reg_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'The date of registration',
	`exist` TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'The flag of topicality',
	`type` TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'The type of interface',
	`name` VARCHAR(255) NOT NULL COMMENT "The name of interface",
	`human_name` VARCHAR(255) NOT NULL COMMENT "The human name of interface",
	`entry_point` VARCHAR(255) NOT NULL COMMENT "The name of interfaces method",
	`human_entry_point` VARCHAR(255) NOT NULL COMMENT "The human name of interfaces method",
	PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- --------------------------------------------------------

DROP TABLE IF EXISTS `interface`;
CREATE TABLE `group` (
	`id` SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(255) NOT NULL COMMENT "The name of group",
	PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- --------------------------------------------------------

DROP TABLE IF EXISTS `interface_group`;
CREATE TABLE `interface_group` (
	`interface_id` SMALLINT(5) UNSIGNED NOT NULL COMMENT 'The interfaces method ID',
	`group_id` SMALLINT(5) UNSIGNED NOT NULL COMMENT 'The groups ID',
	UNIQUE `interace_group` (`interface_id`, `group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- --------------------------------------------------------

DROP TABLE IF EXISTS `group_user`;
CREATE TABLE `group_user` (
	`group_id` SMALLINT(5) UNSIGNED NOT NULL COMMENT 'The groups ID',
	`user_id` SMALLINT(5) UNSIGNED NOT NULL COMMENT 'The users ID',
	UNIQUE `group_user` (`group_id`, `user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- --------------------------------------------------------

DROP TABLE IF EXISTS `guide_producer`;
CREATE TABLE `guide_producer` (
	`id` SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(255) NOT NULL COMMENT "The producer`s name",
	`description` TEXT NOT NULL COMMENT "The producer`s description",
	PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- --------------------------------------------------------

DROP TABLE IF EXISTS `guide_collection`;
CREATE TABLE `guide_collection` (
	`id` SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(255) NOT NULL COMMENT "The collection`s name",
	`description` TEXT NOT NULL COMMENT "The collection`s description",
	PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- --------------------------------------------------------

DROP TABLE IF EXISTS `guide_group`;
CREATE TABLE `guide_group` (
	`id` SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(255) NOT NULL COMMENT "The group`s name",
	`description` TEXT NOT NULL COMMENT "The group`s description",
	PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- --------------------------------------------------------

DROP TABLE IF EXISTS `guide_style`;
CREATE TABLE `guide_style` (
	`id` SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(255) NOT NULL COMMENT "The style`s name",
	`description` TEXT NOT NULL COMMENT "The style`s description",
	PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- --------------------------------------------------------

DROP TABLE IF EXISTS `guide_type`;
CREATE TABLE `guide_type` (
	`id` SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(255) NOT NULL COMMENT "The type`s name",
	`description` TEXT NOT NULL COMMENT "The type`s description",
	PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- --------------------------------------------------------

DROP TABLE IF EXISTS `guide_price`;
CREATE TABLE `guide_price` (
	`id` SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
	`title` VARCHAR(255) NOT NULL COMMENT "The type`s name",
	`cost` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT "Cost",
	PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- --------------------------------------------------------

DROP TABLE IF EXISTS `catalogue_item`;
CREATE TABLE `catalogue_item` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `created_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `creator_uid` smallint(5) unsigned NOT NULL,
  `changed_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `changer_uid` smallint(5) unsigned NOT NULL,
  `on_offer` tinyint(1) unsigned NOT NULL,
  `recomended` tinyint(1) unsigned NOT NULL,
  `income_date` date NOT NULL DEFAULT '0000-00-00',
  `title` varchar(255) NOT NULL DEFAULT '',
  `preview` varchar(255) NOT NULL DEFAULT '',
  `picture` varchar(255) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `price_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `prepayment` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `payment_forward` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `type_id` smallint(5) unsigned NOT NULL,
  `producer_id` smallint(5) unsigned NOT NULL,
  `collection_id` smallint(5) unsigned NOT NULL,
  `group_id` smallint(5) unsigned NOT NULL,
  `style_id` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- --------------------------------------------------------

DROP TABLE IF EXISTS `catalogue_file`;
CREATE TABLE `catalogue_file` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `catalogue_item_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `created_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `changed_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `title` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(128) NOT NULL DEFAULT '',
  `real_name` varchar(64) NOT NULL DEFAULT '',
  `comment` text NOT NULL,
  `item_type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `type` varchar(32) NOT NULL DEFAULT '',
  `size` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `catalogue_item_id` (`catalogue_item_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- --------------------------------------------------------

DROP TABLE IF EXISTS `catalogue_style`;
CREATE TABLE `catalogue_style` (
	`catalogue_item_id` SMALLINT(5) UNSIGNED NOT NULL COMMENT 'The catalogue`s item ID',
	`style_id` SMALLINT(5) UNSIGNED NOT NULL COMMENT 'The style ID',
	UNIQUE `catalogue_style` (`catalogue_item_id`, `style_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- --------------------------------------------------------

DROP TABLE IF EXISTS `ui_view_point`;
CREATE TABLE `ui_view_point` (
	`id` SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
	`page_id` SMALLINT(5) UNSIGNED NOT NULL,
	`view_point` TINYINT(3) UNSIGNED NOT NULL,
	`ui_name` VARCHAR(255) NOT NULL,
	`ui_call` VARCHAR(255) NOT NULL,
	`ui_configure` TEXT NOT NULL,
	PRIMARY KEY (`id`),
	KEY `page_id` (`page_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- --------------------------------------------------------

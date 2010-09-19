-- MySQL dump 10.11
--
-- Host: localhost    Database: market_web50
-- ------------------------------------------------------
-- Server version	5.0.51a-24+lenny3

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `article`
--

DROP TABLE IF EXISTS `article`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `article` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `release_date` date NOT NULL default '0000-00-00',
  `title` varchar(255) NOT NULL default '',
  `author` varchar(255) NOT NULL default '',
  `source` varchar(255) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `catalogue_file`
--

DROP TABLE IF EXISTS `catalogue_file`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `catalogue_file` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `catalogue_item_id` mediumint(8) unsigned NOT NULL default '0',
  `created_datetime` datetime NOT NULL default '0000-00-00 00:00:00',
  `changed_datetime` datetime NOT NULL default '0000-00-00 00:00:00',
  `title` varchar(255) NOT NULL default '',
  `name` varchar(128) NOT NULL default '',
  `real_name` varchar(64) NOT NULL default '',
  `comment` text NOT NULL,
  `item_type` tinyint(3) unsigned NOT NULL default '0',
  `type` varchar(32) NOT NULL default '',
  `size` mediumint(8) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `catalogue_item_id` (`catalogue_item_id`)
) ENGINE=MyISAM AUTO_INCREMENT=36546 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `catalogue_item`
--

DROP TABLE IF EXISTS `catalogue_item`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `catalogue_item` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `created_datetime` datetime NOT NULL default '0000-00-00 00:00:00',
  `creator_uid` smallint(5) unsigned NOT NULL,
  `changed_datetime` datetime NOT NULL default '0000-00-00 00:00:00',
  `changer_uid` smallint(5) unsigned NOT NULL,
  `on_offer` tinyint(1) unsigned NOT NULL,
  `recomended` tinyint(1) unsigned NOT NULL,
  `income_date` date NOT NULL default '0000-00-00',
  `title` varchar(255) NOT NULL default '',
  `preview` varchar(255) NOT NULL default '',
  `picture` varchar(255) NOT NULL default '',
  `description` text NOT NULL,
  `price_id` smallint(5) unsigned NOT NULL default '0',
  `prepayment` decimal(10,2) unsigned NOT NULL default '0.00',
  `payment_forward` decimal(10,2) unsigned NOT NULL default '0.00',
  `type_id` smallint(5) unsigned NOT NULL,
  `producer_id` smallint(5) unsigned NOT NULL,
  `collection_id` smallint(5) unsigned NOT NULL,
  `group_id` smallint(5) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `group_id` (`group_id`),
  KEY `collection_id` (`collection_id`),
  KEY `producer_id` (`producer_id`),
  KEY `type_id` (`type_id`),
  KEY `title` (`title`)
) ENGINE=MyISAM AUTO_INCREMENT=19882 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `catalogue_style`
--

DROP TABLE IF EXISTS `catalogue_style`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `catalogue_style` (
  `catalogue_item_id` smallint(5) unsigned NOT NULL COMMENT 'The catalogue`s item ID',
  `style_id` smallint(5) unsigned NOT NULL COMMENT 'The style ID',
  UNIQUE KEY `catalogue_style` (`catalogue_item_id`,`style_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `country_regions`
--

DROP TABLE IF EXISTS `country_regions`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `country_regions` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `cr_regions_created_datetime` datetime default NULL,
  `cr_regions_changed_datetime` datetime default NULL,
  `cr_regions_deleted_datetime` datetime default NULL,
  `cr_regions_deleter_uid` int(10) NOT NULL default '0',
  `cr_regions_creator_uid` int(10) NOT NULL default '0',
  `cr_regions_changer_uid` int(10) NOT NULL default '0',
  `cr_regions_title` text NOT NULL,
  `cr_regions_part_id` int(10) unsigned NOT NULL default '0',
  `cr_regions_post_zone` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `country_regions_cntry`
--

DROP TABLE IF EXISTS `country_regions_cntry`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `country_regions_cntry` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `cr_cntry_created_datetime` datetime default NULL,
  `cr_cntry_changed_datetime` datetime default NULL,
  `cr_cntry_deleted_datetime` datetime default NULL,
  `cr_cntry_deleter_uid` int(10) NOT NULL default '0',
  `cr_cntry_creator_uid` int(10) NOT NULL default '0',
  `cr_cntry_changer_uid` int(10) NOT NULL default '0',
  `cr_cntry_title` varchar(100) NOT NULL,
  `cr_cntry_title_eng` varchar(100) NOT NULL,
  `cr_cntry_code` varchar(5) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `entry_point`
--

DROP TABLE IF EXISTS `entry_point`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `entry_point` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `reg_date` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'The date of registration',
  `exist` tinyint(1) unsigned NOT NULL default '0' COMMENT 'The flag of topicality',
  `interface_id` smallint(5) NOT NULL default '0' COMMENT 'Foreign key',
  `name` varchar(255) NOT NULL COMMENT 'The name of entry point',
  `human_name` varchar(255) NOT NULL COMMENT 'The human name of entry point',
  PRIMARY KEY  (`id`),
  KEY `exist` (`exist`),
  KEY `interface_id` (`interface_id`)
) ENGINE=MyISAM AUTO_INCREMENT=361 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `entry_point_group`
--

DROP TABLE IF EXISTS `entry_point_group`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `entry_point_group` (
  `entry_point_id` smallint(5) unsigned NOT NULL COMMENT 'The entry point ID',
  `group_id` smallint(5) unsigned NOT NULL COMMENT 'The group ID',
  UNIQUE KEY `entry_point_group` (`entry_point_id`,`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `faq`
--

DROP TABLE IF EXISTS `faq`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `faq` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `faq_created_datetime` datetime default NULL,
  `faq_changed_datetime` datetime default NULL,
  `faq_deleted_datetime` datetime default NULL,
  `faq_deleter_uid` int(10) NOT NULL default '0',
  `faq_creator_uid` int(10) NOT NULL default '0',
  `faq_changer_uid` int(10) NOT NULL default '0',
  `faq_question` text NOT NULL,
  `faq_answer` text NOT NULL,
  `faq_question_author_name` varchar(60) NOT NULL,
  `faq_question_author_email` varchar(60) default NULL,
  `faq_part_id` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `faq_parts`
--

DROP TABLE IF EXISTS `faq_parts`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `faq_parts` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `faqp_created_datetime` datetime default NULL,
  `faqp_changed_datetime` datetime default NULL,
  `faqp_deleted_datetime` datetime default NULL,
  `faqp_deleter_uid` int(10) NOT NULL default '0',
  `faqp_creator_uid` int(10) NOT NULL default '0',
  `faqp_changer_uid` int(10) NOT NULL default '0',
  `faqp_title` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `fm_files`
--

DROP TABLE IF EXISTS `fm_files`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `fm_files` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `fm_folders_id` mediumint(8) unsigned NOT NULL default '0',
  `created_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `changed_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `title` varchar(255) NOT NULL default '',
  `name` varchar(128) NOT NULL default '',
  `real_name` varchar(64) NOT NULL default '',
  `comment` text NOT NULL,
  `type` varchar(32) NOT NULL default '',
  `size` mediumint(8) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `fm_folders_id` (`fm_folders_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `fm_folders`
--

DROP TABLE IF EXISTS `fm_folders`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `fm_folders` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `left` mediumint(8) unsigned NOT NULL default '0',
  `right` mediumint(8) unsigned NOT NULL default '0',
  `level` mediumint(8) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `left` (`left`,`right`,`level`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `group`
--

DROP TABLE IF EXISTS `group`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `group` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL COMMENT 'The name of group',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `group_user`
--

DROP TABLE IF EXISTS `group_user`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `group_user` (
  `group_id` smallint(5) unsigned NOT NULL COMMENT 'The groups ID',
  `user_id` smallint(5) unsigned NOT NULL COMMENT 'The users ID',
  UNIQUE KEY `group_user` (`group_id`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `guestbook`
--

DROP TABLE IF EXISTS `guestbook`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `guestbook` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `gb_created_datetime` datetime default NULL,
  `gb_changed_datetime` datetime default NULL,
  `gb_deleted_datetime` datetime default NULL,
  `gb_deleter_uid` int(10) NOT NULL default '0',
  `gb_creator_uid` int(10) NOT NULL default '0',
  `gb_changer_uid` int(10) NOT NULL default '0',
  `gb_record` text NOT NULL,
  `gb_author_name` varchar(60) NOT NULL,
  `gb_author_email` varchar(60) default NULL,
  `gb_answer` text,
  `gb_author_location` varchar(80) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=96 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `guide_collection`
--

DROP TABLE IF EXISTS `guide_collection`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `guide_collection` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL COMMENT 'The collection`s name',
  `name_eng` varchar(255) NOT NULL COMMENT 'The collection`s name in English',
  `discount` decimal(10,2) unsigned NOT NULL default '0.00' COMMENT 'The collection`s discount',
  `description` text NOT NULL COMMENT 'The collection`s description',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=96 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `guide_country`
--

DROP TABLE IF EXISTS `guide_country`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `guide_country` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `created_datetime` datetime NOT NULL default '0000-00-00 00:00:00',
  `creator_uid` smallint(5) NOT NULL default '0',
  `changed_datetime` datetime NOT NULL default '0000-00-00 00:00:00',
  `changer_uid` smallint(5) NOT NULL default '0',
  `deleted_datetime` datetime NOT NULL default '0000-00-00 00:00:00',
  `deleter_uid` smallint(5) NOT NULL default '0',
  `title` varchar(64) NOT NULL,
  `title_eng` varchar(64) NOT NULL,
  `code` varchar(3) NOT NULL,
  `cost` decimal(10,2) unsigned NOT NULL,
  `ccy` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=75 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `guide_currency`
--

DROP TABLE IF EXISTS `guide_currency`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `guide_currency` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `name` varchar(4) NOT NULL COMMENT 'The currency',
  `title` varchar(255) NOT NULL COMMENT 'The currency title',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `guide_group`
--

DROP TABLE IF EXISTS `guide_group`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `guide_group` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL COMMENT 'The group`s name',
  `description` text NOT NULL COMMENT 'The group`s description',
  PRIMARY KEY  (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=7650 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `guide_order_status`
--

DROP TABLE IF EXISTS `guide_order_status`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `guide_order_status` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL COMMENT 'Title',
  `status` tinyint(1) unsigned NOT NULL COMMENT 'Status',
  PRIMARY KEY  (`id`),
  KEY `status` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `guide_pay_type`
--

DROP TABLE IF EXISTS `guide_pay_type`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `guide_pay_type` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL COMMENT 'Title',
  `status` tinyint(1) unsigned NOT NULL COMMENT 'Status',
  PRIMARY KEY  (`id`),
  KEY `status` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `guide_post_zone`
--

DROP TABLE IF EXISTS `guide_post_zone`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `guide_post_zone` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL COMMENT 'The post zone title',
  `cost` decimal(10,2) unsigned NOT NULL COMMENT 'The delivery cost',
  `ccy` tinyint(1) unsigned NOT NULL COMMENT 'The costs currency',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `guide_price`
--

DROP TABLE IF EXISTS `guide_price`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `guide_price` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL COMMENT 'The type`s name',
  `cost` decimal(10,2) unsigned NOT NULL default '0.00' COMMENT 'Cost',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=383 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `guide_producer`
--

DROP TABLE IF EXISTS `guide_producer`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `guide_producer` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL COMMENT 'The producer`s name',
  `description` text NOT NULL COMMENT 'The producer`s description',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `guide_region`
--

DROP TABLE IF EXISTS `guide_region`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `guide_region` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `created_datetime` datetime NOT NULL default '0000-00-00 00:00:00',
  `creator_uid` smallint(5) NOT NULL default '0',
  `changed_datetime` datetime NOT NULL default '0000-00-00 00:00:00',
  `changer_uid` smallint(5) NOT NULL default '0',
  `deleted_datetime` datetime NOT NULL default '0000-00-00 00:00:00',
  `deleter_uid` smallint(5) NOT NULL default '0',
  `country_id` smallint(5) NOT NULL COMMENT 'The country`s id',
  `title` varchar(64) NOT NULL,
  `post_zone_id` smallint(5) NOT NULL COMMENT 'The post_zone`s id',
  PRIMARY KEY  (`id`),
  KEY `country_id` (`country_id`)
) ENGINE=MyISAM AUTO_INCREMENT=183 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `guide_style`
--

DROP TABLE IF EXISTS `guide_style`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `guide_style` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL COMMENT 'The style`s name',
  `description` text NOT NULL COMMENT 'The style`s description',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=133 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `guide_type`
--

DROP TABLE IF EXISTS `guide_type`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `guide_type` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL COMMENT 'The type`s name',
  `description` text NOT NULL COMMENT 'The type`s description',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `help`
--

DROP TABLE IF EXISTS `help`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `help` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `name` varchar(64) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `interface`
--

DROP TABLE IF EXISTS `interface`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `interface` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `reg_date` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'The date of registration',
  `exist` tinyint(1) unsigned NOT NULL default '0' COMMENT 'The flag of topicality',
  `type` varchar(8) NOT NULL default '' COMMENT 'The type of interface',
  `name` varchar(255) NOT NULL COMMENT 'The name of interface',
  `human_name` varchar(255) NOT NULL COMMENT 'The human name of interface',
  PRIMARY KEY  (`id`),
  KEY `exist` (`exist`),
  KEY `type_name` (`type`,`name`)
) ENGINE=MyISAM AUTO_INCREMENT=76 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `interface_group`
--

DROP TABLE IF EXISTS `interface_group`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `interface_group` (
  `interface_id` smallint(5) unsigned NOT NULL COMMENT 'The interfaces method ID',
  `group_id` smallint(5) unsigned NOT NULL COMMENT 'The groups ID',
  UNIQUE KEY `interace_group` (`interface_id`,`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `market_clients`
--

DROP TABLE IF EXISTS `market_clients`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `market_clients` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `clnt_sys_uid` int(10) unsigned NOT NULL,
  `clnt_created_datetime` datetime NOT NULL,
  `clnt_changed_datetime` datetime NOT NULL,
  `clnt_deleted_datetime` datetime NOT NULL,
  `clnt_creator_uid` int(10) unsigned NOT NULL,
  `clnt_changer_uid` int(10) unsigned NOT NULL,
  `clnt_deleter_uid` int(10) unsigned NOT NULL,
  `clnt_deleted_flag` tinyint(1) unsigned NOT NULL,
  `clnt_name` varchar(64) NOT NULL,
  `clnt_lname` varchar(64) NOT NULL,
  `clnt_mname` varchar(64) NOT NULL,
  `clnt_email` varchar(64) NOT NULL,
  `clnt_country` int(10) unsigned NOT NULL,
  `clnt_region` int(10) unsigned NOT NULL,
  `clnt_region_custom` varchar(64) NOT NULL,
  `clnt_nas_punkt` varchar(64) NOT NULL,
  `clnt_address` varchar(255) NOT NULL,
  `clnt_phone` varchar(32) NOT NULL,
  `clnt_payment_pref` tinyint(1) unsigned NOT NULL,
  `clnt_payment_curr` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `account_id` (`clnt_changed_datetime`,`clnt_deleted_datetime`),
  KEY `hash` (`clnt_nas_punkt`)
) ENGINE=MyISAM AUTO_INCREMENT=17924 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `market_latest`
--

DROP TABLE IF EXISTS `market_latest`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `market_latest` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `m_latest_created_datetime` datetime default NULL,
  `m_latest_changed_datetime` datetime default NULL,
  `m_latest_deleted_datetime` datetime default NULL,
  `m_latest_deleter_uid` int(10) NOT NULL default '0',
  `m_latest_creator_uid` int(10) NOT NULL default '0',
  `m_latest_changer_uid` int(10) NOT NULL default '0',
  `m_latest_product_id` int(10) NOT NULL default '0',
  `m_latest_deleted_flag` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `market_latest_long`
--

DROP TABLE IF EXISTS `market_latest_long`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `market_latest_long` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `m_latest_l_created_datetime` datetime default NULL,
  `m_latest_l_changed_datetime` datetime default NULL,
  `m_latest_l_deleted_datetime` datetime default NULL,
  `m_latest_l_deleter_uid` int(10) NOT NULL default '0',
  `m_latest_l_creator_uid` int(10) NOT NULL default '0',
  `m_latest_l_changer_uid` int(10) NOT NULL default '0',
  `m_latest_l_deleted_flag` tinyint(1) unsigned NOT NULL default '0',
  `m_latest_l_issue_datetime` datetime default NULL,
  `m_latest_l_text` text NOT NULL,
  `m_latest_l_title` varchar(255) NOT NULL default '',
  `m_latest_l_product_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `market_latest_long_list`
--

DROP TABLE IF EXISTS `market_latest_long_list`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `market_latest_long_list` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `m_latest_ls_created_datetime` datetime default NULL,
  `m_latest_ls_changed_datetime` datetime default NULL,
  `m_latest_ls_deleted_datetime` datetime default NULL,
  `m_latest_ls_deleter_uid` int(10) NOT NULL default '0',
  `m_latest_ls_creator_uid` int(10) NOT NULL default '0',
  `m_latest_ls_changer_uid` int(10) NOT NULL default '0',
  `m_latest_ls_product_id` int(10) NOT NULL default '0',
  `m_latest_ls_issue_id` int(10) NOT NULL default '0',
  `m_latest_ls_deleted_flag` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=64 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `market_recomendations`
--

DROP TABLE IF EXISTS `market_recomendations`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `market_recomendations` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `m_recomend_created_datetime` datetime default NULL,
  `m_recomend_changed_datetime` datetime default NULL,
  `m_recomend_deleted_datetime` datetime default NULL,
  `m_recomend_deleter_uid` int(10) NOT NULL default '0',
  `m_recomend_creator_uid` int(10) NOT NULL default '0',
  `m_recomend_changer_uid` int(10) NOT NULL default '0',
  `m_recomend_product_id` int(10) NOT NULL default '0',
  `m_recomend_deleted_flag` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5702 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `news`
--

DROP TABLE IF EXISTS `news`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `news` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `release_date` date NOT NULL,
  `title` varchar(64) collate utf8_unicode_ci NOT NULL,
  `source` varchar(255) collate utf8_unicode_ci NOT NULL,
  `author` varchar(64) collate utf8_unicode_ci NOT NULL,
  `content` mediumtext collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `order`
--

DROP TABLE IF EXISTS `order`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `order` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `created_datetime` datetime NOT NULL default '0000-00-00 00:00:00',
  `creator_uid` smallint(5) unsigned NOT NULL,
  `changed_datetime` datetime NOT NULL default '0000-00-00 00:00:00',
  `changer_uid` smallint(5) unsigned NOT NULL,
  `deleted_datetime` datetime NOT NULL default '0000-00-00 00:00:00',
  `deleter_uid` smallint(5) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  `country_id` smallint(5) unsigned NOT NULL,
  `region_id` smallint(5) unsigned NOT NULL,
  `address` text NOT NULL,
  `method_of_payment` tinyint(1) unsigned NOT NULL,
  `discount` decimal(10,2) unsigned NOT NULL,
  `total_items` smallint(5) unsigned NOT NULL,
  `total_items_cost` decimal(10,2) unsigned NOT NULL,
  `number_of_parcels` tinyint(3) unsigned NOT NULL,
  `delivery_cost` decimal(10,2) unsigned NOT NULL,
  `total_cost` decimal(10,2) unsigned NOT NULL,
  `comments` text NOT NULL,
  `admin_comments` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `creator_uid` (`creator_uid`),
  KEY `status` (`status`),
  KEY `method_of_payment` (`method_of_payment`)
) ENGINE=MyISAM AUTO_INCREMENT=44214 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `order_item`
--

DROP TABLE IF EXISTS `order_item`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `order_item` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `order_id` mediumint(8) unsigned NOT NULL,
  `item_id` smallint(5) unsigned NOT NULL,
  `count` smallint(5) unsigned NOT NULL,
  `price1` decimal(10,2) unsigned NOT NULL,
  `price2` decimal(10,2) unsigned NOT NULL,
  `discbool` tinyint(1) unsigned NOT NULL,
  `discount` decimal(10,2) unsigned NOT NULL,
  `access` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `order_id` (`order_id`),
  KEY `item_id` (`item_id`),
  KEY `count` (`count`)
) ENGINE=MyISAM AUTO_INCREMENT=284656 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `pswremind_req`
--

DROP TABLE IF EXISTS `pswremind_req`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `pswremind_req` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `hash` varchar(255) default NULL,
  `req` text,
  `req_datetime` datetime default NULL,
  `req_ip` varchar(15) default NULL,
  `done` tinyint(1) unsigned NOT NULL default '0',
  `done_datetime` datetime default NULL,
  `done_ip` varchar(15) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `structure`
--

DROP TABLE IF EXISTS `structure`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `structure` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `hidden` tinyint(1) unsigned NOT NULL default '0' COMMENT 'скрыть',
  `title` varchar(255) NOT NULL default '',
  `name` varchar(16) NOT NULL default '',
  `uri` varchar(255) NOT NULL default '',
  `redirect` varchar(255) NOT NULL default '',
  `module` varchar(100) NOT NULL default '',
  `params` varchar(255) NOT NULL default '',
  `template` varchar(64) NOT NULL default 'default',
  `private` tinyint(1) unsigned NOT NULL default '0',
  `auth_module` varchar(32) NOT NULL default '',
  `left` mediumint(8) unsigned NOT NULL default '0',
  `right` mediumint(8) unsigned NOT NULL default '0',
  `level` mediumint(8) unsigned NOT NULL default '0',
  `mtitle` varchar(255) NOT NULL default '',
  `mkeywords` varchar(255) NOT NULL default '',
  `mdescr` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `site_part_id` (`left`,`right`,`level`),
  KEY `uri` (`uri`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `structure_content`
--

DROP TABLE IF EXISTS `structure_content`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `structure_content` (
  `pid` mediumint(8) unsigned NOT NULL COMMENT 'Page ID',
  `cid` mediumint(8) unsigned NOT NULL COMMENT 'Content ID',
  `ui_name` varchar(32) NOT NULL COMMENT 'UI name',
  UNIQUE KEY `pid` (`pid`,`cid`,`ui_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Page Content Link';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `subscribe`
--

DROP TABLE IF EXISTS `subscribe`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `subscribe` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL COMMENT 'The name of group',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `subscribe_accounts`
--

DROP TABLE IF EXISTS `subscribe_accounts`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `subscribe_accounts` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `multi_login` tinyint(1) unsigned NOT NULL,
  `login` varchar(32) NOT NULL COMMENT 'Login',
  `passw` varchar(64) NOT NULL COMMENT 'Password',
  `name` varchar(64) NOT NULL COMMENT 'User name',
  `email` varchar(64) NOT NULL COMMENT 'e-mail',
  `lang` varchar(5) NOT NULL COMMENT 'Users language',
  `hash` varchar(64) NOT NULL,
  `login_date` datetime NOT NULL,
  `remote_addr` varchar(32) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `account_id` (`login`,`passw`),
  KEY `hash` (`hash`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `subscribe_messages`
--

DROP TABLE IF EXISTS `subscribe_messages`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `subscribe_messages` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `subscr_created_datetime` datetime default NULL,
  `subscr_changed_datetime` datetime default NULL,
  `subscr_deleted_datetime` datetime default NULL,
  `subscr_deleter_uid` int(10) NOT NULL default '0',
  `subscr_creator_uid` int(10) NOT NULL default '0',
  `subscr_changer_uid` int(10) NOT NULL default '0',
  `subscr_title` varchar(255) NOT NULL,
  `subscr_message_body` text,
  `subscr_id` int(10) NOT NULL default '0',
  `subscr_sended_flag` tinyint(1) unsigned default '0',
  `subscr_sended_datetime` datetime default NULL,
  `subscr_sheduled_to_send` tinyint(1) unsigned default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=52 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `subscribe_req`
--

DROP TABLE IF EXISTS `subscribe_req`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `subscribe_req` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `hash` varchar(255) default NULL,
  `req` text,
  `req_datetime` datetime default NULL,
  `req_ip` varchar(15) default NULL,
  `done` tinyint(1) unsigned NOT NULL default '0',
  `done_datetime` datetime default NULL,
  `done_ip` varchar(15) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=35 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `subscribe_user`
--

DROP TABLE IF EXISTS `subscribe_user`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `subscribe_user` (
  `group_id` smallint(5) unsigned NOT NULL COMMENT 'The groups ID',
  `user_id` smallint(5) unsigned NOT NULL COMMENT 'The users ID',
  UNIQUE KEY `group_user` (`group_id`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `sys_user`
--

DROP TABLE IF EXISTS `sys_user`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `sys_user` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `multi_login` tinyint(1) unsigned NOT NULL,
  `login` varchar(32) NOT NULL COMMENT 'Login',
  `passw` varchar(64) NOT NULL COMMENT 'Password',
  `name` varchar(64) NOT NULL COMMENT 'User name',
  `email` varchar(64) NOT NULL COMMENT 'e-mail',
  `lang` varchar(5) NOT NULL COMMENT 'Users language',
  `hash` varchar(64) NOT NULL,
  `login_date` datetime NOT NULL,
  `remote_addr` varchar(32) NOT NULL,
  `created_datetime` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `account_id` (`login`,`passw`),
  KEY `hash` (`hash`)
) ENGINE=MyISAM AUTO_INCREMENT=20694 DEFAULT CHARSET=utf8 COMMENT='System user';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `text`
--

DROP TABLE IF EXISTS `text`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `text` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `content` text NOT NULL,
  `hide_title` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `ui_view_point`
--

DROP TABLE IF EXISTS `ui_view_point`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `ui_view_point` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `page_id` smallint(5) unsigned NOT NULL,
  `order` tinyint(3) unsigned NOT NULL,
  `deep_hide` tinyint(1) unsigned NOT NULL,
  `view_point` tinyint(3) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `ui_name` varchar(255) NOT NULL,
  `ui_call` varchar(255) NOT NULL,
  `ui_configure` text NOT NULL,
  `cache_enabled` tinyint(1) unsigned NOT NULL,
  `cache_timeout` varchar(6) default NULL,
  PRIMARY KEY  (`id`),
  KEY `page_id` (`page_id`)
) ENGINE=MyISAM AUTO_INCREMENT=123 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2010-09-18 17:55:58

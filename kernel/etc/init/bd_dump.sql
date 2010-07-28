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
-- Dumping data for table `article`
--

LOCK TABLES `article` WRITE;
/*!40000 ALTER TABLE `article` DISABLE KEYS */;
/*!40000 ALTER TABLE `article` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalogue_item`
--

DROP TABLE IF EXISTS `catalogue_item`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `catalogue_item` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `on_offer` tinyint(1) unsigned NOT NULL,
  `title` varchar(255) NOT NULL default '',
  `description` text NOT NULL,
  `prepayment` decimal(10,2) unsigned NOT NULL default '0.00',
  `payment_forward` decimal(10,2) unsigned NOT NULL default '0.00',
  `type_id` smallint(5) unsigned NOT NULL,
  `producer_id` smallint(5) unsigned NOT NULL,
  `collection_id` smallint(5) unsigned NOT NULL,
  `group_id` smallint(5) unsigned NOT NULL,
  `style_id` smallint(5) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `catalogue_item`
--

LOCK TABLES `catalogue_item` WRITE;
/*!40000 ALTER TABLE `catalogue_item` DISABLE KEYS */;
INSERT INTO `catalogue_item` VALUES (1,0,'мясо','кцукцукцукцукцукц','444.00','4545.00',1,0,0,0,0);
/*!40000 ALTER TABLE `catalogue_item` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `fm_files`
--

LOCK TABLES `fm_files` WRITE;
/*!40000 ALTER TABLE `fm_files` DISABLE KEYS */;
INSERT INTO `fm_files` VALUES (1,3,'2010-06-29 20:02:22','2010-06-29 20:02:22','www','radeon-kms.conf','50e2ddca84d8dbe8d937ea5b5e7ad745.conf','','application/octet-stream',25);
/*!40000 ALTER TABLE `fm_files` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `fm_folders`
--

LOCK TABLES `fm_folders` WRITE;
/*!40000 ALTER TABLE `fm_folders` DISABLE KEYS */;
INSERT INTO `fm_folders` VALUES (1,'Home',1,6,1),(2,'test 1',2,3,2),(3,'test 2',4,5,2);
/*!40000 ALTER TABLE `fm_folders` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `group`
--

LOCK TABLES `group` WRITE;
/*!40000 ALTER TABLE `group` DISABLE KEYS */;
INSERT INTO `group` VALUES (14,'test 5'),(13,'test 4');
/*!40000 ALTER TABLE `group` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `group_user`
--

LOCK TABLES `group_user` WRITE;
/*!40000 ALTER TABLE `group_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `group_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `guide_collection`
--

DROP TABLE IF EXISTS `guide_collection`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `guide_collection` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL COMMENT 'The collection`s name',
  `description` text NOT NULL COMMENT 'The collection`s description',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `guide_collection`
--

LOCK TABLES `guide_collection` WRITE;
/*!40000 ALTER TABLE `guide_collection` DISABLE KEYS */;
/*!40000 ALTER TABLE `guide_collection` ENABLE KEYS */;
UNLOCK TABLES;

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
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `guide_group`
--

LOCK TABLES `guide_group` WRITE;
/*!40000 ALTER TABLE `guide_group` DISABLE KEYS */;
/*!40000 ALTER TABLE `guide_group` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `guide_producer`
--

LOCK TABLES `guide_producer` WRITE;
/*!40000 ALTER TABLE `guide_producer` DISABLE KEYS */;
/*!40000 ALTER TABLE `guide_producer` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `guide_style`
--

LOCK TABLES `guide_style` WRITE;
/*!40000 ALTER TABLE `guide_style` DISABLE KEYS */;
/*!40000 ALTER TABLE `guide_style` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `guide_type`
--

LOCK TABLES `guide_type` WRITE;
/*!40000 ALTER TABLE `guide_type` DISABLE KEYS */;
INSERT INTO `guide_type` VALUES (1,'CD',''),(2,'DVD','');
/*!40000 ALTER TABLE `guide_type` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `help`
--

LOCK TABLES `help` WRITE;
/*!40000 ALTER TABLE `help` DISABLE KEYS */;
/*!40000 ALTER TABLE `help` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `interface`
--

DROP TABLE IF EXISTS `interface`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `interface` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `reg_date` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'The date of registration',
  `exist` tinyint(1) NOT NULL default '0' COMMENT 'The flag of topicality',
  `type` varchar(5) NOT NULL default '',
  `name` varchar(255) NOT NULL COMMENT 'The name of interface',
  `human_name` varchar(255) NOT NULL COMMENT 'The human name of interface',
  `entry_point` varchar(255) NOT NULL COMMENT 'The name of interfaces method',
  `human_entry_point` varchar(255) NOT NULL COMMENT 'The human name of interfaces method',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=162 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `interface`
--

LOCK TABLES `interface` WRITE;
/*!40000 ALTER TABLE `interface` DISABLE KEYS */;
INSERT INTO `interface` VALUES (1,'2010-06-29 17:21:13',1,'di','text','Текстовые страницы','sys_list',''),(2,'2010-06-29 17:21:13',1,'di','text','Текстовые страницы','sys_get',''),(3,'2010-06-29 17:21:13',1,'di','text','Текстовые страницы','sys_item',''),(4,'2010-06-29 17:21:13',1,'di','text','Текстовые страницы','sys_set',''),(5,'2010-06-29 17:21:13',1,'di','text','Текстовые страницы','sys_unset',''),(6,'2010-06-29 17:21:13',1,'di','news','Лента новостей','sys_list',''),(7,'2010-06-29 17:21:13',1,'di','news','Лента новостей','sys_item',''),(8,'2010-06-29 17:21:13',1,'di','news','Лента новостей','sys_set',''),(9,'2010-06-29 17:21:13',1,'di','news','Лента новостей','sys_unset',''),(10,'2010-06-29 17:21:13',1,'di','fm_folders','Папки с файлами','sys_slice',''),(11,'2010-06-29 17:21:13',1,'di','fm_folders','Папки с файлами','sys_item',''),(12,'2010-06-29 17:21:13',1,'di','fm_folders','Папки с файлами','sys_set',''),(13,'2010-06-29 17:21:13',1,'di','fm_folders','Папки с файлами','sys_move',''),(14,'2010-06-29 17:21:13',1,'di','fm_folders','Папки с файлами','sys_unset',''),(15,'2010-06-29 17:21:13',1,'di','group_user','Link between users and groups','sys_add_users_to_group',''),(16,'2010-06-29 17:21:13',1,'di','group_user','Link between users and groups','sys_remove_users_from_group',''),(17,'2010-06-29 17:21:13',1,'di','help','Контакты компании','sys_get',''),(18,'2010-06-29 17:21:13',1,'di','help','Контакты компании','sys_list',''),(19,'2010-06-29 17:21:13',1,'di','help','Контакты компании','sys_item',''),(20,'2010-06-29 17:21:13',1,'di','help','Контакты компании','sys_set',''),(21,'2010-06-29 17:21:13',1,'di','help','Контакты компании','sys_unset',''),(22,'2010-06-29 17:21:13',1,'di','structure','Структура сайта','sys_set',''),(23,'2010-06-29 17:21:13',1,'di','structure','Структура сайта','sys_move',''),(24,'2010-06-29 17:21:13',1,'di','structure','Структура сайта','sys_unset',''),(26,'2010-06-29 17:21:13',1,'di','structure','Структура сайта','sys_page',''),(27,'2010-06-29 17:21:13',1,'di','structure','Структура сайта','sys_slice',''),(28,'2010-06-29 17:21:13',1,'di','interface_group','Link between Interfaces and Groups','sys_add_interfaces_to_group',''),(29,'2010-06-29 17:21:13',1,'di','interface_group','Link between Interfaces and Groups','sys_remove_interfaces_from_group',''),(30,'2010-06-29 17:21:13',1,'di','group','The user`s groups','sys_list',''),(31,'2010-06-29 17:21:13',1,'di','group','The user`s groups','sys_get',''),(32,'2010-06-29 17:21:13',1,'di','group','The user`s groups','sys_set',''),(33,'2010-06-29 17:21:13',1,'di','group','The user`s groups','sys_unset',''),(34,'2010-06-29 17:21:13',1,'di','article','Статьи','sys_list',''),(35,'2010-06-29 17:21:13',1,'di','article','Статьи','sys_get',''),(36,'2010-06-29 17:21:13',1,'di','article','Статьи','sys_item',''),(37,'2010-06-29 17:21:13',1,'di','article','Статьи','sys_set',''),(38,'2010-06-29 17:21:13',1,'di','article','Статьи','sys_unset',''),(39,'2010-06-29 17:21:13',1,'di','interface','The Interfaces','sys_sync',''),(40,'2010-06-29 17:21:13',1,'di','interface','The Interfaces','sys_intefaces_in_group',''),(41,'2010-06-29 17:21:13',1,'di','interface','The Interfaces','sys_list',''),(42,'2010-06-29 17:21:13',1,'di','interface','The Interfaces','sys_get',''),(43,'2010-06-29 17:21:13',1,'di','interface','The Interfaces','sys_set',''),(44,'2010-06-29 17:21:13',1,'di','interface','The Interfaces','sys_unset',''),(45,'2010-06-29 17:21:13',1,'di','user','Administrators','sys_user_in_group',''),(46,'2010-06-29 17:21:13',1,'di','user','Administrators','sys_list',''),(47,'2010-06-29 17:21:13',1,'di','user','Administrators','sys_get',''),(48,'2010-06-29 17:21:13',1,'di','user','Administrators','sys_new',''),(49,'2010-06-29 17:21:13',1,'di','user','Administrators','sys_set',''),(50,'2010-06-29 17:21:13',1,'di','user','Administrators','sys_passwd',''),(51,'2010-06-29 17:21:13',1,'di','user','Administrators','sys_unset',''),(52,'2010-06-29 17:21:13',1,'di','fm_files','Файлы','pub_get',''),(53,'2010-06-29 17:21:13',1,'di','fm_files','Файлы','sys_list',''),(54,'2010-06-29 17:21:13',1,'di','fm_files','Файлы','sys_item',''),(55,'2010-06-29 17:21:13',1,'di','fm_files','Файлы','sys_set',''),(56,'2010-06-29 17:21:13',1,'di','fm_files','Файлы','sys_unset',''),(57,'2010-06-29 17:21:13',1,'ui','login','Менеджер входа в кабинет','sys_dependencies',''),(58,'2010-06-29 17:21:13',1,'ui','text','Текст','pub_content',''),(59,'2010-06-29 17:21:13',1,'ui','text','Текст','sys_main',''),(60,'2010-06-29 17:21:13',1,'ui','text','Текст','sys_dependencies',''),(61,'2010-06-29 17:21:13',1,'ui','security','Управление безопастность','sys_main',''),(62,'2010-06-29 17:21:13',1,'ui','security','Управление безопастность','sys_interfaces',''),(63,'2010-06-29 17:21:13',1,'ui','security','Управление безопастность','sys_dependencies',''),(158,'2010-07-27 21:01:36',1,'di','interface','The Interfaces','sys_pub_entry_points',''),(67,'2010-06-29 17:21:13',1,'ui','structure','Структура','sys_main',''),(68,'2010-06-29 17:21:13',1,'ui','structure','Структура','sys_templates',''),(69,'2010-06-29 17:21:13',1,'ui','structure','Структура','sys_dependencies',''),(70,'2010-06-29 17:21:13',1,'ui','news','Новости','pub_content',''),(71,'2010-06-29 17:21:13',1,'ui','news','Новости','sys_main',''),(72,'2010-06-29 17:21:13',1,'ui','news','Новости','sys_dependencies',''),(73,'2010-06-29 17:21:13',1,'ui','file_manager','File-manager','sys_main',''),(74,'2010-06-29 17:21:13',1,'ui','file_manager','File-manager','sys_browser',''),(75,'2010-06-29 17:21:13',1,'ui','file_manager','File-manager','sys_dependencies',''),(76,'2010-06-29 17:21:13',1,'ui','administrate','Administrate','sys_main',''),(77,'2010-06-29 17:21:13',1,'ui','administrate','Administrate','sys_js',''),(78,'2010-06-29 17:21:13',1,'ui','administrate','Administrate','sys_app_lang',''),(79,'2010-06-29 17:21:13',1,'ui','administrate','Administrate','sys_dependencies',''),(80,'2010-06-29 17:21:13',1,'ui','article','Статьи','pub_content',''),(81,'2010-06-29 17:21:13',1,'ui','article','Статьи','sys_main',''),(82,'2010-06-29 17:21:13',1,'ui','article','Статьи','sys_dependencies',''),(83,'2010-06-29 17:21:13',1,'ui','help','Управление пользователями','sys_main',''),(84,'2010-06-29 17:21:13',1,'ui','help','Управление пользователями','sys_dependencies',''),(85,'2010-06-29 17:21:13',1,'ui','group','Группы пользователей','sys_main',''),(86,'2010-06-29 17:21:13',1,'ui','group','Группы пользователей','sys_editForm',''),(87,'2010-06-29 17:21:13',1,'ui','group','Группы пользователей','sys_dependencies',''),(88,'2010-06-29 17:21:13',1,'ui','user','Управление пользователями','sys_main',''),(89,'2010-06-29 17:21:13',1,'ui','user','Управление пользователями','sys_editForm',''),(90,'2010-06-29 17:21:13',1,'ui','user','Управление пользователями','sys_list',''),(91,'2010-06-29 17:21:13',1,'ui','user','Управление пользователями','sys_dependencies',''),(92,'2010-07-21 19:17:35',1,'di','text','Текстовые страницы','sys_available',''),(93,'2010-07-21 19:17:35',1,'di','guide_style','Справочник: Стили','sys_combolist',''),(94,'2010-07-21 19:17:35',1,'di','guide_style','Справочник: Стили','sys_list',''),(95,'2010-07-21 19:17:35',1,'di','guide_style','Справочник: Стили','sys_get',''),(96,'2010-07-21 19:17:35',1,'di','guide_style','Справочник: Стили','sys_set',''),(97,'2010-07-21 19:17:35',1,'di','guide_style','Справочник: Стили','sys_unset',''),(98,'2010-07-21 19:17:35',1,'di','ui_view_point','Точки вывода UI','sys_list',''),(99,'2010-07-21 19:17:35',1,'di','ui_view_point','Точки вывода UI','sys_page_configuration',''),(100,'2010-07-21 19:17:35',1,'di','ui_view_point','Точки вывода UI','sys_get',''),(101,'2010-07-21 19:17:35',1,'di','ui_view_point','Точки вывода UI','sys_item',''),(102,'2010-07-21 19:17:35',1,'di','ui_view_point','Точки вывода UI','sys_set',''),(103,'2010-07-21 19:17:35',1,'di','ui_view_point','Точки вывода UI','sys_unset',''),(104,'2010-07-21 19:17:35',1,'di','guide_producer','Справочник: Производители','sys_combolist',''),(105,'2010-07-21 19:17:35',1,'di','guide_producer','Справочник: Производители','sys_list',''),(106,'2010-07-21 19:17:35',1,'di','guide_producer','Справочник: Производители','sys_get',''),(107,'2010-07-21 19:17:35',1,'di','guide_producer','Справочник: Производители','sys_set',''),(108,'2010-07-21 19:17:35',1,'di','guide_producer','Справочник: Производители','sys_unset',''),(109,'2010-07-21 19:17:35',1,'di','guide_collection','Справочник: Коллекции','sys_combolist',''),(110,'2010-07-21 19:17:35',1,'di','guide_collection','Справочник: Коллекции','sys_list',''),(111,'2010-07-21 19:17:35',1,'di','guide_collection','Справочник: Коллекции','sys_get',''),(112,'2010-07-21 19:17:35',1,'di','guide_collection','Справочник: Коллекции','sys_set',''),(113,'2010-07-21 19:17:35',1,'di','guide_collection','Справочник: Коллекции','sys_unset',''),(114,'2010-07-21 19:17:35',1,'di','catalogue_item','Каталог: товары','sys_list',''),(115,'2010-07-21 19:17:35',1,'di','catalogue_item','Каталог: товары','sys_get',''),(116,'2010-07-21 19:17:35',1,'di','catalogue_item','Каталог: товары','sys_set',''),(117,'2010-07-21 19:17:35',1,'di','catalogue_item','Каталог: товары','sys_unset',''),(118,'2010-07-21 19:17:35',1,'di','structure','Структура сайта','sys_get',''),(119,'2010-07-21 19:17:35',1,'di','interface','The Interfaces','sys_get_public',''),(120,'2010-07-21 19:17:35',1,'di','guide_group','Справочник: Группы','sys_combolist',''),(121,'2010-07-21 19:17:35',1,'di','guide_group','Справочник: Группы','sys_list',''),(122,'2010-07-21 19:17:35',1,'di','guide_group','Справочник: Группы','sys_get',''),(123,'2010-07-21 19:17:35',1,'di','guide_group','Справочник: Группы','sys_set',''),(124,'2010-07-21 19:17:35',1,'di','guide_group','Справочник: Группы','sys_unset',''),(125,'2010-07-21 19:17:35',1,'di','guide_type','Справочник: Типы','sys_combolist',''),(126,'2010-07-21 19:17:35',1,'di','guide_type','Справочник: Типы','sys_list',''),(127,'2010-07-21 19:17:35',1,'di','guide_type','Справочник: Типы','sys_get',''),(128,'2010-07-21 19:17:35',1,'di','guide_type','Справочник: Типы','sys_set',''),(129,'2010-07-21 19:17:35',1,'di','guide_type','Справочник: Типы','sys_unset',''),(130,'2010-07-21 19:17:35',1,'ui','text','Текст','sys_item_form',''),(131,'2010-07-21 19:17:35',1,'ui','text','Текст','sys_configure_form',''),(153,'2010-07-22 14:47:06',1,'ui','navigation','Навигация по сайту','pub_top_menu',''),(132,'2010-07-21 19:17:35',1,'ui','structure','Структура','sys_site_tree',''),(133,'2010-07-21 19:17:35',1,'ui','structure','Структура','sys_node_form',''),(134,'2010-07-21 19:17:35',1,'ui','structure','Структура','sys_page_view',''),(135,'2010-07-21 19:17:35',1,'ui','structure','Структура','sys_page_view_point',''),(136,'2010-07-21 19:17:35',1,'ui','structure','Структура','sys_page_view_point_form',''),(157,'2010-07-27 21:01:36',1,'di','ui_view_point','Точки вывода UI','sys_apply',''),(137,'2010-07-21 19:17:35',1,'ui','catalogue','Каталог','pub_content',''),(138,'2010-07-21 19:17:35',1,'ui','catalogue','Каталог','sys_main',''),(139,'2010-07-21 19:17:35',1,'ui','catalogue','Каталог','sys_item_form',''),(140,'2010-07-21 19:17:35',1,'ui','catalogue','Каталог','sys_configure_form',''),(141,'2010-07-21 19:17:35',1,'ui','catalogue','Каталог','sys_dependencies',''),(142,'2010-07-21 19:17:35',1,'ui','guide','Справочники','sys_producer',''),(143,'2010-07-21 19:17:35',1,'ui','guide','Справочники','sys_producer_form',''),(144,'2010-07-21 19:17:35',1,'ui','guide','Справочники','sys_collection',''),(145,'2010-07-21 19:17:35',1,'ui','guide','Справочники','sys_collection_form',''),(146,'2010-07-21 19:17:35',1,'ui','guide','Справочники','sys_group',''),(147,'2010-07-21 19:17:35',1,'ui','guide','Справочники','sys_group_form',''),(148,'2010-07-21 19:17:35',1,'ui','guide','Справочники','sys_style',''),(149,'2010-07-21 19:17:35',1,'ui','guide','Справочники','sys_style_form',''),(150,'2010-07-21 19:17:35',1,'ui','guide','Справочники','sys_type',''),(151,'2010-07-21 19:17:35',1,'ui','guide','Справочники','sys_type_form',''),(152,'2010-07-21 19:17:35',1,'ui','guide','Справочники','sys_dependencies',''),(154,'2010-07-22 14:47:06',1,'ui','navigation','Навигация по сайту','pub_sub_menu',''),(155,'2010-07-22 14:47:06',1,'ui','navigation','Навигация по сайту','pub_trunc_menu',''),(156,'2010-07-22 14:47:06',1,'ui','navigation','Навигация по сайту','sys_dependencies',''),(159,'2010-07-27 21:21:52',1,'ui','guestbook','Гостевая','pub_content',''),(160,'2010-07-27 21:21:52',1,'ui','guestbook','Гостевая','pub_double',''),(161,'2010-07-27 21:21:52',1,'ui','guestbook','Гостевая','sys_dependencies','');
/*!40000 ALTER TABLE `interface` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `interface_group`
--

LOCK TABLES `interface_group` WRITE;
/*!40000 ALTER TABLE `interface_group` DISABLE KEYS */;
INSERT INTO `interface_group` VALUES (1,14),(2,14),(3,14),(4,14),(5,14),(6,13),(6,14),(7,13),(7,14),(8,13),(8,14),(9,13),(9,14),(10,14),(11,14),(12,14),(13,14),(14,14),(15,14),(16,14),(17,14),(18,14),(19,14),(20,14),(21,14),(22,13),(22,14),(23,13),(23,14),(24,13),(24,14),(26,13),(26,14),(27,13),(27,14),(28,14),(29,14),(30,14),(31,14),(32,14),(33,14),(34,14),(35,14),(36,14),(37,14),(38,14),(39,14),(40,14),(41,14),(42,14),(43,14),(44,14),(45,14),(46,14),(47,14),(48,14),(49,14),(50,14),(51,14),(52,14),(53,14),(54,14),(55,14),(56,14),(57,13),(57,14),(58,14),(59,14),(60,14),(61,14),(62,14),(63,14),(67,13),(67,14),(68,13),(68,14),(69,13),(69,14),(70,14),(71,14),(72,14),(73,14),(74,14),(75,14),(76,13),(76,14),(77,13),(77,14),(78,13),(78,14),(79,13),(79,14),(80,14),(81,14),(82,14),(83,14),(84,14),(85,14),(86,14),(87,14),(88,14),(89,14),(90,14),(91,14);
/*!40000 ALTER TABLE `interface_group` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `news`
--

LOCK TABLES `news` WRITE;
/*!40000 ALTER TABLE `news` DISABLE KEYS */;
/*!40000 ALTER TABLE `news` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `structure`
--

LOCK TABLES `structure` WRITE;
/*!40000 ALTER TABLE `structure` DISABLE KEYS */;
INSERT INTO `structure` VALUES (1,0,'Главная страница','home','/home/','','text','','default.html',0,'',1,18,1,'','',''),(4,0,'Новинки','incoming','/incoming/','','text','','default.html',0,'',2,3,2,'','',''),(5,0,'Новости','news','/news/','','news','','default.html',0,'',4,5,2,'','',''),(6,0,'Каталог','products','/products/','','text','','default.html',0,'',6,11,2,'','',''),(7,0,'CD','cd','/products/cd/','','catalogue','{\"type\":\"cd\"}','default.html',0,'',7,8,3,'','',''),(8,0,'DVD','dvd','/products/dvd/','','catalogue','{\"type\":\"dvd\"}','default.html',0,'',9,10,3,'','',''),(9,0,'Гостевая','guestbook','/guestbook/','','text','','default.html',0,'',12,13,2,'','',''),(10,0,'Информация','information','/information/','','text','','default.html',0,'',14,15,2,'','',''),(11,0,'Контакты','contacts','/contacts/','','text','','default.html',0,'',16,17,2,'','','');
/*!40000 ALTER TABLE `structure` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `structure_content`
--

LOCK TABLES `structure_content` WRITE;
/*!40000 ALTER TABLE `structure_content` DISABLE KEYS */;
INSERT INTO `structure_content` VALUES (1,1,'text'),(7,4,'catalogue_item'),(8,5,'catalogue_item');
/*!40000 ALTER TABLE `structure_content` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sys_user`
--

DROP TABLE IF EXISTS `sys_user`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `sys_user` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
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
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='System user';
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `sys_user`
--

LOCK TABLES `sys_user` WRITE;
/*!40000 ALTER TABLE `sys_user` DISABLE KEYS */;
INSERT INTO `sys_user` VALUES (1,'admin','*4ACFE3202A5FF5CF467898FC58AAB1D615029441','Administrator','admin@local.host','ru_RU','9c9a566eff18c0bd55b20298573681da','2010-07-28 11:16:35','93.100.77.50'),(2,'devel','*8F23662C357D1E7A6214097613335C88FE8BC390','Developer','devel@mail.ru','ru_RU','5fe305c99caec6863fc6bdf1ed07e73f','2010-06-29 19:06:57','92.101.202.137'),(4,'user_1','*3698E332F65FD723BCE0B7C869554991B0DA418B','The User #1','user_1@mail.ru','ru_RU','','0000-00-00 00:00:00','');
/*!40000 ALTER TABLE `sys_user` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `text`
--

LOCK TABLES `text` WRITE;
/*!40000 ALTER TABLE `text` DISABLE KEYS */;
INSERT INTO `text` VALUES (1,'view point 4','<p>\n	view point 4</p>\n',0),(2,'Блок текста еще один','<p>\n	Этот блок вывешен в крайнюю правую позицию на странице.</p>\n',0),(3,'Заголовок текстового контента  выводим жирным вот прямо тут','<p>\n	Блок текста на странице. Абзац номер один настроенный из админки.(c) DJ PapaSite</p>\n<p>\n	Тут переход строки. <span style=\"color: rgb(255, 0, 0);\">Тут цветной текст</span>.</p>\n',0),(4,'tets3 2','<p>\n	То что ниже это уже другой тектсовой контент вставленны в тот де view point. Собсн, все должно выглядеть так же как будто все что выше и то что ниже есть один и тот же контент, ан нет.</p>\n<p>\n	копипаст остаётся совокупностью копирования с последующей вставкой, и, лишь когда нет ссылки на первоисточник (будь то пресс-релиз), вот тогда и придаётcz негативный смысл копипасту!</p>\n<p>\n	копипаст остаётся совокупностью копирования с последующей вставкой, и, лишь когда нет ссылки на первоисточник (будь то пресс-релиз), вот тогда и придаётcz негативный смысл копипасту!</p>\n<p>\n	копипаст остаётся совокупностью копирования с последующей вставкой, и, лишь когда нет ссылки на первоисточник (будь то пресс-релиз), вот тогда и придаётcz негативный смысл копипасту!</p>\n<p>\n	копипаст остаётся совокупностью копирования с последующей вставкой, и, лишь когда нет ссылки на первоисточник (будь то пресс-релиз), вот тогда и придаётcz негативный смысл копипасту!копипаст остаётся совокупностью копирования с последующей вставкой, и, лишь когда нет ссылки на первоисточник (будь то пресс-релиз), вот тогда и придаётcz негативный смысл копипасту!копипаст остаётся совокупностью копирования с последующей вставкой, и, лишь когда нет ссылки на первоисточник (будь то пресс-релиз), вот тогда и придаётcz негативный смысл копипасту!</p>\n',1);
/*!40000 ALTER TABLE `text` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ui_view_point`
--

DROP TABLE IF EXISTS `ui_view_point`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `ui_view_point` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `page_id` smallint(5) unsigned NOT NULL,
  `view_point` tinyint(3) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `ui_name` varchar(255) NOT NULL,
  `ui_call` varchar(255) NOT NULL,
  `ui_configure` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `page_id` (`page_id`)
) ENGINE=MyISAM AUTO_INCREMENT=44 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `ui_view_point`
--

LOCK TABLES `ui_view_point` WRITE;
/*!40000 ALTER TABLE `ui_view_point` DISABLE KEYS */;
INSERT INTO `ui_view_point` VALUES (16,1,2,'левая колонка','navigation','sub_menu',''),(11,1,4,'правая колонка','text','','{\"_sid\":\"2\"}'),(14,1,3,'центр','text','','{\"_sid\": \"3\"}'),(15,1,3,'центр 2','text','','{\"_sid\": \"4\"}'),(18,1,1,'топ меню','navigation','top_menu',''),(19,1,33,'Меню типа градусник в центральной позиции','navigation','trunc_menu',''),(20,4,1,'топ меню','navigation','top_menu',''),(21,5,1,'Топ меню','navigation','top_menu',''),(22,6,1,'Топ меню','navigation','top_menu',''),(23,7,1,'Топ меню','navigation','top_menu',''),(24,8,1,'Топ меню','navigation','top_menu',''),(25,4,33,'Градусник','navigation','trunc_menu',''),(26,5,33,'Градусник','navigation','trunc_menu',''),(27,6,33,'Градусник','navigation','trunc_menu',''),(28,7,33,'Градусник','navigation','trunc_menu',''),(29,8,33,'Градусник','navigation','trunc_menu',''),(30,7,2,'левая навигация','navigation','sub_menu',''),(31,8,2,'левая навигация','navigation','sub_menu',''),(32,6,2,'левая навигация','navigation','sub_menu',''),(33,9,1,'Топ меню','navigation','top_menu',''),(34,9,33,'Градусник','navigation','trunc_menu',''),(35,9,2,'Левое меню','navigation','sub_menu',''),(36,9,3,'Центральный контент','guestbook','content',''),(37,10,1,'Топ меню','navigation','top_menu',''),(38,10,33,'Градусик','navigation','trunc_menu',''),(39,10,2,'мелвое меню','navigation','sub_menu',''),(40,11,1,'Топ меню','navigation','top_menu',''),(41,11,33,'Градусник','navigation','trunc_menu',''),(42,11,2,'левое меню','navigation','sub_menu',''),(43,5,2,'Левое меню','navigation','sub_menu','');
/*!40000 ALTER TABLE `ui_view_point` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2010-07-28  7:55:10

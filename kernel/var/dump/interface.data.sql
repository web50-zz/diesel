-- MySQL dump 10.11
--
-- Host: localhost    Database: site4
-- ------------------------------------------------------
-- Server version	5.0.51a-24+lenny4

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
-- Dumping data for table `interface`
--

LOCK TABLES `interface` WRITE;
/*!40000 ALTER TABLE `interface` DISABLE KEYS */;
INSERT INTO `interface` VALUES (1,'2011-06-20 22:26:57',1,'di','entry_point_group','Link between Interfaces and Groups'),(2,'2011-06-20 22:26:57',1,'di','fm_folders','Папки с файлами'),(3,'2011-06-20 22:26:57',1,'di','group_user','Link between users and groups'),(4,'2011-06-20 22:26:57',1,'di','registry','Registry'),(5,'2011-06-20 22:26:57',1,'di','help','Страницы помощи'),(6,'2011-06-20 22:26:57',1,'di','system_menu','Системное меню'),(7,'2011-06-20 22:26:57',1,'di','entry_point','Точки вызова'),(8,'2011-06-20 22:26:57',1,'di','interface_group','Link between Interfaces and Groups'),(9,'2011-06-20 22:26:57',1,'di','group','The user`s groups'),(10,'2011-06-20 22:26:57',1,'di','interface','The Interfaces'),(11,'2011-06-20 22:26:57',1,'di','cache','Кэширование'),(12,'2011-06-20 22:26:57',1,'di','util_db','Утиль ДБ'),(13,'2011-06-20 22:26:57',1,'di','user','Пользователи'),(14,'2011-06-20 22:26:57',1,'di','fm_files','Файлы'),(15,'2011-06-20 22:26:57',1,'ui','login','Менеджер входа в кабинет'),(16,'2011-06-20 22:26:57',1,'ui','security','Управление безопастность'),(17,'2011-06-20 22:26:57',1,'ui','file_manager','File-manager'),(18,'2011-06-20 22:26:57',1,'ui','administrate','Administrate'),(19,'2011-06-20 22:26:57',1,'ui','help','Страницы помощи'),(20,'2011-06-20 22:26:57',1,'ui','util_db','Util DB'),(21,'2011-06-20 22:26:57',1,'ui','group','Группы пользователей'),(22,'2011-06-20 22:26:57',1,'ui','registry','Реестр настроек'),(23,'2011-06-20 22:26:57',1,'ui','system_menu','Системное меню'),(24,'2011-06-20 22:26:57',1,'ui','user','Управление пользователями');
/*!40000 ALTER TABLE `interface` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2011-06-20 18:44:34

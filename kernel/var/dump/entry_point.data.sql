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
-- Dumping data for table `entry_point`
--

LOCK TABLES `entry_point` WRITE;
/*!40000 ALTER TABLE `entry_point` DISABLE KEYS */;
INSERT INTO `entry_point` VALUES (1,'2011-06-20 22:26:57',1,1,'sys_add_entry_points_to_group','add_entry_points_to_group'),(2,'2011-06-20 22:26:57',1,1,'sys_remove_entry_points_from_group','remove_entry_points_from_group'),(3,'2011-06-20 22:26:57',1,2,'sys_slice','slice'),(4,'2011-06-20 22:26:57',1,2,'sys_item','item'),(5,'2011-06-20 22:26:57',1,2,'sys_set','set'),(6,'2011-06-20 22:26:57',1,2,'sys_move','move'),(7,'2011-06-20 22:26:57',1,2,'sys_unset','unset'),(8,'2011-06-20 22:26:57',1,3,'sys_add_users_to_group','add_users_to_group'),(9,'2011-06-20 22:26:57',1,3,'sys_remove_users_from_group','remove_users_from_group'),(10,'2011-06-20 22:26:57',1,4,'sys_list','list'),(11,'2011-06-20 22:26:57',1,4,'sys_get','get'),(12,'2011-06-20 22:26:57',1,4,'sys_set','set'),(13,'2011-06-20 22:26:57',1,4,'sys_mset','mset'),(14,'2011-06-20 22:26:57',1,4,'sys_unset','unset'),(15,'2011-06-20 22:26:57',1,5,'sys_get','get'),(16,'2011-06-20 22:26:57',1,5,'sys_list','list'),(17,'2011-06-20 22:26:57',1,5,'sys_item','item'),(18,'2011-06-20 22:26:57',1,5,'sys_set','set'),(19,'2011-06-20 22:26:57',1,5,'sys_unset','unset'),(20,'2011-06-20 22:26:57',1,6,'sys_slice','slice'),(21,'2011-06-20 22:26:57',1,6,'sys_get','get'),(22,'2011-06-20 22:26:57',1,6,'sys_set','set'),(23,'2011-06-20 22:26:57',1,6,'sys_move','move'),(24,'2011-06-20 22:26:57',1,6,'sys_unset','unset'),(25,'2011-06-20 22:26:57',1,7,'sys_public','public'),(26,'2011-06-20 22:26:57',1,7,'sys_in_group','in_group'),(27,'2011-06-20 22:26:57',1,7,'sys_list','list'),(28,'2011-06-20 22:26:57',1,7,'sys_get','get'),(29,'2011-06-20 22:26:57',1,7,'sys_item','item'),(30,'2011-06-20 22:26:57',1,7,'sys_set','set'),(31,'2011-06-20 22:26:57',1,7,'sys_unset','unset'),(32,'2011-06-20 22:26:57',1,8,'sys_add_interfaces_to_group','add_interfaces_to_group'),(33,'2011-06-20 22:26:57',1,8,'sys_remove_interfaces_from_group','remove_interfaces_from_group'),(34,'2011-06-20 22:26:57',1,9,'sys_list','list'),(35,'2011-06-20 22:26:57',1,9,'sys_get','get'),(36,'2011-06-20 22:26:57',1,9,'sys_set','set'),(37,'2011-06-20 22:26:57',1,9,'sys_mset','mset'),(38,'2011-06-20 22:26:57',1,9,'sys_unset','unset'),(39,'2011-06-20 22:26:57',1,10,'sys_public','public'),(40,'2011-06-20 22:26:57',1,10,'sys_sync','sync'),(41,'2011-06-20 22:26:57',1,10,'sys_list','list'),(42,'2011-06-20 22:26:57',1,10,'sys_get','get'),(43,'2011-06-20 22:26:57',1,10,'sys_set','set'),(44,'2011-06-20 22:26:57',1,10,'sys_unset','unset'),(45,'2011-06-20 22:26:57',1,12,'sys_instances_list','instances_list'),(46,'2011-06-20 22:26:57',1,12,'sys_dop_list','dop_list'),(47,'2011-06-20 22:26:57',1,12,'sys_type_list','type_list'),(48,'2011-06-20 22:26:57',1,12,'sys_operations_list','operations_list'),(49,'2011-06-20 22:26:57',1,12,'sys_set','set'),(50,'2011-06-20 22:26:57',1,13,'sys_user_in_group','user_in_group'),(51,'2011-06-20 22:26:57',1,13,'sys_list','list'),(52,'2011-06-20 22:26:57',1,13,'sys_get','get'),(53,'2011-06-20 22:26:57',1,13,'sys_new','new'),(54,'2011-06-20 22:26:57',1,13,'sys_set','set'),(55,'2011-06-20 22:26:57',1,13,'sys_mset','mset'),(56,'2011-06-20 22:26:57',1,13,'sys_passwd','passwd'),(57,'2011-06-20 22:26:57',1,13,'sys_unset','unset'),(58,'2011-06-20 22:26:57',1,14,'pub_get','get'),(59,'2011-06-20 22:26:57',1,14,'sys_list','list'),(60,'2011-06-20 22:26:57',1,14,'sys_item','item'),(61,'2011-06-20 22:26:57',1,14,'sys_set','set'),(62,'2011-06-20 22:26:57',1,14,'sys_unset','unset'),(63,'2011-06-20 22:26:57',1,15,'sys_dependencies','dependencies'),(64,'2011-06-20 22:26:57',1,16,'sys_main','main'),(65,'2011-06-20 22:26:57',1,16,'sys_interfaces','interfaces'),(66,'2011-06-20 22:26:57',1,16,'sys_dependencies','dependencies'),(67,'2011-06-20 22:26:57',1,17,'sys_main','main'),(68,'2011-06-20 22:26:57',1,17,'sys_browser','browser'),(69,'2011-06-20 22:26:57',1,17,'sys_dependencies','dependencies'),(70,'2011-06-20 22:26:57',1,18,'sys_workspace','workspace'),(71,'2011-06-20 22:26:57',1,18,'sys_main','main'),(72,'2011-06-20 22:26:57',1,18,'sys_menu','menu'),(73,'2011-06-20 22:26:57',1,18,'sys_home','home'),(74,'2011-06-20 22:26:57',1,18,'sys_app_lang','app_lang'),(75,'2011-06-20 22:26:57',1,18,'sys_dependencies','dependencies'),(76,'2011-06-20 22:26:57',1,19,'sys_main','main'),(77,'2011-06-20 22:26:57',1,19,'sys_dependencies','dependencies'),(78,'2011-06-20 22:26:57',1,20,'sys_main','main'),(79,'2011-06-20 22:26:57',1,20,'sys_dump_form','dump_form'),(80,'2011-06-20 22:26:57',1,20,'sys_dependencies','dependencies'),(81,'2011-06-20 22:26:57',1,21,'sys_main','main'),(82,'2011-06-20 22:26:57',1,21,'sys_grid','grid'),(83,'2011-06-20 22:26:57',1,21,'sys_item_form','item_form'),(84,'2011-06-20 22:26:57',1,21,'sys_dependencies','dependencies'),(85,'2011-06-20 22:26:57',1,22,'sys_main','main'),(86,'2011-06-20 22:26:57',1,22,'sys_grid','grid'),(87,'2011-06-20 22:26:57',1,22,'sys_item_form','item_form'),(88,'2011-06-20 22:26:57',1,22,'sys_type','type'),(89,'2011-06-20 22:26:57',1,22,'sys_dependencies','dependencies'),(90,'2011-06-20 22:26:58',1,23,'sys_main','main'),(91,'2011-06-20 22:26:58',1,23,'sys_tree','tree'),(92,'2011-06-20 22:26:58',1,23,'sys_item_form','item_form'),(93,'2011-06-20 22:26:58',1,23,'sys_dependencies','dependencies'),(94,'2011-06-20 22:26:58',1,24,'sys_main','main'),(95,'2011-06-20 22:26:58',1,24,'sys_grid','grid'),(96,'2011-06-20 22:26:58',1,24,'sys_item_form','item_form'),(97,'2011-06-20 22:26:58',1,24,'sys_languages','languages'),(98,'2011-06-20 22:26:58',1,24,'sys_list','list'),(99,'2011-06-20 22:26:58',1,24,'sys_user_list','user_list'),(100,'2011-06-20 22:26:58',1,24,'sys_dependencies','dependencies');
/*!40000 ALTER TABLE `entry_point` ENABLE KEYS */;
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

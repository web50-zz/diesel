-- MySQL dump 10.13  Distrib 5.1.49, for debian-linux-gnu (i486)
--
-- Host: localhost    Database: jswg_web50_ru
-- ------------------------------------------------------
-- Server version	5.1.49-3

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
INSERT INTO `interface` VALUES (1,'2011-06-20 22:26:57',1,'di','entry_point_group','Link between Interfaces and Groups'),(2,'2011-06-20 22:26:57',1,'di','fm_folders','Папки с файлами'),(3,'2011-06-20 22:26:57',1,'di','group_user','Link between users and groups'),(4,'2011-06-20 22:26:57',1,'di','registry','Registry'),(5,'2011-06-20 22:26:57',1,'di','help','Страницы помощи'),(6,'2011-06-20 22:26:57',1,'di','system_menu','Системное меню'),(7,'2011-06-20 22:26:57',1,'di','entry_point','Точки вызова'),(8,'2011-06-20 22:26:57',1,'di','interface_group','Link between Interfaces and Groups'),(9,'2011-06-20 22:26:57',1,'di','group','The user`s groups'),(10,'2011-06-20 22:26:57',1,'di','interface','The Interfaces'),(11,'2011-06-20 22:26:57',1,'di','cache','Кэширование'),(12,'2011-06-20 22:26:57',1,'di','util_db','Утиль ДБ'),(13,'2011-06-20 22:26:57',1,'di','user','Пользователи'),(14,'2011-06-20 22:26:57',1,'di','fm_files','Файлы'),(15,'2011-06-20 22:26:57',1,'ui','login','Менеджер входа в кабинет'),(16,'2011-06-20 22:26:57',1,'ui','security','Управление безопастность'),(17,'2011-06-20 22:26:57',1,'ui','file_manager','File-manager'),(18,'2011-06-20 22:26:57',1,'ui','administrate','Administrate'),(19,'2011-06-20 22:26:57',1,'ui','help','Страницы помощи'),(20,'2011-06-20 22:26:57',1,'ui','util_db','Util DB'),(21,'2011-06-20 22:26:57',1,'ui','group','Группы пользователей'),(22,'2011-06-20 22:26:57',1,'ui','registry','Реестр настроек'),(23,'2011-06-20 22:26:57',1,'ui','system_menu','Системное меню'),(24,'2011-06-20 22:26:57',1,'ui','user','Управление пользователями'),(25,'2011-11-11 19:39:59',1,'di','text','Текстовые страницы'),(26,'2011-11-11 19:39:59',1,'di','subscribe_user','Link between subscribe users and subscribe groups'),(27,'2011-11-11 19:39:59',1,'di','subscribe_accounts','Подписчики'),(28,'2011-11-11 19:39:59',1,'di','news','Лента новостей'),(29,'2011-11-11 19:39:59',1,'di','site_map','Site map'),(30,'2011-11-11 19:39:59',1,'di','ui_view_point','Точки вывода UI'),(31,'2011-11-11 19:39:59',1,'di','contacts','Contacts'),(32,'2011-11-11 19:39:59',1,'di','faq_parts','FAQ parts'),(33,'2011-11-11 19:39:59',1,'di','subscribe_req','The subscribe unsubscribe req factory'),(34,'2011-11-11 19:39:59',1,'di','pswremind_req','The lost password recovery requests storage'),(35,'2011-11-11 19:39:59',1,'di','structure','Структура сайта'),(36,'2011-11-11 19:39:59',1,'di','faq','FAQ'),(37,'2011-11-11 19:39:59',1,'di','article','Статьи'),(38,'2011-11-11 19:39:59',1,'di','subscribe_messages','Subscribe messages'),(39,'2011-11-11 19:40:00',1,'di','subscribe','The subscribe user`s groups'),(40,'2011-11-11 19:40:00',1,'di','structure_content','Связь страниц сайта и контента'),(41,'2011-11-11 19:40:00',1,'di','structure_presets','Site Structure: пресеты вьюпоинтов'),(42,'2011-11-11 19:40:00',1,'di','guestbook','Гостевая'),(43,'2011-11-11 19:40:00',1,'ui','ext_spl_form','Библитека Экст SPL Form UX'),(44,'2011-11-11 19:40:00',1,'ui','ext_core','Библитека Экст коре'),(45,'2011-11-11 19:40:00',1,'ui','profile','Профиль'),(46,'2011-11-11 19:40:00',1,'ui','registration','Форма регистрации'),(47,'2011-11-11 19:40:00',1,'ui','ext_alert_box','Библитека Экст коре alert Box'),(48,'2011-11-11 19:40:00',1,'ui','text','Текст'),(49,'2011-11-11 19:40:00',1,'ui','subscribe','Рассылка'),(50,'2011-11-11 19:40:00',1,'ui','contacts','Contacts'),(51,'2011-11-11 19:40:00',1,'ui','jquery_1_6_4','JS jquery v 1.6.4  12 sept 2011'),(52,'2011-11-11 19:40:00',1,'ui','structure','Структура'),(53,'2011-11-11 19:40:00',1,'ui','news','Новости'),(54,'2011-11-11 19:40:00',1,'ui','pager','Пейджер'),(55,'2011-11-11 19:40:00',1,'ui','ext_frontloader','Библитека Экст фронтлоадер'),(56,'2011-11-11 19:40:00',1,'ui','article','Статьи'),(57,'2011-11-11 19:40:00',1,'ui','faq','FAQ'),(58,'2011-11-11 19:40:00',1,'ui','site_map','Карта сайта'),(59,'2011-11-11 19:40:00',1,'ui','action_page','Action page'),(60,'2011-11-11 19:40:00',1,'ui','structure_presets','Структура пресеты'),(61,'2011-11-11 19:40:00',1,'ui','pub_auth','Публичная авторизация'),(62,'2011-11-11 19:40:00',1,'ui','guestbook','Гостевая'),(63,'2011-11-11 19:40:00',1,'ui','navigation','Навигация по сайту'),(64,'2011-11-11 19:40:00',1,'ui','carousel_30092011','JS Widget carousel 30092011'),(65,'2011-11-11 19:40:00',1,'ui','hs_01112011','JS Widget hs 01112011'),(66,'2011-11-11 19:40:00',1,'ui','menu_1','JS Widge Drop down 1 ui_menu_1'),(67,'2011-11-11 19:40:00',1,'ui','jdrop_02112011','JS widget jdrop_02112011 jDropDown'),(68,'2011-11-11 19:40:00',1,'ui','bx_01112011','JS widget bx_01112011 bxSlider'),(69,'2011-11-14 01:28:53',1,'ui','niced_14112011','JS widget niced_14112011 NIC Editor'),(70,'2011-11-14 21:42:13',1,'ui','drop_15112011','JS widget drop_15112011 Drag Drop uploader'),(71,'2011-11-15 15:57:48',1,'ui','multup_15112011','JS widget multup_15112011 multi file uploader'),(72,'2011-11-15 19:02:29',1,'ui','jqueryui_1_8_16','JS jQuery UI 1.8.16 Lib'),(73,'2011-11-24 22:42:31',1,'ui','turbo_24112011','JS widget turbo_24112011 Type 9  Editor'),(74,'2011-11-27 18:52:20',1,'ui','expta_27112011','JS widget expta_27112011 expandable text area'),(75,'2011-11-27 19:27:40',1,'ui','cusel_27112011','JS widget cusel_27112011 eaplace select to more stylish'),(76,'2011-11-27 20:09:17',1,'ui','edsel_27112011','JS widget edsel_27112011  editable select'),(77,'2011-11-27 20:40:18',1,'ui','imgsel_27112011','JS widget imgsel_27112011  image area select select');
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

-- Dump completed on 2011-12-26 18:19:45

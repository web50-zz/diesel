LOCK TABLES `interface` WRITE;
/*!40000 ALTER TABLE `interface` DISABLE KEYS */;
INSERT INTO `interface` VALUES (1,'2010-09-02 22:13:28',1,'di','guide_price','Справочник: Цены'),(2,'2010-09-02 22:13:28',1,'di','entry_point_group','Link between Interfaces and Groups'),(3,'2010-09-02 22:13:28',1,'di','order_item','Заказы: товары'),(4,'2010-09-02 22:13:28',1,'di','text','Текстовые страницы'),(5,'2010-09-02 22:13:28',1,'di','subscribe_user','Link between subscribe users and subscribe groups'),(6,'2010-09-02 22:13:28',1,'di','subscribe_accounts','Подписчики'),(7,'2010-09-02 22:13:28',1,'di','news','Лента новостей'),(9,'2010-09-02 22:13:28',1,'di','fm_folders','Папки с файлами'),(10,'2010-09-02 22:13:28',1,'di','guide_style','Справочник: Стили'),(12,'2010-09-02 22:13:28',1,'di','ui_view_point','Точки вывода UI'),(13,'2010-09-02 22:13:28',1,'di','group_user','Link between users and groups'),(88,'2010-09-23 20:00:00',1,'ui','market_cat_nav','Меню по типам товаров в каталоге'),(15,'2010-09-02 22:13:28',1,'di','guide_producer','Справочник: Производители'),(16,'2010-09-02 22:13:28',1,'di','contacts','Contacts'),(17,'2010-09-02 22:13:28',1,'di','market_clients','Клиенты магазина'),(18,'2010-09-02 22:13:28',1,'di','guide_collection','Справочник: Коллекции'),(19,'2010-09-02 22:13:28',1,'di','faq_parts','FAQ parts'),(20,'2010-09-02 22:13:28',1,'di','market_recomendations','Маркое рекоmендовано DI'),(21,'2010-09-02 22:13:28',1,'di','cart','Заказы'),(22,'2010-09-02 22:13:28',1,'di','catalogue_item','Каталог: товары'),(23,'2010-09-02 22:13:28',1,'di','help','Контакты компании'),(24,'2010-09-02 22:13:28',1,'di','market_latest_long','Маркет новнки  расширенно DI'),(25,'2010-09-02 22:13:28',1,'di','entry_point','Точки вызова'),(26,'2010-09-02 22:13:28',1,'di','structure','Структура сайта'),(27,'2010-09-02 22:13:28',1,'di','interface_group','Link between Interfaces and Groups'),(28,'2010-09-02 22:13:28',1,'di','market_latest','Маркет новнки DI'),(29,'2010-09-02 22:13:28',1,'di','faq','FAQ'),(30,'2010-09-02 22:13:28',1,'di','group','The user`s groups'),(31,'2010-09-02 22:13:28',1,'di','article','Статьи'),(32,'2010-09-02 22:13:28',1,'di','interface','The Interfaces'),(33,'2010-09-02 22:13:28',1,'di','order','Заказы'),(34,'2010-09-02 22:13:28',1,'di','subscribe_messages','Subscribe messages'),(35,'2010-09-02 22:13:28',1,'di','subscribe','The subscribe user`s groups'),(36,'2010-09-02 22:13:28',1,'di','structure_content','Связь страниц сайта и контента'),(37,'2010-09-02 22:13:28',1,'di','user','Пользователи'),(38,'2010-09-02 22:13:28',1,'di','guide_group','Справочник: Группы'),(39,'2010-09-02 22:13:28',1,'di','catalogue_file','Файлы каталога'),(40,'2010-09-02 22:13:28',1,'di','fm_files','Файлы'),(87,'2010-09-22 18:42:32',1,'ui','market_basket','Корзина'),(42,'2010-09-02 22:13:28',1,'di','guestbook','Гостевая'),(43,'2010-09-02 22:13:28',1,'di','guide_type','Справочник: Типы'),(44,'2010-09-02 22:13:28',1,'di','catalogue_style','Link between Interfaces and Groups'),(45,'2010-09-02 22:13:28',1,'di','market_latest_long_list','Маркет новнки DI'),(46,'2010-09-02 22:13:28',1,'ui','order','Заказы'),(47,'2010-09-02 22:13:28',1,'ui','login','Менеджер входа в кабинет'),(48,'2010-09-02 22:13:28',1,'ui','market_top_sales','TOP продаж'),(49,'2010-09-02 22:13:28',1,'ui','registration','Форма регистрации'),(50,'2010-09-02 22:13:28',1,'ui','market_recomendations','Магазин рекомендует'),(51,'2010-09-02 22:13:28',1,'ui','text','Текст'),(52,'2010-09-02 22:13:28',1,'ui','subscribe','Рассылка'),(53,'2010-09-02 22:13:28',1,'ui','contacts','Contacts'),(54,'2010-09-02 22:13:28',1,'ui','security','Управление безопастность'),(55,'2010-09-02 22:13:28',1,'ui','structure','Структура'),(56,'2010-09-02 22:13:28',1,'ui','country_regions','Страны и регионы справочник'),(57,'2010-09-02 22:13:28',1,'ui','news','Новости'),(58,'2010-09-02 22:13:28',1,'ui','file_manager','File-manager'),(59,'2010-09-02 22:13:28',1,'ui','pager','Пейджер'),(60,'2010-09-02 22:13:28',1,'ui','administrate','Administrate'),(61,'2010-09-02 22:13:28',1,'ui','article','Статьи'),(62,'2010-09-02 22:13:28',1,'ui','help','Управление пользователями'),(63,'2010-09-02 22:13:28',1,'ui','market_clients','Клиенты'),(64,'2010-09-02 22:13:28',1,'ui','market_soon','Скоро в продаже'),(65,'2010-09-02 22:13:28',1,'ui','faq','FAQ'),(66,'2010-09-02 22:13:28',1,'ui','cart','Корзина заказа'),(67,'2010-09-02 22:13:28',1,'ui','group','Группы пользователей'),(68,'2010-09-02 22:13:28',1,'ui','catalogue','Каталог'),(69,'2010-09-02 22:13:28',1,'ui','market_latest','Новинки магазина'),(70,'2010-09-02 22:13:28',1,'ui','pub_auth','Публичная авторизация'),(71,'2010-09-02 22:13:28',1,'ui','guide','Справочники'),(72,'2010-09-02 22:13:28',1,'ui','guestbook','Гостевая'),(73,'2010-09-02 22:13:28',1,'ui','navigation','Навигация по сайту'),(74,'2010-09-02 22:13:28',1,'ui','market_latest_long','Новинки магазина расширенно'),(75,'2010-09-02 22:13:28',1,'ui','user','Управление пользователями'),(76,'2010-09-19 11:39:40',1,'di','guide_order_status','Справочник: Статусы заказов'),(77,'2010-09-19 11:39:40',1,'di','guide_post_zone','Справочник: Почтовые зоны'),(78,'2010-09-19 11:39:40',1,'di','subscribe_req','The subscribe unsubscribe req factory'),(79,'2010-09-19 11:39:40',1,'di','pswremind_req','The lost password recovery requests storage'),(80,'2010-09-19 11:39:40',1,'di','guide_currency','Справочник: Валюты'),(81,'2010-09-19 11:39:40',1,'di','guide_region','FAQ'),(82,'2010-09-19 11:39:40',1,'di','guide_country','Справочник: Страны'),(83,'2010-09-19 11:39:40',1,'di','cache','Кэширование'),(84,'2010-09-19 11:39:40',1,'di','guide_pay_type','Справочник: Типы платежей'),(85,'2010-09-19 11:39:40',1,'ui','profile','Профиль'),(86,'2010-09-19 11:39:40',1,'ui','action_page','Action page'),(89,'2010-11-22 21:40:37',1,'di','market_soon','Маркет скоро в продаже DI'),(90,'2010-11-22 21:40:37',1,'di','market_selected','Заказы'),(91,'2010-11-22 21:40:37',1,'di','market_viewed','Заказы'),(92,'2010-11-22 21:40:38',1,'ui','market_selected','Магазин избранное'),(93,'2010-11-22 21:40:38',1,'ui','market_viewed','Магазин просмотренное'),(94,'2011-03-05 18:54:55',1,'di','system_menu','Системное меню'),(95,'2011-03-05 18:54:55',1,'di','util_db','Утиль ДБ'),(96,'2011-03-05 18:54:55',1,'di','market_types','Структура товаров магазина');
/*!40000 ALTER TABLE `interface` ENABLE KEYS */;
UNLOCK TABLES;

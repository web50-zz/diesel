LOCK TABLES `fm_folders` WRITE;
/*!40000 ALTER TABLE `fm_folders` DISABLE KEYS */;
INSERT INTO `fm_folders` VALUES (1,'Home',1,6,1),(2,'test 1',2,3,2),(3,'test 2',4,5,2);
/*!40000 ALTER TABLE `fm_folders` ENABLE KEYS */;
UNLOCK TABLES;

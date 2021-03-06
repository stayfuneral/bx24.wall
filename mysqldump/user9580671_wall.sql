-- MySQL dump 10.14  Distrib 5.5.64-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: user9580671_wall
-- ------------------------------------------------------
-- Server version	5.5.64-MariaDB-cll-lve

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
-- Table structure for table `DIAMETERS`
--

DROP TABLE IF EXISTS `DIAMETERS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `DIAMETERS` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `start_diameter` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `DIAMETERS`
--

LOCK TABLES `DIAMETERS` WRITE;
/*!40000 ALTER TABLE `DIAMETERS` DISABLE KEYS */;
INSERT INTO `DIAMETERS` VALUES (1,32),(2,102),(3,132),(4,152),(5,182),(6,225),(7,250),(8,270),(9,320);
/*!40000 ALTER TABLE `DIAMETERS` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `MATERIAL_TYPES`
--

DROP TABLE IF EXISTS `MATERIAL_TYPES`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `MATERIAL_TYPES` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `material_type` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `MATERIAL_TYPES`
--

LOCK TABLES `MATERIAL_TYPES` WRITE;
/*!40000 ALTER TABLE `MATERIAL_TYPES` DISABLE KEYS */;
INSERT INTO `MATERIAL_TYPES` VALUES (1,'бетон'),(2,'кирпич');
/*!40000 ALTER TABLE `MATERIAL_TYPES` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PRICES`
--

DROP TABLE IF EXISTS `PRICES`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PRICES` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `material_type_id` int(11) NOT NULL,
  `diameter_id` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PRICES`
--

LOCK TABLES `PRICES` WRITE;
/*!40000 ALTER TABLE `PRICES` DISABLE KEYS */;
INSERT INTO `PRICES` VALUES (1,1,1,25),(2,1,2,30),(3,1,3,35),(4,1,4,40),(5,1,5,45),(6,1,6,50),(7,1,7,60),(8,1,8,80),(9,1,9,100),(10,2,1,15),(11,2,2,20),(12,2,3,25),(13,2,4,30),(14,2,5,35),(15,2,6,40),(16,2,7,45),(17,2,8,60),(18,2,9,80);
/*!40000 ALTER TABLE `PRICES` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

<<<<<<< HEAD
-- Dump completed on 2020-02-25 18:06:53
=======
-- Dump completed on 2020-02-25 18:12:30
>>>>>>> dev

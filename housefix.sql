-- MySQL dump 10.13  Distrib 8.0.46, for Win64 (x86_64)
--
-- Host: localhost    Database: housefix
-- ------------------------------------------------------
-- Server version	8.0.20

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin` (
  `admin_id` int NOT NULL AUTO_INCREMENT,
  `username_admin` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  PRIMARY KEY (`admin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES (1,'adminapip','admin123');
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `area`
--

DROP TABLE IF EXISTS `area`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `area` (
  `area_id` int NOT NULL AUTO_INCREMENT,
  `postcode` varchar(10) NOT NULL,
  `town` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  PRIMARY KEY (`area_id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `area`
--

LOCK TABLES `area` WRITE;
/*!40000 ALTER TABLE `area` DISABLE KEYS */;
INSERT INTO `area` VALUES (1,'34000','Taiping','Perak'),(2,'35000','Tapah','Perak'),(3,'30000','Ipoh','Perak'),(4,'33300','Gerik','Perak'),(5,'36000','Teluk Intan','Perak'),(6,'35500','Bidor','Perak'),(7,'33000','Kuala Kangsar','Perak'),(8,'31000','Batu Gajah','Perak'),(9,'31100','Sungai Siput','Perak'),(10,'31150','Ulu Kinta','Perak'),(11,'31200','Chemor','Perak'),(12,'31400','Tanjong Rambutan','Perak'),(13,'31500','Lahat','Perak'),(14,'31550','Pusing','Perak'),(15,'31600','Gopeng','Perak'),(16,'31750','Tronoh','Perak'),(17,'34100','Selama','Perak'),(18,'34200','Parit Buntar','Perak'),(19,'34300','Bagan Serai','Perak'),(20,'34350','Kuala Kurau','Perak'),(21,'34400','Simpang','Perak'),(22,'34500','Batu Kurau','Perak'),(23,'34600','Kamunting','Perak'),(24,'34700','Kuala Sepetang','Perak'),(25,'33100','Pengkalan Hulu','Perak'),(26,'33300','Gerik','Perak'),(27,'33400','Lenggong','Perak'),(28,'34300','Bagan Serai','Perak'),(32,'31850','Kampar','Perak');
/*!40000 ALTER TABLE `area` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `booking`
--

DROP TABLE IF EXISTS `booking`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `booking` (
  `booking_id` int NOT NULL AUTO_INCREMENT,
  `booking_date` date NOT NULL,
  `booking_time` time NOT NULL,
  `booking_status` varchar(50) DEFAULT 'Pending',
  `customer_id` int DEFAULT NULL,
  `technician_id` int DEFAULT NULL,
  PRIMARY KEY (`booking_id`),
  KEY `customer_id` (`customer_id`),
  KEY `technician_id` (`technician_id`),
  CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE CASCADE,
  CONSTRAINT `booking_ibfk_2` FOREIGN KEY (`technician_id`) REFERENCES `technician` (`technician_id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking`
--

LOCK TABLES `booking` WRITE;
/*!40000 ALTER TABLE `booking` DISABLE KEYS */;
INSERT INTO `booking` VALUES (18,'2026-07-23','18:15:00','Completed',18,9),(20,'2026-08-16','19:10:00','Pending',20,9),(22,'2026-06-21','13:11:00','Completed',22,9),(23,'2026-06-12','16:00:00','Completed',23,13),(24,'2026-06-23','16:00:00','Pending',24,10),(25,'2026-11-24','10:00:00','Completed',25,10),(26,'2026-09-13','17:00:00','Pending',26,10),(27,'2026-07-26','13:30:00','Pending',27,11),(28,'2026-06-14','09:00:00','Pending',28,15),(29,'2026-06-20','10:00:00','Completed',29,11),(30,'2026-06-27','12:00:00','Completed',30,11),(31,'2026-06-15','10:00:00','Pending',31,15),(32,'2026-06-16','11:00:00','Pending',32,15),(33,'2026-06-19','14:10:00','Pending',33,12),(34,'2026-06-30','17:10:00','Pending',34,12),(35,'2026-06-17','10:30:00','Pending',35,16),(36,'2026-11-30','13:00:00','Pending',36,12),(37,'2026-06-18','11:30:00','Pending',37,16),(39,'2026-06-19','12:30:00','Pending',39,16),(42,'2026-10-20','10:00:00','Pending',42,14),(43,'2026-08-23','11:10:00','Pending',43,14),(44,'2026-06-21','13:30:00','Pending',44,18),(45,'2026-09-15','10:00:00','Pending',45,14),(46,'2026-06-22','14:20:00','Pending',46,18),(47,'2026-06-24','16:30:00','Pending',47,19),(48,'2026-06-25','17:30:00','Pending',48,19),(49,'2026-06-25','12:20:00','Pending',49,19),(50,'2026-06-28','13:40:00','Completed',50,20),(51,'2026-06-29','14:40:00','Completed',51,20),(52,'2026-06-30','15:40:00','Completed',52,20),(53,'2026-06-13','16:00:00','Completed',53,13),(54,'2026-06-27','17:00:00','Pending',54,13),(55,'2026-06-30','07:00:00','Pending',55,13),(56,'2026-06-14','14:00:00','Completed',56,13),(57,'2026-06-15','16:20:00','Completed',57,13),(58,'2026-06-12','12:00:00','Completed',58,13),(59,'2026-06-17','08:20:00','Completed',59,13),(60,'2026-06-16','18:00:00','Completed',60,13),(61,'2026-06-27','10:04:00','Completed',61,13),(62,'2026-06-30','12:00:00','Completed',62,9),(63,'2026-07-23','17:00:00','Pending',63,9),(64,'2026-07-25','17:30:00','Pending',64,9),(65,'2026-07-23','09:30:00','Completed',65,20),(66,'2026-07-24','09:00:00','Pending',66,20),(67,'2026-07-29','10:00:00','Pending',67,20),(68,'2026-07-30','11:47:00','Completed',68,20),(69,'2026-07-30','10:58:00','Completed',69,20);
/*!40000 ALTER TABLE `booking` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `booking_detail`
--

DROP TABLE IF EXISTS `booking_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `booking_detail` (
  `booking_id` int NOT NULL,
  `services_id` int NOT NULL,
  PRIMARY KEY (`booking_id`,`services_id`),
  KEY `services_id` (`services_id`),
  CONSTRAINT `booking_detail_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE CASCADE,
  CONSTRAINT `booking_detail_ibfk_2` FOREIGN KEY (`services_id`) REFERENCES `services` (`services_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking_detail`
--

LOCK TABLES `booking_detail` WRITE;
/*!40000 ALTER TABLE `booking_detail` DISABLE KEYS */;
INSERT INTO `booking_detail` VALUES (18,1),(20,1),(22,1),(62,1),(63,1),(64,1),(65,1),(27,2),(29,2),(30,2),(28,3),(31,3),(32,3),(50,3),(51,3),(52,3),(66,3),(35,4),(37,4),(39,4),(23,5),(24,5),(25,5),(26,5),(33,5),(34,5),(36,5),(44,5),(46,5),(53,5),(54,5),(55,5),(56,5),(57,5),(58,5),(59,5),(60,5),(61,5),(67,5),(68,5),(69,5),(42,6),(43,6),(45,6),(47,6),(48,6),(49,6);
/*!40000 ALTER TABLE `booking_detail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customers` (
  `customer_id` int NOT NULL AUTO_INCREMENT,
  `customer_name` varchar(255) NOT NULL,
  `customer_address` text NOT NULL,
  `customer_phonenum` varchar(20) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  PRIMARY KEY (`customer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customers`
--

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
INSERT INTO `customers` VALUES (18,'Muhammad Arep Bin Romzi','No. 45, Jalan Lapangan Siber 12,Taman Lapangan Siber,31350 Ipoh,Perak.','0146523886','arep46@gmail.com'),(20,'Muhammad Alip Daniel Bin Fauzi','No. 12, Jalan Bercham Bestari 5,Taman Bercham Bestari,31400 Ipoh,Perak.','0167442361','Alip12@gmail.com'),(22,'Danish Akim Bin Alif','No. B-10-3, Kondominium IP Tower,Jalan Datuk Onn Jaafar,30000 Ipoh,Perak.','0142336120','Danish23@gmail.com'),(23,'Azim Hakimie','azim@gmail.com','011-2345678','azim@gmail.com'),(24,'Muhammad Qayyum Bin Ali','No. 18, Jalan Tupai,Taman Tupai Mas,34000 Taiping,Perak.','0197634561','qay521@gmail.com'),(25,'Amin Bin Ramzi','No. 55, Jalan Kota,Pusat Bandar Taiping,34000 Taiping,Perak.','0115418423','amin764@gmail.com'),(26,'Nur Fatimah Binti Nurzamani','Lot 112, Kampung Sendayan,Jalan Air Putih,34000 Taiping,Perak.','0135682108','fati243@gmail.com'),(27,'Siti Aminah Binti Ismail','Lot 88, Kampung Pajak Potong,Jalan Bendahara,33000 Kuala Kangsar,Perak.','016452345','aminah@gmail.com'),(28,'Ahmad Ali bin Mahmud','No. 24, Jalan Kangsar, Taman Melati, 33000 Kuala Kangsar, Perak','014-2350123','ahmadali@gmail.com'),(29,'Tan Wei Sheng','No. 88, Jalan Bukit Chandan 2,Taman Bukit Chandan,33000 Kuala Kangsar,Perak.','017-892 3456','weishengtan99@gmail.com'),(30,'Prema a/p Rajendran','Lot 405, Kampung Bukit Chandan,Jalan Istana,33000 Kuala Kangsar,Perak.','011-2345 6789','premarajendran@gmail.com'),(31,'Ahmad Abu bin Ali','No. 42, Jalan Chandan, Taman Chandan Putri, 33000 Kuala Kangsar, Perak','014-2220123','ahmadabu@gmail.com'),(32,'Zulkarnain bin Ahmad Ali','No. 15, Laluan Meranti, Taman Kuala Kangsar, 33000 Kuala Kangsar, Perak','016-2350123','zul@gmail.com'),(33,'Siti Nurhaliza binti Ahmad','No. B-12-5, Kondominium IP Tower,Jalan Datuk Onn Jaafar,30000 Ipoh,Perak.','019-345 6781','sitinurhalizaahmad@gmail.com'),(34,'Lim Chee Keong','No. 67, Tingkat Atas, Jalan Sultan Idris Shah,Pusat Bandaraya Ipoh,30000 Ipoh,Perak.','012-765 4321','limcheekeong88@gmail.com'),(35,'Hafiz bin Zulkifli','No. 18, Jalan Fair Park, Taman Fair Park, 31400 Ipoh, Perak','017-2450123','fiz@gmail.com'),(36,'Arvinth a/l Subramaniam','No. 14A, Jalan Raja Ekram,Pusat Bandaraya Ipoh,30000 Ipoh,Perak.','016-890 1234','arvinthsubra@gmail.com'),(37,'Muaz bin Ahmad Zulkarnain','No. 57, Jalan Raja Ekram, Kampung Jawa, 30300 Ipoh, Perak','019-2350123','muaz@gmail.com'),(38,'Ahmad Syakir bin Zainal','No. 24, Jalan Seri Rahma 3,Taman Seri Rahma,33000 Kuala Kangsar,Perak.','019-543 2109','syakirzainal92@gmail.com'),(39,'Wan Ali bin Wan Abu','No. 89, Lorong Taman Ipoh 1, Taman Ipoh, 31400 Ipoh, Perak','016-2440123','wanali@gmail.com'),(40,'Chong Mee Ling','Lot 112, Kampung Sayong Lembah,Jalan Persisiran Sayong,33000 Kuala Kangsar,Perak.','011-3456 7812','chongmeeling@gmail.com'),(41,'Thanaletchumi a/p Ganesan','No. 5, Jalan Kangsar Impian 1,Taman Kangsar Impian,33000 Kuala Kangsar,Perak.','014-987 6543','thanaletchumig@gmail.com'),(42,'Nurul Asyikin binti Ramli','No. 45, Jalan Tupai,Taman Tupai Mas,34000 Taiping,Perak.','013-567 8901','asyikinramli94@gmail.com'),(43,'Lee Kok Wai','No. 118, Jalan Kota,Pusat Bandar Taiping,34000 Taiping,Perak.','017-345 6789','leekokwai88@gmail.com'),(44,'Ahmad Muaz bin Ahmad Zulkarnain','No. 73, Jalan Bukit Chandan, Taman Chandan, 33000 Kuala Kangsar, Perak','017-2350123','muazAmd@gmail.com'),(45,'Saravanan a/l Murugan','Lot 204, Kampung Sendayan,Jalan Air Putih,34000 Taiping,Perak.','011-5678 1234','saravananmurugan@gmail.com'),(46,'Ali Hafiz bin Ahmad','No. 11, Jalan Sultan Iskandar Shah, Kampung Parit, 33000 Kuala Kangsar, Perak','014-2440123','ali@gmail.com'),(47,'Yu Chai Lee','No. 52, Jalan Assam Kumbang, Taman Assam Kumbang, 34000 Taiping, Perak','013-2350123','ycl@gmail.com'),(48,'Arumugam A/L Arumugame','No. 104, Lorong Tupai 3, Taman Tupai Mas, 34000 Taiping, Perak','015-2460123','amg@gmail.com'),(49,'Siti Zara binti Muhd Ali','No. 27, Jalan Pokok Assam, Taman Pokok Assam, 34000 Taiping, Perak','016-2670123','siti@gmail.com'),(50,'Ahmad Wan Ali bin Ahmad Kamaruzzaman','No. 14, Jalan Canning Estate, Taman Canning, 31400 Ipoh, Perak','012-2220345','kamar@gmail.com'),(51,'Hafiz Suip bin Ali','No. 65, Lorong Pasir Puteh 4, Taman Pasir Puteh, 31650 Ipoh, Perak','014-2678123','hafizsuip@gmail.com'),(52,'Mohammad Azrul bin Hadi','No. 112, Jalan Sultan Nazrin Shah, Desa Alam Seksyen 1, 31350 Ipoh, Perak','016-2352468','azr@gmail.com'),(53,'Ahmad Haikal','No 35, Jalan Salim, Kuala Kangsar','013-4459087','ahmadhaikal@gmail.com'),(54,'Khairul Anuar bin Mohd Zain','No. 33, Jalan Seri Rahma 5,Taman Seri Rahma,33000 Kuala Kangsar,Perak.','012-456 1234','khairulanuar.mz@gmail.com'),(55,'Wong Siew Ling','No. 18, Jalan Bukit Chandan 4,Taman Bukit Chandan,33000 Kuala Kangsar,Perak.','017-654 3210','wongsiewling88@gmail.com'),(56,'Kalaimani a/p Balakrishnan','Lot 56, Kampung Pajak Potong,Jalan Bendahara,33000 Kuala Kangsar,Perak.','011-3421 9876','kalaimanibala@gmail.com'),(57,'Mohd Hafiz bin Ibrahim','No. 7, Jalan Kangsar Impian 2,Taman Kangsar Impian,33000 Kuala Kangsar,Perak.','019-876 5432','hafizibrahim90@gmail.com'),(58,'Ng Kar Yee','No. 102, Tingkat Bawah, Jalan Sultan Idris,Pusat Perniagaan Kuala Kangsar,33000 Kuala Kangsar,Perak.','016-234 5678','ngkaryee95@gmail.com'),(59,'Yogeswaran a/l Muthu','Lot 142, Kampung Sayong Lembah,Jalan Persisiran Sayong,33000 Kuala Kangsar,Perak.','014-345 6789','yogesmuthu@gmail.com'),(60,'Nurul Hidayah binti Ismail','No. 29, Jalan Seri Rahma 2,Taman Seri Rahma,33000 Kuala Kangsar,Perak.','013-987 6541','nurulhidayah.ism@gmail.com'),(61,'Hasan Mutalib','No 67,Jalan Angsana Kuala Kangsar','013-5746890','hasan@gmail.com'),(62,'Iskandar Zul','Ipoh, Perak','017-5547411','iskandar@gmail.com'),(63,'Nama','Alamat','0123456789','email@gmail.com'),(64,'Nama2','Alamat2','0123456789','email@gmail.com'),(65,'Faris Farisi','Jalan Ipoh Parade','0175567987','farisi@gmail.com'),(66,'Firas Iskandar','Jalan Ipoh Selatan','013-6780923','iskandar@gmail.com'),(67,'Haqiem Rusli','Jalan Payung Teduh, Taman Teduh, Ipoh, Perak','017-6554224','haqiem@gmail.com'),(68,'Azam Saiful','Jalan Meru Selatan, Ipoh','017-87926266','azam@gmail.com'),(69,'Alia Afiqah','Jalan Ipoh Selatan, Taman Meru Raya, Ipoh','0135664322','afiq@gmail.com');
/*!40000 ALTER TABLE `customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reviews` (
  `review_id` int NOT NULL AUTO_INCREMENT,
  `rating_score` int NOT NULL,
  `review_comment` text,
  `booking_id` int DEFAULT NULL,
  `review_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`review_id`),
  KEY `booking_id` (`booking_id`),
  CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reviews`
--

LOCK TABLES `reviews` WRITE;
/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
INSERT INTO `reviews` VALUES (1,5,'The service was fast and trustworthy!',60,'2026-06-11 00:34:00'),(2,5,'Clean and neat job.',59,'2026-06-11 00:35:11'),(3,4,'Fast Job and Clean!',61,'2026-06-11 02:35:36'),(4,5,'Satisfied',65,'2026-07-22 07:34:37'),(5,5,'Excellent Services',68,'2026-07-27 19:50:25'),(6,5,'Clean Job!',69,'2026-07-27 21:02:25');
/*!40000 ALTER TABLE `reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `services`
--

DROP TABLE IF EXISTS `services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `services` (
  `services_id` int NOT NULL AUTO_INCREMENT,
  `services_name` varchar(255) NOT NULL,
  `services_description` text,
  `services_fee` decimal(10,2) NOT NULL,
  PRIMARY KEY (`services_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `services`
--

LOCK TABLES `services` WRITE;
/*!40000 ALTER TABLE `services` DISABLE KEYS */;
INSERT INTO `services` VALUES (1,'Electrician','Fixing power trips, wiring issues, and lighting installation.',87.00),(2,'Plumbing','Clearing pipe blockages, fixing water leaks, and tap installations.',70.00),(3,'Aircond Service','Chemical cleaning, gas refilling, and cooling repair.',90.00),(4,'Painting','Interior and exterior wall painting services.',120.00),(5,'Handyman','General home repairs, furniture assembly, and drilling.',60.00),(6,'Landscaping','Modify and maintain exterior environments.',65.00),(7,'Pest Control','General pest spraying, and bed bug treatment',150.00),(9,'Housekeeping','Clean the house',62.00);
/*!40000 ALTER TABLE `services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `technician`
--

DROP TABLE IF EXISTS `technician`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `technician` (
  `technician_id` int NOT NULL AUTO_INCREMENT,
  `tech_name` varchar(255) NOT NULL,
  `tech_phonenum` varchar(20) NOT NULL,
  `tech_email` varchar(255) NOT NULL,
  `area_id` int DEFAULT NULL,
  `ssm_num` varchar(50) DEFAULT NULL,
  `photo_path` varchar(255) DEFAULT NULL,
  `tech_availability` varchar(50) DEFAULT 'Available',
  `password_hash` varchar(255) DEFAULT NULL,
  `plan_type` enum('Basic','Premium') DEFAULT 'Basic',
  `payment_status` enum('Unpaid','Paid') DEFAULT 'Unpaid',
  `tech_status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  PRIMARY KEY (`technician_id`),
  KEY `area_id` (`area_id`),
  CONSTRAINT `technician_ibfk_1` FOREIGN KEY (`area_id`) REFERENCES `area` (`area_id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `technician`
--

LOCK TABLES `technician` WRITE;
/*!40000 ALTER TABLE `technician` DISABLE KEYS */;
INSERT INTO `technician` VALUES (9,'Adib Farhan','017-5678890','adib@gmail.com',3,'IP0012346-A','uploads/1781106873_6147694717689337079.jpg','Available','1234','Premium','Paid','Approved'),(10,'Aqil Nuaim','013-5602247','nuaim@gmail.com',1,'IP0012347-B','uploads/1781106944_6147694717689337080.jpg','Unavailable','1234','Premium','Paid','Approved'),(11,'Muhammad Muaz','013-2798857','muaz@gmail.com',7,'IP0012348-C','uploads/1781106980_6147694717689337081.jpg','Available','1234','Premium','Paid','Approved'),(12,'Aqill Romzi','012-9985432','aqil@gmail.com',3,'IP0012349-D','uploads/1781107052_6147694717689337082.jpg','Available','1234','Basic','Unpaid','Approved'),(13,'Uwais Dzarif','013-5702247','uwais@gmail.com',7,'IP0012350-H','uploads/1784574566886_1781107091_6147694717689337083.jpg','Unavailable','1234','Premium','Paid','Approved'),(14,'Faris Danial','013-5502155','payis@gmail.com',1,'IP0012351-K','uploads/1781107140_6147694717689337084.jpg','Available','1234','Basic','Unpaid','Approved'),(15,'Muhammad Sufi','013-2204543','sufi@gmail.com',7,'IP0012352-M','uploads/1781107166_6147694717689337085.jpg','Available','1234','Premium','Paid','Approved'),(16,'Muhamad Danish','017-4552290','danish@gmail.com',3,'IP0012353-P','uploads/1781107203_6147694717689337086.jpg','Available','1234','Basic','Unpaid','Approved'),(18,'Harith Hakimi','013-5662890','harith@gmail.com',7,'IP0012355-V','uploads/1781107339_6271795585799098278.jpg','Available','1234','Premium','Paid','Approved'),(19,'Wan Afif','013-2448824','wan@gmail.com',1,'IP0012356-W','uploads/1781107395_6271795585799098277.jpg','Available','1234','Basic','Unpaid','Approved'),(20,'Malik Helmi','013-2986621','malik@gmail.com',3,'IP0012345-X','uploads/1785177431640_1781107433_6271795585799098279.jpg','Available','1234','Premium','Paid','Approved'),(21,'Muhamad Akmal','013-5308976','akmal@gmail.com',3,'IP0012723-Y','uploads/1781145601_6271795585799098276.jpg','Available','1234','Basic','Unpaid','Approved'),(22,'Haqiem Rusli','0135776342','haqiem@gmail.com',18,'S537729193X9',NULL,'Unavailable','1234','Basic','Unpaid','Approved'),(23,'Andi Bernadee','0136752327','andibernadee@gmail.com',19,'A37729193X9',NULL,'Unavailable','1234','Basic','Unpaid','Rejected'),(24,'Andi Bernadee','0136752327','andibernadee@gmail.com',19,'A37729193X9',NULL,'Unavailable','1234','Basic','Unpaid','Rejected'),(25,'Andi Bernadee','0136752327','andibernadee@gmail.com',8,'A37729193X9',NULL,'Unavailable','$2a$10$GEt85vvmQ2aE8/PTK7kTu.X6HMsYqtC9jlsuZlmczakYD7BxBFAym','Basic','Unpaid','Rejected'),(26,'Zizan bin Razak','0198765431','zizan@gmail.com',3,'IP0021367-D','uploads/1785180103813_zizanprofil.jpg','Available','1234','Premium','Paid','Approved');
/*!40000 ALTER TABLE `technician` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `technician_services`
--

DROP TABLE IF EXISTS `technician_services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `technician_services` (
  `technician_id` int NOT NULL,
  `services_id` int NOT NULL,
  PRIMARY KEY (`technician_id`,`services_id`),
  KEY `services_id` (`services_id`),
  CONSTRAINT `technician_services_ibfk_1` FOREIGN KEY (`technician_id`) REFERENCES `technician` (`technician_id`) ON DELETE CASCADE,
  CONSTRAINT `technician_services_ibfk_2` FOREIGN KEY (`services_id`) REFERENCES `services` (`services_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `technician_services`
--

LOCK TABLES `technician_services` WRITE;
/*!40000 ALTER TABLE `technician_services` DISABLE KEYS */;
INSERT INTO `technician_services` VALUES (9,1),(11,1),(18,1),(20,1),(9,2),(11,2),(13,2),(20,2),(21,2),(13,3),(15,3),(18,3),(20,3),(22,3),(26,3),(11,4),(15,4),(16,4),(26,4),(9,5),(10,5),(12,5),(13,5),(15,5),(18,5),(20,5),(25,5),(26,5),(14,6),(19,6),(23,7),(24,7);
/*!40000 ALTER TABLE `technician_services` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-07-28 13:16:13

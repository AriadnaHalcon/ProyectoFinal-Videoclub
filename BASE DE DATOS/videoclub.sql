-- MySQL dump 10.13  Distrib 8.0.40, for Win64 (x86_64)
--
-- Host: localhost    Database: videoclub
-- ------------------------------------------------------
-- Server version	8.0.40

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `alquileres`
--

DROP TABLE IF EXISTS `alquileres`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `alquileres` (
  `id_alquiler` int NOT NULL AUTO_INCREMENT,
  `dni_cliente` varchar(15) NOT NULL,
  `id_pelicula` int NOT NULL,
  `fecha_alquiler` date NOT NULL DEFAULT (curdate()),
  `fecha_devolucion` date DEFAULT NULL,
  `estado` enum('Pendiente','Devuelto') DEFAULT 'Pendiente',
  `precio_rebajado` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id_alquiler`),
  KEY `alquileres_ibfk_2` (`id_pelicula`),
  KEY `alquileres_ibfk_1` (`dni_cliente`),
  CONSTRAINT `alquileres_ibfk_1` FOREIGN KEY (`dni_cliente`) REFERENCES `clientes` (`dni`),
  CONSTRAINT `alquileres_ibfk_2` FOREIGN KEY (`id_pelicula`) REFERENCES `peliculas` (`id_pelicula`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `alquileres`
--

LOCK TABLES `alquileres` WRITE;
/*!40000 ALTER TABLE `alquileres` DISABLE KEYS */;
INSERT INTO `alquileres` VALUES (40,'12345678I',18,'2025-06-06','2025-06-07','Pendiente',19.00),(41,'13213123U',11,'2025-06-06','2025-06-13','Pendiente',6.00);
/*!40000 ALTER TABLE `alquileres` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('arihal29@gmail.com|127.0.0.1','i:1;',1748344485),('arihal29@gmail.com|127.0.0.1:timer','i:1748344485;',1748344485),('gukuk@ddd|127.0.0.1','i:1;',1748344296),('gukuk@ddd|127.0.0.1:timer','i:1748344296;',1748344296),('josefmart@gmail.com|127.0.0.1','i:1;',1748589235),('josefmart@gmail.com|127.0.0.1:timer','i:1748589235;',1748589235);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `carrito`
--

DROP TABLE IF EXISTS `carrito`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `carrito` (
  `id` int NOT NULL AUTO_INCREMENT,
  `dni_cliente` char(9) NOT NULL,
  `id_pelicula` int DEFAULT NULL,
  `cantidad` int DEFAULT '1',
  `fecha_agregado` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_pelicula` (`id_pelicula`),
  KEY `carrito_ibfk_1` (`dni_cliente`),
  CONSTRAINT `carrito_ibfk_1` FOREIGN KEY (`dni_cliente`) REFERENCES `clientes` (`dni`),
  CONSTRAINT `carrito_ibfk_2` FOREIGN KEY (`id_pelicula`) REFERENCES `peliculas` (`id_pelicula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carrito`
--

LOCK TABLES `carrito` WRITE;
/*!40000 ALTER TABLE `carrito` DISABLE KEYS */;
/*!40000 ALTER TABLE `carrito` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categorias`
--

DROP TABLE IF EXISTS `categorias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categorias` (
  `id_categoria` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text,
  PRIMARY KEY (`id_categoria`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categorias`
--

LOCK TABLES `categorias` WRITE;
/*!40000 ALTER TABLE `categorias` DISABLE KEYS */;
INSERT INTO `categorias` VALUES (1,'Acción','Películas donde siempre pasa algo emocionante.'),(2,'Comedia','Películas para ver si quieres levantar tu ánimo y pasar un buen rato.'),(3,'Romántica','Películas donde siempre hay un final feliz y muy dulce.'),(4,'Terror','Películas para pasar miedo o ver en compañía.'),(9,'Fantasía','Películas para dejar volar tu imaginación.');
/*!40000 ALTER TABLE `categorias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clientes`
--

DROP TABLE IF EXISTS `clientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clientes` (
  `dni` char(9) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `direccion` varchar(150) DEFAULT NULL,
  `telefono` varchar(9) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `fecha_registro` date NOT NULL DEFAULT (curdate()),
  `id_tarifa` int DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`dni`),
  UNIQUE KEY `email` (`email`),
  KEY `fk_id_tarifa` (`id_tarifa`),
  KEY `fk_clientes_users` (`user_id`),
  CONSTRAINT `fk_clientes_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `fk_id_tarifa` FOREIGN KEY (`id_tarifa`) REFERENCES `tarifas` (`id_tarifa`) ON DELETE SET NULL,
  CONSTRAINT `chk_dni_formato` CHECK (regexp_like(`dni`,_utf8mb4'^[0-9]{8}[A-Za-z]$')),
  CONSTRAINT `chk_telefono_formato` CHECK (regexp_like(`telefono`,_utf8mb4'^[0-9]+$'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clientes`
--

LOCK TABLES `clientes` WRITE;
/*!40000 ALTER TABLE `clientes` DISABLE KEYS */;
INSERT INTO `clientes` VALUES ('12345678I','Viviana Wolf','Carabela','827774885','viviwolf@gmail.com','2025-06-05',6,31),('13213123U','Juana Suarez','Su casa','345345345','juasua29@gmail.com','2025-06-05',4,30);
/*!40000 ALTER TABLE `clientes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (7,'0001_01_01_000000_create_users_table',1),(8,'0001_01_01_000001_create_cache_table',1),(9,'0001_01_01_000002_create_jobs_table',1),(10,'2025_05_19_091028_add_rol_to_users_table',2),(11,'2025_05_19_092104_remove_rol_from_clientes_table',3),(12,'2025_06_04_000001_add_checks_to_clientes_table',4);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `peliculas`
--

DROP TABLE IF EXISTS `peliculas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `peliculas` (
  `id_pelicula` int NOT NULL AUTO_INCREMENT,
  `titulo` varchar(100) NOT NULL,
  `id_categoria` int NOT NULL,
  `director` varchar(100) DEFAULT NULL,
  `anio_estreno` year DEFAULT NULL,
  `stock` int DEFAULT '0',
  `imagen` varchar(255) DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id_pelicula`),
  KEY `id_categoria` (`id_categoria`),
  CONSTRAINT `peliculas_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id_categoria`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `peliculas`
--

LOCK TABLES `peliculas` WRITE;
/*!40000 ALTER TABLE `peliculas` DISABLE KEYS */;
INSERT INTO `peliculas` VALUES (6,'Dando la nota',2,'Jason Moore',2012,8,'peliculas/WSX0A1PBBHJBShGtNGvzoXQUP5YoZguLW5UW73zS.jpg',8.50),(8,'Notting Hill',3,'Roger Michell',1999,6,'peliculas/Ye3yUOQC0OUTdoV2h9tFCRCgaY378LurA8n59oFz.jpg',9.99),(11,'Dead Silence',4,'James Wan',2002,2,'peliculas/E0R9C7xLeM3j92vKva5rQmskxYhLZRgfUwiD44Ug.jpg',20.00),(12,'Los vengadores',1,'Anthony Russo, Joss Whedon, Joe Russo',2012,6,'peliculas/gtDfAIVk4DzgbG4rwKt5VZ101PoGB5e3977jhTM2.jpg',10.00),(13,'Hermosas Criaturas',9,'Richard LaGravenese',2012,10,'peliculas/mzQsIzGpqzaLNJReXlLlQl6hT5jil6cD4YVg7JWd.jpg',10.00),(14,'Percy Jackson y el ladrón del rayo',9,'Chris Columbus',2010,7,'peliculas/7CsrJVRLLjje6KWsoTLdOFNQkKx5VoEKBSwBKbIQ.jpg',15.60),(15,'El corredor del laberinto',1,'Wes Ball',2014,1,'peliculas/4HXgp7usw6nNZ3KZBz0HvJN0VTHnNBx9fIcX61Ee.jpg',5.00),(16,'El conjuro',4,'James Wan',2012,6,'peliculas/eaKUjTGYYEBgSBNDG2tvQxQAkaGd1qnyJmOkCMnC.jpg',10.00),(18,'Jumanji',1,'Joe Johnston',1995,4,'peliculas/4duKNL1TU4OtOkxQInAMuPVx6kxSSUoktDAkaSVI.jpg',19.00),(19,'Shrek',9,'Andrew Adamson, Vicky Jenson',2001,12,'peliculas/AYFXvv2TVsXOYkkRk509c6m2OEZyyL8PAkRuP4Wp.jpg',20.00);
/*!40000 ALTER TABLE `peliculas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tarifas`
--

DROP TABLE IF EXISTS `tarifas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tarifas` (
  `id_tarifa` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `descuento` decimal(5,2) DEFAULT '0.00',
  PRIMARY KEY (`id_tarifa`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tarifas`
--

LOCK TABLES `tarifas` WRITE;
/*!40000 ALTER TABLE `tarifas` DISABLE KEYS */;
INSERT INTO `tarifas` VALUES (2,'Oro',10.00),(3,'Platino',40.00),(4,'Premium',70.00),(6,'Estándar',0.00);
/*!40000 ALTER TABLE `tarifas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rol` enum('usuario','administrador') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'usuario',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','admin@admin.com',NULL,'$2y$12$gRnwe7gBw8/1ihH6yjB4JO9ijcAWxnkrT/kMddVtwkC/gscv6NZ7i','administrador',NULL,'2025-01-09 07:12:49','2025-05-27 11:05:42'),(22,'Pantera Rosa','pinkpanther@gmail.com',NULL,'$2y$12$KLor6l4fUOaB7ijvSUwg5.e0DQf3.NxPh1hxlda01zJN0hQ3zVBPm','usuario',NULL,'2025-06-04 05:39:13','2025-06-04 05:39:13'),(23,'Hello Kitty','kittydabest@hotmail.com',NULL,'$2y$12$zZspMQh28YyNnLOOQp9oUeI1Y5bBlSx2DoHo73JxYZNnis2JH9eC.','usuario',NULL,'2025-06-05 04:27:59','2025-06-05 04:27:59'),(24,'Melanie Martinez','melmartinez2804@gmail.com',NULL,'$2y$12$vCx1Gdj3LLO.b0/JfSGdG.cgtqrI6SFN8B7A7CoHYfvG4Ap7StHn2','usuario',NULL,'2025-06-05 04:31:31','2025-06-05 04:31:31'),(28,'Gumball Watterson','gumydar@hotmail.com',NULL,'$2y$12$UBbPIkJD1Ofva1iZTSlIMe/YxE/tSG2rU1jFgqgy4QvNB4aJTYbEu','usuario',NULL,'2025-06-05 05:22:33','2025-06-05 05:22:33'),(29,'Samantha Puckets','sambuttersock@hotmail.com',NULL,'$2y$12$aKL8iTUA.ZfONpVif5HDdOFzok11WEHfp81fi18tj.PBKveigoGE.','usuario',NULL,'2025-06-05 05:42:20','2025-06-05 05:42:20'),(30,'Juana Suarez','juasua29@gmail.com',NULL,'$2y$12$zvIySeiqE0WFArNstSZyW.Fb0ZeqKHNuMB9e8eoitaT5w2Cmgbow2','usuario',NULL,'2025-06-05 05:57:44','2025-06-05 05:57:44'),(31,'Viviana Wolf','viviwolf@gmail.com',NULL,'$2y$12$3tFDFfM3ubjkJ5IQPKq0KOstZYb5uo9w1hPMReq70YxZhg7B3PaoS','usuario',NULL,'2025-06-05 06:37:57','2025-06-05 06:37:57');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-06-06 14:01:11

-- MySQL dump 10.13  Distrib 5.6.23, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: tc_minsa
-- ------------------------------------------------------
-- Server version	5.5.5-10.1.32-MariaDB

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
-- Table structure for table `ma_campos`
--

DROP TABLE IF EXISTS `ma_campos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ma_campos` (
  `id_campo` int(11) NOT NULL AUTO_INCREMENT,
  `id_empresa` int(11) NOT NULL,
  `id_tipo` int(11) NOT NULL,
  `des_campo` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_campo`,`id_empresa`),
  KEY `id_tipo` (`id_tipo`),
  KEY `id_empresa` (`id_empresa`),
  CONSTRAINT `ma_campos_ibfk_1` FOREIGN KEY (`id_tipo`) REFERENCES `sys_tipos_dato` (`id_tipo`),
  CONSTRAINT `ma_campos_ibfk_2` FOREIGN KEY (`id_empresa`) REFERENCES `ma_empresa` (`id_empresa`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ma_campos`
--

LOCK TABLES `ma_campos` WRITE;
/*!40000 ALTER TABLE `ma_campos` DISABLE KEYS */;
INSERT INTO `ma_campos` VALUES (1,1,4,'Fecha registro','2018-09-08 17:38:35',NULL),(2,1,4,'Fecha salida','2018-09-08 17:38:54',NULL),(3,1,1,'Valor','2018-09-08 17:39:04',NULL),(4,1,3,'Observación','2018-09-08 17:39:16',NULL),(5,1,3,'Situación','2018-09-08 17:39:22',NULL);
/*!40000 ALTER TABLE `ma_campos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ma_empresa`
--

DROP TABLE IF EXISTS `ma_empresa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ma_empresa` (
  `id_empresa` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  `st_vigente` varchar(10) NOT NULL DEFAULT 'Vigente',
  `cod_entidad` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id_empresa`),
  KEY `R_1` (`cod_entidad`),
  CONSTRAINT `R_1` FOREIGN KEY (`cod_entidad`) REFERENCES `ma_entidad` (`cod_entidad`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ma_empresa`
--

LOCK TABLES `ma_empresa` WRITE;
/*!40000 ALTER TABLE `ma_empresa` DISABLE KEYS */;
INSERT INTO `ma_empresa` VALUES (1,'2018-09-02 11:27:54',NULL,'Vigente','20131373237');
/*!40000 ALTER TABLE `ma_empresa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ma_entidad`
--

DROP TABLE IF EXISTS `ma_entidad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ma_entidad` (
  `cod_entidad` varchar(15) NOT NULL,
  `des_nombre_1` varchar(50) NOT NULL,
  `des_nombre_2` varchar(50) DEFAULT NULL,
  `des_nombre_3` varchar(50) DEFAULT NULL,
  `tp_documento` varchar(3) NOT NULL,
  `st_vigente` varchar(10) NOT NULL DEFAULT 'Vigente',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`cod_entidad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ma_entidad`
--

LOCK TABLES `ma_entidad` WRITE;
/*!40000 ALTER TABLE `ma_entidad` DISABLE KEYS */;
INSERT INTO `ma_entidad` VALUES ('20131373237','MINISTERIO DE SALUD','MINISTERIO DE SALUD',NULL,'RUC','Vigente','2018-09-02 11:25:55',NULL);
/*!40000 ALTER TABLE `ma_entidad` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ma_hitos_control`
--

DROP TABLE IF EXISTS `ma_hitos_control`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ma_hitos_control` (
  `id_hito` int(11) NOT NULL AUTO_INCREMENT,
  `id_empresa` int(11) NOT NULL,
  `des_hito` varchar(50) NOT NULL,
  `st_vigente` varchar(10) DEFAULT 'Vigente',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  `id_responsable` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_hito`,`id_empresa`),
  KEY `ma_hitos_control_ibfk_2_idx` (`id_empresa`,`id_responsable`),
  CONSTRAINT `ma_hitos_control_ibfk_1` FOREIGN KEY (`id_empresa`) REFERENCES `ma_empresa` (`id_empresa`),
  CONSTRAINT `ma_hitos_control_ibfk_2` FOREIGN KEY (`id_empresa`, `id_responsable`) REFERENCES `ma_puesto` (`id_empresa`, `id_puesto`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ma_hitos_control`
--

LOCK TABLES `ma_hitos_control` WRITE;
/*!40000 ALTER TABLE `ma_hitos_control` DISABLE KEYS */;
INSERT INTO `ma_hitos_control` VALUES (1,1,'Solicitud del pedido','Vigente','2018-09-08 18:18:43',NULL,5),(2,1,'Disponibilidad presupuestaria','Vigente','2018-09-08 18:19:07',NULL,3),(3,1,'Aprobación del requerimiento','Vigente','2018-09-08 18:19:51',NULL,1),(4,1,'Estudio de mercado','Vigente','2018-09-08 18:20:04',NULL,7),(5,1,'Certificación presupuestal','Vigente','2018-09-08 18:20:22',NULL,2),(6,1,'Emisión de la orden','Vigente','2018-09-08 18:20:40',NULL,1),(7,1,'Pago','Vigente','2018-09-15 12:51:24',NULL,3);
/*!40000 ALTER TABLE `ma_hitos_control` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ma_menu`
--

DROP TABLE IF EXISTS `ma_menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ma_menu` (
  `id_item` int(11) NOT NULL AUTO_INCREMENT,
  `des_nombre` varchar(30) NOT NULL,
  `st_vigente` varchar(15) NOT NULL DEFAULT 'Vigente',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  `id_ancestro` int(11) DEFAULT NULL,
  `des_url` varchar(30) NOT NULL,
  PRIMARY KEY (`id_item`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ma_menu`
--

LOCK TABLES `ma_menu` WRITE;
/*!40000 ALTER TABLE `ma_menu` DISABLE KEYS */;
INSERT INTO `ma_menu` VALUES (1,'Registros','Vigente','2018-09-02 14:09:55',NULL,NULL,'registros'),(2,'Estandarización de procesos','Vigente','2018-09-02 14:09:55',NULL,NULL,'estandarizacion'),(3,'Control y seguimiento','Vigente','2018-09-02 14:09:55',NULL,NULL,'seguimiento'),(4,'Reportes e informes','Vigente','2018-09-02 14:09:55',NULL,NULL,'reportes'),(5,'Lista de usuarios','Vigente','2018-09-02 14:12:19',NULL,1,'usuarios'),(6,'Organigrama','Vigente','2018-09-02 14:12:19',NULL,1,'organigrama'),(7,'Administradores','Vigente','2018-09-02 14:12:19',NULL,1,'administradores'),(8,'Maestros','Vigente','2018-09-08 12:01:08',NULL,2,'maestros'),(9,'Procesos','Vigente','2018-09-15 11:03:44',NULL,2,'procesos'),(10,'Matriz de valoración','Vigente','2018-09-15 17:28:34',NULL,2,'valoracion'),(11,'Protal de requerimientos','Vigente','2018-09-15 20:01:55',NULL,3,'resumen'),(12,'Control de cambios','Vigente','2018-09-15 20:01:55',NULL,3,'cambios');
/*!40000 ALTER TABLE `ma_menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ma_oficina`
--

DROP TABLE IF EXISTS `ma_oficina`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ma_oficina` (
  `id_oficina` int(11) NOT NULL AUTO_INCREMENT,
  `des_oficina` varchar(50) NOT NULL,
  `st_vigente` varchar(10) NOT NULL DEFAULT 'Vigente',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  `num_jerarquia` int(11) DEFAULT NULL,
  `id_encargado` int(11) DEFAULT NULL,
  `id_ancestro` int(11) DEFAULT NULL,
  `id_empresa` int(11) NOT NULL,
  PRIMARY KEY (`id_oficina`,`id_empresa`),
  KEY `R_12` (`id_empresa`),
  CONSTRAINT `R_12` FOREIGN KEY (`id_empresa`) REFERENCES `ma_empresa` (`id_empresa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ma_oficina`
--

LOCK TABLES `ma_oficina` WRITE;
/*!40000 ALTER TABLE `ma_oficina` DISABLE KEYS */;
/*!40000 ALTER TABLE `ma_oficina` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ma_puesto`
--

DROP TABLE IF EXISTS `ma_puesto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ma_puesto` (
  `id_puesto` int(11) NOT NULL AUTO_INCREMENT,
  `des_puesto` varchar(50) DEFAULT NULL,
  `num_jerarquia` int(11) DEFAULT NULL,
  `st_vigente` varchar(10) DEFAULT 'Vigente',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  `id_empresa` int(11) NOT NULL,
  `id_oficina` int(11) DEFAULT NULL,
  `id_superior` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_puesto`,`id_empresa`),
  KEY `R_11` (`id_empresa`),
  KEY `R_13` (`id_oficina`,`id_empresa`),
  CONSTRAINT `R_11` FOREIGN KEY (`id_empresa`) REFERENCES `ma_empresa` (`id_empresa`),
  CONSTRAINT `R_13` FOREIGN KEY (`id_oficina`, `id_empresa`) REFERENCES `ma_oficina` (`id_oficina`, `id_empresa`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ma_puesto`
--

LOCK TABLES `ma_puesto` WRITE;
/*!40000 ALTER TABLE `ma_puesto` DISABLE KEYS */;
INSERT INTO `ma_puesto` VALUES (1,'Gerente General',1,'Vigente','2018-09-08 10:23:43',NULL,1,NULL,NULL),(2,'Gerente de TI',2,'Vigente','2018-09-08 10:38:42',NULL,1,NULL,1),(3,'Gerente de Finanzas',2,'Vigente','2018-09-08 17:50:53',NULL,1,NULL,1),(4,'Gerente de RRHH',2,'Vigente','2018-09-08 17:51:06',NULL,1,NULL,1),(5,'Analista TI',3,'Vigente','2018-09-08 17:51:27',NULL,1,NULL,2),(6,'Jefe Mesa de Ayuda',3,'Vigente','2018-09-08 17:51:47',NULL,1,NULL,2),(7,'Gerente Comercial',2,'Vigente','2018-09-15 18:55:31',NULL,1,NULL,1);
/*!40000 ALTER TABLE `ma_puesto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ma_usuarios`
--

DROP TABLE IF EXISTS `ma_usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ma_usuarios` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `des_alias` varchar(30) NOT NULL,
  `des_email` varchar(100) NOT NULL,
  `des_telefono` varchar(15) DEFAULT NULL,
  `tp_usuario` char(1) NOT NULL DEFAULT 'U',
  `st_vigente` varchar(10) NOT NULL DEFAULT 'Vigente',
  `password` varchar(200) NOT NULL,
  `remember_token` varchar(200) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  `fe_ingreso` datetime DEFAULT NULL,
  `st_verifica_mail` char(1) NOT NULL,
  `fe_ultimo_acceso` datetime DEFAULT NULL,
  `cod_entidad` varchar(15) DEFAULT NULL,
  `id_empresa` int(11) NOT NULL,
  PRIMARY KEY (`id_usuario`,`id_empresa`),
  KEY `R_2` (`cod_entidad`),
  KEY `R_3` (`id_empresa`),
  CONSTRAINT `R_2` FOREIGN KEY (`cod_entidad`) REFERENCES `ma_entidad` (`cod_entidad`),
  CONSTRAINT `R_3` FOREIGN KEY (`id_empresa`) REFERENCES `ma_empresa` (`id_empresa`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ma_usuarios`
--

LOCK TABLES `ma_usuarios` WRITE;
/*!40000 ALTER TABLE `ma_usuarios` DISABLE KEYS */;
INSERT INTO `ma_usuarios` VALUES (1,'admin','mvelasquezp88@gmail.com','989 845 561','S','Vigente','$2y$10$7Eua2KNIbltGjLeLSKIY3.LsdmW.rPqLc5O25S8dZ2LvYaRYdCK5y','fyuGBVWyOUj7q5SirDIRul2L9a0AzKlqP8FfQ7Oscs0kp0w42YaqpcwLodsx','2018-09-02 11:28:52',NULL,NULL,'S',NULL,'20131373237',1);
/*!40000 ALTER TABLE `ma_usuarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pr_catalogo_hitos`
--

DROP TABLE IF EXISTS `pr_catalogo_hitos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pr_catalogo_hitos` (
  `id_hito` int(11) NOT NULL,
  `id_empresa` int(11) NOT NULL,
  `id_catalogo` int(11) NOT NULL,
  `id_usuario_registra` int(11) NOT NULL,
  `nu_peso` decimal(4,2) NOT NULL,
  `st_vigente` varchar(10) NOT NULL DEFAULT 'Vigente',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_hito`,`id_empresa`,`id_catalogo`),
  KEY `id_usuario_registra` (`id_usuario_registra`,`id_empresa`),
  KEY `id_catalogo` (`id_catalogo`,`id_empresa`),
  CONSTRAINT `pr_catalogo_hitos_ibfk_1` FOREIGN KEY (`id_usuario_registra`, `id_empresa`) REFERENCES `ma_usuarios` (`id_usuario`, `id_empresa`),
  CONSTRAINT `pr_catalogo_hitos_ibfk_2` FOREIGN KEY (`id_hito`, `id_empresa`) REFERENCES `ma_hitos_control` (`id_hito`, `id_empresa`),
  CONSTRAINT `pr_catalogo_hitos_ibfk_3` FOREIGN KEY (`id_catalogo`, `id_empresa`) REFERENCES `pr_catalogo_proyecto` (`id_catalogo`, `id_empresa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pr_catalogo_hitos`
--

LOCK TABLES `pr_catalogo_hitos` WRITE;
/*!40000 ALTER TABLE `pr_catalogo_hitos` DISABLE KEYS */;
INSERT INTO `pr_catalogo_hitos` VALUES (1,1,1,1,2.00,'Vigente','2018-09-15 16:42:04',NULL),(1,1,2,1,3.00,'Vigente','2018-09-15 17:05:53',NULL),(2,1,1,1,1.00,'Vigente','2018-09-15 16:43:48',NULL),(3,1,2,1,1.00,'Vigente','2018-09-15 17:05:57',NULL),(4,1,1,1,3.00,'Vigente','2018-09-15 16:57:55','2018-09-15 22:05:37'),(6,1,1,1,1.00,'Vigente','2018-09-15 17:03:14',NULL),(6,1,2,1,1.00,'Vigente','2018-09-15 17:06:02',NULL),(7,1,1,1,4.00,'Vigente','2018-09-15 17:03:56',NULL),(7,1,2,1,5.00,'Vigente','2018-09-15 17:06:05',NULL);
/*!40000 ALTER TABLE `pr_catalogo_hitos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pr_catalogo_proyecto`
--

DROP TABLE IF EXISTS `pr_catalogo_proyecto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pr_catalogo_proyecto` (
  `id_catalogo` int(11) NOT NULL AUTO_INCREMENT,
  `id_empresa` int(11) NOT NULL,
  `des_catalogo` varchar(30) NOT NULL,
  `st_vigente` varchar(10) NOT NULL DEFAULT 'Vigente',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_catalogo`,`id_empresa`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pr_catalogo_proyecto`
--

LOCK TABLES `pr_catalogo_proyecto` WRITE;
/*!40000 ALTER TABLE `pr_catalogo_proyecto` DISABLE KEYS */;
INSERT INTO `pr_catalogo_proyecto` VALUES (1,1,'ASP','Vigente','2018-09-15 12:58:32',NULL),(2,1,'Terceros','Vigente','2018-09-15 12:58:33',NULL);
/*!40000 ALTER TABLE `pr_catalogo_proyecto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pr_hitos_campo`
--

DROP TABLE IF EXISTS `pr_hitos_campo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pr_hitos_campo` (
  `id_hito` int(11) NOT NULL,
  `id_empresa` int(11) NOT NULL,
  `id_campo` int(11) NOT NULL,
  `id_usuario_asigna` int(11) NOT NULL,
  `st_vigente` varchar(10) DEFAULT 'Vigente',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_hito`,`id_empresa`,`id_campo`),
  KEY `id_campo` (`id_campo`,`id_empresa`),
  KEY `id_usuario_asigna` (`id_usuario_asigna`,`id_empresa`),
  CONSTRAINT `pr_hitos_campo_ibfk_1` FOREIGN KEY (`id_hito`, `id_empresa`) REFERENCES `ma_hitos_control` (`id_hito`, `id_empresa`),
  CONSTRAINT `pr_hitos_campo_ibfk_2` FOREIGN KEY (`id_campo`, `id_empresa`) REFERENCES `ma_campos` (`id_campo`, `id_empresa`),
  CONSTRAINT `pr_hitos_campo_ibfk_3` FOREIGN KEY (`id_usuario_asigna`, `id_empresa`) REFERENCES `ma_usuarios` (`id_usuario`, `id_empresa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pr_hitos_campo`
--

LOCK TABLES `pr_hitos_campo` WRITE;
/*!40000 ALTER TABLE `pr_hitos_campo` DISABLE KEYS */;
INSERT INTO `pr_hitos_campo` VALUES (1,1,1,1,'Vigente','2018-09-09 18:45:20',NULL),(1,1,3,1,'Vigente','2018-09-09 18:45:22',NULL),(1,1,4,1,'Vigente','2018-09-09 18:47:06',NULL),(1,1,5,1,'Vigente','2018-09-09 18:47:00',NULL),(2,1,2,1,'Vigente','2018-09-09 18:48:48',NULL),(2,1,3,1,'Vigente','2018-09-09 18:48:48',NULL),(3,1,2,1,'Vigente','2018-09-09 18:50:12',NULL),(3,1,4,1,'Vigente','2018-09-09 18:50:12',NULL),(3,1,5,1,'Vigente','2018-09-09 18:50:13',NULL),(4,1,1,1,'Vigente','2018-09-09 18:50:20',NULL),(4,1,2,1,'Vigente','2018-09-09 18:50:20',NULL),(4,1,5,1,'Vigente','2018-09-09 18:50:19',NULL),(5,1,1,1,'Vigente','2018-09-09 18:50:29',NULL),(5,1,3,1,'Vigente','2018-09-09 18:50:30',NULL),(5,1,4,1,'Vigente','2018-09-09 18:50:31',NULL),(6,1,1,1,'Vigente','2018-09-09 18:50:37',NULL),(6,1,2,1,'Vigente','2018-09-09 18:50:38',NULL),(6,1,5,1,'Vigente','2018-09-09 18:50:39',NULL),(7,1,3,1,'Vigente','2018-09-15 12:51:29',NULL),(7,1,4,1,'Vigente','2018-09-15 12:51:31',NULL),(7,1,5,1,'Vigente','2018-09-15 12:51:32',NULL);
/*!40000 ALTER TABLE `pr_hitos_campo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pr_proyecto`
--

DROP TABLE IF EXISTS `pr_proyecto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pr_proyecto` (
  `id_proyecto` int(11) NOT NULL AUTO_INCREMENT,
  `id_empresa` int(11) NOT NULL,
  `id_oficina` int(11) NOT NULL,
  `id_catalogo` int(11) NOT NULL,
  `des_codigo` varchar(15) NOT NULL,
  `des_proyecto` varchar(100) NOT NULL,
  `des_descripcion` varchar(150) DEFAULT NULL,
  `des_expediente` varchar(30) DEFAULT NULL,
  `des_hoja_tramite` varchar(30) DEFAULT NULL,
  `num_valor` decimal(10,2) DEFAULT NULL,
  `des_observaciones` varchar(150) DEFAULT NULL,
  `fe_inicio` datetime NOT NULL,
  `num_dias` int(11) NOT NULL,
  `fe_fin` datetime NOT NULL,
  `st_vigente` varchar(10) NOT NULL DEFAULT 'Vigente',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_proyecto`,`id_empresa`),
  KEY `id_empresa` (`id_empresa`),
  KEY `id_catalogo` (`id_catalogo`,`id_empresa`),
  KEY `id_oficina` (`id_oficina`,`id_empresa`),
  CONSTRAINT `pr_proyecto_ibfk_1` FOREIGN KEY (`id_empresa`) REFERENCES `ma_empresa` (`id_empresa`),
  CONSTRAINT `pr_proyecto_ibfk_2` FOREIGN KEY (`id_catalogo`, `id_empresa`) REFERENCES `pr_catalogo_proyecto` (`id_catalogo`, `id_empresa`),
  CONSTRAINT `pr_proyecto_ibfk_3` FOREIGN KEY (`id_oficina`, `id_empresa`) REFERENCES `ma_oficina` (`id_oficina`, `id_empresa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pr_proyecto`
--

LOCK TABLES `pr_proyecto` WRITE;
/*!40000 ALTER TABLE `pr_proyecto` DISABLE KEYS */;
/*!40000 ALTER TABLE `pr_proyecto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pr_proyecto_hitos`
--

DROP TABLE IF EXISTS `pr_proyecto_hitos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pr_proyecto_hitos` (
  `id_detalle` int(11) NOT NULL,
  `id_proyecto` int(11) NOT NULL,
  `id_hito` int(11) NOT NULL,
  `id_empresa` int(11) NOT NULL,
  `id_catalogo` int(11) NOT NULL,
  `id_estado_proceso` int(11) NOT NULL,
  `id_estado_documentacion` int(11) NOT NULL,
  `id_responsable` int(11) NOT NULL,
  `des_observaciones` varchar(150) DEFAULT NULL,
  `fe_inicio` datetime NOT NULL,
  `nu_dias` int(11) NOT NULL,
  `fe_fin` datetime NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_detalle`,`id_proyecto`,`id_hito`,`id_empresa`,`id_catalogo`),
  KEY `id_responsable` (`id_responsable`,`id_empresa`),
  KEY `id_estado_proceso` (`id_estado_proceso`),
  KEY `id_estado_documentacion` (`id_estado_documentacion`),
  KEY `id_proyecto` (`id_proyecto`,`id_empresa`),
  KEY `id_hito` (`id_hito`,`id_empresa`,`id_catalogo`),
  CONSTRAINT `pr_proyecto_hitos_ibfk_1` FOREIGN KEY (`id_responsable`, `id_empresa`) REFERENCES `ma_puesto` (`id_puesto`, `id_empresa`),
  CONSTRAINT `pr_proyecto_hitos_ibfk_2` FOREIGN KEY (`id_estado_proceso`) REFERENCES `sys_estados` (`id_estado`),
  CONSTRAINT `pr_proyecto_hitos_ibfk_3` FOREIGN KEY (`id_estado_documentacion`) REFERENCES `sys_estados` (`id_estado`),
  CONSTRAINT `pr_proyecto_hitos_ibfk_4` FOREIGN KEY (`id_proyecto`, `id_empresa`) REFERENCES `pr_proyecto` (`id_proyecto`, `id_empresa`),
  CONSTRAINT `pr_proyecto_hitos_ibfk_5` FOREIGN KEY (`id_hito`, `id_empresa`, `id_catalogo`) REFERENCES `pr_catalogo_hitos` (`id_hito`, `id_empresa`, `id_catalogo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pr_proyecto_hitos`
--

LOCK TABLES `pr_proyecto_hitos` WRITE;
/*!40000 ALTER TABLE `pr_proyecto_hitos` DISABLE KEYS */;
/*!40000 ALTER TABLE `pr_proyecto_hitos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pr_valoracion`
--

DROP TABLE IF EXISTS `pr_valoracion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pr_valoracion` (
  `id_estado_p` int(11) NOT NULL,
  `id_estado_c` int(11) NOT NULL,
  `num_puntaje` int(11) NOT NULL,
  `id_usuario_registra` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_estado_p`,`id_estado_c`),
  KEY `id_estado_c` (`id_estado_c`),
  CONSTRAINT `pr_valoracion_ibfk_1` FOREIGN KEY (`id_estado_p`) REFERENCES `sys_estados` (`id_estado`),
  CONSTRAINT `pr_valoracion_ibfk_2` FOREIGN KEY (`id_estado_c`) REFERENCES `sys_estados` (`id_estado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pr_valoracion`
--

LOCK TABLES `pr_valoracion` WRITE;
/*!40000 ALTER TABLE `pr_valoracion` DISABLE KEYS */;
INSERT INTO `pr_valoracion` VALUES (1,2,1,1,'2018-09-15 18:48:15','2018-09-15 23:49:52'),(1,4,2,1,'2018-09-15 18:48:15','2018-09-15 23:49:52'),(1,5,3,1,'2018-09-15 18:48:15','2018-09-15 23:49:53'),(3,2,4,1,'2018-09-15 18:48:15','2018-09-15 23:49:53'),(3,4,5,1,'2018-09-15 18:48:15','2018-09-15 23:49:53'),(3,5,6,1,'2018-09-15 18:43:07','2018-09-15 23:49:53'),(6,2,7,1,'2018-09-15 18:49:53',NULL),(6,4,8,1,'2018-09-15 18:49:53',NULL),(6,5,9,1,'2018-09-15 18:49:53',NULL);
/*!40000 ALTER TABLE `pr_valoracion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sys_estados`
--

DROP TABLE IF EXISTS `sys_estados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_estados` (
  `id_estado` int(11) NOT NULL AUTO_INCREMENT,
  `cod_estado` varchar(30) NOT NULL,
  `des_estado` varchar(30) NOT NULL,
  `tp_estado` char(1) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_estado`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sys_estados`
--

LOCK TABLES `sys_estados` WRITE;
/*!40000 ALTER TABLE `sys_estados` DISABLE KEYS */;
INSERT INTO `sys_estados` VALUES (1,'0-Por iniciar','Por iniciar','P','2018-09-09 11:41:41',NULL),(2,'1-Sin documentacion','Sin documentación','C','2018-09-09 11:49:03',NULL),(3,'2-En proceso','En proceso','P','2018-09-09 11:49:38',NULL),(4,'2-Pendiente','Pendiente','C','2018-09-09 11:49:50',NULL),(5,'3-Completo','Completo','C','2018-09-09 11:49:57',NULL),(6,'3-Cerrado','Cerrado','P','2018-09-09 11:50:14',NULL);
/*!40000 ALTER TABLE `sys_estados` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sys_permisos`
--

DROP TABLE IF EXISTS `sys_permisos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_permisos` (
  `id_item` int(11) NOT NULL,
  `st_habilitado` char(1) NOT NULL DEFAULT 'S',
  `st_vigente` varchar(15) NOT NULL DEFAULT 'Vigente',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_empresa` int(11) NOT NULL,
  PRIMARY KEY (`id_item`,`id_usuario`,`id_empresa`),
  KEY `R_6` (`id_usuario`,`id_empresa`),
  CONSTRAINT `R_6` FOREIGN KEY (`id_usuario`, `id_empresa`) REFERENCES `ma_usuarios` (`id_usuario`, `id_empresa`),
  CONSTRAINT `R_7` FOREIGN KEY (`id_item`) REFERENCES `ma_menu` (`id_item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sys_permisos`
--

LOCK TABLES `sys_permisos` WRITE;
/*!40000 ALTER TABLE `sys_permisos` DISABLE KEYS */;
INSERT INTO `sys_permisos` VALUES (1,'S','Vigente','2018-09-02 14:16:00',NULL,1,1),(2,'S','Vigente','2018-09-02 14:16:00',NULL,1,1),(3,'S','Vigente','2018-09-02 14:16:00',NULL,1,1),(4,'S','Vigente','2018-09-02 14:16:00',NULL,1,1),(5,'S','Vigente','2018-09-02 14:16:00',NULL,1,1),(6,'S','Vigente','2018-09-02 14:16:00',NULL,1,1),(7,'S','Vigente','2018-09-02 14:16:00',NULL,1,1),(8,'S','Vigente','2018-09-08 12:01:29',NULL,1,1),(9,'S','Vigente','2018-09-15 11:04:08',NULL,1,1),(10,'S','Vigente','2018-09-15 17:28:48',NULL,1,1),(11,'S','Vigente','2018-09-15 20:02:12',NULL,1,1),(12,'S','Vigente','2018-09-15 20:02:13',NULL,1,1);
/*!40000 ALTER TABLE `sys_permisos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sys_tipos_dato`
--

DROP TABLE IF EXISTS `sys_tipos_dato`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_tipos_dato` (
  `id_tipo` int(11) NOT NULL AUTO_INCREMENT,
  `des_tipo` varchar(30) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_tipo`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sys_tipos_dato`
--

LOCK TABLES `sys_tipos_dato` WRITE;
/*!40000 ALTER TABLE `sys_tipos_dato` DISABLE KEYS */;
INSERT INTO `sys_tipos_dato` VALUES (1,'Número entero','2018-09-08 16:29:06',NULL),(2,'Número decimal','2018-09-08 16:29:07',NULL),(3,'Texto','2018-09-08 16:29:07',NULL),(4,'Fecha','2018-09-08 16:29:07',NULL),(5,'Caracter','2018-09-08 16:29:07',NULL),(6,'Lógico','2018-09-08 16:29:07',NULL);
/*!40000 ALTER TABLE `sys_tipos_dato` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `us_usuario_puesto`
--

DROP TABLE IF EXISTS `us_usuario_puesto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `us_usuario_puesto` (
  `st_vigente` varchar(10) DEFAULT 'Vigente',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_empresa` int(11) NOT NULL,
  `id_puesto` int(11) NOT NULL,
  PRIMARY KEY (`id_usuario`,`id_empresa`,`id_puesto`),
  KEY `R_10` (`id_puesto`,`id_empresa`),
  CONSTRAINT `R_10` FOREIGN KEY (`id_puesto`, `id_empresa`) REFERENCES `ma_puesto` (`id_puesto`, `id_empresa`),
  CONSTRAINT `R_9` FOREIGN KEY (`id_usuario`, `id_empresa`) REFERENCES `ma_usuarios` (`id_usuario`, `id_empresa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `us_usuario_puesto`
--

LOCK TABLES `us_usuario_puesto` WRITE;
/*!40000 ALTER TABLE `us_usuario_puesto` DISABLE KEYS */;
/*!40000 ALTER TABLE `us_usuario_puesto` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-09-16 21:15:25

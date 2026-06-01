-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 01-06-2026 a las 20:00:59
-- Versión del servidor: 8.3.0
-- Versión de PHP: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `gestion_mobiliario`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `activity_log`
--

DROP TABLE IF EXISTS `activity_log`;
CREATE TABLE IF NOT EXISTS `activity_log` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `log_name` varchar(191) DEFAULT NULL,
  `description` text NOT NULL,
  `subject_type` varchar(191) DEFAULT NULL,
  `event` varchar(191) DEFAULT NULL,
  `subject_id` bigint UNSIGNED DEFAULT NULL,
  `causer_type` varchar(191) DEFAULT NULL,
  `causer_id` bigint UNSIGNED DEFAULT NULL,
  `properties` json DEFAULT NULL,
  `batch_uuid` char(36) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subject` (`subject_type`,`subject_id`),
  KEY `causer` (`causer_type`,`causer_id`),
  KEY `activity_log_log_name_index` (`log_name`)
) ENGINE=MyISAM AUTO_INCREMENT=632 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `activity_log`
--

INSERT INTO `activity_log` (`id`, `log_name`, `description`, `subject_type`, `event`, `subject_id`, `causer_type`, `causer_id`, `properties`, `batch_uuid`, `created_at`, `updated_at`) VALUES
(1, 'default', 'created', 'App\\Models\\CategoriaMobiliario', 'created', 1, NULL, NULL, '{\"attributes\": {\"slug\": \"escritorios\", \"icono\": \"heroicon-o-computer-desktop\", \"orden\": 1, \"activo\": true, \"nombre\": \"Escritorios\", \"descripcion\": null}}', NULL, '2026-05-20 11:53:34', '2026-05-20 11:53:34'),
(2, 'default', 'created', 'App\\Models\\CategoriaMobiliario', 'created', 2, NULL, NULL, '{\"attributes\": {\"slug\": \"recepciones\", \"icono\": \"heroicon-o-building-office\", \"orden\": 2, \"activo\": true, \"nombre\": \"Recepciones\", \"descripcion\": null}}', NULL, '2026-05-20 11:53:34', '2026-05-20 11:53:34'),
(3, 'default', 'created', 'App\\Models\\CategoriaMobiliario', 'created', 3, NULL, NULL, '{\"attributes\": {\"slug\": \"gondolas\", \"icono\": \"heroicon-o-archive-box\", \"orden\": 3, \"activo\": true, \"nombre\": \"Góndolas\", \"descripcion\": null}}', NULL, '2026-05-20 11:53:34', '2026-05-20 11:53:34'),
(4, 'default', 'created', 'App\\Models\\CategoriaMobiliario', 'created', 4, NULL, NULL, '{\"attributes\": {\"slug\": \"exhibidores\", \"icono\": \"heroicon-o-presentation-chart-bar\", \"orden\": 4, \"activo\": true, \"nombre\": \"Exhibidores\", \"descripcion\": null}}', NULL, '2026-05-20 11:53:34', '2026-05-20 11:53:34'),
(5, 'default', 'created', 'App\\Models\\CategoriaMobiliario', 'created', 5, NULL, NULL, '{\"attributes\": {\"slug\": \"backoffice\", \"icono\": \"heroicon-o-briefcase\", \"orden\": 5, \"activo\": true, \"nombre\": \"Backoffice\", \"descripcion\": null}}', NULL, '2026-05-20 11:53:34', '2026-05-20 11:53:34'),
(6, 'default', 'created', 'App\\Models\\CategoriaMobiliario', 'created', 6, NULL, NULL, '{\"attributes\": {\"slug\": \"carteleria\", \"icono\": \"heroicon-o-megaphone\", \"orden\": 6, \"activo\": true, \"nombre\": \"Cartelería\", \"descripcion\": null}}', NULL, '2026-05-20 11:53:34', '2026-05-20 11:53:34'),
(7, 'default', 'created', 'App\\Models\\CategoriaMobiliario', 'created', 7, NULL, NULL, '{\"attributes\": {\"slug\": \"otras\", \"icono\": \"heroicon-o-ellipsis-horizontal\", \"orden\": 7, \"activo\": true, \"nombre\": \"Otras\", \"descripcion\": null}}', NULL, '2026-05-20 11:53:34', '2026-05-20 11:53:34'),
(8, 'default', 'created', 'App\\Models\\UnidadMedida', 'created', 1, NULL, NULL, '{\"attributes\": {\"activo\": true, \"nombre\": \"Metro\", \"abreviatura\": \"m\"}}', NULL, '2026-05-20 11:53:34', '2026-05-20 11:53:34'),
(9, 'default', 'created', 'App\\Models\\UnidadMedida', 'created', 2, NULL, NULL, '{\"attributes\": {\"activo\": true, \"nombre\": \"Centímetro\", \"abreviatura\": \"cm\"}}', NULL, '2026-05-20 11:53:34', '2026-05-20 11:53:34'),
(10, 'default', 'created', 'App\\Models\\UnidadMedida', 'created', 3, NULL, NULL, '{\"attributes\": {\"activo\": true, \"nombre\": \"Milímetro\", \"abreviatura\": \"mm\"}}', NULL, '2026-05-20 11:53:34', '2026-05-20 11:53:34'),
(11, 'default', 'created', 'App\\Models\\UnidadMedida', 'created', 4, NULL, NULL, '{\"attributes\": {\"activo\": true, \"nombre\": \"Metro cuadrado\", \"abreviatura\": \"m²\"}}', NULL, '2026-05-20 11:53:34', '2026-05-20 11:53:34'),
(12, 'default', 'created', 'App\\Models\\UnidadMedida', 'created', 5, NULL, NULL, '{\"attributes\": {\"activo\": true, \"nombre\": \"Kilogramo\", \"abreviatura\": \"kg\"}}', NULL, '2026-05-20 11:53:34', '2026-05-20 11:53:34'),
(13, 'default', 'created', 'App\\Models\\UnidadMedida', 'created', 6, NULL, NULL, '{\"attributes\": {\"activo\": true, \"nombre\": \"Gramo\", \"abreviatura\": \"g\"}}', NULL, '2026-05-20 11:53:34', '2026-05-20 11:53:34'),
(14, 'default', 'created', 'App\\Models\\UnidadMedida', 'created', 7, NULL, NULL, '{\"attributes\": {\"activo\": true, \"nombre\": \"Litro\", \"abreviatura\": \"L\"}}', NULL, '2026-05-20 11:53:34', '2026-05-20 11:53:34'),
(15, 'default', 'created', 'App\\Models\\UnidadMedida', 'created', 8, NULL, NULL, '{\"attributes\": {\"activo\": true, \"nombre\": \"Unidad\", \"abreviatura\": \"u\"}}', NULL, '2026-05-20 11:53:34', '2026-05-20 11:53:34'),
(16, 'default', 'created', 'App\\Models\\UnidadMedida', 'created', 9, NULL, NULL, '{\"attributes\": {\"activo\": true, \"nombre\": \"Par\", \"abreviatura\": \"par\"}}', NULL, '2026-05-20 11:53:34', '2026-05-20 11:53:34'),
(17, 'default', 'created', 'App\\Models\\UnidadMedida', 'created', 10, NULL, NULL, '{\"attributes\": {\"activo\": true, \"nombre\": \"Juego\", \"abreviatura\": \"juego\"}}', NULL, '2026-05-20 11:53:34', '2026-05-20 11:53:34'),
(18, 'default', 'created', 'App\\Models\\UnidadMedida', 'created', 11, NULL, NULL, '{\"attributes\": {\"activo\": true, \"nombre\": \"Rollo\", \"abreviatura\": \"rollo\"}}', NULL, '2026-05-20 11:53:34', '2026-05-20 11:53:34'),
(19, 'default', 'created', 'App\\Models\\UnidadMedida', 'created', 12, NULL, NULL, '{\"attributes\": {\"activo\": true, \"nombre\": \"Plancha\", \"abreviatura\": \"plancha\"}}', NULL, '2026-05-20 11:53:34', '2026-05-20 11:53:34'),
(20, 'default', 'created', 'App\\Models\\Marca', 'created', 1, 'App\\Models\\User', 1, '{\"attributes\": {\"logo\": \"marcas/logos/01KS2R06FZRP58N1RVHK1XGQ43.png\", \"activo\": true, \"nombre\": \"CHERY\", \"colores\": [{\"color\": \"#ffffff\"}]}}', NULL, '2026-05-20 13:08:03', '2026-05-20 13:08:03'),
(21, 'default', 'created', 'App\\Models\\Agencia', 'created', 1, 'App\\Models\\User', 1, '{\"attributes\": {\"activo\": true, \"nombre\": \"ROMA\", \"marca_id\": 1, \"direccion\": \"Av. Bartolome Mitre 441, M5600, San Rafael, Mendoza\", \"etiquetas\": \"\", \"prioridad\": 3, \"responsable\": \"Adrian Pablo Sat\", \"observaciones\": \"MOBILIARIO SALON\", \"codigo_interno\": \"CHR01\"}}', NULL, '2026-05-20 13:12:32', '2026-05-20 13:12:32'),
(22, 'default', 'created', 'App\\Models\\Agencia', 'created', 2, 'App\\Models\\User', 1, '{\"attributes\": {\"activo\": true, \"nombre\": \"ROMA\", \"marca_id\": 1, \"direccion\": \"Av. Bartolome Mitre 441, M5600, San Rafael, Mendoza\", \"etiquetas\": \"\", \"prioridad\": 3, \"responsable\": \"Adrian Pablo Sat\", \"observaciones\": \"MOBILIARIO SALON\", \"codigo_interno\": \"CHR01\"}}', NULL, '2026-05-20 13:23:37', '2026-05-20 13:23:37'),
(23, 'default', 'created', 'App\\Models\\CategoriaMobiliario', 'created', 8, 'App\\Models\\User', 1, '{\"attributes\": {\"slug\": \"mesa-baja\", \"icono\": null, \"orden\": 0, \"activo\": true, \"nombre\": \"Mesa Baja\", \"descripcion\": null}}', NULL, '2026-05-20 13:24:54', '2026-05-20 13:24:54'),
(24, 'default', 'created', 'App\\Models\\Mobiliario', 'created', 1, 'App\\Models\\User', 1, '{\"attributes\": {\"estado\": \"activo\", \"imagen\": null, \"nombre\": \"MESA NEGRA\", \"descripcion\": \"Tapa superior de melamina Antihuella con base metalica pintada a horno con pintura epoxi color negra\", \"categoria_id\": 8, \"observaciones\": \"Patas de Hierro negras\", \"codigo_interno\": \"CH23\", \"version_actual\": 1}}', NULL, '2026-05-20 13:37:05', '2026-05-20 13:37:05'),
(25, 'default', 'created', 'App\\Models\\CategoriaMobiliario', 'created', 9, 'App\\Models\\User', 1, '{\"attributes\": {\"slug\": \"muebletv\", \"icono\": null, \"orden\": 0, \"activo\": true, \"nombre\": \"MuebleTV\", \"descripcion\": null}}', NULL, '2026-05-20 13:38:25', '2026-05-20 13:38:25'),
(26, 'default', 'created', 'App\\Models\\Mobiliario', 'created', 2, 'App\\Models\\User', 1, '{\"attributes\": {\"estado\": \"activo\", \"imagen\": null, \"nombre\": \"Mueble Tv Doble 3600*2400\", \"descripcion\": \"Mueble de guardado BIFAZ, fabricado en melamina nogal terracota y partes en melamina negra, con puertas de abrir y espacios para guardado\", \"categoria_id\": 9, \"observaciones\": \"Va con cenefa de ajuste\", \"codigo_interno\": \"CH10\", \"version_actual\": 1}}', NULL, '2026-05-20 13:41:16', '2026-05-20 13:41:16'),
(27, 'default', 'created', 'App\\Models\\Mobiliario', 'created', 3, 'App\\Models\\User', 1, '{\"attributes\": {\"estado\": \"activo\", \"imagen\": null, \"nombre\": \"Mesa Escritorio\", \"descripcion\": \"Fabricado en melamina negra lisa antihuellas y cantos a 45° con base tipo piramidal segun manual.\", \"categoria_id\": 1, \"observaciones\": null, \"codigo_interno\": \"CH11\", \"version_actual\": 1}}', NULL, '2026-05-20 13:43:46', '2026-05-20 13:43:46'),
(28, 'default', 'created', 'App\\Models\\Proyecto', 'created', 1, 'App\\Models\\User', 1, '{\"attributes\": {\"estado\": \"presupuestar\", \"timeline\": null, \"agencia_id\": 2, \"fecha_inicio\": \"2026-03-09T03:00:00.000000Z\", \"observaciones\": null, \"codigo_interno\": \"CHROMA\", \"fecha_entrega_real\": null, \"fecha_entrega_estimada\": null}}', NULL, '2026-05-20 13:45:25', '2026-05-20 13:45:25'),
(29, 'default', 'created', 'App\\Models\\Presupuesto', 'created', 1, 'App\\Models\\User', 1, '{\"attributes\": {\"codigo\": \"PRES-2026-0001\", \"estado\": \"borrador\", \"version\": 1, \"aprobado_at\": null, \"proyecto_id\": 1, \"aprobado_por\": null, \"fecha_emision\": \"2026-05-20T03:00:00.000000Z\", \"observaciones\": null, \"notas_internas\": \"Las sillas para esta agencia, van aparte\", \"responsable_id\": 1, \"datos_adicionales\": null, \"fecha_vencimiento\": null}}', NULL, '2026-05-20 16:03:21', '2026-05-20 16:03:21'),
(30, 'default', 'created', 'App\\Models\\Presupuesto', 'created', 2, 'App\\Models\\User', 1, '{\"attributes\": {\"codigo\": \"PRES-2026-0002\", \"estado\": \"borrador\", \"version\": 1, \"aprobado_at\": null, \"proyecto_id\": 1, \"aprobado_por\": null, \"fecha_emision\": \"2026-05-20T03:00:00.000000Z\", \"observaciones\": null, \"notas_internas\": \"Las sillas para esta agencia, van aparte\", \"responsable_id\": 1, \"datos_adicionales\": null, \"fecha_vencimiento\": null}}', NULL, '2026-05-20 16:20:56', '2026-05-20 16:20:56'),
(31, 'default', 'created', 'App\\Models\\Presupuesto', 'created', 3, 'App\\Models\\User', 1, '{\"attributes\": {\"codigo\": \"PRES-2026-0003\", \"estado\": \"borrador\", \"version\": 1, \"aprobado_at\": null, \"proyecto_id\": 1, \"aprobado_por\": null, \"fecha_emision\": \"2026-05-20T03:00:00.000000Z\", \"observaciones\": null, \"notas_internas\": \"Las sillas para esta agencia, van aparte\", \"responsable_id\": 1, \"datos_adicionales\": null, \"fecha_vencimiento\": null}}', NULL, '2026-05-20 16:41:57', '2026-05-20 16:41:57'),
(32, 'default', 'created', 'App\\Models\\Presupuesto', 'created', 4, 'App\\Models\\User', 1, '{\"attributes\": {\"codigo\": \"PRES-2026-0004\", \"estado\": \"borrador\", \"version\": 1, \"aprobado_at\": null, \"proyecto_id\": 1, \"aprobado_por\": null, \"fecha_emision\": \"2026-05-20T03:00:00.000000Z\", \"observaciones\": null, \"notas_internas\": \"Las sillas para esta agencia, van aparte\", \"responsable_id\": 1, \"datos_adicionales\": null, \"fecha_vencimiento\": null}}', NULL, '2026-05-20 16:43:01', '2026-05-20 16:43:01'),
(33, 'default', 'created', 'App\\Models\\Presupuesto', 'created', 5, 'App\\Models\\User', 1, '{\"attributes\": {\"codigo\": \"PRES-2026-0005\", \"estado\": \"borrador\", \"version\": 1, \"aprobado_at\": null, \"proyecto_id\": 1, \"aprobado_por\": null, \"fecha_emision\": \"2026-05-20T03:00:00.000000Z\", \"observaciones\": null, \"notas_internas\": \"Las sillas para esta agencia, van aparte\", \"responsable_id\": 1, \"datos_adicionales\": null, \"fecha_vencimiento\": null}}', NULL, '2026-05-20 16:51:03', '2026-05-20 16:51:03'),
(34, 'default', 'updated', 'App\\Models\\Presupuesto', 'updated', 5, 'App\\Models\\User', 1, '{\"old\": {\"estado\": \"borrador\"}, \"attributes\": {\"estado\": \"en_revision\"}}', NULL, '2026-05-20 16:55:20', '2026-05-20 16:55:20'),
(35, 'default', 'deleted', 'App\\Models\\Presupuesto', 'deleted', 4, 'App\\Models\\User', 1, '{\"old\": {\"codigo\": \"PRES-2026-0004\", \"estado\": \"borrador\", \"version\": 1, \"aprobado_at\": null, \"proyecto_id\": 1, \"aprobado_por\": null, \"fecha_emision\": \"2026-05-20T03:00:00.000000Z\", \"observaciones\": null, \"notas_internas\": \"Las sillas para esta agencia, van aparte\", \"responsable_id\": 1, \"datos_adicionales\": null, \"fecha_vencimiento\": null}}', NULL, '2026-05-20 17:55:39', '2026-05-20 17:55:39'),
(36, 'default', 'deleted', 'App\\Models\\Presupuesto', 'deleted', 3, 'App\\Models\\User', 1, '{\"old\": {\"codigo\": \"PRES-2026-0003\", \"estado\": \"borrador\", \"version\": 1, \"aprobado_at\": null, \"proyecto_id\": 1, \"aprobado_por\": null, \"fecha_emision\": \"2026-05-20T03:00:00.000000Z\", \"observaciones\": null, \"notas_internas\": \"Las sillas para esta agencia, van aparte\", \"responsable_id\": 1, \"datos_adicionales\": null, \"fecha_vencimiento\": null}}', NULL, '2026-05-20 17:55:39', '2026-05-20 17:55:39'),
(37, 'default', 'deleted', 'App\\Models\\Presupuesto', 'deleted', 2, 'App\\Models\\User', 1, '{\"old\": {\"codigo\": \"PRES-2026-0002\", \"estado\": \"borrador\", \"version\": 1, \"aprobado_at\": null, \"proyecto_id\": 1, \"aprobado_por\": null, \"fecha_emision\": \"2026-05-20T03:00:00.000000Z\", \"observaciones\": null, \"notas_internas\": \"Las sillas para esta agencia, van aparte\", \"responsable_id\": 1, \"datos_adicionales\": null, \"fecha_vencimiento\": null}}', NULL, '2026-05-20 17:55:39', '2026-05-20 17:55:39'),
(38, 'default', 'deleted', 'App\\Models\\Presupuesto', 'deleted', 1, 'App\\Models\\User', 1, '{\"old\": {\"codigo\": \"PRES-2026-0001\", \"estado\": \"borrador\", \"version\": 1, \"aprobado_at\": null, \"proyecto_id\": 1, \"aprobado_por\": null, \"fecha_emision\": \"2026-05-20T03:00:00.000000Z\", \"observaciones\": null, \"notas_internas\": \"Las sillas para esta agencia, van aparte\", \"responsable_id\": 1, \"datos_adicionales\": null, \"fecha_vencimiento\": null}}', NULL, '2026-05-20 17:55:39', '2026-05-20 17:55:39'),
(39, 'default', 'created', 'App\\Models\\Insumo', 'created', 1, 'App\\Models\\User', 1, '{\"attributes\": {\"activo\": true, \"codigo\": \"INS-0001\", \"nombre\": \"Regaton\", \"ubicacion\": \"Deposito\", \"stock_minimo\": 200, \"observaciones\": null, \"unidad_medida_id\": 8}}', NULL, '2026-05-20 19:24:02', '2026-05-20 19:24:02'),
(40, 'default', 'updated', 'App\\Models\\Presupuesto', 'updated', 5, 'App\\Models\\User', 1, '{\"old\": {\"estado\": \"en_revision\"}, \"attributes\": {\"estado\": \"aprobado\"}}', NULL, '2026-05-20 19:33:24', '2026-05-20 19:33:24'),
(41, 'default', 'updated', 'App\\Models\\Presupuesto', 'updated', 5, 'App\\Models\\User', 1, '{\"old\": {\"aprobado_at\": null, \"aprobado_por\": null}, \"attributes\": {\"aprobado_at\": \"2026-05-20T19:33:24.000000Z\", \"aprobado_por\": 1}}', NULL, '2026-05-20 19:33:24', '2026-05-20 19:33:24'),
(42, 'default', 'updated', 'App\\Models\\Insumo', 'updated', 1, 'App\\Models\\User', 1, '{\"old\": {\"stock_actual\": 0}, \"attributes\": {\"stock_actual\": 100}}', NULL, '2026-05-21 11:01:58', '2026-05-21 11:01:58'),
(43, 'default', 'updated', 'App\\Models\\Presupuesto', 'updated', 5, 'App\\Models\\User', 1, '{\"old\": {\"estado\": \"confirmado\"}, \"attributes\": {\"estado\": \"pagado\"}}', NULL, '2026-05-21 11:56:57', '2026-05-21 11:56:57'),
(44, 'default', 'deleted', 'App\\Models\\Presupuesto', 'deleted', 5, 'App\\Models\\User', 1, '{\"old\": {\"codigo\": \"PRES-2026-0005\", \"estado\": \"pagado\", \"version\": 1, \"aprobado_at\": \"2026-05-20T19:33:24.000000Z\", \"proyecto_id\": 1, \"aprobado_por\": 1, \"fecha_emision\": \"2026-05-20T03:00:00.000000Z\", \"observaciones\": null, \"notas_internas\": \"Las sillas para esta agencia, van aparte\", \"responsable_id\": 1, \"datos_adicionales\": null, \"fecha_vencimiento\": null}}', NULL, '2026-05-21 11:58:19', '2026-05-21 11:58:19'),
(45, 'default', 'updated', 'App\\Models\\Proyecto', 'updated', 1, 'App\\Models\\User', 1, '{\"old\": {\"marca_id\": null}, \"attributes\": {\"marca_id\": 1}}', NULL, '2026-05-21 14:35:14', '2026-05-21 14:35:14'),
(46, 'default', 'created', 'App\\Models\\Marca', 'created', 2, 'App\\Models\\User', 1, '{\"attributes\": {\"logo\": \"marcas/logos/01KS5VQF3JFCQ7VQAC3K6JSP90.png\", \"activo\": true, \"nombre\": \"Jetour\", \"colores\": [{\"color\": \"#404040\"}], \"manual_pdf\": null}}', NULL, '2026-05-21 18:10:55', '2026-05-21 18:10:55'),
(47, 'default', 'created', 'App\\Models\\Marca', 'created', 3, 'App\\Models\\User', 1, '{\"attributes\": {\"logo\": \"marcas/logos/01KS5VQW2B5NFKGFJFB8R7SJ18.png\", \"activo\": true, \"nombre\": \"Kawasaki\", \"colores\": [{\"color\": \"#1ad100\"}], \"manual_pdf\": null}}', NULL, '2026-05-21 18:11:08', '2026-05-21 18:11:08'),
(48, 'default', 'created', 'App\\Models\\Marca', 'created', 4, 'App\\Models\\User', 1, '{\"attributes\": {\"logo\": \"marcas/logos/01KS5VR7RPRBS558RA8QDBANRA.png\", \"activo\": true, \"nombre\": \"Zanella\", \"colores\": [{\"color\": \"#e30000\"}], \"manual_pdf\": null}}', NULL, '2026-05-21 18:11:20', '2026-05-21 18:11:20'),
(49, 'default', 'created', 'App\\Models\\Marca', 'created', 5, 'App\\Models\\User', 1, '{\"attributes\": {\"logo\": \"marcas/logos/01KS5VRS7VA92X0VHRERBX0C1Z.jpg\", \"activo\": true, \"nombre\": \"Renault\", \"colores\": [{\"color\": \"#ffffff\"}], \"manual_pdf\": null}}', NULL, '2026-05-21 18:11:38', '2026-05-21 18:11:38'),
(50, 'default', 'created', 'App\\Models\\Marca', 'created', 6, 'App\\Models\\User', 1, '{\"attributes\": {\"logo\": \"marcas/logos/01KS5VS55PG4YQZZHW4X62G91C.png\", \"activo\": true, \"nombre\": \"Triumph\", \"colores\": [{\"color\": \"#000000\"}], \"manual_pdf\": null}}', NULL, '2026-05-21 18:11:50', '2026-05-21 18:11:50'),
(51, 'default', 'created', 'App\\Models\\Marca', 'created', 7, 'App\\Models\\User', 1, '{\"attributes\": {\"logo\": \"marcas/logos/01KS5VSGFCH7MQGRSN0G47GVM1.png\", \"activo\": true, \"nombre\": \"Nissan\", \"colores\": [{\"color\": \"#7d7d7d\"}], \"manual_pdf\": null}}', NULL, '2026-05-21 18:12:02', '2026-05-21 18:12:02'),
(52, 'default', 'created', 'App\\Models\\Marca', 'created', 8, 'App\\Models\\User', 1, '{\"attributes\": {\"logo\": \"marcas/logos/01KS5VSZM11122SDH3S9JETVNN.jpg\", \"activo\": true, \"nombre\": \"Chevrolet EV\", \"colores\": [{\"color\": \"#0100f2\"}], \"manual_pdf\": null}}', NULL, '2026-05-21 18:12:17', '2026-05-21 18:12:17'),
(53, 'default', 'created', 'App\\Models\\Marca', 'created', 9, 'App\\Models\\User', 1, '{\"attributes\": {\"logo\": \"marcas/logos/01KS5VTNJHCDNTTEQB4R2T9HHR.png\", \"activo\": true, \"nombre\": \"Chevrolet\", \"colores\": [{\"color\": \"#3d3d3d\"}], \"manual_pdf\": null}}', NULL, '2026-05-21 18:12:40', '2026-05-21 18:12:40'),
(54, 'default', 'created', 'App\\Models\\Marca', 'created', 10, 'App\\Models\\User', 1, '{\"attributes\": {\"logo\": \"marcas/logos/01KS5VV6656309VKAH734FH53A.png\", \"activo\": true, \"nombre\": \"Leap\", \"colores\": [{\"color\": \"#4d4d4d\"}], \"manual_pdf\": null}}', NULL, '2026-05-21 18:12:57', '2026-05-21 18:12:57'),
(55, 'default', 'created', 'App\\Models\\Proyecto', 'created', 2, 'App\\Models\\User', 1, '{\"attributes\": {\"estado\": \"presupuestar\", \"marca_id\": 8, \"timeline\": null, \"agencia_id\": null, \"manual_pdf\": null, \"fecha_inicio\": null, \"observaciones\": null, \"codigo_interno\": \"CHEVROLET EV\", \"fecha_entrega_real\": null, \"fecha_entrega_estimada\": null}}', NULL, '2026-05-21 18:14:27', '2026-05-21 18:14:27'),
(56, 'default', 'created', 'App\\Models\\Agencia', 'created', 3, 'App\\Models\\User', 1, '{\"attributes\": {\"activo\": true, \"nombre\": \"Pesado Castro\", \"marca_id\": 8, \"direccion\": null, \"etiquetas\": \"\", \"prioridad\": 3, \"responsable\": null, \"observaciones\": null, \"codigo_interno\": \"PESCAS\"}}', NULL, '2026-05-21 18:15:38', '2026-05-21 18:15:38'),
(57, 'default', 'created', 'App\\Models\\Presupuesto', 'created', 6, 'App\\Models\\User', 1, '{\"attributes\": {\"codigo\": \"PRES-2026-0002\", \"estado\": \"borrador\", \"version\": 1, \"agencia_id\": 3, \"aprobado_at\": null, \"proyecto_id\": 2, \"aprobado_por\": null, \"fecha_emision\": \"2026-05-21T03:00:00.000000Z\", \"observaciones\": null, \"notas_internas\": null, \"responsable_id\": 1, \"datos_adicionales\": null, \"fecha_vencimiento\": null}}', NULL, '2026-05-21 18:15:48', '2026-05-21 18:15:48'),
(58, 'default', 'deleted', 'App\\Models\\Agencia', 'deleted', 3, 'App\\Models\\User', 1, '{\"old\": {\"activo\": true, \"nombre\": \"Pesado Castro\", \"marca_id\": 8, \"direccion\": null, \"etiquetas\": \"\", \"prioridad\": 3, \"responsable\": null, \"observaciones\": null, \"codigo_interno\": \"PESCAS\"}}', NULL, '2026-05-21 19:40:02', '2026-05-21 19:40:02'),
(59, 'default', 'deleted', 'App\\Models\\Marca', 'deleted', 8, 'App\\Models\\User', 1, '{\"old\": {\"logo\": \"marcas/logos/01KS5VSZM11122SDH3S9JETVNN.jpg\", \"activo\": true, \"nombre\": \"Chevrolet EV\", \"colores\": [{\"color\": \"#0100f2\"}], \"manual_pdf\": null}}', NULL, '2026-05-21 19:40:17', '2026-05-21 19:40:17'),
(60, 'default', 'created', 'App\\Models\\Marca', 'created', 11, 'App\\Models\\User', 1, '{\"attributes\": {\"logo\": \"marcas/logos/01KS60VWEFGJDVPB4A3KSVYB9F.jpg\", \"activo\": true, \"nombre\": \"Chevrolet ev\", \"colores\": [{\"color\": \"#2b00d6\"}], \"manual_pdf\": null}}', NULL, '2026-05-21 19:40:42', '2026-05-21 19:40:42'),
(61, 'default', 'created', 'App\\Models\\Agencia', 'created', 4, 'App\\Models\\User', 1, '{\"attributes\": {\"activo\": true, \"nombre\": \"Prueba\", \"marca_id\": 11, \"direccion\": \"Prueba 123\", \"etiquetas\": \"\", \"prioridad\": 3, \"responsable\": \"Prueba\", \"observaciones\": null, \"codigo_interno\": \"chev-01\"}}', NULL, '2026-05-21 19:41:17', '2026-05-21 19:41:17'),
(62, 'default', 'deleted', 'App\\Models\\Presupuesto', 'deleted', 6, 'App\\Models\\User', 1, '{\"old\": {\"codigo\": \"PRES-2026-0002\", \"estado\": \"borrador\", \"version\": 1, \"agencia_id\": 3, \"aprobado_at\": null, \"proyecto_id\": 2, \"aprobado_por\": null, \"fecha_emision\": \"2026-05-21T03:00:00.000000Z\", \"observaciones\": null, \"notas_internas\": null, \"responsable_id\": 1, \"datos_adicionales\": null, \"fecha_vencimiento\": null}}', NULL, '2026-05-21 19:42:31', '2026-05-21 19:42:31'),
(63, 'default', 'deleted', 'App\\Models\\Agencia', 'deleted', 4, 'App\\Models\\User', 1, '{\"old\": {\"activo\": true, \"nombre\": \"Prueba\", \"marca_id\": 11, \"direccion\": \"Prueba 123\", \"etiquetas\": \"\", \"prioridad\": 3, \"responsable\": \"Prueba\", \"observaciones\": null, \"codigo_interno\": \"chev-01\"}}', NULL, '2026-05-21 19:42:43', '2026-05-21 19:42:43'),
(64, 'default', 'deleted', 'App\\Models\\Agencia', 'deleted', 2, 'App\\Models\\User', 1, '{\"old\": {\"activo\": true, \"nombre\": \"ROMA\", \"marca_id\": 1, \"direccion\": \"Av. Bartolome Mitre 441, M5600, San Rafael, Mendoza\", \"etiquetas\": \"\", \"prioridad\": 3, \"responsable\": \"Adrian Pablo Sat\", \"observaciones\": \"MOBILIARIO SALON\", \"codigo_interno\": \"CHR01\"}}', NULL, '2026-05-21 19:42:46', '2026-05-21 19:42:46'),
(65, 'default', 'deleted', 'App\\Models\\Proyecto', 'deleted', 2, 'App\\Models\\User', 1, '{\"old\": {\"estado\": \"presupuestar\", \"marca_id\": 8, \"timeline\": null, \"agencia_id\": null, \"manual_pdf\": null, \"fecha_inicio\": null, \"observaciones\": null, \"codigo_interno\": \"CHEVROLET EV\", \"fecha_entrega_real\": null, \"fecha_entrega_estimada\": null}}', NULL, '2026-05-21 19:43:02', '2026-05-21 19:43:02'),
(66, 'default', 'created', 'App\\Models\\Proyecto', 'created', 3, 'App\\Models\\User', 1, '{\"attributes\": {\"estado\": \"presupuestar\", \"marca_id\": 11, \"timeline\": null, \"agencia_id\": null, \"manual_pdf\": null, \"fecha_inicio\": null, \"observaciones\": null, \"codigo_interno\": \"CHEV-01\", \"fecha_entrega_real\": null, \"fecha_entrega_estimada\": null}}', NULL, '2026-05-21 19:43:36', '2026-05-21 19:43:36'),
(67, 'default', 'created', 'App\\Models\\Agencia', 'created', 5, 'App\\Models\\User', 1, '{\"attributes\": {\"activo\": true, \"nombre\": \"Prueba\", \"marca_id\": 11, \"direccion\": \"Prueba 123\", \"etiquetas\": \"etiqueta ch\", \"prioridad\": 3, \"responsable\": \"Prueba\", \"observaciones\": \"Una obs\", \"codigo_interno\": \"Prueba\"}}', NULL, '2026-05-21 19:44:14', '2026-05-21 19:44:14'),
(68, 'default', 'created', 'App\\Models\\Presupuesto', 'created', 7, 'App\\Models\\User', 1, '{\"attributes\": {\"codigo\": \"PRES-2026-0003\", \"estado\": \"borrador\", \"version\": 1, \"agencia_id\": 5, \"aprobado_at\": null, \"proyecto_id\": 3, \"aprobado_por\": null, \"fecha_emision\": \"2026-05-21T03:00:00.000000Z\", \"observaciones\": null, \"notas_internas\": null, \"responsable_id\": 1, \"datos_adicionales\": null, \"fecha_vencimiento\": null}}', NULL, '2026-05-21 19:44:39', '2026-05-21 19:44:39'),
(69, 'default', 'deleted', 'App\\Models\\Proyecto', 'deleted', 1, 'App\\Models\\User', 1, '{\"old\": {\"estado\": \"presupuestar\", \"marca_id\": 1, \"timeline\": null, \"manual_pdf\": null, \"fecha_inicio\": \"2026-03-09T03:00:00.000000Z\", \"observaciones\": null, \"codigo_interno\": \"CHERY\", \"fecha_entrega_real\": null, \"fecha_entrega_estimada\": null}}', NULL, '2026-05-22 11:06:26', '2026-05-22 11:06:26'),
(70, 'default', 'deleted', 'App\\Models\\Proyecto', 'deleted', 3, 'App\\Models\\User', 1, '{\"old\": {\"estado\": \"presupuestar\", \"marca_id\": 11, \"timeline\": null, \"manual_pdf\": null, \"fecha_inicio\": null, \"observaciones\": null, \"codigo_interno\": \"CHEV-01\", \"fecha_entrega_real\": null, \"fecha_entrega_estimada\": null}}', NULL, '2026-05-22 11:06:30', '2026-05-22 11:06:30'),
(71, 'default', 'deleted', 'App\\Models\\Presupuesto', 'deleted', 7, 'App\\Models\\User', 1, '{\"old\": {\"codigo\": \"PRES-2026-0003\", \"estado\": \"borrador\", \"version\": 1, \"agencia_id\": 5, \"aprobado_at\": null, \"aprobado_por\": null, \"fecha_emision\": \"2026-05-21T03:00:00.000000Z\", \"observaciones\": null, \"notas_internas\": null, \"responsable_id\": 1, \"datos_adicionales\": null, \"fecha_vencimiento\": null}}', NULL, '2026-05-22 11:06:39', '2026-05-22 11:06:39'),
(72, 'default', 'created', 'App\\Models\\Proyecto', 'created', 4, 'App\\Models\\User', 1, '{\"attributes\": {\"estado\": \"presupuestar\", \"marca_id\": 1, \"timeline\": null, \"manual_pdf\": null, \"fecha_inicio\": null, \"observaciones\": null, \"codigo_interno\": \"CHERY25-26\", \"fecha_entrega_real\": null, \"fecha_entrega_estimada\": null}}', NULL, '2026-05-22 11:34:49', '2026-05-22 11:34:49'),
(73, 'default', 'updated', 'App\\Models\\Proyecto', 'updated', 4, 'App\\Models\\User', 1, '{\"old\": {\"manual_pdf\": null}, \"attributes\": {\"manual_pdf\": \"proyectos/manuales/01KS7RKVKV0SCVPR66F1V1JR4S.pdf\"}}', NULL, '2026-05-22 11:55:00', '2026-05-22 11:55:00'),
(74, 'default', 'updated', 'App\\Models\\Proyecto', 'updated', 4, 'App\\Models\\User', 1, '{\"old\": {\"manual_pdf\": [\"proyectos/manuales/01KS7RKVKV0SCVPR66F1V1JR4S.pdf\"]}, \"attributes\": {\"manual_pdf\": [\"proyectos/manuales/e33029f8-f77c-4456-9ddc-7f8fac289686_2025.11.11_Mueble Vendedores Chery.pdf\", \"proyectos/manuales/dcda57af-c349-43bf-9f2d-6971f72f9f31_28 8 25 AGREGADOS.pdf\"]}}', NULL, '2026-05-22 12:35:00', '2026-05-22 12:35:00'),
(75, 'default', 'created', 'App\\Models\\Insumo', 'created', 2, 'App\\Models\\User', 1, '{\"attributes\": {\"activo\": true, \"codigo\": \"INS-0002\", \"nombre\": \"Pata Multimarcas\", \"ubicacion\": \"Deposito - Cuadrante 3\", \"precio_costo\": null, \"stock_actual\": 3, \"stock_minimo\": 5, \"observaciones\": \"COLOR GRAFITO - SIMPLE\", \"unidad_medida_id\": 8}}', NULL, '2026-05-22 16:54:20', '2026-05-22 16:54:20'),
(76, 'default', 'updated', 'App\\Models\\Insumo', 'updated', 2, 'App\\Models\\User', 1, '{\"old\": {\"tag\": null}, \"attributes\": {\"tag\": [\"Patas\"]}}', NULL, '2026-05-22 17:09:03', '2026-05-22 17:09:03'),
(77, 'default', 'updated', 'App\\Models\\Insumo', 'updated', 1, 'App\\Models\\User', 1, '{\"old\": {\"tag\": null}, \"attributes\": {\"tag\": [\"Insumo General\"]}}', NULL, '2026-05-22 19:04:17', '2026-05-22 19:04:17'),
(78, 'default', 'deleted', 'App\\Models\\Agencia', 'deleted', 5, 'App\\Models\\User', 1, '{\"old\": {\"activo\": true, \"nombre\": \"Prueba\", \"direccion\": \"Prueba 123\", \"etiquetas\": \"etiqueta ch\", \"prioridad\": 3, \"proyecto_id\": null, \"responsable\": \"Prueba\", \"observaciones\": \"Una obs\"}}', NULL, '2026-05-22 19:10:45', '2026-05-22 19:10:45'),
(79, 'default', 'created', 'App\\Models\\Agencia', 'created', 6, 'App\\Models\\User', 1, '{\"attributes\": {\"activo\": true, \"nombre\": \"ROMA\", \"direccion\": \"Av. Bartolome Mitre 441, M5600, San Rafael , Mendoza\", \"etiquetas\": \"\", \"prioridad\": 3, \"proyecto_id\": 4, \"responsable\": \"Adrian Pablo Sat\", \"observaciones\": null}}', NULL, '2026-05-22 19:12:03', '2026-05-22 19:12:03'),
(80, 'default', 'created', 'App\\Models\\Presupuesto', 'created', 8, 'App\\Models\\User', 1, '{\"attributes\": {\"codigo\": \"PRES-2026-0004\", \"estado\": \"borrador\", \"version\": 1, \"agencia_id\": 6, \"aprobado_at\": null, \"aprobado_por\": null, \"fecha_emision\": \"2026-05-22T03:00:00.000000Z\", \"observaciones\": null, \"notas_internas\": null, \"responsable_id\": 1, \"datos_adicionales\": null, \"fecha_vencimiento\": null}}', NULL, '2026-05-22 19:13:47', '2026-05-22 19:13:47'),
(81, 'default', 'updated', 'App\\Models\\Mobiliario', 'updated', 1, 'App\\Models\\User', 1, '{\"old\": {\"marca_id\": null}, \"attributes\": {\"marca_id\": 1}}', NULL, '2026-05-26 11:29:33', '2026-05-26 11:29:33'),
(82, 'default', 'updated', 'App\\Models\\Agencia', 'updated', 6, 'App\\Models\\User', 1, '{\"old\": {\"ciudad_id\": null, \"provincia_id\": null}, \"attributes\": {\"ciudad_id\": 42, \"provincia_id\": 13}}', NULL, '2026-05-26 16:02:58', '2026-05-26 16:02:58'),
(83, 'default', 'deleted', 'App\\Models\\Proyecto', 'deleted', 4, 'App\\Models\\User', 1, '{\"old\": {\"estado\": \"presupuestar\", \"marca_id\": 1, \"timeline\": null, \"manual_pdf\": [\"proyectos/manuales/e33029f8-f77c-4456-9ddc-7f8fac289686_2025.11.11_Mueble Vendedores Chery.pdf\", \"proyectos/manuales/dcda57af-c349-43bf-9f2d-6971f72f9f31_28 8 25 AGREGADOS.pdf\"], \"fecha_inicio\": null, \"observaciones\": null, \"codigo_interno\": \"CHERY25-26\", \"fecha_entrega_real\": null, \"fecha_entrega_estimada\": null}}', NULL, '2026-05-26 17:00:16', '2026-05-26 17:00:16'),
(84, 'default', 'deleted', 'App\\Models\\Presupuesto', 'deleted', 8, 'App\\Models\\User', 1, '{\"old\": {\"codigo\": \"PRES-2026-0004\", \"estado\": \"borrador\", \"version\": 1, \"agencia_id\": 6, \"aprobado_at\": null, \"aprobado_por\": null, \"fecha_emision\": \"2026-05-22T03:00:00.000000Z\", \"observaciones\": null, \"notas_internas\": null, \"responsable_id\": 1, \"datos_adicionales\": null, \"fecha_vencimiento\": null}}', NULL, '2026-05-26 17:00:44', '2026-05-26 17:00:44'),
(85, 'default', 'created', 'App\\Models\\Mobiliario', 'created', 4, 'App\\Models\\User', 1, '{\"attributes\": {\"estado\": \"activo\", \"imagen\": null, \"nombre\": \"MESA BAJA\", \"marca_id\": 1, \"descripcion\": \"Mesa de melamina revestida en solido simil marmol y negro Antihuellas\", \"categoria_id\": 8, \"observaciones\": null, \"codigo_interno\": \"CH05\", \"version_actual\": 1}}', NULL, '2026-05-26 17:06:07', '2026-05-26 17:06:07'),
(86, 'default', 'deleted', 'App\\Models\\Agencia', 'deleted', 6, 'App\\Models\\User', 1, '{\"old\": {\"activo\": true, \"nombre\": \"ROMA\", \"ciudad_id\": 42, \"direccion\": \"Av. Bartolome Mitre 441, M5600, San Rafael , Mendoza\", \"etiquetas\": \"\", \"prioridad\": 3, \"proyecto_id\": 4, \"responsable\": \"Adrian Pablo Sat\", \"provincia_id\": 13, \"observaciones\": null}}', NULL, '2026-05-26 17:07:00', '2026-05-26 17:07:00'),
(87, 'default', 'updated', 'App\\Models\\Mobiliario', 'updated', 2, 'App\\Models\\User', 1, '{\"old\": {\"marca_id\": null}, \"attributes\": {\"marca_id\": 1}}', NULL, '2026-05-26 17:11:27', '2026-05-26 17:11:27'),
(88, 'default', 'updated', 'App\\Models\\Mobiliario', 'updated', 3, 'App\\Models\\User', 1, '{\"old\": {\"marca_id\": null}, \"attributes\": {\"marca_id\": 1}}', NULL, '2026-05-26 17:11:36', '2026-05-26 17:11:36'),
(89, 'default', 'created', 'App\\Models\\Proyecto', 'created', 5, 'App\\Models\\User', 1, '{\"attributes\": {\"estado\": \"presupuestar\", \"marca_id\": 1, \"timeline\": null, \"manual_pdf\": [\"proyectos/manuales/23b9e830-dfaa-4eb6-a2ab-d87f0ab286c0_2025.08.22_PRESUPUESTO MOBILIARIO CHERY.pdf\", \"proyectos/manuales/9e79a645-9d8a-4fc6-b670-6fbd5b256a12_MOBILIARIO CHERY SOLO MUEBLES.pdf\"], \"fecha_inicio\": null, \"observaciones\": null, \"codigo_interno\": \"CHERY-25-26\", \"fecha_entrega_real\": null, \"fecha_entrega_estimada\": null}}', NULL, '2026-05-26 17:12:25', '2026-05-26 17:12:25'),
(90, 'default', 'created', 'App\\Models\\Agencia', 'created', 7, 'App\\Models\\User', 1, '{\"attributes\": {\"activo\": true, \"nombre\": \"ROMA\", \"ciudad_id\": 42, \"direccion\": \"Av. Bartolome Mitre 441, M5600\", \"etiquetas\": \"\", \"prioridad\": 3, \"proyecto_id\": 5, \"responsable\": \"Adrian Pablo Sat\", \"provincia_id\": 13, \"observaciones\": null}}', NULL, '2026-05-26 17:16:24', '2026-05-26 17:16:24'),
(91, 'default', 'created', 'App\\Models\\Presupuesto', 'created', 10, 'App\\Models\\User', 1, '{\"attributes\": {\"codigo\": \"PRES-2026-0001\", \"estado\": \"borrador\", \"version\": 1, \"agencia_id\": 7, \"aprobado_at\": null, \"aprobado_por\": null, \"fecha_emision\": \"2026-05-26T03:00:00.000000Z\", \"observaciones\": \"Agencia en construccion / Cambio de mobiliario\", \"notas_internas\": \"Es el pesado de MYM\", \"responsable_id\": 1, \"datos_adicionales\": null, \"fecha_vencimiento\": null}}', NULL, '2026-05-26 17:21:06', '2026-05-26 17:21:06'),
(92, 'default', 'created', 'App\\Models\\Agencia', 'created', 8, 'App\\Models\\User', 1, '{\"attributes\": {\"activo\": true, \"nombre\": \"ROMA\", \"ciudad_id\": null, \"direccion\": null, \"etiquetas\": \"\", \"prioridad\": 3, \"proyecto_id\": 5, \"responsable\": \"A\", \"provincia_id\": null, \"observaciones\": null}}', NULL, '2026-05-26 17:34:05', '2026-05-26 17:34:05'),
(93, 'default', 'updated', 'App\\Models\\Agencia', 'updated', 8, 'App\\Models\\User', 1, '{\"old\": {\"ciudad_id\": null, \"direccion\": null, \"responsable\": \"A\", \"provincia_id\": null}, \"attributes\": {\"ciudad_id\": 42, \"direccion\": \"Av. Bartolome Mitre 441, M5600\", \"responsable\": \"Adrian Pablo Sat\", \"provincia_id\": 13}}', NULL, '2026-05-26 17:34:31', '2026-05-26 17:34:31'),
(94, 'default', 'updated', 'App\\Models\\Insumo', 'updated', 2, 'App\\Models\\User', 1, '{\"old\": {\"stock_actual\": 3}, \"attributes\": {\"stock_actual\": 7}}', NULL, '2026-05-27 12:54:24', '2026-05-27 12:54:24'),
(95, 'default', 'updated', 'App\\Models\\Presupuesto', 'updated', 10, 'App\\Models\\User', 4, '{\"old\": {\"estado\": \"borrador\"}, \"attributes\": {\"estado\": \"en_revision\"}}', NULL, '2026-05-27 19:43:10', '2026-05-27 19:43:10'),
(96, 'default', 'updated', 'App\\Models\\Presupuesto', 'updated', 10, 'App\\Models\\User', 4, '{\"old\": {\"estado\": \"en_revision\"}, \"attributes\": {\"estado\": \"aprobado\"}}', NULL, '2026-05-27 19:43:14', '2026-05-27 19:43:14'),
(97, 'default', 'updated', 'App\\Models\\Presupuesto', 'updated', 10, 'App\\Models\\User', 4, '{\"old\": {\"aprobado_at\": null, \"aprobado_por\": null}, \"attributes\": {\"aprobado_at\": \"2026-05-27T19:43:14.000000Z\", \"aprobado_por\": 4}}', NULL, '2026-05-27 19:43:14', '2026-05-27 19:43:14'),
(98, 'default', 'created', 'App\\Models\\CategoriaInsumo', 'created', 1, 'App\\Models\\User', 1, '{\"attributes\": {\"activo\": true, \"nombre\": \"Sillas\"}}', NULL, '2026-05-28 11:01:27', '2026-05-28 11:01:27'),
(99, 'default', 'created', 'App\\Models\\CategoriaInsumo', 'created', 2, 'App\\Models\\User', 1, '{\"attributes\": {\"activo\": true, \"nombre\": \"Patas\"}}', NULL, '2026-05-28 11:01:33', '2026-05-28 11:01:33'),
(100, 'default', 'created', 'App\\Models\\TipoSilla', 'created', 1, 'App\\Models\\User', 1, '{\"attributes\": {\"activo\": true, \"nombre\": \"Silla de Vendedor\"}}', NULL, '2026-05-28 11:10:07', '2026-05-28 11:10:07'),
(101, 'default', 'created', 'App\\Models\\CategoriaInsumo', 'created', 3, 'App\\Models\\User', 1, '{\"attributes\": {\"activo\": true, \"nombre\": \"Insumo General\"}}', NULL, '2026-05-28 14:17:44', '2026-05-28 14:17:44'),
(102, 'default', 'updated', 'App\\Models\\Insumo', 'updated', 1, 'App\\Models\\User', 1, '{\"old\": {\"categoria_insumo_id\": null}, \"attributes\": {\"categoria_insumo_id\": 3}}', NULL, '2026-05-28 14:17:49', '2026-05-28 14:17:49'),
(103, 'default', 'updated', 'App\\Models\\Insumo', 'updated', 2, 'App\\Models\\User', 1, '{\"old\": {\"categoria_insumo_id\": null}, \"attributes\": {\"categoria_insumo_id\": 2}}', NULL, '2026-05-28 14:18:05', '2026-05-28 14:18:05'),
(104, 'default', 'created', 'App\\Models\\CategoriaMobiliario', 'created', 10, 'App\\Models\\User', 1, '{\"attributes\": {\"slug\": \"sillas\", \"icono\": null, \"orden\": 0, \"activo\": true, \"nombre\": \"Sillas\", \"descripcion\": null}}', NULL, '2026-05-28 15:08:55', '2026-05-28 15:08:55'),
(105, 'default', 'created', 'App\\Models\\Insumo', 'created', 3, 'App\\Models\\User', 1, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0003\", \"nombre\": \"SILLA TOKIO\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": 3, \"stock_actual\": 5, \"stock_minimo\": 3, \"observaciones\": null, \"tipo_silla_id\": 1, \"unidad_medida_id\": 8, \"categoria_insumo_id\": 1}}', NULL, '2026-05-28 18:23:17', '2026-05-28 18:23:17'),
(106, 'default', 'created', 'App\\Models\\CategoriaInsumo', 'created', 4, 'App\\Models\\User', 1, '{\"attributes\": {\"activo\": true, \"nombre\": \"Categoria 2\"}}', NULL, '2026-05-29 12:09:09', '2026-05-29 12:09:09'),
(107, 'default', 'deleted', 'App\\Models\\Presupuesto', 'deleted', 10, 'App\\Models\\User', 1, '{\"old\": {\"codigo\": \"PRES-2026-0001\", \"estado\": \"aprobado\", \"version\": 1, \"agencia_id\": 8, \"aprobado_at\": \"2026-05-27T19:43:14.000000Z\", \"aprobado_por\": 4, \"fecha_emision\": \"2026-05-26T03:00:00.000000Z\", \"observaciones\": \"Agencia en construccion / Cambio de mobiliario\", \"notas_internas\": \"Es el pesado de MYM\", \"responsable_id\": 1, \"datos_adicionales\": null, \"fecha_vencimiento\": null}}', NULL, '2026-06-01 12:11:08', '2026-06-01 12:11:08'),
(108, 'default', 'deleted', 'App\\Models\\Proyecto', 'deleted', 5, 'App\\Models\\User', 1, '{\"old\": {\"estado\": \"presupuestar\", \"marca_id\": 1, \"timeline\": null, \"manual_pdf\": [\"proyectos/manuales/23b9e830-dfaa-4eb6-a2ab-d87f0ab286c0_2025.08.22_PRESUPUESTO MOBILIARIO CHERY.pdf\", \"proyectos/manuales/9e79a645-9d8a-4fc6-b670-6fbd5b256a12_MOBILIARIO CHERY SOLO MUEBLES.pdf\"], \"fecha_inicio\": null, \"observaciones\": null, \"codigo_interno\": \"CHERY-25-26\", \"fecha_entrega_real\": null, \"fecha_entrega_estimada\": null}}', NULL, '2026-06-01 12:11:18', '2026-06-01 12:11:18'),
(109, 'default', 'deleted', 'App\\Models\\Agencia', 'deleted', 8, 'App\\Models\\User', 1, '{\"old\": {\"activo\": true, \"nombre\": \"ROMA\", \"ciudad_id\": 42, \"direccion\": \"Av. Bartolome Mitre 441, M5600\", \"etiquetas\": \"\", \"prioridad\": 3, \"proyecto_id\": 5, \"responsable\": \"Adrian Pablo Sat\", \"provincia_id\": 13, \"observaciones\": null}}', NULL, '2026-06-01 12:11:23', '2026-06-01 12:11:23'),
(110, 'default', 'deleted', 'App\\Models\\Mobiliario', 'deleted', 2, 'App\\Models\\User', 1, '{\"old\": {\"estado\": \"activo\", \"imagen\": null, \"nombre\": \"Mueble Tv Doble 3600*2400\", \"precio\": null, \"marca_id\": 1, \"descripcion\": \"Mueble de guardado BIFAZ, fabricado en melamina nogal terracota y partes en melamina negra, con puertas de abrir y espacios para guardado\", \"categoria_id\": 9, \"observaciones\": \"Va con cenefa de ajuste\", \"codigo_interno\": \"CH10\", \"version_actual\": 1}}', NULL, '2026-06-01 12:11:33', '2026-06-01 12:11:33'),
(111, 'default', 'deleted', 'App\\Models\\Mobiliario', 'deleted', 1, 'App\\Models\\User', 1, '{\"old\": {\"estado\": \"activo\", \"imagen\": null, \"nombre\": \"MESA NEGRA\", \"precio\": null, \"marca_id\": 1, \"descripcion\": \"Tapa superior de melamina Antihuella con base metalica pintada a horno con pintura epoxi color negra\", \"categoria_id\": 8, \"observaciones\": \"Patas de Hierro negras\", \"codigo_interno\": \"CH23\", \"version_actual\": 1}}', NULL, '2026-06-01 12:11:39', '2026-06-01 12:11:39'),
(112, 'default', 'deleted', 'App\\Models\\Mobiliario', 'deleted', 3, 'App\\Models\\User', 1, '{\"old\": {\"estado\": \"activo\", \"imagen\": null, \"nombre\": \"Mesa Escritorio\", \"precio\": null, \"marca_id\": 1, \"descripcion\": \"Fabricado en melamina negra lisa antihuellas y cantos a 45° con base tipo piramidal segun manual.\", \"categoria_id\": 1, \"observaciones\": null, \"codigo_interno\": \"CH11\", \"version_actual\": 1}}', NULL, '2026-06-01 12:11:39', '2026-06-01 12:11:39'),
(113, 'default', 'deleted', 'App\\Models\\Mobiliario', 'deleted', 4, 'App\\Models\\User', 1, '{\"old\": {\"estado\": \"activo\", \"imagen\": null, \"nombre\": \"MESA BAJA\", \"precio\": null, \"marca_id\": 1, \"descripcion\": \"Mesa de melamina revestida en solido simil marmol y negro Antihuellas\", \"categoria_id\": 8, \"observaciones\": null, \"codigo_interno\": \"CH05\", \"version_actual\": 1}}', NULL, '2026-06-01 12:11:39', '2026-06-01 12:11:39'),
(114, 'default', 'deleted', 'App\\Models\\Insumo', 'deleted', 1, 'App\\Models\\User', 1, '{\"old\": {\"tag\": [\"Insumo General\"], \"activo\": true, \"codigo\": \"INS-0001\", \"nombre\": \"Regaton\", \"ubicacion\": \"Deposito\", \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 100, \"stock_minimo\": 200, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:11:58', '2026-06-01 12:11:58'),
(115, 'default', 'deleted', 'App\\Models\\Insumo', 'deleted', 2, 'App\\Models\\User', 1, '{\"old\": {\"tag\": [\"Patas\"], \"activo\": true, \"codigo\": \"INS-0002\", \"nombre\": \"Pata Multimarcas\", \"ubicacion\": \"Deposito - Cuadrante 3\", \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 7, \"stock_minimo\": 5, \"observaciones\": \"COLOR GRAFITO - SIMPLE\", \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:11:58', '2026-06-01 12:11:58'),
(116, 'default', 'deleted', 'App\\Models\\Insumo', 'deleted', 3, 'App\\Models\\User', 1, '{\"old\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0003\", \"nombre\": \"SILLA TOKIO\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": 3, \"stock_actual\": 5, \"stock_minimo\": 3, \"observaciones\": null, \"tipo_silla_id\": 1, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:11:58', '2026-06-01 12:11:58'),
(117, 'default', 'created', 'App\\Models\\Insumo', 'created', 4, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0004\", \"nombre\": \"ACCESORIOS PORTA BLISTER\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:31:09', '2026-06-01 12:31:09'),
(118, 'default', 'created', 'App\\Models\\Insumo', 'created', 5, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0005\", \"nombre\": \"ACCESORIOS PORTA CALZADO\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:31:09', '2026-06-01 12:31:09'),
(119, 'default', 'created', 'App\\Models\\Insumo', 'created', 6, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0006\", \"nombre\": \"ANGULO ALUMINIO 25 MM X25 MM\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 1}}', NULL, '2026-06-01 12:31:09', '2026-06-01 12:31:09'),
(120, 'default', 'created', 'App\\Models\\Insumo', 'created', 7, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0007\", \"nombre\": \"ATRIL JETOUR\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:31:09', '2026-06-01 12:31:09'),
(121, 'default', 'created', 'App\\Models\\Insumo', 'created', 8, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0008\", \"nombre\": \"ATRIL TRIUMPH\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:31:09', '2026-06-01 12:31:09'),
(122, 'default', 'created', 'App\\Models\\Insumo', 'created', 9, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0009\", \"nombre\": \"BALDOSA LISAS CFMOTO\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:31:09', '2026-06-01 12:31:09'),
(123, 'default', 'created', 'App\\Models\\Insumo', 'created', 10, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0010\", \"nombre\": \"BALDOSA SEMILLA DE MELON TARIMA CFMOTO\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:31:09', '2026-06-01 12:31:09'),
(124, 'default', 'created', 'App\\Models\\Insumo', 'created', 11, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0011\", \"nombre\": \"BANQUETA RENAULT\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:31:09', '2026-06-01 12:31:09'),
(125, 'default', 'created', 'App\\Models\\Insumo', 'created', 12, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0012\", \"nombre\": \"BANQUETA SIENA NEGRA\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:31:09', '2026-06-01 12:31:09'),
(126, 'default', 'created', 'App\\Models\\Insumo', 'created', 13, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0013\", \"nombre\": \"BANQUETA TOLIX\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:31:09', '2026-06-01 12:31:09'),
(127, 'default', 'created', 'App\\Models\\Insumo', 'created', 14, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0014\", \"nombre\": \"BANQUETA VENETO NEGRA BASE CROMO\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:31:09', '2026-06-01 12:31:09'),
(128, 'default', 'created', 'App\\Models\\Insumo', 'created', 15, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0015\", \"nombre\": \"BASE CROMADA SILLON SKI\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:31:09', '2026-06-01 12:31:09'),
(129, 'default', 'created', 'App\\Models\\Insumo', 'created', 16, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0016\", \"nombre\": \"BASE MESA COWORKING RENAULT\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:31:09', '2026-06-01 12:31:09'),
(130, 'default', 'created', 'App\\Models\\Insumo', 'created', 17, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0017\", \"nombre\": \"BASE MESA DE CENTRO TST 01 TRIUMPH\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:31:09', '2026-06-01 12:31:09'),
(131, 'default', 'created', 'App\\Models\\Insumo', 'created', 18, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0018\", \"nombre\": \"BASE MESA RE002 RENAULT\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:31:09', '2026-06-01 12:31:09'),
(132, 'default', 'created', 'App\\Models\\Insumo', 'created', 19, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0019\", \"nombre\": \"BASE METALICA PUNTO ENCUENTRO RENAULT\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:31:09', '2026-06-01 12:31:09'),
(133, 'default', 'created', 'App\\Models\\Insumo', 'created', 20, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0020\", \"nombre\": \"BASE PARA SILLAS 4 RAYOS RENAULT\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:31:09', '2026-06-01 12:31:09');
INSERT INTO `activity_log` (`id`, `log_name`, `description`, `subject_type`, `event`, `subject_id`, `causer_type`, `causer_id`, `properties`, `batch_uuid`, `created_at`, `updated_at`) VALUES
(134, 'default', 'created', 'App\\Models\\Insumo', 'created', 21, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0021\", \"nombre\": \"BASE PARA SILLAS 5 RAYOS RENAULT\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:31:09', '2026-06-01 12:31:09'),
(135, 'default', 'created', 'App\\Models\\Insumo', 'created', 22, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0022\", \"nombre\": \"BASE PLASTICA RECTANGULAR\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:31:09', '2026-06-01 12:31:09'),
(136, 'default', 'created', 'App\\Models\\Insumo', 'created', 23, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0023\", \"nombre\": \"BASE PLEGADA MESA CENTRO CHERY\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:31:09', '2026-06-01 12:31:09'),
(137, 'default', 'created', 'App\\Models\\Insumo', 'created', 24, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0024\", \"nombre\": \"BASE PULPITO\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:31:09', '2026-06-01 12:31:09'),
(138, 'default', 'created', 'App\\Models\\Insumo', 'created', 25, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0025\", \"nombre\": \"BASE TRINEO JE04\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:31:09', '2026-06-01 12:31:09'),
(139, 'default', 'created', 'App\\Models\\Insumo', 'created', 26, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0026\", \"nombre\": \"BISAGRAS PARA VIDRIOS\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:31:09', '2026-06-01 12:31:09'),
(140, 'default', 'created', 'App\\Models\\Insumo', 'created', 27, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0027\", \"nombre\": \"CALCO ACCESORIES TOTEM TRIUMPH\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:31:09', '2026-06-01 12:31:09'),
(141, 'default', 'created', 'App\\Models\\Insumo', 'created', 28, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0028\", \"nombre\": \"CALCO APPAREL TOTEM TRIUMPH\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:31:09', '2026-06-01 12:31:09'),
(142, 'default', 'created', 'App\\Models\\Insumo', 'created', 29, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0029\", \"nombre\": \"CALCO CORONA COMMUNITY WALL TRIUMPH\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:31:09', '2026-06-01 12:31:09'),
(143, 'default', 'created', 'App\\Models\\Insumo', 'created', 30, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0030\", \"nombre\": \"CALCO SERVICE RECEPTION\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:31:09', '2026-06-01 12:31:09'),
(144, 'default', 'created', 'App\\Models\\Insumo', 'created', 31, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0031\", \"nombre\": \"CALCO TSW 01 TRIUMPH\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:31:09', '2026-06-01 12:31:09'),
(145, 'default', 'created', 'App\\Models\\Insumo', 'created', 32, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0032\", \"nombre\": \"CALCO WELCOME DESK TWD\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:31:09', '2026-06-01 12:31:09'),
(146, 'default', 'created', 'App\\Models\\Insumo', 'created', 33, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0033\", \"nombre\": \"CAÑO PERCHERO OVAL CROMO EN MTS\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(147, 'default', 'created', 'App\\Models\\Insumo', 'created', 34, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0034\", \"nombre\": \"CASCO ALTO CHERY VENDEDOR Y ESPERA\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(148, 'default', 'created', 'App\\Models\\Insumo', 'created', 35, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0035\", \"nombre\": \"CASCO BAJO CHERY SILLA CLIENTE\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(149, 'default', 'created', 'App\\Models\\Insumo', 'created', 36, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0036\", \"nombre\": \"CASCO FIBRA PGT RENAULT\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(150, 'default', 'created', 'App\\Models\\Insumo', 'created', 37, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0037\", \"nombre\": \"CASCO JE04\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(151, 'default', 'created', 'App\\Models\\Insumo', 'created', 38, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0038\", \"nombre\": \"CASCO JE06\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(152, 'default', 'created', 'App\\Models\\Insumo', 'created', 39, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0039\", \"nombre\": \"CERRADURA COLECTIVA FRONTAL\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(153, 'default', 'created', 'App\\Models\\Insumo', 'created', 40, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0040\", \"nombre\": \"CERRADURA COLECTIVA LATERAL\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(154, 'default', 'created', 'App\\Models\\Insumo', 'created', 41, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0041\", \"nombre\": \"CERRADURA TAMBOR FRONTAL\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(155, 'default', 'created', 'App\\Models\\Insumo', 'created', 42, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0042\", \"nombre\": \"CESTO MUEBLE CAFÉ RENAULT\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(156, 'default', 'created', 'App\\Models\\Insumo', 'created', 43, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0043\", \"nombre\": \"CHAPA PERFORADA ALUMINIO MEVACO\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(157, 'default', 'created', 'App\\Models\\Insumo', 'created', 44, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0044\", \"nombre\": \"CONJUNTO ESPUMA P 2 CUERPOS\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(158, 'default', 'created', 'App\\Models\\Insumo', 'created', 45, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0045\", \"nombre\": \"CONJUNTO ESPUMA P 3 CUERPOS\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(159, 'default', 'created', 'App\\Models\\Insumo', 'created', 46, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0046\", \"nombre\": \"CONJUNTO VIDRIOS VITRINA CFMOTO\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(160, 'default', 'created', 'App\\Models\\Insumo', 'created', 47, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0047\", \"nombre\": \"CONTRAPESO ATRIL (HIERROMAS)\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(161, 'default', 'created', 'App\\Models\\Insumo', 'created', 48, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0048\", \"nombre\": \"CUERINA/TELA CHERY PARA 2 CUERPOS\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(162, 'default', 'created', 'App\\Models\\Insumo', 'created', 49, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0049\", \"nombre\": \"CUERINA/TELA CHERY PARA 3 CUERPOS\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(163, 'default', 'created', 'App\\Models\\Insumo', 'created', 50, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0050\", \"nombre\": \"CUPULA DE VIDRIO VITRINA CFMOTO\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(164, 'default', 'created', 'App\\Models\\Insumo', 'created', 51, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0051\", \"nombre\": \"ESTRUCTURA C08B MUEBLE DE ACCESORIOS CORVEN BAJAJ\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(165, 'default', 'created', 'App\\Models\\Insumo', 'created', 52, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0052\", \"nombre\": \"ESTRUCTURA CB11 VITRINA DE REPUESTOS CORVEN BAJAJ\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(166, 'default', 'created', 'App\\Models\\Insumo', 'created', 53, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0053\", \"nombre\": \"ESTRUCTURA EXTENSION RECEPCION CB03\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(167, 'default', 'created', 'App\\Models\\Insumo', 'created', 54, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0054\", \"nombre\": \"ESTRUCTURA MODULO C08B\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(168, 'default', 'created', 'App\\Models\\Insumo', 'created', 55, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0055\", \"nombre\": \"ESTRUCTURA PORTA CASCOS MOTOS\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(169, 'default', 'created', 'App\\Models\\Insumo', 'created', 56, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0056\", \"nombre\": \"ESTRUCTURA VITRINA CFMOTO 1 X 2,10\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(170, 'default', 'created', 'App\\Models\\Insumo', 'created', 57, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0057\", \"nombre\": \"ESTRUCTURA VITRINA TSC TRIUMPH\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(171, 'default', 'created', 'App\\Models\\Insumo', 'created', 58, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0058\", \"nombre\": \"GONDOLA TRIUMPH\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(172, 'default', 'created', 'App\\Models\\Insumo', 'created', 59, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0059\", \"nombre\": \"JGO CALCOS TCHW 02 TRIUMPH\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(173, 'default', 'created', 'App\\Models\\Insumo', 'created', 60, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0060\", \"nombre\": \"JGO CALCOS TCHW TRIUMPH\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(174, 'default', 'created', 'App\\Models\\Insumo', 'created', 61, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0061\", \"nombre\": \"JGO ESPUMA SILLON RENAULT RE012\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(175, 'default', 'created', 'App\\Models\\Insumo', 'created', 62, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0062\", \"nombre\": \"JGO MAMPARAS VENDEDOR RENAULT\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(176, 'default', 'created', 'App\\Models\\Insumo', 'created', 63, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0063\", \"nombre\": \"JGO VIDRIOS TSC TRIUMPH\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(177, 'default', 'created', 'App\\Models\\Insumo', 'created', 64, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0064\", \"nombre\": \"JUEGO COSTADO CHAPA HAFELE 40CM\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(178, 'default', 'created', 'App\\Models\\Insumo', 'created', 65, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0065\", \"nombre\": \"JUEGO DE VIDRIOS ESTRUCTURA CB11\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(179, 'default', 'created', 'App\\Models\\Insumo', 'created', 66, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0066\", \"nombre\": \"JUEGO DE VIDRIOS EXTENSION RECEPCION CORVEN BAJAJ\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(180, 'default', 'created', 'App\\Models\\Insumo', 'created', 67, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0067\", \"nombre\": \"JUEGO PATA CAÑO MESA RATONA CORVEN BAJAJ\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(181, 'default', 'created', 'App\\Models\\Insumo', 'created', 68, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0068\", \"nombre\": \"KIT PUERTAS CORREDIZAS 1535 RUEDA MAS\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(182, 'default', 'created', 'App\\Models\\Insumo', 'created', 69, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0069\", \"nombre\": \"KOMPAC GRIS X MT\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(183, 'default', 'created', 'App\\Models\\Insumo', 'created', 70, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0070\", \"nombre\": \"LAMINADO 0,8 WALLIS M868 FORMICA (TERRACOTA) x HOJA\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(184, 'default', 'created', 'App\\Models\\Insumo', 'created', 71, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0071\", \"nombre\": \"LAMINADO CARRARA X M2\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(185, 'default', 'created', 'App\\Models\\Insumo', 'created', 72, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0072\", \"nombre\": \"LAMINADO GRIS HUMO X MT2\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(186, 'default', 'created', 'App\\Models\\Insumo', 'created', 73, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0073\", \"nombre\": \"LAMINADO PERFECT SENSE X M2\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(187, 'default', 'created', 'App\\Models\\Insumo', 'created', 74, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0074\", \"nombre\": \"LOGO ACCESORIOS MODULO CFMOTO\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(188, 'default', 'created', 'App\\Models\\Insumo', 'created', 75, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0075\", \"nombre\": \"LOGO ATRIL BAJAJ\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(189, 'default', 'created', 'App\\Models\\Insumo', 'created', 76, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0076\", \"nombre\": \"LOGO ATRIL CFMOTO\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(190, 'default', 'created', 'App\\Models\\Insumo', 'created', 77, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0077\", \"nombre\": \"LOGO ATRIL CORVEN\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(191, 'default', 'created', 'App\\Models\\Insumo', 'created', 78, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0078\", \"nombre\": \"LOGO CFMOTO PANEL PARED\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(192, 'default', 'created', 'App\\Models\\Insumo', 'created', 79, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0079\", \"nombre\": \"LOGO CFMOTO TABLERO PARED\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(193, 'default', 'created', 'App\\Models\\Insumo', 'created', 80, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0080\", \"nombre\": \"LOGO CHERY RECEPCION\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(194, 'default', 'created', 'App\\Models\\Insumo', 'created', 81, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0081\", \"nombre\": \"LOGO CLX PANEL CFMOTO\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(195, 'default', 'created', 'App\\Models\\Insumo', 'created', 82, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0082\", \"nombre\": \"LOGO JE01 JEOTUR\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(196, 'default', 'created', 'App\\Models\\Insumo', 'created', 83, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0083\", \"nombre\": \"LOGO MUEBLE DE ACCESORIOS CORVEN BAJAJ CB10\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(197, 'default', 'created', 'App\\Models\\Insumo', 'created', 84, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0084\", \"nombre\": \"LOGO MURO CFMOTO\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(198, 'default', 'created', 'App\\Models\\Insumo', 'created', 85, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0085\", \"nombre\": \"LOGO RECEPCION CFMOTO\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(199, 'default', 'created', 'App\\Models\\Insumo', 'created', 86, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0086\", \"nombre\": \"LOGO TARIMA BAJAJ\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(200, 'default', 'created', 'App\\Models\\Insumo', 'created', 87, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0087\", \"nombre\": \"LOGO TARIMA CORVEN\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(201, 'default', 'created', 'App\\Models\\Insumo', 'created', 88, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0088\", \"nombre\": \"LOGO TRIUMPH 50X50\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(202, 'default', 'created', 'App\\Models\\Insumo', 'created', 89, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0089\", \"nombre\": \"MAA MODULO MULTIMARCAS\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(203, 'default', 'created', 'App\\Models\\Insumo', 'created', 90, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0090\", \"nombre\": \"MAC MODULO MULTIMARCAS\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(204, 'default', 'created', 'App\\Models\\Insumo', 'created', 91, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0091\", \"nombre\": \"MANIQUI CON CAÑO TRIUMPH\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(205, 'default', 'created', 'App\\Models\\Insumo', 'created', 92, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0092\", \"nombre\": \"MANIQUI TRIUMPH\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(206, 'default', 'created', 'App\\Models\\Insumo', 'created', 93, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0093\", \"nombre\": \"PANTALLA TAO43 TRIUMPH\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(207, 'default', 'created', 'App\\Models\\Insumo', 'created', 94, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0094\", \"nombre\": \"PASACABLE BOX240 WIRE\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(208, 'default', 'created', 'App\\Models\\Insumo', 'created', 95, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0095\", \"nombre\": \"PASACABLE RECTANGULAR PLASTICO negro CON CEPILLO WIRENEX\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(209, 'default', 'created', 'App\\Models\\Insumo', 'created', 96, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0096\", \"nombre\": \"PASACABLES 60MM NEGRO\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(210, 'default', 'created', 'App\\Models\\Insumo', 'created', 97, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0097\", \"nombre\": \"PATA CHAPA CIRCULAR RENAULT\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(211, 'default', 'created', 'App\\Models\\Insumo', 'created', 98, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0098\", \"nombre\": \"PATA DE CAÑO ESCRITORIO 70 30\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(212, 'default', 'created', 'App\\Models\\Insumo', 'created', 99, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0099\", \"nombre\": \"PATA METALICA MULTIMARCAS MODULOS\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(213, 'default', 'created', 'App\\Models\\Insumo', 'created', 100, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0100\", \"nombre\": \"PATA WORK STATION JGO MULTIMARCAS\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(214, 'default', 'created', 'App\\Models\\Insumo', 'created', 101, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0101\", \"nombre\": \"PATAS MESA LOUNGE RENAULT BLANCA\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(215, 'default', 'created', 'App\\Models\\Insumo', 'created', 102, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0102\", \"nombre\": \"PATAS MESA LOUNGE RENAULT NEGRA\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(216, 'default', 'created', 'App\\Models\\Insumo', 'created', 103, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0103\", \"nombre\": \"PATAS MESA LOUNGE RENAULT ROBLE\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(217, 'default', 'created', 'App\\Models\\Insumo', 'created', 104, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0104\", \"nombre\": \"PERFIL AUTOADHESIVO CAJONERAS X MTS\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(218, 'default', 'created', 'App\\Models\\Insumo', 'created', 105, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0105\", \"nombre\": \"PERFIL CURVO (TABLERO CFMOTO)\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(219, 'default', 'created', 'App\\Models\\Insumo', 'created', 106, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0106\", \"nombre\": \"PERFIL PLACA RANURADA\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(220, 'default', 'created', 'App\\Models\\Insumo', 'created', 107, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0107\", \"nombre\": \"PERFIL PORTA LED X 3M LDP\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(221, 'default', 'created', 'App\\Models\\Insumo', 'created', 108, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0108\", \"nombre\": \"PERFIL TAPACANTOS 18MM X MTS\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(222, 'default', 'created', 'App\\Models\\Insumo', 'created', 109, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0109\", \"nombre\": \"PINTURA BAJAJ AZUL PANTONE 2945C\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(223, 'default', 'created', 'App\\Models\\Insumo', 'created', 110, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0110\", \"nombre\": \"PINTURA CORVEN NARANJA PANTONE 021 EN LTS\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(224, 'default', 'created', 'App\\Models\\Insumo', 'created', 111, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0111\", \"nombre\": \"PINTURA GRIS CF CHAPA CODIGO REEM18538\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(225, 'default', 'created', 'App\\Models\\Insumo', 'created', 112, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0112\", \"nombre\": \"PINTURA GRIS CF MADERA CODIGO RES18538\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(226, 'default', 'created', 'App\\Models\\Insumo', 'created', 113, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0113\", \"nombre\": \"PINTURA NEGRA ATRILES MOTOS\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(227, 'default', 'created', 'App\\Models\\Insumo', 'created', 114, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0114\", \"nombre\": \"PINTURA VERDE TRIUMPH SW 6172 SATINADO\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(228, 'default', 'created', 'App\\Models\\Insumo', 'created', 115, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0115\", \"nombre\": \"PLOTEO AMARILLO PARA MAMPARAS RENAULT\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(229, 'default', 'created', 'App\\Models\\Insumo', 'created', 116, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0116\", \"nombre\": \"PLOTEO NEGRO MAMPARAS RENAULT\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(230, 'default', 'created', 'App\\Models\\Insumo', 'created', 117, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0117\", \"nombre\": \"PUFF TCH 06 TRIUMPH\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(231, 'default', 'created', 'App\\Models\\Insumo', 'created', 118, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0118\", \"nombre\": \"RECEPCION CHERY SILLA CLIENTE\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(232, 'default', 'created', 'App\\Models\\Insumo', 'created', 119, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0119\", \"nombre\": \"REGATON REDONDOS PLASTICOS GRANDES\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(233, 'default', 'created', 'App\\Models\\Insumo', 'created', 120, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0120\", \"nombre\": \"REGATON REGULABLE 5/16\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(234, 'default', 'created', 'App\\Models\\Insumo', 'created', 121, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0121\", \"nombre\": \"SEPARADORES CHICOS ESCRITORIO 3D RENAULT\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(235, 'default', 'created', 'App\\Models\\Insumo', 'created', 122, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0122\", \"nombre\": \"SEPARADORES GRANDES ESCRITORIO GRANDE 3D RENAULT\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(236, 'default', 'created', 'App\\Models\\Insumo', 'created', 123, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0123\", \"nombre\": \"SILLA ADRIX\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(237, 'default', 'created', 'App\\Models\\Insumo', 'created', 124, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0124\", \"nombre\": \"SILLA ALMA ASIENTO NEGRO Y RESPALDO NEGRO\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(238, 'default', 'created', 'App\\Models\\Insumo', 'created', 125, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0125\", \"nombre\": \"SILLA ATHINA BASE CROMO NEGRA FULL\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(239, 'default', 'created', 'App\\Models\\Insumo', 'created', 126, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0126\", \"nombre\": \"SILLA ATHINA BASE CROMO NEGRA FULL CON ARO POSAPIE\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(240, 'default', 'created', 'App\\Models\\Insumo', 'created', 127, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0127\", \"nombre\": \"SILLA ATHINA BASE CROMO TRINEO NEGRA FULL\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(241, 'default', 'created', 'App\\Models\\Insumo', 'created', 128, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0128\", \"nombre\": \"SILLA BASTONE ALTA BASE CROMO PORT\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(242, 'default', 'created', 'App\\Models\\Insumo', 'created', 129, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0129\", \"nombre\": \"SILLA BASTONE BAJA BASE CROMO PORT\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(243, 'default', 'created', 'App\\Models\\Insumo', 'created', 130, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0130\", \"nombre\": \"SILLA GROU FULL NEGRA ROLIC\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(244, 'default', 'created', 'App\\Models\\Insumo', 'created', 131, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0131\", \"nombre\": \"SILLA GROU FULL NEGRA ROLIC BASE ARO POSAPIE\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(245, 'default', 'created', 'App\\Models\\Insumo', 'created', 132, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0132\", \"nombre\": \"SILLA GUD EXECUTIVE NOVEC\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16');
INSERT INTO `activity_log` (`id`, `log_name`, `description`, `subject_type`, `event`, `subject_id`, `causer_type`, `causer_id`, `properties`, `batch_uuid`, `created_at`, `updated_at`) VALUES
(246, 'default', 'created', 'App\\Models\\Insumo', 'created', 133, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0133\", \"nombre\": \"SILLA INDIA BASE TRINEO FULL NEGRA PORT\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(247, 'default', 'created', 'App\\Models\\Insumo', 'created', 134, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0134\", \"nombre\": \"SILLA MINT FULL NEGRA CON APOYA CABEZA Y LUMBAR ROL\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(248, 'default', 'created', 'App\\Models\\Insumo', 'created', 135, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0135\", \"nombre\": \"SILLA MINT FULL NEGRA CON APOYA CABEZA Y LUMBAR Y ARO POSAPIE ROL\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(249, 'default', 'created', 'App\\Models\\Insumo', 'created', 136, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0136\", \"nombre\": \"SILLA TOKIO FULL NEGRA BASE CROMADA\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(250, 'default', 'created', 'App\\Models\\Insumo', 'created', 137, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0137\", \"nombre\": \"SILLON LC3 1 CUERPO OPCION 1\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(251, 'default', 'created', 'App\\Models\\Insumo', 'created', 138, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0138\", \"nombre\": \"SILLON RE028 SACHETI RENAULT\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(252, 'default', 'created', 'App\\Models\\Insumo', 'created', 139, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0139\", \"nombre\": \"SILLON TCH 05 TRIUMPH\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(253, 'default', 'created', 'App\\Models\\Insumo', 'created', 140, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0140\", \"nombre\": \"SOBRE PARA ATRIL\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(254, 'default', 'created', 'App\\Models\\Insumo', 'created', 141, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0141\", \"nombre\": \"SOPORTE CAÑO PERCHERO OVAL\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(255, 'default', 'created', 'App\\Models\\Insumo', 'created', 142, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0142\", \"nombre\": \"SOPORTE MB01 TRIUMPH\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(256, 'default', 'created', 'App\\Models\\Insumo', 'created', 143, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0143\", \"nombre\": \"SOPORTE MB04 TRIUMPH\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(257, 'default', 'created', 'App\\Models\\Insumo', 'created', 144, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0144\", \"nombre\": \"SOPORTE MB20 TRIUMPH\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(258, 'default', 'created', 'App\\Models\\Insumo', 'created', 145, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0145\", \"nombre\": \"SOPORTE MB22 TRIUMPH\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(259, 'default', 'created', 'App\\Models\\Insumo', 'created', 146, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0146\", \"nombre\": \"TABURETE MULTIMARCAS\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:16', '2026-06-01 12:32:16'),
(260, 'default', 'created', 'App\\Models\\Insumo', 'created', 147, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0147\", \"nombre\": \"TAPIZADO DEDOS PANEL JETOUR\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:17', '2026-06-01 12:32:17'),
(261, 'default', 'created', 'App\\Models\\Insumo', 'created', 148, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0148\", \"nombre\": \"TARIMA TRIUMPH PLEGADA\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:17', '2026-06-01 12:32:17'),
(262, 'default', 'created', 'App\\Models\\Insumo', 'created', 149, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0149\", \"nombre\": \"TIRADOR BALA\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:17', '2026-06-01 12:32:17'),
(263, 'default', 'created', 'App\\Models\\Insumo', 'created', 150, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0150\", \"nombre\": \"TOTEM HERO TRIUMPH\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:17', '2026-06-01 12:32:17'),
(264, 'default', 'created', 'App\\Models\\Insumo', 'created', 151, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0151\", \"nombre\": \"TOTEM TAO43 TRIUMPH\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:17', '2026-06-01 12:32:17'),
(265, 'default', 'created', 'App\\Models\\Insumo', 'created', 152, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0152\", \"nombre\": \"TUBO LED\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:17', '2026-06-01 12:32:17'),
(266, 'default', 'created', 'App\\Models\\Insumo', 'created', 153, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0153\", \"nombre\": \"VIDRIO TOTEM PLV\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:17', '2026-06-01 12:32:17'),
(267, 'default', 'created', 'App\\Models\\Insumo', 'created', 154, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0154\", \"nombre\": \"VINILOS UV MATE CFMOTO\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:32:17', '2026-06-01 12:32:17'),
(268, 'default', 'created', 'App\\Models\\CategoriaInsumo', 'created', 5, 'App\\Models\\User', 1, '{\"attributes\": {\"activo\": true, \"nombre\": \"Perfiles\"}}', NULL, '2026-06-01 12:38:59', '2026-06-01 12:38:59'),
(269, 'default', 'created', 'App\\Models\\CategoriaInsumo', 'created', 6, 'App\\Models\\User', 1, '{\"attributes\": {\"activo\": true, \"nombre\": \"Pintura\"}}', NULL, '2026-06-01 12:39:15', '2026-06-01 12:39:15'),
(270, 'default', 'created', 'App\\Models\\CategoriaInsumo', 'created', 7, 'App\\Models\\User', 1, '{\"attributes\": {\"activo\": true, \"nombre\": \"Cerraduras\"}}', NULL, '2026-06-01 12:39:42', '2026-06-01 12:39:42'),
(271, 'default', 'created', 'App\\Models\\CategoriaInsumo', 'created', 8, 'App\\Models\\User', 1, '{\"attributes\": {\"activo\": true, \"nombre\": \"Bases Sillas\"}}', NULL, '2026-06-01 12:39:53', '2026-06-01 12:39:53'),
(272, 'default', 'created', 'App\\Models\\CategoriaInsumo', 'created', 9, 'App\\Models\\User', 1, '{\"attributes\": {\"activo\": true, \"nombre\": \"Goma espuma\"}}', NULL, '2026-06-01 12:40:33', '2026-06-01 12:40:33'),
(273, 'default', 'created', 'App\\Models\\Insumo', 'created', 1, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0001\", \"nombre\": \"ACCESORIOS PORTA BLISTER\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(274, 'default', 'created', 'App\\Models\\Insumo', 'created', 2, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0002\", \"nombre\": \"ACCESORIOS PORTA CALZADO\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(275, 'default', 'created', 'App\\Models\\Insumo', 'created', 3, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0003\", \"nombre\": \"ANGULO ALUMINIO 25 MM X25 MM\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 1}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(276, 'default', 'created', 'App\\Models\\Insumo', 'created', 4, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0004\", \"nombre\": \"ATRIL JETOUR\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(277, 'default', 'created', 'App\\Models\\Insumo', 'created', 5, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0005\", \"nombre\": \"ATRIL TRIUMPH\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(278, 'default', 'created', 'App\\Models\\Insumo', 'created', 6, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0006\", \"nombre\": \"BALDOSA LISAS CFMOTO\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(279, 'default', 'created', 'App\\Models\\Insumo', 'created', 7, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0007\", \"nombre\": \"BALDOSA SEMILLA DE MELON TARIMA CFMOTO\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(280, 'default', 'created', 'App\\Models\\Insumo', 'created', 8, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0008\", \"nombre\": \"BANQUETA RENAULT\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(281, 'default', 'created', 'App\\Models\\Insumo', 'created', 9, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0009\", \"nombre\": \"BANQUETA SIENA NEGRA\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(282, 'default', 'created', 'App\\Models\\Insumo', 'created', 10, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0010\", \"nombre\": \"BANQUETA TOLIX\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(283, 'default', 'created', 'App\\Models\\Insumo', 'created', 11, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0011\", \"nombre\": \"BANQUETA VENETO NEGRA BASE CROMO\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(284, 'default', 'created', 'App\\Models\\Insumo', 'created', 12, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0012\", \"nombre\": \"BASE CROMADA SILLON SKI\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(285, 'default', 'created', 'App\\Models\\Insumo', 'created', 13, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0013\", \"nombre\": \"BASE MESA COWORKING RENAULT\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(286, 'default', 'created', 'App\\Models\\Insumo', 'created', 14, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0014\", \"nombre\": \"BASE MESA DE CENTRO TST 01 TRIUMPH\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(287, 'default', 'created', 'App\\Models\\Insumo', 'created', 15, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0015\", \"nombre\": \"BASE MESA RE002 RENAULT\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(288, 'default', 'created', 'App\\Models\\Insumo', 'created', 16, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0016\", \"nombre\": \"BASE METALICA PUNTO ENCUENTRO RENAULT\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(289, 'default', 'created', 'App\\Models\\Insumo', 'created', 17, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0017\", \"nombre\": \"BASE PARA SILLAS 4 RAYOS RENAULT\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(290, 'default', 'created', 'App\\Models\\Insumo', 'created', 18, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0018\", \"nombre\": \"BASE PARA SILLAS 5 RAYOS RENAULT\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(291, 'default', 'created', 'App\\Models\\Insumo', 'created', 19, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0019\", \"nombre\": \"BASE PLASTICA RECTANGULAR\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(292, 'default', 'created', 'App\\Models\\Insumo', 'created', 20, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0020\", \"nombre\": \"BASE PLEGADA MESA CENTRO CHERY\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(293, 'default', 'created', 'App\\Models\\Insumo', 'created', 21, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0021\", \"nombre\": \"BASE PULPITO\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(294, 'default', 'created', 'App\\Models\\Insumo', 'created', 22, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0022\", \"nombre\": \"BASE TRINEO JE04\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(295, 'default', 'created', 'App\\Models\\Insumo', 'created', 23, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0023\", \"nombre\": \"BISAGRAS PARA VIDRIOS\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(296, 'default', 'created', 'App\\Models\\Insumo', 'created', 24, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0024\", \"nombre\": \"CALCO ACCESORIES TOTEM TRIUMPH\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(297, 'default', 'created', 'App\\Models\\Insumo', 'created', 25, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0025\", \"nombre\": \"CALCO APPAREL TOTEM TRIUMPH\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(298, 'default', 'created', 'App\\Models\\Insumo', 'created', 26, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0026\", \"nombre\": \"CALCO CORONA COMMUNITY WALL TRIUMPH\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(299, 'default', 'created', 'App\\Models\\Insumo', 'created', 27, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0027\", \"nombre\": \"CALCO SERVICE RECEPTION\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(300, 'default', 'created', 'App\\Models\\Insumo', 'created', 28, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0028\", \"nombre\": \"CALCO TSW 01 TRIUMPH\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(301, 'default', 'created', 'App\\Models\\Insumo', 'created', 29, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0029\", \"nombre\": \"CALCO WELCOME DESK TWD\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(302, 'default', 'created', 'App\\Models\\Insumo', 'created', 30, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0030\", \"nombre\": \"CAÑO PERCHERO OVAL CROMO EN MTS\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(303, 'default', 'created', 'App\\Models\\Insumo', 'created', 31, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0031\", \"nombre\": \"CASCO ALTO CHERY VENDEDOR Y ESPERA\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(304, 'default', 'created', 'App\\Models\\Insumo', 'created', 32, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0032\", \"nombre\": \"CASCO BAJO CHERY SILLA CLIENTE\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(305, 'default', 'created', 'App\\Models\\Insumo', 'created', 33, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0033\", \"nombre\": \"CASCO FIBRA PGT RENAULT\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(306, 'default', 'created', 'App\\Models\\Insumo', 'created', 34, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0034\", \"nombre\": \"CASCO JE04\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(307, 'default', 'created', 'App\\Models\\Insumo', 'created', 35, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0035\", \"nombre\": \"CASCO JE06\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(308, 'default', 'created', 'App\\Models\\Insumo', 'created', 36, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0036\", \"nombre\": \"CERRADURA COLECTIVA FRONTAL\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(309, 'default', 'created', 'App\\Models\\Insumo', 'created', 37, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0037\", \"nombre\": \"CERRADURA COLECTIVA LATERAL\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(310, 'default', 'created', 'App\\Models\\Insumo', 'created', 38, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0038\", \"nombre\": \"CERRADURA TAMBOR FRONTAL\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(311, 'default', 'created', 'App\\Models\\Insumo', 'created', 39, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0039\", \"nombre\": \"CESTO MUEBLE CAFÉ RENAULT\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(312, 'default', 'created', 'App\\Models\\Insumo', 'created', 40, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0040\", \"nombre\": \"CHAPA PERFORADA ALUMINIO MEVACO\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(313, 'default', 'created', 'App\\Models\\Insumo', 'created', 41, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0041\", \"nombre\": \"CONJUNTO ESPUMA P 2 CUERPOS\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(314, 'default', 'created', 'App\\Models\\Insumo', 'created', 42, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0042\", \"nombre\": \"CONJUNTO ESPUMA P 3 CUERPOS\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(315, 'default', 'created', 'App\\Models\\Insumo', 'created', 43, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0043\", \"nombre\": \"CONJUNTO VIDRIOS VITRINA CFMOTO\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(316, 'default', 'created', 'App\\Models\\Insumo', 'created', 44, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0044\", \"nombre\": \"CONTRAPESO ATRIL (HIERROMAS)\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(317, 'default', 'created', 'App\\Models\\Insumo', 'created', 45, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0045\", \"nombre\": \"CUERINA/TELA CHERY PARA 2 CUERPOS\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(318, 'default', 'created', 'App\\Models\\Insumo', 'created', 46, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0046\", \"nombre\": \"CUERINA/TELA CHERY PARA 3 CUERPOS\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(319, 'default', 'created', 'App\\Models\\Insumo', 'created', 47, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0047\", \"nombre\": \"CUPULA DE VIDRIO VITRINA CFMOTO\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(320, 'default', 'created', 'App\\Models\\Insumo', 'created', 48, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0048\", \"nombre\": \"ESTRUCTURA C08B MUEBLE DE ACCESORIOS CORVEN BAJAJ\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(321, 'default', 'created', 'App\\Models\\Insumo', 'created', 49, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0049\", \"nombre\": \"ESTRUCTURA CB11 VITRINA DE REPUESTOS CORVEN BAJAJ\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(322, 'default', 'created', 'App\\Models\\Insumo', 'created', 50, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0050\", \"nombre\": \"ESTRUCTURA EXTENSION RECEPCION CB03\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(323, 'default', 'created', 'App\\Models\\Insumo', 'created', 51, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0051\", \"nombre\": \"ESTRUCTURA MODULO C08B\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(324, 'default', 'created', 'App\\Models\\Insumo', 'created', 52, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0052\", \"nombre\": \"ESTRUCTURA PORTA CASCOS MOTOS\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(325, 'default', 'created', 'App\\Models\\Insumo', 'created', 53, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0053\", \"nombre\": \"ESTRUCTURA VITRINA CFMOTO 1 X 2,10\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(326, 'default', 'created', 'App\\Models\\Insumo', 'created', 54, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0054\", \"nombre\": \"ESTRUCTURA VITRINA TSC TRIUMPH\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(327, 'default', 'created', 'App\\Models\\Insumo', 'created', 55, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0055\", \"nombre\": \"GONDOLA TRIUMPH\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(328, 'default', 'created', 'App\\Models\\Insumo', 'created', 56, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0056\", \"nombre\": \"JGO CALCOS TCHW 02 TRIUMPH\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(329, 'default', 'created', 'App\\Models\\Insumo', 'created', 57, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0057\", \"nombre\": \"JGO CALCOS TCHW TRIUMPH\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(330, 'default', 'created', 'App\\Models\\Insumo', 'created', 58, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0058\", \"nombre\": \"JGO ESPUMA SILLON RENAULT RE012\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(331, 'default', 'created', 'App\\Models\\Insumo', 'created', 59, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0059\", \"nombre\": \"JGO MAMPARAS VENDEDOR RENAULT\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(332, 'default', 'created', 'App\\Models\\Insumo', 'created', 60, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0060\", \"nombre\": \"JGO VIDRIOS TSC TRIUMPH\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(333, 'default', 'created', 'App\\Models\\Insumo', 'created', 61, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0061\", \"nombre\": \"JUEGO COSTADO CHAPA HAFELE 40CM\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(334, 'default', 'created', 'App\\Models\\Insumo', 'created', 62, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0062\", \"nombre\": \"JUEGO DE VIDRIOS ESTRUCTURA CB11\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(335, 'default', 'created', 'App\\Models\\Insumo', 'created', 63, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0063\", \"nombre\": \"JUEGO DE VIDRIOS EXTENSION RECEPCION CORVEN BAJAJ\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(336, 'default', 'created', 'App\\Models\\Insumo', 'created', 64, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0064\", \"nombre\": \"JUEGO PATA CAÑO MESA RATONA CORVEN BAJAJ\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(337, 'default', 'created', 'App\\Models\\Insumo', 'created', 65, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0065\", \"nombre\": \"KIT PUERTAS CORREDIZAS 1535 RUEDA MAS\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(338, 'default', 'created', 'App\\Models\\Insumo', 'created', 66, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0066\", \"nombre\": \"KOMPAC GRIS X MT\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(339, 'default', 'created', 'App\\Models\\Insumo', 'created', 67, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0067\", \"nombre\": \"LAMINADO 0,8 WALLIS M868 FORMICA (TERRACOTA) x HOJA\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(340, 'default', 'created', 'App\\Models\\Insumo', 'created', 68, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0068\", \"nombre\": \"LAMINADO CARRARA X M2\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(341, 'default', 'created', 'App\\Models\\Insumo', 'created', 69, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0069\", \"nombre\": \"LAMINADO GRIS HUMO X MT2\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(342, 'default', 'created', 'App\\Models\\Insumo', 'created', 70, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0070\", \"nombre\": \"LAMINADO PERFECT SENSE X M2\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(343, 'default', 'created', 'App\\Models\\Insumo', 'created', 71, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0071\", \"nombre\": \"LOGO ACCESORIOS MODULO CFMOTO\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(344, 'default', 'created', 'App\\Models\\Insumo', 'created', 72, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0072\", \"nombre\": \"LOGO ATRIL BAJAJ\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(345, 'default', 'created', 'App\\Models\\Insumo', 'created', 73, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0073\", \"nombre\": \"LOGO ATRIL CFMOTO\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(346, 'default', 'created', 'App\\Models\\Insumo', 'created', 74, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0074\", \"nombre\": \"LOGO ATRIL CORVEN\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(347, 'default', 'created', 'App\\Models\\Insumo', 'created', 75, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0075\", \"nombre\": \"LOGO CFMOTO PANEL PARED\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(348, 'default', 'created', 'App\\Models\\Insumo', 'created', 76, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0076\", \"nombre\": \"LOGO CFMOTO TABLERO PARED\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(349, 'default', 'created', 'App\\Models\\Insumo', 'created', 77, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0077\", \"nombre\": \"LOGO CHERY RECEPCION\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(350, 'default', 'created', 'App\\Models\\Insumo', 'created', 78, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0078\", \"nombre\": \"LOGO CLX PANEL CFMOTO\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(351, 'default', 'created', 'App\\Models\\Insumo', 'created', 79, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0079\", \"nombre\": \"LOGO JE01 JEOTUR\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(352, 'default', 'created', 'App\\Models\\Insumo', 'created', 80, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0080\", \"nombre\": \"LOGO MUEBLE DE ACCESORIOS CORVEN BAJAJ CB10\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(353, 'default', 'created', 'App\\Models\\Insumo', 'created', 81, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0081\", \"nombre\": \"LOGO MURO CFMOTO\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(354, 'default', 'created', 'App\\Models\\Insumo', 'created', 82, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0082\", \"nombre\": \"LOGO RECEPCION CFMOTO\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(355, 'default', 'created', 'App\\Models\\Insumo', 'created', 83, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0083\", \"nombre\": \"LOGO TARIMA BAJAJ\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(356, 'default', 'created', 'App\\Models\\Insumo', 'created', 84, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0084\", \"nombre\": \"LOGO TARIMA CORVEN\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(357, 'default', 'created', 'App\\Models\\Insumo', 'created', 85, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0085\", \"nombre\": \"LOGO TRIUMPH 50X50\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(358, 'default', 'created', 'App\\Models\\Insumo', 'created', 86, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0086\", \"nombre\": \"MAA MODULO MULTIMARCAS\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(359, 'default', 'created', 'App\\Models\\Insumo', 'created', 87, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0087\", \"nombre\": \"MAC MODULO MULTIMARCAS\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(360, 'default', 'created', 'App\\Models\\Insumo', 'created', 88, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0088\", \"nombre\": \"MANIQUI CON CAÑO TRIUMPH\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42');
INSERT INTO `activity_log` (`id`, `log_name`, `description`, `subject_type`, `event`, `subject_id`, `causer_type`, `causer_id`, `properties`, `batch_uuid`, `created_at`, `updated_at`) VALUES
(361, 'default', 'created', 'App\\Models\\Insumo', 'created', 89, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0089\", \"nombre\": \"MANIQUI TRIUMPH\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(362, 'default', 'created', 'App\\Models\\Insumo', 'created', 90, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0090\", \"nombre\": \"PANTALLA TAO43 TRIUMPH\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(363, 'default', 'created', 'App\\Models\\Insumo', 'created', 91, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0091\", \"nombre\": \"PASACABLE BOX240 WIRE\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(364, 'default', 'created', 'App\\Models\\Insumo', 'created', 92, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0092\", \"nombre\": \"PASACABLE RECTANGULAR PLASTICO negro CON CEPILLO WIRENEX\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(365, 'default', 'created', 'App\\Models\\Insumo', 'created', 93, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0093\", \"nombre\": \"PASACABLES 60MM NEGRO\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(366, 'default', 'created', 'App\\Models\\Insumo', 'created', 94, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0094\", \"nombre\": \"PATA CHAPA CIRCULAR RENAULT\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(367, 'default', 'created', 'App\\Models\\Insumo', 'created', 95, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0095\", \"nombre\": \"PATA DE CAÑO ESCRITORIO 70 30\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(368, 'default', 'created', 'App\\Models\\Insumo', 'created', 96, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0096\", \"nombre\": \"PATA METALICA MULTIMARCAS MODULOS\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(369, 'default', 'created', 'App\\Models\\Insumo', 'created', 97, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0097\", \"nombre\": \"PATA WORK STATION JGO MULTIMARCAS\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(370, 'default', 'created', 'App\\Models\\Insumo', 'created', 98, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0098\", \"nombre\": \"PATAS MESA LOUNGE RENAULT BLANCA\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(371, 'default', 'created', 'App\\Models\\Insumo', 'created', 99, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0099\", \"nombre\": \"PATAS MESA LOUNGE RENAULT NEGRA\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(372, 'default', 'created', 'App\\Models\\Insumo', 'created', 100, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0100\", \"nombre\": \"PATAS MESA LOUNGE RENAULT ROBLE\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(373, 'default', 'created', 'App\\Models\\Insumo', 'created', 101, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0101\", \"nombre\": \"PERFIL AUTOADHESIVO CAJONERAS X MTS\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(374, 'default', 'created', 'App\\Models\\Insumo', 'created', 102, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0102\", \"nombre\": \"PERFIL CURVO (TABLERO CFMOTO)\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(375, 'default', 'created', 'App\\Models\\Insumo', 'created', 103, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0103\", \"nombre\": \"PERFIL PLACA RANURADA\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(376, 'default', 'created', 'App\\Models\\Insumo', 'created', 104, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0104\", \"nombre\": \"PERFIL PORTA LED X 3M LDP\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(377, 'default', 'created', 'App\\Models\\Insumo', 'created', 105, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0105\", \"nombre\": \"PERFIL TAPACANTOS 18MM X MTS\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(378, 'default', 'created', 'App\\Models\\Insumo', 'created', 106, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0106\", \"nombre\": \"PINTURA BAJAJ AZUL PANTONE 2945C\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(379, 'default', 'created', 'App\\Models\\Insumo', 'created', 107, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0107\", \"nombre\": \"PINTURA CORVEN NARANJA PANTONE 021 EN LTS\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(380, 'default', 'created', 'App\\Models\\Insumo', 'created', 108, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0108\", \"nombre\": \"PINTURA GRIS CF CHAPA CODIGO REEM18538\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(381, 'default', 'created', 'App\\Models\\Insumo', 'created', 109, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0109\", \"nombre\": \"PINTURA GRIS CF MADERA CODIGO RES18538\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(382, 'default', 'created', 'App\\Models\\Insumo', 'created', 110, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0110\", \"nombre\": \"PINTURA NEGRA ATRILES MOTOS\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(383, 'default', 'created', 'App\\Models\\Insumo', 'created', 111, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0111\", \"nombre\": \"PINTURA VERDE TRIUMPH SW 6172 SATINADO\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(384, 'default', 'created', 'App\\Models\\Insumo', 'created', 112, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0112\", \"nombre\": \"PLOTEO AMARILLO PARA MAMPARAS RENAULT\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(385, 'default', 'created', 'App\\Models\\Insumo', 'created', 113, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0113\", \"nombre\": \"PLOTEO NEGRO MAMPARAS RENAULT\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(386, 'default', 'created', 'App\\Models\\Insumo', 'created', 114, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0114\", \"nombre\": \"PUFF TCH 06 TRIUMPH\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(387, 'default', 'created', 'App\\Models\\Insumo', 'created', 115, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0115\", \"nombre\": \"RECEPCION CHERY SILLA CLIENTE\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(388, 'default', 'created', 'App\\Models\\Insumo', 'created', 116, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0116\", \"nombre\": \"REGATON REDONDOS PLASTICOS GRANDES\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:42', '2026-06-01 12:49:42'),
(389, 'default', 'created', 'App\\Models\\Insumo', 'created', 117, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0117\", \"nombre\": \"REGATON REGULABLE 5/16\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:43', '2026-06-01 12:49:43'),
(390, 'default', 'created', 'App\\Models\\Insumo', 'created', 118, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0118\", \"nombre\": \"SEPARADORES CHICOS ESCRITORIO 3D RENAULT\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:43', '2026-06-01 12:49:43'),
(391, 'default', 'created', 'App\\Models\\Insumo', 'created', 119, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0119\", \"nombre\": \"SEPARADORES GRANDES ESCRITORIO GRANDE 3D RENAULT\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:43', '2026-06-01 12:49:43'),
(392, 'default', 'created', 'App\\Models\\Insumo', 'created', 120, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0120\", \"nombre\": \"SILLA ADRIX\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:43', '2026-06-01 12:49:43'),
(393, 'default', 'created', 'App\\Models\\Insumo', 'created', 121, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0121\", \"nombre\": \"SILLA ALMA ASIENTO NEGRO Y RESPALDO NEGRO\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:43', '2026-06-01 12:49:43'),
(394, 'default', 'created', 'App\\Models\\Insumo', 'created', 122, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0122\", \"nombre\": \"SILLA ATHINA BASE CROMO NEGRA FULL\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:43', '2026-06-01 12:49:43'),
(395, 'default', 'created', 'App\\Models\\Insumo', 'created', 123, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0123\", \"nombre\": \"SILLA ATHINA BASE CROMO NEGRA FULL CON ARO POSAPIE\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:43', '2026-06-01 12:49:43'),
(396, 'default', 'created', 'App\\Models\\Insumo', 'created', 124, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0124\", \"nombre\": \"SILLA ATHINA BASE CROMO TRINEO NEGRA FULL\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:43', '2026-06-01 12:49:43'),
(397, 'default', 'created', 'App\\Models\\Insumo', 'created', 125, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0125\", \"nombre\": \"SILLA BASTONE ALTA BASE CROMO PORT\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:43', '2026-06-01 12:49:43'),
(398, 'default', 'created', 'App\\Models\\Insumo', 'created', 126, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0126\", \"nombre\": \"SILLA BASTONE BAJA BASE CROMO PORT\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:43', '2026-06-01 12:49:43'),
(399, 'default', 'created', 'App\\Models\\Insumo', 'created', 127, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0127\", \"nombre\": \"SILLA GROU FULL NEGRA ROLIC\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:43', '2026-06-01 12:49:43'),
(400, 'default', 'created', 'App\\Models\\Insumo', 'created', 128, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0128\", \"nombre\": \"SILLA GROU FULL NEGRA ROLIC BASE ARO POSAPIE\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:43', '2026-06-01 12:49:43'),
(401, 'default', 'created', 'App\\Models\\Insumo', 'created', 129, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0129\", \"nombre\": \"SILLA GUD EXECUTIVE NOVEC\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:43', '2026-06-01 12:49:43'),
(402, 'default', 'created', 'App\\Models\\Insumo', 'created', 130, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0130\", \"nombre\": \"SILLA INDIA BASE TRINEO FULL NEGRA PORT\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:43', '2026-06-01 12:49:43'),
(403, 'default', 'created', 'App\\Models\\Insumo', 'created', 131, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0131\", \"nombre\": \"SILLA MINT FULL NEGRA CON APOYA CABEZA Y LUMBAR ROL\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:43', '2026-06-01 12:49:43'),
(404, 'default', 'created', 'App\\Models\\Insumo', 'created', 132, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0132\", \"nombre\": \"SILLA MINT FULL NEGRA CON APOYA CABEZA Y LUMBAR Y ARO POSAPIE ROL\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:43', '2026-06-01 12:49:43'),
(405, 'default', 'created', 'App\\Models\\Insumo', 'created', 133, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0133\", \"nombre\": \"SILLA TOKIO FULL NEGRA BASE CROMADA\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:43', '2026-06-01 12:49:43'),
(406, 'default', 'created', 'App\\Models\\Insumo', 'created', 134, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0134\", \"nombre\": \"SILLON LC3 1 CUERPO OPCION 1\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:43', '2026-06-01 12:49:43'),
(407, 'default', 'created', 'App\\Models\\Insumo', 'created', 135, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0135\", \"nombre\": \"SILLON RE028 SACHETI RENAULT\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:43', '2026-06-01 12:49:43'),
(408, 'default', 'created', 'App\\Models\\Insumo', 'created', 136, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0136\", \"nombre\": \"SILLON TCH 05 TRIUMPH\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:43', '2026-06-01 12:49:43'),
(409, 'default', 'created', 'App\\Models\\Insumo', 'created', 137, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0137\", \"nombre\": \"SOBRE PARA ATRIL\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:43', '2026-06-01 12:49:43'),
(410, 'default', 'created', 'App\\Models\\Insumo', 'created', 138, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0138\", \"nombre\": \"SOPORTE CAÑO PERCHERO OVAL\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:43', '2026-06-01 12:49:43'),
(411, 'default', 'created', 'App\\Models\\Insumo', 'created', 139, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0139\", \"nombre\": \"SOPORTE MB01 TRIUMPH\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:43', '2026-06-01 12:49:43'),
(412, 'default', 'created', 'App\\Models\\Insumo', 'created', 140, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0140\", \"nombre\": \"SOPORTE MB04 TRIUMPH\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:43', '2026-06-01 12:49:43'),
(413, 'default', 'created', 'App\\Models\\Insumo', 'created', 141, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0141\", \"nombre\": \"SOPORTE MB20 TRIUMPH\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:43', '2026-06-01 12:49:43'),
(414, 'default', 'created', 'App\\Models\\Insumo', 'created', 142, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0142\", \"nombre\": \"SOPORTE MB22 TRIUMPH\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:43', '2026-06-01 12:49:43'),
(415, 'default', 'created', 'App\\Models\\Insumo', 'created', 143, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0143\", \"nombre\": \"TABURETE MULTIMARCAS\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:43', '2026-06-01 12:49:43'),
(416, 'default', 'created', 'App\\Models\\Insumo', 'created', 144, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0144\", \"nombre\": \"TAPIZADO DEDOS PANEL JETOUR\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:43', '2026-06-01 12:49:43'),
(417, 'default', 'created', 'App\\Models\\Insumo', 'created', 145, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0145\", \"nombre\": \"TARIMA TRIUMPH PLEGADA\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:43', '2026-06-01 12:49:43'),
(418, 'default', 'created', 'App\\Models\\Insumo', 'created', 146, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0146\", \"nombre\": \"TIRADOR BALA\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:43', '2026-06-01 12:49:43'),
(419, 'default', 'created', 'App\\Models\\Insumo', 'created', 147, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0147\", \"nombre\": \"TOTEM HERO TRIUMPH\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:43', '2026-06-01 12:49:43'),
(420, 'default', 'created', 'App\\Models\\Insumo', 'created', 148, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0148\", \"nombre\": \"TOTEM TAO43 TRIUMPH\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:43', '2026-06-01 12:49:43'),
(421, 'default', 'created', 'App\\Models\\Insumo', 'created', 149, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0149\", \"nombre\": \"TUBO LED\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:43', '2026-06-01 12:49:43'),
(422, 'default', 'created', 'App\\Models\\Insumo', 'created', 150, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0150\", \"nombre\": \"VIDRIO TOTEM PLV\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:43', '2026-06-01 12:49:43'),
(423, 'default', 'created', 'App\\Models\\Insumo', 'created', 151, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0151\", \"nombre\": \"VINILOS UV MATE CFMOTO\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:49:43', '2026-06-01 12:49:43'),
(424, 'default', 'created', 'App\\Models\\Insumo', 'created', 152, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0001\", \"nombre\": \"ACCESORIOS PORTA BLISTER\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(425, 'default', 'created', 'App\\Models\\Insumo', 'created', 153, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0153\", \"nombre\": \"ACCESORIOS PORTA CALZADO\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(426, 'default', 'created', 'App\\Models\\Insumo', 'created', 154, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0154\", \"nombre\": \"ANGULO ALUMINIO 25 MM X25 MM\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 1}}', NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(427, 'default', 'created', 'App\\Models\\Insumo', 'created', 155, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0155\", \"nombre\": \"ATRIL JETOUR\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(428, 'default', 'created', 'App\\Models\\Insumo', 'created', 156, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0156\", \"nombre\": \"ATRIL TRIUMPH\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(429, 'default', 'created', 'App\\Models\\Insumo', 'created', 157, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0157\", \"nombre\": \"BALDOSA LISAS CFMOTO\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(430, 'default', 'created', 'App\\Models\\Insumo', 'created', 158, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0158\", \"nombre\": \"BALDOSA SEMILLA DE MELON TARIMA CFMOTO\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(431, 'default', 'created', 'App\\Models\\Insumo', 'created', 159, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0159\", \"nombre\": \"BANQUETA RENAULT\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(432, 'default', 'created', 'App\\Models\\Insumo', 'created', 160, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0160\", \"nombre\": \"BANQUETA SIENA NEGRA\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(433, 'default', 'created', 'App\\Models\\Insumo', 'created', 161, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0161\", \"nombre\": \"BANQUETA TOLIX\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(434, 'default', 'created', 'App\\Models\\Insumo', 'created', 162, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0162\", \"nombre\": \"BANQUETA VENETO NEGRA BASE CROMO\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(435, 'default', 'created', 'App\\Models\\Insumo', 'created', 163, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0163\", \"nombre\": \"BASE CROMADA SILLON SKI\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(436, 'default', 'created', 'App\\Models\\Insumo', 'created', 164, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0164\", \"nombre\": \"BASE MESA COWORKING RENAULT\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(437, 'default', 'created', 'App\\Models\\Insumo', 'created', 165, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0165\", \"nombre\": \"BASE MESA DE CENTRO TST 01 TRIUMPH\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(438, 'default', 'created', 'App\\Models\\Insumo', 'created', 166, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0166\", \"nombre\": \"BASE MESA RE002 RENAULT\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(439, 'default', 'created', 'App\\Models\\Insumo', 'created', 167, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0167\", \"nombre\": \"BASE METALICA PUNTO ENCUENTRO RENAULT\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(440, 'default', 'created', 'App\\Models\\Insumo', 'created', 168, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0168\", \"nombre\": \"BASE PARA SILLAS 4 RAYOS RENAULT\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(441, 'default', 'created', 'App\\Models\\Insumo', 'created', 169, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0169\", \"nombre\": \"BASE PARA SILLAS 5 RAYOS RENAULT\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(442, 'default', 'created', 'App\\Models\\Insumo', 'created', 170, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0170\", \"nombre\": \"BASE PLASTICA RECTANGULAR\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(443, 'default', 'created', 'App\\Models\\Insumo', 'created', 171, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0171\", \"nombre\": \"BASE PLEGADA MESA CENTRO CHERY\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(444, 'default', 'created', 'App\\Models\\Insumo', 'created', 172, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0172\", \"nombre\": \"BASE PULPITO\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(445, 'default', 'created', 'App\\Models\\Insumo', 'created', 173, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0173\", \"nombre\": \"BASE TRINEO JE04\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(446, 'default', 'created', 'App\\Models\\Insumo', 'created', 174, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0174\", \"nombre\": \"BISAGRAS PARA VIDRIOS\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(447, 'default', 'created', 'App\\Models\\Insumo', 'created', 175, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0175\", \"nombre\": \"CALCO ACCESORIES TOTEM TRIUMPH\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(448, 'default', 'created', 'App\\Models\\Insumo', 'created', 176, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0176\", \"nombre\": \"CALCO APPAREL TOTEM TRIUMPH\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(449, 'default', 'created', 'App\\Models\\Insumo', 'created', 177, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0177\", \"nombre\": \"CALCO CORONA COMMUNITY WALL TRIUMPH\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(450, 'default', 'created', 'App\\Models\\Insumo', 'created', 178, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0178\", \"nombre\": \"CALCO SERVICE RECEPTION\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(451, 'default', 'created', 'App\\Models\\Insumo', 'created', 179, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0179\", \"nombre\": \"CALCO TSW 01 TRIUMPH\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(452, 'default', 'created', 'App\\Models\\Insumo', 'created', 180, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0180\", \"nombre\": \"CALCO WELCOME DESK TWD\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(453, 'default', 'created', 'App\\Models\\Insumo', 'created', 181, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0181\", \"nombre\": \"CAÑO PERCHERO OVAL CROMO EN MTS\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(454, 'default', 'created', 'App\\Models\\Insumo', 'created', 182, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0182\", \"nombre\": \"CASCO ALTO CHERY VENDEDOR Y ESPERA\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(455, 'default', 'created', 'App\\Models\\Insumo', 'created', 183, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0183\", \"nombre\": \"CASCO BAJO CHERY SILLA CLIENTE\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(456, 'default', 'created', 'App\\Models\\Insumo', 'created', 184, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0184\", \"nombre\": \"CASCO FIBRA PGT RENAULT\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(457, 'default', 'created', 'App\\Models\\Insumo', 'created', 185, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0185\", \"nombre\": \"CASCO JE04\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(458, 'default', 'created', 'App\\Models\\Insumo', 'created', 186, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0186\", \"nombre\": \"CASCO JE06\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(459, 'default', 'created', 'App\\Models\\Insumo', 'created', 187, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0187\", \"nombre\": \"CERRADURA COLECTIVA FRONTAL\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(460, 'default', 'created', 'App\\Models\\Insumo', 'created', 188, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0188\", \"nombre\": \"CERRADURA COLECTIVA LATERAL\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(461, 'default', 'created', 'App\\Models\\Insumo', 'created', 189, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0189\", \"nombre\": \"CERRADURA TAMBOR FRONTAL\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(462, 'default', 'created', 'App\\Models\\Insumo', 'created', 190, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0190\", \"nombre\": \"CESTO MUEBLE CAFÉ RENAULT\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(463, 'default', 'created', 'App\\Models\\Insumo', 'created', 191, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0191\", \"nombre\": \"CHAPA PERFORADA ALUMINIO MEVACO\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(464, 'default', 'created', 'App\\Models\\Insumo', 'created', 192, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0192\", \"nombre\": \"CONJUNTO ESPUMA P 2 CUERPOS\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(465, 'default', 'created', 'App\\Models\\Insumo', 'created', 193, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0193\", \"nombre\": \"CONJUNTO ESPUMA P 3 CUERPOS\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(466, 'default', 'created', 'App\\Models\\Insumo', 'created', 194, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0194\", \"nombre\": \"CONJUNTO VIDRIOS VITRINA CFMOTO\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(467, 'default', 'created', 'App\\Models\\Insumo', 'created', 195, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0195\", \"nombre\": \"CONTRAPESO ATRIL (HIERROMAS)\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(468, 'default', 'created', 'App\\Models\\Insumo', 'created', 196, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0196\", \"nombre\": \"CUERINA/TELA CHERY PARA 2 CUERPOS\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(469, 'default', 'created', 'App\\Models\\Insumo', 'created', 197, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0197\", \"nombre\": \"CUERINA/TELA CHERY PARA 3 CUERPOS\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(470, 'default', 'created', 'App\\Models\\Insumo', 'created', 198, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0198\", \"nombre\": \"CUPULA DE VIDRIO VITRINA CFMOTO\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(471, 'default', 'created', 'App\\Models\\Insumo', 'created', 199, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0199\", \"nombre\": \"ESTRUCTURA C08B MUEBLE DE ACCESORIOS CORVEN BAJAJ\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(472, 'default', 'created', 'App\\Models\\Insumo', 'created', 200, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0200\", \"nombre\": \"ESTRUCTURA CB11 VITRINA DE REPUESTOS CORVEN BAJAJ\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26');
INSERT INTO `activity_log` (`id`, `log_name`, `description`, `subject_type`, `event`, `subject_id`, `causer_type`, `causer_id`, `properties`, `batch_uuid`, `created_at`, `updated_at`) VALUES
(473, 'default', 'created', 'App\\Models\\Insumo', 'created', 201, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0201\", \"nombre\": \"ESTRUCTURA EXTENSION RECEPCION CB03\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(474, 'default', 'created', 'App\\Models\\Insumo', 'created', 202, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0202\", \"nombre\": \"ESTRUCTURA MODULO C08B\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(475, 'default', 'created', 'App\\Models\\Insumo', 'created', 203, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0203\", \"nombre\": \"ESTRUCTURA PORTA CASCOS MOTOS\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(476, 'default', 'created', 'App\\Models\\Insumo', 'created', 204, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0204\", \"nombre\": \"ESTRUCTURA VITRINA CFMOTO 1 X 2,10\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(477, 'default', 'created', 'App\\Models\\Insumo', 'created', 205, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0205\", \"nombre\": \"ESTRUCTURA VITRINA TSC TRIUMPH\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(478, 'default', 'created', 'App\\Models\\Insumo', 'created', 206, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0206\", \"nombre\": \"GONDOLA TRIUMPH\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(479, 'default', 'created', 'App\\Models\\Insumo', 'created', 207, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0207\", \"nombre\": \"JGO CALCOS TCHW 02 TRIUMPH\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(480, 'default', 'created', 'App\\Models\\Insumo', 'created', 208, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0208\", \"nombre\": \"JGO CALCOS TCHW TRIUMPH\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(481, 'default', 'created', 'App\\Models\\Insumo', 'created', 209, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0209\", \"nombre\": \"JGO ESPUMA SILLON RENAULT RE012\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(482, 'default', 'created', 'App\\Models\\Insumo', 'created', 210, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0210\", \"nombre\": \"JGO MAMPARAS VENDEDOR RENAULT\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(483, 'default', 'created', 'App\\Models\\Insumo', 'created', 211, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0211\", \"nombre\": \"JGO VIDRIOS TSC TRIUMPH\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(484, 'default', 'created', 'App\\Models\\Insumo', 'created', 212, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0212\", \"nombre\": \"JUEGO COSTADO CHAPA HAFELE 40CM\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(485, 'default', 'created', 'App\\Models\\Insumo', 'created', 213, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0213\", \"nombre\": \"JUEGO DE VIDRIOS ESTRUCTURA CB11\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(486, 'default', 'created', 'App\\Models\\Insumo', 'created', 214, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0214\", \"nombre\": \"JUEGO DE VIDRIOS EXTENSION RECEPCION CORVEN BAJAJ\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(487, 'default', 'created', 'App\\Models\\Insumo', 'created', 215, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0215\", \"nombre\": \"JUEGO PATA CAÑO MESA RATONA CORVEN BAJAJ\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(488, 'default', 'created', 'App\\Models\\Insumo', 'created', 216, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0216\", \"nombre\": \"KIT PUERTAS CORREDIZAS 1535 RUEDA MAS\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(489, 'default', 'created', 'App\\Models\\Insumo', 'created', 217, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0217\", \"nombre\": \"KOMPAC GRIS X MT\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(490, 'default', 'created', 'App\\Models\\Insumo', 'created', 218, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0218\", \"nombre\": \"LAMINADO 0,8 WALLIS M868 FORMICA (TERRACOTA) x HOJA\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(491, 'default', 'created', 'App\\Models\\Insumo', 'created', 219, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0219\", \"nombre\": \"LAMINADO CARRARA X M2\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(492, 'default', 'created', 'App\\Models\\Insumo', 'created', 220, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0220\", \"nombre\": \"LAMINADO GRIS HUMO X MT2\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(493, 'default', 'created', 'App\\Models\\Insumo', 'created', 221, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0221\", \"nombre\": \"LAMINADO PERFECT SENSE X M2\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(494, 'default', 'created', 'App\\Models\\Insumo', 'created', 222, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0222\", \"nombre\": \"LOGO ACCESORIOS MODULO CFMOTO\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(495, 'default', 'created', 'App\\Models\\Insumo', 'created', 223, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0223\", \"nombre\": \"LOGO ATRIL BAJAJ\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(496, 'default', 'created', 'App\\Models\\Insumo', 'created', 224, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0224\", \"nombre\": \"LOGO ATRIL CFMOTO\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(497, 'default', 'created', 'App\\Models\\Insumo', 'created', 225, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0225\", \"nombre\": \"LOGO ATRIL CORVEN\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(498, 'default', 'created', 'App\\Models\\Insumo', 'created', 226, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0226\", \"nombre\": \"LOGO CFMOTO PANEL PARED\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(499, 'default', 'created', 'App\\Models\\Insumo', 'created', 227, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0227\", \"nombre\": \"LOGO CFMOTO TABLERO PARED\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(500, 'default', 'created', 'App\\Models\\Insumo', 'created', 228, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0228\", \"nombre\": \"LOGO CHERY RECEPCION\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(501, 'default', 'created', 'App\\Models\\Insumo', 'created', 229, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0229\", \"nombre\": \"LOGO CLX PANEL CFMOTO\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(502, 'default', 'created', 'App\\Models\\Insumo', 'created', 230, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0230\", \"nombre\": \"LOGO JE01 JEOTUR\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(503, 'default', 'created', 'App\\Models\\Insumo', 'created', 231, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0231\", \"nombre\": \"LOGO MUEBLE DE ACCESORIOS CORVEN BAJAJ CB10\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(504, 'default', 'created', 'App\\Models\\Insumo', 'created', 232, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0232\", \"nombre\": \"LOGO MURO CFMOTO\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(505, 'default', 'created', 'App\\Models\\Insumo', 'created', 233, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0233\", \"nombre\": \"LOGO RECEPCION CFMOTO\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(506, 'default', 'created', 'App\\Models\\Insumo', 'created', 234, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0234\", \"nombre\": \"LOGO TARIMA BAJAJ\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(507, 'default', 'created', 'App\\Models\\Insumo', 'created', 235, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0235\", \"nombre\": \"LOGO TARIMA CORVEN\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(508, 'default', 'created', 'App\\Models\\Insumo', 'created', 236, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0236\", \"nombre\": \"LOGO TRIUMPH 50X50\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(509, 'default', 'created', 'App\\Models\\Insumo', 'created', 237, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0237\", \"nombre\": \"MAA MODULO MULTIMARCAS\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(510, 'default', 'created', 'App\\Models\\Insumo', 'created', 238, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0238\", \"nombre\": \"MAC MODULO MULTIMARCAS\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(511, 'default', 'created', 'App\\Models\\Insumo', 'created', 239, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0239\", \"nombre\": \"MANIQUI CON CAÑO TRIUMPH\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(512, 'default', 'created', 'App\\Models\\Insumo', 'created', 240, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0240\", \"nombre\": \"MANIQUI TRIUMPH\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(513, 'default', 'created', 'App\\Models\\Insumo', 'created', 241, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0241\", \"nombre\": \"PANTALLA TAO43 TRIUMPH\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(514, 'default', 'created', 'App\\Models\\Insumo', 'created', 242, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0242\", \"nombre\": \"PASACABLE BOX240 WIRE\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(515, 'default', 'created', 'App\\Models\\Insumo', 'created', 243, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0243\", \"nombre\": \"PASACABLE RECTANGULAR PLASTICO negro CON CEPILLO WIRENEX\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(516, 'default', 'created', 'App\\Models\\Insumo', 'created', 244, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0244\", \"nombre\": \"PASACABLES 60MM NEGRO\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(517, 'default', 'created', 'App\\Models\\Insumo', 'created', 245, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0245\", \"nombre\": \"PATA CHAPA CIRCULAR RENAULT\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(518, 'default', 'created', 'App\\Models\\Insumo', 'created', 246, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0246\", \"nombre\": \"PATA DE CAÑO ESCRITORIO 70 30\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(519, 'default', 'created', 'App\\Models\\Insumo', 'created', 247, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0247\", \"nombre\": \"PATA METALICA MULTIMARCAS MODULOS\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(520, 'default', 'created', 'App\\Models\\Insumo', 'created', 248, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0248\", \"nombre\": \"PATA WORK STATION JGO MULTIMARCAS\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(521, 'default', 'created', 'App\\Models\\Insumo', 'created', 249, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0249\", \"nombre\": \"PATAS MESA LOUNGE RENAULT BLANCA\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(522, 'default', 'created', 'App\\Models\\Insumo', 'created', 250, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0250\", \"nombre\": \"PATAS MESA LOUNGE RENAULT NEGRA\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(523, 'default', 'created', 'App\\Models\\Insumo', 'created', 251, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0251\", \"nombre\": \"PATAS MESA LOUNGE RENAULT ROBLE\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(524, 'default', 'created', 'App\\Models\\Insumo', 'created', 252, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0252\", \"nombre\": \"PERFIL AUTOADHESIVO CAJONERAS X MTS\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(525, 'default', 'created', 'App\\Models\\Insumo', 'created', 253, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0253\", \"nombre\": \"PERFIL CURVO (TABLERO CFMOTO)\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(526, 'default', 'created', 'App\\Models\\Insumo', 'created', 254, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0254\", \"nombre\": \"PERFIL PLACA RANURADA\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(527, 'default', 'created', 'App\\Models\\Insumo', 'created', 255, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0255\", \"nombre\": \"PERFIL PORTA LED X 3M LDP\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(528, 'default', 'created', 'App\\Models\\Insumo', 'created', 256, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0256\", \"nombre\": \"PERFIL TAPACANTOS 18MM X MTS\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(529, 'default', 'created', 'App\\Models\\Insumo', 'created', 257, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0257\", \"nombre\": \"PINTURA BAJAJ AZUL PANTONE 2945C\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(530, 'default', 'created', 'App\\Models\\Insumo', 'created', 258, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0258\", \"nombre\": \"PINTURA CORVEN NARANJA PANTONE 021 EN LTS\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(531, 'default', 'created', 'App\\Models\\Insumo', 'created', 259, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0259\", \"nombre\": \"PINTURA GRIS CF CHAPA CODIGO REEM18538\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(532, 'default', 'created', 'App\\Models\\Insumo', 'created', 260, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0260\", \"nombre\": \"PINTURA GRIS CF MADERA CODIGO RES18538\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(533, 'default', 'created', 'App\\Models\\Insumo', 'created', 261, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0261\", \"nombre\": \"PINTURA NEGRA ATRILES MOTOS\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(534, 'default', 'created', 'App\\Models\\Insumo', 'created', 262, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0262\", \"nombre\": \"PINTURA VERDE TRIUMPH SW 6172 SATINADO\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(535, 'default', 'created', 'App\\Models\\Insumo', 'created', 263, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0263\", \"nombre\": \"PLOTEO AMARILLO PARA MAMPARAS RENAULT\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(536, 'default', 'created', 'App\\Models\\Insumo', 'created', 264, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0264\", \"nombre\": \"PLOTEO NEGRO MAMPARAS RENAULT\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(537, 'default', 'created', 'App\\Models\\Insumo', 'created', 265, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0265\", \"nombre\": \"PUFF TCH 06 TRIUMPH\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(538, 'default', 'created', 'App\\Models\\Insumo', 'created', 266, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0266\", \"nombre\": \"RECEPCION CHERY SILLA CLIENTE\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(539, 'default', 'created', 'App\\Models\\Insumo', 'created', 267, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0267\", \"nombre\": \"REGATON REDONDOS PLASTICOS GRANDES\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(540, 'default', 'created', 'App\\Models\\Insumo', 'created', 268, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0268\", \"nombre\": \"REGATON REGULABLE 5/16\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(541, 'default', 'created', 'App\\Models\\Insumo', 'created', 269, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0269\", \"nombre\": \"SEPARADORES CHICOS ESCRITORIO 3D RENAULT\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(542, 'default', 'created', 'App\\Models\\Insumo', 'created', 270, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0270\", \"nombre\": \"SEPARADORES GRANDES ESCRITORIO GRANDE 3D RENAULT\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(543, 'default', 'created', 'App\\Models\\Insumo', 'created', 271, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0271\", \"nombre\": \"SILLA ADRIX\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(544, 'default', 'created', 'App\\Models\\Insumo', 'created', 272, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0272\", \"nombre\": \"SILLA ALMA ASIENTO NEGRO Y RESPALDO NEGRO\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(545, 'default', 'created', 'App\\Models\\Insumo', 'created', 273, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0273\", \"nombre\": \"SILLA ATHINA BASE CROMO NEGRA FULL\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(546, 'default', 'created', 'App\\Models\\Insumo', 'created', 274, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0274\", \"nombre\": \"SILLA ATHINA BASE CROMO NEGRA FULL CON ARO POSAPIE\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(547, 'default', 'created', 'App\\Models\\Insumo', 'created', 275, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0275\", \"nombre\": \"SILLA ATHINA BASE CROMO TRINEO NEGRA FULL\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(548, 'default', 'created', 'App\\Models\\Insumo', 'created', 276, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0276\", \"nombre\": \"SILLA BASTONE ALTA BASE CROMO PORT\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(549, 'default', 'created', 'App\\Models\\Insumo', 'created', 277, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0277\", \"nombre\": \"SILLA BASTONE BAJA BASE CROMO PORT\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(550, 'default', 'created', 'App\\Models\\Insumo', 'created', 278, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0278\", \"nombre\": \"SILLA GROU FULL NEGRA ROLIC\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(551, 'default', 'created', 'App\\Models\\Insumo', 'created', 279, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0279\", \"nombre\": \"SILLA GROU FULL NEGRA ROLIC BASE ARO POSAPIE\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(552, 'default', 'created', 'App\\Models\\Insumo', 'created', 280, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0280\", \"nombre\": \"SILLA GUD EXECUTIVE NOVEC\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(553, 'default', 'created', 'App\\Models\\Insumo', 'created', 281, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0281\", \"nombre\": \"SILLA INDIA BASE TRINEO FULL NEGRA PORT\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(554, 'default', 'created', 'App\\Models\\Insumo', 'created', 282, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0282\", \"nombre\": \"SILLA MINT FULL NEGRA CON APOYA CABEZA Y LUMBAR ROL\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(555, 'default', 'created', 'App\\Models\\Insumo', 'created', 283, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0283\", \"nombre\": \"SILLA MINT FULL NEGRA CON APOYA CABEZA Y LUMBAR Y ARO POSAPIE ROL\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(556, 'default', 'created', 'App\\Models\\Insumo', 'created', 284, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0284\", \"nombre\": \"SILLA TOKIO FULL NEGRA BASE CROMADA\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(557, 'default', 'created', 'App\\Models\\Insumo', 'created', 285, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0285\", \"nombre\": \"SILLON LC3 1 CUERPO OPCION 1\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(558, 'default', 'created', 'App\\Models\\Insumo', 'created', 286, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0286\", \"nombre\": \"SILLON RE028 SACHETI RENAULT\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(559, 'default', 'created', 'App\\Models\\Insumo', 'created', 287, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0287\", \"nombre\": \"SILLON TCH 05 TRIUMPH\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(560, 'default', 'created', 'App\\Models\\Insumo', 'created', 288, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0288\", \"nombre\": \"SOBRE PARA ATRIL\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(561, 'default', 'created', 'App\\Models\\Insumo', 'created', 289, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0289\", \"nombre\": \"SOPORTE CAÑO PERCHERO OVAL\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(562, 'default', 'created', 'App\\Models\\Insumo', 'created', 290, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0290\", \"nombre\": \"SOPORTE MB01 TRIUMPH\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(563, 'default', 'created', 'App\\Models\\Insumo', 'created', 291, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0291\", \"nombre\": \"SOPORTE MB04 TRIUMPH\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(564, 'default', 'created', 'App\\Models\\Insumo', 'created', 292, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0292\", \"nombre\": \"SOPORTE MB20 TRIUMPH\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(565, 'default', 'created', 'App\\Models\\Insumo', 'created', 293, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0293\", \"nombre\": \"SOPORTE MB22 TRIUMPH\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(566, 'default', 'created', 'App\\Models\\Insumo', 'created', 294, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0294\", \"nombre\": \"TABURETE MULTIMARCAS\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(567, 'default', 'created', 'App\\Models\\Insumo', 'created', 295, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0295\", \"nombre\": \"TAPIZADO DEDOS PANEL JETOUR\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(568, 'default', 'created', 'App\\Models\\Insumo', 'created', 296, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0296\", \"nombre\": \"TARIMA TRIUMPH PLEGADA\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(569, 'default', 'created', 'App\\Models\\Insumo', 'created', 297, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0297\", \"nombre\": \"TIRADOR BALA\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(570, 'default', 'created', 'App\\Models\\Insumo', 'created', 298, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0298\", \"nombre\": \"TOTEM HERO TRIUMPH\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(571, 'default', 'created', 'App\\Models\\Insumo', 'created', 299, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0299\", \"nombre\": \"TOTEM TAO43 TRIUMPH\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(572, 'default', 'created', 'App\\Models\\Insumo', 'created', 300, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0300\", \"nombre\": \"TUBO LED\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(573, 'default', 'created', 'App\\Models\\Insumo', 'created', 301, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0301\", \"nombre\": \"VIDRIO TOTEM PLV\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(574, 'default', 'created', 'App\\Models\\Insumo', 'created', 302, NULL, NULL, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0302\", \"nombre\": \"VINILOS UV MATE CFMOTO\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 0, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(575, 'default', 'created', 'App\\Models\\Marca', 'created', 12, 'App\\Models\\User', 1, '{\"attributes\": {\"logo\": null, \"activo\": true, \"nombre\": \"CF Moto\", \"manual_pdf\": null}}', NULL, '2026-06-01 13:21:53', '2026-06-01 13:21:53'),
(576, 'default', 'created', 'App\\Models\\Marca', 'created', 13, 'App\\Models\\User', 1, '{\"attributes\": {\"logo\": null, \"activo\": true, \"nombre\": \"Corven-Bajaj\", \"manual_pdf\": null}}', NULL, '2026-06-01 13:22:01', '2026-06-01 13:22:01'),
(577, 'default', 'updated', 'App\\Models\\Marca', 'updated', 13, 'App\\Models\\User', 1, '{\"old\": {\"nombre\": \"Corven-Bajaj\"}, \"attributes\": {\"nombre\": \"Citroen\"}}', NULL, '2026-06-01 13:22:08', '2026-06-01 13:22:08'),
(578, 'default', 'created', 'App\\Models\\Marca', 'created', 14, 'App\\Models\\User', 1, '{\"attributes\": {\"logo\": null, \"activo\": true, \"nombre\": \"Peugeot\", \"manual_pdf\": null}}', NULL, '2026-06-01 13:22:21', '2026-06-01 13:22:21'),
(579, 'default', 'created', 'App\\Models\\Marca', 'created', 15, 'App\\Models\\User', 1, '{\"attributes\": {\"logo\": null, \"activo\": true, \"nombre\": \"Multimarcas\", \"manual_pdf\": null}}', NULL, '2026-06-01 13:22:28', '2026-06-01 13:22:28'),
(580, 'default', 'created', 'App\\Models\\Marca', 'created', 16, 'App\\Models\\User', 1, '{\"attributes\": {\"logo\": null, \"activo\": true, \"nombre\": \"DongFeng\", \"manual_pdf\": null}}', NULL, '2026-06-01 13:22:35', '2026-06-01 13:22:35'),
(581, 'default', 'created', 'App\\Models\\Mobiliario', 'created', 5, 'App\\Models\\User', 1, '{\"attributes\": {\"estado\": \"activo\", \"imagen\": null, \"nombre\": \"SILLON 3 CUERPOS\", \"precio\": null, \"marca_id\": 1, \"descripcion\": \"Sillón fabricado según plano, tapizado en cuerina marrón en apoya brazos y almohadones. Resto de sillón tapizado en tela gris clara\", \"categoria_id\": 10, \"observaciones\": null, \"codigo_interno\": \"CH02\", \"version_actual\": 1}}', NULL, '2026-06-01 15:25:53', '2026-06-01 15:25:53'),
(582, 'default', 'created', 'App\\Models\\Mobiliario', 'created', 6, 'App\\Models\\User', 1, '{\"attributes\": {\"estado\": \"activo\", \"imagen\": null, \"nombre\": \"MESA BAJA\", \"precio\": null, \"marca_id\": 1, \"descripcion\": \"Mesa de melamina revestida en solido símil mármol y negro antihuellas\", \"categoria_id\": 8, \"observaciones\": null, \"codigo_interno\": \"CH05\", \"version_actual\": 1}}', NULL, '2026-06-01 15:28:48', '2026-06-01 15:28:48'),
(583, 'default', 'created', 'App\\Models\\Mobiliario', 'created', 7, 'App\\Models\\User', 1, '{\"attributes\": {\"estado\": \"activo\", \"imagen\": null, \"nombre\": \"SILLA SALA DE ESPERA\", \"precio\": null, \"marca_id\": 1, \"descripcion\": \"Diseño segun manual\", \"categoria_id\": 10, \"observaciones\": null, \"codigo_interno\": \"CH31\", \"version_actual\": 1}}', NULL, '2026-06-01 15:30:30', '2026-06-01 15:30:30'),
(584, 'default', 'created', 'App\\Models\\Mobiliario', 'created', 8, 'App\\Models\\User', 1, '{\"attributes\": {\"estado\": \"activo\", \"imagen\": null, \"nombre\": \"SILLA ESCRITORIO DE VENDEDORES\", \"precio\": null, \"marca_id\": 1, \"descripcion\": \"Diseño según manual\", \"categoria_id\": 10, \"observaciones\": null, \"codigo_interno\": \"CH08\", \"version_actual\": 1}}', NULL, '2026-06-01 15:31:25', '2026-06-01 15:31:25'),
(585, 'default', 'created', 'App\\Models\\Mobiliario', 'created', 9, 'App\\Models\\User', 1, '{\"attributes\": {\"estado\": \"activo\", \"imagen\": null, \"nombre\": \"SILLA CLIENTES\", \"precio\": null, \"marca_id\": 1, \"descripcion\": \"Diseño según manual\", \"categoria_id\": 10, \"observaciones\": null, \"codigo_interno\": \"CH32\", \"version_actual\": 1}}', NULL, '2026-06-01 15:32:06', '2026-06-01 15:32:06'),
(586, 'default', 'created', 'App\\Models\\CategoriaMobiliario', 'created', 11, 'App\\Models\\User', 1, '{\"attributes\": {\"slug\": \"mueble-de-guardado\", \"icono\": null, \"orden\": 0, \"activo\": true, \"nombre\": \"Mueble de guardado\", \"descripcion\": null}}', NULL, '2026-06-01 15:33:25', '2026-06-01 15:33:25');
INSERT INTO `activity_log` (`id`, `log_name`, `description`, `subject_type`, `event`, `subject_id`, `causer_type`, `causer_id`, `properties`, `batch_uuid`, `created_at`, `updated_at`) VALUES
(587, 'default', 'created', 'App\\Models\\Mobiliario', 'created', 10, 'App\\Models\\User', 1, '{\"attributes\": {\"estado\": \"activo\", \"imagen\": null, \"nombre\": \"MUEBLE DE TV\", \"precio\": null, \"marca_id\": 1, \"descripcion\": \"Mueble de guardado, fabricado en melamina nogal terracota y partes en melamina negra, con puertas de abrir y espacios para guardado\", \"categoria_id\": 11, \"observaciones\": \"Mueble de TV SIMPLE - No lleva TV, escritorio, ni puertas de ambos lados. (VER PUERTAS)\", \"codigo_interno\": \"CH09-2800\", \"version_actual\": 1}}', NULL, '2026-06-01 15:35:52', '2026-06-01 15:35:52'),
(588, 'default', 'created', 'App\\Models\\Mobiliario', 'created', 11, 'App\\Models\\User', 1, '{\"attributes\": {\"estado\": \"activo\", \"imagen\": null, \"nombre\": \"MUEBLE DE TV DOBLE\", \"precio\": null, \"marca_id\": 1, \"descripcion\": \"Mueble de guardado BIFAZ, fabricado en melamina nogal terracota y partes en melamina negra, con puertas de abrir y espacios para guardado.\", \"categoria_id\": 11, \"observaciones\": \"Lleva TV ,puertas y escritorios de ambos lados.\", \"codigo_interno\": \"CH10-2800\", \"version_actual\": 1}}', NULL, '2026-06-01 15:37:46', '2026-06-01 15:37:46'),
(589, 'default', 'created', 'App\\Models\\CategoriaMobiliario', 'created', 12, 'App\\Models\\User', 1, '{\"attributes\": {\"slug\": \"mueble-de-cafe\", \"icono\": null, \"orden\": 0, \"activo\": true, \"nombre\": \"MUEBLE DE CAFE\", \"descripcion\": null}}', NULL, '2026-06-01 15:42:09', '2026-06-01 15:42:09'),
(590, 'default', 'created', 'App\\Models\\Mobiliario', 'created', 12, 'App\\Models\\User', 1, '{\"attributes\": {\"estado\": \"activo\", \"imagen\": null, \"nombre\": \"MUEBLE DE TV Y CAFE\", \"precio\": null, \"marca_id\": 1, \"descripcion\": \"Mueble de guardado, fabricado en melamina nogal terracota y partes en melamina negra, con puertas de abrir y espacios para guardado.\", \"categoria_id\": 12, \"observaciones\": null, \"codigo_interno\": \"CH30\", \"version_actual\": 1}}', NULL, '2026-06-01 15:44:32', '2026-06-01 15:44:32'),
(591, 'default', 'created', 'App\\Models\\Mobiliario', 'created', 13, 'App\\Models\\User', 1, '{\"attributes\": {\"estado\": \"activo\", \"imagen\": null, \"nombre\": \"MESA ESCRITORIO\", \"precio\": null, \"marca_id\": 1, \"sector_id\": null, \"descripcion\": \"Fabricado en melamina negra lisa anti huellas y cantos a 45° con base tipo piramidal según manual\", \"categoria_id\": 1, \"observaciones\": \"Medida especial 1500\", \"codigo_interno\": \"CH11\", \"version_actual\": 1}}', NULL, '2026-06-01 16:12:33', '2026-06-01 16:12:33'),
(592, 'default', 'created', 'App\\Models\\Mobiliario', 'created', 14, 'App\\Models\\User', 1, '{\"attributes\": {\"estado\": \"activo\", \"imagen\": null, \"nombre\": \"SILLA RECEPCION\", \"precio\": null, \"marca_id\": 1, \"sector_id\": 1, \"descripcion\": \"Diseño según manual\", \"categoria_id\": 10, \"observaciones\": null, \"codigo_interno\": \"CH19\", \"version_actual\": 1}}', NULL, '2026-06-01 16:14:01', '2026-06-01 16:14:01'),
(593, 'default', 'created', 'App\\Models\\Insumo', 'created', 303, 'App\\Models\\User', 1, '{\"attributes\": {\"tag\": null, \"activo\": true, \"codigo\": \"INS-0303\", \"nombre\": \"PATA MESA BAJA CHERY\", \"ubicacion\": null, \"precio_costo\": null, \"proveedor_id\": null, \"stock_actual\": 0, \"stock_minimo\": 1, \"observaciones\": null, \"tipo_silla_id\": null, \"unidad_medida_id\": 8}}', NULL, '2026-06-01 16:16:45', '2026-06-01 16:16:45'),
(594, 'default', 'created', 'App\\Models\\Mobiliario', 'created', 15, 'App\\Models\\User', 1, '{\"attributes\": {\"estado\": \"activo\", \"imagen\": null, \"nombre\": \"MESA NEGRA\", \"precio\": null, \"marca_id\": 1, \"sector_id\": null, \"descripcion\": \"Tapa superior de melamina ANTIHUELLA con base metálica pintada a horno con pintura epoxi color negra\", \"categoria_id\": 8, \"observaciones\": \"Tapa con canto a 45°\", \"codigo_interno\": \"CH23\", \"version_actual\": 1}}', NULL, '2026-06-01 16:16:58', '2026-06-01 16:16:58'),
(595, 'default', 'created', 'App\\Models\\CategoriaMobiliario', 'created', 13, 'App\\Models\\User', 1, '{\"attributes\": {\"slug\": \"panel\", \"icono\": null, \"orden\": 0, \"activo\": true, \"nombre\": \"Panel\", \"descripcion\": null}}', NULL, '2026-06-01 16:17:30', '2026-06-01 16:17:30'),
(596, 'default', 'created', 'App\\Models\\Mobiliario', 'created', 16, 'App\\Models\\User', 1, '{\"attributes\": {\"estado\": \"activo\", \"imagen\": null, \"nombre\": \"MURO DE MARCA\", \"precio\": null, \"marca_id\": 1, \"sector_id\": null, \"descripcion\": \"Paneles de melamina color aluminio, con 10 led verticales y logo corpóreo en acrílico cortado a laser\", \"categoria_id\": 13, \"observaciones\": \"Revisar medidas en layout\", \"codigo_interno\": \"CH24\", \"version_actual\": 1}}', NULL, '2026-06-01 16:32:46', '2026-06-01 16:32:46'),
(597, 'default', 'created', 'App\\Models\\Mobiliario', 'created', 17, 'App\\Models\\User', 1, '{\"attributes\": {\"estado\": \"activo\", \"imagen\": null, \"nombre\": \"MUEBLE DE TV Y CAFE\", \"precio\": null, \"marca_id\": 1, \"descripcion\": \"Mueble de guardado, fabricado en melamina nogal terracota y partes en melamina negra, con puertas de abrir y espacios para guardado\", \"categoria_id\": 12, \"observaciones\": \"Verificar CENEFA y AJUSTE con layout\", \"codigo_interno\": \"CH30-2400\", \"version_actual\": 1}}', NULL, '2026-06-01 17:27:17', '2026-06-01 17:27:17'),
(598, 'default', 'created', 'App\\Models\\Mobiliario', 'created', 18, 'App\\Models\\User', 1, '{\"attributes\": {\"estado\": \"activo\", \"imagen\": null, \"nombre\": \"ESCRITORIO POSTVENTA MOD1\", \"precio\": null, \"marca_id\": 1, \"descripcion\": \"Recepción fabricada en melamina everest, con partes en melamina color gris humo\", \"categoria_id\": 2, \"observaciones\": \"Verificar módulos para su producción \", \"codigo_interno\": \"CH15\", \"version_actual\": 1}}', NULL, '2026-06-01 17:30:12', '2026-06-01 17:30:12'),
(599, 'default', 'created', 'App\\Models\\Mobiliario', 'created', 19, 'App\\Models\\User', 1, '{\"attributes\": {\"estado\": \"activo\", \"imagen\": null, \"nombre\": \"ESCRITORIO POSTVENTA MOD2\", \"precio\": null, \"marca_id\": 1, \"descripcion\": \"Recepción fabricada en melamina everest, con partes en melamina color gris humo\", \"categoria_id\": 2, \"observaciones\": \"Verificar módulos para su producción \", \"codigo_interno\": \"CH16\", \"version_actual\": 1}}', NULL, '2026-06-01 17:32:45', '2026-06-01 17:32:45'),
(600, 'default', 'created', 'App\\Models\\Mobiliario', 'created', 20, 'App\\Models\\User', 1, '{\"attributes\": {\"estado\": \"activo\", \"imagen\": null, \"nombre\": \"ESCRITORIO POSTVENTA MOD2\", \"precio\": null, \"marca_id\": 1, \"descripcion\": \"Recepción fabricada en melamina everest, con partes en melamina color gris humo\", \"categoria_id\": 2, \"observaciones\": \"Verificar módulos para su producción \", \"codigo_interno\": \"CH17\", \"version_actual\": 1}}', NULL, '2026-06-01 17:34:08', '2026-06-01 17:34:08'),
(601, 'default', 'created', 'App\\Models\\Mobiliario', 'created', 21, 'App\\Models\\User', 1, '{\"attributes\": {\"estado\": \"activo\", \"imagen\": null, \"nombre\": \"MUEBLE DE TV DOBLE\", \"precio\": null, \"marca_id\": 1, \"descripcion\": \"Mueble de guardado BIFAZ, fabricado en melamina nogal terracota y partes en melamina negra, con puertas de abrir y espacios para guardado\", \"categoria_id\": 11, \"observaciones\": null, \"codigo_interno\": \"CH10\", \"version_actual\": 1}}', NULL, '2026-06-01 17:37:32', '2026-06-01 17:37:32'),
(602, 'default', 'created', 'App\\Models\\Mobiliario', 'created', 22, 'App\\Models\\User', 1, '{\"attributes\": {\"estado\": \"activo\", \"imagen\": null, \"nombre\": \"MESA ESCRITORIO\", \"precio\": null, \"marca_id\": 1, \"descripcion\": \"Fabricado en melamina negra lisa anti huellas y cantos a 45° con base tipo piramidal según manual\", \"categoria_id\": 1, \"observaciones\": \"MEDIDA ESPECIAL 2200 * 800\", \"codigo_interno\": \"CH11-2200\", \"version_actual\": 1}}', NULL, '2026-06-01 17:40:17', '2026-06-01 17:40:17'),
(603, 'default', 'created', 'App\\Models\\Mobiliario', 'created', 23, 'App\\Models\\User', 1, '{\"attributes\": {\"estado\": \"activo\", \"imagen\": null, \"nombre\": \"MUEBLE TV DOBLE\", \"precio\": null, \"marca_id\": 1, \"descripcion\": \"Mueble de guardado BIFAZ, fabricado en melamina nogal terracota y partes en melamina negra, con puertas de abrir y espacios para guardado\", \"categoria_id\": 11, \"observaciones\": \"Lleva TV ,puertas y escritorios de ambos lados.\\nVerificar CENEFA y AJUSTE con layout\", \"codigo_interno\": \"CH10-2400\", \"version_actual\": 1}}', NULL, '2026-06-01 17:43:27', '2026-06-01 17:43:27'),
(604, 'default', 'created', 'App\\Models\\Mobiliario', 'created', 24, 'App\\Models\\User', 1, '{\"attributes\": {\"estado\": \"activo\", \"imagen\": null, \"nombre\": \"RECEPCION\", \"precio\": null, \"marca_id\": 1, \"descripcion\": \"Recepción fabricada en melamina silver, con partes en solido color negro, con logo corpóreo en acrílico cortado a laser\", \"categoria_id\": 2, \"observaciones\": null, \"codigo_interno\": \"CH13-2000\", \"version_actual\": 1}}', NULL, '2026-06-01 17:47:20', '2026-06-01 17:47:20'),
(605, 'default', 'created', 'App\\Models\\Proyecto', 'created', 6, 'App\\Models\\User', 1, '{\"attributes\": {\"estado\": \"presupuestar\", \"marca_id\": 1, \"timeline\": null, \"manual_pdf\": [\"proyectos/manuales/efbb5a46-c60c-4455-83c4-17da46fecff5_2025.11.11_Mueble Vendedores Chery.pdf\", \"proyectos/manuales/fef21eb1-91c5-41e5-b935-b54bbfdf3fde_MOBILIARIO CHERY SOLO MUEBLES.pdf\"], \"fecha_inicio\": null, \"observaciones\": null, \"codigo_interno\": \"CHY-2026\", \"fecha_entrega_real\": null, \"fecha_entrega_estimada\": null}}', NULL, '2026-06-01 17:51:05', '2026-06-01 17:51:05'),
(606, 'default', 'deleted', 'App\\Models\\Proyecto', 'deleted', 6, 'App\\Models\\User', 1, '{\"old\": {\"estado\": \"presupuestar\", \"marca_id\": 1, \"timeline\": null, \"manual_pdf\": [\"proyectos/manuales/efbb5a46-c60c-4455-83c4-17da46fecff5_2025.11.11_Mueble Vendedores Chery.pdf\", \"proyectos/manuales/fef21eb1-91c5-41e5-b935-b54bbfdf3fde_MOBILIARIO CHERY SOLO MUEBLES.pdf\"], \"fecha_inicio\": null, \"observaciones\": null, \"codigo_interno\": \"CHY-2026\", \"fecha_entrega_real\": null, \"fecha_entrega_estimada\": null}}', NULL, '2026-06-01 18:48:27', '2026-06-01 18:48:27'),
(607, 'default', 'created', 'App\\Models\\Proyecto', 'created', 7, 'App\\Models\\User', 1, '{\"attributes\": {\"estado\": \"presupuestar\", \"marca_id\": 1, \"timeline\": null, \"manual_pdf\": [], \"fecha_inicio\": null, \"observaciones\": null, \"codigo_interno\": \"CHY-26\", \"fecha_entrega_real\": null, \"fecha_entrega_estimada\": null}}', NULL, '2026-06-01 18:50:52', '2026-06-01 18:50:52'),
(608, 'default', 'created', 'App\\Models\\Agencia', 'created', 9, 'App\\Models\\User', 1, '{\"attributes\": {\"activo\": true, \"nombre\": \"AVANT\", \"ciudad_id\": 23, \"direccion\": \"Av. Emilio Carrafa 1913\", \"etiquetas\": \"\", \"prioridad\": 3, \"proyecto_id\": 7, \"responsable\": \"Florencia Pitt\", \"provincia_id\": 6, \"observaciones\": null}}', NULL, '2026-06-01 18:56:54', '2026-06-01 18:56:54'),
(609, 'default', 'created', 'App\\Models\\Agencia', 'created', 10, 'App\\Models\\User', 1, '{\"attributes\": {\"activo\": true, \"nombre\": \"ROMA\", \"ciudad_id\": 42, \"direccion\": \"Av. Bartolome Mitre 441, M5600\", \"etiquetas\": \"\", \"prioridad\": 3, \"proyecto_id\": 7, \"responsable\": \"Adrian Pablo Sat\", \"provincia_id\": 13, \"observaciones\": null}}', NULL, '2026-06-01 18:57:48', '2026-06-01 18:57:48'),
(610, 'default', 'created', 'App\\Models\\Agencia', 'created', 11, 'App\\Models\\User', 1, '{\"attributes\": {\"activo\": true, \"nombre\": \"SURFENG\", \"ciudad_id\": 43, \"direccion\": \"Av. San Marin 4010\", \"etiquetas\": \"\", \"prioridad\": 3, \"proyecto_id\": 7, \"responsable\": \"Paula Halpern\", \"provincia_id\": 13, \"observaciones\": null}}', NULL, '2026-06-01 18:58:40', '2026-06-01 18:58:40'),
(611, 'default', 'created', 'App\\Models\\Agencia', 'created', 12, 'App\\Models\\User', 1, '{\"attributes\": {\"activo\": true, \"nombre\": \"ESCOBAR\", \"ciudad_id\": 65, \"direccion\": \"Av. Gdor. Freyre 2922\", \"etiquetas\": \"\", \"prioridad\": 3, \"proyecto_id\": 7, \"responsable\": \"Jose Luis Aiello\", \"provincia_id\": 21, \"observaciones\": null}}', NULL, '2026-06-01 18:59:41', '2026-06-01 18:59:41'),
(612, 'default', 'created', 'App\\Models\\Agencia', 'created', 13, 'App\\Models\\User', 1, '{\"attributes\": {\"activo\": true, \"nombre\": \"ESCOBAR\", \"ciudad_id\": 65, \"direccion\": \"Av. Gdor. Freyre 2922\", \"etiquetas\": \"\", \"prioridad\": 3, \"proyecto_id\": 7, \"responsable\": \"Jose Luis Aiello\", \"provincia_id\": 21, \"observaciones\": null}}', NULL, '2026-06-01 19:01:38', '2026-06-01 19:01:38'),
(613, 'default', 'created', 'App\\Models\\Agencia', 'created', 14, 'App\\Models\\User', 1, '{\"attributes\": {\"activo\": true, \"nombre\": \"ESCOBAR\", \"ciudad_id\": 65, \"direccion\": \"Av. Gdor. Freyre 2922\", \"etiquetas\": \"\", \"prioridad\": 3, \"proyecto_id\": 7, \"responsable\": \"Jose Luis Aiello\", \"provincia_id\": 21, \"observaciones\": null}}', NULL, '2026-06-01 19:02:23', '2026-06-01 19:02:23'),
(614, 'default', 'created', 'App\\Models\\Agencia', 'created', 15, 'App\\Models\\User', 1, '{\"attributes\": {\"activo\": true, \"nombre\": \"ESCOBAR\", \"ciudad_id\": 65, \"direccion\": \"Av. Gdor. Freyre 2922\", \"etiquetas\": \"\", \"prioridad\": 3, \"proyecto_id\": 7, \"responsable\": \"Jose Luis Aiello\", \"provincia_id\": 21, \"observaciones\": null}}', NULL, '2026-06-01 19:03:04', '2026-06-01 19:03:04'),
(615, 'default', 'created', 'App\\Models\\Agencia', 'created', 16, 'App\\Models\\User', 1, '{\"attributes\": {\"activo\": true, \"nombre\": \"ESCOBAR\", \"ciudad_id\": 65, \"direccion\": \"Av. Gdor. Freyre 2922\", \"etiquetas\": \"\", \"prioridad\": 3, \"proyecto_id\": 7, \"responsable\": \"Jose Luis Aiello\", \"provincia_id\": 21, \"observaciones\": null}}', NULL, '2026-06-01 19:03:45', '2026-06-01 19:03:45'),
(616, 'default', 'created', 'App\\Models\\Agencia', 'created', 17, 'App\\Models\\User', 1, '{\"attributes\": {\"activo\": true, \"nombre\": \"ESCOBAR\", \"ciudad_id\": 65, \"direccion\": \"Av. Gdor. Freyre 2922\", \"etiquetas\": \"\", \"prioridad\": 3, \"proyecto_id\": 7, \"responsable\": \"Jose Luis Aiello\", \"provincia_id\": 21, \"observaciones\": null}}', NULL, '2026-06-01 19:04:19', '2026-06-01 19:04:19'),
(617, 'default', 'created', 'App\\Models\\Agencia', 'created', 18, 'App\\Models\\User', 1, '{\"attributes\": {\"activo\": true, \"nombre\": \"ESCOBAR\", \"ciudad_id\": 65, \"direccion\": \"Av. Gdor. Freyre 2922\", \"etiquetas\": \"\", \"prioridad\": 3, \"proyecto_id\": 7, \"responsable\": \"Jose Luis Aiello\", \"provincia_id\": 21, \"observaciones\": null}}', NULL, '2026-06-01 19:06:51', '2026-06-01 19:06:51'),
(618, 'default', 'created', 'App\\Models\\Agencia', 'created', 19, 'App\\Models\\User', 1, '{\"attributes\": {\"activo\": true, \"nombre\": \"ESCOBAR\", \"ciudad_id\": 65, \"direccion\": \"Av. Gdor. Freyre 2922\", \"etiquetas\": \"\", \"prioridad\": 3, \"proyecto_id\": 7, \"responsable\": \"Jose Luis Aiello\", \"provincia_id\": 21, \"observaciones\": null}}', NULL, '2026-06-01 19:11:59', '2026-06-01 19:11:59'),
(619, 'default', 'created', 'App\\Models\\Agencia', 'created', 20, 'App\\Models\\User', 1, '{\"attributes\": {\"activo\": true, \"nombre\": \"ESCOBAR\", \"ciudad_id\": 65, \"direccion\": \"Av. Gdor. Freyre 2922\", \"etiquetas\": \"\", \"prioridad\": 3, \"proyecto_id\": 7, \"responsable\": \"Jose Luis Aiello\", \"provincia_id\": 21, \"observaciones\": null}}', NULL, '2026-06-01 19:15:06', '2026-06-01 19:15:06'),
(620, 'default', 'created', 'App\\Models\\Agencia', 'created', 21, 'App\\Models\\User', 1, '{\"attributes\": {\"activo\": true, \"nombre\": \"SVA\", \"ciudad_id\": null, \"direccion\": \"Av. Maipu 3850, B1636\", \"etiquetas\": \"\", \"prioridad\": 3, \"proyecto_id\": 7, \"responsable\": \"Andres Davidian\", \"provincia_id\": 1, \"observaciones\": null}}', NULL, '2026-06-01 19:16:58', '2026-06-01 19:16:58'),
(621, 'default', 'deleted', 'App\\Models\\Agencia', 'deleted', 13, 'App\\Models\\User', 1, '{\"old\": {\"activo\": true, \"nombre\": \"ESCOBAR\", \"ciudad_id\": 65, \"direccion\": \"Av. Gdor. Freyre 2922\", \"etiquetas\": \"\", \"prioridad\": 3, \"proyecto_id\": 7, \"responsable\": \"Jose Luis Aiello\", \"provincia_id\": 21, \"observaciones\": null}}', NULL, '2026-06-01 19:20:24', '2026-06-01 19:20:24'),
(622, 'default', 'deleted', 'App\\Models\\Agencia', 'deleted', 14, 'App\\Models\\User', 1, '{\"old\": {\"activo\": true, \"nombre\": \"ESCOBAR\", \"ciudad_id\": 65, \"direccion\": \"Av. Gdor. Freyre 2922\", \"etiquetas\": \"\", \"prioridad\": 3, \"proyecto_id\": 7, \"responsable\": \"Jose Luis Aiello\", \"provincia_id\": 21, \"observaciones\": null}}', NULL, '2026-06-01 19:20:34', '2026-06-01 19:20:34'),
(623, 'default', 'deleted', 'App\\Models\\Agencia', 'deleted', 12, 'App\\Models\\User', 1, '{\"old\": {\"activo\": true, \"nombre\": \"ESCOBAR\", \"ciudad_id\": 65, \"direccion\": \"Av. Gdor. Freyre 2922\", \"etiquetas\": \"\", \"prioridad\": 3, \"proyecto_id\": 7, \"responsable\": \"Jose Luis Aiello\", \"provincia_id\": 21, \"observaciones\": null}}', NULL, '2026-06-01 19:20:44', '2026-06-01 19:20:44'),
(624, 'default', 'deleted', 'App\\Models\\Agencia', 'deleted', 15, 'App\\Models\\User', 1, '{\"old\": {\"activo\": true, \"nombre\": \"ESCOBAR\", \"ciudad_id\": 65, \"direccion\": \"Av. Gdor. Freyre 2922\", \"etiquetas\": \"\", \"prioridad\": 3, \"proyecto_id\": 7, \"responsable\": \"Jose Luis Aiello\", \"provincia_id\": 21, \"observaciones\": null}}', NULL, '2026-06-01 19:21:11', '2026-06-01 19:21:11'),
(625, 'default', 'deleted', 'App\\Models\\Agencia', 'deleted', 16, 'App\\Models\\User', 1, '{\"old\": {\"activo\": true, \"nombre\": \"ESCOBAR\", \"ciudad_id\": 65, \"direccion\": \"Av. Gdor. Freyre 2922\", \"etiquetas\": \"\", \"prioridad\": 3, \"proyecto_id\": 7, \"responsable\": \"Jose Luis Aiello\", \"provincia_id\": 21, \"observaciones\": null}}', NULL, '2026-06-01 19:21:11', '2026-06-01 19:21:11'),
(626, 'default', 'deleted', 'App\\Models\\Agencia', 'deleted', 17, 'App\\Models\\User', 1, '{\"old\": {\"activo\": true, \"nombre\": \"ESCOBAR\", \"ciudad_id\": 65, \"direccion\": \"Av. Gdor. Freyre 2922\", \"etiquetas\": \"\", \"prioridad\": 3, \"proyecto_id\": 7, \"responsable\": \"Jose Luis Aiello\", \"provincia_id\": 21, \"observaciones\": null}}', NULL, '2026-06-01 19:21:11', '2026-06-01 19:21:11'),
(627, 'default', 'deleted', 'App\\Models\\Agencia', 'deleted', 18, 'App\\Models\\User', 1, '{\"old\": {\"activo\": true, \"nombre\": \"ESCOBAR\", \"ciudad_id\": 65, \"direccion\": \"Av. Gdor. Freyre 2922\", \"etiquetas\": \"\", \"prioridad\": 3, \"proyecto_id\": 7, \"responsable\": \"Jose Luis Aiello\", \"provincia_id\": 21, \"observaciones\": null}}', NULL, '2026-06-01 19:21:11', '2026-06-01 19:21:11'),
(628, 'default', 'deleted', 'App\\Models\\Agencia', 'deleted', 19, 'App\\Models\\User', 1, '{\"old\": {\"activo\": true, \"nombre\": \"ESCOBAR\", \"ciudad_id\": 65, \"direccion\": \"Av. Gdor. Freyre 2922\", \"etiquetas\": \"\", \"prioridad\": 3, \"proyecto_id\": 7, \"responsable\": \"Jose Luis Aiello\", \"provincia_id\": 21, \"observaciones\": null}}', NULL, '2026-06-01 19:21:11', '2026-06-01 19:21:11'),
(629, 'default', 'created', 'App\\Models\\Mobiliario', 'created', 25, 'App\\Models\\User', 1, '{\"attributes\": {\"estado\": \"activo\", \"imagen\": null, \"nombre\": \"MESA ESCRITORIO\", \"precio\": null, \"marca_id\": 1, \"descripcion\": \"Fabricado en melamina negra lisa anti huellas y cantos a 45° con base tipo piramidal según manual\", \"categoria_id\": 1, \"observaciones\": \"MEDIDA ESPECIAL 1500*1000\", \"codigo_interno\": \"CH11-1500\", \"version_actual\": 1}}', NULL, '2026-06-01 19:50:14', '2026-06-01 19:50:14'),
(630, 'default', 'updated', 'App\\Models\\Mobiliario', 'updated', 20, 'App\\Models\\User', 1, '{\"old\": {\"nombre\": \"ESCRITORIO POSTVENTA MOD2\"}, \"attributes\": {\"nombre\": \"ESCRITORIO POSTVENTA MOD3\"}}', NULL, '2026-06-01 19:51:50', '2026-06-01 19:51:50'),
(631, 'default', 'created', 'App\\Models\\Presupuesto', 'created', 11, 'App\\Models\\User', 1, '{\"attributes\": {\"codigo\": \"PRES-2026-0001\", \"estado\": \"borrador\", \"version\": 1, \"agencia_id\": 21, \"aprobado_at\": null, \"aprobado_por\": null, \"fecha_emision\": \"2026-05-13T03:00:00.000000Z\", \"observaciones\": null, \"notas_internas\": null, \"responsable_id\": 1, \"datos_adicionales\": null, \"fecha_vencimiento\": null}}', NULL, '2026-06-01 19:54:15', '2026-06-01 19:54:15');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `agencias`
--

DROP TABLE IF EXISTS `agencias`;
CREATE TABLE IF NOT EXISTS `agencias` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` varchar(191) NOT NULL,
  `proyecto_id` bigint UNSIGNED DEFAULT NULL,
  `direccion` varchar(191) DEFAULT NULL,
  `provincia_id` bigint UNSIGNED DEFAULT NULL,
  `ciudad_id` bigint UNSIGNED DEFAULT NULL,
  `responsable` varchar(191) DEFAULT NULL,
  `observaciones` text,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `prioridad` tinyint UNSIGNED NOT NULL DEFAULT '3',
  `etiquetas` json DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `agencias_proyecto_id_foreign` (`proyecto_id`),
  KEY `agencias_provincia_id_foreign` (`provincia_id`),
  KEY `agencias_ciudad_id_foreign` (`ciudad_id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `agencias`
--

INSERT INTO `agencias` (`id`, `nombre`, `proyecto_id`, `direccion`, `provincia_id`, `ciudad_id`, `responsable`, `observaciones`, `activo`, `prioridad`, `etiquetas`, `deleted_at`, `created_at`, `updated_at`) VALUES
(12, 'ESCOBAR', 7, 'Av. Gdor. Freyre 2922', 21, 65, 'Jose Luis Aiello', NULL, 1, 3, '\"\"', '2026-06-01 19:20:44', '2026-06-01 18:59:41', '2026-06-01 19:20:44'),
(11, 'SURFENG', 7, 'Av. San Marin 4010', 13, 43, 'Paula Halpern', NULL, 1, 3, '\"\"', NULL, '2026-06-01 18:58:40', '2026-06-01 18:58:40'),
(10, 'ROMA', 7, 'Av. Bartolome Mitre 441, M5600', 13, 42, 'Adrian Pablo Sat', NULL, 1, 3, '\"\"', NULL, '2026-06-01 18:57:48', '2026-06-01 18:57:48'),
(9, 'AVANT', 7, 'Av. Emilio Carrafa 1913', 6, 23, 'Florencia Pitt', NULL, 1, 3, '\"\"', NULL, '2026-06-01 18:56:54', '2026-06-01 18:56:54'),
(8, 'ROMA', 5, 'Av. Bartolome Mitre 441, M5600', 13, 42, 'Adrian Pablo Sat', NULL, 1, 3, '\"\"', '2026-06-01 12:11:23', '2026-05-26 17:34:05', '2026-06-01 12:11:23'),
(13, 'ESCOBAR', 7, 'Av. Gdor. Freyre 2922', 21, 65, 'Jose Luis Aiello', NULL, 1, 3, '\"\"', '2026-06-01 19:20:24', '2026-06-01 19:01:38', '2026-06-01 19:20:24'),
(14, 'ESCOBAR', 7, 'Av. Gdor. Freyre 2922', 21, 65, 'Jose Luis Aiello', NULL, 1, 3, '\"\"', '2026-06-01 19:20:34', '2026-06-01 19:02:23', '2026-06-01 19:20:34'),
(15, 'ESCOBAR', 7, 'Av. Gdor. Freyre 2922', 21, 65, 'Jose Luis Aiello', NULL, 1, 3, '\"\"', '2026-06-01 19:21:11', '2026-06-01 19:03:04', '2026-06-01 19:21:11'),
(16, 'ESCOBAR', 7, 'Av. Gdor. Freyre 2922', 21, 65, 'Jose Luis Aiello', NULL, 1, 3, '\"\"', '2026-06-01 19:21:11', '2026-06-01 19:03:45', '2026-06-01 19:21:11'),
(17, 'ESCOBAR', 7, 'Av. Gdor. Freyre 2922', 21, 65, 'Jose Luis Aiello', NULL, 1, 3, '\"\"', '2026-06-01 19:21:11', '2026-06-01 19:04:19', '2026-06-01 19:21:11'),
(18, 'ESCOBAR', 7, 'Av. Gdor. Freyre 2922', 21, 65, 'Jose Luis Aiello', NULL, 1, 3, '\"\"', '2026-06-01 19:21:11', '2026-06-01 19:06:51', '2026-06-01 19:21:11'),
(19, 'ESCOBAR', 7, 'Av. Gdor. Freyre 2922', 21, 65, 'Jose Luis Aiello', NULL, 1, 3, '\"\"', '2026-06-01 19:21:11', '2026-06-01 19:11:59', '2026-06-01 19:21:11'),
(20, 'ESCOBAR', 7, 'Av. Gdor. Freyre 2922', 21, 65, 'Jose Luis Aiello', NULL, 1, 3, '\"\"', NULL, '2026-06-01 19:15:06', '2026-06-01 19:15:06'),
(21, 'SVA', 7, 'Av. Maipu 3850, B1636', 1, NULL, 'Andres Davidian', NULL, 1, 3, '\"\"', NULL, '2026-06-01 19:16:58', '2026-06-01 19:16:58');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `atributos_mobiliario`
--

DROP TABLE IF EXISTS `atributos_mobiliario`;
CREATE TABLE IF NOT EXISTS `atributos_mobiliario` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `mobiliario_id` bigint UNSIGNED NOT NULL,
  `clave` varchar(191) NOT NULL,
  `valor` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `atributos_mobiliario_mobiliario_id_foreign` (`mobiliario_id`)
) ENGINE=MyISAM AUTO_INCREMENT=62 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `atributos_mobiliario`
--

INSERT INTO `atributos_mobiliario` (`id`, `mobiliario_id`, `clave`, `valor`, `created_at`, `updated_at`) VALUES
(1, 1, 'Diametro', '790', '2026-05-20 13:37:05', '2026-05-20 13:37:05'),
(2, 1, 'Alto', '750', '2026-05-20 13:37:05', '2026-05-20 13:37:05'),
(3, 2, 'Ancho', '3600', '2026-05-20 13:41:16', '2026-05-20 13:41:16'),
(4, 2, 'Profundidad', '500', '2026-05-20 13:41:16', '2026-05-20 13:41:16'),
(5, 2, 'Alto', '2400', '2026-05-20 13:41:16', '2026-05-20 13:41:16'),
(6, 3, 'Ancho', '800', '2026-05-20 13:43:46', '2026-05-20 13:43:46'),
(7, 3, 'Largo', '1800', '2026-05-20 13:43:46', '2026-05-20 13:43:46'),
(8, 3, 'Alto', '750', '2026-05-20 13:43:46', '2026-05-20 13:43:46'),
(9, 4, 'Largo', '830', '2026-05-26 17:06:08', '2026-05-26 17:06:08'),
(10, 4, 'Ancho', '730', '2026-05-26 17:06:08', '2026-05-26 17:06:08'),
(11, 4, 'Alto', '240', '2026-05-26 17:06:08', '2026-05-26 17:06:08'),
(12, 5, 'Alto', '750', '2026-06-01 15:25:53', '2026-06-01 15:25:53'),
(13, 5, 'Largo', '2800', '2026-06-01 15:25:53', '2026-06-01 15:25:53'),
(14, 5, 'Ancho ', '920', '2026-06-01 15:25:53', '2026-06-01 15:25:53'),
(15, 6, 'Largo', '820', '2026-06-01 15:28:48', '2026-06-01 15:28:48'),
(16, 6, 'Ancho', '730', '2026-06-01 15:28:48', '2026-06-01 15:28:48'),
(17, 6, 'Alto', '240', '2026-06-01 15:28:48', '2026-06-01 15:28:48'),
(18, 7, 'Dimensiones', '1000 x 1000', '2026-06-01 15:30:30', '2026-06-01 15:30:30'),
(19, 10, 'Largo ', '2800', '2026-06-01 15:35:52', '2026-06-01 15:35:52'),
(20, 10, 'Profundidad', '500', '2026-06-01 15:35:52', '2026-06-01 15:35:52'),
(21, 10, 'Alto', '2000', '2026-06-01 15:35:52', '2026-06-01 15:35:52'),
(22, 11, 'Largo', '2800', '2026-06-01 15:37:46', '2026-06-01 15:37:46'),
(23, 11, 'Profundidad', '500', '2026-06-01 15:37:46', '2026-06-01 15:37:46'),
(24, 11, 'Alto', '2000', '2026-06-01 15:37:46', '2026-06-01 15:37:46'),
(25, 12, 'Largo', '2400', '2026-06-01 15:44:32', '2026-06-01 15:44:32'),
(26, 12, 'Profundidad', '500', '2026-06-01 15:44:33', '2026-06-01 15:44:33'),
(27, 12, 'Alto', '2000', '2026-06-01 15:44:33', '2026-06-01 15:44:33'),
(28, 13, 'Largo', '1500', '2026-06-01 16:12:33', '2026-06-01 16:12:33'),
(29, 13, 'Ancho', '1000', '2026-06-01 16:12:33', '2026-06-01 16:12:33'),
(30, 13, 'Alto', '750', '2026-06-01 16:12:33', '2026-06-01 16:12:33'),
(31, 15, 'Diametro', '790', '2026-06-01 16:16:58', '2026-06-01 16:16:58'),
(32, 15, 'Alto', '750', '2026-06-01 16:16:58', '2026-06-01 16:16:58'),
(33, 16, 'Largo', '3120', '2026-06-01 16:32:46', '2026-06-01 16:32:46'),
(34, 16, 'Alto', '1500', '2026-06-01 16:32:46', '2026-06-01 16:32:46'),
(35, 17, 'Largo', '2400', '2026-06-01 17:27:17', '2026-06-01 17:27:17'),
(36, 17, 'Profundidad', '500', '2026-06-01 17:27:17', '2026-06-01 17:27:17'),
(37, 17, 'Alto', '2400', '2026-06-01 17:27:17', '2026-06-01 17:27:17'),
(38, 18, 'Largo', '800', '2026-06-01 17:30:12', '2026-06-01 17:30:12'),
(39, 18, 'Ancho', '800', '2026-06-01 17:30:12', '2026-06-01 17:32:55'),
(40, 18, 'Alto', '1100', '2026-06-01 17:30:12', '2026-06-01 17:30:12'),
(41, 19, 'Largo', '800', '2026-06-01 17:32:45', '2026-06-01 17:32:45'),
(42, 19, 'Ancho', '800', '2026-06-01 17:32:45', '2026-06-01 17:32:45'),
(43, 19, 'Alto', '1100', '2026-06-01 17:32:45', '2026-06-01 17:32:45'),
(44, 20, 'Largo', '800', '2026-06-01 17:34:08', '2026-06-01 17:34:08'),
(45, 20, 'Ancho', '800', '2026-06-01 17:34:08', '2026-06-01 17:34:08'),
(46, 20, 'Alto', '1100', '2026-06-01 17:34:09', '2026-06-01 17:34:09'),
(47, 21, 'Largo', '3600', '2026-06-01 17:37:33', '2026-06-01 17:37:33'),
(48, 21, 'Profundidad', '500', '2026-06-01 17:37:33', '2026-06-01 17:37:33'),
(49, 21, 'Alto', '2000', '2026-06-01 17:37:33', '2026-06-01 17:37:33'),
(50, 22, 'Largo', '2200', '2026-06-01 17:40:17', '2026-06-01 17:40:17'),
(51, 22, 'Ancho', '800', '2026-06-01 17:40:17', '2026-06-01 17:40:17'),
(52, 22, 'Alto', '750', '2026-06-01 17:40:17', '2026-06-01 17:40:17'),
(53, 23, 'Largo', '3600', '2026-06-01 17:43:27', '2026-06-01 17:43:27'),
(54, 23, 'Profundidad', '500', '2026-06-01 17:43:27', '2026-06-01 17:43:27'),
(55, 23, 'Alto', '2400', '2026-06-01 17:43:27', '2026-06-01 17:43:27'),
(56, 24, 'Largo', '2000', '2026-06-01 17:47:20', '2026-06-01 17:47:20'),
(57, 24, 'Ancho', '900', '2026-06-01 17:47:20', '2026-06-01 17:47:20'),
(58, 24, 'Alto', '1100', '2026-06-01 17:47:20', '2026-06-01 17:47:20'),
(59, 25, 'Largo', '1500', '2026-06-01 19:50:14', '2026-06-01 19:50:14'),
(60, 25, 'Ancho', '1000', '2026-06-01 19:50:14', '2026-06-01 19:50:14'),
(61, 25, 'Alto', '750', '2026-06-01 19:50:14', '2026-06-01 19:50:14');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache`
--

DROP TABLE IF EXISTS `cache`;
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(191) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(191) NOT NULL,
  `owner` varchar(191) NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias_insumo`
--

DROP TABLE IF EXISTS `categorias_insumo`;
CREATE TABLE IF NOT EXISTS `categorias_insumo` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` varchar(191) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categorias_insumo_nombre_unique` (`nombre`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `categorias_insumo`
--

INSERT INTO `categorias_insumo` (`id`, `nombre`, `activo`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Sillas', 1, '2026-05-28 11:01:27', '2026-05-28 11:01:27', NULL),
(2, 'Patas', 1, '2026-05-28 11:01:33', '2026-05-28 11:01:33', NULL),
(5, 'Perfiles', 1, '2026-06-01 12:38:59', '2026-06-01 12:38:59', NULL),
(6, 'Pintura', 1, '2026-06-01 12:39:15', '2026-06-01 12:39:15', NULL),
(7, 'Cerraduras', 1, '2026-06-01 12:39:42', '2026-06-01 12:39:42', NULL),
(8, 'Bases', 1, '2026-06-01 12:39:53', '2026-06-01 12:39:53', NULL),
(9, 'Goma espuma', 1, '2026-06-01 12:40:33', '2026-06-01 12:40:33', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias_mobiliario`
--

DROP TABLE IF EXISTS `categorias_mobiliario`;
CREATE TABLE IF NOT EXISTS `categorias_mobiliario` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` varchar(191) NOT NULL,
  `slug` varchar(191) NOT NULL,
  `descripcion` text,
  `icono` varchar(191) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `orden` int UNSIGNED NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categorias_mobiliario_slug_unique` (`slug`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `categorias_mobiliario`
--

INSERT INTO `categorias_mobiliario` (`id`, `nombre`, `slug`, `descripcion`, `icono`, `activo`, `orden`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Escritorios', 'escritorios', NULL, 'heroicon-o-computer-desktop', 1, 1, NULL, '2026-05-20 11:53:34', '2026-05-20 11:53:34'),
(2, 'Recepciones', 'recepciones', NULL, 'heroicon-o-building-office', 1, 2, NULL, '2026-05-20 11:53:34', '2026-05-20 11:53:34'),
(3, 'Góndolas', 'gondolas', NULL, 'heroicon-o-archive-box', 1, 3, NULL, '2026-05-20 11:53:34', '2026-05-20 11:53:34'),
(4, 'Exhibidores', 'exhibidores', NULL, 'heroicon-o-presentation-chart-bar', 1, 4, NULL, '2026-05-20 11:53:34', '2026-05-20 11:53:34'),
(5, 'Backoffice', 'backoffice', NULL, 'heroicon-o-briefcase', 1, 5, NULL, '2026-05-20 11:53:34', '2026-05-20 11:53:34'),
(6, 'Cartelería', 'carteleria', NULL, 'heroicon-o-megaphone', 1, 6, NULL, '2026-05-20 11:53:34', '2026-05-20 11:53:34'),
(7, 'Otras', 'otras', NULL, 'heroicon-o-ellipsis-horizontal', 1, 7, NULL, '2026-05-20 11:53:34', '2026-05-20 11:53:34'),
(8, 'Mesa Baja', 'mesa-baja', NULL, NULL, 1, 0, NULL, '2026-05-20 13:24:54', '2026-05-20 13:24:54'),
(9, 'MuebleTV', 'muebletv', NULL, NULL, 1, 0, NULL, '2026-05-20 13:38:25', '2026-05-20 13:38:25'),
(10, 'Sillas', 'sillas', NULL, NULL, 1, 0, NULL, '2026-05-28 15:08:55', '2026-05-28 15:08:55'),
(11, 'Mueble de guardado', 'mueble-de-guardado', NULL, NULL, 1, 0, NULL, '2026-06-01 15:33:25', '2026-06-01 15:33:25'),
(12, 'MUEBLE DE CAFE', 'mueble-de-cafe', NULL, NULL, 1, 0, NULL, '2026-06-01 15:42:09', '2026-06-01 15:42:09'),
(13, 'Panel', 'panel', NULL, NULL, 1, 0, NULL, '2026-06-01 16:17:30', '2026-06-01 16:17:30');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria_insumo_insumo`
--

DROP TABLE IF EXISTS `categoria_insumo_insumo`;
CREATE TABLE IF NOT EXISTS `categoria_insumo_insumo` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `insumo_id` bigint UNSIGNED NOT NULL,
  `categoria_insumo_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categoria_insumo_insumo_insumo_id_categoria_insumo_id_unique` (`insumo_id`,`categoria_insumo_id`),
  KEY `categoria_insumo_insumo_categoria_insumo_id_foreign` (`categoria_insumo_id`)
) ENGINE=MyISAM AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `categoria_insumo_insumo`
--

INSERT INTO `categoria_insumo_insumo` (`id`, `insumo_id`, `categoria_insumo_id`, `created_at`, `updated_at`) VALUES
(1, 1, 3, '2026-05-29 12:08:16', '2026-05-29 12:08:16'),
(2, 2, 2, '2026-05-29 12:08:16', '2026-05-29 12:08:16'),
(3, 3, 1, '2026-05-29 12:08:16', '2026-05-29 12:08:16'),
(4, 1, 4, NULL, NULL),
(5, 159, 1, '2026-06-01 12:58:02', '2026-06-01 12:58:02'),
(6, 160, 1, '2026-06-01 12:58:02', '2026-06-01 12:58:02'),
(7, 161, 1, '2026-06-01 12:58:02', '2026-06-01 12:58:02'),
(8, 162, 1, '2026-06-01 12:58:02', '2026-06-01 12:58:02'),
(9, 163, 8, '2026-06-01 12:58:02', '2026-06-01 12:58:02'),
(10, 164, 8, '2026-06-01 12:58:02', '2026-06-01 12:58:02'),
(11, 165, 8, '2026-06-01 12:58:02', '2026-06-01 12:58:02'),
(12, 166, 8, '2026-06-01 12:58:02', '2026-06-01 12:58:02'),
(13, 167, 8, '2026-06-01 12:58:02', '2026-06-01 12:58:02'),
(14, 168, 8, '2026-06-01 12:58:02', '2026-06-01 12:58:02'),
(15, 169, 8, '2026-06-01 12:58:02', '2026-06-01 12:58:02'),
(16, 170, 8, '2026-06-01 12:58:02', '2026-06-01 12:58:02'),
(17, 171, 8, '2026-06-01 12:58:02', '2026-06-01 12:58:02'),
(18, 172, 8, '2026-06-01 12:58:02', '2026-06-01 12:58:02'),
(19, 173, 8, '2026-06-01 12:58:02', '2026-06-01 12:58:02'),
(20, 187, 7, '2026-06-01 12:58:02', '2026-06-01 12:58:02'),
(21, 188, 7, '2026-06-01 12:58:02', '2026-06-01 12:58:02'),
(22, 189, 7, '2026-06-01 12:58:02', '2026-06-01 12:58:02'),
(23, 192, 9, '2026-06-01 12:58:02', '2026-06-01 12:58:02'),
(24, 193, 9, '2026-06-01 12:58:02', '2026-06-01 12:58:02'),
(25, 245, 2, '2026-06-01 12:58:02', '2026-06-01 12:58:02'),
(26, 246, 2, '2026-06-01 12:58:02', '2026-06-01 12:58:02'),
(27, 247, 2, '2026-06-01 12:58:02', '2026-06-01 12:58:02'),
(28, 248, 2, '2026-06-01 12:58:02', '2026-06-01 12:58:02'),
(29, 249, 2, '2026-06-01 12:58:02', '2026-06-01 12:58:02'),
(30, 250, 2, '2026-06-01 12:58:02', '2026-06-01 12:58:02'),
(31, 251, 2, '2026-06-01 12:58:02', '2026-06-01 12:58:02'),
(32, 252, 5, '2026-06-01 12:58:02', '2026-06-01 12:58:02'),
(33, 253, 5, '2026-06-01 12:58:02', '2026-06-01 12:58:02'),
(34, 254, 5, '2026-06-01 12:58:02', '2026-06-01 12:58:02'),
(35, 255, 5, '2026-06-01 12:58:02', '2026-06-01 12:58:02'),
(36, 256, 5, '2026-06-01 12:58:02', '2026-06-01 12:58:02'),
(37, 257, 6, '2026-06-01 12:58:02', '2026-06-01 12:58:02'),
(38, 258, 6, '2026-06-01 12:58:02', '2026-06-01 12:58:02'),
(39, 259, 6, '2026-06-01 12:58:02', '2026-06-01 12:58:02'),
(40, 260, 6, '2026-06-01 12:58:02', '2026-06-01 12:58:02'),
(41, 261, 6, '2026-06-01 12:58:02', '2026-06-01 12:58:02'),
(42, 262, 6, '2026-06-01 12:58:02', '2026-06-01 12:58:02'),
(43, 271, 1, '2026-06-01 12:58:02', '2026-06-01 12:58:02'),
(44, 272, 1, '2026-06-01 12:58:02', '2026-06-01 12:58:02'),
(45, 273, 1, '2026-06-01 12:58:02', '2026-06-01 12:58:02'),
(46, 274, 1, '2026-06-01 12:58:02', '2026-06-01 12:58:02'),
(47, 275, 1, '2026-06-01 12:58:02', '2026-06-01 12:58:02'),
(48, 276, 1, '2026-06-01 12:58:02', '2026-06-01 12:58:02'),
(49, 277, 1, '2026-06-01 12:58:02', '2026-06-01 12:58:02'),
(50, 278, 1, '2026-06-01 12:58:02', '2026-06-01 12:58:02'),
(51, 279, 1, '2026-06-01 12:58:02', '2026-06-01 12:58:02'),
(52, 280, 1, '2026-06-01 12:58:02', '2026-06-01 12:58:02'),
(53, 281, 1, '2026-06-01 12:58:02', '2026-06-01 12:58:02'),
(54, 282, 1, '2026-06-01 12:58:02', '2026-06-01 12:58:02'),
(55, 283, 1, '2026-06-01 12:58:02', '2026-06-01 12:58:02'),
(56, 284, 1, '2026-06-01 12:58:02', '2026-06-01 12:58:02');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ciudades`
--

DROP TABLE IF EXISTS `ciudades`;
CREATE TABLE IF NOT EXISTS `ciudades` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` varchar(191) NOT NULL,
  `provincia_id` bigint UNSIGNED NOT NULL,
  `codigo_postal` varchar(191) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ciudades_nombre_provincia_id_unique` (`nombre`,`provincia_id`),
  KEY `ciudades_provincia_id_foreign` (`provincia_id`)
) ENGINE=MyISAM AUTO_INCREMENT=103 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `ciudades`
--

INSERT INTO `ciudades` (`id`, `nombre`, `provincia_id`, `codigo_postal`) VALUES
(1, 'La Plata', 1, NULL),
(2, 'Mar del Plata', 1, NULL),
(3, 'Bragado', 1, NULL),
(4, 'Junín', 1, NULL),
(5, 'Tandil', 1, NULL),
(6, 'Bahía Blanca', 1, NULL),
(7, 'San Fernando del Valle de Catamarca', 2, NULL),
(8, 'Belén', 2, NULL),
(9, 'Resistencia', 3, NULL),
(10, 'Corrientes', 3, NULL),
(11, 'Rawson', 4, NULL),
(12, 'Comodoro Rivadavia', 4, NULL),
(13, 'Trelew', 4, NULL),
(14, 'San Nicolás', 5, NULL),
(15, 'Recoleta', 5, NULL),
(16, 'Palermo', 5, NULL),
(17, 'Belgrano', 5, NULL),
(18, 'Flores', 5, NULL),
(19, 'San Telmo', 5, NULL),
(20, 'Caballito', 5, NULL),
(21, 'La Boca', 5, NULL),
(22, 'Córdoba', 6, NULL),
(23, 'Río Cuarto', 6, NULL),
(24, 'Villa María', 6, NULL),
(25, 'San Francisco', 6, NULL),
(26, 'Corrientes', 7, NULL),
(27, 'Oberá', 7, NULL),
(28, 'Paso de la Cruz', 7, NULL),
(29, 'Paraná', 8, NULL),
(30, 'Concordia', 8, NULL),
(31, 'Gualeguaychú', 8, NULL),
(32, 'Formosa', 9, NULL),
(33, 'Clorinda', 9, NULL),
(34, 'San Salvador de Jujuy', 10, NULL),
(35, 'Palpalá', 10, NULL),
(36, 'Perico', 10, NULL),
(37, 'Santa Rosa', 11, NULL),
(38, 'General Pico', 11, NULL),
(39, 'La Rioja', 12, NULL),
(40, 'Chamical', 12, NULL),
(41, 'Mendoza', 13, NULL),
(42, 'San Rafael', 13, NULL),
(43, 'Godoy Cruz', 13, NULL),
(44, 'Las Heras', 13, NULL),
(45, 'Posadas', 14, NULL),
(46, 'Oberá', 14, NULL),
(47, 'Puerto Iguazú', 14, NULL),
(48, 'Neuquén', 15, NULL),
(49, 'San Martín de los Andes', 15, NULL),
(50, 'Junín de los Andes', 15, NULL),
(51, 'Viedma', 16, NULL),
(52, 'San Carlos de Bariloche', 16, NULL),
(53, 'General Roca', 16, NULL),
(54, 'Salta', 17, NULL),
(55, 'San Salvador de Jujuy', 17, NULL),
(56, 'Tartagal', 17, NULL),
(57, 'San Juan', 18, NULL),
(58, 'Chimbas', 18, NULL),
(59, 'Caucete', 18, NULL),
(60, 'San Luis', 19, NULL),
(61, 'Villa Mercedes', 19, NULL),
(62, 'Río Gallegos', 20, NULL),
(63, 'Calafate', 20, NULL),
(64, 'El Chaltén', 20, NULL),
(65, 'Santa Fe', 21, NULL),
(66, 'Rosario', 21, NULL),
(67, 'Venado Tuerto', 21, NULL),
(68, 'Rafaela', 21, NULL),
(69, 'Santiago del Estero', 22, NULL),
(70, 'La Banda', 22, NULL),
(71, 'Ushuaia', 23, NULL),
(72, 'Río Grande', 23, NULL),
(73, 'San Miguel de Tucumán', 24, NULL),
(74, 'Tafí Viejo', 24, NULL),
(75, 'Yerba Buena', 24, NULL),
(76, 'BUENOS AIRES', 1, NULL),
(77, 'LOBOS', 1, NULL),
(78, 'CAÑADA DE GOMEZ', 21, NULL),
(79, 'C.A.B.A.', 21, NULL),
(80, 'CORREA', 21, NULL),
(81, 'CDA.DE GOMEZ', 21, NULL),
(82, 'CASILDA', 21, NULL),
(83, 'ROSARIO SUD', 21, NULL),
(84, 'MORON', 1, NULL),
(85, 'CIUDAD DE CORDOBA SU', 6, NULL),
(86, 'CAPITAL FEDERAL', 25, NULL),
(87, 'ALVEAR', 21, NULL),
(88, 'EL TALAR', 1, NULL),
(89, 'VILLA GDOR. GALVEZ', 21, NULL),
(90, 'SOLDINI', 21, NULL),
(91, 'CABA', 25, NULL),
(92, 'VILLA LOMA HERMOSA', 1, NULL),
(93, 'DON BOSCO', 1, NULL),
(94, 'HURLINGHAM', 1, NULL),
(95, 'COLAZO', 6, NULL),
(96, 'CASEROS', 1, NULL),
(97, 'PABLO PODESTA', 1, NULL),
(98, 'CRUZ ALTA', 6, NULL),
(99, 'SAN MARTIN', 1, NULL),
(100, 'C.A.B.A.', 25, NULL),
(101, 'BERAZATEGUI', 1, NULL),
(102, 'La Lucila', 1, 'B1636');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `composicion_tecnica`
--

DROP TABLE IF EXISTS `composicion_tecnica`;
CREATE TABLE IF NOT EXISTS `composicion_tecnica` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `mobiliario_id` bigint UNSIGNED NOT NULL,
  `insumo_id` bigint UNSIGNED NOT NULL,
  `cantidad` decimal(10,4) NOT NULL,
  `version` int UNSIGNED NOT NULL DEFAULT '1',
  `observaciones` text,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `composicion_tecnica_mobiliario_id_insumo_id_version_unique` (`mobiliario_id`,`insumo_id`,`version`),
  KEY `composicion_tecnica_insumo_id_foreign` (`insumo_id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `composicion_tecnica`
--

INSERT INTO `composicion_tecnica` (`id`, `mobiliario_id`, `insumo_id`, `cantidad`, `version`, `observaciones`, `activo`, `created_at`, `updated_at`) VALUES
(1, 3, 1, 4.0000, 1, NULL, 1, '2026-05-20 19:24:34', '2026-05-20 19:24:34'),
(2, 4, 1, 8.0000, 1, NULL, 1, '2026-05-26 17:06:08', '2026-05-26 17:06:08'),
(3, 5, 163, 1.0000, 1, NULL, 1, '2026-06-01 15:25:53', '2026-06-01 15:25:53'),
(4, 10, 242, 1.0000, 1, NULL, 1, '2026-06-01 15:35:52', '2026-06-01 15:35:52'),
(5, 10, 244, 1.0000, 1, NULL, 1, '2026-06-01 15:35:52', '2026-06-01 15:35:52'),
(6, 11, 242, 2.0000, 1, NULL, 1, '2026-06-01 15:37:46', '2026-06-01 15:37:46'),
(7, 11, 244, 2.0000, 1, NULL, 1, '2026-06-01 15:37:46', '2026-06-01 15:37:46'),
(8, 12, 244, 1.0000, 1, NULL, 1, '2026-06-01 15:44:33', '2026-06-01 15:44:33'),
(9, 15, 303, 1.0000, 1, NULL, 1, '2026-06-01 16:16:58', '2026-06-01 16:16:58'),
(10, 17, 244, 1.0000, 1, NULL, 1, '2026-06-01 17:27:17', '2026-06-01 17:27:17'),
(11, 18, 187, 1.0000, 1, NULL, 1, '2026-06-01 17:30:12', '2026-06-01 17:30:12'),
(12, 18, 297, 2.0000, 1, NULL, 1, '2026-06-01 17:30:12', '2026-06-01 17:30:12'),
(13, 21, 242, 2.0000, 1, NULL, 1, '2026-06-01 17:37:33', '2026-06-01 17:37:33'),
(14, 21, 244, 2.0000, 1, NULL, 1, '2026-06-01 17:37:33', '2026-06-01 17:37:33'),
(15, 23, 242, 2.0000, 1, NULL, 1, '2026-06-01 17:43:27', '2026-06-01 17:43:27'),
(16, 23, 244, 2.0000, 1, NULL, 1, '2026-06-01 17:43:27', '2026-06-01 17:43:27'),
(17, 24, 187, 1.0000, 1, NULL, 1, '2026-06-01 17:47:20', '2026-06-01 17:47:20'),
(18, 24, 189, 1.0000, 1, NULL, 1, '2026-06-01 17:47:20', '2026-06-01 17:47:20');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_versiones`
--

DROP TABLE IF EXISTS `historial_versiones`;
CREATE TABLE IF NOT EXISTS `historial_versiones` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `mobiliario_id` bigint UNSIGNED NOT NULL,
  `version` int UNSIGNED NOT NULL,
  `descripcion_cambio` varchar(191) NOT NULL,
  `snapshot` json DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `historial_versiones_mobiliario_id_version_unique` (`mobiliario_id`,`version`),
  KEY `historial_versiones_user_id_foreign` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `insumos`
--

DROP TABLE IF EXISTS `insumos`;
CREATE TABLE IF NOT EXISTS `insumos` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `codigo` varchar(191) NOT NULL,
  `nombre` varchar(191) NOT NULL,
  `unidad_medida_id` bigint UNSIGNED NOT NULL,
  `stock_minimo` decimal(10,2) NOT NULL DEFAULT '0.00',
  `stock_actual` decimal(10,2) NOT NULL DEFAULT '0.00',
  `precio_costo` decimal(10,2) DEFAULT NULL,
  `ubicacion` varchar(191) DEFAULT NULL,
  `tag` json DEFAULT NULL,
  `observaciones` text,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `proveedor_id` bigint UNSIGNED DEFAULT NULL,
  `tipo_silla_id` bigint UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `insumos_codigo_unique` (`codigo`),
  KEY `insumos_unidad_medida_id_foreign` (`unidad_medida_id`)
) ENGINE=MyISAM AUTO_INCREMENT=304 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `insumos`
--

INSERT INTO `insumos` (`id`, `codigo`, `nombre`, `unidad_medida_id`, `stock_minimo`, `stock_actual`, `precio_costo`, `ubicacion`, `tag`, `observaciones`, `activo`, `proveedor_id`, `tipo_silla_id`, `deleted_at`, `created_at`, `updated_at`) VALUES
(273, 'INS-0273', 'SILLA ATHINA BASE CROMO NEGRA FULL', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(272, 'INS-0272', 'SILLA ALMA ASIENTO NEGRO Y RESPALDO NEGRO', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(271, 'INS-0271', 'SILLA ADRIX', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(270, 'INS-0270', 'SEPARADORES GRANDES ESCRITORIO GRANDE 3D RENAULT', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(269, 'INS-0269', 'SEPARADORES CHICOS ESCRITORIO 3D RENAULT', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(268, 'INS-0268', 'REGATON REGULABLE 5/16', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(267, 'INS-0267', 'REGATON REDONDOS PLASTICOS GRANDES', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(266, 'INS-0266', 'RECEPCION CHERY SILLA CLIENTE', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(265, 'INS-0265', 'PUFF TCH 06 TRIUMPH', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(264, 'INS-0264', 'PLOTEO NEGRO MAMPARAS RENAULT', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(263, 'INS-0263', 'PLOTEO AMARILLO PARA MAMPARAS RENAULT', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(262, 'INS-0262', 'PINTURA VERDE TRIUMPH SW 6172 SATINADO', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(261, 'INS-0261', 'PINTURA NEGRA ATRILES MOTOS', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(260, 'INS-0260', 'PINTURA GRIS CF MADERA CODIGO RES18538', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(259, 'INS-0259', 'PINTURA GRIS CF CHAPA CODIGO REEM18538', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(258, 'INS-0258', 'PINTURA CORVEN NARANJA PANTONE 021 EN LTS', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(257, 'INS-0257', 'PINTURA BAJAJ AZUL PANTONE 2945C', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(256, 'INS-0256', 'PERFIL TAPACANTOS 18MM X MTS', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(255, 'INS-0255', 'PERFIL PORTA LED X 3M LDP', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(254, 'INS-0254', 'PERFIL PLACA RANURADA', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(253, 'INS-0253', 'PERFIL CURVO (TABLERO CFMOTO)', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(252, 'INS-0252', 'PERFIL AUTOADHESIVO CAJONERAS X MTS', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(251, 'INS-0251', 'PATAS MESA LOUNGE RENAULT ROBLE', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(250, 'INS-0250', 'PATAS MESA LOUNGE RENAULT NEGRA', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(249, 'INS-0249', 'PATAS MESA LOUNGE RENAULT BLANCA', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(248, 'INS-0248', 'PATA WORK STATION JGO MULTIMARCAS', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(247, 'INS-0247', 'PATA METALICA MULTIMARCAS MODULOS', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(246, 'INS-0246', 'PATA DE CAÑO ESCRITORIO 70 30', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(245, 'INS-0245', 'PATA CHAPA CIRCULAR RENAULT', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(244, 'INS-0244', 'PASACABLES 60MM NEGRO', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(243, 'INS-0243', 'PASACABLE RECTANGULAR PLASTICO negro CON CEPILLO WIRENEX', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(242, 'INS-0242', 'PASACABLE BOX240 WIRE', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(241, 'INS-0241', 'PANTALLA TAO43 TRIUMPH', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(240, 'INS-0240', 'MANIQUI TRIUMPH', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(239, 'INS-0239', 'MANIQUI CON CAÑO TRIUMPH', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(238, 'INS-0238', 'MAC MODULO MULTIMARCAS', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(237, 'INS-0237', 'MAA MODULO MULTIMARCAS', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(236, 'INS-0236', 'LOGO TRIUMPH 50X50', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(235, 'INS-0235', 'LOGO TARIMA CORVEN', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(234, 'INS-0234', 'LOGO TARIMA BAJAJ', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(233, 'INS-0233', 'LOGO RECEPCION CFMOTO', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(232, 'INS-0232', 'LOGO MURO CFMOTO', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(231, 'INS-0231', 'LOGO MUEBLE DE ACCESORIOS CORVEN BAJAJ CB10', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(230, 'INS-0230', 'LOGO JE01 JEOTUR', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(229, 'INS-0229', 'LOGO CLX PANEL CFMOTO', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(228, 'INS-0228', 'LOGO CHERY RECEPCION', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(227, 'INS-0227', 'LOGO CFMOTO TABLERO PARED', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(226, 'INS-0226', 'LOGO CFMOTO PANEL PARED', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(225, 'INS-0225', 'LOGO ATRIL CORVEN', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(224, 'INS-0224', 'LOGO ATRIL CFMOTO', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(223, 'INS-0223', 'LOGO ATRIL BAJAJ', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(222, 'INS-0222', 'LOGO ACCESORIOS MODULO CFMOTO', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(221, 'INS-0221', 'LAMINADO PERFECT SENSE X M2', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(220, 'INS-0220', 'LAMINADO GRIS HUMO X MT2', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(219, 'INS-0219', 'LAMINADO CARRARA X M2', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(218, 'INS-0218', 'LAMINADO 0,8 WALLIS M868 FORMICA (TERRACOTA) x HOJA', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(217, 'INS-0217', 'KOMPAC GRIS X MT', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(216, 'INS-0216', 'KIT PUERTAS CORREDIZAS 1535 RUEDA MAS', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(215, 'INS-0215', 'JUEGO PATA CAÑO MESA RATONA CORVEN BAJAJ', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(214, 'INS-0214', 'JUEGO DE VIDRIOS EXTENSION RECEPCION CORVEN BAJAJ', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(213, 'INS-0213', 'JUEGO DE VIDRIOS ESTRUCTURA CB11', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(212, 'INS-0212', 'JUEGO COSTADO CHAPA HAFELE 40CM', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(211, 'INS-0211', 'JGO VIDRIOS TSC TRIUMPH', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(210, 'INS-0210', 'JGO MAMPARAS VENDEDOR RENAULT', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(209, 'INS-0209', 'JGO ESPUMA SILLON RENAULT RE012', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(208, 'INS-0208', 'JGO CALCOS TCHW TRIUMPH', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(207, 'INS-0207', 'JGO CALCOS TCHW 02 TRIUMPH', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(206, 'INS-0206', 'GONDOLA TRIUMPH', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(205, 'INS-0205', 'ESTRUCTURA VITRINA TSC TRIUMPH', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(204, 'INS-0204', 'ESTRUCTURA VITRINA CFMOTO 1 X 2,10', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(203, 'INS-0203', 'ESTRUCTURA PORTA CASCOS MOTOS', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(202, 'INS-0202', 'ESTRUCTURA MODULO C08B', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(201, 'INS-0201', 'ESTRUCTURA EXTENSION RECEPCION CB03', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(200, 'INS-0200', 'ESTRUCTURA CB11 VITRINA DE REPUESTOS CORVEN BAJAJ', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(199, 'INS-0199', 'ESTRUCTURA C08B MUEBLE DE ACCESORIOS CORVEN BAJAJ', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(198, 'INS-0198', 'CUPULA DE VIDRIO VITRINA CFMOTO', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(197, 'INS-0197', 'CUERINA/TELA CHERY PARA 3 CUERPOS', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(196, 'INS-0196', 'CUERINA/TELA CHERY PARA 2 CUERPOS', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(195, 'INS-0195', 'CONTRAPESO ATRIL (HIERROMAS)', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(194, 'INS-0194', 'CONJUNTO VIDRIOS VITRINA CFMOTO', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(193, 'INS-0193', 'CONJUNTO ESPUMA P 3 CUERPOS', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(192, 'INS-0192', 'CONJUNTO ESPUMA P 2 CUERPOS', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(191, 'INS-0191', 'CHAPA PERFORADA ALUMINIO MEVACO', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(190, 'INS-0190', 'CESTO MUEBLE CAFÉ RENAULT', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(189, 'INS-0189', 'CERRADURA TAMBOR FRONTAL', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(188, 'INS-0188', 'CERRADURA COLECTIVA LATERAL', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(187, 'INS-0187', 'CERRADURA COLECTIVA FRONTAL', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(186, 'INS-0186', 'CASCO JE06', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(185, 'INS-0185', 'CASCO JE04', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(184, 'INS-0184', 'CASCO FIBRA PGT RENAULT', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(183, 'INS-0183', 'CASCO BAJO CHERY SILLA CLIENTE', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(182, 'INS-0182', 'CASCO ALTO CHERY VENDEDOR Y ESPERA', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(181, 'INS-0181', 'CAÑO PERCHERO OVAL CROMO EN MTS', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(180, 'INS-0180', 'CALCO WELCOME DESK TWD', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(179, 'INS-0179', 'CALCO TSW 01 TRIUMPH', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(178, 'INS-0178', 'CALCO SERVICE RECEPTION', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(177, 'INS-0177', 'CALCO CORONA COMMUNITY WALL TRIUMPH', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(176, 'INS-0176', 'CALCO APPAREL TOTEM TRIUMPH', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(175, 'INS-0175', 'CALCO ACCESORIES TOTEM TRIUMPH', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(174, 'INS-0174', 'BISAGRAS PARA VIDRIOS', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(173, 'INS-0173', 'BASE TRINEO JE04', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(172, 'INS-0172', 'BASE PULPITO', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(171, 'INS-0171', 'BASE PLEGADA MESA CENTRO CHERY', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(170, 'INS-0170', 'BASE PLASTICA RECTANGULAR', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(169, 'INS-0169', 'BASE PARA SILLAS 5 RAYOS RENAULT', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(168, 'INS-0168', 'BASE PARA SILLAS 4 RAYOS RENAULT', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(167, 'INS-0167', 'BASE METALICA PUNTO ENCUENTRO RENAULT', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(166, 'INS-0166', 'BASE MESA RE002 RENAULT', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(165, 'INS-0165', 'BASE MESA DE CENTRO TST 01 TRIUMPH', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(164, 'INS-0164', 'BASE MESA COWORKING RENAULT', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(163, 'INS-0163', 'BASE CROMADA SILLON SKI', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(162, 'INS-0162', 'BANQUETA VENETO NEGRA BASE CROMO', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(161, 'INS-0161', 'BANQUETA TOLIX', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(160, 'INS-0160', 'BANQUETA SIENA NEGRA', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(159, 'INS-0159', 'BANQUETA RENAULT', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(158, 'INS-0158', 'BALDOSA SEMILLA DE MELON TARIMA CFMOTO', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(157, 'INS-0157', 'BALDOSA LISAS CFMOTO', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(156, 'INS-0156', 'ATRIL TRIUMPH', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(155, 'INS-0155', 'ATRIL JETOUR', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(154, 'INS-0154', 'ANGULO ALUMINIO 25 MM X25 MM', 1, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(153, 'INS-0153', 'ACCESORIOS PORTA CALZADO', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(152, 'INS-0001', 'ACCESORIOS PORTA BLISTER', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:26', '2026-06-01 12:51:26'),
(274, 'INS-0274', 'SILLA ATHINA BASE CROMO NEGRA FULL CON ARO POSAPIE', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(275, 'INS-0275', 'SILLA ATHINA BASE CROMO TRINEO NEGRA FULL', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(276, 'INS-0276', 'SILLA BASTONE ALTA BASE CROMO PORT', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(277, 'INS-0277', 'SILLA BASTONE BAJA BASE CROMO PORT', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(278, 'INS-0278', 'SILLA GROU FULL NEGRA ROLIC', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(279, 'INS-0279', 'SILLA GROU FULL NEGRA ROLIC BASE ARO POSAPIE', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(280, 'INS-0280', 'SILLA GUD EXECUTIVE NOVEC', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(281, 'INS-0281', 'SILLA INDIA BASE TRINEO FULL NEGRA PORT', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(282, 'INS-0282', 'SILLA MINT FULL NEGRA CON APOYA CABEZA Y LUMBAR ROL', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(283, 'INS-0283', 'SILLA MINT FULL NEGRA CON APOYA CABEZA Y LUMBAR Y ARO POSAPIE ROL', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(284, 'INS-0284', 'SILLA TOKIO FULL NEGRA BASE CROMADA', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(285, 'INS-0285', 'SILLON LC3 1 CUERPO OPCION 1', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(286, 'INS-0286', 'SILLON RE028 SACHETI RENAULT', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(287, 'INS-0287', 'SILLON TCH 05 TRIUMPH', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(288, 'INS-0288', 'SOBRE PARA ATRIL', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(289, 'INS-0289', 'SOPORTE CAÑO PERCHERO OVAL', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(290, 'INS-0290', 'SOPORTE MB01 TRIUMPH', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(291, 'INS-0291', 'SOPORTE MB04 TRIUMPH', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(292, 'INS-0292', 'SOPORTE MB20 TRIUMPH', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(293, 'INS-0293', 'SOPORTE MB22 TRIUMPH', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(294, 'INS-0294', 'TABURETE MULTIMARCAS', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(295, 'INS-0295', 'TAPIZADO DEDOS PANEL JETOUR', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(296, 'INS-0296', 'TARIMA TRIUMPH PLEGADA', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(297, 'INS-0297', 'TIRADOR BALA', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(298, 'INS-0298', 'TOTEM HERO TRIUMPH', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(299, 'INS-0299', 'TOTEM TAO43 TRIUMPH', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(300, 'INS-0300', 'TUBO LED', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(301, 'INS-0301', 'VIDRIO TOTEM PLV', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(302, 'INS-0302', 'VINILOS UV MATE CFMOTO', 8, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 12:51:27', '2026-06-01 12:51:27'),
(303, 'INS-0303', 'PATA MESA BAJA CHERY', 8, 1.00, 0.00, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 16:16:45', '2026-06-01 16:16:45');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `insumo_marcas_silla`
--

DROP TABLE IF EXISTS `insumo_marcas_silla`;
CREATE TABLE IF NOT EXISTS `insumo_marcas_silla` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `insumo_id` bigint UNSIGNED NOT NULL,
  `marca_id` bigint UNSIGNED DEFAULT NULL,
  `nombre_fantasia` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `insumo_marcas_silla_insumo_id_foreign` (`insumo_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `insumo_marcas_silla`
--

INSERT INTO `insumo_marcas_silla` (`id`, `insumo_id`, `marca_id`, `nombre_fantasia`, `created_at`, `updated_at`) VALUES
(1, 3, 9, 'SILLA VENDEDOR CHEVROLET', '2026-05-28 18:23:17', '2026-05-28 18:23:17'),
(2, 3, 1, 'SILLON VENDEDOR', '2026-05-28 18:23:17', '2026-05-28 18:23:17');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jobs`
--

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(191) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `jobs`
--

INSERT INTO `jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`) VALUES
(1, 'default', '{\"uuid\":\"dd55d19e-271d-48a3-8b99-0eeda34f7961\",\"displayName\":\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\",\"command\":\"O:58:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\\\":6:{s:14:\\\"\\u0000*\\u0000conversions\\\";O:52:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\ConversionCollection\\\":2:{s:8:\\\"\\u0000*\\u0000items\\\";a:1:{i:0;O:42:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Conversion\\\":12:{s:12:\\\"\\u0000*\\u0000fileNamer\\\";O:54:\\\"Spatie\\\\MediaLibrary\\\\Support\\\\FileNamer\\\\DefaultFileNamer\\\":0:{}s:28:\\\"\\u0000*\\u0000extractVideoFrameAtSecond\\\";d:0;s:16:\\\"\\u0000*\\u0000manipulations\\\";O:45:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Manipulations\\\":1:{s:16:\\\"\\u0000*\\u0000manipulations\\\";a:5:{s:8:\\\"optimize\\\";a:1:{i:0;O:36:\\\"Spatie\\\\ImageOptimizer\\\\OptimizerChain\\\":3:{s:13:\\\"\\u0000*\\u0000optimizers\\\";a:7:{i:0;O:42:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Jpegoptim\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m85\\\";i:1;s:7:\\\"--force\\\";i:2;s:11:\\\"--strip-all\\\";i:3;s:17:\\\"--all-progressive\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:9:\\\"jpegoptim\\\";}i:1;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Pngquant\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:7:\\\"--force\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"pngquant\\\";}i:2;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Optipng\\\":5:{s:7:\\\"options\\\";a:3:{i:0;s:3:\\\"-i0\\\";i:1;s:3:\\\"-o2\\\";i:2;s:6:\\\"-quiet\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"optipng\\\";}i:3;O:37:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Svgo\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:20:\\\"--disable=cleanupIDs\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:4:\\\"svgo\\\";}i:4;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Gifsicle\\\":5:{s:7:\\\"options\\\";a:2:{i:0;s:2:\\\"-b\\\";i:1;s:3:\\\"-O3\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"gifsicle\\\";}i:5;O:38:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Cwebp\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m 6\\\";i:1;s:8:\\\"-pass 10\\\";i:2;s:3:\\\"-mt\\\";i:3;s:5:\\\"-q 90\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:5:\\\"cwebp\\\";}i:6;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Avifenc\\\":6:{s:7:\\\"options\\\";a:8:{i:0;s:14:\\\"-a cq-level=23\\\";i:1;s:6:\\\"-j all\\\";i:2;s:7:\\\"--min 0\\\";i:3;s:8:\\\"--max 63\\\";i:4;s:12:\\\"--minalpha 0\\\";i:5;s:13:\\\"--maxalpha 63\\\";i:6;s:14:\\\"-a end-usage=q\\\";i:7;s:12:\\\"-a tune=ssim\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"avifenc\\\";s:16:\\\"decodeBinaryName\\\";s:7:\\\"avifdec\\\";}}s:9:\\\"\\u0000*\\u0000logger\\\";O:33:\\\"Spatie\\\\ImageOptimizer\\\\DummyLogger\\\":0:{}s:10:\\\"\\u0000*\\u0000timeout\\\";i:60;}}s:6:\\\"format\\\";a:1:{i:0;s:3:\\\"jpg\\\";}s:5:\\\"width\\\";a:1:{i:0;i:300;}s:6:\\\"height\\\";a:1:{i:0;i:300;}s:7:\\\"sharpen\\\";a:1:{i:0;i:10;}}}s:23:\\\"\\u0000*\\u0000performOnCollections\\\";a:0:{}s:17:\\\"\\u0000*\\u0000performOnQueue\\\";b:1;s:18:\\\"\\u0000*\\u0000performDeferred\\\";b:0;s:26:\\\"\\u0000*\\u0000keepOriginalImageFormat\\\";b:0;s:27:\\\"\\u0000*\\u0000generateResponsiveImages\\\";b:0;s:18:\\\"\\u0000*\\u0000widthCalculator\\\";N;s:24:\\\"\\u0000*\\u0000loadingAttributeValue\\\";N;s:16:\\\"\\u0000*\\u0000pdfPageNumber\\\";i:1;s:7:\\\"\\u0000*\\u0000name\\\";s:5:\\\"thumb\\\";}}s:28:\\\"\\u0000*\\u0000escapeWhenCastingToString\\\";b:0;}s:8:\\\"\\u0000*\\u0000media\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:49:\\\"Spatie\\\\MediaLibrary\\\\MediaCollections\\\\Models\\\\Media\\\";s:2:\\\"id\\\";i:1;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:14:\\\"\\u0000*\\u0000onlyMissing\\\";b:0;s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:0:\\\"\\\";s:11:\\\"afterCommit\\\";b:1;}\"}}', 0, NULL, 1779283417, 1779283417),
(2, 'default', '{\"uuid\":\"a8e60c5e-f13e-459d-9e65-fd3d63d757f1\",\"displayName\":\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\",\"command\":\"O:58:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\\\":6:{s:14:\\\"\\u0000*\\u0000conversions\\\";O:52:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\ConversionCollection\\\":2:{s:8:\\\"\\u0000*\\u0000items\\\";a:1:{i:0;O:42:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Conversion\\\":12:{s:12:\\\"\\u0000*\\u0000fileNamer\\\";O:54:\\\"Spatie\\\\MediaLibrary\\\\Support\\\\FileNamer\\\\DefaultFileNamer\\\":0:{}s:28:\\\"\\u0000*\\u0000extractVideoFrameAtSecond\\\";d:0;s:16:\\\"\\u0000*\\u0000manipulations\\\";O:45:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Manipulations\\\":1:{s:16:\\\"\\u0000*\\u0000manipulations\\\";a:5:{s:8:\\\"optimize\\\";a:1:{i:0;O:36:\\\"Spatie\\\\ImageOptimizer\\\\OptimizerChain\\\":3:{s:13:\\\"\\u0000*\\u0000optimizers\\\";a:7:{i:0;O:42:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Jpegoptim\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m85\\\";i:1;s:7:\\\"--force\\\";i:2;s:11:\\\"--strip-all\\\";i:3;s:17:\\\"--all-progressive\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:9:\\\"jpegoptim\\\";}i:1;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Pngquant\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:7:\\\"--force\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"pngquant\\\";}i:2;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Optipng\\\":5:{s:7:\\\"options\\\";a:3:{i:0;s:3:\\\"-i0\\\";i:1;s:3:\\\"-o2\\\";i:2;s:6:\\\"-quiet\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"optipng\\\";}i:3;O:37:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Svgo\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:20:\\\"--disable=cleanupIDs\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:4:\\\"svgo\\\";}i:4;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Gifsicle\\\":5:{s:7:\\\"options\\\";a:2:{i:0;s:2:\\\"-b\\\";i:1;s:3:\\\"-O3\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"gifsicle\\\";}i:5;O:38:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Cwebp\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m 6\\\";i:1;s:8:\\\"-pass 10\\\";i:2;s:3:\\\"-mt\\\";i:3;s:5:\\\"-q 90\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:5:\\\"cwebp\\\";}i:6;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Avifenc\\\":6:{s:7:\\\"options\\\";a:8:{i:0;s:14:\\\"-a cq-level=23\\\";i:1;s:6:\\\"-j all\\\";i:2;s:7:\\\"--min 0\\\";i:3;s:8:\\\"--max 63\\\";i:4;s:12:\\\"--minalpha 0\\\";i:5;s:13:\\\"--maxalpha 63\\\";i:6;s:14:\\\"-a end-usage=q\\\";i:7;s:12:\\\"-a tune=ssim\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"avifenc\\\";s:16:\\\"decodeBinaryName\\\";s:7:\\\"avifdec\\\";}}s:9:\\\"\\u0000*\\u0000logger\\\";O:33:\\\"Spatie\\\\ImageOptimizer\\\\DummyLogger\\\":0:{}s:10:\\\"\\u0000*\\u0000timeout\\\";i:60;}}s:6:\\\"format\\\";a:1:{i:0;s:3:\\\"jpg\\\";}s:5:\\\"width\\\";a:1:{i:0;i:300;}s:6:\\\"height\\\";a:1:{i:0;i:300;}s:7:\\\"sharpen\\\";a:1:{i:0;i:10;}}}s:23:\\\"\\u0000*\\u0000performOnCollections\\\";a:0:{}s:17:\\\"\\u0000*\\u0000performOnQueue\\\";b:1;s:18:\\\"\\u0000*\\u0000performDeferred\\\";b:0;s:26:\\\"\\u0000*\\u0000keepOriginalImageFormat\\\";b:0;s:27:\\\"\\u0000*\\u0000generateResponsiveImages\\\";b:0;s:18:\\\"\\u0000*\\u0000widthCalculator\\\";N;s:24:\\\"\\u0000*\\u0000loadingAttributeValue\\\";N;s:16:\\\"\\u0000*\\u0000pdfPageNumber\\\";i:1;s:7:\\\"\\u0000*\\u0000name\\\";s:5:\\\"thumb\\\";}}s:28:\\\"\\u0000*\\u0000escapeWhenCastingToString\\\";b:0;}s:8:\\\"\\u0000*\\u0000media\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:49:\\\"Spatie\\\\MediaLibrary\\\\MediaCollections\\\\Models\\\\Media\\\";s:2:\\\"id\\\";i:2;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:14:\\\"\\u0000*\\u0000onlyMissing\\\";b:0;s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:0:\\\"\\\";s:11:\\\"afterCommit\\\";b:1;}\"}}', 0, NULL, 1779283417, 1779283417),
(3, 'default', '{\"uuid\":\"cdad49c5-5665-4878-9a42-bf98e4821a1a\",\"displayName\":\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\",\"command\":\"O:58:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\\\":6:{s:14:\\\"\\u0000*\\u0000conversions\\\";O:52:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\ConversionCollection\\\":2:{s:8:\\\"\\u0000*\\u0000items\\\";a:2:{i:0;O:42:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Conversion\\\":12:{s:12:\\\"\\u0000*\\u0000fileNamer\\\";O:54:\\\"Spatie\\\\MediaLibrary\\\\Support\\\\FileNamer\\\\DefaultFileNamer\\\":0:{}s:28:\\\"\\u0000*\\u0000extractVideoFrameAtSecond\\\";d:0;s:16:\\\"\\u0000*\\u0000manipulations\\\";O:45:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Manipulations\\\":1:{s:16:\\\"\\u0000*\\u0000manipulations\\\";a:5:{s:8:\\\"optimize\\\";a:1:{i:0;O:36:\\\"Spatie\\\\ImageOptimizer\\\\OptimizerChain\\\":3:{s:13:\\\"\\u0000*\\u0000optimizers\\\";a:7:{i:0;O:42:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Jpegoptim\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m85\\\";i:1;s:7:\\\"--force\\\";i:2;s:11:\\\"--strip-all\\\";i:3;s:17:\\\"--all-progressive\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:9:\\\"jpegoptim\\\";}i:1;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Pngquant\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:7:\\\"--force\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"pngquant\\\";}i:2;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Optipng\\\":5:{s:7:\\\"options\\\";a:3:{i:0;s:3:\\\"-i0\\\";i:1;s:3:\\\"-o2\\\";i:2;s:6:\\\"-quiet\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"optipng\\\";}i:3;O:37:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Svgo\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:20:\\\"--disable=cleanupIDs\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:4:\\\"svgo\\\";}i:4;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Gifsicle\\\":5:{s:7:\\\"options\\\";a:2:{i:0;s:2:\\\"-b\\\";i:1;s:3:\\\"-O3\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"gifsicle\\\";}i:5;O:38:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Cwebp\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m 6\\\";i:1;s:8:\\\"-pass 10\\\";i:2;s:3:\\\"-mt\\\";i:3;s:5:\\\"-q 90\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:5:\\\"cwebp\\\";}i:6;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Avifenc\\\":6:{s:7:\\\"options\\\";a:8:{i:0;s:14:\\\"-a cq-level=23\\\";i:1;s:6:\\\"-j all\\\";i:2;s:7:\\\"--min 0\\\";i:3;s:8:\\\"--max 63\\\";i:4;s:12:\\\"--minalpha 0\\\";i:5;s:13:\\\"--maxalpha 63\\\";i:6;s:14:\\\"-a end-usage=q\\\";i:7;s:12:\\\"-a tune=ssim\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"avifenc\\\";s:16:\\\"decodeBinaryName\\\";s:7:\\\"avifdec\\\";}}s:9:\\\"\\u0000*\\u0000logger\\\";O:33:\\\"Spatie\\\\ImageOptimizer\\\\DummyLogger\\\":0:{}s:10:\\\"\\u0000*\\u0000timeout\\\";i:60;}}s:6:\\\"format\\\";a:1:{i:0;s:3:\\\"jpg\\\";}s:5:\\\"width\\\";a:1:{i:0;i:300;}s:6:\\\"height\\\";a:1:{i:0;i:300;}s:7:\\\"sharpen\\\";a:1:{i:0;i:10;}}}s:23:\\\"\\u0000*\\u0000performOnCollections\\\";a:0:{}s:17:\\\"\\u0000*\\u0000performOnQueue\\\";b:1;s:18:\\\"\\u0000*\\u0000performDeferred\\\";b:0;s:26:\\\"\\u0000*\\u0000keepOriginalImageFormat\\\";b:0;s:27:\\\"\\u0000*\\u0000generateResponsiveImages\\\";b:0;s:18:\\\"\\u0000*\\u0000widthCalculator\\\";N;s:24:\\\"\\u0000*\\u0000loadingAttributeValue\\\";N;s:16:\\\"\\u0000*\\u0000pdfPageNumber\\\";i:1;s:7:\\\"\\u0000*\\u0000name\\\";s:5:\\\"thumb\\\";}i:1;O:42:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Conversion\\\":12:{s:12:\\\"\\u0000*\\u0000fileNamer\\\";O:54:\\\"Spatie\\\\MediaLibrary\\\\Support\\\\FileNamer\\\\DefaultFileNamer\\\":0:{}s:28:\\\"\\u0000*\\u0000extractVideoFrameAtSecond\\\";d:0;s:16:\\\"\\u0000*\\u0000manipulations\\\";O:45:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Manipulations\\\":1:{s:16:\\\"\\u0000*\\u0000manipulations\\\";a:4:{s:8:\\\"optimize\\\";a:1:{i:0;O:36:\\\"Spatie\\\\ImageOptimizer\\\\OptimizerChain\\\":3:{s:13:\\\"\\u0000*\\u0000optimizers\\\";a:7:{i:0;O:42:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Jpegoptim\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m85\\\";i:1;s:7:\\\"--force\\\";i:2;s:11:\\\"--strip-all\\\";i:3;s:17:\\\"--all-progressive\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:9:\\\"jpegoptim\\\";}i:1;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Pngquant\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:7:\\\"--force\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"pngquant\\\";}i:2;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Optipng\\\":5:{s:7:\\\"options\\\";a:3:{i:0;s:3:\\\"-i0\\\";i:1;s:3:\\\"-o2\\\";i:2;s:6:\\\"-quiet\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"optipng\\\";}i:3;O:37:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Svgo\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:20:\\\"--disable=cleanupIDs\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:4:\\\"svgo\\\";}i:4;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Gifsicle\\\":5:{s:7:\\\"options\\\";a:2:{i:0;s:2:\\\"-b\\\";i:1;s:3:\\\"-O3\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"gifsicle\\\";}i:5;O:38:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Cwebp\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m 6\\\";i:1;s:8:\\\"-pass 10\\\";i:2;s:3:\\\"-mt\\\";i:3;s:5:\\\"-q 90\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:5:\\\"cwebp\\\";}i:6;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Avifenc\\\":6:{s:7:\\\"options\\\";a:8:{i:0;s:14:\\\"-a cq-level=23\\\";i:1;s:6:\\\"-j all\\\";i:2;s:7:\\\"--min 0\\\";i:3;s:8:\\\"--max 63\\\";i:4;s:12:\\\"--minalpha 0\\\";i:5;s:13:\\\"--maxalpha 63\\\";i:6;s:14:\\\"-a end-usage=q\\\";i:7;s:12:\\\"-a tune=ssim\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"avifenc\\\";s:16:\\\"decodeBinaryName\\\";s:7:\\\"avifdec\\\";}}s:9:\\\"\\u0000*\\u0000logger\\\";O:33:\\\"Spatie\\\\ImageOptimizer\\\\DummyLogger\\\":0:{}s:10:\\\"\\u0000*\\u0000timeout\\\";i:60;}}s:6:\\\"format\\\";a:1:{i:0;s:3:\\\"jpg\\\";}s:5:\\\"width\\\";a:1:{i:0;i:800;}s:6:\\\"height\\\";a:1:{i:0;i:600;}}}s:23:\\\"\\u0000*\\u0000performOnCollections\\\";a:0:{}s:17:\\\"\\u0000*\\u0000performOnQueue\\\";b:1;s:18:\\\"\\u0000*\\u0000performDeferred\\\";b:0;s:26:\\\"\\u0000*\\u0000keepOriginalImageFormat\\\";b:0;s:27:\\\"\\u0000*\\u0000generateResponsiveImages\\\";b:0;s:18:\\\"\\u0000*\\u0000widthCalculator\\\";N;s:24:\\\"\\u0000*\\u0000loadingAttributeValue\\\";N;s:16:\\\"\\u0000*\\u0000pdfPageNumber\\\";i:1;s:7:\\\"\\u0000*\\u0000name\\\";s:6:\\\"medium\\\";}}s:28:\\\"\\u0000*\\u0000escapeWhenCastingToString\\\";b:0;}s:8:\\\"\\u0000*\\u0000media\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:49:\\\"Spatie\\\\MediaLibrary\\\\MediaCollections\\\\Models\\\\Media\\\";s:2:\\\"id\\\";i:3;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:14:\\\"\\u0000*\\u0000onlyMissing\\\";b:0;s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:0:\\\"\\\";s:11:\\\"afterCommit\\\";b:1;}\"}}', 0, NULL, 1779284225, 1779284225),
(4, 'default', '{\"uuid\":\"a2f0eac5-c5ae-4460-b40a-3d7e9150bb15\",\"displayName\":\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\",\"command\":\"O:58:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\\\":6:{s:14:\\\"\\u0000*\\u0000conversions\\\";O:52:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\ConversionCollection\\\":2:{s:8:\\\"\\u0000*\\u0000items\\\";a:2:{i:0;O:42:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Conversion\\\":12:{s:12:\\\"\\u0000*\\u0000fileNamer\\\";O:54:\\\"Spatie\\\\MediaLibrary\\\\Support\\\\FileNamer\\\\DefaultFileNamer\\\":0:{}s:28:\\\"\\u0000*\\u0000extractVideoFrameAtSecond\\\";d:0;s:16:\\\"\\u0000*\\u0000manipulations\\\";O:45:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Manipulations\\\":1:{s:16:\\\"\\u0000*\\u0000manipulations\\\";a:5:{s:8:\\\"optimize\\\";a:1:{i:0;O:36:\\\"Spatie\\\\ImageOptimizer\\\\OptimizerChain\\\":3:{s:13:\\\"\\u0000*\\u0000optimizers\\\";a:7:{i:0;O:42:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Jpegoptim\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m85\\\";i:1;s:7:\\\"--force\\\";i:2;s:11:\\\"--strip-all\\\";i:3;s:17:\\\"--all-progressive\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:9:\\\"jpegoptim\\\";}i:1;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Pngquant\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:7:\\\"--force\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"pngquant\\\";}i:2;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Optipng\\\":5:{s:7:\\\"options\\\";a:3:{i:0;s:3:\\\"-i0\\\";i:1;s:3:\\\"-o2\\\";i:2;s:6:\\\"-quiet\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"optipng\\\";}i:3;O:37:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Svgo\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:20:\\\"--disable=cleanupIDs\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:4:\\\"svgo\\\";}i:4;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Gifsicle\\\":5:{s:7:\\\"options\\\";a:2:{i:0;s:2:\\\"-b\\\";i:1;s:3:\\\"-O3\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"gifsicle\\\";}i:5;O:38:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Cwebp\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m 6\\\";i:1;s:8:\\\"-pass 10\\\";i:2;s:3:\\\"-mt\\\";i:3;s:5:\\\"-q 90\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:5:\\\"cwebp\\\";}i:6;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Avifenc\\\":6:{s:7:\\\"options\\\";a:8:{i:0;s:14:\\\"-a cq-level=23\\\";i:1;s:6:\\\"-j all\\\";i:2;s:7:\\\"--min 0\\\";i:3;s:8:\\\"--max 63\\\";i:4;s:12:\\\"--minalpha 0\\\";i:5;s:13:\\\"--maxalpha 63\\\";i:6;s:14:\\\"-a end-usage=q\\\";i:7;s:12:\\\"-a tune=ssim\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"avifenc\\\";s:16:\\\"decodeBinaryName\\\";s:7:\\\"avifdec\\\";}}s:9:\\\"\\u0000*\\u0000logger\\\";O:33:\\\"Spatie\\\\ImageOptimizer\\\\DummyLogger\\\":0:{}s:10:\\\"\\u0000*\\u0000timeout\\\";i:60;}}s:6:\\\"format\\\";a:1:{i:0;s:3:\\\"jpg\\\";}s:5:\\\"width\\\";a:1:{i:0;i:300;}s:6:\\\"height\\\";a:1:{i:0;i:300;}s:7:\\\"sharpen\\\";a:1:{i:0;i:10;}}}s:23:\\\"\\u0000*\\u0000performOnCollections\\\";a:0:{}s:17:\\\"\\u0000*\\u0000performOnQueue\\\";b:1;s:18:\\\"\\u0000*\\u0000performDeferred\\\";b:0;s:26:\\\"\\u0000*\\u0000keepOriginalImageFormat\\\";b:0;s:27:\\\"\\u0000*\\u0000generateResponsiveImages\\\";b:0;s:18:\\\"\\u0000*\\u0000widthCalculator\\\";N;s:24:\\\"\\u0000*\\u0000loadingAttributeValue\\\";N;s:16:\\\"\\u0000*\\u0000pdfPageNumber\\\";i:1;s:7:\\\"\\u0000*\\u0000name\\\";s:5:\\\"thumb\\\";}i:1;O:42:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Conversion\\\":12:{s:12:\\\"\\u0000*\\u0000fileNamer\\\";O:54:\\\"Spatie\\\\MediaLibrary\\\\Support\\\\FileNamer\\\\DefaultFileNamer\\\":0:{}s:28:\\\"\\u0000*\\u0000extractVideoFrameAtSecond\\\";d:0;s:16:\\\"\\u0000*\\u0000manipulations\\\";O:45:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Manipulations\\\":1:{s:16:\\\"\\u0000*\\u0000manipulations\\\";a:4:{s:8:\\\"optimize\\\";a:1:{i:0;O:36:\\\"Spatie\\\\ImageOptimizer\\\\OptimizerChain\\\":3:{s:13:\\\"\\u0000*\\u0000optimizers\\\";a:7:{i:0;O:42:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Jpegoptim\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m85\\\";i:1;s:7:\\\"--force\\\";i:2;s:11:\\\"--strip-all\\\";i:3;s:17:\\\"--all-progressive\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:9:\\\"jpegoptim\\\";}i:1;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Pngquant\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:7:\\\"--force\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"pngquant\\\";}i:2;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Optipng\\\":5:{s:7:\\\"options\\\";a:3:{i:0;s:3:\\\"-i0\\\";i:1;s:3:\\\"-o2\\\";i:2;s:6:\\\"-quiet\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"optipng\\\";}i:3;O:37:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Svgo\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:20:\\\"--disable=cleanupIDs\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:4:\\\"svgo\\\";}i:4;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Gifsicle\\\":5:{s:7:\\\"options\\\";a:2:{i:0;s:2:\\\"-b\\\";i:1;s:3:\\\"-O3\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"gifsicle\\\";}i:5;O:38:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Cwebp\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m 6\\\";i:1;s:8:\\\"-pass 10\\\";i:2;s:3:\\\"-mt\\\";i:3;s:5:\\\"-q 90\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:5:\\\"cwebp\\\";}i:6;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Avifenc\\\":6:{s:7:\\\"options\\\";a:8:{i:0;s:14:\\\"-a cq-level=23\\\";i:1;s:6:\\\"-j all\\\";i:2;s:7:\\\"--min 0\\\";i:3;s:8:\\\"--max 63\\\";i:4;s:12:\\\"--minalpha 0\\\";i:5;s:13:\\\"--maxalpha 63\\\";i:6;s:14:\\\"-a end-usage=q\\\";i:7;s:12:\\\"-a tune=ssim\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"avifenc\\\";s:16:\\\"decodeBinaryName\\\";s:7:\\\"avifdec\\\";}}s:9:\\\"\\u0000*\\u0000logger\\\";O:33:\\\"Spatie\\\\ImageOptimizer\\\\DummyLogger\\\":0:{}s:10:\\\"\\u0000*\\u0000timeout\\\";i:60;}}s:6:\\\"format\\\";a:1:{i:0;s:3:\\\"jpg\\\";}s:5:\\\"width\\\";a:1:{i:0;i:800;}s:6:\\\"height\\\";a:1:{i:0;i:600;}}}s:23:\\\"\\u0000*\\u0000performOnCollections\\\";a:0:{}s:17:\\\"\\u0000*\\u0000performOnQueue\\\";b:1;s:18:\\\"\\u0000*\\u0000performDeferred\\\";b:0;s:26:\\\"\\u0000*\\u0000keepOriginalImageFormat\\\";b:0;s:27:\\\"\\u0000*\\u0000generateResponsiveImages\\\";b:0;s:18:\\\"\\u0000*\\u0000widthCalculator\\\";N;s:24:\\\"\\u0000*\\u0000loadingAttributeValue\\\";N;s:16:\\\"\\u0000*\\u0000pdfPageNumber\\\";i:1;s:7:\\\"\\u0000*\\u0000name\\\";s:6:\\\"medium\\\";}}s:28:\\\"\\u0000*\\u0000escapeWhenCastingToString\\\";b:0;}s:8:\\\"\\u0000*\\u0000media\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:49:\\\"Spatie\\\\MediaLibrary\\\\MediaCollections\\\\Models\\\\Media\\\";s:2:\\\"id\\\";i:4;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:14:\\\"\\u0000*\\u0000onlyMissing\\\";b:0;s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:0:\\\"\\\";s:11:\\\"afterCommit\\\";b:1;}\"}}', 0, NULL, 1779284476, 1779284476),
(5, 'default', '{\"uuid\":\"f2c64860-96a9-43e3-ac48-17ce5821e1b4\",\"displayName\":\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\",\"command\":\"O:58:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\\\":6:{s:14:\\\"\\u0000*\\u0000conversions\\\";O:52:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\ConversionCollection\\\":2:{s:8:\\\"\\u0000*\\u0000items\\\";a:2:{i:0;O:42:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Conversion\\\":12:{s:12:\\\"\\u0000*\\u0000fileNamer\\\";O:54:\\\"Spatie\\\\MediaLibrary\\\\Support\\\\FileNamer\\\\DefaultFileNamer\\\":0:{}s:28:\\\"\\u0000*\\u0000extractVideoFrameAtSecond\\\";d:0;s:16:\\\"\\u0000*\\u0000manipulations\\\";O:45:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Manipulations\\\":1:{s:16:\\\"\\u0000*\\u0000manipulations\\\";a:5:{s:8:\\\"optimize\\\";a:1:{i:0;O:36:\\\"Spatie\\\\ImageOptimizer\\\\OptimizerChain\\\":3:{s:13:\\\"\\u0000*\\u0000optimizers\\\";a:7:{i:0;O:42:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Jpegoptim\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m85\\\";i:1;s:7:\\\"--force\\\";i:2;s:11:\\\"--strip-all\\\";i:3;s:17:\\\"--all-progressive\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:9:\\\"jpegoptim\\\";}i:1;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Pngquant\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:7:\\\"--force\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"pngquant\\\";}i:2;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Optipng\\\":5:{s:7:\\\"options\\\";a:3:{i:0;s:3:\\\"-i0\\\";i:1;s:3:\\\"-o2\\\";i:2;s:6:\\\"-quiet\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"optipng\\\";}i:3;O:37:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Svgo\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:20:\\\"--disable=cleanupIDs\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:4:\\\"svgo\\\";}i:4;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Gifsicle\\\":5:{s:7:\\\"options\\\";a:2:{i:0;s:2:\\\"-b\\\";i:1;s:3:\\\"-O3\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"gifsicle\\\";}i:5;O:38:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Cwebp\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m 6\\\";i:1;s:8:\\\"-pass 10\\\";i:2;s:3:\\\"-mt\\\";i:3;s:5:\\\"-q 90\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:5:\\\"cwebp\\\";}i:6;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Avifenc\\\":6:{s:7:\\\"options\\\";a:8:{i:0;s:14:\\\"-a cq-level=23\\\";i:1;s:6:\\\"-j all\\\";i:2;s:7:\\\"--min 0\\\";i:3;s:8:\\\"--max 63\\\";i:4;s:12:\\\"--minalpha 0\\\";i:5;s:13:\\\"--maxalpha 63\\\";i:6;s:14:\\\"-a end-usage=q\\\";i:7;s:12:\\\"-a tune=ssim\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"avifenc\\\";s:16:\\\"decodeBinaryName\\\";s:7:\\\"avifdec\\\";}}s:9:\\\"\\u0000*\\u0000logger\\\";O:33:\\\"Spatie\\\\ImageOptimizer\\\\DummyLogger\\\":0:{}s:10:\\\"\\u0000*\\u0000timeout\\\";i:60;}}s:6:\\\"format\\\";a:1:{i:0;s:3:\\\"jpg\\\";}s:5:\\\"width\\\";a:1:{i:0;i:300;}s:6:\\\"height\\\";a:1:{i:0;i:300;}s:7:\\\"sharpen\\\";a:1:{i:0;i:10;}}}s:23:\\\"\\u0000*\\u0000performOnCollections\\\";a:0:{}s:17:\\\"\\u0000*\\u0000performOnQueue\\\";b:1;s:18:\\\"\\u0000*\\u0000performDeferred\\\";b:0;s:26:\\\"\\u0000*\\u0000keepOriginalImageFormat\\\";b:0;s:27:\\\"\\u0000*\\u0000generateResponsiveImages\\\";b:0;s:18:\\\"\\u0000*\\u0000widthCalculator\\\";N;s:24:\\\"\\u0000*\\u0000loadingAttributeValue\\\";N;s:16:\\\"\\u0000*\\u0000pdfPageNumber\\\";i:1;s:7:\\\"\\u0000*\\u0000name\\\";s:5:\\\"thumb\\\";}i:1;O:42:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Conversion\\\":12:{s:12:\\\"\\u0000*\\u0000fileNamer\\\";O:54:\\\"Spatie\\\\MediaLibrary\\\\Support\\\\FileNamer\\\\DefaultFileNamer\\\":0:{}s:28:\\\"\\u0000*\\u0000extractVideoFrameAtSecond\\\";d:0;s:16:\\\"\\u0000*\\u0000manipulations\\\";O:45:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Manipulations\\\":1:{s:16:\\\"\\u0000*\\u0000manipulations\\\";a:4:{s:8:\\\"optimize\\\";a:1:{i:0;O:36:\\\"Spatie\\\\ImageOptimizer\\\\OptimizerChain\\\":3:{s:13:\\\"\\u0000*\\u0000optimizers\\\";a:7:{i:0;O:42:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Jpegoptim\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m85\\\";i:1;s:7:\\\"--force\\\";i:2;s:11:\\\"--strip-all\\\";i:3;s:17:\\\"--all-progressive\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:9:\\\"jpegoptim\\\";}i:1;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Pngquant\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:7:\\\"--force\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"pngquant\\\";}i:2;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Optipng\\\":5:{s:7:\\\"options\\\";a:3:{i:0;s:3:\\\"-i0\\\";i:1;s:3:\\\"-o2\\\";i:2;s:6:\\\"-quiet\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"optipng\\\";}i:3;O:37:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Svgo\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:20:\\\"--disable=cleanupIDs\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:4:\\\"svgo\\\";}i:4;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Gifsicle\\\":5:{s:7:\\\"options\\\";a:2:{i:0;s:2:\\\"-b\\\";i:1;s:3:\\\"-O3\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"gifsicle\\\";}i:5;O:38:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Cwebp\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m 6\\\";i:1;s:8:\\\"-pass 10\\\";i:2;s:3:\\\"-mt\\\";i:3;s:5:\\\"-q 90\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:5:\\\"cwebp\\\";}i:6;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Avifenc\\\":6:{s:7:\\\"options\\\";a:8:{i:0;s:14:\\\"-a cq-level=23\\\";i:1;s:6:\\\"-j all\\\";i:2;s:7:\\\"--min 0\\\";i:3;s:8:\\\"--max 63\\\";i:4;s:12:\\\"--minalpha 0\\\";i:5;s:13:\\\"--maxalpha 63\\\";i:6;s:14:\\\"-a end-usage=q\\\";i:7;s:12:\\\"-a tune=ssim\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"avifenc\\\";s:16:\\\"decodeBinaryName\\\";s:7:\\\"avifdec\\\";}}s:9:\\\"\\u0000*\\u0000logger\\\";O:33:\\\"Spatie\\\\ImageOptimizer\\\\DummyLogger\\\":0:{}s:10:\\\"\\u0000*\\u0000timeout\\\";i:60;}}s:6:\\\"format\\\";a:1:{i:0;s:3:\\\"jpg\\\";}s:5:\\\"width\\\";a:1:{i:0;i:800;}s:6:\\\"height\\\";a:1:{i:0;i:600;}}}s:23:\\\"\\u0000*\\u0000performOnCollections\\\";a:0:{}s:17:\\\"\\u0000*\\u0000performOnQueue\\\";b:1;s:18:\\\"\\u0000*\\u0000performDeferred\\\";b:0;s:26:\\\"\\u0000*\\u0000keepOriginalImageFormat\\\";b:0;s:27:\\\"\\u0000*\\u0000generateResponsiveImages\\\";b:0;s:18:\\\"\\u0000*\\u0000widthCalculator\\\";N;s:24:\\\"\\u0000*\\u0000loadingAttributeValue\\\";N;s:16:\\\"\\u0000*\\u0000pdfPageNumber\\\";i:1;s:7:\\\"\\u0000*\\u0000name\\\";s:6:\\\"medium\\\";}}s:28:\\\"\\u0000*\\u0000escapeWhenCastingToString\\\";b:0;}s:8:\\\"\\u0000*\\u0000media\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:49:\\\"Spatie\\\\MediaLibrary\\\\MediaCollections\\\\Models\\\\Media\\\";s:2:\\\"id\\\";i:5;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:14:\\\"\\u0000*\\u0000onlyMissing\\\";b:0;s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:0:\\\"\\\";s:11:\\\"afterCommit\\\";b:1;}\"}}', 0, NULL, 1779284626, 1779284626),
(6, 'default', '{\"uuid\":\"d2b71e7f-4784-40f9-8be4-0bdec80cf584\",\"displayName\":\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\",\"command\":\"O:58:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\\\":6:{s:14:\\\"\\u0000*\\u0000conversions\\\";O:52:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\ConversionCollection\\\":2:{s:8:\\\"\\u0000*\\u0000items\\\";a:2:{i:0;O:42:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Conversion\\\":12:{s:12:\\\"\\u0000*\\u0000fileNamer\\\";O:54:\\\"Spatie\\\\MediaLibrary\\\\Support\\\\FileNamer\\\\DefaultFileNamer\\\":0:{}s:28:\\\"\\u0000*\\u0000extractVideoFrameAtSecond\\\";d:0;s:16:\\\"\\u0000*\\u0000manipulations\\\";O:45:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Manipulations\\\":1:{s:16:\\\"\\u0000*\\u0000manipulations\\\";a:5:{s:8:\\\"optimize\\\";a:1:{i:0;O:36:\\\"Spatie\\\\ImageOptimizer\\\\OptimizerChain\\\":3:{s:13:\\\"\\u0000*\\u0000optimizers\\\";a:7:{i:0;O:42:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Jpegoptim\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m85\\\";i:1;s:7:\\\"--force\\\";i:2;s:11:\\\"--strip-all\\\";i:3;s:17:\\\"--all-progressive\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:9:\\\"jpegoptim\\\";}i:1;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Pngquant\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:7:\\\"--force\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"pngquant\\\";}i:2;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Optipng\\\":5:{s:7:\\\"options\\\";a:3:{i:0;s:3:\\\"-i0\\\";i:1;s:3:\\\"-o2\\\";i:2;s:6:\\\"-quiet\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"optipng\\\";}i:3;O:37:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Svgo\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:20:\\\"--disable=cleanupIDs\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:4:\\\"svgo\\\";}i:4;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Gifsicle\\\":5:{s:7:\\\"options\\\";a:2:{i:0;s:2:\\\"-b\\\";i:1;s:3:\\\"-O3\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"gifsicle\\\";}i:5;O:38:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Cwebp\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m 6\\\";i:1;s:8:\\\"-pass 10\\\";i:2;s:3:\\\"-mt\\\";i:3;s:5:\\\"-q 90\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:5:\\\"cwebp\\\";}i:6;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Avifenc\\\":6:{s:7:\\\"options\\\";a:8:{i:0;s:14:\\\"-a cq-level=23\\\";i:1;s:6:\\\"-j all\\\";i:2;s:7:\\\"--min 0\\\";i:3;s:8:\\\"--max 63\\\";i:4;s:12:\\\"--minalpha 0\\\";i:5;s:13:\\\"--maxalpha 63\\\";i:6;s:14:\\\"-a end-usage=q\\\";i:7;s:12:\\\"-a tune=ssim\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"avifenc\\\";s:16:\\\"decodeBinaryName\\\";s:7:\\\"avifdec\\\";}}s:9:\\\"\\u0000*\\u0000logger\\\";O:33:\\\"Spatie\\\\ImageOptimizer\\\\DummyLogger\\\":0:{}s:10:\\\"\\u0000*\\u0000timeout\\\";i:60;}}s:6:\\\"format\\\";a:1:{i:0;s:3:\\\"jpg\\\";}s:5:\\\"width\\\";a:1:{i:0;i:300;}s:6:\\\"height\\\";a:1:{i:0;i:300;}s:7:\\\"sharpen\\\";a:1:{i:0;i:10;}}}s:23:\\\"\\u0000*\\u0000performOnCollections\\\";a:0:{}s:17:\\\"\\u0000*\\u0000performOnQueue\\\";b:1;s:18:\\\"\\u0000*\\u0000performDeferred\\\";b:0;s:26:\\\"\\u0000*\\u0000keepOriginalImageFormat\\\";b:0;s:27:\\\"\\u0000*\\u0000generateResponsiveImages\\\";b:0;s:18:\\\"\\u0000*\\u0000widthCalculator\\\";N;s:24:\\\"\\u0000*\\u0000loadingAttributeValue\\\";N;s:16:\\\"\\u0000*\\u0000pdfPageNumber\\\";i:1;s:7:\\\"\\u0000*\\u0000name\\\";s:5:\\\"thumb\\\";}i:1;O:42:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Conversion\\\":12:{s:12:\\\"\\u0000*\\u0000fileNamer\\\";O:54:\\\"Spatie\\\\MediaLibrary\\\\Support\\\\FileNamer\\\\DefaultFileNamer\\\":0:{}s:28:\\\"\\u0000*\\u0000extractVideoFrameAtSecond\\\";d:0;s:16:\\\"\\u0000*\\u0000manipulations\\\";O:45:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Manipulations\\\":1:{s:16:\\\"\\u0000*\\u0000manipulations\\\";a:4:{s:8:\\\"optimize\\\";a:1:{i:0;O:36:\\\"Spatie\\\\ImageOptimizer\\\\OptimizerChain\\\":3:{s:13:\\\"\\u0000*\\u0000optimizers\\\";a:7:{i:0;O:42:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Jpegoptim\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m85\\\";i:1;s:7:\\\"--force\\\";i:2;s:11:\\\"--strip-all\\\";i:3;s:17:\\\"--all-progressive\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:9:\\\"jpegoptim\\\";}i:1;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Pngquant\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:7:\\\"--force\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"pngquant\\\";}i:2;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Optipng\\\":5:{s:7:\\\"options\\\";a:3:{i:0;s:3:\\\"-i0\\\";i:1;s:3:\\\"-o2\\\";i:2;s:6:\\\"-quiet\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"optipng\\\";}i:3;O:37:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Svgo\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:20:\\\"--disable=cleanupIDs\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:4:\\\"svgo\\\";}i:4;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Gifsicle\\\":5:{s:7:\\\"options\\\";a:2:{i:0;s:2:\\\"-b\\\";i:1;s:3:\\\"-O3\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"gifsicle\\\";}i:5;O:38:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Cwebp\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m 6\\\";i:1;s:8:\\\"-pass 10\\\";i:2;s:3:\\\"-mt\\\";i:3;s:5:\\\"-q 90\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:5:\\\"cwebp\\\";}i:6;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Avifenc\\\":6:{s:7:\\\"options\\\";a:8:{i:0;s:14:\\\"-a cq-level=23\\\";i:1;s:6:\\\"-j all\\\";i:2;s:7:\\\"--min 0\\\";i:3;s:8:\\\"--max 63\\\";i:4;s:12:\\\"--minalpha 0\\\";i:5;s:13:\\\"--maxalpha 63\\\";i:6;s:14:\\\"-a end-usage=q\\\";i:7;s:12:\\\"-a tune=ssim\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"avifenc\\\";s:16:\\\"decodeBinaryName\\\";s:7:\\\"avifdec\\\";}}s:9:\\\"\\u0000*\\u0000logger\\\";O:33:\\\"Spatie\\\\ImageOptimizer\\\\DummyLogger\\\":0:{}s:10:\\\"\\u0000*\\u0000timeout\\\";i:60;}}s:6:\\\"format\\\";a:1:{i:0;s:3:\\\"jpg\\\";}s:5:\\\"width\\\";a:1:{i:0;i:800;}s:6:\\\"height\\\";a:1:{i:0;i:600;}}}s:23:\\\"\\u0000*\\u0000performOnCollections\\\";a:0:{}s:17:\\\"\\u0000*\\u0000performOnQueue\\\";b:1;s:18:\\\"\\u0000*\\u0000performDeferred\\\";b:0;s:26:\\\"\\u0000*\\u0000keepOriginalImageFormat\\\";b:0;s:27:\\\"\\u0000*\\u0000generateResponsiveImages\\\";b:0;s:18:\\\"\\u0000*\\u0000widthCalculator\\\";N;s:24:\\\"\\u0000*\\u0000loadingAttributeValue\\\";N;s:16:\\\"\\u0000*\\u0000pdfPageNumber\\\";i:1;s:7:\\\"\\u0000*\\u0000name\\\";s:6:\\\"medium\\\";}}s:28:\\\"\\u0000*\\u0000escapeWhenCastingToString\\\";b:0;}s:8:\\\"\\u0000*\\u0000media\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:49:\\\"Spatie\\\\MediaLibrary\\\\MediaCollections\\\\Models\\\\Media\\\";s:2:\\\"id\\\";i:6;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:14:\\\"\\u0000*\\u0000onlyMissing\\\";b:0;s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:0:\\\"\\\";s:11:\\\"afterCommit\\\";b:1;}\"}}', 0, NULL, 1779298601, 1779298601),
(7, 'default', '{\"uuid\":\"2c99215e-385d-4254-ab73-fb606dcc988a\",\"displayName\":\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\",\"command\":\"O:58:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\\\":6:{s:14:\\\"\\u0000*\\u0000conversions\\\";O:52:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\ConversionCollection\\\":2:{s:8:\\\"\\u0000*\\u0000items\\\";a:1:{i:0;O:42:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Conversion\\\":12:{s:12:\\\"\\u0000*\\u0000fileNamer\\\";O:54:\\\"Spatie\\\\MediaLibrary\\\\Support\\\\FileNamer\\\\DefaultFileNamer\\\":0:{}s:28:\\\"\\u0000*\\u0000extractVideoFrameAtSecond\\\";d:0;s:16:\\\"\\u0000*\\u0000manipulations\\\";O:45:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Manipulations\\\":1:{s:16:\\\"\\u0000*\\u0000manipulations\\\";a:4:{s:8:\\\"optimize\\\";a:1:{i:0;O:36:\\\"Spatie\\\\ImageOptimizer\\\\OptimizerChain\\\":3:{s:13:\\\"\\u0000*\\u0000optimizers\\\";a:7:{i:0;O:42:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Jpegoptim\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m85\\\";i:1;s:7:\\\"--force\\\";i:2;s:11:\\\"--strip-all\\\";i:3;s:17:\\\"--all-progressive\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:9:\\\"jpegoptim\\\";}i:1;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Pngquant\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:7:\\\"--force\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"pngquant\\\";}i:2;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Optipng\\\":5:{s:7:\\\"options\\\";a:3:{i:0;s:3:\\\"-i0\\\";i:1;s:3:\\\"-o2\\\";i:2;s:6:\\\"-quiet\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"optipng\\\";}i:3;O:37:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Svgo\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:20:\\\"--disable=cleanupIDs\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:4:\\\"svgo\\\";}i:4;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Gifsicle\\\":5:{s:7:\\\"options\\\";a:2:{i:0;s:2:\\\"-b\\\";i:1;s:3:\\\"-O3\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"gifsicle\\\";}i:5;O:38:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Cwebp\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m 6\\\";i:1;s:8:\\\"-pass 10\\\";i:2;s:3:\\\"-mt\\\";i:3;s:5:\\\"-q 90\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:5:\\\"cwebp\\\";}i:6;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Avifenc\\\":6:{s:7:\\\"options\\\";a:8:{i:0;s:14:\\\"-a cq-level=23\\\";i:1;s:6:\\\"-j all\\\";i:2;s:7:\\\"--min 0\\\";i:3;s:8:\\\"--max 63\\\";i:4;s:12:\\\"--minalpha 0\\\";i:5;s:13:\\\"--maxalpha 63\\\";i:6;s:14:\\\"-a end-usage=q\\\";i:7;s:12:\\\"-a tune=ssim\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"avifenc\\\";s:16:\\\"decodeBinaryName\\\";s:7:\\\"avifdec\\\";}}s:9:\\\"\\u0000*\\u0000logger\\\";O:33:\\\"Spatie\\\\ImageOptimizer\\\\DummyLogger\\\":0:{}s:10:\\\"\\u0000*\\u0000timeout\\\";i:60;}}s:6:\\\"format\\\";a:1:{i:0;s:3:\\\"jpg\\\";}s:3:\\\"fit\\\";a:3:{i:0;E:30:\\\"Spatie\\\\Image\\\\Enums\\\\Fit:Contain\\\";i:1;i:300;i:2;i:300;}s:7:\\\"sharpen\\\";a:1:{i:0;i:10;}}}s:23:\\\"\\u0000*\\u0000performOnCollections\\\";a:0:{}s:17:\\\"\\u0000*\\u0000performOnQueue\\\";b:1;s:18:\\\"\\u0000*\\u0000performDeferred\\\";b:0;s:26:\\\"\\u0000*\\u0000keepOriginalImageFormat\\\";b:0;s:27:\\\"\\u0000*\\u0000generateResponsiveImages\\\";b:0;s:18:\\\"\\u0000*\\u0000widthCalculator\\\";N;s:24:\\\"\\u0000*\\u0000loadingAttributeValue\\\";N;s:16:\\\"\\u0000*\\u0000pdfPageNumber\\\";i:1;s:7:\\\"\\u0000*\\u0000name\\\";s:5:\\\"thumb\\\";}}s:28:\\\"\\u0000*\\u0000escapeWhenCastingToString\\\";b:0;}s:8:\\\"\\u0000*\\u0000media\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:49:\\\"Spatie\\\\MediaLibrary\\\\MediaCollections\\\\Models\\\\Media\\\";s:2:\\\"id\\\";i:1;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:14:\\\"\\u0000*\\u0000onlyMissing\\\";b:0;s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:0:\\\"\\\";s:11:\\\"afterCommit\\\";b:1;}\"}}', 0, NULL, 1779392362, 1779392362);
INSERT INTO `jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`) VALUES
(8, 'default', '{\"uuid\":\"d3baab5a-24de-4428-9f10-d6abcb37af28\",\"displayName\":\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\",\"command\":\"O:58:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\\\":6:{s:14:\\\"\\u0000*\\u0000conversions\\\";O:52:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\ConversionCollection\\\":2:{s:8:\\\"\\u0000*\\u0000items\\\";a:1:{i:0;O:42:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Conversion\\\":12:{s:12:\\\"\\u0000*\\u0000fileNamer\\\";O:54:\\\"Spatie\\\\MediaLibrary\\\\Support\\\\FileNamer\\\\DefaultFileNamer\\\":0:{}s:28:\\\"\\u0000*\\u0000extractVideoFrameAtSecond\\\";d:0;s:16:\\\"\\u0000*\\u0000manipulations\\\";O:45:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Manipulations\\\":1:{s:16:\\\"\\u0000*\\u0000manipulations\\\";a:4:{s:8:\\\"optimize\\\";a:1:{i:0;O:36:\\\"Spatie\\\\ImageOptimizer\\\\OptimizerChain\\\":3:{s:13:\\\"\\u0000*\\u0000optimizers\\\";a:7:{i:0;O:42:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Jpegoptim\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m85\\\";i:1;s:7:\\\"--force\\\";i:2;s:11:\\\"--strip-all\\\";i:3;s:17:\\\"--all-progressive\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:9:\\\"jpegoptim\\\";}i:1;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Pngquant\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:7:\\\"--force\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"pngquant\\\";}i:2;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Optipng\\\":5:{s:7:\\\"options\\\";a:3:{i:0;s:3:\\\"-i0\\\";i:1;s:3:\\\"-o2\\\";i:2;s:6:\\\"-quiet\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"optipng\\\";}i:3;O:37:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Svgo\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:20:\\\"--disable=cleanupIDs\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:4:\\\"svgo\\\";}i:4;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Gifsicle\\\":5:{s:7:\\\"options\\\";a:2:{i:0;s:2:\\\"-b\\\";i:1;s:3:\\\"-O3\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"gifsicle\\\";}i:5;O:38:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Cwebp\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m 6\\\";i:1;s:8:\\\"-pass 10\\\";i:2;s:3:\\\"-mt\\\";i:3;s:5:\\\"-q 90\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:5:\\\"cwebp\\\";}i:6;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Avifenc\\\":6:{s:7:\\\"options\\\";a:8:{i:0;s:14:\\\"-a cq-level=23\\\";i:1;s:6:\\\"-j all\\\";i:2;s:7:\\\"--min 0\\\";i:3;s:8:\\\"--max 63\\\";i:4;s:12:\\\"--minalpha 0\\\";i:5;s:13:\\\"--maxalpha 63\\\";i:6;s:14:\\\"-a end-usage=q\\\";i:7;s:12:\\\"-a tune=ssim\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"avifenc\\\";s:16:\\\"decodeBinaryName\\\";s:7:\\\"avifdec\\\";}}s:9:\\\"\\u0000*\\u0000logger\\\";O:33:\\\"Spatie\\\\ImageOptimizer\\\\DummyLogger\\\":0:{}s:10:\\\"\\u0000*\\u0000timeout\\\";i:60;}}s:6:\\\"format\\\";a:1:{i:0;s:3:\\\"jpg\\\";}s:3:\\\"fit\\\";a:3:{i:0;E:30:\\\"Spatie\\\\Image\\\\Enums\\\\Fit:Contain\\\";i:1;i:300;i:2;i:300;}s:7:\\\"sharpen\\\";a:1:{i:0;i:10;}}}s:23:\\\"\\u0000*\\u0000performOnCollections\\\";a:0:{}s:17:\\\"\\u0000*\\u0000performOnQueue\\\";b:1;s:18:\\\"\\u0000*\\u0000performDeferred\\\";b:0;s:26:\\\"\\u0000*\\u0000keepOriginalImageFormat\\\";b:0;s:27:\\\"\\u0000*\\u0000generateResponsiveImages\\\";b:0;s:18:\\\"\\u0000*\\u0000widthCalculator\\\";N;s:24:\\\"\\u0000*\\u0000loadingAttributeValue\\\";N;s:16:\\\"\\u0000*\\u0000pdfPageNumber\\\";i:1;s:7:\\\"\\u0000*\\u0000name\\\";s:5:\\\"thumb\\\";}}s:28:\\\"\\u0000*\\u0000escapeWhenCastingToString\\\";b:0;}s:8:\\\"\\u0000*\\u0000media\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:49:\\\"Spatie\\\\MediaLibrary\\\\MediaCollections\\\\Models\\\\Media\\\";s:2:\\\"id\\\";i:2;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:14:\\\"\\u0000*\\u0000onlyMissing\\\";b:0;s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:0:\\\"\\\";s:11:\\\"afterCommit\\\";b:1;}\"}}', 0, NULL, 1779392362, 1779392362),
(9, 'default', '{\"uuid\":\"a8006210-1c63-4a37-a44b-ce55a1bddc24\",\"displayName\":\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\",\"command\":\"O:58:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\\\":6:{s:14:\\\"\\u0000*\\u0000conversions\\\";O:52:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\ConversionCollection\\\":2:{s:8:\\\"\\u0000*\\u0000items\\\";a:2:{i:0;O:42:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Conversion\\\":12:{s:12:\\\"\\u0000*\\u0000fileNamer\\\";O:54:\\\"Spatie\\\\MediaLibrary\\\\Support\\\\FileNamer\\\\DefaultFileNamer\\\":0:{}s:28:\\\"\\u0000*\\u0000extractVideoFrameAtSecond\\\";d:0;s:16:\\\"\\u0000*\\u0000manipulations\\\";O:45:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Manipulations\\\":1:{s:16:\\\"\\u0000*\\u0000manipulations\\\";a:4:{s:8:\\\"optimize\\\";a:1:{i:0;O:36:\\\"Spatie\\\\ImageOptimizer\\\\OptimizerChain\\\":3:{s:13:\\\"\\u0000*\\u0000optimizers\\\";a:7:{i:0;O:42:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Jpegoptim\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m85\\\";i:1;s:7:\\\"--force\\\";i:2;s:11:\\\"--strip-all\\\";i:3;s:17:\\\"--all-progressive\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:9:\\\"jpegoptim\\\";}i:1;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Pngquant\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:7:\\\"--force\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"pngquant\\\";}i:2;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Optipng\\\":5:{s:7:\\\"options\\\";a:3:{i:0;s:3:\\\"-i0\\\";i:1;s:3:\\\"-o2\\\";i:2;s:6:\\\"-quiet\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"optipng\\\";}i:3;O:37:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Svgo\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:20:\\\"--disable=cleanupIDs\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:4:\\\"svgo\\\";}i:4;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Gifsicle\\\":5:{s:7:\\\"options\\\";a:2:{i:0;s:2:\\\"-b\\\";i:1;s:3:\\\"-O3\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"gifsicle\\\";}i:5;O:38:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Cwebp\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m 6\\\";i:1;s:8:\\\"-pass 10\\\";i:2;s:3:\\\"-mt\\\";i:3;s:5:\\\"-q 90\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:5:\\\"cwebp\\\";}i:6;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Avifenc\\\":6:{s:7:\\\"options\\\";a:8:{i:0;s:14:\\\"-a cq-level=23\\\";i:1;s:6:\\\"-j all\\\";i:2;s:7:\\\"--min 0\\\";i:3;s:8:\\\"--max 63\\\";i:4;s:12:\\\"--minalpha 0\\\";i:5;s:13:\\\"--maxalpha 63\\\";i:6;s:14:\\\"-a end-usage=q\\\";i:7;s:12:\\\"-a tune=ssim\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"avifenc\\\";s:16:\\\"decodeBinaryName\\\";s:7:\\\"avifdec\\\";}}s:9:\\\"\\u0000*\\u0000logger\\\";O:33:\\\"Spatie\\\\ImageOptimizer\\\\DummyLogger\\\":0:{}s:10:\\\"\\u0000*\\u0000timeout\\\";i:60;}}s:6:\\\"format\\\";a:1:{i:0;s:3:\\\"jpg\\\";}s:3:\\\"fit\\\";a:3:{i:0;E:30:\\\"Spatie\\\\Image\\\\Enums\\\\Fit:Contain\\\";i:1;i:300;i:2;i:300;}s:7:\\\"sharpen\\\";a:1:{i:0;i:10;}}}s:23:\\\"\\u0000*\\u0000performOnCollections\\\";a:0:{}s:17:\\\"\\u0000*\\u0000performOnQueue\\\";b:1;s:18:\\\"\\u0000*\\u0000performDeferred\\\";b:0;s:26:\\\"\\u0000*\\u0000keepOriginalImageFormat\\\";b:0;s:27:\\\"\\u0000*\\u0000generateResponsiveImages\\\";b:0;s:18:\\\"\\u0000*\\u0000widthCalculator\\\";N;s:24:\\\"\\u0000*\\u0000loadingAttributeValue\\\";N;s:16:\\\"\\u0000*\\u0000pdfPageNumber\\\";i:1;s:7:\\\"\\u0000*\\u0000name\\\";s:5:\\\"thumb\\\";}i:1;O:42:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Conversion\\\":12:{s:12:\\\"\\u0000*\\u0000fileNamer\\\";O:54:\\\"Spatie\\\\MediaLibrary\\\\Support\\\\FileNamer\\\\DefaultFileNamer\\\":0:{}s:28:\\\"\\u0000*\\u0000extractVideoFrameAtSecond\\\";d:0;s:16:\\\"\\u0000*\\u0000manipulations\\\";O:45:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Manipulations\\\":1:{s:16:\\\"\\u0000*\\u0000manipulations\\\";a:3:{s:8:\\\"optimize\\\";a:1:{i:0;O:36:\\\"Spatie\\\\ImageOptimizer\\\\OptimizerChain\\\":3:{s:13:\\\"\\u0000*\\u0000optimizers\\\";a:7:{i:0;O:42:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Jpegoptim\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m85\\\";i:1;s:7:\\\"--force\\\";i:2;s:11:\\\"--strip-all\\\";i:3;s:17:\\\"--all-progressive\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:9:\\\"jpegoptim\\\";}i:1;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Pngquant\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:7:\\\"--force\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"pngquant\\\";}i:2;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Optipng\\\":5:{s:7:\\\"options\\\";a:3:{i:0;s:3:\\\"-i0\\\";i:1;s:3:\\\"-o2\\\";i:2;s:6:\\\"-quiet\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"optipng\\\";}i:3;O:37:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Svgo\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:20:\\\"--disable=cleanupIDs\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:4:\\\"svgo\\\";}i:4;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Gifsicle\\\":5:{s:7:\\\"options\\\";a:2:{i:0;s:2:\\\"-b\\\";i:1;s:3:\\\"-O3\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"gifsicle\\\";}i:5;O:38:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Cwebp\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m 6\\\";i:1;s:8:\\\"-pass 10\\\";i:2;s:3:\\\"-mt\\\";i:3;s:5:\\\"-q 90\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:5:\\\"cwebp\\\";}i:6;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Avifenc\\\":6:{s:7:\\\"options\\\";a:8:{i:0;s:14:\\\"-a cq-level=23\\\";i:1;s:6:\\\"-j all\\\";i:2;s:7:\\\"--min 0\\\";i:3;s:8:\\\"--max 63\\\";i:4;s:12:\\\"--minalpha 0\\\";i:5;s:13:\\\"--maxalpha 63\\\";i:6;s:14:\\\"-a end-usage=q\\\";i:7;s:12:\\\"-a tune=ssim\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"avifenc\\\";s:16:\\\"decodeBinaryName\\\";s:7:\\\"avifdec\\\";}}s:9:\\\"\\u0000*\\u0000logger\\\";O:33:\\\"Spatie\\\\ImageOptimizer\\\\DummyLogger\\\":0:{}s:10:\\\"\\u0000*\\u0000timeout\\\";i:60;}}s:6:\\\"format\\\";a:1:{i:0;s:3:\\\"jpg\\\";}s:3:\\\"fit\\\";a:3:{i:0;E:26:\\\"Spatie\\\\Image\\\\Enums\\\\Fit:Max\\\";i:1;i:800;i:2;i:600;}}}s:23:\\\"\\u0000*\\u0000performOnCollections\\\";a:0:{}s:17:\\\"\\u0000*\\u0000performOnQueue\\\";b:1;s:18:\\\"\\u0000*\\u0000performDeferred\\\";b:0;s:26:\\\"\\u0000*\\u0000keepOriginalImageFormat\\\";b:0;s:27:\\\"\\u0000*\\u0000generateResponsiveImages\\\";b:0;s:18:\\\"\\u0000*\\u0000widthCalculator\\\";N;s:24:\\\"\\u0000*\\u0000loadingAttributeValue\\\";N;s:16:\\\"\\u0000*\\u0000pdfPageNumber\\\";i:1;s:7:\\\"\\u0000*\\u0000name\\\";s:6:\\\"medium\\\";}}s:28:\\\"\\u0000*\\u0000escapeWhenCastingToString\\\";b:0;}s:8:\\\"\\u0000*\\u0000media\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:49:\\\"Spatie\\\\MediaLibrary\\\\MediaCollections\\\\Models\\\\Media\\\";s:2:\\\"id\\\";i:4;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:14:\\\"\\u0000*\\u0000onlyMissing\\\";b:0;s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:0:\\\"\\\";s:11:\\\"afterCommit\\\";b:1;}\"}}', 0, NULL, 1779392362, 1779392362),
(10, 'default', '{\"uuid\":\"762aee45-02ee-4a01-959d-20dd6416381e\",\"displayName\":\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\",\"command\":\"O:58:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\\\":6:{s:14:\\\"\\u0000*\\u0000conversions\\\";O:52:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\ConversionCollection\\\":2:{s:8:\\\"\\u0000*\\u0000items\\\";a:2:{i:0;O:42:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Conversion\\\":12:{s:12:\\\"\\u0000*\\u0000fileNamer\\\";O:54:\\\"Spatie\\\\MediaLibrary\\\\Support\\\\FileNamer\\\\DefaultFileNamer\\\":0:{}s:28:\\\"\\u0000*\\u0000extractVideoFrameAtSecond\\\";d:0;s:16:\\\"\\u0000*\\u0000manipulations\\\";O:45:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Manipulations\\\":1:{s:16:\\\"\\u0000*\\u0000manipulations\\\";a:4:{s:8:\\\"optimize\\\";a:1:{i:0;O:36:\\\"Spatie\\\\ImageOptimizer\\\\OptimizerChain\\\":3:{s:13:\\\"\\u0000*\\u0000optimizers\\\";a:7:{i:0;O:42:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Jpegoptim\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m85\\\";i:1;s:7:\\\"--force\\\";i:2;s:11:\\\"--strip-all\\\";i:3;s:17:\\\"--all-progressive\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:9:\\\"jpegoptim\\\";}i:1;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Pngquant\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:7:\\\"--force\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"pngquant\\\";}i:2;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Optipng\\\":5:{s:7:\\\"options\\\";a:3:{i:0;s:3:\\\"-i0\\\";i:1;s:3:\\\"-o2\\\";i:2;s:6:\\\"-quiet\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"optipng\\\";}i:3;O:37:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Svgo\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:20:\\\"--disable=cleanupIDs\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:4:\\\"svgo\\\";}i:4;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Gifsicle\\\":5:{s:7:\\\"options\\\";a:2:{i:0;s:2:\\\"-b\\\";i:1;s:3:\\\"-O3\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"gifsicle\\\";}i:5;O:38:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Cwebp\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m 6\\\";i:1;s:8:\\\"-pass 10\\\";i:2;s:3:\\\"-mt\\\";i:3;s:5:\\\"-q 90\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:5:\\\"cwebp\\\";}i:6;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Avifenc\\\":6:{s:7:\\\"options\\\";a:8:{i:0;s:14:\\\"-a cq-level=23\\\";i:1;s:6:\\\"-j all\\\";i:2;s:7:\\\"--min 0\\\";i:3;s:8:\\\"--max 63\\\";i:4;s:12:\\\"--minalpha 0\\\";i:5;s:13:\\\"--maxalpha 63\\\";i:6;s:14:\\\"-a end-usage=q\\\";i:7;s:12:\\\"-a tune=ssim\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"avifenc\\\";s:16:\\\"decodeBinaryName\\\";s:7:\\\"avifdec\\\";}}s:9:\\\"\\u0000*\\u0000logger\\\";O:33:\\\"Spatie\\\\ImageOptimizer\\\\DummyLogger\\\":0:{}s:10:\\\"\\u0000*\\u0000timeout\\\";i:60;}}s:6:\\\"format\\\";a:1:{i:0;s:3:\\\"jpg\\\";}s:3:\\\"fit\\\";a:3:{i:0;E:30:\\\"Spatie\\\\Image\\\\Enums\\\\Fit:Contain\\\";i:1;i:300;i:2;i:300;}s:7:\\\"sharpen\\\";a:1:{i:0;i:10;}}}s:23:\\\"\\u0000*\\u0000performOnCollections\\\";a:0:{}s:17:\\\"\\u0000*\\u0000performOnQueue\\\";b:1;s:18:\\\"\\u0000*\\u0000performDeferred\\\";b:0;s:26:\\\"\\u0000*\\u0000keepOriginalImageFormat\\\";b:0;s:27:\\\"\\u0000*\\u0000generateResponsiveImages\\\";b:0;s:18:\\\"\\u0000*\\u0000widthCalculator\\\";N;s:24:\\\"\\u0000*\\u0000loadingAttributeValue\\\";N;s:16:\\\"\\u0000*\\u0000pdfPageNumber\\\";i:1;s:7:\\\"\\u0000*\\u0000name\\\";s:5:\\\"thumb\\\";}i:1;O:42:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Conversion\\\":12:{s:12:\\\"\\u0000*\\u0000fileNamer\\\";O:54:\\\"Spatie\\\\MediaLibrary\\\\Support\\\\FileNamer\\\\DefaultFileNamer\\\":0:{}s:28:\\\"\\u0000*\\u0000extractVideoFrameAtSecond\\\";d:0;s:16:\\\"\\u0000*\\u0000manipulations\\\";O:45:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Manipulations\\\":1:{s:16:\\\"\\u0000*\\u0000manipulations\\\";a:3:{s:8:\\\"optimize\\\";a:1:{i:0;O:36:\\\"Spatie\\\\ImageOptimizer\\\\OptimizerChain\\\":3:{s:13:\\\"\\u0000*\\u0000optimizers\\\";a:7:{i:0;O:42:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Jpegoptim\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m85\\\";i:1;s:7:\\\"--force\\\";i:2;s:11:\\\"--strip-all\\\";i:3;s:17:\\\"--all-progressive\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:9:\\\"jpegoptim\\\";}i:1;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Pngquant\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:7:\\\"--force\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"pngquant\\\";}i:2;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Optipng\\\":5:{s:7:\\\"options\\\";a:3:{i:0;s:3:\\\"-i0\\\";i:1;s:3:\\\"-o2\\\";i:2;s:6:\\\"-quiet\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"optipng\\\";}i:3;O:37:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Svgo\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:20:\\\"--disable=cleanupIDs\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:4:\\\"svgo\\\";}i:4;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Gifsicle\\\":5:{s:7:\\\"options\\\";a:2:{i:0;s:2:\\\"-b\\\";i:1;s:3:\\\"-O3\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"gifsicle\\\";}i:5;O:38:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Cwebp\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m 6\\\";i:1;s:8:\\\"-pass 10\\\";i:2;s:3:\\\"-mt\\\";i:3;s:5:\\\"-q 90\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:5:\\\"cwebp\\\";}i:6;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Avifenc\\\":6:{s:7:\\\"options\\\";a:8:{i:0;s:14:\\\"-a cq-level=23\\\";i:1;s:6:\\\"-j all\\\";i:2;s:7:\\\"--min 0\\\";i:3;s:8:\\\"--max 63\\\";i:4;s:12:\\\"--minalpha 0\\\";i:5;s:13:\\\"--maxalpha 63\\\";i:6;s:14:\\\"-a end-usage=q\\\";i:7;s:12:\\\"-a tune=ssim\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"avifenc\\\";s:16:\\\"decodeBinaryName\\\";s:7:\\\"avifdec\\\";}}s:9:\\\"\\u0000*\\u0000logger\\\";O:33:\\\"Spatie\\\\ImageOptimizer\\\\DummyLogger\\\":0:{}s:10:\\\"\\u0000*\\u0000timeout\\\";i:60;}}s:6:\\\"format\\\";a:1:{i:0;s:3:\\\"jpg\\\";}s:3:\\\"fit\\\";a:3:{i:0;E:26:\\\"Spatie\\\\Image\\\\Enums\\\\Fit:Max\\\";i:1;i:800;i:2;i:600;}}}s:23:\\\"\\u0000*\\u0000performOnCollections\\\";a:0:{}s:17:\\\"\\u0000*\\u0000performOnQueue\\\";b:1;s:18:\\\"\\u0000*\\u0000performDeferred\\\";b:0;s:26:\\\"\\u0000*\\u0000keepOriginalImageFormat\\\";b:0;s:27:\\\"\\u0000*\\u0000generateResponsiveImages\\\";b:0;s:18:\\\"\\u0000*\\u0000widthCalculator\\\";N;s:24:\\\"\\u0000*\\u0000loadingAttributeValue\\\";N;s:16:\\\"\\u0000*\\u0000pdfPageNumber\\\";i:1;s:7:\\\"\\u0000*\\u0000name\\\";s:6:\\\"medium\\\";}}s:28:\\\"\\u0000*\\u0000escapeWhenCastingToString\\\";b:0;}s:8:\\\"\\u0000*\\u0000media\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:49:\\\"Spatie\\\\MediaLibrary\\\\MediaCollections\\\\Models\\\\Media\\\";s:2:\\\"id\\\";i:5;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:14:\\\"\\u0000*\\u0000onlyMissing\\\";b:0;s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:0:\\\"\\\";s:11:\\\"afterCommit\\\";b:1;}\"}}', 0, NULL, 1779392362, 1779392362),
(11, 'default', '{\"uuid\":\"4d564e98-d3a0-41c9-bafa-6d7510fdae95\",\"displayName\":\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\",\"command\":\"O:58:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\\\":6:{s:14:\\\"\\u0000*\\u0000conversions\\\";O:52:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\ConversionCollection\\\":2:{s:8:\\\"\\u0000*\\u0000items\\\";a:2:{i:0;O:42:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Conversion\\\":12:{s:12:\\\"\\u0000*\\u0000fileNamer\\\";O:54:\\\"Spatie\\\\MediaLibrary\\\\Support\\\\FileNamer\\\\DefaultFileNamer\\\":0:{}s:28:\\\"\\u0000*\\u0000extractVideoFrameAtSecond\\\";d:0;s:16:\\\"\\u0000*\\u0000manipulations\\\";O:45:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Manipulations\\\":1:{s:16:\\\"\\u0000*\\u0000manipulations\\\";a:4:{s:8:\\\"optimize\\\";a:1:{i:0;O:36:\\\"Spatie\\\\ImageOptimizer\\\\OptimizerChain\\\":3:{s:13:\\\"\\u0000*\\u0000optimizers\\\";a:7:{i:0;O:42:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Jpegoptim\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m85\\\";i:1;s:7:\\\"--force\\\";i:2;s:11:\\\"--strip-all\\\";i:3;s:17:\\\"--all-progressive\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:9:\\\"jpegoptim\\\";}i:1;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Pngquant\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:7:\\\"--force\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"pngquant\\\";}i:2;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Optipng\\\":5:{s:7:\\\"options\\\";a:3:{i:0;s:3:\\\"-i0\\\";i:1;s:3:\\\"-o2\\\";i:2;s:6:\\\"-quiet\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"optipng\\\";}i:3;O:37:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Svgo\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:20:\\\"--disable=cleanupIDs\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:4:\\\"svgo\\\";}i:4;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Gifsicle\\\":5:{s:7:\\\"options\\\";a:2:{i:0;s:2:\\\"-b\\\";i:1;s:3:\\\"-O3\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"gifsicle\\\";}i:5;O:38:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Cwebp\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m 6\\\";i:1;s:8:\\\"-pass 10\\\";i:2;s:3:\\\"-mt\\\";i:3;s:5:\\\"-q 90\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:5:\\\"cwebp\\\";}i:6;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Avifenc\\\":6:{s:7:\\\"options\\\";a:8:{i:0;s:14:\\\"-a cq-level=23\\\";i:1;s:6:\\\"-j all\\\";i:2;s:7:\\\"--min 0\\\";i:3;s:8:\\\"--max 63\\\";i:4;s:12:\\\"--minalpha 0\\\";i:5;s:13:\\\"--maxalpha 63\\\";i:6;s:14:\\\"-a end-usage=q\\\";i:7;s:12:\\\"-a tune=ssim\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"avifenc\\\";s:16:\\\"decodeBinaryName\\\";s:7:\\\"avifdec\\\";}}s:9:\\\"\\u0000*\\u0000logger\\\";O:33:\\\"Spatie\\\\ImageOptimizer\\\\DummyLogger\\\":0:{}s:10:\\\"\\u0000*\\u0000timeout\\\";i:60;}}s:6:\\\"format\\\";a:1:{i:0;s:3:\\\"jpg\\\";}s:3:\\\"fit\\\";a:3:{i:0;E:30:\\\"Spatie\\\\Image\\\\Enums\\\\Fit:Contain\\\";i:1;i:300;i:2;i:300;}s:7:\\\"sharpen\\\";a:1:{i:0;i:10;}}}s:23:\\\"\\u0000*\\u0000performOnCollections\\\";a:0:{}s:17:\\\"\\u0000*\\u0000performOnQueue\\\";b:1;s:18:\\\"\\u0000*\\u0000performDeferred\\\";b:0;s:26:\\\"\\u0000*\\u0000keepOriginalImageFormat\\\";b:0;s:27:\\\"\\u0000*\\u0000generateResponsiveImages\\\";b:0;s:18:\\\"\\u0000*\\u0000widthCalculator\\\";N;s:24:\\\"\\u0000*\\u0000loadingAttributeValue\\\";N;s:16:\\\"\\u0000*\\u0000pdfPageNumber\\\";i:1;s:7:\\\"\\u0000*\\u0000name\\\";s:5:\\\"thumb\\\";}i:1;O:42:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Conversion\\\":12:{s:12:\\\"\\u0000*\\u0000fileNamer\\\";O:54:\\\"Spatie\\\\MediaLibrary\\\\Support\\\\FileNamer\\\\DefaultFileNamer\\\":0:{}s:28:\\\"\\u0000*\\u0000extractVideoFrameAtSecond\\\";d:0;s:16:\\\"\\u0000*\\u0000manipulations\\\";O:45:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Manipulations\\\":1:{s:16:\\\"\\u0000*\\u0000manipulations\\\";a:3:{s:8:\\\"optimize\\\";a:1:{i:0;O:36:\\\"Spatie\\\\ImageOptimizer\\\\OptimizerChain\\\":3:{s:13:\\\"\\u0000*\\u0000optimizers\\\";a:7:{i:0;O:42:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Jpegoptim\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m85\\\";i:1;s:7:\\\"--force\\\";i:2;s:11:\\\"--strip-all\\\";i:3;s:17:\\\"--all-progressive\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:9:\\\"jpegoptim\\\";}i:1;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Pngquant\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:7:\\\"--force\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"pngquant\\\";}i:2;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Optipng\\\":5:{s:7:\\\"options\\\";a:3:{i:0;s:3:\\\"-i0\\\";i:1;s:3:\\\"-o2\\\";i:2;s:6:\\\"-quiet\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"optipng\\\";}i:3;O:37:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Svgo\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:20:\\\"--disable=cleanupIDs\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:4:\\\"svgo\\\";}i:4;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Gifsicle\\\":5:{s:7:\\\"options\\\";a:2:{i:0;s:2:\\\"-b\\\";i:1;s:3:\\\"-O3\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"gifsicle\\\";}i:5;O:38:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Cwebp\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m 6\\\";i:1;s:8:\\\"-pass 10\\\";i:2;s:3:\\\"-mt\\\";i:3;s:5:\\\"-q 90\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:5:\\\"cwebp\\\";}i:6;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Avifenc\\\":6:{s:7:\\\"options\\\";a:8:{i:0;s:14:\\\"-a cq-level=23\\\";i:1;s:6:\\\"-j all\\\";i:2;s:7:\\\"--min 0\\\";i:3;s:8:\\\"--max 63\\\";i:4;s:12:\\\"--minalpha 0\\\";i:5;s:13:\\\"--maxalpha 63\\\";i:6;s:14:\\\"-a end-usage=q\\\";i:7;s:12:\\\"-a tune=ssim\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"avifenc\\\";s:16:\\\"decodeBinaryName\\\";s:7:\\\"avifdec\\\";}}s:9:\\\"\\u0000*\\u0000logger\\\";O:33:\\\"Spatie\\\\ImageOptimizer\\\\DummyLogger\\\":0:{}s:10:\\\"\\u0000*\\u0000timeout\\\";i:60;}}s:6:\\\"format\\\";a:1:{i:0;s:3:\\\"jpg\\\";}s:3:\\\"fit\\\";a:3:{i:0;E:26:\\\"Spatie\\\\Image\\\\Enums\\\\Fit:Max\\\";i:1;i:800;i:2;i:600;}}}s:23:\\\"\\u0000*\\u0000performOnCollections\\\";a:0:{}s:17:\\\"\\u0000*\\u0000performOnQueue\\\";b:1;s:18:\\\"\\u0000*\\u0000performDeferred\\\";b:0;s:26:\\\"\\u0000*\\u0000keepOriginalImageFormat\\\";b:0;s:27:\\\"\\u0000*\\u0000generateResponsiveImages\\\";b:0;s:18:\\\"\\u0000*\\u0000widthCalculator\\\";N;s:24:\\\"\\u0000*\\u0000loadingAttributeValue\\\";N;s:16:\\\"\\u0000*\\u0000pdfPageNumber\\\";i:1;s:7:\\\"\\u0000*\\u0000name\\\";s:6:\\\"medium\\\";}}s:28:\\\"\\u0000*\\u0000escapeWhenCastingToString\\\";b:0;}s:8:\\\"\\u0000*\\u0000media\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:49:\\\"Spatie\\\\MediaLibrary\\\\MediaCollections\\\\Models\\\\Media\\\";s:2:\\\"id\\\";i:6;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:14:\\\"\\u0000*\\u0000onlyMissing\\\";b:0;s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:0:\\\"\\\";s:11:\\\"afterCommit\\\";b:1;}\"}}', 0, NULL, 1779392362, 1779392362),
(12, 'default', '{\"uuid\":\"3a068921-9f3e-45d8-b19a-132a219e4641\",\"displayName\":\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\",\"command\":\"O:58:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\\\":6:{s:14:\\\"\\u0000*\\u0000conversions\\\";O:52:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\ConversionCollection\\\":2:{s:8:\\\"\\u0000*\\u0000items\\\";a:2:{i:0;O:42:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Conversion\\\":12:{s:12:\\\"\\u0000*\\u0000fileNamer\\\";O:54:\\\"Spatie\\\\MediaLibrary\\\\Support\\\\FileNamer\\\\DefaultFileNamer\\\":0:{}s:28:\\\"\\u0000*\\u0000extractVideoFrameAtSecond\\\";d:0;s:16:\\\"\\u0000*\\u0000manipulations\\\";O:45:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Manipulations\\\":1:{s:16:\\\"\\u0000*\\u0000manipulations\\\";a:4:{s:8:\\\"optimize\\\";a:1:{i:0;O:36:\\\"Spatie\\\\ImageOptimizer\\\\OptimizerChain\\\":3:{s:13:\\\"\\u0000*\\u0000optimizers\\\";a:7:{i:0;O:42:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Jpegoptim\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m85\\\";i:1;s:7:\\\"--force\\\";i:2;s:11:\\\"--strip-all\\\";i:3;s:17:\\\"--all-progressive\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:9:\\\"jpegoptim\\\";}i:1;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Pngquant\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:7:\\\"--force\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"pngquant\\\";}i:2;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Optipng\\\":5:{s:7:\\\"options\\\";a:3:{i:0;s:3:\\\"-i0\\\";i:1;s:3:\\\"-o2\\\";i:2;s:6:\\\"-quiet\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"optipng\\\";}i:3;O:37:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Svgo\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:20:\\\"--disable=cleanupIDs\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:4:\\\"svgo\\\";}i:4;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Gifsicle\\\":5:{s:7:\\\"options\\\";a:2:{i:0;s:2:\\\"-b\\\";i:1;s:3:\\\"-O3\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"gifsicle\\\";}i:5;O:38:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Cwebp\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m 6\\\";i:1;s:8:\\\"-pass 10\\\";i:2;s:3:\\\"-mt\\\";i:3;s:5:\\\"-q 90\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:5:\\\"cwebp\\\";}i:6;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Avifenc\\\":6:{s:7:\\\"options\\\";a:8:{i:0;s:14:\\\"-a cq-level=23\\\";i:1;s:6:\\\"-j all\\\";i:2;s:7:\\\"--min 0\\\";i:3;s:8:\\\"--max 63\\\";i:4;s:12:\\\"--minalpha 0\\\";i:5;s:13:\\\"--maxalpha 63\\\";i:6;s:14:\\\"-a end-usage=q\\\";i:7;s:12:\\\"-a tune=ssim\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"avifenc\\\";s:16:\\\"decodeBinaryName\\\";s:7:\\\"avifdec\\\";}}s:9:\\\"\\u0000*\\u0000logger\\\";O:33:\\\"Spatie\\\\ImageOptimizer\\\\DummyLogger\\\":0:{}s:10:\\\"\\u0000*\\u0000timeout\\\";i:60;}}s:6:\\\"format\\\";a:1:{i:0;s:3:\\\"jpg\\\";}s:3:\\\"fit\\\";a:3:{i:0;E:30:\\\"Spatie\\\\Image\\\\Enums\\\\Fit:Contain\\\";i:1;i:300;i:2;i:300;}s:7:\\\"sharpen\\\";a:1:{i:0;i:10;}}}s:23:\\\"\\u0000*\\u0000performOnCollections\\\";a:0:{}s:17:\\\"\\u0000*\\u0000performOnQueue\\\";b:1;s:18:\\\"\\u0000*\\u0000performDeferred\\\";b:0;s:26:\\\"\\u0000*\\u0000keepOriginalImageFormat\\\";b:0;s:27:\\\"\\u0000*\\u0000generateResponsiveImages\\\";b:0;s:18:\\\"\\u0000*\\u0000widthCalculator\\\";N;s:24:\\\"\\u0000*\\u0000loadingAttributeValue\\\";N;s:16:\\\"\\u0000*\\u0000pdfPageNumber\\\";i:1;s:7:\\\"\\u0000*\\u0000name\\\";s:5:\\\"thumb\\\";}i:1;O:42:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Conversion\\\":12:{s:12:\\\"\\u0000*\\u0000fileNamer\\\";O:54:\\\"Spatie\\\\MediaLibrary\\\\Support\\\\FileNamer\\\\DefaultFileNamer\\\":0:{}s:28:\\\"\\u0000*\\u0000extractVideoFrameAtSecond\\\";d:0;s:16:\\\"\\u0000*\\u0000manipulations\\\";O:45:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Manipulations\\\":1:{s:16:\\\"\\u0000*\\u0000manipulations\\\";a:3:{s:8:\\\"optimize\\\";a:1:{i:0;O:36:\\\"Spatie\\\\ImageOptimizer\\\\OptimizerChain\\\":3:{s:13:\\\"\\u0000*\\u0000optimizers\\\";a:7:{i:0;O:42:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Jpegoptim\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m85\\\";i:1;s:7:\\\"--force\\\";i:2;s:11:\\\"--strip-all\\\";i:3;s:17:\\\"--all-progressive\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:9:\\\"jpegoptim\\\";}i:1;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Pngquant\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:7:\\\"--force\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"pngquant\\\";}i:2;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Optipng\\\":5:{s:7:\\\"options\\\";a:3:{i:0;s:3:\\\"-i0\\\";i:1;s:3:\\\"-o2\\\";i:2;s:6:\\\"-quiet\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"optipng\\\";}i:3;O:37:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Svgo\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:20:\\\"--disable=cleanupIDs\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:4:\\\"svgo\\\";}i:4;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Gifsicle\\\":5:{s:7:\\\"options\\\";a:2:{i:0;s:2:\\\"-b\\\";i:1;s:3:\\\"-O3\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"gifsicle\\\";}i:5;O:38:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Cwebp\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m 6\\\";i:1;s:8:\\\"-pass 10\\\";i:2;s:3:\\\"-mt\\\";i:3;s:5:\\\"-q 90\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:5:\\\"cwebp\\\";}i:6;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Avifenc\\\":6:{s:7:\\\"options\\\";a:8:{i:0;s:14:\\\"-a cq-level=23\\\";i:1;s:6:\\\"-j all\\\";i:2;s:7:\\\"--min 0\\\";i:3;s:8:\\\"--max 63\\\";i:4;s:12:\\\"--minalpha 0\\\";i:5;s:13:\\\"--maxalpha 63\\\";i:6;s:14:\\\"-a end-usage=q\\\";i:7;s:12:\\\"-a tune=ssim\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"avifenc\\\";s:16:\\\"decodeBinaryName\\\";s:7:\\\"avifdec\\\";}}s:9:\\\"\\u0000*\\u0000logger\\\";O:33:\\\"Spatie\\\\ImageOptimizer\\\\DummyLogger\\\":0:{}s:10:\\\"\\u0000*\\u0000timeout\\\";i:60;}}s:6:\\\"format\\\";a:1:{i:0;s:3:\\\"jpg\\\";}s:3:\\\"fit\\\";a:3:{i:0;E:26:\\\"Spatie\\\\Image\\\\Enums\\\\Fit:Max\\\";i:1;i:800;i:2;i:600;}}}s:23:\\\"\\u0000*\\u0000performOnCollections\\\";a:0:{}s:17:\\\"\\u0000*\\u0000performOnQueue\\\";b:1;s:18:\\\"\\u0000*\\u0000performDeferred\\\";b:0;s:26:\\\"\\u0000*\\u0000keepOriginalImageFormat\\\";b:0;s:27:\\\"\\u0000*\\u0000generateResponsiveImages\\\";b:0;s:18:\\\"\\u0000*\\u0000widthCalculator\\\";N;s:24:\\\"\\u0000*\\u0000loadingAttributeValue\\\";N;s:16:\\\"\\u0000*\\u0000pdfPageNumber\\\";i:1;s:7:\\\"\\u0000*\\u0000name\\\";s:6:\\\"medium\\\";}}s:28:\\\"\\u0000*\\u0000escapeWhenCastingToString\\\";b:0;}s:8:\\\"\\u0000*\\u0000media\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:49:\\\"Spatie\\\\MediaLibrary\\\\MediaCollections\\\\Models\\\\Media\\\";s:2:\\\"id\\\";i:7;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:14:\\\"\\u0000*\\u0000onlyMissing\\\";b:0;s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:0:\\\"\\\";s:11:\\\"afterCommit\\\";b:1;}\"}}', 0, NULL, 1779465011, 1779465011),
(13, 'default', '{\"uuid\":\"41af7208-71a1-4ae2-a882-34274d19b68e\",\"displayName\":\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\",\"command\":\"O:58:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\\\":6:{s:14:\\\"\\u0000*\\u0000conversions\\\";O:52:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\ConversionCollection\\\":2:{s:8:\\\"\\u0000*\\u0000items\\\";a:2:{i:0;O:42:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Conversion\\\":12:{s:12:\\\"\\u0000*\\u0000fileNamer\\\";O:54:\\\"Spatie\\\\MediaLibrary\\\\Support\\\\FileNamer\\\\DefaultFileNamer\\\":0:{}s:28:\\\"\\u0000*\\u0000extractVideoFrameAtSecond\\\";d:0;s:16:\\\"\\u0000*\\u0000manipulations\\\";O:45:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Manipulations\\\":1:{s:16:\\\"\\u0000*\\u0000manipulations\\\";a:4:{s:8:\\\"optimize\\\";a:1:{i:0;O:36:\\\"Spatie\\\\ImageOptimizer\\\\OptimizerChain\\\":3:{s:13:\\\"\\u0000*\\u0000optimizers\\\";a:7:{i:0;O:42:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Jpegoptim\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m85\\\";i:1;s:7:\\\"--force\\\";i:2;s:11:\\\"--strip-all\\\";i:3;s:17:\\\"--all-progressive\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:9:\\\"jpegoptim\\\";}i:1;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Pngquant\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:7:\\\"--force\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"pngquant\\\";}i:2;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Optipng\\\":5:{s:7:\\\"options\\\";a:3:{i:0;s:3:\\\"-i0\\\";i:1;s:3:\\\"-o2\\\";i:2;s:6:\\\"-quiet\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"optipng\\\";}i:3;O:37:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Svgo\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:20:\\\"--disable=cleanupIDs\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:4:\\\"svgo\\\";}i:4;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Gifsicle\\\":5:{s:7:\\\"options\\\";a:2:{i:0;s:2:\\\"-b\\\";i:1;s:3:\\\"-O3\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"gifsicle\\\";}i:5;O:38:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Cwebp\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m 6\\\";i:1;s:8:\\\"-pass 10\\\";i:2;s:3:\\\"-mt\\\";i:3;s:5:\\\"-q 90\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:5:\\\"cwebp\\\";}i:6;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Avifenc\\\":6:{s:7:\\\"options\\\";a:8:{i:0;s:14:\\\"-a cq-level=23\\\";i:1;s:6:\\\"-j all\\\";i:2;s:7:\\\"--min 0\\\";i:3;s:8:\\\"--max 63\\\";i:4;s:12:\\\"--minalpha 0\\\";i:5;s:13:\\\"--maxalpha 63\\\";i:6;s:14:\\\"-a end-usage=q\\\";i:7;s:12:\\\"-a tune=ssim\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"avifenc\\\";s:16:\\\"decodeBinaryName\\\";s:7:\\\"avifdec\\\";}}s:9:\\\"\\u0000*\\u0000logger\\\";O:33:\\\"Spatie\\\\ImageOptimizer\\\\DummyLogger\\\":0:{}s:10:\\\"\\u0000*\\u0000timeout\\\";i:60;}}s:6:\\\"format\\\";a:1:{i:0;s:3:\\\"jpg\\\";}s:3:\\\"fit\\\";a:3:{i:0;E:30:\\\"Spatie\\\\Image\\\\Enums\\\\Fit:Contain\\\";i:1;i:300;i:2;i:300;}s:7:\\\"sharpen\\\";a:1:{i:0;i:10;}}}s:23:\\\"\\u0000*\\u0000performOnCollections\\\";a:0:{}s:17:\\\"\\u0000*\\u0000performOnQueue\\\";b:1;s:18:\\\"\\u0000*\\u0000performDeferred\\\";b:0;s:26:\\\"\\u0000*\\u0000keepOriginalImageFormat\\\";b:0;s:27:\\\"\\u0000*\\u0000generateResponsiveImages\\\";b:0;s:18:\\\"\\u0000*\\u0000widthCalculator\\\";N;s:24:\\\"\\u0000*\\u0000loadingAttributeValue\\\";N;s:16:\\\"\\u0000*\\u0000pdfPageNumber\\\";i:1;s:7:\\\"\\u0000*\\u0000name\\\";s:5:\\\"thumb\\\";}i:1;O:42:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Conversion\\\":12:{s:12:\\\"\\u0000*\\u0000fileNamer\\\";O:54:\\\"Spatie\\\\MediaLibrary\\\\Support\\\\FileNamer\\\\DefaultFileNamer\\\":0:{}s:28:\\\"\\u0000*\\u0000extractVideoFrameAtSecond\\\";d:0;s:16:\\\"\\u0000*\\u0000manipulations\\\";O:45:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Manipulations\\\":1:{s:16:\\\"\\u0000*\\u0000manipulations\\\";a:3:{s:8:\\\"optimize\\\";a:1:{i:0;O:36:\\\"Spatie\\\\ImageOptimizer\\\\OptimizerChain\\\":3:{s:13:\\\"\\u0000*\\u0000optimizers\\\";a:7:{i:0;O:42:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Jpegoptim\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m85\\\";i:1;s:7:\\\"--force\\\";i:2;s:11:\\\"--strip-all\\\";i:3;s:17:\\\"--all-progressive\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:9:\\\"jpegoptim\\\";}i:1;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Pngquant\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:7:\\\"--force\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"pngquant\\\";}i:2;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Optipng\\\":5:{s:7:\\\"options\\\";a:3:{i:0;s:3:\\\"-i0\\\";i:1;s:3:\\\"-o2\\\";i:2;s:6:\\\"-quiet\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"optipng\\\";}i:3;O:37:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Svgo\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:20:\\\"--disable=cleanupIDs\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:4:\\\"svgo\\\";}i:4;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Gifsicle\\\":5:{s:7:\\\"options\\\";a:2:{i:0;s:2:\\\"-b\\\";i:1;s:3:\\\"-O3\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"gifsicle\\\";}i:5;O:38:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Cwebp\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m 6\\\";i:1;s:8:\\\"-pass 10\\\";i:2;s:3:\\\"-mt\\\";i:3;s:5:\\\"-q 90\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:5:\\\"cwebp\\\";}i:6;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Avifenc\\\":6:{s:7:\\\"options\\\";a:8:{i:0;s:14:\\\"-a cq-level=23\\\";i:1;s:6:\\\"-j all\\\";i:2;s:7:\\\"--min 0\\\";i:3;s:8:\\\"--max 63\\\";i:4;s:12:\\\"--minalpha 0\\\";i:5;s:13:\\\"--maxalpha 63\\\";i:6;s:14:\\\"-a end-usage=q\\\";i:7;s:12:\\\"-a tune=ssim\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"avifenc\\\";s:16:\\\"decodeBinaryName\\\";s:7:\\\"avifdec\\\";}}s:9:\\\"\\u0000*\\u0000logger\\\";O:33:\\\"Spatie\\\\ImageOptimizer\\\\DummyLogger\\\":0:{}s:10:\\\"\\u0000*\\u0000timeout\\\";i:60;}}s:6:\\\"format\\\";a:1:{i:0;s:3:\\\"jpg\\\";}s:3:\\\"fit\\\";a:3:{i:0;E:26:\\\"Spatie\\\\Image\\\\Enums\\\\Fit:Max\\\";i:1;i:800;i:2;i:600;}}}s:23:\\\"\\u0000*\\u0000performOnCollections\\\";a:0:{}s:17:\\\"\\u0000*\\u0000performOnQueue\\\";b:1;s:18:\\\"\\u0000*\\u0000performDeferred\\\";b:0;s:26:\\\"\\u0000*\\u0000keepOriginalImageFormat\\\";b:0;s:27:\\\"\\u0000*\\u0000generateResponsiveImages\\\";b:0;s:18:\\\"\\u0000*\\u0000widthCalculator\\\";N;s:24:\\\"\\u0000*\\u0000loadingAttributeValue\\\";N;s:16:\\\"\\u0000*\\u0000pdfPageNumber\\\";i:1;s:7:\\\"\\u0000*\\u0000name\\\";s:6:\\\"medium\\\";}}s:28:\\\"\\u0000*\\u0000escapeWhenCastingToString\\\";b:0;}s:8:\\\"\\u0000*\\u0000media\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:49:\\\"Spatie\\\\MediaLibrary\\\\MediaCollections\\\\Models\\\\Media\\\";s:2:\\\"id\\\";i:8;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:14:\\\"\\u0000*\\u0000onlyMissing\\\";b:0;s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:0:\\\"\\\";s:11:\\\"afterCommit\\\";b:1;}\"}}', 0, NULL, 1779465300, 1779465300);
INSERT INTO `jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`) VALUES
(14, 'default', '{\"uuid\":\"cee340fe-c5b5-4c2c-af8f-ad1b22654e1c\",\"displayName\":\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\",\"command\":\"O:58:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\\\":6:{s:14:\\\"\\u0000*\\u0000conversions\\\";O:52:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\ConversionCollection\\\":2:{s:8:\\\"\\u0000*\\u0000items\\\";a:2:{i:0;O:42:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Conversion\\\":12:{s:12:\\\"\\u0000*\\u0000fileNamer\\\";O:54:\\\"Spatie\\\\MediaLibrary\\\\Support\\\\FileNamer\\\\DefaultFileNamer\\\":0:{}s:28:\\\"\\u0000*\\u0000extractVideoFrameAtSecond\\\";d:0;s:16:\\\"\\u0000*\\u0000manipulations\\\";O:45:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Manipulations\\\":1:{s:16:\\\"\\u0000*\\u0000manipulations\\\";a:4:{s:8:\\\"optimize\\\";a:1:{i:0;O:36:\\\"Spatie\\\\ImageOptimizer\\\\OptimizerChain\\\":3:{s:13:\\\"\\u0000*\\u0000optimizers\\\";a:7:{i:0;O:42:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Jpegoptim\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m85\\\";i:1;s:7:\\\"--force\\\";i:2;s:11:\\\"--strip-all\\\";i:3;s:17:\\\"--all-progressive\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:9:\\\"jpegoptim\\\";}i:1;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Pngquant\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:7:\\\"--force\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"pngquant\\\";}i:2;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Optipng\\\":5:{s:7:\\\"options\\\";a:3:{i:0;s:3:\\\"-i0\\\";i:1;s:3:\\\"-o2\\\";i:2;s:6:\\\"-quiet\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"optipng\\\";}i:3;O:37:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Svgo\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:20:\\\"--disable=cleanupIDs\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:4:\\\"svgo\\\";}i:4;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Gifsicle\\\":5:{s:7:\\\"options\\\";a:2:{i:0;s:2:\\\"-b\\\";i:1;s:3:\\\"-O3\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"gifsicle\\\";}i:5;O:38:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Cwebp\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m 6\\\";i:1;s:8:\\\"-pass 10\\\";i:2;s:3:\\\"-mt\\\";i:3;s:5:\\\"-q 90\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:5:\\\"cwebp\\\";}i:6;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Avifenc\\\":6:{s:7:\\\"options\\\";a:8:{i:0;s:14:\\\"-a cq-level=23\\\";i:1;s:6:\\\"-j all\\\";i:2;s:7:\\\"--min 0\\\";i:3;s:8:\\\"--max 63\\\";i:4;s:12:\\\"--minalpha 0\\\";i:5;s:13:\\\"--maxalpha 63\\\";i:6;s:14:\\\"-a end-usage=q\\\";i:7;s:12:\\\"-a tune=ssim\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"avifenc\\\";s:16:\\\"decodeBinaryName\\\";s:7:\\\"avifdec\\\";}}s:9:\\\"\\u0000*\\u0000logger\\\";O:33:\\\"Spatie\\\\ImageOptimizer\\\\DummyLogger\\\":0:{}s:10:\\\"\\u0000*\\u0000timeout\\\";i:60;}}s:6:\\\"format\\\";a:1:{i:0;s:3:\\\"jpg\\\";}s:3:\\\"fit\\\";a:3:{i:0;E:30:\\\"Spatie\\\\Image\\\\Enums\\\\Fit:Contain\\\";i:1;i:300;i:2;i:300;}s:7:\\\"sharpen\\\";a:1:{i:0;i:10;}}}s:23:\\\"\\u0000*\\u0000performOnCollections\\\";a:0:{}s:17:\\\"\\u0000*\\u0000performOnQueue\\\";b:1;s:18:\\\"\\u0000*\\u0000performDeferred\\\";b:0;s:26:\\\"\\u0000*\\u0000keepOriginalImageFormat\\\";b:0;s:27:\\\"\\u0000*\\u0000generateResponsiveImages\\\";b:0;s:18:\\\"\\u0000*\\u0000widthCalculator\\\";N;s:24:\\\"\\u0000*\\u0000loadingAttributeValue\\\";N;s:16:\\\"\\u0000*\\u0000pdfPageNumber\\\";i:1;s:7:\\\"\\u0000*\\u0000name\\\";s:5:\\\"thumb\\\";}i:1;O:42:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Conversion\\\":12:{s:12:\\\"\\u0000*\\u0000fileNamer\\\";O:54:\\\"Spatie\\\\MediaLibrary\\\\Support\\\\FileNamer\\\\DefaultFileNamer\\\":0:{}s:28:\\\"\\u0000*\\u0000extractVideoFrameAtSecond\\\";d:0;s:16:\\\"\\u0000*\\u0000manipulations\\\";O:45:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Manipulations\\\":1:{s:16:\\\"\\u0000*\\u0000manipulations\\\";a:3:{s:8:\\\"optimize\\\";a:1:{i:0;O:36:\\\"Spatie\\\\ImageOptimizer\\\\OptimizerChain\\\":3:{s:13:\\\"\\u0000*\\u0000optimizers\\\";a:7:{i:0;O:42:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Jpegoptim\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m85\\\";i:1;s:7:\\\"--force\\\";i:2;s:11:\\\"--strip-all\\\";i:3;s:17:\\\"--all-progressive\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:9:\\\"jpegoptim\\\";}i:1;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Pngquant\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:7:\\\"--force\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"pngquant\\\";}i:2;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Optipng\\\":5:{s:7:\\\"options\\\";a:3:{i:0;s:3:\\\"-i0\\\";i:1;s:3:\\\"-o2\\\";i:2;s:6:\\\"-quiet\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"optipng\\\";}i:3;O:37:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Svgo\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:20:\\\"--disable=cleanupIDs\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:4:\\\"svgo\\\";}i:4;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Gifsicle\\\":5:{s:7:\\\"options\\\";a:2:{i:0;s:2:\\\"-b\\\";i:1;s:3:\\\"-O3\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"gifsicle\\\";}i:5;O:38:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Cwebp\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m 6\\\";i:1;s:8:\\\"-pass 10\\\";i:2;s:3:\\\"-mt\\\";i:3;s:5:\\\"-q 90\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:5:\\\"cwebp\\\";}i:6;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Avifenc\\\":6:{s:7:\\\"options\\\";a:8:{i:0;s:14:\\\"-a cq-level=23\\\";i:1;s:6:\\\"-j all\\\";i:2;s:7:\\\"--min 0\\\";i:3;s:8:\\\"--max 63\\\";i:4;s:12:\\\"--minalpha 0\\\";i:5;s:13:\\\"--maxalpha 63\\\";i:6;s:14:\\\"-a end-usage=q\\\";i:7;s:12:\\\"-a tune=ssim\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"avifenc\\\";s:16:\\\"decodeBinaryName\\\";s:7:\\\"avifdec\\\";}}s:9:\\\"\\u0000*\\u0000logger\\\";O:33:\\\"Spatie\\\\ImageOptimizer\\\\DummyLogger\\\":0:{}s:10:\\\"\\u0000*\\u0000timeout\\\";i:60;}}s:6:\\\"format\\\";a:1:{i:0;s:3:\\\"jpg\\\";}s:3:\\\"fit\\\";a:3:{i:0;E:26:\\\"Spatie\\\\Image\\\\Enums\\\\Fit:Max\\\";i:1;i:800;i:2;i:600;}}}s:23:\\\"\\u0000*\\u0000performOnCollections\\\";a:0:{}s:17:\\\"\\u0000*\\u0000performOnQueue\\\";b:1;s:18:\\\"\\u0000*\\u0000performDeferred\\\";b:0;s:26:\\\"\\u0000*\\u0000keepOriginalImageFormat\\\";b:0;s:27:\\\"\\u0000*\\u0000generateResponsiveImages\\\";b:0;s:18:\\\"\\u0000*\\u0000widthCalculator\\\";N;s:24:\\\"\\u0000*\\u0000loadingAttributeValue\\\";N;s:16:\\\"\\u0000*\\u0000pdfPageNumber\\\";i:1;s:7:\\\"\\u0000*\\u0000name\\\";s:6:\\\"medium\\\";}}s:28:\\\"\\u0000*\\u0000escapeWhenCastingToString\\\";b:0;}s:8:\\\"\\u0000*\\u0000media\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:49:\\\"Spatie\\\\MediaLibrary\\\\MediaCollections\\\\Models\\\\Media\\\";s:2:\\\"id\\\";i:12;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:14:\\\"\\u0000*\\u0000onlyMissing\\\";b:0;s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:0:\\\"\\\";s:11:\\\"afterCommit\\\";b:1;}\"}}', 0, NULL, 1779815167, 1779815167);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(191) NOT NULL,
  `name` varchar(191) NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lotes_proceso_externo`
--

DROP TABLE IF EXISTS `lotes_proceso_externo`;
CREATE TABLE IF NOT EXISTS `lotes_proceso_externo` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `codigo` varchar(191) NOT NULL,
  `plantilla_id` bigint UNSIGNED DEFAULT NULL,
  `entidad_tipo` enum('insumo','mobiliario') NOT NULL,
  `entidad_id` bigint UNSIGNED NOT NULL,
  `cantidad` decimal(10,2) NOT NULL,
  `origen_tipo` enum('orden_compra','proyecto','manual') NOT NULL DEFAULT 'manual',
  `origen_id` bigint UNSIGNED DEFAULT NULL,
  `estado` enum('pendiente','en_proceso','completado','cancelado') NOT NULL DEFAULT 'pendiente',
  `fecha_inicio` date DEFAULT NULL,
  `fecha_finalizacion_estimada` date DEFAULT NULL,
  `fecha_finalizacion_real` date DEFAULT NULL,
  `observaciones` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lotes_proceso_externo_codigo_unique` (`codigo`),
  KEY `lotes_proceso_externo_plantilla_id_foreign` (`plantilla_id`),
  KEY `lotes_proceso_externo_entidad_tipo_entidad_id_index` (`entidad_tipo`,`entidad_id`),
  KEY `lotes_proceso_externo_estado_index` (`estado`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `lotes_proceso_externo`
--

INSERT INTO `lotes_proceso_externo` (`id`, `codigo`, `plantilla_id`, `entidad_tipo`, `entidad_id`, `cantidad`, `origen_tipo`, `origen_id`, `estado`, `fecha_inicio`, `fecha_finalizacion_estimada`, `fecha_finalizacion_real`, `observaciones`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'LPE-2026-0001', 1, 'insumo', 2, 4.00, 'manual', NULL, 'completado', '2026-05-27', '2026-06-22', '2026-05-27', NULL, '2026-05-27 12:28:19', '2026-06-01 12:12:16', '2026-06-01 12:12:16'),
(2, 'LPE-2026-0002', 1, 'insumo', 2, 5.00, 'manual', NULL, 'en_proceso', '2026-05-27', '2026-05-29', NULL, NULL, '2026-05-27 15:40:55', '2026-06-01 12:12:16', '2026-06-01 12:12:16'),
(3, 'LPE-2026-0003', 1, 'insumo', 2, 2.00, 'orden_compra', 1, 'en_proceso', '2026-05-27', NULL, NULL, NULL, '2026-05-27 16:38:45', '2026-06-01 12:12:16', '2026-06-01 12:12:16');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lote_etapas`
--

DROP TABLE IF EXISTS `lote_etapas`;
CREATE TABLE IF NOT EXISTS `lote_etapas` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `lote_id` bigint UNSIGNED NOT NULL,
  `orden` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `tipo_proceso_id` bigint UNSIGNED NOT NULL,
  `tercero_id` bigint UNSIGNED DEFAULT NULL,
  `estado` enum('pendiente','en_transito','en_proceso','completado') NOT NULL DEFAULT 'pendiente',
  `fecha_envio` date DEFAULT NULL,
  `fecha_recepcion_estimada` date DEFAULT NULL,
  `fecha_recepcion_real` date DEFAULT NULL,
  `costo` decimal(10,2) DEFAULT NULL,
  `observaciones` text,
  `usuario_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lote_etapas_lote_id_foreign` (`lote_id`),
  KEY `lote_etapas_tipo_proceso_id_foreign` (`tipo_proceso_id`),
  KEY `lote_etapas_tercero_id_foreign` (`tercero_id`),
  KEY `lote_etapas_usuario_id_foreign` (`usuario_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `lote_etapas`
--

INSERT INTO `lote_etapas` (`id`, `lote_id`, `orden`, `tipo_proceso_id`, `tercero_id`, `estado`, `fecha_envio`, `fecha_recepcion_estimada`, `fecha_recepcion_real`, `costo`, `observaciones`, `usuario_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 5, 3, 'completado', '2026-05-27', '2026-06-01', '2026-05-27', NULL, NULL, 1, '2026-05-27 12:28:19', '2026-05-27 12:53:27'),
(2, 1, 2, 1, 1, 'completado', '2026-05-27', '2026-05-30', '2026-05-27', NULL, NULL, 1, '2026-05-27 12:28:19', '2026-05-27 12:54:00'),
(3, 1, 2, 2, 2, 'completado', '2026-05-27', '2026-05-30', '2026-05-27', NULL, NULL, 1, '2026-05-27 12:28:19', '2026-05-27 12:54:24'),
(4, 2, 1, 5, 3, 'pendiente', NULL, '2026-06-01', NULL, NULL, NULL, NULL, '2026-05-27 15:40:55', '2026-05-27 15:40:55'),
(5, 2, 2, 1, 1, 'pendiente', NULL, '2026-05-30', NULL, NULL, NULL, NULL, '2026-05-27 15:40:55', '2026-05-27 15:40:55'),
(6, 2, 2, 2, 2, 'pendiente', NULL, '2026-05-30', NULL, NULL, NULL, NULL, '2026-05-27 15:40:55', '2026-05-27 15:40:55'),
(7, 3, 1, 5, 3, 'completado', '2026-05-27', '2026-06-01', '2026-05-27', NULL, NULL, 4, '2026-05-27 16:38:45', '2026-05-27 19:31:09'),
(8, 3, 2, 1, 1, 'pendiente', NULL, '2026-05-30', NULL, NULL, NULL, NULL, '2026-05-27 16:38:45', '2026-05-27 16:38:45'),
(9, 3, 2, 2, 2, 'en_transito', '2026-05-27', '2026-05-30', NULL, NULL, NULL, 4, '2026-05-27 16:38:45', '2026-05-27 19:33:12');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `marcas`
--

DROP TABLE IF EXISTS `marcas`;
CREATE TABLE IF NOT EXISTS `marcas` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` varchar(191) NOT NULL,
  `logo` varchar(191) DEFAULT NULL,
  `manual_pdf` varchar(191) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `marcas`
--

INSERT INTO `marcas` (`id`, `nombre`, `logo`, `manual_pdf`, `activo`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'CHERY', 'marcas/logos/01KS2R06FZRP58N1RVHK1XGQ43.png', NULL, 1, NULL, '2026-05-20 13:08:03', '2026-05-20 13:08:03'),
(2, 'Jetour', 'marcas/logos/01KS5VQF3JFCQ7VQAC3K6JSP90.png', NULL, 1, NULL, '2026-05-21 18:10:55', '2026-05-21 18:10:55'),
(3, 'Kawasaki', 'marcas/logos/01KS5VQW2B5NFKGFJFB8R7SJ18.png', NULL, 1, NULL, '2026-05-21 18:11:08', '2026-05-21 18:11:08'),
(4, 'Zanella', 'marcas/logos/01KS5VR7RPRBS558RA8QDBANRA.png', NULL, 1, NULL, '2026-05-21 18:11:20', '2026-05-21 18:11:20'),
(5, 'Renault', 'marcas/logos/01KS5VRS7VA92X0VHRERBX0C1Z.jpg', NULL, 1, NULL, '2026-05-21 18:11:38', '2026-05-21 18:11:38'),
(6, 'Triumph', 'marcas/logos/01KS5VS55PG4YQZZHW4X62G91C.png', NULL, 1, NULL, '2026-05-21 18:11:50', '2026-05-21 18:11:50'),
(7, 'Nissan', 'marcas/logos/01KS5VSGFCH7MQGRSN0G47GVM1.png', NULL, 1, NULL, '2026-05-21 18:12:02', '2026-05-21 18:12:02'),
(12, 'CF Moto', NULL, NULL, 1, NULL, '2026-06-01 13:21:53', '2026-06-01 13:21:53'),
(13, 'Citroen', NULL, NULL, 1, NULL, '2026-06-01 13:22:01', '2026-06-01 13:22:08'),
(9, 'Chevrolet', 'marcas/logos/01KS5VTNJHCDNTTEQB4R2T9HHR.png', NULL, 1, NULL, '2026-05-21 18:12:40', '2026-05-21 18:12:40'),
(10, 'Leap', 'marcas/logos/01KS5VV6656309VKAH734FH53A.png', NULL, 1, NULL, '2026-05-21 18:12:57', '2026-05-21 18:12:57'),
(11, 'Chevrolet ev', 'marcas/logos/01KS60VWEFGJDVPB4A3KSVYB9F.jpg', NULL, 1, NULL, '2026-05-21 19:40:42', '2026-05-21 19:40:42'),
(14, 'Peugeot', NULL, NULL, 1, NULL, '2026-06-01 13:22:21', '2026-06-01 13:22:21'),
(15, 'Multimarcas', NULL, NULL, 1, NULL, '2026-06-01 13:22:28', '2026-06-01 13:22:28'),
(16, 'DongFeng', NULL, NULL, 1, NULL, '2026-06-01 13:22:35', '2026-06-01 13:22:35');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `media`
--

DROP TABLE IF EXISTS `media`;
CREATE TABLE IF NOT EXISTS `media` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `model_type` varchar(191) NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  `uuid` char(36) DEFAULT NULL,
  `collection_name` varchar(191) NOT NULL,
  `name` varchar(191) NOT NULL,
  `file_name` varchar(191) NOT NULL,
  `mime_type` varchar(191) DEFAULT NULL,
  `disk` varchar(191) NOT NULL,
  `conversions_disk` varchar(191) DEFAULT NULL,
  `size` bigint UNSIGNED NOT NULL,
  `manipulations` json NOT NULL,
  `custom_properties` json NOT NULL,
  `generated_conversions` json NOT NULL,
  `responsive_images` json NOT NULL,
  `order_column` int UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `media_uuid_unique` (`uuid`),
  KEY `media_model_type_model_id_index` (`model_type`,`model_id`),
  KEY `media_order_column_index` (`order_column`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `media`
--

INSERT INTO `media` (`id`, `model_type`, `model_id`, `uuid`, `collection_name`, `name`, `file_name`, `mime_type`, `disk`, `conversions_disk`, `size`, `manipulations`, `custom_properties`, `generated_conversions`, `responsive_images`, `order_column`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\Agencia', 2, 'cf1a0fcb-1985-4f07-be8e-5cbcbeabaec5', 'planos', 'roma chery 2', '01KS2RWPPH5S06DB0JN2P49J5M.png', 'image/png', 'public', 'public', 76119, '[]', '[]', '[]', '[]', 1, '2026-05-20 13:23:37', '2026-05-20 13:23:37'),
(2, 'App\\Models\\Agencia', 2, 'fe2adf42-3b08-45a4-94be-1bf2ee1ca6dc', 'planos', 'roma chery 1', '01KS2RWPSNE3QHXMB3VPSST1J7.png', 'image/png', 'public', 'public', 322976, '[]', '[]', '[]', '[]', 2, '2026-05-20 13:23:37', '2026-05-20 13:23:37'),
(9, 'App\\Models\\Insumo', 2, '3bb29fc7-966d-436c-a94a-b7dee1b6aeb9', 'imagen', 'pata multimarca', '01KS89QZ4WGVQ076X43DD4PBRW.png', 'image/png', 'public', 'public', 8554, '[]', '[]', '{\"thumb\": true}', '[]', 1, '2026-05-22 16:54:20', '2026-05-22 16:54:21'),
(8, 'App\\Models\\Mobiliario', 2, '154551fa-74d0-45d4-95ba-c976df61bfd9', 'imagenes', 'mueble tv doble cher', '01KS86BAXASKEF40Y75XB6TAVK.png', 'image/png', 'public', 'public', 13215, '[]', '[]', '[]', '[]', 1, '2026-05-22 15:55:00', '2026-05-22 15:55:00'),
(5, 'App\\Models\\Mobiliario', 3, '10a09659-f9df-4753-9d7f-c41e0baea45f', 'imagenes', 'esctiroio chery', '01KS2T1KAY3HGQPQZQDA7EQGAW.png', 'image/png', 'public', 'public', 38461, '[]', '[]', '[]', '[]', 1, '2026-05-20 13:43:46', '2026-05-20 13:43:46'),
(7, 'App\\Models\\Mobiliario', 1, '1cc86d40-35de-45d8-8753-6e2e4a098b1b', 'imagenes', 'mesa negra chery', '01KS862G1R1RYZQ4X2Y7GZMWYM.png', 'image/png', 'public', 'public', 45922, '[]', '[]', '[]', '[]', 1, '2026-05-22 15:50:11', '2026-05-22 15:50:11'),
(10, 'App\\Models\\Insumo', 2, '8e2e2056-1560-42ac-a3f5-270399c37957', 'plano', 'PATAS COWORKING SIMPLE', '01KS89Z70W8J6ZGFN3AQ4BZH97.pdf', 'application/pdf', 'public', 'public', 103337, '[]', '[]', '[]', '[]', 2, '2026-05-22 16:58:18', '2026-05-22 16:58:18'),
(11, 'App\\Models\\Insumo', 1, '96f68a62-34cc-4803-a69e-5e251f33910f', 'imagen', 'regaton de goma', '01KS8HE4W6PXZMFEYANBMG8KCZ.jpg', 'image/jpeg', 'public', 'public', 5137, '[]', '[]', '{\"thumb\": true}', '[]', 1, '2026-05-22 19:08:47', '2026-05-22 19:08:47'),
(12, 'App\\Models\\Mobiliario', 4, 'd383eda5-db01-4084-8ca4-c2dd768f75e3', 'imagenes', 'mesa baja chery', '01KSJM0DRBHKZYGNWJK2MRBJYW.png', 'image/png', 'public', 'public', 51777, '[]', '[]', '[]', '[]', 1, '2026-05-26 17:06:07', '2026-05-26 17:06:07'),
(13, 'App\\Models\\Agencia', 7, '341b7d0b-ba11-4873-9246-a2f41328cd44', 'planos', '2025.09.10_Chery_Roma_Mendoza', '01KSJMK7T9W2HN7XCQ02S5NEJH.pdf', 'application/pdf', 'public', 'public', 6964325, '[]', '[]', '[]', '[]', 1, '2026-05-26 17:16:24', '2026-05-26 17:16:24'),
(14, 'App\\Models\\Agencia', 8, 'f39ac0b8-e2b5-4c72-ac6d-9489538b0d41', 'planos', '2025.09.10_Chery_Roma_Mendoza', '01KSJNMDCVHYKKPPXYTD3MJ16W.pdf', 'application/pdf', 'public', 'public', 6964325, '[]', '[]', '[]', '[]', 1, '2026-05-26 17:34:31', '2026-05-26 17:34:31'),
(15, 'App\\Models\\Mobiliario', 5, 'bb977e94-5b4c-4763-9bec-2e7d6cb1e1ce', 'documentos', 'GOMA ESPUMA SILLON', '01KT1WN6TB9Q290SVPE7DEHZXD.pdf', 'application/pdf', 'public', 'public', 4991595, '[]', '[]', '[]', '[]', 1, '2026-06-01 15:25:53', '2026-06-01 15:25:53'),
(16, 'App\\Models\\Agencia', 10, '9dcb154e-f6fb-49a3-8a81-62b4eb5cf222', 'planos', '2025.09.10_Chery_Roma_Mendoza', '01KT28S74NCX3BGCHZT78E8D1S.pdf', 'application/pdf', 'public', 'public', 6964325, '[]', '[]', '[]', '[]', 1, '2026-06-01 18:57:48', '2026-06-01 18:57:48'),
(17, 'App\\Models\\Agencia', 11, '4dc6ea4d-7b49-4a3d-8638-c69fbb227ce0', 'planos', '2025.09.15_Chery_Surfeng_Mendoza (1)', '01KT28TTDQZXTMM9KQKGGDBK9X.pdf', 'application/pdf', 'public', 'public', 3545894, '[]', '[]', '[]', '[]', 1, '2026-06-01 18:58:40', '2026-06-01 18:58:40'),
(18, 'App\\Models\\Agencia', 20, '3e722ad5-e1f3-45f0-93fa-a474da801a4a', 'planos', '2025-12-02 Layout Chery Escobar Santa Fe', '01KT29RX840N5W37RN7JYAZJZ7.pdf', 'application/pdf', 'public', 'public', 11321130, '[]', '[]', '[]', '[]', 1, '2026-06-01 19:15:06', '2026-06-01 19:15:06'),
(19, 'App\\Models\\Agencia', 21, '6b5e7030-e2ff-4fe2-9383-03f20932fa5e', 'planos', '2026.03.16_SVA_Olivos', '01KT29WA6GFY46JB6HB6F5R5A9.pdf', 'application/pdf', 'public', 'public', 8132794, '[]', '[]', '[]', '[]', 1, '2026-06-01 19:16:58', '2026-06-01 19:16:58');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=66 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_05_20_083739_create_permission_tables', 1),
(5, '2026_05_20_083741_create_activity_log_table', 1),
(6, '2026_05_20_083742_add_event_column_to_activity_log_table', 1),
(7, '2026_05_20_083743_add_batch_uuid_column_to_activity_log_table', 1),
(8, '2026_05_20_100000_create_marcas_table', 1),
(9, '2026_05_20_100001_create_agencias_table', 1),
(10, '2026_05_20_100002_create_proyectos_table', 1),
(11, '2026_05_20_100003_create_proyecto_historial_table', 1),
(12, '2026_05_20_100004_create_categorias_mobiliario_table', 1),
(13, '2026_05_20_100005_create_mobiliarios_table', 1),
(14, '2026_05_20_100006_create_atributos_mobiliario_table', 1),
(15, '2026_05_20_100007_create_unidades_medida_table', 1),
(16, '2026_05_20_100008_create_insumos_table', 1),
(17, '2026_05_20_100009_create_composicion_tecnica_table', 1),
(18, '2026_05_20_100010_create_historial_versiones_table', 1),
(19, '2026_05_20_100011_create_proyecto_mobiliario_table', 1),
(20, '2026_05_20_101408_create_media_table', 2),
(21, '2026_05_20_110000_create_presupuestos_table', 3),
(22, '2026_05_20_110001_create_presupuesto_items_table', 3),
(23, '2026_05_20_110002_create_presupuesto_versiones_table', 3),
(24, '2026_05_20_110003_create_presupuesto_historial_table', 3),
(25, '2026_05_20_200001_add_stock_actual_to_insumos_table', 4),
(26, '2026_05_20_200002_create_reservas_stock_table', 5),
(27, '2026_05_20_200003_create_ordenes_compra_tables', 5),
(28, '2026_05_21_100000_add_manual_pdf_to_marcas_table', 6),
(29, '2026_05_21_100001_add_marca_id_to_proyectos_table', 6),
(30, '2026_05_21_100002_add_agencia_to_presupuestos_table', 6),
(31, '2026_05_21_110000_add_manual_pdf_to_proyectos_table', 7),
(32, '2026_05_21_200001_restructure_agencia_proyecto_presupuesto', 8),
(33, '2026_05_21_200002_drop_codigo_interno_agencias_and_colores_marcas', 8),
(34, '2026_05_22_000000_change_manual_pdf_to_json_in_proyectos_table', 9),
(35, '2026_05_22_140321_add_tag_to_insumos_table', 10),
(36, '2026_05_24_000001_add_username_to_users_table', 11),
(37, '2026_05_25_000001_add_marca_id_to_mobiliarios_table', 12),
(38, '2026_05_26_000001_create_provincias_table', 13),
(39, '2026_05_26_000002_create_ciudades_table', 13),
(40, '2026_05_26_000003_add_provincia_ciudad_to_agencias', 13),
(41, '2026_05_27_000001_create_terceros_table', 14),
(42, '2026_05_27_000002_create_tipos_proceso_externo_table', 14),
(43, '2026_05_27_000003_create_plantillas_flujo_externo_table', 14),
(44, '2026_05_27_000004_create_plantilla_etapas_table', 14),
(45, '2026_05_27_000005_create_lotes_proceso_externo_table', 14),
(46, '2026_05_27_000006_create_lote_etapas_table', 14),
(47, '2026_05_28_000001_create_categorias_insumo_table', 15),
(48, '2026_05_28_000002_add_categoria_insumo_id_to_insumos_table', 15),
(49, '2026_05_28_000003_create_tipos_silla_table', 16),
(50, '2026_05_28_000004_add_silla_fields_to_insumos_table', 16),
(51, '2026_05_28_000005_create_insumo_marcas_silla_table', 16),
(52, '2026_05_28_000006_add_precio_to_mobiliarios_table', 17),
(53, '2026_05_28_000006_create_mobiliario_historial_precios_table', 17),
(54, '2026_05_28_000007_move_silla_fields_from_insumos_to_mobiliarios', 18),
(55, '2026_05_28_000008_create_proveedores_table', 19),
(56, '2026_05_28_000009_create_rubros_table', 20),
(57, '2026_05_28_000010_replace_rubro_in_proveedores', 20),
(58, '2026_05_28_000008_revert_silla_fields_to_insumos', 21),
(59, '2026_05_28_000009_add_insumo_id_to_presupuesto_items', 22),
(60, '2026_05_29_000001_convert_proveedor_rubro_to_many_to_many', 23),
(61, '2026_05_29_000002_convert_categoria_insumo_to_many_to_many', 24),
(62, '2026_06_01_000001_create_sectores_table', 25),
(63, '2026_06_01_000002_add_sector_id_to_mobiliarios', 26),
(64, '2026_06_01_000003_move_sector_from_mobiliarios_to_presupuesto_items', 27),
(65, '2026_06_01_000004_add_sector_id_to_proyecto_mobiliario', 28);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mobiliarios`
--

DROP TABLE IF EXISTS `mobiliarios`;
CREATE TABLE IF NOT EXISTS `mobiliarios` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `codigo_interno` varchar(191) NOT NULL,
  `nombre` varchar(191) NOT NULL,
  `categoria_id` bigint UNSIGNED NOT NULL,
  `marca_id` bigint UNSIGNED DEFAULT NULL,
  `imagen` varchar(191) DEFAULT NULL,
  `descripcion` text,
  `observaciones` text,
  `estado` varchar(191) NOT NULL DEFAULT 'activo',
  `precio` decimal(12,2) DEFAULT NULL,
  `version_actual` int UNSIGNED NOT NULL DEFAULT '1',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mobiliarios_codigo_interno_unique` (`codigo_interno`),
  KEY `mobiliarios_categoria_id_foreign` (`categoria_id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `mobiliarios`
--

INSERT INTO `mobiliarios` (`id`, `codigo_interno`, `nombre`, `categoria_id`, `marca_id`, `imagen`, `descripcion`, `observaciones`, `estado`, `precio`, `version_actual`, `deleted_at`, `created_at`, `updated_at`) VALUES
(5, 'CH02', 'SILLON 3 CUERPOS', 10, 1, NULL, 'Sillón fabricado según plano, tapizado en cuerina marrón en apoya brazos y almohadones. Resto de sillón tapizado en tela gris clara', NULL, 'activo', NULL, 1, NULL, '2026-06-01 15:25:53', '2026-06-01 15:25:53'),
(6, 'CH05', 'MESA BAJA', 8, 1, NULL, 'Mesa de melamina revestida en solido símil mármol y negro antihuellas', NULL, 'activo', NULL, 1, NULL, '2026-06-01 15:28:48', '2026-06-01 15:28:48'),
(7, 'CH31', 'SILLA SALA DE ESPERA', 10, 1, NULL, 'Diseño segun manual', NULL, 'activo', NULL, 1, NULL, '2026-06-01 15:30:30', '2026-06-01 15:30:30'),
(8, 'CH08', 'SILLA ESCRITORIO DE VENDEDORES', 10, 1, NULL, 'Diseño según manual', NULL, 'activo', NULL, 1, NULL, '2026-06-01 15:31:25', '2026-06-01 15:31:25'),
(9, 'CH32', 'SILLA CLIENTES', 10, 1, NULL, 'Diseño según manual', NULL, 'activo', NULL, 1, NULL, '2026-06-01 15:32:06', '2026-06-01 15:32:06'),
(10, 'CH09-2800', 'MUEBLE DE TV', 11, 1, NULL, 'Mueble de guardado, fabricado en melamina nogal terracota y partes en melamina negra, con puertas de abrir y espacios para guardado', 'Mueble de TV SIMPLE - No lleva TV, escritorio, ni puertas de ambos lados. (VER PUERTAS)', 'activo', NULL, 1, NULL, '2026-06-01 15:35:52', '2026-06-01 15:35:52'),
(11, 'CH10-2800', 'MUEBLE DE TV DOBLE', 11, 1, NULL, 'Mueble de guardado BIFAZ, fabricado en melamina nogal terracota y partes en melamina negra, con puertas de abrir y espacios para guardado.', 'Lleva TV ,puertas y escritorios de ambos lados.', 'activo', NULL, 1, NULL, '2026-06-01 15:37:46', '2026-06-01 15:37:46'),
(12, 'CH30', 'MUEBLE DE TV Y CAFE', 12, 1, NULL, 'Mueble de guardado, fabricado en melamina nogal terracota y partes en melamina negra, con puertas de abrir y espacios para guardado.', NULL, 'activo', NULL, 1, NULL, '2026-06-01 15:44:32', '2026-06-01 15:44:32'),
(13, 'CH11', 'MESA ESCRITORIO', 1, 1, NULL, 'Fabricado en melamina negra lisa anti huellas y cantos a 45° con base tipo piramidal según manual', 'Medida especial 1500', 'activo', NULL, 1, NULL, '2026-06-01 16:12:33', '2026-06-01 16:12:33'),
(14, 'CH19', 'SILLA RECEPCION', 10, 1, NULL, 'Diseño según manual', NULL, 'activo', NULL, 1, NULL, '2026-06-01 16:14:01', '2026-06-01 16:14:01'),
(15, 'CH23', 'MESA NEGRA', 8, 1, NULL, 'Tapa superior de melamina ANTIHUELLA con base metálica pintada a horno con pintura epoxi color negra', 'Tapa con canto a 45°', 'activo', NULL, 1, NULL, '2026-06-01 16:16:58', '2026-06-01 16:16:58'),
(16, 'CH24', 'MURO DE MARCA', 13, 1, NULL, 'Paneles de melamina color aluminio, con 10 led verticales y logo corpóreo en acrílico cortado a laser', 'Revisar medidas en layout', 'activo', NULL, 1, NULL, '2026-06-01 16:32:46', '2026-06-01 16:32:46'),
(17, 'CH30-2400', 'MUEBLE DE TV Y CAFE', 12, 1, NULL, 'Mueble de guardado, fabricado en melamina nogal terracota y partes en melamina negra, con puertas de abrir y espacios para guardado', 'Verificar CENEFA y AJUSTE con layout', 'activo', NULL, 1, NULL, '2026-06-01 17:27:17', '2026-06-01 17:27:17'),
(18, 'CH15', 'ESCRITORIO POSTVENTA MOD1', 2, 1, NULL, 'Recepción fabricada en melamina everest, con partes en melamina color gris humo', 'Verificar módulos para su producción ', 'activo', NULL, 1, NULL, '2026-06-01 17:30:12', '2026-06-01 17:30:12'),
(19, 'CH16', 'ESCRITORIO POSTVENTA MOD2', 2, 1, NULL, 'Recepción fabricada en melamina everest, con partes en melamina color gris humo', 'Verificar módulos para su producción ', 'activo', NULL, 1, NULL, '2026-06-01 17:32:45', '2026-06-01 17:32:45'),
(20, 'CH17', 'ESCRITORIO POSTVENTA MOD3', 2, 1, NULL, 'Recepción fabricada en melamina everest, con partes en melamina color gris humo', 'Verificar módulos para su producción ', 'activo', NULL, 1, NULL, '2026-06-01 17:34:08', '2026-06-01 19:51:50'),
(21, 'CH10', 'MUEBLE DE TV DOBLE', 11, 1, NULL, 'Mueble de guardado BIFAZ, fabricado en melamina nogal terracota y partes en melamina negra, con puertas de abrir y espacios para guardado', NULL, 'activo', NULL, 1, NULL, '2026-06-01 17:37:32', '2026-06-01 17:37:32'),
(22, 'CH11-2200', 'MESA ESCRITORIO', 1, 1, NULL, 'Fabricado en melamina negra lisa anti huellas y cantos a 45° con base tipo piramidal según manual', 'MEDIDA ESPECIAL 2200 * 800', 'activo', NULL, 1, NULL, '2026-06-01 17:40:17', '2026-06-01 17:40:17'),
(23, 'CH10-2400', 'MUEBLE TV DOBLE', 11, 1, NULL, 'Mueble de guardado BIFAZ, fabricado en melamina nogal terracota y partes en melamina negra, con puertas de abrir y espacios para guardado', 'Lleva TV ,puertas y escritorios de ambos lados.\nVerificar CENEFA y AJUSTE con layout', 'activo', NULL, 1, NULL, '2026-06-01 17:43:27', '2026-06-01 17:43:27'),
(24, 'CH13-2000', 'RECEPCION', 2, 1, NULL, 'Recepción fabricada en melamina silver, con partes en solido color negro, con logo corpóreo en acrílico cortado a laser', NULL, 'activo', NULL, 1, NULL, '2026-06-01 17:47:20', '2026-06-01 17:47:20'),
(25, 'CH11-1500', 'MESA ESCRITORIO', 1, 1, NULL, 'Fabricado en melamina negra lisa anti huellas y cantos a 45° con base tipo piramidal según manual', 'MEDIDA ESPECIAL 1500*1000', 'activo', NULL, 1, NULL, '2026-06-01 19:50:14', '2026-06-01 19:50:14');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mobiliario_historial_precios`
--

DROP TABLE IF EXISTS `mobiliario_historial_precios`;
CREATE TABLE IF NOT EXISTS `mobiliario_historial_precios` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `mobiliario_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `precio` decimal(12,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `mobiliario_historial_precios_mobiliario_id_foreign` (`mobiliario_id`),
  KEY `mobiliario_historial_precios_user_id_foreign` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
CREATE TABLE IF NOT EXISTS `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(191) NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
CREATE TABLE IF NOT EXISTS `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(191) NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(1, 'App\\Models\\User', 4),
(1, 'App\\Models\\User', 5),
(2, 'App\\Models\\User', 2),
(2, 'App\\Models\\User', 6),
(3, 'App\\Models\\User', 3),
(4, 'App\\Models\\User', 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ordenes_compra`
--

DROP TABLE IF EXISTS `ordenes_compra`;
CREATE TABLE IF NOT EXISTS `ordenes_compra` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `codigo` varchar(191) NOT NULL,
  `estado` enum('sugerida','pendiente','aprobada','recibida','cancelada') NOT NULL DEFAULT 'sugerida',
  `prioridad` enum('baja','media','alta','critica') NOT NULL DEFAULT 'media',
  `generado_automaticamente` tinyint(1) NOT NULL DEFAULT '1',
  `observaciones` text,
  `presupuesto_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ordenes_compra_codigo_unique` (`codigo`),
  KEY `ordenes_compra_estado_index` (`estado`),
  KEY `ordenes_compra_prioridad_index` (`prioridad`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `ordenes_compra`
--

INSERT INTO `ordenes_compra` (`id`, `codigo`, `estado`, `prioridad`, `generado_automaticamente`, `observaciones`, `presupuesto_id`, `created_at`, `updated_at`) VALUES
(1, 'OC-2026-0001', 'aprobada', 'media', 1, NULL, NULL, '2026-05-27 16:31:02', '2026-05-27 16:42:22');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orden_compra_items`
--

DROP TABLE IF EXISTS `orden_compra_items`;
CREATE TABLE IF NOT EXISTS `orden_compra_items` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `orden_compra_id` bigint UNSIGNED NOT NULL,
  `insumo_id` bigint UNSIGNED NOT NULL,
  `cantidad_solicitada` decimal(10,2) NOT NULL,
  `cantidad_recibida` decimal(10,2) NOT NULL DEFAULT '0.00',
  `precio_unitario` decimal(10,2) DEFAULT NULL,
  `observaciones` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `orden_compra_items`
--

INSERT INTO `orden_compra_items` (`id`, `orden_compra_id`, `insumo_id`, `cantidad_solicitada`, `cantidad_recibida`, `precio_unitario`, `observaciones`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 2.00, 1.00, NULL, NULL, '2026-05-27 16:31:02', '2026-05-27 16:41:57');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(191) NOT NULL,
  `token` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `guard_name` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'marcas.viewAny', 'web', '2026-05-20 11:53:33', '2026-05-20 11:53:33'),
(2, 'marcas.view', 'web', '2026-05-20 11:53:33', '2026-05-20 11:53:33'),
(3, 'marcas.create', 'web', '2026-05-20 11:53:33', '2026-05-20 11:53:33'),
(4, 'marcas.update', 'web', '2026-05-20 11:53:33', '2026-05-20 11:53:33'),
(5, 'marcas.delete', 'web', '2026-05-20 11:53:33', '2026-05-20 11:53:33'),
(6, 'agencias.viewAny', 'web', '2026-05-20 11:53:33', '2026-05-20 11:53:33'),
(7, 'agencias.view', 'web', '2026-05-20 11:53:33', '2026-05-20 11:53:33'),
(8, 'agencias.create', 'web', '2026-05-20 11:53:33', '2026-05-20 11:53:33'),
(9, 'agencias.update', 'web', '2026-05-20 11:53:33', '2026-05-20 11:53:33'),
(10, 'agencias.delete', 'web', '2026-05-20 11:53:33', '2026-05-20 11:53:33'),
(11, 'proyectos.viewAny', 'web', '2026-05-20 11:53:33', '2026-05-20 11:53:33'),
(12, 'proyectos.view', 'web', '2026-05-20 11:53:33', '2026-05-20 11:53:33'),
(13, 'proyectos.create', 'web', '2026-05-20 11:53:33', '2026-05-20 11:53:33'),
(14, 'proyectos.update', 'web', '2026-05-20 11:53:33', '2026-05-20 11:53:33'),
(15, 'proyectos.delete', 'web', '2026-05-20 11:53:33', '2026-05-20 11:53:33'),
(16, 'mobiliarios.viewAny', 'web', '2026-05-20 11:53:33', '2026-05-20 11:53:33'),
(17, 'mobiliarios.view', 'web', '2026-05-20 11:53:33', '2026-05-20 11:53:33'),
(18, 'mobiliarios.create', 'web', '2026-05-20 11:53:33', '2026-05-20 11:53:33'),
(19, 'mobiliarios.update', 'web', '2026-05-20 11:53:33', '2026-05-20 11:53:33'),
(20, 'mobiliarios.delete', 'web', '2026-05-20 11:53:33', '2026-05-20 11:53:33'),
(21, 'categorias.viewAny', 'web', '2026-05-20 11:53:33', '2026-05-20 11:53:33'),
(22, 'categorias.view', 'web', '2026-05-20 11:53:33', '2026-05-20 11:53:33'),
(23, 'categorias.create', 'web', '2026-05-20 11:53:33', '2026-05-20 11:53:33'),
(24, 'categorias.update', 'web', '2026-05-20 11:53:33', '2026-05-20 11:53:33'),
(25, 'categorias.delete', 'web', '2026-05-20 11:53:33', '2026-05-20 11:53:33'),
(26, 'insumos.viewAny', 'web', '2026-05-20 11:53:33', '2026-05-20 11:53:33'),
(27, 'insumos.view', 'web', '2026-05-20 11:53:33', '2026-05-20 11:53:33'),
(28, 'insumos.create', 'web', '2026-05-20 11:53:33', '2026-05-20 11:53:33'),
(29, 'insumos.update', 'web', '2026-05-20 11:53:33', '2026-05-20 11:53:33'),
(30, 'insumos.delete', 'web', '2026-05-20 11:53:33', '2026-05-20 11:53:33'),
(31, 'usuarios.viewAny', 'web', '2026-05-20 11:53:33', '2026-05-20 11:53:33'),
(32, 'usuarios.view', 'web', '2026-05-20 11:53:33', '2026-05-20 11:53:33'),
(33, 'usuarios.create', 'web', '2026-05-20 11:53:33', '2026-05-20 11:53:33'),
(34, 'usuarios.update', 'web', '2026-05-20 11:53:33', '2026-05-20 11:53:33'),
(35, 'usuarios.delete', 'web', '2026-05-20 11:53:33', '2026-05-20 11:53:33');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `plantillas_flujo_externo`
--

DROP TABLE IF EXISTS `plantillas_flujo_externo`;
CREATE TABLE IF NOT EXISTS `plantillas_flujo_externo` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` varchar(191) NOT NULL,
  `entidad_tipo` enum('insumo','mobiliario') NOT NULL,
  `entidad_id` bigint UNSIGNED NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `plantillas_flujo_externo_entidad_tipo_entidad_id_index` (`entidad_tipo`,`entidad_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `plantillas_flujo_externo`
--

INSERT INTO `plantillas_flujo_externo` (`id`, `nombre`, `entidad_tipo`, `entidad_id`, `activo`, `created_at`, `updated_at`) VALUES
(2, 'Tapizado Sillon Chery', 'mobiliario', 5, 1, '2026-06-01 19:27:13', '2026-06-01 19:27:13');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `plantilla_etapas`
--

DROP TABLE IF EXISTS `plantilla_etapas`;
CREATE TABLE IF NOT EXISTS `plantilla_etapas` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `plantilla_id` bigint UNSIGNED NOT NULL,
  `orden` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `tipo_proceso_id` bigint UNSIGNED NOT NULL,
  `tercero_id` bigint UNSIGNED DEFAULT NULL,
  `dias_estimados` smallint UNSIGNED DEFAULT NULL,
  `observaciones` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `plantilla_etapas_plantilla_id_foreign` (`plantilla_id`),
  KEY `plantilla_etapas_tipo_proceso_id_foreign` (`tipo_proceso_id`),
  KEY `plantilla_etapas_tercero_id_foreign` (`tercero_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `plantilla_etapas`
--

INSERT INTO `plantilla_etapas` (`id`, `plantilla_id`, `orden`, `tipo_proceso_id`, `tercero_id`, `dias_estimados`, `observaciones`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 5, 3, 5, NULL, '2026-05-27 12:27:46', '2026-05-27 12:27:46'),
(2, 1, 2, 1, 1, 3, NULL, '2026-05-27 12:27:46', '2026-05-27 12:27:46'),
(3, 1, 2, 2, 2, 3, NULL, '2026-05-27 12:27:46', '2026-05-27 12:27:46'),
(4, 2, 1, 4, 6, 10, NULL, '2026-06-01 19:27:13', '2026-06-01 19:27:13');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `presupuestos`
--

DROP TABLE IF EXISTS `presupuestos`;
CREATE TABLE IF NOT EXISTS `presupuestos` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `codigo` varchar(30) NOT NULL,
  `agencia_id` bigint UNSIGNED DEFAULT NULL,
  `responsable_id` bigint UNSIGNED NOT NULL,
  `estado` varchar(30) NOT NULL DEFAULT 'borrador',
  `version` smallint UNSIGNED NOT NULL DEFAULT '1',
  `fecha_emision` date NOT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `observaciones` text,
  `notas_internas` text,
  `aprobado_por` bigint UNSIGNED DEFAULT NULL,
  `aprobado_at` timestamp NULL DEFAULT NULL,
  `datos_adicionales` json DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `presupuestos_codigo_unique` (`codigo`),
  KEY `presupuestos_responsable_id_foreign` (`responsable_id`),
  KEY `presupuestos_aprobado_por_foreign` (`aprobado_por`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `presupuestos`
--

INSERT INTO `presupuestos` (`id`, `codigo`, `agencia_id`, `responsable_id`, `estado`, `version`, `fecha_emision`, `fecha_vencimiento`, `observaciones`, `notas_internas`, `aprobado_por`, `aprobado_at`, `datos_adicionales`, `deleted_at`, `created_at`, `updated_at`) VALUES
(11, 'PRES-2026-0001', 21, 1, 'borrador', 1, '2026-05-13', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-06-01 19:54:15', '2026-06-01 19:54:15');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `presupuesto_historial`
--

DROP TABLE IF EXISTS `presupuesto_historial`;
CREATE TABLE IF NOT EXISTS `presupuesto_historial` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `presupuesto_id` bigint UNSIGNED NOT NULL,
  `estado_anterior` varchar(30) DEFAULT NULL,
  `estado_nuevo` varchar(30) NOT NULL,
  `comentario` text,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `presupuesto_historial_presupuesto_id_foreign` (`presupuesto_id`),
  KEY `presupuesto_historial_user_id_foreign` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `presupuesto_items`
--

DROP TABLE IF EXISTS `presupuesto_items`;
CREATE TABLE IF NOT EXISTS `presupuesto_items` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `presupuesto_id` bigint UNSIGNED NOT NULL,
  `mobiliario_id` bigint UNSIGNED DEFAULT NULL,
  `insumo_id` bigint UNSIGNED DEFAULT NULL,
  `sector_id` bigint UNSIGNED DEFAULT NULL,
  `cantidad` int UNSIGNED NOT NULL DEFAULT '1',
  `precio_unitario` decimal(12,2) DEFAULT NULL,
  `descripcion_override` varchar(191) DEFAULT NULL,
  `observaciones` text,
  `notas_manuales` text,
  `orden` smallint UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `presupuesto_items_presupuesto_id_foreign` (`presupuesto_id`),
  KEY `presupuesto_items_mobiliario_id_foreign` (`mobiliario_id`),
  KEY `presupuesto_items_sector_id_foreign` (`sector_id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `presupuesto_items`
--

INSERT INTO `presupuesto_items` (`id`, `presupuesto_id`, `mobiliario_id`, `insumo_id`, `sector_id`, `cantidad`, `precio_unitario`, `descripcion_override`, `observaciones`, `notas_manuales`, `orden`, `created_at`, `updated_at`) VALUES
(11, 11, 5, NULL, 3, 1, NULL, NULL, NULL, NULL, 1, '2026-06-01 19:54:15', '2026-06-01 19:54:15'),
(12, 11, 6, NULL, 3, 1, NULL, NULL, NULL, NULL, 2, '2026-06-01 19:54:15', '2026-06-01 19:54:15'),
(13, 11, 7, NULL, 3, 2, NULL, NULL, NULL, NULL, 3, '2026-06-01 19:54:15', '2026-06-01 19:54:15'),
(14, 11, 8, NULL, 4, 4, NULL, NULL, NULL, NULL, 4, '2026-06-01 19:54:15', '2026-06-01 19:54:15'),
(15, 11, 9, NULL, 4, 8, NULL, NULL, NULL, NULL, 5, '2026-06-01 19:54:15', '2026-06-01 19:54:15'),
(16, 11, 10, NULL, 4, 2, NULL, NULL, NULL, NULL, 6, '2026-06-01 19:54:15', '2026-06-01 19:54:15'),
(17, 11, 11, NULL, 4, 1, NULL, NULL, NULL, NULL, 7, '2026-06-01 19:54:15', '2026-06-01 19:54:15'),
(18, 11, 12, NULL, 4, 1, NULL, NULL, NULL, NULL, 8, '2026-06-01 19:54:15', '2026-06-01 19:54:15'),
(19, 11, 18, NULL, 1, 1, NULL, NULL, NULL, NULL, 9, '2026-06-01 19:54:15', '2026-06-01 19:54:15'),
(20, 11, 19, NULL, 1, 2, NULL, NULL, NULL, NULL, 10, '2026-06-01 19:54:16', '2026-06-01 19:54:16'),
(21, 11, 20, NULL, 1, 1, NULL, NULL, NULL, NULL, 11, '2026-06-01 19:54:16', '2026-06-01 19:54:16'),
(22, 11, 15, NULL, 1, 2, NULL, NULL, NULL, NULL, 12, '2026-06-01 19:54:16', '2026-06-01 19:54:16'),
(23, 11, 16, NULL, 1, 1, NULL, NULL, NULL, NULL, 13, '2026-06-01 19:54:16', '2026-06-01 19:54:16'),
(24, 11, 18, NULL, 2, 1, NULL, NULL, NULL, NULL, 14, '2026-06-01 19:54:16', '2026-06-01 19:54:16'),
(25, 11, 20, NULL, 2, 1, NULL, NULL, NULL, NULL, 15, '2026-06-01 19:54:16', '2026-06-01 19:54:16'),
(26, 11, 25, NULL, NULL, 1, NULL, NULL, NULL, NULL, 16, '2026-06-01 20:00:16', '2026-06-01 20:00:16');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `presupuesto_versiones`
--

DROP TABLE IF EXISTS `presupuesto_versiones`;
CREATE TABLE IF NOT EXISTS `presupuesto_versiones` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `presupuesto_id` bigint UNSIGNED NOT NULL,
  `numero_version` smallint UNSIGNED NOT NULL,
  `snapshot` json NOT NULL,
  `motivo_cambio` varchar(191) DEFAULT NULL,
  `creado_por` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `presupuesto_versiones_presupuesto_id_numero_version_unique` (`presupuesto_id`,`numero_version`),
  KEY `presupuesto_versiones_creado_por_foreign` (`creado_por`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

DROP TABLE IF EXISTS `proveedores`;
CREATE TABLE IF NOT EXISTS `proveedores` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `codigo` varchar(191) NOT NULL,
  `razon_social` varchar(191) NOT NULL,
  `nombre_comercial` varchar(191) DEFAULT NULL,
  `cuit` varchar(191) DEFAULT NULL,
  `condicion_iva` varchar(191) DEFAULT NULL,
  `direccion` varchar(191) DEFAULT NULL,
  `provincia_id` bigint UNSIGNED DEFAULT NULL,
  `ciudad_id` bigint UNSIGNED DEFAULT NULL,
  `telefono` varchar(191) DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `persona_contacto` varchar(191) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `observaciones` text,
  `condicion_pago` varchar(191) DEFAULT NULL,
  `lista_precio` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `proveedores_codigo_unique` (`codigo`)
) ENGINE=MyISAM AUTO_INCREMENT=69 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `proveedores`
--

INSERT INTO `proveedores` (`id`, `codigo`, `razon_social`, `nombre_comercial`, `cuit`, `condicion_iva`, `direccion`, `provincia_id`, `ciudad_id`, `telefono`, `email`, `persona_contacto`, `activo`, `observaciones`, `condicion_pago`, `lista_precio`, `created_at`, `updated_at`, `deleted_at`) VALUES
(3, '1021', 'VICAL S.A.', 'VICAL S.A.', '33-51845009-9', 'responsable_inscripto', 'MELIAN 3259', 1, 76, '011-45451200', NULL, NULL, 1, 'P/ mueble recepcion - P mueble luminado', '40', NULL, '2026-06-01 11:57:49', '2026-06-01 11:57:49', NULL),
(4, '1663', 'CORES SANDRA M.(AMBROSETTI)', 'CORES SANDRA M.(AMBROSETTI)', '27-16677398-4', 'responsable_inscripto', 'RUTA 205 - KM 95 500', 1, 77, '02227-494750', NULL, NULL, 1, 'Para fabricacion interna', '1', NULL, '2026-06-01 11:57:49', '2026-06-01 11:57:49', NULL),
(5, '593', 'BERTOLINI HNOS. S.R.L.', 'BERTOLINI HNOS. S.R.L.', '30-70772990-9', 'responsable_inscripto', 'LAVALLE 1269', 21, 78, '03471-424086', NULL, NULL, 1, 'Cinta ,Strech', '41', NULL, '2026-06-01 11:57:49', '2026-06-01 11:57:49', NULL),
(6, '1610', 'TAPICEL S.A.', 'TAPICEL S.A.', '30-70810439-2', 'responsable_inscripto', 'CAMARONES 1950', 21, 79, '011-45844033', NULL, NULL, 1, NULL, '31', NULL, '2026-06-01 11:57:49', '2026-06-01 11:57:49', NULL),
(7, '1412', 'LURASCHI RICARDO GILBERTO', 'LURASCHI RICARDO GILBERTO', '20-13750756-1', 'responsable_inscripto', 'LAPRIDA 1477', 21, 80, NULL, NULL, NULL, 1, 'Servicio metalurgico', '7', NULL, '2026-06-01 11:57:49', '2026-06-01 11:57:49', NULL),
(8, '1859', 'FRISSOLO LAUTARO MATIAS', 'XOGO', '20-37077747-1', 'monotributista', 'JOSE HERNANDEZ 827', 21, 78, NULL, NULL, NULL, 1, 'Servicio metalurgico', '2', NULL, '2026-06-01 11:57:49', '2026-06-01 11:57:49', NULL),
(9, '1448', 'VERDICHIO MALVINA SANDRA', 'VERDICHIO MALVINA SANDRA', '23-21417365-4', 'responsable_inscripto', 'PRIMERA JUNTA 1606', 21, 81, '03471-425929', NULL, NULL, 1, 'Material', '52', NULL, '2026-06-01 11:57:49', '2026-06-01 11:57:49', NULL),
(10, '2070', 'LURASCHI ALEJANDRO', 'LURASCHI ALEJANDRO', '23-35250834-9', 'monotributista', 'MOLINA 1412', 21, 80, NULL, NULL, NULL, 1, 'Servicio metalurgico', '6', NULL, '2026-06-01 11:57:49', '2026-06-01 11:57:49', NULL),
(11, '2042', 'PACIARONI BRUNO DANIEL', 'PACIARONI BRUNO DANIEL', '23-39854717-9', 'responsable_inscripto', 'LAPRIDA 1570', 21, 80, NULL, NULL, NULL, 1, 'Material', '27', NULL, '2026-06-01 11:57:49', '2026-06-01 11:57:49', NULL),
(12, '1233', 'DOMISSI HERMANOS S.R.L.', 'DOMISSI HERMANOS S.R.L.', '30-59423838-5', 'responsable_inscripto', 'TUCUMAN 1690', 21, 82, '03464-42209/426661', NULL, NULL, 1, 'Material', '10', NULL, '2026-06-01 11:57:49', '2026-06-01 11:57:49', NULL),
(13, '1197', 'HIERROMAS CDG S.R.L.', 'HIERROMAS CDG S.R.L.', '30-70905249-3', 'responsable_inscripto', 'MORENO 727', 21, 78, '03471-423939', NULL, NULL, 1, 'Material', '28', NULL, '2026-06-01 11:57:49', '2026-06-01 11:57:49', NULL),
(14, '2091', 'ACEROS CUFER  S. A.', 'ACEROS CUFER  S. A.', '33-63966545-9', 'responsable_inscripto', 'AVDA CIRCUNVALACION 4050', 21, 83, NULL, NULL, NULL, 1, 'Material', '2', NULL, '2026-06-01 11:57:49', '2026-06-01 11:57:49', NULL),
(15, '1029', 'RIVAROLA JULIO M. (CIMA FJ)', 'RIVAROLA JULIO M. (CIMA FJ)', '20-27178714-7', 'responsable_inscripto', 'AVDA. EVA PERON 3235', 1, 84, '011-46978785/153-5364073', NULL, NULL, 1, NULL, '6', NULL, '2026-06-01 11:57:49', '2026-06-01 11:57:49', NULL),
(16, '1289', 'LOVATO PABLO MARTIN', 'LOVATO PABLO MARTIN', '20-28172228-0', 'responsable_inscripto', 'OCAMPO 1349', 21, 78, '03471-425568', NULL, NULL, 1, NULL, '17', NULL, '2026-06-01 11:57:49', '2026-06-01 11:57:49', NULL),
(17, '1647', 'DI PAOLA LUCIANO', 'DI PAOLA LUCIANO', '20-37402423-0', 'responsable_inscripto', 'AV.SANTA FE 1536', 21, 78, '03471-426187/15618396', NULL, NULL, 1, NULL, '36', NULL, '2026-06-01 11:57:49', '2026-06-01 11:57:49', NULL),
(18, '635', 'HERRAJES NORTE S.R.L.', 'HERRAJES NORTE S.R.L.', '30-70832534-8', 'responsable_inscripto', 'OCAMPO 395', 21, 78, '03471-424107-423635', NULL, NULL, 1, NULL, '40', NULL, '2026-06-01 11:57:49', '2026-06-01 11:57:49', NULL),
(19, '1033', 'LASER HOUSE S.A.', 'LASER HOUSE S.A.', '30-70854065-6', 'responsable_inscripto', 'ALMAFUERTE 1038', 21, 66, '0341-4377080', NULL, NULL, 1, 'Insumos para Atril ficha tecnica de autos', '1', NULL, '2026-06-01 11:57:49', '2026-06-01 11:57:49', NULL),
(20, '2062', 'STAAL S.A.S.', 'STAAL S.A.S.', '30-71682994-0', 'responsable_inscripto', 'AU. CORDOBA-ROSARIO- KM 700- L', 6, 85, NULL, NULL, NULL, 1, NULL, '1', NULL, '2026-06-01 11:57:49', '2026-06-01 11:57:49', NULL),
(21, '1753', 'COMERCIAL CAB S.R.L.', 'COMERCIAL CAB S.R.L.', '30-71697510-6', 'responsable_inscripto', 'BUENOS AIRES 2119', 21, 66, '03414816901', NULL, NULL, 1, NULL, '39', NULL, '2026-06-01 11:57:49', '2026-06-01 11:57:49', NULL),
(22, '1858', 'GRANDIS FERRETERIA S.A.', 'GRANDIS FERRETERIA S.A.', '30-71837981-0', 'responsable_inscripto', 'MORENO 789', 21, 78, NULL, NULL, NULL, 1, NULL, '32', NULL, '2026-06-01 11:57:49', '2026-06-01 11:57:49', NULL),
(23, '2082', 'HERRAJES TRONADOR', 'HERRAJES TRONADOR', '30-71866022-6', 'responsable_inscripto', 'BALBIN RICARDO 3698', 25, 86, NULL, NULL, NULL, 1, NULL, '5', NULL, '2026-06-01 11:57:49', '2026-06-01 11:57:49', NULL),
(24, '2099', 'TORNILLO ALVEAR S. R. L.', 'TORNILLO ALVEAR S. R. L.', '30-71154356-9', 'responsable_inscripto', 'RUTA NRO. 21 KM 7 52', 21, 87, NULL, NULL, NULL, 1, 'Tornillo autoperforante para madera', '1', NULL, '2026-06-01 11:57:49', '2026-06-01 11:57:49', NULL),
(25, '588', 'ROZZI JAVIER ALBERTO', 'ROZZI JAVIER ALBERTO', '20-21417409-0', 'responsable_inscripto', 'NECOCHEA 790', 21, 78, '03471-428257', NULL, NULL, 1, 'Tirad LED para muebles', '75', NULL, '2026-06-01 11:57:49', '2026-06-01 11:57:49', NULL),
(26, '1796', 'MAGGI VIRGINIA (CASA MAGGI)', 'MAGGI VIRGINIA (CASA MAGGI)', '27-26349312-0', 'responsable_inscripto', 'PELLEGRINI 340', 21, 78, NULL, NULL, NULL, 1, 'Tirad LED para muebles', '21', NULL, '2026-06-01 11:57:49', '2026-06-01 11:57:49', NULL),
(27, '2041', 'FORMICOLOR SOCIEDAD ANONIMA', 'FORMICOLOR SOCIEDAD ANONIMA', '30-64154657-3', 'responsable_inscripto', 'HIPOLITO YRIGOYEN 2421', 1, 88, NULL, NULL, NULL, 1, NULL, '1', NULL, '2026-06-01 11:57:49', '2026-06-01 11:57:49', NULL),
(28, '329', 'E. SLUTZKY S.A.', 'E. SLUTZKY S.A.', '30-53741886-5', 'responsable_inscripto', 'ALEM 387', 21, 78, '03471-422357/423266', 'slutzkysa@gmail.com', NULL, 1, NULL, '45', NULL, '2026-06-01 11:57:49', '2026-06-01 11:57:49', NULL),
(29, '1952', 'MULE ERNESTO Y MULE ORLANDO', 'MULE ERNESTO Y MULE ORLANDO', '30-56266595-8', 'responsable_inscripto', 'DORREGO 860', 21, 78, NULL, NULL, NULL, 1, NULL, '20', NULL, '2026-06-01 11:57:49', '2026-06-01 11:57:49', NULL),
(30, '1419', 'DIELFE S.R.L.', 'DIELFE S.R.L.', '30-63990755-0', 'responsable_inscripto', 'AV.RAUL ALFONSIN 3215', 21, 89, '0341-3178600', NULL, NULL, 1, NULL, '54', NULL, '2026-06-01 11:57:49', '2026-06-01 11:57:49', NULL),
(31, '1544', 'SOLDINI STOCK S.A.', 'SOLDINI STOCK S.A.', '30-64270678-7', 'responsable_inscripto', 'ESTANISLAO LOPEZ 107', 21, 90, '0341-156161650', NULL, 'VENDEDOR: FRANCO', 1, NULL, '31', NULL, '2026-06-01 11:57:49', '2026-06-01 11:57:49', NULL),
(32, '1290', 'MARASCA MADERAS S.A.', 'MARASCA MADERAS S.A.', '30-70849636-3', 'responsable_inscripto', 'OROÑO 897', 21, 78, '03471-422210/421461/421463', NULL, NULL, 1, NULL, '15', NULL, '2026-06-01 11:57:49', '2026-06-01 11:57:49', NULL),
(33, '892', 'JOSE LONGO Y CIA. S.A.', 'JOSE LONGO Y CIA. S.A.', '30-71035788-5', 'responsable_inscripto', 'ALEM 610', 21, 78, '03471-423445 - 155-63232', NULL, 'Jose', 1, NULL, '66', NULL, '2026-06-01 11:57:49', '2026-06-01 11:57:49', NULL),
(34, '2055', 'INDUSTRIAS EL TUCAN S. R. L.', 'INDUSTRIAS EL TUCAN S. R. L.', '30-71893460-1', 'responsable_inscripto', 'LAPRIDA 910', 21, 78, NULL, NULL, NULL, 1, 'El herraje es una planchuela. Producto unico', '1', NULL, '2026-06-01 11:57:49', '2026-06-01 11:57:49', NULL),
(35, '2095', 'MARAJOR DE GRILLI ROMINA', 'MARAJOR DE GRILLI ROMINA', '27-32992471-3', 'responsable_inscripto', 'BACACAY 5100', 25, 91, NULL, NULL, NULL, 1, 'Polietileno de alto impacto P/Laminado- Herramiento: Refilador.', '4', NULL, '2026-06-01 11:57:49', '2026-06-01 11:57:49', NULL),
(36, '2025', 'ESTRULAM SA', 'ESTRULAM SA', '30-57718545-6', 'responsable_inscripto', 'BRIG GRAL JUAN MANUEL DE ROSAS', 1, 92, NULL, NULL, NULL, 1, 'Polietileno de alto impacto P/Laminado- Herramiento: Refilador.', '1', NULL, '2026-06-01 11:57:49', '2026-06-01 11:57:49', NULL),
(37, '583', 'CENTRO S.A. (KARIKAL)', 'CENTRO S.A. (KARIKAL)', '30-57900711-3', 'responsable_inscripto', 'BEDOYA 934', 6, 22, '0351-4741717', NULL, NULL, 1, 'Polietileno de alto impacto P/Laminado- Herramiento: Refilador.', '10', NULL, '2026-06-01 11:57:49', '2026-06-01 11:57:49', NULL),
(38, '1276', 'DAF INSUMOS S.R.L.-VINILGRAPH', 'DAF INSUMOS S.R.L.-VINILGRAPH', '30-71481746-5', 'responsable_inscripto', 'SALTA 2429', 21, 66, '0341-4381145', NULL, NULL, 1, 'Placa de plastico de 2 mm de esperar para revestir los muebles P/Laminado', '1', NULL, '2026-06-01 11:57:49', '2026-06-01 11:57:49', NULL),
(39, '1512', 'DAYPLAS S.A.', 'DAYPLAS S.A.', '33-58249873-9', 'responsable_inscripto', 'AV.COMB.DE MALVINAS 3197', 21, 79, '011-45244500/45230275', NULL, NULL, 1, 'Placa de plastico de 2 mm de esperar para revestir los muebles P/Laminado', '1', NULL, '2026-06-01 11:57:49', '2026-06-01 11:57:49', NULL),
(40, '1641', 'LINIZIO S.A.', 'LINIZIO S.A.', '33-71543182-9', 'responsable_inscripto', 'AV.INDEPENDENCIA 2723', 21, 79, '11-22003822', NULL, NULL, 1, NULL, '8', NULL, '2026-06-01 11:57:49', '2026-06-01 11:57:49', NULL),
(41, '2056', 'SISTO PABLO EMILIANO (FEBAU)', 'FEBAU HERRAJES', '20-36536578-5', 'responsable_inscripto', 'SANTIAGO DEL ESTERO 1539', 1, 84, NULL, NULL, NULL, 1, 'Caño para Peugeot', '3', NULL, '2026-06-01 11:57:49', '2026-06-01 11:57:49', NULL),
(42, '2049', 'ALUMINIO ROSALUM S A', 'ALUMINIO ROSALUM S A', '30-69537152-3', 'responsable_inscripto', 'BV.ORO&#209;O 4824', 21, 83, NULL, NULL, NULL, 1, NULL, '1', NULL, '2026-06-01 11:57:49', '2026-06-01 11:57:49', NULL),
(43, '39', 'PINTUR. S.MARTIN DE PRETINI J.', 'PINTUR. S.MARTIN DE PRETINI J.', '27-05904219-5', 'responsable_inscripto', 'SAN MARTIN 21', 21, 78, '03471-424090', NULL, NULL, 1, 'P Laqueado de muebles', '40', NULL, '2026-06-01 11:57:49', '2026-06-01 11:57:49', NULL),
(44, '717', 'LUVILOR S.R.L.', 'LUVILOR S.R.L.', '30-51881254-4', 'responsable_inscripto', 'LAVALLE 1096', 1, 93, '011-42598923 (M.Rosa)', NULL, NULL, 1, 'P Laqueado de muebles', '37', NULL, '2026-06-01 11:57:49', '2026-06-01 11:57:49', NULL),
(45, '1578', 'CP SA - CAÑADA PINTURERIAS', 'CP SA - CAÑADA PINTURERIAS', '30-55528223-7', 'responsable_inscripto', 'LAVALLE 713', 21, 78, '03471-422300/424763', NULL, NULL, 1, 'P Laqueado de muebles', '22', NULL, '2026-06-01 11:57:49', '2026-06-01 11:57:49', NULL),
(46, '650', 'QUIMICA PETROSIL S.R.L.', 'QUIMICA PETROSIL S.R.L.', '30-67384453-3', 'responsable_inscripto', NULL, 21, NULL, '03471-425938', NULL, NULL, 1, 'P Laqueado de muebles', NULL, NULL, '2026-06-01 11:57:49', '2026-06-01 11:57:49', NULL),
(47, '1114', 'LACAPOL S.A.', 'LACAPOL S.A.', '30-70892237-0', 'responsable_inscripto', 'CONCEPCION ARENAL 2412', 1, 94, '011-44590302/5155/6202', NULL, NULL, 1, 'P Laqueado de muebles', '58', NULL, '2026-06-01 11:57:49', '2026-06-01 11:57:49', NULL),
(48, '1516', 'SINTETICOS Y LACAS S.A.', 'SINTETICOS Y LACAS S.A.', '30-71504028-6', 'responsable_inscripto', 'RUTA PROV.10 KM.133.5', 6, 95, '03532-493511', NULL, NULL, 1, 'P Laqueado de muebles', '80', NULL, '2026-06-01 11:57:49', '2026-06-01 11:57:49', NULL),
(49, '1871', 'SORIA RAMON ENRIQUE', 'TAPICERIA RES', '20-16253007-1', 'responsable_inscripto', 'ITALIA 1410', 1, 88, NULL, NULL, NULL, 1, 'Servicio de tapiceria', '3', NULL, '2026-06-01 11:57:49', '2026-06-01 11:57:49', NULL),
(50, '1720', 'COULIN FERNANDO ANIBAL', 'COULIN FERNANDO ANIBAL', '20-32898729-6', 'responsable_inscripto', 'BV. CENTENARIO 1478', 21, 78, NULL, NULL, NULL, 1, 'Separadores p Fabricacion interna', '7', NULL, '2026-06-01 11:57:49', '2026-06-01 11:57:49', NULL),
(51, '1607', 'SUDEX ARGENTINA SRL (NOVEC)', 'SUDEX ARGENTINA SRL (NOVEC)', '30-70804169-2', 'responsable_inscripto', 'JOSE HERNANDEZ 1485', 1, 6, '291-4888222/4888155', NULL, NULL, 1, 'Sillas Terminadas', '26', NULL, '2026-06-01 11:57:49', '2026-06-01 11:57:49', NULL),
(52, '1026', 'PORTANTINO S.A.', 'PORTANTINO S.A.', '30-70816592-8', 'responsable_inscripto', 'OZANAM 1369', 1, 84, '011-46968844', 'info@portantino.com.ar', NULL, 1, 'Sillas Terminadas', '41', NULL, '2026-06-01 11:57:49', '2026-06-01 11:57:49', NULL),
(53, '1180', 'JAIM S.R.L.', 'JAIM S.R.L.', '30-71204303-9', 'responsable_inscripto', 'FERNANDEZ D OLIVEIRA 3660', 1, 96, '011-54312500-156-6902500', 'deco@jaimsrl.com.ar', NULL, 1, 'Base de silla para fabricacion interna', '13', NULL, '2026-06-01 11:57:49', '2026-06-01 11:57:49', NULL),
(54, '1682', 'CARDOZO-ESPLUGAS F-DIAZ(ALRIO)', 'CARDOZO-ESPLUGAS F-DIAZ(ALRIO)', '30-71237304-7', 'responsable_inscripto', 'V.LOPEZ Y PLANES 8450', 1, 97, NULL, NULL, NULL, 1, 'Casco de sillas para armar Para fabricacion interna', '3', NULL, '2026-06-01 11:57:49', '2026-06-01 11:57:49', NULL),
(55, '1973', 'VICENTE PLASTICOS S.R.L.', 'VICENTE PLASTICOS S.R.L.', '30-71352420-0', 'responsable_inscripto', 'MALVINAS ARGENTINAS 702', 6, 98, NULL, NULL, NULL, 1, 'Casco de sillas (Fibra de vidrio) para armar Para fabricacion interna', '47', NULL, '2026-06-01 11:57:49', '2026-06-01 11:57:49', NULL),
(56, '1360', 'INDAR EQUIPAMIENTOS SRL', 'INDAR EQUIPAMIENTOS SRL', '33-70830538-9', 'responsable_inscripto', 'MARCO POLO 4755', 1, 96, '011-47502147/47150650/47', NULL, NULL, 1, 'Sillas Terminadas', '48', NULL, '2026-06-01 11:57:49', '2026-06-01 11:57:49', NULL),
(57, '1976', 'MONDELLI LEONARDO CHRISTIAN - LEO PATAS', 'MONDELLI LEONARDO CHRISTIAN', '20-22910749-7', 'responsable_inscripto', 'ORTIZ DE ROSAS 861', 1, 84, NULL, NULL, NULL, 1, 'Pata para sillones', '3', NULL, '2026-06-01 11:57:49', '2026-06-01 11:57:49', NULL),
(58, '2000', 'SACHETTI LEANDRO ADRIAN', 'SACHETTI LEANDRO ADRIAN', '20-32146122-1', 'monotributista', 'AVENIDA MITRE 5146 DPTO 9', 1, 96, NULL, NULL, NULL, 1, 'Vende sillon. Se le envia la tela de color para tapizar según pedido de la Marca', '5', NULL, '2026-06-01 11:57:49', '2026-06-01 11:57:49', NULL),
(59, '1870', 'SAVORGNAN INES CRISTINA', 'OPCION UNO', '27-13638625-0', 'responsable_inscripto', '62 - CHIVILCOY 6031 - PB - DPT', 1, 99, NULL, NULL, NULL, 1, 'Terminadas', '1', NULL, '2026-06-01 11:57:49', '2026-06-01 11:57:49', NULL),
(60, '1857', 'PEGATEL SOC DE HECHO DE SALOMO', 'PEGATEL SOC DE HECHO DE SALOMO', '30-71115423-6', 'responsable_inscripto', 'JOSE C. PAZ 5851', 1, 99, NULL, NULL, NULL, 1, 'Servicio de bondiado. Se envia la espuma y la tela. Una vez finlizado el servicio, se manda ese material al tapicero', '5', NULL, '2026-06-01 11:57:49', '2026-06-01 11:57:49', NULL),
(61, '1449', 'ADHYSER S.R.L.', 'ADHYSER S.R.L.', '30-70978224-6', 'responsable_inscripto', 'EUGENIO GARZON 5939', 21, 79, '011-46879939', NULL, NULL, 1, 'P/pegadora de filos', '3', NULL, '2026-06-01 11:57:49', '2026-06-01 11:57:49', NULL),
(62, '1830', 'TELAS OÑATE S.R.L.', 'TELAS OÑATE S.R.L.', '30-57529958-6', 'responsable_inscripto', 'VALENTIN VIRASORO 1769', 25, 100, NULL, 'info@telasonate.com.ar', NULL, 1, 'Cuerina y tela', '20', NULL, '2026-06-01 11:57:49', '2026-06-01 11:57:49', NULL),
(63, '1460', 'PLASTICOS TEXTIL S R L', 'PLASTICOS TEXTIL S R L', '30-70839722-5', 'responsable_inscripto', '13 4304', 1, 101, '011-4381-5331/0625', NULL, NULL, 1, 'Cuerina y tela', '103', NULL, '2026-06-01 11:57:49', '2026-06-01 11:57:49', NULL),
(64, '1974', 'GREVY S.R.L.', 'GREVY S.R.L.', '30-70840280-6', 'responsable_inscripto', 'AV. LA PLATA 1413', 25, 91, NULL, NULL, NULL, 1, 'Cuerina y tela', '2', NULL, '2026-06-01 11:57:49', '2026-06-01 11:57:49', NULL),
(65, '1517', 'VIDR.SAMPAOLESI DIV.ALUM SRL', 'VIDR.SAMPAOLESI DIV.ALUM SRL', '30-70999192-9', 'responsable_inscripto', 'J.B.ALBERDI 1185', 21, 80, '03471-440588', NULL, NULL, 1, NULL, '39', NULL, '2026-06-01 11:57:49', '2026-06-01 11:57:49', NULL),
(66, '1934', 'FORESI SEBASTIAN', 'FORESI SEBASTIAN', '20-24863257-8', 'monotributista', 'AYACUCHO 175', 21, 78, NULL, NULL, NULL, 1, NULL, '3', NULL, '2026-06-01 11:57:49', '2026-06-01 11:57:49', NULL),
(67, '2073', 'FIEDCZUK PALAVECINO PILAR MELI', 'FIEDCZUK PALAVECINO PILAR MELI', '27-46337824-5', 'monotributista', 'CALLE 10 5501', 1, 101, NULL, NULL, NULL, 1, NULL, '1', NULL, '2026-06-01 11:57:49', '2026-06-01 11:57:49', NULL),
(68, '1016', 'JAVIER GINACA Y ASOC.', 'JAVIER GINACA Y ASOC.', '30-70768124-8', 'responsable_inscripto', NULL, 1, NULL, '011-48362224', NULL, NULL, 1, NULL, NULL, NULL, '2026-06-01 11:57:49', '2026-06-01 11:57:49', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor_rubro`
--

DROP TABLE IF EXISTS `proveedor_rubro`;
CREATE TABLE IF NOT EXISTS `proveedor_rubro` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `proveedor_id` bigint UNSIGNED NOT NULL,
  `rubro_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `proveedor_rubro_proveedor_id_rubro_id_unique` (`proveedor_id`,`rubro_id`),
  KEY `proveedor_rubro_rubro_id_foreign` (`rubro_id`)
) ENGINE=MyISAM AUTO_INCREMENT=73 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `proveedor_rubro`
--

INSERT INTO `proveedor_rubro` (`id`, `proveedor_id`, `rubro_id`, `created_at`, `updated_at`) VALUES
(3, 3, 3, NULL, NULL),
(4, 4, 4, NULL, NULL),
(5, 5, 5, NULL, NULL),
(6, 6, 6, NULL, NULL),
(7, 7, 7, NULL, NULL),
(8, 8, 7, NULL, NULL),
(9, 9, 7, NULL, NULL),
(10, 10, 7, NULL, NULL),
(11, 11, 7, NULL, NULL),
(12, 12, 7, NULL, NULL),
(13, 13, 7, NULL, NULL),
(14, 14, 7, NULL, NULL),
(15, 15, 8, NULL, NULL),
(16, 16, 8, NULL, NULL),
(17, 17, 8, NULL, NULL),
(18, 18, 8, NULL, NULL),
(19, 19, 8, NULL, NULL),
(20, 20, 8, NULL, NULL),
(21, 21, 8, NULL, NULL),
(22, 22, 8, NULL, NULL),
(23, 23, 8, NULL, NULL),
(24, 24, 8, NULL, NULL),
(25, 25, 9, NULL, NULL),
(26, 26, 9, NULL, NULL),
(27, 27, 10, NULL, NULL),
(28, 28, 11, NULL, NULL),
(29, 29, 11, NULL, NULL),
(30, 30, 11, NULL, NULL),
(31, 30, 12, NULL, NULL),
(32, 31, 11, NULL, NULL),
(33, 31, 12, NULL, NULL),
(34, 32, 11, NULL, NULL),
(35, 33, 11, NULL, NULL),
(36, 34, 11, NULL, NULL),
(37, 34, 8, NULL, NULL),
(38, 35, 13, NULL, NULL),
(39, 36, 13, NULL, NULL),
(40, 37, 13, NULL, NULL),
(41, 38, 13, NULL, NULL),
(42, 39, 13, NULL, NULL),
(43, 40, 14, NULL, NULL),
(44, 41, 15, NULL, NULL),
(45, 42, 15, NULL, NULL),
(46, 43, 16, NULL, NULL),
(47, 44, 16, NULL, NULL),
(48, 45, 16, NULL, NULL),
(49, 46, 16, NULL, NULL),
(50, 47, 16, NULL, NULL),
(51, 48, 16, NULL, NULL),
(52, 49, 17, NULL, NULL),
(53, 50, 17, NULL, NULL),
(54, 51, 17, NULL, NULL),
(55, 52, 17, NULL, NULL),
(56, 53, 17, NULL, NULL),
(57, 54, 17, NULL, NULL),
(58, 55, 17, NULL, NULL),
(59, 56, 17, NULL, NULL),
(60, 57, 18, NULL, NULL),
(61, 58, 18, NULL, NULL),
(62, 59, 18, NULL, NULL),
(63, 60, 18, NULL, NULL),
(64, 61, 12, NULL, NULL),
(65, 61, 19, NULL, NULL),
(66, 62, 20, NULL, NULL),
(67, 63, 20, NULL, NULL),
(68, 64, 20, NULL, NULL),
(69, 65, 21, NULL, NULL),
(70, 66, 22, NULL, NULL),
(71, 67, 22, NULL, NULL),
(72, 68, 22, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `provincias`
--

DROP TABLE IF EXISTS `provincias`;
CREATE TABLE IF NOT EXISTS `provincias` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` varchar(191) NOT NULL,
  `codigo` varchar(191) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `provincias_nombre_unique` (`nombre`),
  UNIQUE KEY `provincias_codigo_unique` (`codigo`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `provincias`
--

INSERT INTO `provincias` (`id`, `nombre`, `codigo`) VALUES
(1, 'Buenos Aires', '06'),
(2, 'Catamarca', '10'),
(3, 'Chaco', '16'),
(4, 'Chubut', '20'),
(5, 'Ciudad Autónoma de Buenos Aires', '02'),
(6, 'Córdoba', '14'),
(7, 'Corrientes', '18'),
(8, 'Entre Ríos', '30'),
(9, 'Formosa', '22'),
(10, 'Jujuy', '38'),
(11, 'La Pampa', '42'),
(12, 'La Rioja', '46'),
(13, 'Mendoza', '50'),
(14, 'Misiones', '54'),
(15, 'Neuquén', '58'),
(16, 'Río Negro', '62'),
(17, 'Salta', '66'),
(18, 'San Juan', '70'),
(19, 'San Luis', '74'),
(20, 'Santa Cruz', '78'),
(21, 'Santa Fe', '82'),
(22, 'Santiago del Estero', '86'),
(23, 'Tierra del Fuego', '94'),
(24, 'Tucumán', '90'),
(25, 'CAPITAL', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proyectos`
--

DROP TABLE IF EXISTS `proyectos`;
CREATE TABLE IF NOT EXISTS `proyectos` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `codigo_interno` varchar(191) NOT NULL,
  `marca_id` bigint UNSIGNED DEFAULT NULL,
  `manual_pdf` json DEFAULT NULL,
  `estado` varchar(191) NOT NULL DEFAULT 'presupuestar',
  `fecha_inicio` date DEFAULT NULL,
  `fecha_entrega_estimada` date DEFAULT NULL,
  `fecha_entrega_real` date DEFAULT NULL,
  `observaciones` text,
  `timeline` json DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `proyectos_codigo_interno_unique` (`codigo_interno`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `proyectos`
--

INSERT INTO `proyectos` (`id`, `codigo_interno`, `marca_id`, `manual_pdf`, `estado`, `fecha_inicio`, `fecha_entrega_estimada`, `fecha_entrega_real`, `observaciones`, `timeline`, `deleted_at`, `created_at`, `updated_at`) VALUES
(7, 'CHY-26', 1, '[]', 'presupuestar', NULL, NULL, NULL, NULL, NULL, NULL, '2026-06-01 18:50:52', '2026-06-01 18:50:52');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proyecto_historial`
--

DROP TABLE IF EXISTS `proyecto_historial`;
CREATE TABLE IF NOT EXISTS `proyecto_historial` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `proyecto_id` bigint UNSIGNED NOT NULL,
  `estado_anterior` varchar(191) DEFAULT NULL,
  `estado_nuevo` varchar(191) NOT NULL,
  `observacion` text,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `proyecto_historial_proyecto_id_foreign` (`proyecto_id`),
  KEY `proyecto_historial_user_id_foreign` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proyecto_mobiliario`
--

DROP TABLE IF EXISTS `proyecto_mobiliario`;
CREATE TABLE IF NOT EXISTS `proyecto_mobiliario` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `proyecto_id` bigint UNSIGNED NOT NULL,
  `mobiliario_id` bigint UNSIGNED NOT NULL,
  `sector_id` bigint UNSIGNED DEFAULT NULL,
  `cantidad` int UNSIGNED NOT NULL DEFAULT '1',
  `observaciones` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `proyecto_mobiliario_proyecto_id_foreign` (`proyecto_id`),
  KEY `proyecto_mobiliario_mobiliario_id_foreign` (`mobiliario_id`),
  KEY `proyecto_mobiliario_sector_id_foreign` (`sector_id`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `proyecto_mobiliario`
--

INSERT INTO `proyecto_mobiliario` (`id`, `proyecto_id`, `mobiliario_id`, `sector_id`, `cantidad`, `observaciones`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, 1, NULL, '2026-05-20 13:45:25', '2026-05-20 13:45:25'),
(2, 1, 3, NULL, 2, NULL, '2026-05-20 13:45:25', '2026-05-20 13:45:25'),
(3, 1, 2, NULL, 1, NULL, '2026-05-20 13:45:25', '2026-05-20 13:45:25'),
(4, 4, 1, NULL, 1, NULL, '2026-05-22 11:35:11', '2026-05-22 11:35:11'),
(5, 4, 2, NULL, 1, NULL, '2026-05-22 11:35:11', '2026-05-22 11:35:11'),
(6, 4, 3, NULL, 1, NULL, '2026-05-22 11:35:11', '2026-05-22 11:35:11'),
(7, 5, 4, NULL, 1, NULL, '2026-05-26 17:12:25', '2026-05-26 17:12:25'),
(8, 5, 1, NULL, 1, NULL, '2026-05-26 17:12:25', '2026-05-26 17:12:25'),
(9, 5, 3, NULL, 1, NULL, '2026-05-26 17:12:25', '2026-05-26 17:12:25'),
(10, 5, 2, NULL, 1, NULL, '2026-05-26 17:12:25', '2026-05-26 17:12:25'),
(11, 7, 18, NULL, 1, NULL, '2026-06-01 18:50:52', '2026-06-01 18:50:52'),
(12, 7, 19, NULL, 1, NULL, '2026-06-01 18:50:52', '2026-06-01 18:50:52'),
(13, 7, 20, NULL, 1, NULL, '2026-06-01 18:50:52', '2026-06-01 18:50:52'),
(14, 7, 6, NULL, 1, NULL, '2026-06-01 18:50:52', '2026-06-01 18:50:52'),
(15, 7, 13, NULL, 1, NULL, '2026-06-01 18:50:52', '2026-06-01 18:50:52'),
(16, 7, 22, NULL, 1, NULL, '2026-06-01 18:50:52', '2026-06-01 18:50:52'),
(17, 7, 15, NULL, 1, NULL, '2026-06-01 18:50:52', '2026-06-01 18:50:52'),
(18, 7, 10, NULL, 1, NULL, '2026-06-01 18:50:52', '2026-06-01 18:50:52'),
(19, 7, 11, NULL, 1, NULL, '2026-06-01 18:50:52', '2026-06-01 18:50:52'),
(20, 7, 21, NULL, 1, NULL, '2026-06-01 18:50:52', '2026-06-01 18:50:52'),
(21, 7, 12, NULL, 1, NULL, '2026-06-01 18:50:52', '2026-06-01 18:50:52'),
(22, 7, 17, NULL, 1, NULL, '2026-06-01 18:50:52', '2026-06-01 18:50:52'),
(23, 7, 23, NULL, 1, NULL, '2026-06-01 18:50:52', '2026-06-01 18:50:52'),
(24, 7, 16, NULL, 1, NULL, '2026-06-01 18:50:52', '2026-06-01 18:50:52'),
(25, 7, 24, NULL, 1, NULL, '2026-06-01 18:50:52', '2026-06-01 18:50:52'),
(26, 7, 9, NULL, 1, NULL, '2026-06-01 18:50:52', '2026-06-01 18:50:52'),
(27, 7, 8, NULL, 1, NULL, '2026-06-01 18:50:52', '2026-06-01 18:50:52'),
(28, 7, 14, NULL, 1, NULL, '2026-06-01 18:50:52', '2026-06-01 18:50:52'),
(29, 7, 7, NULL, 1, NULL, '2026-06-01 18:50:52', '2026-06-01 18:50:52'),
(30, 7, 5, NULL, 1, NULL, '2026-06-01 18:50:52', '2026-06-01 18:50:52'),
(31, 7, 25, NULL, 1, NULL, '2026-06-01 19:59:59', '2026-06-01 19:59:59');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservas_stock`
--

DROP TABLE IF EXISTS `reservas_stock`;
CREATE TABLE IF NOT EXISTS `reservas_stock` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `presupuesto_id` bigint UNSIGNED NOT NULL,
  `insumo_id` bigint UNSIGNED NOT NULL,
  `cantidad_reservada` decimal(10,2) NOT NULL,
  `estado` enum('activa','liberada','consumida') NOT NULL DEFAULT 'activa',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reservas_stock_presupuesto_id_estado_index` (`presupuesto_id`,`estado`),
  KEY `reservas_stock_insumo_id_estado_index` (`insumo_id`,`estado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `guard_name` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Administrador', 'web', '2026-05-20 11:53:33', '2026-05-20 11:53:33'),
(2, 'Ventas', 'web', '2026-05-20 11:53:33', '2026-05-20 11:53:33'),
(3, 'Producción', 'web', '2026-05-20 11:53:33', '2026-05-20 11:53:33'),
(4, 'Depósito / Stock', 'web', '2026-05-20 11:53:33', '2026-05-20 11:53:33'),
(5, 'Solo lectura', 'web', '2026-05-20 11:53:34', '2026-05-20 11:53:34');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
CREATE TABLE IF NOT EXISTS `role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(1, 2),
(1, 5),
(2, 1),
(2, 2),
(2, 5),
(3, 1),
(3, 2),
(4, 1),
(4, 2),
(5, 1),
(6, 1),
(6, 2),
(6, 5),
(7, 1),
(7, 2),
(7, 5),
(8, 1),
(8, 2),
(9, 1),
(9, 2),
(10, 1),
(11, 1),
(11, 2),
(11, 3),
(11, 4),
(11, 5),
(12, 1),
(12, 2),
(12, 3),
(12, 4),
(12, 5),
(13, 1),
(13, 2),
(14, 1),
(14, 2),
(14, 3),
(15, 1),
(16, 1),
(16, 2),
(16, 3),
(16, 4),
(16, 5),
(17, 1),
(17, 2),
(17, 3),
(17, 4),
(17, 5),
(18, 1),
(18, 3),
(19, 1),
(19, 3),
(20, 1),
(21, 1),
(21, 2),
(21, 3),
(21, 5),
(22, 1),
(22, 2),
(22, 3),
(22, 5),
(23, 1),
(23, 3),
(24, 1),
(24, 3),
(25, 1),
(26, 1),
(26, 3),
(26, 4),
(26, 5),
(27, 1),
(27, 3),
(27, 4),
(27, 5),
(28, 1),
(28, 4),
(29, 1),
(29, 4),
(30, 1),
(31, 1),
(31, 5),
(32, 1),
(32, 5),
(33, 1),
(34, 1),
(35, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rubros`
--

DROP TABLE IF EXISTS `rubros`;
CREATE TABLE IF NOT EXISTS `rubros` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` varchar(191) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `rubros_nombre_unique` (`nombre`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `rubros`
--

INSERT INTO `rubros` (`id`, `nombre`, `activo`, `created_at`, `updated_at`) VALUES
(3, 'Acrilicos', 1, '2026-06-01 12:09:29', '2026-06-01 12:09:29'),
(4, 'Base de mesa', 1, '2026-06-01 12:09:29', '2026-06-01 12:09:29'),
(5, 'Embalaje', 1, '2026-06-01 12:09:29', '2026-06-01 12:09:29'),
(6, 'Espumas', 1, '2026-06-01 12:09:29', '2026-06-01 12:09:29'),
(7, 'Estructura de hierro', 1, '2026-06-01 12:09:29', '2026-06-01 12:09:29'),
(8, 'Herrajes', 1, '2026-06-01 12:09:29', '2026-06-01 12:09:29'),
(9, 'Iluminacion', 1, '2026-06-01 12:09:29', '2026-06-01 12:09:29'),
(10, 'Laminado', 1, '2026-06-01 12:09:29', '2026-06-01 12:09:29'),
(11, 'Madera', 1, '2026-06-01 12:09:29', '2026-06-01 12:09:29'),
(12, 'Tapacantos', 1, '2026-06-01 12:09:29', '2026-06-01 12:09:29'),
(13, 'PAI', 1, '2026-06-01 12:09:29', '2026-06-01 12:09:29'),
(14, 'Pasacables', 1, '2026-06-01 12:09:29', '2026-06-01 12:09:29'),
(15, 'Perfiles de aluminio', 1, '2026-06-01 12:09:29', '2026-06-01 12:09:29'),
(16, 'Pintura', 1, '2026-06-01 12:09:29', '2026-06-01 12:09:29'),
(17, 'Sillas', 1, '2026-06-01 12:09:29', '2026-06-01 12:09:29'),
(18, 'Sillones', 1, '2026-06-01 12:09:29', '2026-06-01 12:09:29'),
(19, 'Cola HOTMEL', 1, '2026-06-01 12:09:29', '2026-06-01 12:09:29'),
(20, 'Tapiceria', 1, '2026-06-01 12:09:29', '2026-06-01 12:09:29'),
(21, 'Vidrio', 1, '2026-06-01 12:09:29', '2026-06-01 12:09:29'),
(22, 'Vinilos', 1, '2026-06-01 12:09:29', '2026-06-01 12:09:29');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sectores`
--

DROP TABLE IF EXISTS `sectores`;
CREATE TABLE IF NOT EXISTS `sectores` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sectores_nombre_unique` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `sectores`
--

INSERT INTO `sectores` (`id`, `nombre`, `activo`, `created_at`, `updated_at`) VALUES
(1, 'POSTVENTA', 1, '2026-06-01 16:13:30', '2026-06-01 16:13:30'),
(2, 'REPUESTOS', 1, '2026-06-01 16:33:04', '2026-06-01 16:33:04'),
(3, 'SALON', 1, '2026-06-01 19:23:52', '2026-06-01 19:23:52'),
(4, 'VENDEDORES', 1, '2026-06-01 19:46:34', '2026-06-01 19:46:34');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sessions`
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(191) NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text,
  `payload` longtext NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('FWFybdMOtRLti98BNTTZxgMuwsBOwV8dsdXHxTs7', 1, '192.168.0.106', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', 'YTo3OntzOjY6Il90b2tlbiI7czo0MDoic2VHYVJHdTVMNUpjN1dXWTRFNWFDUUlMQ2xFNnh2OE9ic25RRVdtWSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTA6Imh0dHBzOi8vZ2VzdGlvbm1vYmlsaWFyaW8ubG9jYWwvYWRtaW4vcHJlc3VwdWVzdG9zIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjE3OiJwYXNzd29yZF9oYXNoX3dlYiI7czo2MDoiJDJ5JDEyJEsxdXpCb3JPb3JoZnhvMVJCalZpSHUuTjFyWUgxY1dZTHBwWm8zLjRmVHFoZnpacUZIOTJtIjtzOjg6ImZpbGFtZW50IjthOjA6e31zOjY6InRhYmxlcyI7YToxOntzOjIwOiJMaXN0SW5zdW1vc19wZXJfcGFnZSI7czoyOiI1MCI7fX0=', 1780344024),
('v8oPGD28vNAwvKHNXNLDge6Nba8AxD2ouu4iSWno', 6, '192.168.0.90', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiek5TazZraDViTEttRzVqUE4xZlg4ZkVNUU1HWlg3cEZ0cDhjdUtYUyI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NjtzOjE3OiJwYXNzd29yZF9oYXNoX3dlYiI7czo2MDoiJDJ5JDEyJEZGc2pObVRPclNKNHV0Mlp0LzJWdS5PRnd5b3c5L0JxYnk4RWI0NG1UTGZ6LmRwZjRsNnBxIjtzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czozODoiaHR0cHM6Ly9nZXN0aW9ubW9iaWxpYXJpb3MubG9jYWwvYWRtaW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1780343913);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `terceros`
--

DROP TABLE IF EXISTS `terceros`;
CREATE TABLE IF NOT EXISTS `terceros` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` varchar(191) NOT NULL,
  `tipo` enum('proveedor_material','servicio_externo') NOT NULL DEFAULT 'servicio_externo',
  `especialidad` varchar(191) DEFAULT NULL,
  `telefono` varchar(191) DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `contacto` varchar(191) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `observaciones` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `terceros`
--

INSERT INTO `terceros` (`id`, `nombre`, `tipo`, `especialidad`, `telefono`, `email`, `contacto`, `activo`, `observaciones`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Alejandro Luraschi', 'servicio_externo', 'Herreria', NULL, NULL, 'Alejandro', 1, 'Laprida 1490 - Galpon - Correa ', '2026-05-27 12:23:15', '2026-05-27 14:26:27', NULL),
(2, 'Tito Brasca', 'servicio_externo', 'Pintura', NULL, NULL, 'Tito', 1, 'Capdevila 423 - Galpon', '2026-05-27 12:23:45', '2026-05-27 14:24:45', NULL),
(3, 'Hierromas', 'proveedor_material', 'Corte y plegado de hierro', '3467 - 641154', NULL, 'Mateo', 1, 'Area Industrial ', '2026-05-27 12:24:18', '2026-05-27 14:27:02', NULL),
(4, 'Angel Menoti', 'servicio_externo', 'Pintura Madera', NULL, NULL, 'Angel', 1, '24 de Septiembre 1533 - Casa - Correa', '2026-05-27 14:28:30', '2026-05-27 14:28:30', NULL),
(5, 'Sampaolesi Vidrios', 'servicio_externo', 'Corte de Vidrio', NULL, NULL, 'Pamela', 1, NULL, '2026-05-27 15:22:56', '2026-05-27 15:22:56', NULL),
(6, 'Ramon', 'servicio_externo', 'Tapizado', NULL, NULL, NULL, 1, NULL, '2026-06-01 19:26:36', '2026-06-01 19:26:36', NULL),
(7, 'Hernan', 'servicio_externo', 'Tapizado', NULL, NULL, NULL, 1, 'Correa', '2026-06-01 19:26:49', '2026-06-01 19:26:49', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_proceso_externo`
--

DROP TABLE IF EXISTS `tipos_proceso_externo`;
CREATE TABLE IF NOT EXISTS `tipos_proceso_externo` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` varchar(191) NOT NULL,
  `descripcion` varchar(191) DEFAULT NULL,
  `color` varchar(191) NOT NULL DEFAULT 'gray',
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `tipos_proceso_externo`
--

INSERT INTO `tipos_proceso_externo` (`id`, `nombre`, `descripcion`, `color`, `activo`, `created_at`, `updated_at`) VALUES
(1, 'Herreria', NULL, 'gray', 1, '2026-05-27 12:25:44', '2026-05-27 12:25:44'),
(2, 'PIntura', NULL, 'danger', 1, '2026-05-27 12:25:54', '2026-05-27 12:25:54'),
(3, 'Vidrieria', NULL, 'info', 1, '2026-05-27 12:26:06', '2026-05-27 12:26:06'),
(4, 'Tapizado', NULL, 'success', 1, '2026-05-27 12:26:16', '2026-05-27 12:26:16'),
(5, 'Corte y Plegado Hierro', NULL, 'gray', 1, '2026-05-27 12:26:51', '2026-05-27 12:26:51');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_silla`
--

DROP TABLE IF EXISTS `tipos_silla`;
CREATE TABLE IF NOT EXISTS `tipos_silla` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` varchar(191) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tipos_silla_nombre_unique` (`nombre`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `tipos_silla`
--

INSERT INTO `tipos_silla` (`id`, `nombre`, `activo`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Silla de Vendedor', 1, '2026-05-28 11:10:07', '2026-05-28 11:10:07', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `unidades_medida`
--

DROP TABLE IF EXISTS `unidades_medida`;
CREATE TABLE IF NOT EXISTS `unidades_medida` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` varchar(191) NOT NULL,
  `abreviatura` varchar(191) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unidades_medida_abreviatura_unique` (`abreviatura`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `unidades_medida`
--

INSERT INTO `unidades_medida` (`id`, `nombre`, `abreviatura`, `activo`, `created_at`, `updated_at`) VALUES
(1, 'Metro Lineal', 'm', 1, '2026-05-20 11:53:34', '2026-05-20 11:53:34'),
(2, 'Centímetro', 'cm', 1, '2026-05-20 11:53:34', '2026-05-20 11:53:34'),
(3, 'Milímetro', 'mm', 1, '2026-05-20 11:53:34', '2026-05-20 11:53:34'),
(4, 'Metro cuadrado', 'm²', 1, '2026-05-20 11:53:34', '2026-05-20 11:53:34'),
(5, 'Kilogramo', 'kg', 1, '2026-05-20 11:53:34', '2026-05-20 11:53:34'),
(6, 'Gramo', 'g', 1, '2026-05-20 11:53:34', '2026-05-20 11:53:34'),
(7, 'Litro', 'L', 1, '2026-05-20 11:53:34', '2026-05-20 11:53:34'),
(8, 'Unidad', 'u', 1, '2026-05-20 11:53:34', '2026-05-20 11:53:34'),
(9, 'Par', 'par', 1, '2026-05-20 11:53:34', '2026-05-20 11:53:34'),
(10, 'Juego', 'juego', 1, '2026-05-20 11:53:34', '2026-05-20 11:53:34'),
(11, 'Rollo', 'rollo', 1, '2026-05-20 11:53:34', '2026-05-20 11:53:34'),
(12, 'Plancha', 'plancha', 1, '2026-05-20 11:53:34', '2026-05-20 11:53:34');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `username` varchar(191) DEFAULT NULL,
  `email` varchar(191) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_username_unique` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `email_verified_at`, `password`, `activo`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Administrador', 'Administrador', 'admin@gestionmobiliario.com', NULL, '$2y$12$K1uzBorOorhfxo1RBjViHu.N1rYH1cWYLppZo3.4fTqhfzZqFH92m', 1, 'QDbj1iOmzFxPBfsObBvvhOi7P7ADCp4akOnY5Blrb5addPEKpvvsQ8i1VSA5', '2026-05-20 11:53:34', '2026-05-20 11:53:34'),
(2, 'Usuario Ventas', NULL, 'ventas@gestionmobiliario.com', NULL, '$2y$12$LwfMiZ/oo2hNj0jQJOXRdeV97HFWLM8SoXqZh2nv8GXkT/vl40/fe', 1, NULL, '2026-05-20 11:53:34', '2026-05-20 11:53:34'),
(3, 'Usuario Producción', NULL, 'produccion@gestionmobiliario.com', NULL, '$2y$12$.ShTPuoa3WS6l5FaEdMiie0b5zsh2807coAfgVhvhUxtiYFA06izW', 1, NULL, '2026-05-20 11:53:34', '2026-05-20 11:53:34'),
(4, 'Facundo', 'Facu', 'facundo@pierantoni.com.ar', NULL, '$2y$12$3j5ZGlh2RX3zoFDkNIbPgerPNmvQl.3vw7WINR1nFTW0aMroT2wUa', 1, NULL, '2026-05-27 12:20:00', '2026-05-27 12:20:00'),
(5, 'Sergio', 'Sergio', 'sergio@pierantoni.com.ar', NULL, '$2y$12$qvd.gLj2QZWX7rPDJQCXnOaJBAinHNsroEUVqGtabq1mbyvoNkCAa', 1, NULL, '2026-05-27 12:20:24', '2026-05-27 12:20:24'),
(6, 'Rodrigo Botta', 'Rodrigo', 'rodrigo@pierantoni.com.ar', NULL, '$2y$12$FFsjNmTOrSJ4ut2Zt/2Vu.OFwyow9/Bqby8Eb44mTLfz.dpf4l6pq', 1, 'so5BV0jQBsIEKLIrpzAQdaR6LzBtKQx5beWdjBvdm9WzOBBLziewV3XodZ3d', '2026-05-27 15:55:26', '2026-05-27 15:55:26');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `presupuestos`
--
ALTER TABLE `presupuestos`
  ADD CONSTRAINT `presupuestos_aprobado_por_foreign` FOREIGN KEY (`aprobado_por`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `presupuestos_responsable_id_foreign` FOREIGN KEY (`responsable_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT;

--
-- Filtros para la tabla `presupuesto_historial`
--
ALTER TABLE `presupuesto_historial`
  ADD CONSTRAINT `presupuesto_historial_presupuesto_id_foreign` FOREIGN KEY (`presupuesto_id`) REFERENCES `presupuestos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `presupuesto_historial_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `presupuesto_items`
--
ALTER TABLE `presupuesto_items`
  ADD CONSTRAINT `presupuesto_items_mobiliario_id_foreign` FOREIGN KEY (`mobiliario_id`) REFERENCES `mobiliarios` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `presupuesto_items_presupuesto_id_foreign` FOREIGN KEY (`presupuesto_id`) REFERENCES `presupuestos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `presupuesto_items_sector_id_foreign` FOREIGN KEY (`sector_id`) REFERENCES `sectores` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `presupuesto_versiones`
--
ALTER TABLE `presupuesto_versiones`
  ADD CONSTRAINT `presupuesto_versiones_creado_por_foreign` FOREIGN KEY (`creado_por`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `presupuesto_versiones_presupuesto_id_foreign` FOREIGN KEY (`presupuesto_id`) REFERENCES `presupuestos` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

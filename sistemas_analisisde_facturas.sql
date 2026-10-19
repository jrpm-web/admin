-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.4.3 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping data for table its.alertas: ~0 rows (approximately)

-- Dumping data for table its.asignaciones: ~0 rows (approximately)

-- Dumping data for table its.cache: ~0 rows (approximately)

-- Dumping data for table its.cache_locks: ~0 rows (approximately)

-- Dumping data for table its.categorias: ~6 rows (approximately)
REPLACE INTO `categorias` (`id`, `nombre_categoria`, `descripcion`, `icono`, `color`, `orden`, `activo`, `creado_por`, `actualizado_por`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'Telefonia', 'Telefonica', 'bi-tag', '#6c757d', 0, 1, NULL, NULL, '2026-06-23 17:01:01', '2026-06-23 17:01:01', NULL),
	(2, 'Consumible', 'pruebas', 'bi-tag', '#6c757d', 0, 1, 1, NULL, '2026-07-15 23:28:19', '2026-07-15 23:28:19', NULL),
	(3, 'Consumibles', 'ds', 'bi-tag', '#6c757d', 0, 1, 1, NULL, '2026-07-15 23:42:45', '2026-07-15 23:42:45', NULL),
	(4, 'Papeleria', 'papeleria', 'bi-tag', '#6c757d', 0, 1, 1, NULL, '2026-07-18 00:30:31', '2026-07-18 00:30:31', NULL),
	(5, 'Consumible1', 'wefgsdfg', 'bi-tag', '#6c757d', 0, 1, 1, NULL, '2026-07-20 19:50:25', '2026-07-31 00:34:39', '2026-07-31 00:34:39'),
	(6, 'Celulares e Internet Móvil', 'Celulares e Internet Móvil', 'bi-tag', '#6c757d', 0, 1, 1, NULL, '2026-07-31 00:34:20', '2026-07-31 00:34:20', NULL);

-- Dumping data for table its.detalles_almacenamiento: ~0 rows (approximately)

-- Dumping data for table its.detalles_cctv: ~0 rows (approximately)

-- Dumping data for table its.detalles_computadoras: ~0 rows (approximately)

-- Dumping data for table its.detalles_impresoras: ~0 rows (approximately)

-- Dumping data for table its.detalles_monitores: ~0 rows (approximately)

-- Dumping data for table its.detalles_muebles: ~0 rows (approximately)

-- Dumping data for table its.detalles_red: ~0 rows (approximately)

-- Dumping data for table its.detalles_servidores: ~0 rows (approximately)

-- Dumping data for table its.detalles_telefonia: ~0 rows (approximately)

-- Dumping data for table its.detalles_ups: ~0 rows (approximately)

-- Dumping data for table its.estatus_productos: ~5 rows (approximately)
REPLACE INTO `estatus_productos` (`id`, `producto_id`, `estatus`, `observaciones`, `usuario_registro_id`, `fecha_cambio`, `created_at`, `updated_at`) VALUES
	(1, 2, 'baja', NULL, 1, '2026-07-03 23:34:26', '2026-07-03 23:34:26', '2026-07-03 23:34:26'),
	(2, 3, 'disponible', NULL, NULL, '2026-07-10 00:51:39', '2026-07-10 00:51:39', '2026-07-10 00:51:39'),
	(3, 4, 'disponible', NULL, NULL, '2026-07-31 00:40:30', '2026-07-31 00:40:30', '2026-07-31 00:40:30'),
	(4, 5, 'disponible', NULL, NULL, '2026-08-05 16:51:28', '2026-08-05 16:51:28', '2026-08-05 16:51:28'),
	(5, 6, 'disponible', NULL, NULL, '2026-08-07 16:55:17', '2026-08-07 16:55:17', '2026-08-07 16:55:17');

-- Dumping data for table its.facturas: ~20 rows (approximately)
REPLACE INTO `facturas` (`id`, `numero_factura`, `proveedor_id`, `monto`, `moneda`, `tasa_referencia`, `monto_usd`, `mes`, `fecha_factura`, `fecha_pago`, `estado_pago`, `observaciones`, `creado_por`, `actualizado_por`, `created_at`, `updated_at`, `deleted_at`, `producto_id`) VALUES
	(12, '81540027', 22, 344119.54, 'Bs', 673.11, 511.24, 'June 2026', '2026-06-01', '2026-07-23', 'pagado', NULL, 1, 1, '2026-07-30 15:56:18', '2026-07-30 17:28:56', NULL, NULL),
	(13, '81376226', 22, 463752.85, 'Bs', 557.97, 831.14, 'May 2026', '2026-05-01', '2026-06-23', 'pagado', NULL, 1, 1, '2026-07-30 16:03:34', '2026-07-30 17:29:38', NULL, NULL),
	(16, '81219550', 22, 442509.19, 'Bs', 490.04, 903.01, 'April 2026', '2026-04-01', '2026-05-22', 'pagado', NULL, 1, 1, '2026-07-30 16:15:42', '2026-07-30 16:58:36', NULL, NULL),
	(17, '81062354', 22, 462032.59, 'Bs', 474.05, 974.65, 'March 2026', '2026-03-01', '2026-04-23', 'pagado', NULL, 1, 1, '2026-07-30 16:36:20', '2026-07-30 16:59:37', NULL, NULL),
	(18, '80904541', 22, 255010.93, 'Bs', 421.87, 604.48, 'February 2026', '2026-02-01', '2026-03-24', 'pagado', NULL, 1, 1, '2026-07-30 16:42:07', '2026-07-30 17:00:25', NULL, NULL),
	(19, '80746082', 22, 226121.79, 'Bs', 370.25, 610.73, 'January 2026', '2026-01-01', '2026-02-23', 'pagado', NULL, 1, 1, '2026-07-30 16:45:05', '2026-07-30 17:01:07', NULL, NULL),
	(28, '81689792', 22, 265518.13, 'Bs', 748.78, 354.60, 'July 2026', '2026-07-04', NULL, 'pendiente', NULL, 1, 1, '2026-08-04 16:02:58', '2026-08-07 18:29:19', NULL, NULL),
	(29, '000922221673', 24, 219287.73, 'Bs', 363.66, 603.00, 'January 2026', '2026-01-28', NULL, 'pagado', NULL, 1, NULL, '2026-08-05 17:13:19', '2026-08-05 17:13:19', NULL, NULL),
	(30, '000923417304', 24, 84315.61, 'Bs', 388.73, 216.90, 'February 2026', '2026-02-10', NULL, 'pagado', NULL, 1, NULL, '2026-08-05 17:15:37', '2026-08-05 17:15:37', NULL, NULL),
	(31, '000925072757', 24, 266201.53, 'Bs', 438.20, 607.48, 'March 2026', '2026-03-10', NULL, 'pagado', NULL, 1, NULL, '2026-08-05 17:19:01', '2026-08-05 17:19:01', NULL, NULL),
	(32, '000928622701', 24, 250379.90, 'Bs', 500.46, 500.30, 'May 2026', '2026-05-10', NULL, 'pagado', NULL, 1, 1, '2026-08-05 17:21:16', '2026-08-05 17:47:42', NULL, NULL),
	(33, '000930424372', 24, 336646.94, 'Bs', 567.68, 593.02, 'June 2026', '2026-06-13', NULL, 'pagado', NULL, 1, NULL, '2026-08-05 17:26:57', '2026-08-05 17:26:57', NULL, NULL),
	(34, '000931827388', 24, 429300.69, 'Bs', 667.05, 643.58, 'July 2026', '2026-07-03', NULL, 'pagado', NULL, 1, NULL, '2026-08-05 17:35:01', '2026-08-05 17:35:01', NULL, NULL),
	(35, '000930091817', 24, 17646.42, 'Bs', 567.68, 31.09, 'June 2026', '2026-06-10', NULL, 'pagado', NULL, 1, NULL, '2026-08-05 17:59:17', '2026-08-05 17:59:17', NULL, NULL),
	(36, '11238769', 25, 42035.00, 'Bs', 349.92, 120.13, 'January 2026', '2026-01-21', NULL, 'pagado', NULL, 1, 1, '2026-08-07 16:58:07', '2026-08-07 16:58:42', NULL, NULL),
	(38, '11335717', 25, 51534.20, 'Bs', 405.35, 127.14, 'February 2026', '2026-02-21', NULL, 'pagado', NULL, 1, NULL, '2026-08-07 17:04:07', '2026-08-07 17:04:07', NULL, NULL),
	(39, '11529252', 25, 72109.60, 'Bs', 457.07, 157.76, 'March 2026', '2026-04-21', NULL, 'pagado', NULL, 1, NULL, '2026-08-07 17:07:32', '2026-08-07 17:07:32', NULL, NULL),
	(40, '11628117', 25, 76572.60, 'Bs', 526.86, 145.34, 'May 2026', '2026-05-21', NULL, 'pagado', NULL, 1, NULL, '2026-08-07 17:12:01', '2026-08-07 17:12:01', NULL, NULL),
	(41, '11722778', 25, 85973.58, 'Bs', 612.43, 140.38, 'June 2026', '2026-06-21', NULL, 'pagado', NULL, 1, NULL, '2026-08-07 17:15:56', '2026-08-07 17:15:56', NULL, NULL),
	(42, '11816943', 25, 40135.20, 'Bs', 737.23, 54.44, 'July 2026', '2026-07-21', '2026-08-26', 'pagado', NULL, 1, 1, '2026-08-07 17:18:12', '2026-08-07 17:23:46', NULL, NULL);

-- Dumping data for table its.factura_archivos: ~20 rows (approximately)
REPLACE INTO `factura_archivos` (`id`, `factura_id`, `nombre_original`, `nombre_guardado`, `ruta`, `tipo_archivo`, `tamaño`, `mime_type`, `categoria`, `descripcion`, `hash`, `subido_por`, `created_at`, `updated_at`) VALUES
	(1, 12, 'Factura_81540027.pdf', '1785412578_1a2b2bc82dbe899bf28f76243a51b4abddd0fa3a2b1b78e947be8e25f3f4406a.pdf', 'facturas/12//1785412578_1a2b2bc82dbe899bf28f76243a51b4abddd0fa3a2b1b78e947be8e25f3f4406a.pdf', 'pdf', 45886, 'application/pdf', 'factura', 'Factura 81540027', 'fe02f762dd5235bc4be15aed1f03e885ba0d8baefc84ab0c98f58073df371eae', 1, '2026-07-30 15:56:18', '2026-07-30 15:56:18'),
	(2, 13, 'Factura_81376226.pdf', '1785413014_40b876532c3f37708b24abc45d28cdca95cf62fb43cbaaa5ffb620e392ab2a24.pdf', 'facturas/13//1785413014_40b876532c3f37708b24abc45d28cdca95cf62fb43cbaaa5ffb620e392ab2a24.pdf', 'pdf', 48498, 'application/pdf', 'factura', 'Factura 81376226', 'ad07d0ac56c27bdb31f5101d902cb6580f2d607bf5d41d7e16eb2be12fd8a329', 1, '2026-07-30 16:03:34', '2026-07-30 16:03:34'),
	(4, 16, 'factura-digitel-81219550.pdf', '1785413742_7d7243e2f3295bb930bf4de2b88de61aea422afb713e7a2f4c1a7021aa4c11c1.pdf', 'facturas/16//1785413742_7d7243e2f3295bb930bf4de2b88de61aea422afb713e7a2f4c1a7021aa4c11c1.pdf', 'pdf', 127327, 'application/pdf', 'factura', 'Factura 81219550', '4b7263ad26228c411562942731df253d15efa6590ce9c59c653d42c65903d981', 1, '2026-07-30 16:15:42', '2026-07-30 16:15:42'),
	(5, 17, 'factura-digitel-81219550.pdf', '1785414980_5e4386f3d71dc9f173cc18dc097932cb73a4f311d2bbb718e33d9fc78743c5b4.pdf', 'facturas/17//1785414980_5e4386f3d71dc9f173cc18dc097932cb73a4f311d2bbb718e33d9fc78743c5b4.pdf', 'pdf', 127327, 'application/pdf', 'factura', 'Factura 81062354', '4b7263ad26228c411562942731df253d15efa6590ce9c59c653d42c65903d981', 1, '2026-07-30 16:36:20', '2026-07-30 16:36:20'),
	(6, 18, 'Digitel Febrero 2026 Renta Final.pdf', '1785415327_aeab2ffa2fd339812c52e7e8b8c7715d31bf4d85a153c3d1dda04daa25667e34.pdf', 'facturas/18//1785415327_aeab2ffa2fd339812c52e7e8b8c7715d31bf4d85a153c3d1dda04daa25667e34.pdf', 'pdf', 199920, 'application/pdf', 'factura', 'Factura 80904541', '1b4ee95180e7e9ca18e77ecd3efef68a8adea5378fb028cd056e99bded4608eb', 1, '2026-07-30 16:42:07', '2026-07-30 16:42:07'),
	(7, 19, 'Factura_80746082.pdf', '1785415505_ea1fe7e1296a97bc1a8b63f5c382c12f2b582bdd90f9ba52db481e41c491d75d.pdf', 'facturas/19//1785415505_ea1fe7e1296a97bc1a8b63f5c382c12f2b582bdd90f9ba52db481e41c491d75d.pdf', 'pdf', 155035, 'application/pdf', 'factura', 'Factura 80746082', 'b42d9ee51e1fc249e47bb9cabdda2dc5511630ebaffbb9a0d0f5e1022569d97e', 1, '2026-07-30 16:45:05', '2026-07-30 16:45:05'),
	(14, 28, 'factura_81689792_20260804.pdf', '1785844978_9a2b588b04c1454370041730a12646310d389edd0939fca027f845896d058c3b.pdf', 'facturas/28//1785844978_9a2b588b04c1454370041730a12646310d389edd0939fca027f845896d058c3b.pdf', 'pdf', 126839, 'application/pdf', 'factura', 'Factura 81689792', 'a75032f3e15f33862e679897ce6d1d8ed07593515d678d47443104033d154f26', 1, '2026-08-04 16:02:58', '2026-08-04 16:02:58'),
	(15, 29, 'Factura_922221673.pdf', '1785935599_7321dcbb83edcf792e19a12bfad682f2a1963bcaff1bf1cbf53ed7647fd069de.pdf', 'facturas/29//1785935599_7321dcbb83edcf792e19a12bfad682f2a1963bcaff1bf1cbf53ed7647fd069de.pdf', 'pdf', 210000, 'application/pdf', 'factura', 'Factura 000922221673', '0e94c015c02110a1168124bd649b285debdd3c9d75bcb4703215169af8672c54', 1, '2026-08-05 17:13:19', '2026-08-05 17:13:19'),
	(16, 30, 'Factura_923417304.pdf', '1785935737_676a91370388faadfe29a03a79dae4cac436e16c14dce35ec0ccc11ff89620d7.pdf', 'facturas/30//1785935737_676a91370388faadfe29a03a79dae4cac436e16c14dce35ec0ccc11ff89620d7.pdf', 'pdf', 194501, 'application/pdf', 'factura', 'Factura 000923417304', '1ad11c0b885fd8d97c0cc8d1ea641947b30d10198ffba0f8d8a959c70a1558bf', 1, '2026-08-05 17:15:37', '2026-08-05 17:15:37'),
	(17, 31, 'Factura_925072757.pdf', '1785935941_00d4fb0b75070ed7515b063d3fd1f3bfb127589b2b55a7973c44a4d2fb1d1cf6.pdf', 'facturas/31//1785935941_00d4fb0b75070ed7515b063d3fd1f3bfb127589b2b55a7973c44a4d2fb1d1cf6.pdf', 'pdf', 212454, 'application/pdf', 'factura', 'Factura 000925072757', '6699c653f6e885ebc7b993a426a6537b3a75afb1aed11fa197a40cc27e532863', 1, '2026-08-05 17:19:01', '2026-08-05 17:19:01'),
	(18, 32, 'Factura_NCEF000928622701.pdf', '1785936076_85460e5d7f9ba40e9e4c78e65fb30fdb38d9ad8ee0b2eadf191d189ddefdb26e.pdf', 'facturas/32//1785936076_85460e5d7f9ba40e9e4c78e65fb30fdb38d9ad8ee0b2eadf191d189ddefdb26e.pdf', 'pdf', 51547, 'application/pdf', 'factura', 'Factura 000928622701', '08dbba41f2a1ffa98a2068cd662fe5c057dee406c935c5aeb29dbbeb38a091c6', 1, '2026-08-05 17:21:16', '2026-08-05 17:21:16'),
	(19, 33, 'F000930424372 2129019111 J507762718 1021686914 MES JUNIO 2026.pdf', '1785936417_066874feea49448000cee1e0df93f7aa6dbde0f7dbc08723745b6e3591d79358.pdf', 'facturas/33//1785936417_066874feea49448000cee1e0df93f7aa6dbde0f7dbc08723745b6e3591d79358.pdf', 'pdf', 47662, 'application/pdf', 'factura', 'Factura 000930424372', 'ee55e4c6d09ebc71afd9e0fc6b18f0c5bb822f2e3e1b78f1f5130e1618029e3c', 1, '2026-08-05 17:26:57', '2026-08-05 17:26:57'),
	(20, 34, 'F000931827388 2120175426 J507762718 1021686914.pdf', '1785936901_8296674075cabe685658a140422d709185da2ad1c5dac025a74279fa2030c993.pdf', 'facturas/34//1785936901_8296674075cabe685658a140422d709185da2ad1c5dac025a74279fa2030c993.pdf', 'pdf', 134923, 'application/pdf', 'factura', 'Factura 000931827388', '5f2bd865d6139efd2f28fa8526fc1c390bd15a86a9af7f74579d3a6b32b6a3de', 1, '2026-08-05 17:35:01', '2026-08-05 17:35:01'),
	(21, 35, 'Factura_F000930091817.pdf', '1785938357_ac182695e0c5d7f825c7166d29b44cd7773e0f4221d1243e18a42b6515d695eb.pdf', 'facturas/35//1785938357_ac182695e0c5d7f825c7166d29b44cd7773e0f4221d1243e18a42b6515d695eb.pdf', 'pdf', 43179, 'application/pdf', 'factura', 'Factura 000930091817', '657edb77b33fccaa54ea1adfcbf9516931689f1296b703c23c7dbc6c9fa722e9', 1, '2026-08-05 17:59:17', '2026-08-05 17:59:17'),
	(22, 36, 'Factura Nro. 11238769 -Enero 2026 .pdf', '1786107487_aff0836beb396ecec6a378fec7f3f09a0a908607390d4ee81384bee691b4f5c9.pdf', 'facturas/36//1786107487_aff0836beb396ecec6a378fec7f3f09a0a908607390d4ee81384bee691b4f5c9.pdf', 'pdf', 27132, 'application/pdf', 'factura', 'Factura 11238769', '55890d2f4c56337fb2adc6c766939d1b12b3517e1c4063868c59b23f4426be07', 1, '2026-08-07 16:58:07', '2026-08-07 16:58:07'),
	(23, 38, 'Factura Nro. 11335717 - Febrero 2026.pdf', '1786107847_1f984fa3537050068a9b2358b522a4e31b806da1c6df9016e150dbd8da98aa8a.pdf', 'facturas/38//1786107847_1f984fa3537050068a9b2358b522a4e31b806da1c6df9016e150dbd8da98aa8a.pdf', 'pdf', 210686, 'application/pdf', 'factura', 'Factura 11335717', 'ebd2716c6457bea1ac2c0df0023456bd4d5083d67ebafd3c7129b02979e74440', 1, '2026-08-07 17:04:07', '2026-08-07 17:04:07'),
	(24, 39, 'Factura_11529252.pdf', '1786108052_ecca3ddc978f6f4a12a639559393f811daeb706992481e0e502dfd4472953adc.pdf', 'facturas/39//1786108052_ecca3ddc978f6f4a12a639559393f811daeb706992481e0e502dfd4472953adc.pdf', 'pdf', 156737, 'application/pdf', 'factura', 'Factura 11529252', '3a42e62dddcf83172a8dff1e3525532286d9e131159cb8ecb33e566bb73760db', 1, '2026-08-07 17:07:32', '2026-08-07 17:07:32'),
	(25, 40, 'Factura_11628117.pdf', '1786108321_26befcb5195deb64ff0ea91655b39546470454aaefce0bae477fe64c65e61ac2.pdf', 'facturas/40//1786108321_26befcb5195deb64ff0ea91655b39546470454aaefce0bae477fe64c65e61ac2.pdf', 'pdf', 66671, 'application/pdf', 'factura', 'Factura 11628117', '21d5127404af31afce430fc49c76bb7a90e49df357e559a32783331652ffb869', 1, '2026-08-07 17:12:01', '2026-08-07 17:12:01'),
	(26, 41, '1_5082690411487561382.pdf', '1786108556_57b8a5c8565559c68e7a8df3c18aee0cd3edea15866175f8a808446b03c77091.pdf', 'facturas/41//1786108556_57b8a5c8565559c68e7a8df3c18aee0cd3edea15866175f8a808446b03c77091.pdf', 'pdf', 27950, 'application/pdf', 'factura', 'Factura 11722778', '8289a988428f2c7f2e117c0b0f82f7b205d507ebf1b5b9cf162672d6592a3805', 1, '2026-08-07 17:15:56', '2026-08-07 17:15:56'),
	(27, 42, 'Factura_11816943.pdf', '1786108692_19f918b2bc152c9210bdbea30035f732f22732a7d96c9f52c6621b60a9509ca5.pdf', 'facturas/42//1786108692_19f918b2bc152c9210bdbea30035f732f22732a7d96c9f52c6621b60a9509ca5.pdf', 'pdf', 67944, 'application/pdf', 'factura', 'Factura 11816943', '72546460b7c26e24dc14f647a8f8274f25138dbc0a4764a76bcbc435b33330fe', 1, '2026-08-07 17:18:12', '2026-08-07 17:18:12');

-- Dumping data for table its.failed_jobs: ~0 rows (approximately)

-- Dumping data for table its.jobs: ~0 rows (approximately)

-- Dumping data for table its.job_batches: ~0 rows (approximately)

-- Dumping data for table its.migrations: ~34 rows (approximately)
REPLACE INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1),
	(4, '2026_06_18_192208_create_proveedores_table', 1),
	(5, '2026_06_22_193848_create_categorias_table', 1),
	(6, '2026_06_22_202746_create_productos_table', 1),
	(7, '2026_06_28_091522_create_facturas_table', 1),
	(8, '2026_06_23_125903_add_deleted_at_to_categorias_table', 2),
	(11, '2026_06_23_200438_create_estatus_productos_table', 3),
	(12, '2026_06_23_200459_create_asignaciones_table', 3),
	(13, '2026_07_03_164521_create_detalles_red_table', 4),
	(14, '2026_07_03_164530_create_detalles_telefonia_table', 4),
	(15, '2026_07_03_164531_create_detalles_computadoras_table', 4),
	(16, '2026_07_03_164531_create_detalles_monitores_table', 4),
	(17, '2026_07_03_164532_create_detalles_cctv_table', 4),
	(18, '2026_07_03_164532_create_detalles_impresoras_table', 4),
	(19, '2026_07_03_164533_create_detalles_muebles_table', 4),
	(20, '2026_07_03_164534_create_detalles_almacenamiento_table', 4),
	(21, '2026_07_03_164534_create_detalles_servidores_table', 4),
	(22, '2026_07_03_164537_create_detalles_ups_table', 4),
	(23, '2026_07_03_174054_add_missing_columns_to_users_table', 5),
	(24, '2026_07_03_174342_add_soft_deletes_to_users_table', 6),
	(25, '2026_07_03_183912_add_audit_fields_to_categorias_table', 7),
	(26, '2026_07_04_154459_update_facturas_table_with_currency', 8),
	(27, '2026_07_04_155439_add_soft_deletes_to_facturas_table', 8),
	(28, '2026_07_04_161125_add_columns_to_facturas_table', 8),
	(29, '2026_07_04_161357_add_soft_deletes_to_facturas_table', 8),
	(30, '2026_07_04_161548_create_facturas_table_new', 8),
	(31, '2026_07_09_000551_create_factura_archivos_table', 9),
	(32, '2026_07_09_165856_create_alertas_table', 10),
	(33, '2026_07_09_165927_add_fecha_facturacion_to_proveedores', 10),
	(34, '2026_07_09_203511_add_tipo_pago_to_productos_table', 11),
	(35, '2026_07_09_205609_add_producto_id_to_facturas_table.', 12),
	(36, '2026_07_14_204354_fix_unique_index_on_proveedores', 12),
	(37, '2026_07_15_140826_add_ciudad_to_proveedores_table', 13),
	(38, '2026_07_17_182832_add_categoria_id_to_proveedores_table', 14);

-- Dumping data for table its.password_reset_tokens: ~0 rows (approximately)

-- Dumping data for table its.productos: ~6 rows (approximately)
REPLACE INTO `productos` (`id`, `nombre`, `codigo`, `descripcion`, `precio_compra`, `precio_venta`, `stock`, `stock_minimo`, `categoria_id`, `proveedor_id`, `unidad_medida`, `ubicacion`, `activo`, `tipo_pago`, `frecuencia_pago`, `dia_facturacion`, `proxima_facturacion`, `notificar_antes`, `dias_notificacion`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'Chicit 04241516687', '0412265789', 'Pruebas', 1.00, 2.00, 0, 1, 1, 1, 'unidad', NULL, 1, 'unico', NULL, NULL, NULL, 1, 5, '2026-06-24 00:47:24', '2026-06-24 00:47:24', NULL),
	(2, 'SIM Card', 'SIM CARD', 'SIM card', 0.01, 0.01, 1, 1, 1, 22, 'unidad', 'Caracas', 1, 'unico', NULL, NULL, NULL, 1, 5, '2026-07-03 23:34:26', '2026-07-03 23:34:26', NULL),
	(3, 'Telefonica Movil', '5200193407', '5200193407', 1.00, 0.00, 1, 0, 1, 22, 'unidad', 'Caracas', 1, 'recurrente', 'mensual', 1, '2026-08-01', 1, 1, '2026-07-10 00:51:39', '2026-07-10 00:51:39', NULL),
	(4, 'TELEFONÍA MÓVIL - INTERNET - PLC', 'TELEFONÍA MÓVIL - INTERNET - PLC', 'TELEFONÍA MÓVIL - INTERNET - PLC', 0.00, 0.00, 30, 1, 6, 23, 'unidad', 'Caracas', 1, 'recurrente', 'mensual', 7, '2026-08-07', 1, 1, '2026-07-31 00:40:30', '2026-07-31 00:40:30', NULL),
	(5, 'Telefonica Fija Cantv', 'TELEFONICA FIJA CANTV', 'Telefonica Fija Cantv', 0.00, 0.00, 0, 1, 1, 24, 'unidad', NULL, 1, 'recurrente', 'mensual', 6, '2026-08-13', 1, 1, '2026-08-05 16:51:28', '2026-08-05 16:51:28', NULL),
	(6, 'Telefonica Movil Y Inetnet', '300005690', 'Servicio de telefonía móvil e Internet', 0.00, 0.00, 0, 1, 1, 25, 'unidad', 'Caracas', 1, 'recurrente', 'mensual', 21, '2026-08-21', 1, 1, '2026-08-07 16:55:17', '2026-08-07 16:55:17', NULL);

-- Dumping data for table its.proveedores: ~21 rows (approximately)
REPLACE INTO `proveedores` (`id`, `categoria_id`, `proveedor`, `fecha_facturacion`, `dias_alertar`, `monitoreo_activo`, `asesor`, `telefono_contacto`, `correo`, `ciudad`, `descripcion_corta`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, NULL, 'Telefonica', NULL, 5, 1, 'Jesus Puentes', '04241516687', 'jesus.puentes0@gmail.com', NULL, 'pruebas', '2026-06-24 00:21:31', '2026-07-06 21:06:16', '2026-07-06 21:06:16'),
	(2, NULL, 'Digitel', NULL, 5, 1, 'Orianny Vargas', NULL, 'orianny.vargas@digitel.com.ve', NULL, '', '2026-07-06 21:07:27', '2026-07-14 23:28:05', '2026-07-14 23:28:05'),
	(3, NULL, 'Telefoncia', NULL, 5, 1, 'Dioan J. Barrios E.', NULL, 'dioan.barrios@telefonica.com', NULL, '', '2026-07-06 23:05:55', '2026-07-15 16:15:40', '2026-07-15 16:15:40'),
	(4, NULL, 'Cantv', NULL, 5, 1, 'Lina Herrera', NULL, 'orianny.vargas1@digitel.com.ve', NULL, '', '2026-07-10 16:11:29', '2026-07-15 16:15:31', '2026-07-15 16:15:31'),
	(8, NULL, 'Telefonica', NULL, 5, 1, 'Orianny Vargas', NULL, 'orianny.vargas@digitel.com.ve', NULL, '', '2026-07-15 00:44:59', '2026-07-15 00:46:23', '2026-07-15 00:46:23'),
	(9, NULL, 'Telefonica', NULL, 5, 1, 'Orianny Vargas', NULL, 'orianny.vargas@digitel.com.ve', NULL, '', '2026-07-15 16:17:51', '2026-07-15 16:17:55', '2026-07-15 16:17:55'),
	(10, NULL, 'Digitel', NULL, 5, 1, 'Orianny Vargas', '04128081870', 'orianny.vargas@digitel.com.ve', 'Caracas', '', '2026-07-15 16:20:53', '2026-07-17 22:59:45', '2026-07-17 22:59:45'),
	(11, NULL, 'Telefonica', NULL, 5, 1, 'Jesus Puentes', '04128081870', 'jesus.puentes0@gmail.com', 'Caracas', '', '2026-07-15 19:33:23', '2026-07-30 15:48:22', '2026-07-30 15:48:22'),
	(12, NULL, 'Telefonica', NULL, 5, 1, 'Orianny Vargas', '04128081870', 'orianny.vargas2@digitel.com.ve', 'Caracas', '', '2026-07-17 22:09:53', '2026-07-18 00:24:26', '2026-07-18 00:24:26'),
	(13, 1, 'Digitel', NULL, 5, 1, 'Orianny Vargas', '04241516687', 'dioan.barrios@telefonica.com', 'Caracas', '', '2026-07-17 23:17:04', '2026-07-30 15:48:26', '2026-07-30 15:48:26'),
	(14, 2, 'Cantv', NULL, 5, 1, 'Lina Heerera', '04241516687', 'orianny.vargas@cantv.com.ve', 'Caracas', '', '2026-07-18 00:24:07', '2026-07-18 00:24:18', '2026-07-18 00:24:18'),
	(15, 1, 'Telefonica', NULL, 5, 1, 'Orianny Vargas', '04128081870', 'orianny.vargas@digitel.com.ve', 'Caracas', '', '2026-07-18 00:37:49', '2026-07-30 15:48:29', '2026-07-30 15:48:29'),
	(16, 2, 'Caracas', NULL, 5, 1, 'Jesus Puentes', '04142156587', 'pruebas@gmail.com', 'Caracas', '', '2026-07-20 19:58:46', '2026-07-30 15:48:32', '2026-07-30 15:48:32'),
	(17, 2, 'Caracas', NULL, 5, 1, 'Jesus Puentes', '04142156587', 'pruebas2@gmail.com', 'Caracas', '', '2026-07-20 20:00:35', '2026-07-30 15:48:35', '2026-07-30 15:48:35'),
	(18, 5, 'Telefonica', NULL, 5, 1, 'Jesus Puentes', '04241516687', 'orianny.vargas32@digitel.com.ve', 'Caracas', '', '2026-07-20 20:02:33', '2026-07-30 15:48:37', '2026-07-30 15:48:37'),
	(19, 5, 'Telefonica', NULL, 5, 1, 'Jesus Puentes', '04241516687', 'orianny.vargas22@digitel.com.ve', 'Caracas', '', '2026-07-20 20:09:39', '2026-07-30 15:48:40', '2026-07-30 15:48:40'),
	(20, 2, 'Caracas', NULL, 5, 1, 'Dioan J. Barrios E.', '04142156587', 'dioan.barrio1s@telefonica.com', 'caracas', '', '2026-07-21 00:32:19', '2026-07-21 00:39:23', '2026-07-21 00:39:23'),
	(21, 2, 'Asdfdfsf', NULL, 5, 1, 'Jesus Puentes', '04142156587', 'dioan.barriossdf@telefonica.com', 'asdfasdf', '', '2026-07-21 00:50:26', '2026-07-30 15:48:44', '2026-07-30 15:48:44'),
	(22, 2, 'Digitel', NULL, 5, 1, 'Orianny Vargas', '04241516687', 'orianny.varga45s@digitel.com.ve', 'Caracas', '', '2026-07-21 16:55:04', '2026-07-21 16:55:04', NULL),
	(23, 6, 'Movistar', NULL, 5, 1, 'YOLANDA RODRÍGUEZ', '02122001253', 'yolanda.rodriguez@telefonica.com', 'Caracas', '', '2026-07-31 00:36:46', '2026-07-31 00:36:46', NULL),
	(24, 1, 'Cantv', NULL, 5, 1, 'Herrera Moreno Lina Marbelys Jose', '04265715119', 'lherre06@cantv.com.ve', 'Caracas', '', '2026-08-05 16:49:13', '2026-08-05 16:49:13', NULL),
	(25, 1, 'Movilnet', NULL, 5, 1, 'Barco Duarte Pedro Manuel', '04166291825', 'pedro.barco@movilnet.com.ve', 'Caracas', '', '2026-08-07 16:52:20', '2026-08-07 16:52:20', NULL);

-- Dumping data for table its.sessions: ~1 rows (approximately)
REPLACE INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('eqzOlf1pKtUfKcZiIKoPGMN3SyG62mKl7aO2PUCe', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiIxNERVZEpVTkVKSTdxQ1VxTUJIdzRnaUNmZ0VzUGZzM0Vlcm00NEJHIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL3Npc3RlbWFhbmFsaXNpc2RlZmFjdHVyYXMudGVzdFwvYWxlcnRhc1wvYWN0dWFsaXphciIsInJvdXRlIjoiYWxlcnRhcy5hY3R1YWxpemFyIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjF9', 1786134504);

-- Dumping data for table its.users: ~1 rows (approximately)
REPLACE INTO `users` (`id`, `name`, `apellidos`, `email`, `cedula`, `telefono`, `cargo`, `departamento`, `foto`, `estado`, `rol`, `ultimo_acceso`, `ultima_actividad`, `ip_ultimo_acceso`, `user_agent_ultimo_acceso`, `preferencias`, `tema`, `idioma`, `creado_por`, `actualizado_por`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'jesus puentes', NULL, 'puentes_jr@rzneft.com', '17793066', '04241516687', 'Especialista', 'ITs', NULL, 'activo', 'admin', '2026-08-05 18:05:29', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', NULL, 'light', 'es', NULL, NULL, NULL, '$2y$12$Ti2lg3Y32fFo27nOZd9vaO2BSYbNEOvh2RQIS8Cp/IvkXvC0Fne2C', 'jlFlDIFk2GjBytcDohTxuJXZCOD8GkaWGbBklSp149zuc1bWi8HSC6eEL7lN', '2026-07-03 20:57:35', '2026-08-05 18:05:29', NULL);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;

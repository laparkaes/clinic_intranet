-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- 생성 시간: 23-06-14 21:50
-- 서버 버전: 10.4.24-MariaDB
-- PHP 버전: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 데이터베이스: `everlyn`
--

-- --------------------------------------------------------

--
-- 테이블 구조 `voucher`
--

CREATE TABLE `voucher` (
  `id` int(11) NOT NULL,
  `sale_id` int(11) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `status_id` int(11) NOT NULL,
  `sunat_sent` tinyint(1) DEFAULT NULL,
  `sunat_msg` varchar(250) DEFAULT NULL,
  `type` varchar(10) NOT NULL,
  `code` varchar(5) NOT NULL,
  `letter` char(1) NOT NULL,
  `serie` varchar(5) NOT NULL,
  `correlative` int(11) NOT NULL,
  `payment_method` varchar(20) NOT NULL,
  `currency` varchar(5) NOT NULL,
  `currency_code` varchar(5) NOT NULL,
  `total` float NOT NULL,
  `amount` float NOT NULL,
  `vat` float NOT NULL,
  `received` float NOT NULL,
  `change` float NOT NULL,
  `legend` varchar(250) NOT NULL,
  `hash` varchar(50) NOT NULL,
  `registed_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 테이블의 덤프 데이터 `voucher`
--

INSERT INTO `voucher` (`id`, `sale_id`, `client_id`, `status_id`, `sunat_sent`, `sunat_msg`, `type`, `code`, `letter`, `serie`, `correlative`, `payment_method`, `currency`, `currency_code`, `total`, `amount`, `vat`, `received`, `change`, `legend`, `hash`, `registed_at`) VALUES
(32, 8, 153, 4, 0, 'ocurrio error de comunicacion con sunat', 'Boleta', '03', 'B', '002', 1, 'Efectivo', 'S/', 'PEN', 132300, 112119, 20181.3, 132300, 0, 'CIENTO TREINTA Y DOS MIL TRESCIENTOS CON 00/100 SOLES', 'ghaBy2W1w295x.hh7TJwMYikWJoK', '2023-02-17 21:00:30'),
(33, 22, 176, 4, 1, NULL, 'Factura', '01', 'F', '002', 1, 'Tarjeta', 'S/', 'PEN', 9000, 7627.12, 1372.88, 9000, 0, 'NUEVE MIL CON 00/100 SOLES', 'ChnHCrmATMBh3YRObcKftGFA3PMu', '2023-02-17 21:01:59'),
(34, 18, 177, 4, 0, 'ocurrio error de comunicacion con sunat', 'Factura', '01', 'F', '001', 1, 'Tarjeta', 'S/', 'PEN', 9000, 7627.12, 1372.88, 9000, 0, 'NUEVE MIL CON 00/100 SOLES', 'ssVziyj.tL.AIz.CvM3fWYcE0.2.', '2023-02-17 21:03:00'),
(35, 11, 153, 4, 0, 'ocurrio error de comunicacion con sunat', 'Boleta', '03', 'B', '002', 2, 'Efectivo', 'S/', 'PEN', 132300, 112119, 20181.3, 132300, 0, 'CIENTO TREINTA Y DOS MIL TRESCIENTOS CON 00/100 SOLES', 'Eg1YVGcnEtbgN95r1UkEzZp2v0aO', '2023-02-17 21:04:04'),
(36, 6, 153, 4, 0, 'ocurrio error de comunicacion con sunat.', 'Boleta', '03', 'B', '002', 3, 'Tarjeta', 'S/', 'PEN', 132300, 112119, 20181.3, 132300, 0, 'CIENTO TREINTA Y DOS MIL TRESCIENTOS CON 00/100 SOLES', 'tMGqFm2B7XNlG1gETRKqnNXPKWoq', '2023-02-17 21:04:36'),
(37, 23, 178, 4, 1, 'Factura electronica recibida.', 'Factura', '01', 'F', '002', 2, 'Tarjeta', 'S/', 'PEN', 1332, 1128.81, 203.19, 1332, 0, 'MIL TRESCIENTOS TREINTA Y DOS CON 00/100 SOLES', 'cbnoybzIWJZRKLfVtmq1z7AigdBe', '2023-02-17 21:21:29'),
(38, 20, 153, 4, 1, 'Factura electronica recibida.', 'Boleta', '03', 'B', '002', 4, 'Efectivo', 'S/', 'PEN', 9999, 8473.73, 1525.27, 9999, 0, 'NUEVE MIL NOVECIENTOS NOVENTA Y NUEVE CON 00/100 SOLES', 'G0KYh/LHHomLsgDuJu5k6MBY8mCC', '2023-02-17 21:24:45'),
(39, 16, 179, 4, 0, 'ocurrio error de comunicacion con sunat.', 'Factura', '01', 'F', '002', 3, 'Tarjeta', 'S/', 'PEN', 15690, 13296.6, 2393.39, 15690, 0, 'QUINCE MIL SEISCIENTOS NOVENTA CON 00/100 SOLES', '23CU4x3rB5usUscjKTfER0cP7t7C', '2023-02-17 21:25:27'),
(40, 3, 153, 2, 1, 'Factura electronica recibida.', 'Boleta', '03', 'B', '002', 1, 'Tarjeta', 'S/', 'PEN', 132300, 112119, 20181.3, 132300, 0, 'CIENTO TREINTA Y DOS MIL TRESCIENTOS CON 00/100 SOLES', 'R/XeQlL2mJXNec51NJaYjk.pLQwq', '2023-02-17 21:47:22'),
(41, 1, 180, 2, 1, 'Factura electronica recibida.', 'Factura', '01', 'F', '002', 1, 'Tarjeta', 'S/', 'PEN', 132300, 112119, 20181.3, 132300, 0, 'CIENTO TREINTA Y DOS MIL TRESCIENTOS CON 00/100 SOLES', 'lAPw0oXfcsfCShrpDxwh1novsQvm', '2023-02-17 21:49:12'),
(42, 2, 181, 2, 0, 'ocurrio error de comunicacion con sunat.', 'Factura', '01', 'F', '002', 2, 'Tarjeta', 'S/', 'PEN', 132300, 112119, 20181.3, 132300, 0, 'CIENTO TREINTA Y DOS MIL TRESCIENTOS CON 00/100 SOLES', 'CoxSQLEFedCwxasssFepsJbX4JTW', '2023-02-17 21:51:20'),
(43, 25, 154, 2, 0, 'ocurrio error de comunicacion con sunat.', 'Boleta', '03', 'B', '002', 2, 'Tarjeta', 'S/', 'PEN', 63090, 53466.1, 9623.9, 63090, 0, 'SESENTA Y TRES MIL NOVENTA CON 00/100 SOLES', 'lTzw5xP.0cyvv9FLk.QQKnxImXR.', '2023-02-17 23:11:06'),
(44, 15, 153, 2, 1, 'Factura electronica recibida.', 'Boleta', '03', 'B', '002', 3, 'Tarjeta', 'S/', 'PEN', 15690, 13296.6, 2393.39, 15690, 0, 'QUINCE MIL SEISCIENTOS NOVENTA CON 00/100 SOLES', 'UaX8BCK.xAMicLZN5zIpRtr2iaM.', '2023-02-17 23:56:34'),
(45, 51, 153, 2, 1, 'Factura electronica recibida.', 'Boleta', '03', 'B', '002', 4, 'Efectivo', 'S/', 'PEN', 546.33, 462.99, 83.34, 600, 53.67, 'QUINIENTOS CUARENTA Y SEIS CON 33/100 SOLES', 'ovGPh1cPA/7Ki/9FCznyBrtsZBue', '2023-02-18 00:57:55'),
(46, 52, 201, 2, 0, 'ocurrio error de comunicacion con sunat.', 'Boleta', '03', 'B', '001', 1, 'Efectivo', 'S/', 'PEN', 50, 42.37, 7.63, 50, 0, 'CINCUENTA CON 00/100 SOLES', 'mebkGh5VP7/53DZDTXsWnXrsn1BG', '2023-02-23 22:35:13'),
(50, 66, 211, 2, 1, 'Factura electronica recibida.', 'Factura', '01', 'F', '002', 3, 'Tarjeta', 'S/', 'PEN', 1340, 1135.6, 204.4, 1340, 0, 'MIL TRESCIENTOS CUARENTA CON 00/100 SOLES', '6v84DI4yW3Z2gjeDINxfQONZMi1K', '2023-03-06 23:12:25'),
(53, 69, NULL, 2, 1, 'Factura electronica recibida.', 'Boleta', '03', 'B', '002', 5, 'Efectivo', 'S/', 'PEN', 700, 593.22, 106.78, 700, 0, 'SETECIENTOS CON 00/100 SOLES', 'eqj.Gr1ivaHjz8b6mjOmPtRp0GkS', '2023-03-06 23:16:15'),
(54, 71, 213, 2, 1, 'Factura electronica recibida.', 'Boleta', '03', 'B', '002', 6, 'Tarjeta', 'S/', 'PEN', 111, 94.07, 16.93, 200, 89, 'CIENTO ONCE CON 00/100 SOLES', 'JT50NpQIht3geugQqB3M3wodkGSW', '2023-03-06 23:56:15'),
(55, 72, 215, 2, 1, 'Factura electronica recibida.', 'Factura', '01', 'F', '002', 4, 'Efectivo', 'S/', 'PEN', 96, 81.36, 14.64, 96, 0, 'NOVENTA Y SEIS CON 00/100 SOLES', 'lF7F2NZFMp47hKI7NRavLK1rZXkK', '2023-03-06 23:59:09'),
(56, 73, NULL, 2, 1, 'Factura electronica recibida.', 'Boleta', '03', 'B', '001', 2, 'Efectivo', 'S/', 'PEN', 50, 42.37, 7.63, 100, 50, 'CINCUENTA CON 00/100 SOLES', '5BdZAIMOGvGD6VzRf.PH9W1ZFl5m', '2023-03-07 05:54:58'),
(57, 74, 217, 2, 1, 'Factura electronica recibida.', 'Factura', '01', 'F', '001', 1, 'Efectivo', 'S/', 'PEN', 300, 254.24, 45.76, 400, 100, 'TRESCIENTOS CON 00/100 SOLES', '45kBwtisdMfq7bp8dS5oimeIaJES', '2023-03-07 21:32:08'),
(58, 75, 219, 2, 1, 'Factura electronica recibida.', 'Factura', '01', 'F', '001', 2, 'Efectivo', 'S/', 'PEN', 50, 42.37, 7.63, 50, 0, 'CINCUENTA CON 00/100 SOLES', 'KEziNfN9KFZh5X476wlLiZnQs8ca', '2023-03-07 21:33:27'),
(59, 76, NULL, 2, 1, 'Factura electronica recibida.', 'Boleta', '03', 'B', '001', 3, 'Efectivo', 'S/', 'PEN', 100, 84.75, 15.25, 100, 0, 'CIEN CON 00/100 SOLES', 'kVJR7WRaWIcZrV9/xbfujot0E00S', '2023-03-07 21:49:18'),
(60, 77, 220, 2, 1, 'Factura electronica recibida.', 'Boleta', '03', 'B', '002', 7, 'Tarjeta', 'S/', 'PEN', 360, 305.08, 54.92, 360, 0, 'TRESCIENTOS SESENTA CON 00/100 SOLES', 'EI0BbpUZJexOtSRZGN8kK8gj01eK', '2023-03-07 21:58:15'),
(61, 70, 212, 2, 1, 'Factura electronica recibida.', 'Boleta', '03', 'B', '002', 8, 'Efectivo', 'S/', 'PEN', 272, 230.51, 41.49, 272, 0, 'DOSCIENTOS SETENTA Y DOS CON 00/100 SOLES', 'MF4bpyIaLGExpF1SRmOfmFpXHr1a', '2023-03-07 22:04:43'),
(62, 10, 222, 2, 1, 'Factura electronica recibida.', 'Factura', '01', 'F', '002', 5, 'Efectivo', 'S/', 'PEN', 132300, 112119, 20181.3, 132300, 0, 'CIENTO TREINTA Y DOS MIL TRESCIENTOS CON 00/100 SOLES', 'wbLr4lRz4YKcKBdWEGdVQu/x5KGS', '2023-03-08 17:11:31'),
(63, 80, NULL, 2, 1, 'Factura electronica recibida.', 'Boleta', '03', 'B', '002', 9, 'Tarjeta', 'S/', 'PEN', 120, 101.69, 18.31, 120, 0, 'CIENTO VEINTE CON 00/100 SOLES', '1yQHD4LDZ1Lb9G4.i9HSFcCxWfUi', '2023-03-09 20:26:37'),
(64, 82, 231, 2, 1, 'Factura electronica recibida.', 'Factura', '01', 'F', '002', 6, 'Tarjeta', 'S/', 'PEN', 500, 423.73, 76.27, 500, 0, 'QUINIENTOS CON 00/100 SOLES', 'ytFSPQHYVHaI5UROc7cpQAw14AGy', '2023-03-10 02:19:12'),
(65, 90, 237, 2, 0, 'ocurrio error de comunicacion con sunat.', 'Factura', '01', 'F', '002', 7, 'Tarjeta', 'S/', 'PEN', 530, 449.15, 80.85, 530, 0, 'QUINIENTOS TREINTA CON 00/100 SOLES', 'q1bI8mwrXF9eA9pAdMmehjmCSWd6', '2023-04-21 22:03:42'),
(66, 91, 238, 2, 0, 'ocurrio error de comunicacion con sunat.', 'Boleta', '03', 'B', '002', 10, 'Efectivo', 'S/', 'PEN', 360, 305.08, 54.92, 360, 0, 'TRESCIENTOS SESENTA CON 00/100 SOLES', 'gL4FiYkrit7kpP82k3ayvnh0VGKm', '2023-04-21 22:10:48'),
(67, 92, 228, 4, 1, 'Factura electronica anulada.', 'Boleta', '03', 'B', '002', 11, 'Efectivo', 'S/', 'PEN', 360, 305.08, 54.92, 360, 0, 'TRESCIENTOS SESENTA CON 00/100 SOLES', '2De2q7C1.MafRHNk1D9ddN.FQTJa', '2023-04-21 22:12:52'),
(68, 93, NULL, 4, 1, 'Factura electronica anulada.', 'Boleta', '03', 'B', '002', 11, 'Efectivo', 'S/', 'PEN', 720, 610.17, 109.83, 720, 0, 'SETECIENTOS VEINTE CON 00/100 SOLES', 'fodcdsdmFE7me2RbpMbtKmDmdf.m', '2023-04-21 22:34:18'),
(74, 94, 237, 4, 1, 'Factura electronica anulada.', 'Factura', '01', 'F', '001', 3, 'Efectivo', 'S/', 'PEN', 13620, 11168.4, 2451.6, 13620, 0, 'TRECE MIL SEISCIENTOS VEINTE CON 00/100 SOLES', 'rWiX3i2nxRq.mE3cEi4kDPpVH.EC', '2023-05-21 22:51:23'),
(75, 95, 228, 2, 1, 'Factura electronica recibida.', 'Boleta', '03', 'B', '002', 11, 'Tarjeta', 'S/', 'PEN', 840, 688.8, 151.2, 840, 0, 'OCHOCIENTOS CUARENTA CON 00/100 SOLES', 'n0kDvcyVOXveNwOyaKk7BF7GfoI2', '2023-05-21 23:02:10'),
(76, 96, 237, 2, 1, 'Factura electronica recibida.', 'Factura', '01', 'F', '001', 3, 'Efectivo', 'S/', 'PEN', 10720, 8790.4, 1929.6, 10720, 0, 'DIEZ MIL SETECIENTOS VEINTE CON 00/100 SOLES', '6VP7u7U2Dlp1eSH60rRChd.2qWqm', '2023-05-24 22:56:17');

--
-- 덤프된 테이블의 인덱스
--

--
-- 테이블의 인덱스 `voucher`
--
ALTER TABLE `voucher`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sale_id` (`sale_id`);

--
-- 덤프된 테이블의 AUTO_INCREMENT
--

--
-- 테이블의 AUTO_INCREMENT `voucher`
--
ALTER TABLE `voucher`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

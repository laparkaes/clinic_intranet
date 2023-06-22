-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- 생성 시간: 23-06-23 00:54
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
-- 테이블 구조 `sunat_resume`
--

CREATE TABLE `sunat_resume` (
  `id` int(11) NOT NULL,
  `correlative` int(11) NOT NULL,
  `ticket` varchar(150) DEFAULT NULL,
  `is_success` tinyint(1) DEFAULT NULL,
  `message` varchar(250) DEFAULT NULL,
  `reason` varchar(250) DEFAULT NULL,
  `registed_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 테이블의 덤프 데이터 `sunat_resume`
--

INSERT INTO `sunat_resume` (`id`, `correlative`, `ticket`, `is_success`, `message`, `reason`, `registed_at`) VALUES
(1, 1, NULL, NULL, NULL, NULL, '2023-06-22 16:17:18'),
(2, 2, NULL, NULL, NULL, NULL, '2023-06-22 16:17:23'),
(3, 3, NULL, NULL, NULL, NULL, '2023-06-22 16:17:29'),
(4, 4, NULL, NULL, NULL, NULL, '2023-06-22 16:19:48'),
(5, 5, NULL, NULL, NULL, NULL, '2023-06-22 16:20:47'),
(6, 6, NULL, NULL, NULL, NULL, '2023-06-22 16:20:53'),
(7, 7, NULL, NULL, NULL, NULL, '2023-06-22 16:27:34'),
(8, 8, NULL, NULL, NULL, NULL, '2023-06-22 16:38:47'),
(9, 9, NULL, NULL, NULL, NULL, '2023-06-22 16:39:09'),
(10, 10, NULL, NULL, NULL, NULL, '2023-06-22 16:39:22'),
(11, 11, '1687452410276', 1, 'La Comunicacion de baja RA-20230622-11, ha sido aceptada', NULL, '2023-06-22 16:50:43'),
(12, 12, '1687459325513', 1, 'La Comunicacion de baja RA-20230622-12, ha sido aceptada', NULL, '2023-06-22 18:45:58'),
(13, 13, '1687463013730', 1, 'La Comunicacion de baja RA-20230622-13, ha sido aceptada', NULL, '2023-06-22 19:47:26'),
(14, 14, '1687463520708', 0, 'HTTP - Unauthorized', NULL, '2023-06-22 19:55:53'),
(15, 15, '1687463527175', 0, 'HTTP - Unauthorized', NULL, '2023-06-22 19:56:00'),
(16, 16, '1687463535089', 0, 'HTTP - Unauthorized', NULL, '2023-06-22 19:56:07'),
(17, 17, '1687463542715', 1, 'La Comunicacion de baja RA-20230622-17, ha sido aceptada', NULL, '2023-06-22 19:56:15'),
(18, 18, '1687464090544', 0, 'HTTP - Unauthorized', 'error de ', '2023-06-22 20:05:23'),
(19, 19, '1687464096640', 0, 'HTTP - Unauthorized', 'error de ', '2023-06-22 20:05:29'),
(20, 20, '1687464101023', 0, 'HTTP - Unauthorized', 'error de ', '2023-06-22 20:05:33'),
(21, 21, '1687464107029', 0, 'HTTP - Unauthorized', 'error de ', '2023-06-22 20:05:39'),
(22, 22, '1687464111015', 0, 'HTTP - Unauthorized', 'error de ', '2023-06-22 20:05:43'),
(23, 23, '1687464116201', 0, 'HTTP - Unauthorized', 'error de ', '2023-06-22 20:05:48'),
(24, 24, '1687464119587', 0, 'HTTP - Unauthorized', 'error de ', '2023-06-22 20:05:52'),
(25, 25, '1687464122751', 0, 'HTTP - Unauthorized', 'error de ', '2023-06-22 20:05:55'),
(26, 26, '1687464138748', 0, 'HTTP - Unauthorized', 'Error de RUC', '2023-06-22 20:06:11'),
(27, 27, '1687464141917', 0, 'HTTP - Unauthorized', 'Error de RUC', '2023-06-22 20:06:14'),
(28, 28, '1687464147430', 1, 'El Resumen diario RC-20230622-28, ha sido aceptado', 'Error de RUC', '2023-06-22 20:06:21'),
(29, 29, '1687464250920', 0, 'HTTP - Unauthorized', 'Error de RUC', '2023-06-22 20:08:03'),
(30, 30, '1687464254288', 1, 'El Resumen diario RC-20230622-30, ha sido aceptado', 'Error de RUC', '2023-06-22 20:08:07'),
(31, 31, '1687464349552', 0, 'HTTP - Unauthorized', 'Error de RUC', '2023-06-22 20:09:42'),
(32, 32, '1687464353206', 1, 'La Comunicacion de baja RA-20230622-32, ha sido aceptada', 'Error de RUC', '2023-06-22 20:09:47'),
(33, 33, '1687465086819', 1, 'El Resumen diario RC-20230622-33, ha sido aceptado', 'devolucion de producto', '2023-06-22 20:21:59');

--
-- 덤프된 테이블의 인덱스
--

--
-- 테이블의 인덱스 `sunat_resume`
--
ALTER TABLE `sunat_resume`
  ADD PRIMARY KEY (`id`);

--
-- 덤프된 테이블의 AUTO_INCREMENT
--

--
-- 테이블의 AUTO_INCREMENT `sunat_resume`
--
ALTER TABLE `sunat_resume`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

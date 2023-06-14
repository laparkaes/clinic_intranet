-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- 생성 시간: 23-06-15 01:26
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
  `voucher_type_id` int(11) NOT NULL,
  `sale_id` int(11) NOT NULL,
  `sale_type_id` int(11) NOT NULL,
  `payment_method_id` int(11) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `status_id` int(11) NOT NULL,
  `sunat_sent` tinyint(1) DEFAULT NULL,
  `sunat_msg` varchar(250) DEFAULT NULL,
  `correlative` int(11) NOT NULL,
  `received` float NOT NULL,
  `change` float NOT NULL,
  `legend` varchar(250) NOT NULL,
  `hash` varchar(50) NOT NULL,
  `registed_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 테이블의 덤프 데이터 `voucher`
--

INSERT INTO `voucher` (`id`, `voucher_type_id`, `sale_id`, `sale_type_id`, `payment_method_id`, `client_id`, `status_id`, `sunat_sent`, `sunat_msg`, `correlative`, `received`, `change`, `legend`, `hash`, `registed_at`) VALUES
(2, 2, 100, 2, 1, 237, 5, 0, 'Sunat no chambea.', 1, 120, 0, 'CIENTO VEINTE CON 00/100 SOLES', 'qAfwVkCGVAfYSEDrKpgVmrE/3AFG', '2023-06-14 21:42:58'),
(3, 1, 99, 1, 1, 228, 5, 0, 'Sunat no chambea.', 1, 6750, 0, 'SEIS MIL SETECIENTOS CINCUENTA CON 00/100 SOLES', 'vkI8aEHr4AiyadxIlhcaajBrV/2O', '2023-06-14 21:44:59'),
(6, 1, 96, 1, 1, 238, 5, 0, 'Sunat no chambea.', 2, 10720, 0, 'DIEZ MIL SETECIENTOS VEINTE CON 00/100 SOLES', 'poDhFIf/qCARaKv1nCr.RZAX4dJW', '2023-06-14 22:01:20'),
(7, 1, 95, 2, 2, 228, 5, 0, 'Sunat no chambea.', 1, 840, 0, 'OCHOCIENTOS CUARENTA CON 00/100 SOLES', '12bSpiaatnF0QhhOq30S9SowYhtW', '2023-06-14 22:22:38');

--
-- 덤프된 테이블의 인덱스
--

--
-- 테이블의 인덱스 `voucher`
--
ALTER TABLE `voucher`
  ADD PRIMARY KEY (`id`);

--
-- 덤프된 테이블의 AUTO_INCREMENT
--

--
-- 테이블의 AUTO_INCREMENT `voucher`
--
ALTER TABLE `voucher`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

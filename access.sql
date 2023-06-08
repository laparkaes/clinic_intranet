-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- 생성 시간: 23-06-09 01:14
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
-- 테이블 구조 `access`
--

CREATE TABLE `access` (
  `id` int(11) NOT NULL,
  `module` varchar(50) NOT NULL,
  `description` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 테이블의 덤프 데이터 `access`
--

INSERT INTO `access` (`id`, `module`, `description`) VALUES
(1, 'dashboard', 'index'),
(2, 'appointment', 'index'),
(3, 'appointment', 'detail'),
(4, 'appointment', 'register'),
(5, 'appointment', 'update'),
(6, 'appointment', 'update_medical_attention'),
(7, 'appointment', 'report'),
(8, 'doctor', 'index'),
(9, 'doctor', 'detail'),
(10, 'doctor', 'register'),
(11, 'doctor', 'update'),
(12, 'patient', 'index'),
(13, 'patient', 'detail'),
(14, 'patient', 'register'),
(15, 'patient', 'update'),
(16, 'product', 'index'),
(17, 'product', 'detail'),
(18, 'product', 'register'),
(19, 'product', 'update'),
(20, 'product', 'delete'),
(21, 'product', 'admin_category'),
(22, 'report', 'index'),
(23, 'sale', 'index'),
(24, 'sale', 'detail'),
(25, 'sale', 'register'),
(26, 'sale', 'cancel'),
(27, 'sale', 'admin_payment'),
(28, 'sale', 'admin_voucher'),
(29, 'surgery', 'index'),
(30, 'surgery', 'detail'),
(31, 'surgery', 'register'),
(32, 'surgery', 'update'),
(33, 'surgery', 'report'),
(34, 'config', 'index'),
(35, 'config', 'admin_log'),
(36, 'config', 'admin_access'),
(37, 'config', 'admin_profile'),
(38, 'config', 'admin_medicine'),
(39, 'config', 'update_company'),
(40, 'account', 'index'),
(41, 'account', 'register'),
(42, 'account', 'reset_password'),
(43, 'account', 'delete'),
(112, 'config', 'admin_image');

--
-- 덤프된 테이블의 인덱스
--

--
-- 테이블의 인덱스 `access`
--
ALTER TABLE `access`
  ADD PRIMARY KEY (`id`);

--
-- 덤프된 테이블의 AUTO_INCREMENT
--

--
-- 테이블의 AUTO_INCREMENT `access`
--
ALTER TABLE `access`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

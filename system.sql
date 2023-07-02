-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- 생성 시간: 23-07-02 23:53
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
-- 테이블 구조 `system`
--

CREATE TABLE `system` (
  `id` int(11) NOT NULL,
  `is_finished` tinyint(1) NOT NULL,
  `company_id` int(11) DEFAULT NULL,
  `account_id` int(11) DEFAULT NULL,
  `sunat_access` tinyint(1) DEFAULT NULL,
  `sunat_certificate` varchar(20) DEFAULT NULL,
  `sunat_ruc` varchar(20) DEFAULT NULL,
  `sunat_username` varchar(50) DEFAULT NULL,
  `sunat_password` varchar(50) DEFAULT NULL,
  `sale_type_finished` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 테이블의 덤프 데이터 `system`
--

INSERT INTO `system` (`id`, `is_finished`, `company_id`, `account_id`, `sunat_access`, `sunat_certificate`, `sunat_ruc`, `sunat_username`, `sunat_password`, `sale_type_finished`) VALUES
(1, 0, 1, 84, NULL, 'Catálogo Yeollin Ses', '12345789', 'Fuldady', 'asdf3dsf', 1);

--
-- 덤프된 테이블의 인덱스
--

--
-- 테이블의 인덱스 `system`
--
ALTER TABLE `system`
  ADD PRIMARY KEY (`id`);

--
-- 덤프된 테이블의 AUTO_INCREMENT
--

--
-- 테이블의 AUTO_INCREMENT `system`
--
ALTER TABLE `system`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

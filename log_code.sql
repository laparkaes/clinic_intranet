-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- 생성 시간: 23-04-19 16:46
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
-- 테이블 구조 `log_code`
--

CREATE TABLE `log_code` (
  `id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 테이블의 덤프 데이터 `log_code`
--

INSERT INTO `log_code` (`id`, `code`) VALUES
(1, 'doctor_register'),
(2, 'doctor_update'),
(3, 'account_update'),
(4, 'password_update'),
(5, 'doctor_enabled'),
(6, 'doctor_disabled'),
(7, 'person_register'),
(8, 'person_update'),
(9, 'file_upload'),
(10, 'file_delete');

--
-- 덤프된 테이블의 인덱스
--

--
-- 테이블의 인덱스 `log_code`
--
ALTER TABLE `log_code`
  ADD PRIMARY KEY (`id`);

--
-- 덤프된 테이블의 AUTO_INCREMENT
--

--
-- 테이블의 AUTO_INCREMENT `log_code`
--
ALTER TABLE `log_code`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

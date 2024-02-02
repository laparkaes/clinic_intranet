-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- 생성 시간: 24-02-02 15:53
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
-- 데이터베이스: `jweverlyn`
--

-- --------------------------------------------------------

--
-- 테이블 구조 `inoutcome`
--

CREATE TABLE `inoutcome` (
  `inoutcome_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `description` varchar(250) NOT NULL,
  `amount` double NOT NULL,
  `valid` tinyint(1) NOT NULL DEFAULT 1,
  `registed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 테이블 구조 `inoutcome_category`
--

CREATE TABLE `inoutcome_category` (
  `category_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `category` varchar(200) NOT NULL,
  `valid` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 테이블 구조 `inoutcome_type`
--

CREATE TABLE `inoutcome_type` (
  `type_id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 테이블의 덤프 데이터 `inoutcome_type`
--

INSERT INTO `inoutcome_type` (`type_id`, `type`) VALUES
(1, 'Egreso'),
(2, 'Ingreso');

--
-- 덤프된 테이블의 인덱스
--

--
-- 테이블의 인덱스 `inoutcome`
--
ALTER TABLE `inoutcome`
  ADD PRIMARY KEY (`inoutcome_id`),
  ADD KEY `fk_inoutcome_type` (`type_id`),
  ADD KEY `fk_inoutcome_category` (`category_id`);

--
-- 테이블의 인덱스 `inoutcome_category`
--
ALTER TABLE `inoutcome_category`
  ADD PRIMARY KEY (`category_id`),
  ADD KEY `fk_type_category_inoutcome` (`type_id`);

--
-- 테이블의 인덱스 `inoutcome_type`
--
ALTER TABLE `inoutcome_type`
  ADD PRIMARY KEY (`type_id`);

--
-- 덤프된 테이블의 AUTO_INCREMENT
--

--
-- 테이블의 AUTO_INCREMENT `inoutcome`
--
ALTER TABLE `inoutcome`
  MODIFY `inoutcome_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 테이블의 AUTO_INCREMENT `inoutcome_category`
--
ALTER TABLE `inoutcome_category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 테이블의 AUTO_INCREMENT `inoutcome_type`
--
ALTER TABLE `inoutcome_type`
  MODIFY `type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 덤프된 테이블의 제약사항
--

--
-- 테이블의 제약사항 `inoutcome`
--
ALTER TABLE `inoutcome`
  ADD CONSTRAINT `fk_inoutcome_category` FOREIGN KEY (`category_id`) REFERENCES `inoutcome_category` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_inoutcome_type` FOREIGN KEY (`type_id`) REFERENCES `inoutcome_type` (`type_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- 테이블의 제약사항 `inoutcome_category`
--
ALTER TABLE `inoutcome_category`
  ADD CONSTRAINT `fk_type_category_inoutcome` FOREIGN KEY (`type_id`) REFERENCES `inoutcome_type` (`type_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

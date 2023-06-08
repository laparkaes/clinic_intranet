-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- 생성 시간: 23-06-09 01:13
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
-- 테이블 구조 `image`
--

CREATE TABLE `image` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 테이블의 덤프 데이터 `image`
--

INSERT INTO `image` (`id`, `category_id`, `name`) VALUES
(1, 1, 'Radiografía de abdomen'),
(2, 1, 'Radiografía de cadera'),
(3, 1, 'Radiografía de columna cervical'),
(4, 1, 'Radiografía de columna vertebral (radiografía de columna)'),
(5, 1, 'Radiografía de extremidades'),
(6, 1, 'Radiografía de huesos (radiografía ósea)'),
(7, 1, 'Radiografía de pelvis'),
(8, 1, 'Radiografía de senos paranasales'),
(9, 1, 'Radiografía de tórax (radiografía de tórax)'),
(10, 1, 'Radiografía dental (radiografía panorámica dental)'),
(11, 2, 'Ecografía abdominal'),
(12, 2, 'Ecografía cardíaca (ecocardiografía)'),
(13, 2, 'Ecografía de partes blandas'),
(14, 2, 'Ecografía de tiroides'),
(15, 2, 'Ecografía ginecológica'),
(16, 2, 'Ecografía mamaria'),
(17, 2, 'Ecografía musculoesquelética'),
(18, 2, 'Ecografía obstétrica'),
(19, 2, 'Ecografía transvaginal'),
(20, 2, 'Ecografía vascular');

--
-- 덤프된 테이블의 인덱스
--

--
-- 테이블의 인덱스 `image`
--
ALTER TABLE `image`
  ADD PRIMARY KEY (`id`);

--
-- 덤프된 테이블의 AUTO_INCREMENT
--

--
-- 테이블의 AUTO_INCREMENT `image`
--
ALTER TABLE `image`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

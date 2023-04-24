-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- 생성 시간: 23-04-24 07:18
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
-- 테이블 구조 `specialty`
--

CREATE TABLE `specialty` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 테이블의 덤프 데이터 `specialty`
--

INSERT INTO `specialty` (`id`, `name`) VALUES
(1, 'Administración y Gestión en Salud'),
(2, 'Anestesiología'),
(3, 'Cardiología'),
(4, 'Cirugía General'),
(5, 'Endocrinología'),
(6, 'Gastroenterología'),
(7, 'Geriatría'),
(8, 'Ginecología y Obstetricia'),
(9, 'Medicina de Emergencias y Desastres'),
(10, 'Medicina de Enfermedades Infecciosas y Tropicales'),
(11, 'Medicina Familiar y Comunitaria'),
(12, 'Medicina Intensiva'),
(13, 'Medicina Intensiva Pediátrica'),
(14, 'Medicina Interna'),
(15, 'Medicina Oncológica'),
(16, 'Nefrología'),
(17, 'Neonatología'),
(18, 'Neumología'),
(19, 'Neurocirugía'),
(20, 'Neurología'),
(21, 'Ortopedia y Traumatología'),
(22, 'Patología Clínica'),
(23, 'Pediatría'),
(24, 'Psiquiatría'),
(25, 'Radiología');

--
-- 덤프된 테이블의 인덱스
--

--
-- 테이블의 인덱스 `specialty`
--
ALTER TABLE `specialty`
  ADD PRIMARY KEY (`id`);

--
-- 덤프된 테이블의 AUTO_INCREMENT
--

--
-- 테이블의 AUTO_INCREMENT `specialty`
--
ALTER TABLE `specialty`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=301;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

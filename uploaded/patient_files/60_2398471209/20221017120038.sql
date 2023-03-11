-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- 생성 시간: 22-10-11 08:14
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
-- 테이블 구조 `appointment_anamnesis`
--

CREATE TABLE `appointment_anamnesis` (
  `id` int(11) NOT NULL,
  `appointment_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `age` int(11) NOT NULL,
  `sex` varchar(1) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `birthplace` varchar(200) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `tel` varchar(30) DEFAULT NULL,
  `responsible` varchar(50) DEFAULT NULL,
  `provenance_place` varchar(100) DEFAULT NULL,
  `last_trips` varchar(200) DEFAULT NULL,
  `race` varchar(30) DEFAULT NULL,
  `civil_status` int(11) DEFAULT NULL,
  `occupation` varchar(30) DEFAULT NULL,
  `religion` varchar(30) DEFAULT NULL,
  `illness_time` varchar(200) DEFAULT NULL,
  `illness_start` varchar(200) DEFAULT NULL,
  `illness_course` varchar(200) DEFAULT NULL,
  `illness_main_Symptoms` text DEFAULT NULL,
  `illness_story` text DEFAULT NULL,
  `func_bio_appetite` varchar(200) DEFAULT NULL,
  `func_bio_urine` varchar(200) DEFAULT NULL,
  `func_bio_thirst` varchar(200) DEFAULT NULL,
  `func_bio_bowel_movements` varchar(200) DEFAULT NULL,
  `func_bio_sweat` varchar(200) DEFAULT NULL,
  `func_bio_weight` varchar(200) DEFAULT NULL,
  `func_bio_sleep` varchar(200) DEFAULT NULL,
  `func_bio_encouragement` varchar(200) DEFAULT NULL,
  `patho_pre_illnesses` varchar(250) DEFAULT NULL,
  `patho_pre_hospitalization` text DEFAULT NULL,
  `patho_pre_surgery` text DEFAULT NULL,
  `patho_ram` varchar(200) DEFAULT NULL,
  `patho_transfusion` varchar(200) DEFAULT NULL,
  `patho_pre_medication` varchar(200) DEFAULT NULL,
  `gyne_fur` varchar(200) DEFAULT NULL,
  `gyne_g` varchar(200) DEFAULT NULL,
  `gyne_p` varchar(200) DEFAULT NULL,
  `gyne_mac` varchar(200) DEFAULT NULL,
  `family_history` text DEFAULT NULL,
  `registed_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 덤프된 테이블의 인덱스
--

--
-- 테이블의 인덱스 `appointment_anamnesis`
--
ALTER TABLE `appointment_anamnesis`
  ADD PRIMARY KEY (`id`);

--
-- 덤프된 테이블의 AUTO_INCREMENT
--

--
-- 테이블의 AUTO_INCREMENT `appointment_anamnesis`
--
ALTER TABLE `appointment_anamnesis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- 생성 시간: 25-10-05 17:23
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
-- 데이터베이스: `llamasys`
--

-- --------------------------------------------------------

--
-- 테이블 구조 `lgepr_closed_order`
--

CREATE TABLE `lgepr_closed_order` (
  `order_id` int(11) NOT NULL,
  `dash_company` varchar(30) DEFAULT NULL,
  `dash_division` varchar(30) DEFAULT NULL,
  `category` text DEFAULT NULL,
  `bill_to_name` text DEFAULT NULL,
  `ship_to_name` text DEFAULT NULL,
  `customer_name` varchar(100) DEFAULT NULL,
  `model` text DEFAULT NULL,
  `order_qty` int(11) DEFAULT NULL,
  `total_amount_usd` float DEFAULT NULL,
  `total_amount` float DEFAULT NULL,
  `order_amount_usd` float DEFAULT NULL,
  `order_amount` float DEFAULT NULL,
  `line_charge_amount` float DEFAULT NULL,
  `header_charge_amount` float DEFAULT NULL,
  `tax_amount` float DEFAULT NULL,
  `dc_amount` float DEFAULT NULL,
  `dc_rate` float DEFAULT NULL,
  `currency` varchar(5) DEFAULT NULL,
  `inventory_org` text DEFAULT NULL,
  `sub_inventory` text DEFAULT NULL,
  `sales_person` text DEFAULT NULL,
  `customer_department` text DEFAULT NULL,
  `product_level1_name` text DEFAULT NULL,
  `product_level2_name` text DEFAULT NULL,
  `product_level3_name` text DEFAULT NULL,
  `product_level4_name` text DEFAULT NULL,
  `model_category` text DEFAULT NULL,
  `item_weight` float DEFAULT NULL,
  `item_cbm` float DEFAULT NULL,
  `order_date` date DEFAULT NULL,
  `shipment_date` date DEFAULT NULL,
  `closed_date` date DEFAULT NULL,
  `bill_to_code` text DEFAULT NULL,
  `ship_to_code` text DEFAULT NULL,
  `ship_to_city` text DEFAULT NULL,
  `sales_channel` text DEFAULT NULL,
  `order_source` text DEFAULT NULL,
  `order_type` varchar(50) DEFAULT NULL,
  `order_line` text DEFAULT NULL,
  `order_no` text DEFAULT NULL,
  `line_no` text DEFAULT NULL,
  `customer_po_no` text DEFAULT NULL,
  `project_code` text DEFAULT NULL,
  `product_level4` text DEFAULT NULL,
  `receiver_city` text DEFAULT NULL,
  `invoice_no` text DEFAULT NULL,
  `invoice_date` date DEFAULT NULL,
  `shipping_method` text DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 테이블 구조 `lgepr_sales_order`
--

CREATE TABLE `lgepr_sales_order` (
  `sales_order_id` int(11) NOT NULL,
  `dash_company` varchar(30) DEFAULT NULL,
  `dash_division` varchar(30) DEFAULT NULL,
  `bill_to_name` varchar(30) DEFAULT NULL,
  `ship_to_name` varchar(30) DEFAULT NULL,
  `customer_name` varchar(100) DEFAULT NULL,
  `customer_po_no` varchar(100) DEFAULT NULL,
  `customer_po_date` date DEFAULT NULL,
  `model` varchar(30) DEFAULT NULL,
  `order_line` varchar(30) DEFAULT NULL,
  `order_no` varchar(30) DEFAULT NULL,
  `line_no` varchar(30) DEFAULT NULL,
  `order_type` varchar(30) DEFAULT NULL,
  `line_status` varchar(30) DEFAULT NULL,
  `so_status` varchar(50) DEFAULT NULL,
  `ordered_qty` int(11) DEFAULT NULL,
  `cbm` float DEFAULT NULL,
  `unit_selling_price` float DEFAULT NULL,
  `sales_amount` float DEFAULT NULL,
  `sales_amount_usd` float DEFAULT NULL,
  `tax_amount` float DEFAULT NULL,
  `charge_amount` float DEFAULT NULL,
  `line_total` float DEFAULT NULL,
  `currency` varchar(30) DEFAULT NULL,
  `booked_date` date DEFAULT NULL,
  `req_arrival_date_to` date DEFAULT NULL,
  `shipment_date` date DEFAULT NULL,
  `appointment_date` date DEFAULT NULL,
  `bill_to` varchar(30) DEFAULT NULL,
  `customer_department` varchar(30) DEFAULT NULL,
  `ship_to` varchar(30) DEFAULT NULL,
  `inventory_org` varchar(30) DEFAULT NULL,
  `sub_inventory` text DEFAULT NULL,
  `order_status` varchar(30) DEFAULT NULL,
  `order_category` varchar(30) DEFAULT NULL,
  `receiver_city` text DEFAULT NULL,
  `item_division` varchar(30) DEFAULT NULL,
  `product_level1_name` varchar(100) DEFAULT NULL,
  `product_level2_name` varchar(100) DEFAULT NULL,
  `product_level3_name` varchar(100) DEFAULT NULL,
  `product_level4_name` varchar(100) DEFAULT NULL,
  `product_level4_code` varchar(100) DEFAULT NULL,
  `model_category` varchar(30) DEFAULT NULL,
  `item_type_desctiption` varchar(30) DEFAULT NULL,
  `create_date` date DEFAULT NULL,
  `hold_flag` char(1) DEFAULT NULL,
  `instock_flag` char(1) DEFAULT NULL,
  `back_order_hold` char(1) DEFAULT NULL,
  `credit_hold` char(1) DEFAULT NULL,
  `overdue_hold` char(1) DEFAULT NULL,
  `customer_hold` char(1) DEFAULT NULL,
  `payterm_term_hold` char(1) DEFAULT NULL,
  `fp_hold` char(1) DEFAULT NULL,
  `minimum_hold` char(1) DEFAULT NULL,
  `future_hold` char(1) DEFAULT NULL,
  `reserve_hold` char(1) DEFAULT NULL,
  `manual_hold` char(1) DEFAULT NULL,
  `auto_pending_hold` char(1) DEFAULT NULL,
  `sa_hold` char(1) DEFAULT NULL,
  `form_hold` char(1) DEFAULT NULL,
  `bank_collateral_hold` char(1) DEFAULT NULL,
  `insurance_hold` char(1) DEFAULT NULL,
  `partial_flag` char(1) DEFAULT NULL,
  `load_hold_flag` char(1) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `om_line_status` varchar(50) DEFAULT NULL,
  `om_appointment` timestamp NULL DEFAULT NULL,
  `om_appointment_remark` text DEFAULT NULL,
  `om_updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 덤프된 테이블의 인덱스
--

--
-- 테이블의 인덱스 `lgepr_closed_order`
--
ALTER TABLE `lgepr_closed_order`
  ADD PRIMARY KEY (`order_id`);

--
-- 테이블의 인덱스 `lgepr_sales_order`
--
ALTER TABLE `lgepr_sales_order`
  ADD PRIMARY KEY (`sales_order_id`);

--
-- 덤프된 테이블의 AUTO_INCREMENT
--

--
-- 테이블의 AUTO_INCREMENT `lgepr_closed_order`
--
ALTER TABLE `lgepr_closed_order`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 테이블의 AUTO_INCREMENT `lgepr_sales_order`
--
ALTER TABLE `lgepr_sales_order`
  MODIFY `sales_order_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

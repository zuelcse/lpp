-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 22, 2026 at 03:00 AM
-- Server version: 8.0.45
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lpp.alisoftbd`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `create_at` datetime NOT NULL,
  `update_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`, `create_at`, `update_at`) VALUES
(1, 'AC', '2025-08-30 18:32:38', '2025-08-30 18:32:38'),
(2, 'FAN', '2025-08-30 18:33:08', '2025-08-30 18:33:08'),
(3, 'Bulb', '2025-08-30 18:33:08', '2025-08-30 18:33:08');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `general_settings`
--

CREATE TABLE `general_settings` (
  `id` bigint UNSIGNED NOT NULL,
  `logo` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `favicon` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `site_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `copyright` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `loader` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `default_image` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_address` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `company_phone` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `company_email` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `facebook` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `instrgram` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `youtube` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `twitter` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `linkedin` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `app_version_code` int DEFAULT NULL,
  `android_app_version` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `android_app_link` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `android_app_version_msg` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `general_settings`
--

INSERT INTO `general_settings` (`id`, `logo`, `favicon`, `site_name`, `title`, `copyright`, `loader`, `default_image`, `company_address`, `description`, `company_phone`, `company_email`, `facebook`, `instrgram`, `youtube`, `twitter`, `linkedin`, `app_version_code`, `android_app_version`, `android_app_link`, `android_app_version_msg`, `created_at`, `updated_at`) VALUES
(1, 'apdfsdg.png', 'l3i2qdkeph1730053940.png', 'Trading Solutions', 'Trading Solutions', '© All Copyright | Alisoft (BD)', 'ummzb8icis1729322727.jpg', 'vqlmdgs1h41730053940.png', 'House: 38/5, Road: 11, Kallyanpur, Dhaka, Bangladesh\nwww.prottoysolutions.com', NULL, '+8801999569856', 'alisoftbdinfo@gmail.com', 'https://www.facebook.com/', 'https://www.instagram.com/', 'https://youtube.com/', 'twitter.com', 'linkedin.com', 1, '1.0.0', NULL, 'মোবাইল অ্যাপটির একটি নতুন ভার্সন প্রকাশিত হয়েছে।\nপ্রথমে অ্যাপটির নতুন ভার্সন ডাউনলোড করুন। তারপরে পুরানোটি আনইনস্টল করুন এবং অ্যাপটির নতুন ভার্সনটি পুনরায় ইনস্টল করুন। ধন্যবাদ', '2026-05-20 09:23:17', '2026-05-20 09:23:22');

-- --------------------------------------------------------

--
-- Table structure for table `group_main`
--

CREATE TABLE `group_main` (
  `id` bigint UNSIGNED NOT NULL,
  `alias` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `group_type` enum('Asset','Liability','Income','Expense','Capital') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `preferred_side` enum('Dr','Cr') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_editable` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 = user can edit/delete',
  `is_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 = active,\r\n0 = inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `group_main`
--

INSERT INTO `group_main` (`id`, `alias`, `name`, `group_type`, `preferred_side`, `is_editable`, `is_active`, `created_at`, `updated_at`) VALUES
(1, '01', 'Share Capital', 'Asset', 'Cr', 0, 1, '2025-08-31 17:30:07', '2025-08-30 18:00:00'),
(2, '02', 'Reserves & Surplus', 'Asset', 'Cr', 0, 1, '2025-08-30 18:00:00', '2025-08-30 18:00:00'),
(3, '03', 'Long Term Loan', 'Asset', 'Cr', 0, 1, '2025-01-31 17:30:53', '2025-08-30 18:00:00'),
(4, '04', 'Deferred Liabilities', 'Asset', 'Cr', 0, 1, '2025-08-31 17:33:17', '2025-08-30 18:00:00'),
(5, '05', 'Loans', 'Asset', 'Cr', 0, 1, '2025-08-30 18:00:00', '2025-08-30 18:00:00'),
(6, '06', 'Interest Payable', 'Asset', 'Cr', 0, 1, '2025-08-30 18:00:00', '2025-08-30 18:00:00'),
(7, '07', 'Creditors', 'Asset', 'Cr', 0, 1, '2025-08-30 18:00:00', '2025-08-30 18:00:00'),
(8, '08', 'Accured Expenses', 'Asset', 'Cr', 0, 1, '2025-08-30 18:00:00', '2025-08-30 18:00:00'),
(9, '09', 'Liabilities for other Finance', 'Asset', 'Cr', 0, 1, '2025-08-30 18:00:00', '2025-08-30 18:00:00'),
(10, '10', 'Liabilities for Capital Expenditure', 'Asset', 'Cr', 0, 1, '2025-08-30 18:00:00', '2025-08-30 18:00:00'),
(11, '11', 'Provision for Taxation', 'Asset', 'Cr', 0, 1, '2025-08-30 18:00:00', '2025-08-30 18:00:00'),
(12, '12', 'Dividend Payable', 'Asset', 'Cr', 0, 1, '2025-08-30 18:00:00', '2025-08-30 18:00:00'),
(13, '13', 'Fixed Assets', 'Asset', 'Cr', 0, 1, '2025-08-30 18:00:00', '2025-08-30 18:00:00'),
(14, '14', 'Accumulated Depreciation', 'Asset', 'Cr', 0, 1, '2025-08-30 18:00:00', '2025-08-30 18:00:00'),
(15, '15', 'Capital Work-in-progress', 'Asset', 'Cr', 0, 1, '2025-08-30 18:00:00', '2025-08-30 18:00:00'),
(16, '16', 'Deferred Expenses', 'Asset', 'Cr', 0, 1, '2025-08-30 18:00:00', '2025-08-30 18:00:00'),
(17, '17', 'Investment', 'Asset', 'Cr', 0, 1, '2025-08-30 18:00:00', '2025-08-30 18:00:00'),
(18, '18', 'Raw Materials', 'Asset', 'Cr', 0, 1, '2025-08-30 18:00:00', '2025-08-30 18:00:00'),
(19, '19', 'Packing Materials', 'Asset', 'Cr', 0, 1, '2025-08-30 18:00:00', '2025-08-30 18:00:00'),
(20, '20', 'Chemicals', 'Asset', 'Cr', 0, 1, '2025-08-30 18:00:00', '2025-08-30 18:00:00'),
(21, '21', 'Materials-in-transit', 'Asset', 'Cr', 0, 1, '2025-08-30 18:00:00', '2025-08-30 18:00:00'),
(22, '22', 'Work-in-process', 'Asset', 'Cr', 0, 1, '2025-08-30 18:00:00', '2025-08-30 18:00:00'),
(23, '23', 'Finished Goods', 'Asset', 'Cr', 0, 1, '2025-08-30 18:00:00', '2025-08-30 18:00:00'),
(24, '24', 'Stores & Spares', 'Asset', 'Cr', 0, 1, '2025-08-30 18:00:00', '2025-08-30 18:00:00'),
(25, '25', 'Debtors', 'Asset', 'Cr', 0, 1, '2025-08-30 18:00:00', '2025-08-30 18:00:00'),
(26, '26', 'Accounts Receivable', 'Asset', 'Cr', 0, 1, '2025-08-30 18:00:00', '2025-08-30 18:00:00'),
(27, '27', 'Advance', 'Asset', 'Cr', 0, 1, '2025-08-30 18:00:00', '2025-08-30 18:00:00'),
(28, '28', 'Deposits', 'Asset', 'Cr', 0, 1, '2025-08-30 18:00:00', '2025-08-30 18:00:00'),
(29, '29', 'Pre-Payments', 'Asset', 'Cr', 0, 1, '2025-08-30 18:00:00', '2025-08-30 18:00:00'),
(30, '30', 'Cash in Hand', 'Asset', 'Cr', 0, 1, '2025-08-30 18:00:00', '2025-08-30 18:00:00'),
(31, '31', 'Cash at Bank', 'Asset', 'Cr', 0, 1, '2025-08-30 18:00:00', '2025-08-30 18:00:00'),
(32, '32', 'Inter Company Current Accounts', 'Asset', 'Cr', 0, 1, '2025-08-30 18:00:00', '2025-08-30 18:00:00'),
(33, '33', 'Revenue Income', 'Asset', 'Cr', 0, 1, '2025-08-30 18:00:00', '2025-08-30 18:00:00'),
(34, '34', 'Excise Duty/VAT-M', 'Asset', 'Cr', 0, 1, '2025-08-30 18:00:00', '2025-08-30 18:00:00'),
(35, '35', 'Consumption Raw Materials', 'Asset', 'Cr', 0, 1, '2025-08-30 18:00:00', '2025-08-30 18:00:00'),
(36, '36', 'Consumption Packing Materials', 'Asset', 'Cr', 0, 1, '2025-08-30 18:00:00', '2025-08-30 18:00:00'),
(37, '37', 'Consumption Chemicals', 'Asset', 'Cr', 0, 1, '2025-08-30 18:00:00', '2025-08-30 18:00:00'),
(38, '38', 'Consumption Stores & Spares', 'Asset', 'Cr', 0, 1, '2025-08-30 18:00:00', '2025-08-30 18:00:00'),
(39, '39', 'Scrap Sales-Raw Materials', 'Asset', 'Cr', 0, 1, '2025-08-30 18:00:00', '2025-08-30 18:00:00'),
(40, '40', 'Scrap Sales - Packing Materials', 'Asset', 'Cr', 0, 1, '2025-08-30 18:00:00', '2025-08-30 18:00:00'),
(41, '41', 'Scrap Sales - Chemicals', 'Asset', 'Cr', 0, 1, '2025-08-30 18:00:00', '2025-08-30 18:00:00'),
(42, '42', 'Scrap Sales - Stores & Spars', 'Asset', 'Cr', 0, 1, '2025-08-30 18:00:00', '2025-08-30 18:00:00'),
(43, '43', 'Inventory Adjustment - Raw Materials', 'Asset', 'Cr', 0, 1, '2025-08-30 18:00:00', '2025-08-30 18:00:00'),
(44, '44', 'Inventory Adjustment - Packing Materials', 'Asset', 'Cr', 0, 1, '2025-08-30 18:00:00', '2025-08-30 18:00:00'),
(45, '45', 'Inventory Adjustment - Chemicals', 'Asset', 'Cr', 0, 1, '2025-08-30 18:00:00', '2025-08-30 18:00:00'),
(46, '46', 'Inventory Adjustment - Stores & Spares', 'Asset', 'Cr', 0, 1, '2025-08-30 18:00:00', '2025-08-30 18:00:00'),
(47, '47', 'Factory Overhead', 'Asset', 'Cr', 0, 1, '2025-08-30 18:00:00', '2025-08-30 18:00:00'),
(48, '48', 'Depreciation - F', 'Asset', 'Cr', 0, 1, '2025-08-30 18:00:00', '2025-08-30 18:00:00'),
(49, '49', 'Administrative Expenses', 'Asset', 'Cr', 0, 1, '2025-08-30 18:00:00', '2025-08-30 18:00:00'),
(50, '50', 'Selling & Distribution Expenses', 'Asset', 'Cr', 0, 1, '2025-08-30 18:00:00', '2025-08-30 18:00:00'),
(51, '51', 'Bank Charges', 'Asset', 'Cr', 0, 1, '2025-08-30 18:00:00', '2025-08-30 18:00:00'),
(52, '52', 'Interest Expenses', 'Asset', 'Cr', 0, 1, '2025-08-30 18:00:00', '2025-08-30 18:00:00'),
(53, '53', 'Non-Operating Income', 'Asset', 'Cr', 0, 1, '2025-08-30 18:00:00', '2025-08-30 18:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `group_main_ac`
--

CREATE TABLE `group_main_ac` (
  `id` bigint UNSIGNED NOT NULL,
  `alias` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `group_type` enum('Asset','Liability','Income','Expense','Capital') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `preferred_side` enum('Dr','Cr') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_editable` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 = user can edit/delete',
  `is_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 = active,\r\n0 = inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `group_main_ac`
--

INSERT INTO `group_main_ac` (`id`, `alias`, `name`, `group_type`, `preferred_side`, `is_editable`, `is_active`, `created_at`, `updated_at`) VALUES
(1, '01', 'Assets', 'Asset', 'Dr', 0, 1, NULL, NULL),
(2, '02', 'Liabilities', 'Liability', 'Cr', 0, 1, NULL, NULL),
(3, '03', 'Income', 'Income', 'Cr', 0, 1, NULL, NULL),
(4, '04', 'Expenses', 'Expense', 'Dr', 0, 1, NULL, NULL),
(5, '05', 'Capital', 'Capital', 'Cr', 0, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `group_sub`
--

CREATE TABLE `group_sub` (
  `id` bigint UNSIGNED NOT NULL,
  `main_group_id` int NOT NULL,
  `alias` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `group_type` enum('Asset','Liability','Income','Expense','Capital') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Inherited from main group',
  `preferred_side` enum('Dr','Cr') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Inherited from main group',
  `is_editable` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 if user can modify',
  `is_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 = active, 0 = inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='sub groups';

--
-- Dumping data for table `group_sub`
--

INSERT INTO `group_sub` (`id`, `main_group_id`, `alias`, `name`, `group_type`, `preferred_side`, `is_editable`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 30, '30001', 'Cash in Hand', 'Asset', 'Dr', 0, 1, '2025-09-27 21:36:58', '2025-09-27 21:36:58'),
(2, 18, '18001', 'Purchase', 'Asset', 'Dr', 0, 1, '2025-10-03 18:54:31', '2025-10-03 18:54:31'),
(3, 33, '33001', 'Sales', 'Asset', 'Dr', 0, 1, '2025-10-03 18:53:48', '2025-10-03 18:53:48'),
(4, 32, '32001', 'Opening Balance', 'Asset', 'Dr', 0, 1, '2025-10-07 16:01:52', '2025-10-07 16:01:52'),
(5, 2, '02001', 'Loans', 'Asset', 'Dr', 1, 1, '2025-08-30 18:00:00', '2025-08-30 18:00:00'),
(6, 5, '05001', 'Payroll', 'Asset', 'Dr', 1, 1, '2025-08-30 18:00:00', '2025-08-30 18:00:00'),
(7, 5, '05002', 'Rent', 'Asset', 'Dr', 1, 1, '2025-08-30 18:00:00', '2025-08-30 18:00:00'),
(8, 5, '05003', 'Utilities', 'Asset', 'Dr', 1, 1, '2025-08-30 18:00:00', '2025-08-30 18:00:00'),
(9, 5, '05004', 'Depreciation', 'Asset', 'Dr', 1, 1, '2025-08-30 18:00:00', '2025-08-30 18:00:00'),
(10, 1, '01004', 'Test W', 'Asset', 'Dr', 1, 1, '2025-09-27 21:31:18', '2025-10-24 07:47:49'),
(11, 1, '01001', 'Cash', 'Asset', 'Dr', 1, 1, '2025-08-30 18:00:00', '2025-10-24 07:07:40'),
(12, 1, '01005', 'Test A', 'Asset', 'Dr', 1, 1, '2025-09-27 21:36:58', '2025-09-27 21:36:58'),
(14, 25, '25001', 'Debtors', 'Asset', 'Dr', 1, 1, '2025-09-30 19:36:22', '2025-09-30 19:36:22'),
(15, 31, '31001', 'Banks', 'Asset', 'Dr', 1, 1, '2025-09-30 21:28:37', '2025-09-30 21:28:37'),
(16, 49, '49001', 'Donation', 'Asset', 'Dr', 1, 1, '2025-10-03 12:48:19', '2025-10-03 12:48:19'),
(18, 1, '01002', 'Property', 'Asset', 'Dr', 1, 1, '2025-01-31 17:30:53', '2025-08-30 18:00:00'),
(19, 7, '07001', 'Supplier', 'Asset', 'Dr', 1, 1, '2025-08-31 17:30:07', '2025-08-30 18:00:00'),
(20, 1, '01003', 'Equipment', 'Asset', 'Dr', 1, 1, '2025-08-31 17:33:17', '2025-08-30 18:00:00'),
(21, 25, '25002', 'Sub Dilar', 'Asset', 'Dr', 1, 1, '2025-10-18 13:36:02', '2025-10-18 13:36:02');

-- --------------------------------------------------------

--
-- Table structure for table `ledgers`
--

CREATE TABLE `ledgers` (
  `id` int NOT NULL,
  `main_group_id` int NOT NULL DEFAULT '0',
  `sub_group_id` int NOT NULL DEFAULT '0',
  `alias` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `name` varchar(1024) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `name_bn` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `is_revenue` tinyint DEFAULT NULL,
  `closing_balance` decimal(17,2) DEFAULT '0.00',
  `credit_limit` decimal(17,2) DEFAULT '0.00',
  `description` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `address` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `address_bn` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `mobile` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `email` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `tax_rate` decimal(9,4) DEFAULT '0.0000',
  `bank_account_holder` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `bank_account_number` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `bank_name` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `bank_branch` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `region_id` int DEFAULT '0',
  `area_id` int DEFAULT '0',
  `territory_id` int DEFAULT '0',
  `group_type` enum('Asset','Liability','Income','Expense','Capital') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `preferred_side` enum('Dr','Cr') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `is_editable` tinyint(1) DEFAULT '1' COMMENT '1 = user can edit/delete',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `ledgers`
--

INSERT INTO `ledgers` (`id`, `main_group_id`, `sub_group_id`, `alias`, `name`, `name_bn`, `is_revenue`, `closing_balance`, `credit_limit`, `description`, `address`, `address_bn`, `mobile`, `email`, `tax_rate`, `bank_account_holder`, `bank_account_number`, `bank_name`, `bank_branch`, `region_id`, `area_id`, `territory_id`, `group_type`, `preferred_side`, `is_editable`, `created_at`, `updated_at`) VALUES
(1, 30, 1, '3000100001', 'Cash in Hand', 'Cash in Hand', NULL, 6897.00, NULL, NULL, NULL, NULL, NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, 0, 0, 0, 'Asset', 'Dr', 0, '2025-09-30 22:41:29', '2026-05-19 13:24:12'),
(2, 18, 2, '1800100001', 'Purchase', NULL, NULL, 12277646.00, NULL, NULL, NULL, NULL, NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, 0, 0, 0, 'Asset', 'Dr', 0, '2025-10-04 00:55:53', '2026-05-07 03:59:12'),
(3, 33, 3, '3300100001', 'Sales', NULL, NULL, -11287.00, NULL, NULL, NULL, NULL, NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, 0, 0, 0, 'Asset', 'Dr', 0, '2025-10-04 00:56:24', '2026-05-18 21:57:03'),
(4, 32, 4, '3200100001', 'Opening Balance', NULL, NULL, -12353812.00, NULL, NULL, NULL, NULL, NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, 0, 0, 0, 'Asset', 'Dr', 0, '2025-10-07 22:02:28', '2026-05-20 11:29:00'),
(11, 25, 14, '2500100002', 'Ajay Saha', 'অজয় সাহা', NULL, 350.00, NULL, NULL, 'Dhonirampur, Comilla.', 'ধনিরামপুর, কুমিল্লা।', NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Asset', 'Dr', 1, '2025-10-01 14:52:36', '2026-05-21 13:51:40'),
(12, 25, 14, '3100100001', 'Ghosh Carton (Babu Arun Ghosh)', 'ঘোষ কার্টুন (বাবু অরুণ ঘোষ)', NULL, -22.00, NULL, NULL, 'Kaliganj, Gazipur.', 'কালীগঞ্জ, গাজীপুর।', NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Asset', 'Dr', 1, '2025-10-01 03:29:13', '2026-05-21 17:16:29'),
(13, 25, 14, '4900100001', 'Anik Ghosh', 'অনিক ঘোষ', NULL, 1000.00, NULL, NULL, 'Monohardi.', 'মনোহরদী।', NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Asset', 'Dr', 1, '2025-10-03 19:15:14', '2026-05-21 17:18:35'),
(14, 25, 14, '2500100001', 'Honest Trade', 'অনেস্ট ট্রেড', NULL, 18335.00, NULL, 'description', 'Narsingdi.', 'নরসিংদী।', NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Asset', 'Dr', 1, '2025-10-01 01:37:51', '2026-05-21 13:49:00'),
(15, 25, 14, '0100100001', 'Annapurna Oil Mill', 'অন্নপূর্ণ অয়েল মিল', NULL, 72425.00, NULL, NULL, 'Narsingdi.', 'নরসিংদী।', '01711333759', NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Asset', 'Dr', 1, '2025-10-04 01:21:44', '2026-05-20 11:29:00'),
(16, 7, 19, '0700100001', 'Sun Paper', 'সান পেপার', NULL, -17485.00, NULL, NULL, 'Nayabazar, Dhaka.', 'নয়াবাজার, ঢাকা।', '01793319166', NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Asset', 'Dr', 1, '2025-09-07 00:36:11', '2026-05-21 13:25:27'),
(17, 25, 14, '2500100003', 'Agraduat Chira Mill', 'অগ্রদূত চিঁড়ার মিল', NULL, -137.00, NULL, NULL, 'Narsingdi.', 'নরসিংদী।', NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Asset', 'Dr', 1, '2025-10-07 23:37:49', '2026-05-21 13:53:08'),
(19, 25, 14, '2500100004', 'Ankur Print Saree', 'অংকুর প্রিন্ট শাড়ী', NULL, -145.00, NULL, NULL, 'Narsingdi.', 'নরসিংদী।', NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Asset', 'Dr', 1, '2025-10-07 23:39:26', '2026-05-21 13:54:12'),
(20, 25, 14, '0700100002', 'Anjan Saha', 'অঞ্জন সাহা', NULL, -244.00, NULL, NULL, 'Narsingdi.', 'নরসিংদী।', NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Asset', 'Dr', 1, '2025-10-08 02:33:36', '2026-05-21 13:11:22'),
(21, 25, 14, '2500200001', 'Online Saree', 'অনলাইল শাড়ী', NULL, -96.00, NULL, NULL, 'Narsingdi.', 'নরসিংদী।', NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Asset', 'Dr', 1, '2025-10-25 22:44:06', '2026-05-21 17:13:42'),
(22, 25, 14, '2500100005', 'Apu Das', 'অপু দাস', NULL, 0.00, 123.00, NULL, 'Velanagar, Narsingdi.', 'ভেলানগর, নরসিংদী।', NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Asset', 'Dr', 1, '2026-05-18 22:24:46', '2026-05-21 13:55:30'),
(23, 25, 14, '4900100002', 'Achal Cloth Store', 'আঁচল ক্লথ ষ্টোর', NULL, 0.00, NULL, NULL, 'Narsingdi.', 'নরসিংদী।', NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Asset', 'Dr', 1, '2026-05-21 17:20:19', '2026-05-21 17:20:19'),
(24, 25, 14, '4900100003', 'Adi Sundari Cloth', 'আদি সুন্দরী ক্লথ', NULL, 0.00, NULL, NULL, 'Narsingdi.', 'নরসিংদী।', NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Asset', 'Dr', 1, '2026-05-21 17:22:04', '2026-05-21 17:22:04'),
(25, 25, 14, '4900100004', 'Asad Enterprise', 'আসাদ এন্টারপ্রাইজ', NULL, 0.00, NULL, NULL, 'Narsingdi.', 'নরসিংদী।', NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Asset', 'Dr', 1, '2026-05-21 17:23:59', '2026-05-21 17:23:59'),
(26, 25, 14, '4900100005', 'Asad Telecom', 'আসাদ টেলিকম', NULL, 0.00, NULL, NULL, 'Narsingdi.', 'নরসিংদী।', NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Asset', 'Dr', 1, '2026-05-21 17:30:37', '2026-05-21 17:30:37'),
(27, 25, 14, '4900100006', 'Abha Saree', 'আভা শাড়ী', NULL, 0.00, NULL, NULL, 'Narsingdi.', 'নরসিংদী।', NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Asset', 'Dr', 1, '2026-05-21 17:32:59', '2026-05-21 17:32:59'),
(28, 25, 14, '4900100007', 'Aftab Uddin Membar', 'আফতাব উদ্দিন মেম্বার', NULL, 0.00, NULL, NULL, 'Narsingdi.', 'নরসিংদী।', NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Asset', 'Dr', 1, '2026-05-21 17:34:52', '2026-05-21 17:34:52'),
(29, 25, 14, '4900100008', 'Abu Hanifa', 'আবু হানিফা', NULL, 0.00, NULL, NULL, 'Baburhat, Narsingdi.', 'বাবুরহাট, নরসিংদী।', NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Asset', 'Dr', 1, '2026-05-21 17:36:34', '2026-05-21 17:36:34'),
(30, 25, 14, '4900100009', 'R. T. Bastraloy', 'আর টি বস্ত্রালয়', NULL, 0.00, NULL, NULL, 'Madhabdi, Narsingdi.', 'মাধবদী, নরসিংদী।', '01732803782', NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Asset', 'Dr', 1, '2026-05-21 17:39:03', '2026-05-21 17:39:03'),
(31, 25, 14, '4900100010', 'Amena Jhal Muri', 'আমেনা ঝাল মুড়ি', NULL, 0.00, NULL, NULL, 'Narsingdi.', 'নরসিংদী।', NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Asset', 'Dr', 1, '2026-05-21 17:40:24', '2026-05-21 17:40:24'),
(32, 25, 14, '4900100011', 'Abul and Sons (Abul Lungi)', 'আবুল এন্ড সন্স (আবুল লুঙ্গী)', NULL, 0.00, NULL, NULL, 'Sekherchor, Narsingdi.', 'সেকেরচর, নরসিংদী।', NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Asset', 'Dr', 1, '2026-05-21 17:42:24', '2026-05-21 17:42:24'),
(33, 25, 14, '4900100012', 'Aarohi Print Saree', 'আরোহী প্রিন্ট শাড়ী', NULL, 0.00, NULL, NULL, 'Narsingdi.', 'নরসিংদী।', NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Asset', 'Dr', 1, '2026-05-21 17:43:04', '2026-05-21 17:43:04'),
(34, 25, 14, '4900100013', 'Al-Hera Lungi', 'আল-হেরা লুঙ্গী', NULL, 0.00, NULL, NULL, 'Narsingdi.', 'নরসিংদী।', NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Asset', 'Dr', 1, '2026-05-21 17:43:42', '2026-05-21 17:43:42'),
(35, 25, 14, '4900100014', 'Adiba Print Saree', 'আদিবা প্রিন্ট শাড়ী', NULL, 0.00, NULL, NULL, 'Sekherchor, Narsingdi.', 'সেকেরচর, নরসিংদী।', NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Asset', 'Dr', 1, '2026-05-21 17:46:38', '2026-05-21 17:46:38'),
(36, 25, 14, '4900100015', 'Ananda Bostraloy', 'আনন্দ বস্ত্রালয়', NULL, 0.00, NULL, NULL, 'Sekherchor, Narsingdi.', 'সেকেরচর, নরসিংদী।', NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Asset', 'Dr', 1, '2026-05-21 17:47:25', '2026-05-21 17:47:25'),
(37, 25, 14, '4900100016', 'Ayesha Print Saree', 'আয়েশা প্রিন্ট শাড়ী', NULL, 0.00, NULL, NULL, 'Sekherchor, Narsingdi.', 'সেকেরচর, নরসিংদী।', NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Asset', 'Dr', 1, '2026-05-21 17:55:00', '2026-05-21 17:55:00'),
(38, 25, 14, '4900100017', 'Amela Jhal Muri, Prop: Hiron Miah', 'আমেলা ঝাল মুড়ি, প্রোঃ হিরণ মিয়া', NULL, 0.00, NULL, NULL, 'Shibpur.', 'শিবপুর।', NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Asset', 'Dr', 1, '2026-05-21 17:56:54', '2026-05-21 17:56:54'),
(39, 25, 14, '4900100018', 'Ina Cloth Store', 'ইনা ক্লথ ষ্টোর', NULL, 0.00, NULL, NULL, 'Sekherchor, Narsingdi.', 'সেকেরচর, নরসিংদী।', NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Asset', 'Dr', 1, '2026-05-21 17:57:43', '2026-05-21 17:57:43'),
(40, 25, 14, '4900100019', 'Iqbal and Brothers, Prop: Golap Miah', 'ইকবাল এন্ড ব্রাদার্স, প্রোঃ গোলাপ মিয়া', NULL, 0.00, NULL, NULL, 'Raipura,  Narsingdi.', 'রায়পুরা, নরসিংদী।', NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Asset', 'Dr', 1, '2026-05-21 17:59:20', '2026-05-21 17:59:20'),
(41, 25, 14, '4900100020', 'Inas Oil Mill', 'ইনাস অয়েল মিল', NULL, 0.00, NULL, NULL, 'Narsingdi.', 'নরসিংদী।', NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Asset', 'Dr', 1, '2026-05-21 17:59:57', '2026-05-21 17:59:57'),
(42, 25, 14, '4900100021', 'Yamin Bostraloy', 'ইয়ামিন বস্ত্রালয়', NULL, 0.00, NULL, NULL, 'Sekherchor, Narsingdi.', 'সেকেরচর, নরসিংদী।', NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Asset', 'Dr', 1, '2026-05-21 18:00:39', '2026-05-21 18:00:39'),
(43, 25, 14, '4900100022', 'Incepta Pharma, Md. Nazrul Islam', 'ইনসেপ্টা ফার্মা, মোঃ নজরুল ইসলাম', NULL, 0.00, NULL, NULL, 'Narsingdi.', 'নরসিংদী।', '01732631893', NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Asset', 'Dr', 1, '2026-05-21 18:02:36', '2026-05-21 18:02:36'),
(44, 25, 14, '4900100023', 'E. P. Electronics, Prop: Sakhawat Hossain (Piyas)', 'ই. পি. ইলেকট্রনিক্স, প্রোঃ সাখাওয়াত হোসেন (পিয়াস)', NULL, 0.00, NULL, NULL, 'Dhaka.', 'ঢাকা।', NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Asset', 'Dr', 1, '2026-05-21 18:12:41', '2026-05-21 18:12:41'),
(45, 25, 14, '4900100024', 'S. R. Carton, MD. A. M. Emran Hasan (Sajib)', 'এস. আর. কার্টুন, মোঃ এ. এম. ইমরান হাসান (সজিব)', NULL, 0.00, NULL, NULL, 'Madhabdi, Narsingdi.', 'মাধবদী, নরসিংদী।', '01686036167', NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Asset', 'Dr', 1, '2026-05-21 18:26:16', '2026-05-21 18:26:16'),
(46, 25, 14, '4900100025', 'A. K. S. Lungi', 'এ. কে. এস. লুঙ্গী', NULL, 0.00, NULL, NULL, 'Narsingdi.', 'নরসিংদী।', NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Asset', 'Dr', 1, '2026-05-21 18:27:19', '2026-05-21 18:27:19'),
(47, 25, 14, '4900100026', 'Kabir Hossain', 'কবির হোসেন', NULL, 0.00, NULL, NULL, 'Itakhola, Shibpur.', 'ইটাখোলা, শিবপুর।', '01912011028', NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Asset', 'Dr', 1, '2026-05-21 18:30:12', '2026-05-21 18:30:12'),
(48, 25, 14, '4900100027', 'Kashmeri Lungi', 'কাশ্মীরী লুঙ্গী', NULL, 0.00, NULL, NULL, 'Baburhat, Narsingdi.', 'বাবুরহাট, নরসিংদী।', NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Asset', 'Dr', 1, '2026-05-21 18:31:11', '2026-05-21 18:31:11'),
(49, 25, 14, '4900100028', 'Krishna Ray', 'কৃষ্ণ রায়', NULL, 0.00, NULL, NULL, 'Madhabdi, Narsingdi.', 'মাধবদী, নরসিংদী।', NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Asset', 'Dr', 1, '2026-05-21 18:31:59', '2026-05-21 18:31:59'),
(50, 25, 14, '4900100029', 'Komal Chandra Gope', 'কমল চন্দ্র গোপ', NULL, 0.00, NULL, NULL, 'Bhatpara, Narsingdi.', 'ভাটপাড়া, নরসিংদী।', NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Asset', 'Dr', 1, '2026-05-21 18:33:21', '2026-05-21 18:33:21'),
(51, 25, 14, '4900100030', 'Kabir Miah (Shalu)', 'কবির মিয়া (শালু)', NULL, 0.00, NULL, NULL, 'Baburhat, Narsingdi.', 'বাবুরহাট, নরসিংদী।', NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Asset', 'Dr', 1, '2026-05-21 18:33:58', '2026-05-21 18:33:58'),
(52, 25, 14, '4900100031', 'Krishnan Lungi', 'কৃষ্ণাণ লুঙ্গী', NULL, 0.00, NULL, NULL, 'Baburhat, Narsingdi.', 'বাবুরহাট, নরসিংদী।', NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Asset', 'Dr', 1, '2026-05-21 18:35:23', '2026-05-21 18:35:23'),
(53, 25, 14, '4900100032', 'Khandani Lungi', 'খান্দানী লুঙ্গী', NULL, 0.00, NULL, NULL, 'Sekherchor, Narsingdi.', 'সেকেরচর, নরসিংদী।', NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Asset', 'Dr', 1, '2026-05-21 18:36:33', '2026-05-21 18:36:33'),
(54, 25, 14, '4900100033', 'Mukto Print, Prop: Babu Gopal Debnath', 'মুক্ত প্রিন্ট, প্রোঃ বাবু গোপাল দেবনাথ', NULL, 0.00, NULL, NULL, 'Narsingdi.', 'নরসিংদী।', NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Asset', 'Dr', 1, '2026-05-21 18:37:47', '2026-05-21 18:37:47'),
(55, 25, 14, '4900100034', 'G. K. Textile, Prop: Babu Gopal Saha', 'জি. কে. টেক্সটাইল, প্রোঃ বাবু গোপাল সাহা', NULL, 0.00, NULL, NULL, 'Narsingdi.', 'নরসিংদী।', NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Asset', 'Dr', 1, '2026-05-21 18:39:15', '2026-05-21 18:39:15'),
(56, 25, 14, '4900100035', 'Gour Vandar', 'গৌর ভান্ডার', NULL, 0.00, NULL, NULL, 'Narsingdi.', 'নরসিংদী।', '01675574327', NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Asset', 'Dr', 1, '2026-05-21 18:41:27', '2026-05-21 18:41:27'),
(57, 25, 14, '4900100036', 'Ghosh and Sons', 'ঘোষ এন্ড সন্স', NULL, 0.00, NULL, NULL, 'Baburhat, Narsingdi.', 'বাবুরহাট, নরসিংদী।', '01711604680', NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Asset', 'Dr', 1, '2026-05-21 18:43:45', '2026-05-21 18:43:45'),
(58, 25, 14, '4900100037', 'Chandan Kumar Saha', 'চন্দন কুমার সাহা', NULL, 0.00, NULL, NULL, 'Narsingdi.', 'নরসিংদী।', '01712559159', NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Asset', 'Dr', 1, '2026-05-21 18:44:34', '2026-05-21 18:44:34'),
(59, 25, 14, '4900100038', 'Cheti Textile', 'চৈতি টেক্সটাইল', NULL, 0.00, NULL, NULL, 'Madhabdi, Narsingdi.', 'মাধবদী, নরসিংদী।', '01714435224', NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Asset', 'Dr', 1, '2026-05-21 18:45:36', '2026-05-21 18:45:36'),
(60, 25, 14, '4900100039', 'Chand Batik Four Piece', 'চাঁদ বাটিক ফোর পিছ', NULL, 0.00, NULL, NULL, 'Baburhat, Narsingdi.', 'বাবুরহাট, নরসিংদী।', NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Asset', 'Dr', 1, '2026-05-21 18:46:28', '2026-05-21 18:46:28'),
(61, 25, 14, '4900100040', 'Chanda Print Saree', 'ছন্দা প্রিন্ট শাড়ী', NULL, 0.00, NULL, NULL, 'Narsingdi.', 'নরসিংদী।', NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Asset', 'Dr', 1, '2026-05-21 18:47:11', '2026-05-21 18:47:11'),
(62, 25, 14, '4900100041', 'Chafa Oil Mill', 'ছাফা অয়েল মিল', NULL, 0.00, NULL, NULL, 'Narsingdi.', 'নরসিংদী।', '01710556336', NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Asset', 'Dr', 1, '2026-05-21 18:48:14', '2026-05-21 18:48:14'),
(63, 25, 14, '4900100042', 'Kakoli Print Saree, Prop: Babu Jiban Debnath', 'কাকলী প্রিন্ট শাড়ী, প্রোঃ বাবু জীবন দেবনাথ', NULL, 0.00, NULL, NULL, 'Narsingdi.', 'নরসিংদী।', '01751215182', NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Asset', 'Dr', 1, '2026-05-21 18:49:37', '2026-05-21 18:49:37'),
(64, 25, 14, '4900100043', 'Mayer Doa Bed Sheet, Prop: Janab Zinnat Ali', 'মায়ের দোয়া বেড সিট, প্রোঃ জনাব জিন্নত আলী', NULL, 0.00, NULL, NULL, 'Sekherchor, Narsingdi.', 'সেকেরচর, নরসিংদী।', '01737985914', NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Asset', 'Dr', 1, '2026-05-21 18:50:44', '2026-05-21 18:50:44'),
(65, 25, 14, '4900100044', 'Jonomot Lungi', 'জনমত লুঙ্গী', NULL, 0.00, NULL, NULL, 'Sekherchor, Narsingdi.', 'সেকেরচর, নরসিংদী।', '01921652164', NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Asset', 'Dr', 1, '2026-05-21 18:51:39', '2026-05-21 18:51:39'),
(66, 25, 14, '4900100045', 'Junayed Sarkar', 'জুনায়েদ সরকার', NULL, 0.00, NULL, NULL, 'Narsingdi.', 'নরসিংদী।', '01748398564', NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Asset', 'Dr', 1, '2026-05-21 18:52:49', '2026-05-21 18:52:49'),
(67, 25, 14, '4900100046', 'Jomi Print Sharee', 'জমি প্রিন্ট শাড়ী', NULL, 0.00, NULL, NULL, 'Narsingdi.', 'নরসিংদী।', NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Asset', 'Dr', 1, '2026-05-21 18:53:59', '2026-05-21 18:53:59'),
(68, 25, 14, '4900100047', 'Jewel Miah (Box)', 'জুয়েল মিয়া (বক্স)', NULL, 0.00, NULL, NULL, 'Madhabdi, Narsingdi.', 'মাধবদী, নরসিংদী।', NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Asset', 'Dr', 1, '2026-05-21 18:55:33', '2026-05-21 18:55:33'),
(69, 25, 14, '4900100048', 'Jui Print Sharee', 'জুঁই প্রিন্ট শাড়ী', NULL, 0.00, NULL, NULL, 'Baburhat, Narsingdi.', 'বাবুরহাট, নরসিংদী।', '01717824576', NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Asset', 'Dr', 1, '2026-05-21 18:57:09', '2026-05-21 18:57:09'),
(70, 25, 14, '4900100049', 'Dress Corner Tailors', 'ড্রেস কর্নার টেইলার্স', NULL, 0.00, NULL, NULL, 'Narsingdi.', 'নরসিংদী।', NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Asset', 'Dr', 1, '2026-05-21 18:57:46', '2026-05-21 18:57:46'),
(71, 25, 14, '4900100050', 'D. N. Textile', 'ডি. এন. টেক্সটাইল', NULL, 0.00, NULL, NULL, 'Narsingdi.', 'নরসিংদী।', '01711626256', NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Asset', 'Dr', 1, '2026-05-21 18:58:35', '2026-05-21 18:58:35'),
(72, 25, 14, '4900100051', 'Dhaka Hospital', 'ঢাকা হসপিটাল', NULL, 0.00, NULL, NULL, 'Narsingdi.', 'নরসিংদী।', '01712826727', NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Asset', 'Dr', 1, '2026-05-21 19:02:03', '2026-05-21 19:02:03'),
(73, 25, 14, '4900100052', 'Nurul Islam', 'নুরুল ইসলাম', NULL, 0.00, NULL, NULL, 'Panchdona, Narsingdi.', 'পাঁচদোনা,  নরসিংদী।', NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Asset', 'Dr', 1, '2026-05-21 19:03:30', '2026-05-21 19:03:30'),
(74, 25, 14, '4900100053', 'Narsingdi Cable Networks', 'নরসিংদী ক্যাবল নেটওয়ার্কস', NULL, 0.00, NULL, NULL, 'Narsingdi.', 'নরসিংদী।', NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Asset', 'Dr', 1, '2026-05-21 19:04:47', '2026-05-21 19:04:47'),
(75, 25, 14, '4900100054', 'Nitula', 'নিটুলা', NULL, 0.00, NULL, NULL, 'Narsingdi.', 'নরসিংদী।', '01912087239', NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Asset', 'Dr', 1, '2026-05-21 19:05:45', '2026-05-21 19:05:45'),
(76, 25, 14, '4900100055', 'Sathi Print Saree, Prop: Janab Nazrul Islam', 'সাথী প্রিন্ট শাড়ী, প্রোঃ জনাব নজরুল ইসলাম', NULL, 0.00, NULL, NULL, 'Narsingdi.', 'নরসিংদী।', '01713539550', NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Asset', 'Dr', 1, '2026-05-21 19:06:49', '2026-05-21 19:06:49'),
(77, 25, 14, '4900100056', 'Narayan Chandra Das', 'নারায়ণ চন্দ্র দাস', NULL, 0.00, NULL, NULL, 'Panchdona, Narsingdi.', 'পাঁচদোনা, নরসিংদী।', NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Asset', 'Dr', 1, '2026-05-21 19:07:31', '2026-05-21 19:07:31'),
(78, 25, 14, '4900100057', 'Noni Debnath', 'ননী দেবনাথ', NULL, 0.00, NULL, NULL, 'Narsingdi.', 'নরসিংদী।', NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Asset', 'Dr', 1, '2026-05-21 19:07:57', '2026-05-21 19:07:57'),
(79, 25, 14, '4900100058', 'Naresh Chandra Ghosh', 'নরেশ চন্দ্র ঘোষ', NULL, 0.00, NULL, NULL, 'Narayanganj.', 'নারায়ণগঞ্জ।', NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Asset', 'Dr', 1, '2026-05-21 19:08:56', '2026-05-21 19:08:56'),
(80, 25, 14, '4900100059', 'Narayan Saha', 'নারায়ণ সাহা', NULL, 0.00, NULL, NULL, 'Comilla.', 'কুমিল্লা।', NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Asset', 'Dr', 1, '2026-05-21 19:10:16', '2026-05-21 19:10:16');

-- --------------------------------------------------------

--
-- Table structure for table `location_areas`
--

CREATE TABLE `location_areas` (
  `id` bigint UNSIGNED NOT NULL,
  `region_id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `location_areas`
--

INSERT INTO `location_areas` (`id`, `region_id`, `name`, `created_at`, `updated_at`) VALUES
(1, 1, 'Barisal', '2025-08-31 18:05:08', '2025-08-31 18:00:00'),
(2, 1, 'Barguna', '2025-08-31 18:05:08', '2025-08-31 18:00:00'),
(3, 1, 'Bhola', '2025-08-31 18:05:08', '2025-08-31 18:00:00'),
(4, 1, 'Jhalokati', '2025-08-31 18:05:08', '2025-08-31 18:00:00'),
(5, 1, 'Patuakhali', '2025-08-31 18:05:08', '2025-08-31 18:00:00'),
(6, 1, 'Pirojpur', '2025-08-31 18:05:08', '2025-08-31 18:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `location_regions`
--

CREATE TABLE `location_regions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `location_regions`
--

INSERT INTO `location_regions` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Barisal', '2025-08-31 17:30:07', '2025-08-30 18:00:00'),
(2, 'Chittagong', '2025-08-30 18:00:00', '2025-08-30 18:00:00'),
(3, 'Dhaka', '2025-01-31 17:30:53', '2025-08-30 18:00:00'),
(4, 'Khulna', '2025-08-31 17:33:17', '2025-08-30 18:00:00'),
(5, 'Mymensingh', '2025-08-30 18:00:00', '2025-08-30 18:00:00'),
(6, 'Rajshahi', '2025-08-30 18:00:00', '2025-08-30 18:00:00'),
(7, 'Rangpur', '2025-08-30 18:00:00', '2025-08-30 18:00:00'),
(8, 'Sylhet', '2025-08-30 18:00:00', '2025-08-30 18:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `location_territorys`
--

CREATE TABLE `location_territorys` (
  `id` bigint UNSIGNED NOT NULL,
  `area_id` int NOT NULL,
  `region_id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `location_territorys`
--

INSERT INTO `location_territorys` (`id`, `area_id`, `region_id`, `name`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Barishal Sadar', '2025-08-31 18:14:05', '2025-08-31 18:00:00'),
(2, 1, 1, 'Bakerganj', '2025-08-31 18:14:05', '2025-08-31 18:00:00'),
(3, 1, 1, 'Babuganj', '2025-08-31 18:14:05', '2025-08-31 18:00:00'),
(4, 1, 1, 'Wazirpur', '2025-08-31 18:14:05', '2025-08-31 18:00:00'),
(5, 1, 1, 'Banaripara', '2025-08-31 18:14:05', '2025-08-31 18:00:00'),
(6, 1, 1, 'Gournadi', '2025-08-31 18:14:05', '2025-08-31 18:00:00'),
(7, 1, 1, 'Agailjhara', '2025-08-31 18:14:05', '2025-08-31 18:00:00'),
(8, 1, 1, 'Mehendiganj', '2025-08-31 18:14:05', '2025-08-31 18:00:00'),
(9, 1, 1, 'Muladi', '2025-08-31 18:14:05', '2025-08-31 18:00:00'),
(10, 1, 1, 'Hizla', '2025-08-31 18:14:05', '2025-08-31 18:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `manufacturer`
--

CREATE TABLE `manufacturer` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `create_at` datetime NOT NULL,
  `update_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `manufacturer`
--

INSERT INTO `manufacturer` (`id`, `name`, `create_at`, `update_at`) VALUES
(1, 'Walton', '2025-08-30 18:32:38', '2025-08-30 18:32:38'),
(2, 'RFL', '2025-08-30 18:33:08', '2025-08-30 18:33:08');

-- --------------------------------------------------------

--
-- Table structure for table `master_colors`
--

CREATE TABLE `master_colors` (
  `id` int NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `name_bn` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `master_colors`
--

INSERT INTO `master_colors` (`id`, `name`, `name_bn`, `created_at`, `updated_at`) VALUES
(5, '4 Color', '৪ কালার', '2026-05-20 11:15:03', '2026-05-20 11:15:03');

-- --------------------------------------------------------

--
-- Table structure for table `master_items`
--

CREATE TABLE `master_items` (
  `id` int NOT NULL,
  `voucher_no` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `voucher_type` tinyint NOT NULL COMMENT '1:purchase,\r\n2:sales,\r\n3:PReturn,\r\n4:SReturn',
  `item_id` int NOT NULL DEFAULT '0',
  `debit_head` int NOT NULL,
  `credit_head` int NOT NULL,
  `purchase_quantity` decimal(11,2) DEFAULT NULL,
  `sales_quantity` decimal(11,2) DEFAULT NULL,
  `work_name_id` int DEFAULT '0',
  `work_type_id` int DEFAULT '0',
  `size_id` int DEFAULT '0',
  `color_id` int DEFAULT '0',
  `weight_id` int DEFAULT '0',
  `paper_id` int DEFAULT '0',
  `lamination_id` int DEFAULT '0',
  `note` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `unit` int NOT NULL,
  `rate` decimal(11,2) NOT NULL,
  `amount` decimal(11,2) NOT NULL COMMENT 'before discount',
  `discount_percent` float NOT NULL COMMENT 'discount_percent',
  `discount_amount` float NOT NULL COMMENT 'discount_amount',
  `net_amount` decimal(11,2) NOT NULL COMMENT 'after discount',
  `date` date NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `master_laminations`
--

CREATE TABLE `master_laminations` (
  `id` int NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `name_bn` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `master_laminations`
--

INSERT INTO `master_laminations` (`id`, `name`, `name_bn`, `created_at`, `updated_at`) VALUES
(4, 'Siver', 'সিলভার', '2026-05-20 11:17:38', '2026-05-20 11:17:38');

-- --------------------------------------------------------

--
-- Table structure for table `master_papers`
--

CREATE TABLE `master_papers` (
  `id` int NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `name_bn` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `master_papers`
--

INSERT INTO `master_papers` (`id`, `name`, `name_bn`, `created_at`, `updated_at`) VALUES
(4, 'Art Paper', 'আট্ পেপার', '2026-05-20 11:16:33', '2026-05-20 11:16:33');

-- --------------------------------------------------------

--
-- Table structure for table `master_sizes`
--

CREATE TABLE `master_sizes` (
  `id` int NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `name_bn` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `master_sizes`
--

INSERT INTO `master_sizes` (`id`, `name`, `name_bn`, `created_at`, `updated_at`) VALUES
(6, '2.0Kg', '২.0 কেজি', '2026-05-20 11:03:52', '2026-05-20 11:03:52'),
(7, '1.5 & 2.0Kg', '১.৫ ও ২.0 কেজি', '2026-05-20 11:03:53', '2026-05-20 11:03:53');

-- --------------------------------------------------------

--
-- Table structure for table `master_voucher`
--

CREATE TABLE `master_voucher` (
  `id` int NOT NULL,
  `voucher_no` varchar(20) NOT NULL,
  `voucher_type` tinyint NOT NULL,
  `debit_head` int NOT NULL,
  `credit_head` int NOT NULL,
  `amount` decimal(11,2) NOT NULL,
  `note` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `date` date NOT NULL,
  `status` tinyint NOT NULL DEFAULT '0' COMMENT '-1:softDelete\r\n0:int\r\n1:approved\r\n2:Tally Updated',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `master_voucher`
--

INSERT INTO `master_voucher` (`id`, `voucher_no`, `voucher_type`, `debit_head`, `credit_head`, `amount`, `note`, `date`, `status`, `created_at`, `updated_at`) VALUES
(1, 'JOV2605-1', 13, 15, 4, 72000.00, NULL, '2026-05-20', 0, '2026-05-20 11:29:00', '2026-05-20 11:29:00'),
(2, 'JOV2605-2', 13, 14, 16, 15000.00, '10 Rim 100 GSM Art Paper', '2026-05-20', 0, '2026-05-20 11:45:14', '2026-05-20 11:45:14');

-- --------------------------------------------------------

--
-- Table structure for table `master_weights`
--

CREATE TABLE `master_weights` (
  `id` int NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `name_bn` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `master_weights`
--

INSERT INTO `master_weights` (`id`, `name`, `name_bn`, `created_at`, `updated_at`) VALUES
(5, '100 GSM', '১00 গ্রাম', '2026-05-20 11:15:54', '2026-05-20 11:15:54');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2014_10_12_100000_create_password_resets_table', 1),
(4, '2016_06_01_000001_create_oauth_auth_codes_table', 1),
(5, '2016_06_01_000002_create_oauth_access_tokens_table', 1),
(6, '2016_06_01_000003_create_oauth_refresh_tokens_table', 1),
(7, '2016_06_01_000004_create_oauth_clients_table', 1),
(8, '2016_06_01_000005_create_oauth_personal_access_clients_table', 1),
(9, '2019_08_19_000000_create_failed_jobs_table', 1),
(10, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(11, '2024_06_12_163623_create_stock_items_table', 2),
(12, '2024_06_14_064208_create_units_table', 2),
(15, '2024_06_23_104310_create_permission_tables', 3),
(16, '2023_10_23_043558_create_parent_permissions_table', 4);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(6, 'App\\Models\\User', 8),
(6, 'App\\Models\\User', 24),
(6, 'App\\Models\\User', 39);

-- --------------------------------------------------------

--
-- Table structure for table `oauth_access_tokens`
--

CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `client_id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_auth_codes`
--

CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `client_id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `scopes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_clients`
--

CREATE TABLE `oauth_clients` (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `redirect` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_clients`
--

INSERT INTO `oauth_clients` (`id`, `user_id`, `name`, `secret`, `provider`, `redirect`, `personal_access_client`, `password_client`, `revoked`, `created_at`, `updated_at`) VALUES
('9c40b867-acd8-49e9-bdc7-ee1607bae834', NULL, 'Laravel Personal Access Client', 'pv22RSSSZVX7TtRHo6DtKLhGXduCdiHttEpFD4JJ', NULL, 'http://localhost', 1, 0, 0, '2024-06-10 05:10:56', '2024-06-10 05:10:56'),
('9c40b868-46d0-4d54-805d-0dd5a3c8bdac', NULL, 'Laravel Password Grant Client', 'Frtsajkc9wr0Jkt09QLlgTlJXdDfhIrmws15V2BV', 'users', 'http://localhost', 0, 1, 0, '2024-06-10 05:10:56', '2024-06-10 05:10:56');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_personal_access_clients`
--

CREATE TABLE `oauth_personal_access_clients` (
  `id` bigint UNSIGNED NOT NULL,
  `client_id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_personal_access_clients`
--

INSERT INTO `oauth_personal_access_clients` (`id`, `client_id`, `created_at`, `updated_at`) VALUES
(1, '9c40b867-acd8-49e9-bdc7-ee1607bae834', '2024-06-10 05:10:56', '2024-06-10 05:10:56');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_refresh_tokens`
--

CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `parent_permissions`
--

CREATE TABLE `parent_permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `parent_permissions`
--

INSERT INTO `parent_permissions` (`id`, `name`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Role', 1, NULL, NULL),
(2, 'Permission', 1, NULL, NULL),
(3, 'Role & Permission', 1, NULL, NULL),
(6, 'User', 1, '2024-06-29 02:48:27', '2024-06-29 02:48:27'),
(7, 'Menu', 1, '2024-09-01 10:56:01', '2024-09-01 10:56:01'),
(8, 'Access', 1, '2024-09-01 11:24:57', '2024-09-01 11:24:57');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `parent_permission_id` int DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `parent_permission_id`, `name`, `guard_name`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'all_roles', 'web', 1, '2024-06-24 04:38:08', '2024-06-24 04:38:08'),
(2, 1, 'add_role', 'web', 1, '2024-06-24 04:38:08', '2024-06-24 04:38:08'),
(3, 1, 'edit_role', 'web', 1, '2024-06-24 04:38:08', '2024-06-24 04:38:08'),
(4, 1, 'delete_role', 'web', 1, '2024-06-24 04:38:08', '2024-06-24 04:38:08'),
(5, 2, 'all_permissions', 'web', 1, '2024-06-24 04:38:08', '2024-06-24 04:38:08'),
(6, 2, 'add_permission', 'web', 1, '2024-06-24 04:38:08', '2024-06-24 04:38:08'),
(7, 2, 'edit_permission', 'web', 1, '2024-06-24 04:38:08', '2024-06-24 04:38:08'),
(8, 3, 'assign_permission_list', 'web', 1, '2024-06-24 04:38:08', '2024-06-24 04:38:08'),
(9, 3, 'assign_permission', 'web', 1, '2024-06-24 04:38:08', '2024-06-24 04:38:08'),
(10, 3, 'edit_assign_permission', 'web', 1, '2024-06-24 04:38:08', '2024-06-24 04:38:08'),
(14, 6, 'add_user', 'web', 1, '2024-06-27 05:05:17', '2024-06-29 02:48:36'),
(15, 6, 'edit_user', 'web', 1, '2024-06-27 05:05:29', '2024-06-29 02:48:44'),
(16, 6, 'delete_user', 'web', 1, '2024-06-27 05:05:38', '2024-06-29 02:48:50'),
(17, 2, 'delete_permission', 'web', 1, '2024-06-29 02:46:14', '2024-06-29 02:46:14'),
(18, 7, 'user_management', 'web', 1, '2024-09-01 10:13:05', '2024-09-01 12:01:39'),
(19, 7, 'dashboard', 'web', 1, '2024-09-01 10:56:52', '2024-09-01 10:56:52'),
(20, 7, 'report', 'web', 1, '2024-09-01 10:57:01', '2024-09-01 10:57:01'),
(21, 7, 'sales', 'web', 1, '2024-09-01 10:57:17', '2024-09-01 10:57:17'),
(22, 7, 'receipt', 'web', 1, '2024-09-01 10:57:33', '2025-09-28 18:27:41'),
(23, 8, 'Sales Order Approve', 'web', 1, '2024-09-01 11:25:20', '2024-09-01 11:25:20'),
(24, 6, 'all_user', 'web', 1, '2024-09-01 12:17:44', '2024-09-01 12:17:44'),
(25, 8, 'basic', 'web', 1, '2024-09-01 12:18:23', '2024-09-01 12:18:23'),
(26, 8, 'sales_order_delete', 'web', 1, '2024-09-01 12:18:39', '2024-09-01 12:18:39'),
(27, 8, 'receipt_delete', 'web', 1, '2024-09-01 12:18:54', '2024-09-01 12:18:54'),
(28, 8, 'setting', 'web', 1, '2024-09-01 12:48:30', '2024-09-01 12:48:30'),
(32, 7, 'purchase', 'web', 1, '2025-09-06 19:28:40', '2025-09-06 19:28:40'),
(33, 7, 'voucher', 'web', 1, '2025-09-28 18:32:38', '2025-09-28 18:32:38');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 4, 'MyApp', '770896b0163d370b0da3c8341e9feecaf164ea43e7d70a6092943e163d7e8ea1', '[\"*\"]', NULL, NULL, '2024-08-11 11:16:01', '2024-08-11 11:16:01'),
(2, 'App\\Models\\User', 1, 'MyApp', '5227e26345a9a81a319e3ee7ea0a6acec0323982d03dce0f27b704e12a8be7e5', '[\"*\"]', NULL, NULL, '2024-08-11 11:17:00', '2024-08-11 11:17:00'),
(3, 'App\\Models\\User', 4, 'MyApp', '87a4aff4eb3fab3ac6d76597b657f9f67a027f6f45a901e84e866527000a9773', '[\"*\"]', NULL, NULL, '2024-08-11 11:17:21', '2024-08-11 11:17:21'),
(4, 'App\\Models\\User', 1, 'MyApp', '7a04b84c0b2b8bd203ccf94be09a013d96285ce0b30234ed82895cc7b6cd42db', '[\"*\"]', NULL, NULL, '2024-08-11 14:43:05', '2024-08-11 14:43:05'),
(5, 'App\\Models\\User', 1, 'MyApp', '3fb960ab16399e96c6a6aa06ac393330969846dceaae0bf1c1d6463de35a999d', '[\"*\"]', NULL, NULL, '2024-08-12 15:51:28', '2024-08-12 15:51:28'),
(6, 'App\\Models\\User', 1, 'MyApp', 'cde446fa137443b677027128b4d9c27a991d377e92956d3136e3ba47cb660306', '[\"*\"]', NULL, NULL, '2024-08-12 15:52:15', '2024-08-12 15:52:15'),
(7, 'App\\Models\\User', 1, 'MyApp', 'b571cf7675f545a71f46c33ef8ee491e15ef8908b6b643cc9eaee0cc5ac641b0', '[\"*\"]', NULL, NULL, '2024-08-12 15:56:11', '2024-08-12 15:56:11'),
(8, 'App\\Models\\User', 1, 'MyApp', 'fd60bd9d757be798918b0d9aa24b62e1408bd2770c919580d7439f1d131fa999', '[\"*\"]', NULL, NULL, '2024-08-12 15:58:42', '2024-08-12 15:58:42'),
(9, 'App\\Models\\User', 1, 'MyApp', 'a85b084ed24519abea9708fd522af4cae8b7c6844930a88c61f819cad57e4c00', '[\"*\"]', NULL, NULL, '2024-08-12 16:02:24', '2024-08-12 16:02:24'),
(10, 'App\\Models\\User', 1, 'MyApp', 'b7a92020a16a1a4fe22945d08f9b708d0d14236350093358b42d544ac78d8ebe', '[\"*\"]', NULL, NULL, '2024-08-12 16:05:51', '2024-08-12 16:05:51'),
(11, 'App\\Models\\User', 1, 'MyApp', '9409abdb2f31b983493b98dd251e018b0362076ac356b236f03e7a5007b5b347', '[\"*\"]', NULL, NULL, '2024-08-15 08:07:47', '2024-08-15 08:07:47'),
(12, 'App\\Models\\User', 1, 'MyApp', '3433aa93a5466a515a202d9c44dca7a4b1362f07e1f6d3059f2798a321142b09', '[\"*\"]', NULL, NULL, '2024-08-15 08:11:06', '2024-08-15 08:11:06'),
(13, 'App\\Models\\User', 1, 'MyApp', '7a0e657479162043ee18af2aa3770db8123108791a412314b8ca5fb7c6836b19', '[\"*\"]', NULL, NULL, '2024-08-17 15:56:07', '2024-08-17 15:56:07'),
(14, 'App\\Models\\User', 1, 'MyApp', 'ad5fa0a202bf922684678a424c4ff45a28b0be7bb247474da9f6b04cc5fda2ca', '[\"*\"]', NULL, NULL, '2024-08-17 15:57:25', '2024-08-17 15:57:25'),
(15, 'App\\Models\\User', 1, 'MyApp', '6e12a10cce6aa8254604854158d8e9c4e86fdab296875cf4a3e6704809304f41', '[\"*\"]', NULL, NULL, '2024-08-17 16:00:50', '2024-08-17 16:00:50'),
(16, 'App\\Models\\User', 1, 'MyApp', 'ca6dc2a2d2cff7ec877057d1888b158dd8cc1b43c77f29312fd63e9f8211f343', '[\"*\"]', NULL, NULL, '2024-08-17 17:14:21', '2024-08-17 17:14:21'),
(17, 'App\\Models\\User', 1, 'MyApp', 'a49f25712969180c3df1db4e328dc335477837cd37dfce6cc20287e01bb94eb5', '[\"*\"]', NULL, NULL, '2024-08-17 18:39:25', '2024-08-17 18:39:25'),
(18, 'App\\Models\\User', 1, 'MyApp', '9faa70bb9d1eed1c0111aa2f09fff4dac83e45917efd202f054765f76c7f552c', '[\"*\"]', NULL, NULL, '2024-08-17 18:43:18', '2024-08-17 18:43:18'),
(19, 'App\\Models\\User', 1, 'MyApp', 'f673223cd7bcd4419cef0ba3e40f1c86d4bd11e495e8724115918645becba679', '[\"*\"]', NULL, NULL, '2024-08-17 18:45:41', '2024-08-17 18:45:41'),
(20, 'App\\Models\\User', 1, 'MyApp', '2df8b3093b8ef20f536372bdcb2129839149f78e6722a8abe849bd688b4a63cd', '[\"*\"]', NULL, NULL, '2024-08-17 18:47:34', '2024-08-17 18:47:34'),
(21, 'App\\Models\\User', 1, 'MyApp', '347a9e2c5f95f5994ac989b1ec90837c805f8829edd7f5877751c3eecd165ff0', '[\"*\"]', NULL, NULL, '2024-08-17 18:49:17', '2024-08-17 18:49:17'),
(22, 'App\\Models\\User', 1, 'MyApp', '112d4fec875a3ff97707aa7b3693ff63b3b839a3590c98bb48d34e2e9d5fd3eb', '[\"*\"]', NULL, NULL, '2024-08-17 18:50:09', '2024-08-17 18:50:09'),
(23, 'App\\Models\\User', 1, 'MyApp', 'b3a5e9216b9c895d06dd5a71bf9aa47dc3ec9973c5ae79c52afe4658ff1b77a4', '[\"*\"]', NULL, NULL, '2024-08-18 12:11:37', '2024-08-18 12:11:37'),
(24, 'App\\Models\\User', 1, 'MyApp', '7515187f256b2df1da9bd3ce37b78b737d4df0f1970af79e078fbda9e63c8227', '[\"*\"]', NULL, NULL, '2024-08-18 15:46:33', '2024-08-18 15:46:33'),
(25, 'App\\Models\\User', 1, 'MyApp', '56e98b59b5cdf427270e429bc1a7857172cf8e4d3df6bc160df51cd10cccb279', '[\"*\"]', NULL, NULL, '2024-08-18 18:19:55', '2024-08-18 18:19:55'),
(26, 'App\\Models\\User', 1, 'MyApp', '12ac5110d8141d860765fc73f348c31d267481b3e9510efa2a4066058f148661', '[\"*\"]', NULL, NULL, '2024-08-18 18:32:16', '2024-08-18 18:32:16'),
(27, 'App\\Models\\User', 1, 'MyApp', 'c25b2091a0d0e47e56cb5139712036220fd1dd5e26ee401d4d49bee7bdd34859', '[\"*\"]', NULL, NULL, '2024-08-18 18:35:34', '2024-08-18 18:35:34'),
(28, 'App\\Models\\User', 1, 'MyApp', 'e7ed970a2c4682b0a0f41d7535b745237b52ea42ab1df9e5c5e56c3ae45dec57', '[\"*\"]', NULL, NULL, '2024-08-19 08:26:29', '2024-08-19 08:26:29'),
(29, 'App\\Models\\User', 1, 'MyApp', '5afd086747db9c8a224fec6a0038e840a7096dd7662793263b62d3ad2632cc1f', '[\"*\"]', NULL, NULL, '2024-08-19 09:15:25', '2024-08-19 09:15:25'),
(30, 'App\\Models\\User', 1, 'MyApp', '3a1b064563615a37420038c79710deb110da91ab6eaef8303991a8bd522c1e8b', '[\"*\"]', NULL, NULL, '2024-08-19 10:09:29', '2024-08-19 10:09:29'),
(31, 'App\\Models\\User', 1, 'MyApp', '967f9438faf53a3b8d23d91cb92be2d5b52476eae2c42ca7c2e86eec7e93108c', '[\"*\"]', NULL, NULL, '2024-08-19 10:11:26', '2024-08-19 10:11:26'),
(32, 'App\\Models\\User', 1, 'MyApp', 'edd8516014eb5f7242b3262ac5a5ae6048c7299b71021ec99c981fc5940853a6', '[\"*\"]', NULL, NULL, '2024-08-19 10:23:33', '2024-08-19 10:23:33'),
(33, 'App\\Models\\User', 1, 'MyApp', '0ab7e2d4c4491cd4f7c97399dd6cebcfb8e189f46fc7b0b0d7aef92e41ee0104', '[\"*\"]', NULL, NULL, '2024-08-19 10:25:47', '2024-08-19 10:25:47'),
(34, 'App\\Models\\User', 1, 'MyApp', '0d51ed79d2d967e2c4db595ae0414348d5fd7798ce34227e9b5ce355debbabac', '[\"*\"]', NULL, NULL, '2024-08-19 10:29:08', '2024-08-19 10:29:08'),
(35, 'App\\Models\\User', 1, 'MyApp', '2d60fb972a9fc1953aea26e7d665fec8caf1ebce15c3d507da3838415b59804f', '[\"*\"]', NULL, NULL, '2024-08-19 11:21:00', '2024-08-19 11:21:00'),
(36, 'App\\Models\\User', 1, 'MyApp', '5388a02e7093fc1eba384a87e2d1eedbbf2ec911150a5901bfd6bfd98b854291', '[\"*\"]', NULL, NULL, '2024-08-19 14:26:29', '2024-08-19 14:26:29'),
(37, 'App\\Models\\User', 1, 'MyApp', 'a2fb443daa4ebfbe719056b070c96ebd8f13cc11c38f3ebcac8883d1def09e02', '[\"*\"]', NULL, NULL, '2024-08-19 14:40:49', '2024-08-19 14:40:49'),
(38, 'App\\Models\\User', 1, 'MyApp', '78bf517f949eddcd1874c42ee8b77dd4556714f384c8eb9f21e6b96f34a1b960', '[\"*\"]', NULL, NULL, '2024-08-19 14:42:45', '2024-08-19 14:42:45'),
(39, 'App\\Models\\User', 1, 'MyApp', 'c3fad23f106ee652b5e5318752f4e65e250c6a0f536f3c03ef68dd480a3f60a4', '[\"*\"]', NULL, NULL, '2024-08-19 14:43:51', '2024-08-19 14:43:51'),
(40, 'App\\Models\\User', 1, 'MyApp', 'f0883199032bc85fdcce6e5520496ca40b3957cc9092664583877932e8bec3d3', '[\"*\"]', NULL, NULL, '2024-08-19 14:50:23', '2024-08-19 14:50:23'),
(41, 'App\\Models\\User', 1, 'MyApp', '871c18925526fcf8a9b246a5227b8409bfa0bad1c89101c9ad535455d4b9e823', '[\"*\"]', NULL, NULL, '2024-08-19 14:53:39', '2024-08-19 14:53:39'),
(42, 'App\\Models\\User', 1, 'MyApp', 'f10644271b5b4c175ccfbd9107493482f1fd96031bdf017cf8b7be2284ecd8ca', '[\"*\"]', NULL, NULL, '2024-08-19 15:24:43', '2024-08-19 15:24:43'),
(43, 'App\\Models\\User', 1, 'MyApp', '105bce54dd650219404df007c0882ce421b976ec2a3e57bd15ce69ae6496ffb0', '[\"*\"]', NULL, NULL, '2024-08-19 15:27:55', '2024-08-19 15:27:55'),
(44, 'App\\Models\\User', 1, 'MyApp', 'c5f872b8a816d53fedb2cecee6fa45e6a38725fbde89d0de72e723905eaf0a52', '[\"*\"]', NULL, NULL, '2024-08-19 15:31:16', '2024-08-19 15:31:16'),
(45, 'App\\Models\\User', 1, 'MyApp', '5c10fde01137ddd8cc79e40d5c1041c800728d74b88d4b87593d34c04b20818a', '[\"*\"]', NULL, NULL, '2024-08-19 15:38:47', '2024-08-19 15:38:47'),
(46, 'App\\Models\\User', 1, 'MyApp', 'f9dc594e0bf7ea19c15eb0ccc79354a5b31edf5454fd46fdd3e1ee81a9ab7040', '[\"*\"]', NULL, NULL, '2024-08-19 16:32:01', '2024-08-19 16:32:01'),
(47, 'App\\Models\\User', 1, 'MyApp', 'd4aac50cf19e2741228bc5dd13de9497f3a8012dd854913d6e50c638d28b9471', '[\"*\"]', NULL, NULL, '2024-08-20 06:33:00', '2024-08-20 06:33:00'),
(48, 'App\\Models\\User', 1, 'MyApp', '19fb479963ee6ba5d72acf9452dc293ae67a95a8ec2bfc2cfadd986347d6b98b', '[\"*\"]', NULL, NULL, '2024-08-20 06:38:43', '2024-08-20 06:38:43'),
(49, 'App\\Models\\User', 1, 'MyApp', 'b2afdbf22f2a6a5f3361e8c271893f3e06aad6a72424af6ccf76cb466445ee5b', '[\"*\"]', NULL, NULL, '2024-08-20 06:44:44', '2024-08-20 06:44:44'),
(50, 'App\\Models\\User', 1, 'MyApp', 'f1b1d50be99b8450a28c33a17d5c7adbba265187945885bed49ab7d3f9af2ed5', '[\"*\"]', NULL, NULL, '2024-08-20 06:45:38', '2024-08-20 06:45:38'),
(51, 'App\\Models\\User', 1, 'MyApp', '96c3d568e9a112f4b77616fc881dd0382b570767dcded10240ef2ac31ab65638', '[\"*\"]', NULL, NULL, '2024-08-20 06:50:33', '2024-08-20 06:50:33'),
(52, 'App\\Models\\User', 1, 'MyApp', 'b10d42a28bb111bf3fb0ce128f9ff33655509fca19cadd4930bdaec1f8d9d94b', '[\"*\"]', NULL, NULL, '2024-08-20 06:56:59', '2024-08-20 06:56:59'),
(53, 'App\\Models\\User', 1, 'MyApp', 'af0eca54c5ee2b4e84b792270640214b003359ef973203ad0cadc3e1e6ea41ba', '[\"*\"]', NULL, NULL, '2024-08-20 07:47:09', '2024-08-20 07:47:09'),
(54, 'App\\Models\\User', 1, 'MyApp', '4cbe1a900ee6f780da99f4049f74fc8cc43378dd9fa47d3a0df70515a7547ebc', '[\"*\"]', NULL, NULL, '2024-08-20 08:05:28', '2024-08-20 08:05:28'),
(55, 'App\\Models\\User', 1, 'MyApp', '04ca9769333b56598928505744bd0a791a95a9e0919f63c07f921604ba094bdc', '[\"*\"]', NULL, NULL, '2024-08-20 08:10:07', '2024-08-20 08:10:07'),
(56, 'App\\Models\\User', 1, 'MyApp', 'c8bc7632ee01742f7342ba12fe7aba6fdf514241dada58d28e87aaae045ea998', '[\"*\"]', NULL, NULL, '2024-08-20 14:43:12', '2024-08-20 14:43:12'),
(57, 'App\\Models\\User', 1, 'MyApp', 'a974748a085a227165b5af9ce0fe0ab53c81dcac00285f9473ceb482e076c564', '[\"*\"]', NULL, NULL, '2024-08-21 02:37:42', '2024-08-21 02:37:42'),
(58, 'App\\Models\\User', 1, 'MyApp', '3b42b530a04580bb8a5db8798e2fa9dbd805fa197e0718664012b42ae7fc3bb3', '[\"*\"]', NULL, NULL, '2024-08-21 11:29:08', '2024-08-21 11:29:08'),
(59, 'App\\Models\\User', 1, 'MyApp', '40403efc1a059fbb33f0a1d7baf6b17641ef90732e1502b35f4c7412b96be655', '[\"*\"]', NULL, NULL, '2024-08-22 04:08:54', '2024-08-22 04:08:54'),
(60, 'App\\Models\\User', 1, 'MyApp', 'ef4c18ce4ba9377a9bee5ac965d4cd25a612f3c57aaf66ceadbcf693b5ae24f0', '[\"*\"]', NULL, NULL, '2024-08-22 15:25:30', '2024-08-22 15:25:30'),
(61, 'App\\Models\\User', 1, 'MyApp', '0d32697c8e33eda20eb07000b345419918ef08653ae9c1a5727ec7ae198c39a6', '[\"*\"]', NULL, NULL, '2024-08-22 15:32:39', '2024-08-22 15:32:39'),
(62, 'App\\Models\\User', 1, 'MyApp', '2c707b5f7b6f4719798ff146c12d897eadc5e584f067699f087464d1089c6d27', '[\"*\"]', NULL, NULL, '2024-08-23 05:08:08', '2024-08-23 05:08:08'),
(63, 'App\\Models\\User', 1, 'MyApp', '80b07286e29b4021c5e40aad8145afd559b1b0ccd57c0c31b2c98cb2eedb8c96', '[\"*\"]', NULL, NULL, '2024-08-23 05:16:56', '2024-08-23 05:16:56'),
(64, 'App\\Models\\User', 1, 'MyApp', '59cb72528c583296fae6b89940b2f27d4995a4053b7cf5ed3b20fc3d0d5123ee', '[\"*\"]', NULL, NULL, '2024-08-23 11:18:01', '2024-08-23 11:18:01'),
(65, 'App\\Models\\User', 1, 'MyApp', '40867af6b12b774a35389773f6afc3ca332936e9e545c66d6bd96a264d348470', '[\"*\"]', NULL, NULL, '2024-08-23 11:23:13', '2024-08-23 11:23:13'),
(66, 'App\\Models\\User', 1, 'MyApp', 'bd278e822b006051ae882a2dc42eb29ad03205ddbaf2d212f30bce8fb70af96e', '[\"*\"]', NULL, NULL, '2024-08-23 11:24:06', '2024-08-23 11:24:06'),
(67, 'App\\Models\\User', 1, 'MyApp', 'c0485686fbfe9d5319b96fb352ffc7adf22966a4a618882b3e5a974dce2f5f98', '[\"*\"]', NULL, NULL, '2024-08-23 11:27:36', '2024-08-23 11:27:36'),
(68, 'App\\Models\\User', 1, 'MyApp', '758c087e7265130c403da7ca7178aaee9772bcdbccd19da6c7183752ca23f468', '[\"*\"]', NULL, NULL, '2024-08-25 15:16:22', '2024-08-25 15:16:22'),
(69, 'App\\Models\\User', 1, 'MyApp', 'cb97b87ff6a8b7272cf95c8b97469914f70ee9a88cf94e755c9102e2a158bb23', '[\"*\"]', NULL, NULL, '2024-08-25 15:16:34', '2024-08-25 15:16:34'),
(70, 'App\\Models\\User', 1, 'MyApp', '2140c68972f870d89bbe9b826c0d694459a8e8415a59e581df3e0701c6f3ba5b', '[\"*\"]', NULL, NULL, '2024-08-27 08:24:45', '2024-08-27 08:24:45'),
(71, 'App\\Models\\User', 1, 'MyApp', '32acd99a54ac5e3b3d99698606a81c9580d42fed4694f5bde690ca229a7fa731', '[\"*\"]', NULL, NULL, '2024-08-27 08:26:21', '2024-08-27 08:26:21'),
(72, 'App\\Models\\User', 1, 'MyApp', 'abfe5e307b094ac5dbbf001dcdb7ed0177b44e0b8eb868182e79cf6b3cef520e', '[\"*\"]', NULL, NULL, '2024-08-27 08:28:16', '2024-08-27 08:28:16'),
(73, 'App\\Models\\User', 1, 'MyApp', '7371c81d8e9d5abce13eba119ea2590a3c120479546f31e3317b25ec022b359f', '[\"*\"]', NULL, NULL, '2024-08-27 08:31:35', '2024-08-27 08:31:35'),
(74, 'App\\Models\\User', 1, 'MyApp', '23f0cf456dc497a121307253ae474cc710002324e41b878e0da95f45c9aa376f', '[\"*\"]', NULL, NULL, '2024-08-27 08:34:41', '2024-08-27 08:34:41'),
(75, 'App\\Models\\User', 1, 'MyApp', 'aa1c85f7ef218daf8f0606ebe1512da713886dafff6fe45490c53aa6f71c67ce', '[\"*\"]', NULL, NULL, '2024-08-27 08:36:33', '2024-08-27 08:36:33'),
(76, 'App\\Models\\User', 1, 'MyApp', '91290cd51d2f71bd2267f147ccf7ea491d015fc8549ccf95509cb184aeab21d9', '[\"*\"]', NULL, NULL, '2024-08-27 08:38:10', '2024-08-27 08:38:10'),
(77, 'App\\Models\\User', 1, 'MyApp', 'c103b25fa8e623482ae95c5f75d5a73a779089866c562c839f188ac4c07ea7a7', '[\"*\"]', NULL, NULL, '2024-08-27 09:49:21', '2024-08-27 09:49:21'),
(78, 'App\\Models\\User', 1, 'MyApp', '7d853ddc095453a7efde817f8caf35095bcb8243a75e160fcb63f5efb6cf0684', '[\"*\"]', NULL, NULL, '2024-08-27 09:50:36', '2024-08-27 09:50:36'),
(79, 'App\\Models\\User', 1, 'MyApp', '7d05290fa46be13e478de0a47d655c19d11a78e1af061085e43df7cf2aae35ed', '[\"*\"]', NULL, NULL, '2024-08-27 09:52:09', '2024-08-27 09:52:09'),
(80, 'App\\Models\\User', 1, 'MyApp', '3be5b0acfc74222b92fb0f30e1da39baa1539f0044e16a3eb7fc84b9ac5b3f57', '[\"*\"]', NULL, NULL, '2024-08-27 09:53:26', '2024-08-27 09:53:26'),
(81, 'App\\Models\\User', 1, 'MyApp', 'cfdf34d329d31664d5c1d4cacfecf2ebe73a1ac29fe67ffe7b0e207a6b072cc2', '[\"*\"]', NULL, NULL, '2024-08-27 10:12:09', '2024-08-27 10:12:09'),
(82, 'App\\Models\\User', 1, 'MyApp', 'c69dd709909f70928b26b2eff1232623546deae77a35438a3c6a6013f23d957c', '[\"*\"]', NULL, NULL, '2024-08-27 17:34:25', '2024-08-27 17:34:25'),
(83, 'App\\Models\\User', 1, 'MyApp', '5b539260d5d7dd44f48927950241138adbf3c81580c27b99451e3dbf0c3609f1', '[\"*\"]', NULL, NULL, '2024-08-27 17:34:55', '2024-08-27 17:34:55'),
(84, 'App\\Models\\User', 1, 'MyApp', 'dacc5da29c98f8a3b455068f9d67e64f01ca82fd9e82e8a210e3a4970845dda3', '[\"*\"]', NULL, NULL, '2024-08-27 17:55:24', '2024-08-27 17:55:24'),
(85, 'App\\Models\\User', 1, 'MyApp', '46bf567dcb85a9c637c78560f02aef9a4261fd5892be8a17540b114c5208d89f', '[\"*\"]', NULL, NULL, '2024-08-27 17:57:52', '2024-08-27 17:57:52'),
(86, 'App\\Models\\User', 1, 'MyApp', '4df95bad43155d9817be117444cb8ed3884bbdae1b2a7f978b1c20e06e92d7ed', '[\"*\"]', NULL, NULL, '2024-08-27 17:59:33', '2024-08-27 17:59:33'),
(87, 'App\\Models\\User', 1, 'MyApp', '7000223b7b251e1283afb158c17e6e9770f41f6a7cecd26cd3ee4d227d51ceff', '[\"*\"]', NULL, NULL, '2024-08-27 18:05:37', '2024-08-27 18:05:37'),
(88, 'App\\Models\\User', 1, 'MyApp', '72b11a6c523d0b482ac49be666fbce2c25813dbb181bef5df66faec9d5496a57', '[\"*\"]', NULL, NULL, '2024-08-27 18:12:12', '2024-08-27 18:12:12'),
(89, 'App\\Models\\User', 1, 'MyApp', '7583a3024a87826eced31ed1b63571fe57ec6f6dcfcb8f66667136aeb2e899f3', '[\"*\"]', NULL, NULL, '2024-08-27 18:24:05', '2024-08-27 18:24:05'),
(90, 'App\\Models\\User', 1, 'MyApp', '4080fd251c8c0912405098141fec3a3e46fc387f9643f2ef9bcabede606aa958', '[\"*\"]', NULL, NULL, '2024-08-28 08:39:57', '2024-08-28 08:39:57'),
(91, 'App\\Models\\User', 1, 'MyApp', '7f5524c717d193173dc633a94d65ea4a5ba6f0782e03e380480edc592ec96923', '[\"*\"]', NULL, NULL, '2024-08-28 08:44:51', '2024-08-28 08:44:51'),
(92, 'App\\Models\\User', 1, 'MyApp', 'cad66de01c10dc50a2f99b09ca23cc4f614c50ab84c64103ac52f95b21a7a4c8', '[\"*\"]', NULL, NULL, '2024-08-28 08:46:29', '2024-08-28 08:46:29'),
(93, 'App\\Models\\User', 1, 'MyApp', '2c1a5cee9bd71cc7c19178787142876dcc9ace8c2463214a4dd6d4913c43a162', '[\"*\"]', NULL, NULL, '2024-08-28 08:49:05', '2024-08-28 08:49:05'),
(94, 'App\\Models\\User', 1, 'MyApp', '4ddf8229d93d8b6f3148c0b1693cfdb5df14496fed3f97a86b94950777647339', '[\"*\"]', NULL, NULL, '2024-08-28 08:51:49', '2024-08-28 08:51:49'),
(95, 'App\\Models\\User', 1, 'MyApp', 'e03aa721919e5d879eb9c628132ef971c80ae0f22ef7e6ec880ec055f4fa0a87', '[\"*\"]', NULL, NULL, '2024-08-28 08:55:22', '2024-08-28 08:55:22'),
(96, 'App\\Models\\User', 1, 'MyApp', '0b0e1299d949fe6995d43dbcdfd46ee7e3fcf75be06e8271b647f62be846afc1', '[\"*\"]', NULL, NULL, '2024-08-28 09:48:49', '2024-08-28 09:48:49'),
(97, 'App\\Models\\User', 1, 'MyApp', '3929bcacb39720c73ff95e6be410f05e38a66f43f52b00e09af978d1daef6342', '[\"*\"]', NULL, NULL, '2024-08-28 09:50:03', '2024-08-28 09:50:03'),
(98, 'App\\Models\\User', 1, 'MyApp', '673700292f01acdb4a87ee6c3721c4b6c7732b5a27cdd9963cdd82a6b28f99b4', '[\"*\"]', NULL, NULL, '2024-08-28 09:51:28', '2024-08-28 09:51:28'),
(99, 'App\\Models\\User', 1, 'MyApp', '3b8301b9dd3d99272c43d4c88f00cdfb12086c1a19e0cd6d811a8f25bf65e296', '[\"*\"]', NULL, NULL, '2024-08-28 09:53:07', '2024-08-28 09:53:07'),
(100, 'App\\Models\\User', 1, 'MyApp', '0ec2939c4a59ae4ef14ba07916d382402daafa4f5962cb6ccc01d3f5a8bef021', '[\"*\"]', NULL, NULL, '2024-08-28 09:55:26', '2024-08-28 09:55:26'),
(101, 'App\\Models\\User', 1, 'MyApp', 'a7e85d9af6fe8d830dbdbcd50f77f9fa46f91187734545e626eea43bba3bba33', '[\"*\"]', NULL, NULL, '2024-08-28 09:56:48', '2024-08-28 09:56:48'),
(102, 'App\\Models\\User', 1, 'MyApp', 'ae4839d48ef1a607a6ef663c7718d71f4cd6fab269857024509e9a4bf7050803', '[\"*\"]', NULL, NULL, '2024-08-28 10:03:56', '2024-08-28 10:03:56'),
(103, 'App\\Models\\User', 1, 'MyApp', '0ca48aab30c60cb868058d4da83fc480442716e87553c6e1631120d00b4045b2', '[\"*\"]', NULL, NULL, '2024-08-28 10:31:56', '2024-08-28 10:31:56'),
(104, 'App\\Models\\User', 1, 'MyApp', '31c7a320cb0babd530240eee81ae8967334b593c35b6945d9d34ac0e2f6bfb39', '[\"*\"]', NULL, NULL, '2024-08-28 10:54:47', '2024-08-28 10:54:47'),
(105, 'App\\Models\\User', 1, 'MyApp', '76b36408ad441e46bc95d3c00abc3322f532562fd4dcf378754757d3f430809b', '[\"*\"]', NULL, NULL, '2024-08-28 13:38:46', '2024-08-28 13:38:46'),
(106, 'App\\Models\\User', 1, 'MyApp', 'f2a6a07c87a2e72e05db2b03ec8b94d4d5f38cf0662e148e359cdfd9d64a89ad', '[\"*\"]', NULL, NULL, '2024-08-29 05:05:25', '2024-08-29 05:05:25'),
(107, 'App\\Models\\User', 1, 'MyApp', '968b6983d86d1742c116865f1d9a3ba3ba4bd04b16bdba33affbdab8611b48f4', '[\"*\"]', NULL, NULL, '2024-08-29 05:07:50', '2024-08-29 05:07:50'),
(108, 'App\\Models\\User', 1, 'MyApp', 'f998113aee44c44df38c486a46ad7eda8c223dd54f92a24a770435803f6862d7', '[\"*\"]', NULL, NULL, '2024-09-01 07:53:15', '2024-09-01 07:53:15'),
(109, 'App\\Models\\User', 1, 'MyApp', 'b921e776a4e3b5b6192fb81007c0eee0307966942f169b854bfb0edf88f77250', '[\"*\"]', NULL, NULL, '2024-09-01 07:55:44', '2024-09-01 07:55:44'),
(110, 'App\\Models\\User', 1, 'MyApp', '9c4d0becfb7345b990600841089396c1916a9c19746fcbe4d7155902bf438ede', '[\"*\"]', NULL, NULL, '2024-09-01 08:01:54', '2024-09-01 08:01:54'),
(111, 'App\\Models\\User', 1, 'MyApp', '547899d328f7d5f5e7c1596bdd30c419fdcea581851c15f0acc2081e71eeeb58', '[\"*\"]', NULL, NULL, '2024-09-01 08:04:47', '2024-09-01 08:04:47'),
(112, 'App\\Models\\User', 1, 'MyApp', 'c715aea2ed9fdee9ef58487aacf5f11fd03bf6bd687763b06f01493c6679bad8', '[\"*\"]', NULL, NULL, '2024-09-01 08:18:32', '2024-09-01 08:18:32'),
(113, 'App\\Models\\User', 1, 'MyApp', 'c62b31e6162cb94d686a9755bf1b8740c012ee23b823b7e92ed2dc26350bf37d', '[\"*\"]', NULL, NULL, '2024-09-01 08:21:28', '2024-09-01 08:21:28'),
(114, 'App\\Models\\User', 1, 'MyApp', '179ef478b0b8b86098ccacf8fa71e5db3a5eefb7c6cf889203a6bf9f25152bf4', '[\"*\"]', NULL, NULL, '2024-09-01 08:24:14', '2024-09-01 08:24:14'),
(115, 'App\\Models\\User', 1, 'MyApp', '8d5f332f6f64d42ad33267da6d10a7478461575a5e22aa797755ccdd767f5f86', '[\"*\"]', NULL, NULL, '2024-09-02 06:28:30', '2024-09-02 06:28:30'),
(116, 'App\\Models\\User', 1, 'MyApp', '06b726ccb432baa358b2449cb13ac1d3edf0b62f49d6d139674c63f9e6467624', '[\"*\"]', NULL, NULL, '2024-09-02 06:40:15', '2024-09-02 06:40:15'),
(117, 'App\\Models\\User', 1, 'MyApp', '71b21ac360ba3d6dcda7cf92315b98f2ab5c8dbe55ec615e7d72109ba4763c7c', '[\"*\"]', NULL, NULL, '2024-09-02 08:01:40', '2024-09-02 08:01:40'),
(118, 'App\\Models\\User', 1, 'MyApp', 'e43d661ad52198af092888fed0fb010467ebf2b6db02d0174392d3ce4ede0882', '[\"*\"]', NULL, NULL, '2024-09-02 08:03:43', '2024-09-02 08:03:43'),
(119, 'App\\Models\\User', 1, 'MyApp', 'f085583bc54490ad16bfe6a456339076c30579f1417149264f11e8178f93725e', '[\"*\"]', NULL, NULL, '2024-09-02 08:21:27', '2024-09-02 08:21:27'),
(120, 'App\\Models\\User', 1, 'MyApp', '625a08eee49dbc5ec8d897613b80ce222765b3892f1dc103c14f0fce62d5d53f', '[\"*\"]', NULL, NULL, '2024-09-02 08:36:28', '2024-09-02 08:36:28'),
(121, 'App\\Models\\User', 1, 'MyApp', 'f6e6ff03df09db50610dc89a58896848632e526c3ebe312857073dc57305a405', '[\"*\"]', NULL, NULL, '2024-09-02 08:43:53', '2024-09-02 08:43:53'),
(122, 'App\\Models\\User', 1, 'MyApp', '9a878d9474e7cf1aba8bed44db9ad8d11de31bc65f67761870afd618c29a8b82', '[\"*\"]', NULL, NULL, '2024-09-02 09:35:49', '2024-09-02 09:35:49'),
(123, 'App\\Models\\User', 1, 'MyApp', '96bdf05a56668bf38c0e3b8b8ae2fa31558c5b1612abdf9927e7a88556e08fef', '[\"*\"]', NULL, NULL, '2024-09-02 09:42:14', '2024-09-02 09:42:14'),
(124, 'App\\Models\\User', 1, 'MyApp', 'a03e62d66cb43bf43c43737ddcbc88b6431be4b37f9044aac1a2b0aef023c134', '[\"*\"]', NULL, NULL, '2024-09-04 06:28:26', '2024-09-04 06:28:26'),
(125, 'App\\Models\\User', 1, 'MyApp', '30893a5560acb52987698465867e40c3132c83c2eac1de089ab46c9ad15c4ba2', '[\"*\"]', NULL, NULL, '2024-09-30 01:07:37', '2024-09-30 01:07:37'),
(126, 'App\\Models\\User', 1, 'MyApp', '6c1d12e19bab3f362db65dee47951d682ea0ba59391a99a49560a713c6ec2144', '[\"*\"]', NULL, NULL, '2024-09-30 01:10:32', '2024-09-30 01:10:32'),
(127, 'App\\Models\\User', 1, 'MyApp', '18ab29d6dbbe48e42fe37362c34dfedb12cb8c048a53c18ea6440dd0cf40cc45', '[\"*\"]', NULL, NULL, '2024-09-30 01:29:42', '2024-09-30 01:29:42'),
(128, 'App\\Models\\User', 1, 'MyApp', 'f2009999eaeff93fa61c525747599a36efd4a65bf75c9a4e80871916a5a628dc', '[\"*\"]', NULL, NULL, '2024-09-30 01:36:13', '2024-09-30 01:36:13'),
(129, 'App\\Models\\User', 1, 'MyApp', '573489bb84775d160da6f7e6f31fbd59161c0d44c0cbf4297ca33fbd75c0c505', '[\"*\"]', NULL, NULL, '2024-09-30 02:16:56', '2024-09-30 02:16:56'),
(130, 'App\\Models\\User', 1, 'MyApp', '66abfd194652afde6624ce0e50c315f477a0bfb853375b6384a516cd6e58475c', '[\"*\"]', NULL, NULL, '2024-09-30 02:25:15', '2024-09-30 02:25:15'),
(131, 'App\\Models\\User', 1, 'MyApp', 'efd35866f83a10d9d51647a071effd9821004d689dcb70eefbb46c4b4302d23d', '[\"*\"]', NULL, NULL, '2024-09-30 02:25:58', '2024-09-30 02:25:58'),
(132, 'App\\Models\\User', 1, 'MyApp', 'bdbcd46edc8db7279e2485d819d1a26ac8021c5e3bad969ddc92a75a3a16fe94', '[\"*\"]', NULL, NULL, '2024-09-30 02:49:48', '2024-09-30 02:49:48'),
(133, 'App\\Models\\User', 1, 'MyApp', '0cd84147f0196404a696580309edfd38b1f5f9d392e9f46b7ebbe44e89392e97', '[\"*\"]', NULL, NULL, '2024-09-30 02:51:06', '2024-09-30 02:51:06'),
(134, 'App\\Models\\User', 1, 'MyApp', 'ff456291bac14cab8c659bca082138db95f53b7164a94e5930161b335e1c36f8', '[\"*\"]', NULL, NULL, '2024-09-30 02:53:57', '2024-09-30 02:53:57'),
(135, 'App\\Models\\User', 1, 'MyApp', 'c6458068a8d0332380ac1a3621e8d456c5bbaa075a7831bfd9f3c31882650d92', '[\"*\"]', NULL, NULL, '2024-09-30 02:54:16', '2024-09-30 02:54:16'),
(136, 'App\\Models\\User', 8, 'MyApp', 'c6137f0ee98a33fa43f56aa99f3cdb88d2c1a1ae77d719ac0beefeb4a044c476', '[\"*\"]', NULL, NULL, '2024-09-30 02:56:18', '2024-09-30 02:56:18'),
(137, 'App\\Models\\User', 1, 'MyApp', '68cf916f7efccc5a5f1c85c2e8b1e056f34e7bf6b7dac877823a4b566c86468d', '[\"*\"]', NULL, NULL, '2024-09-30 03:31:34', '2024-09-30 03:31:34'),
(138, 'App\\Models\\User', 1, 'MyApp', '97dffb3eef4ab65ed973bf269a4c8a1365ae45c44a08c8eb7bf4a0e6f3232650', '[\"*\"]', NULL, NULL, '2024-09-30 03:32:37', '2024-09-30 03:32:37'),
(139, 'App\\Models\\User', 1, 'MyApp', 'f801967f18856aa5fed0557f9f6d2fa8ed0dc0ee604b3f13e186608825148eec', '[\"*\"]', NULL, NULL, '2024-09-30 03:40:34', '2024-09-30 03:40:34'),
(140, 'App\\Models\\User', 1, 'MyApp', 'e25f479fe07fe0c2a6ad15f96b8ab13cc77d5c71c09c2400cac1e33bd8165ee6', '[\"*\"]', NULL, NULL, '2024-09-30 05:56:45', '2024-09-30 05:56:45'),
(141, 'App\\Models\\User', 1, 'MyApp', 'd3fddd556861853b9d818378eab58f56e31c83123325c2cc6b5cfd2c0ee6fc64', '[\"*\"]', NULL, NULL, '2024-09-30 05:59:07', '2024-09-30 05:59:07'),
(142, 'App\\Models\\User', 8, 'MyApp', 'af6fa3799bf2866bb34d608b6db34ee357762eebe38f94c5b19cf50f8d0b6f01', '[\"*\"]', NULL, NULL, '2024-09-30 06:43:55', '2024-09-30 06:43:55'),
(143, 'App\\Models\\User', 8, 'MyApp', 'c74b8f2ba5b5d6f40ff96312b73efe8ac9cb7a335136807cf5efc5ba6ce2dbf3', '[\"*\"]', NULL, NULL, '2024-10-01 23:52:27', '2024-10-01 23:52:27'),
(144, 'App\\Models\\User', 1, 'MyApp', 'f3bc17e7b4bf3e2823115f78d37bb31e10fb06fac0e6a5f4b4649ce235358dc7', '[\"*\"]', NULL, NULL, '2024-10-02 05:38:55', '2024-10-02 05:38:55'),
(145, 'App\\Models\\User', 9, 'MyApp', '065092581b1315aa2ca0d9df7c8dd853b7d769e9732f755753762ead9527eaa1', '[\"*\"]', NULL, NULL, '2024-10-02 05:43:26', '2024-10-02 05:43:26'),
(146, 'App\\Models\\User', 8, 'MyApp', '439e72d0b1811d126686a9e8e284515e050220eebcb416d9da99f088578346b2', '[\"*\"]', NULL, NULL, '2024-10-03 03:54:45', '2024-10-03 03:54:45'),
(147, 'App\\Models\\User', 8, 'MyApp', 'd11f191b1a6cac25f3be9c30cf725676b871db1c16fb9cc4d1a969be817ad969', '[\"*\"]', NULL, NULL, '2024-10-03 03:57:09', '2024-10-03 03:57:09'),
(148, 'App\\Models\\User', 8, 'MyApp', '77d884d20f697c18f71126228f6383510c50cca22a01dec28f581911d548ffb0', '[\"*\"]', NULL, NULL, '2024-10-03 03:57:47', '2024-10-03 03:57:47'),
(149, 'App\\Models\\User', 8, 'MyApp', 'c8327903b49f11fb702685838548da72d9efe849d10c419f1131d1564f46ac0f', '[\"*\"]', NULL, NULL, '2024-10-03 04:00:12', '2024-10-03 04:00:12'),
(150, 'App\\Models\\User', 8, 'MyApp', 'cc3e756862d20c569fd157ac880924567e50c9c3d4c88abe06822fb7a620867f', '[\"*\"]', NULL, NULL, '2024-10-03 04:00:57', '2024-10-03 04:00:57'),
(151, 'App\\Models\\User', 7, 'MyApp', '9156e0bbe3c52c4a5e6bf780164b6c257a608b0959483f524f850c551bd76638', '[\"*\"]', NULL, NULL, '2024-10-03 04:11:11', '2024-10-03 04:11:11'),
(152, 'App\\Models\\User', 7, 'MyApp', 'd0c2e3b494add2481aa2f56848ab535b86fb86774cf24fc1fcf257d691f2de92', '[\"*\"]', NULL, NULL, '2024-10-03 04:13:13', '2024-10-03 04:13:13'),
(153, 'App\\Models\\User', 8, 'MyApp', '4dcd59e4a3d982169e6793e1af7cdf01255c2081050b37c0d061a91d545bcfef', '[\"*\"]', NULL, NULL, '2024-10-03 11:01:47', '2024-10-03 11:01:47'),
(154, 'App\\Models\\User', 7, 'MyApp', '5b698b6779b81d138c28d38f2b19074a32e2ea22f89c2e25bf9249831e2db445', '[\"*\"]', NULL, NULL, '2024-10-03 22:09:51', '2024-10-03 22:09:51'),
(155, 'App\\Models\\User', 9, 'MyApp', '4065f2b7217026f60d089d5e6d86cb748e94f07fec5b0e5505e4e9d6f90283e3', '[\"*\"]', NULL, NULL, '2024-10-09 05:15:47', '2024-10-09 05:15:47'),
(156, 'App\\Models\\User', 9, 'MyApp', '5e69de68d3ad0dbab18a4d117c245f16297108e0ff4cc2a0e8c5b11c66b4d15d', '[\"*\"]', NULL, NULL, '2024-10-09 05:17:19', '2024-10-09 05:17:19'),
(157, 'App\\Models\\User', 9, 'MyApp', '94b2040d2fdccef535dd7e420253076fbe02194f4ea1d537fe212ec7e1378053', '[\"*\"]', NULL, NULL, '2024-10-09 08:26:32', '2024-10-09 08:26:32'),
(158, 'App\\Models\\User', 9, 'MyApp', '6322b4fd28805c151c123fe76dbf7aaca8cdc7bb33641241103ba9ef756921ac', '[\"*\"]', NULL, NULL, '2024-10-09 08:27:49', '2024-10-09 08:27:49'),
(159, 'App\\Models\\User', 9, 'MyApp', '0756f4949b1d0a510688feb8ab7adff632394b05760f85d7123432462e2cdeba', '[\"*\"]', NULL, NULL, '2024-10-09 08:29:27', '2024-10-09 08:29:27'),
(160, 'App\\Models\\User', 9, 'MyApp', '9feb52cd5e340d5b23dd5009eaf86c1469d02619d36164bb0dfedc669381a677', '[\"*\"]', NULL, NULL, '2024-10-09 09:15:30', '2024-10-09 09:15:30'),
(161, 'App\\Models\\User', 9, 'MyApp', 'ba63ee3f390806c39bdd32b2aa4257f1505b1a863f4f59180474d08576fd54af', '[\"*\"]', NULL, NULL, '2024-10-09 09:18:34', '2024-10-09 09:18:34'),
(162, 'App\\Models\\User', 9, 'MyApp', '4e097295db5c262f947dae8a819802617b87a9616ece0733e0c51565b6b2bd8e', '[\"*\"]', NULL, NULL, '2024-10-09 09:36:59', '2024-10-09 09:36:59'),
(163, 'App\\Models\\User', 9, 'MyApp', '45f4cba3b094cd1717dc86a44374d90ee835e0153a32f1103cac35faee09d90f', '[\"*\"]', NULL, NULL, '2024-10-09 09:39:57', '2024-10-09 09:39:57'),
(164, 'App\\Models\\User', 9, 'MyApp', '9c7dd9ea5d17f70097324b33182db845ca03844ebb170c2a59295ea69a986b88', '[\"*\"]', NULL, NULL, '2024-10-09 09:43:30', '2024-10-09 09:43:30'),
(165, 'App\\Models\\User', 9, 'MyApp', '9a4c55ebaae9587bdc4520a7e09a9bd30979fde26d6585e21ecfab9893b97ee1', '[\"*\"]', NULL, NULL, '2024-10-09 10:04:09', '2024-10-09 10:04:09'),
(166, 'App\\Models\\User', 9, 'MyApp', '0cd4c9a43f9a937ed275c562d3a06f759d57e3d40f3590a0ed1962ad654b8267', '[\"*\"]', NULL, NULL, '2024-10-09 10:10:04', '2024-10-09 10:10:04'),
(167, 'App\\Models\\User', 9, 'MyApp', '9efe21622dbf14963558434ece7a97034e6aa411a6af4dfaeb785e6bdfcdea45', '[\"*\"]', NULL, NULL, '2024-10-09 10:11:48', '2024-10-09 10:11:48'),
(168, 'App\\Models\\User', 9, 'MyApp', '1393bb249c3937d01c23d1338915674eee999e849987e06ae703468541fb2e3c', '[\"*\"]', NULL, NULL, '2024-10-09 10:13:32', '2024-10-09 10:13:32'),
(169, 'App\\Models\\User', 9, 'MyApp', 'a98c9eb819f4a94237614b293d887216680bb13fee75fe5fc675d5b09bf7ae9d', '[\"*\"]', NULL, NULL, '2024-10-09 10:15:46', '2024-10-09 10:15:46'),
(170, 'App\\Models\\User', 9, 'MyApp', 'baa82dc61b8fac87a0e74bb6aea9f30aebee18efc329f94faf372b79e81e57bb', '[\"*\"]', NULL, NULL, '2024-10-09 10:17:05', '2024-10-09 10:17:05'),
(171, 'App\\Models\\User', 9, 'MyApp', '1edd49e03e4699439fb55e4eb053def8c8062272528f94ba7692fd5d65c10b4b', '[\"*\"]', NULL, NULL, '2024-10-09 11:24:04', '2024-10-09 11:24:04'),
(172, 'App\\Models\\User', 9, 'MyApp', '63974a7349fbf93dcd05cdfaa7c5a4cff88f19bf7c484d843319c64b0286d50b', '[\"*\"]', NULL, NULL, '2024-10-09 11:49:29', '2024-10-09 11:49:29'),
(173, 'App\\Models\\User', 9, 'MyApp', '44e710fccef7b896ae67a820acfe660f88ed2987f82e7d080911fcd5a8e0f43c', '[\"*\"]', NULL, NULL, '2024-10-09 11:51:53', '2024-10-09 11:51:53'),
(174, 'App\\Models\\User', 1, 'MyApp', 'ea6c7b1aa741a958e9856967b4c5114b4540387d21705cb831db803d29531103', '[\"*\"]', NULL, NULL, '2024-10-09 12:06:47', '2024-10-09 12:06:47'),
(175, 'App\\Models\\User', 9, 'MyApp', '56ce64eb24807e42ea37a8c0ee019c8bffa935eb9ab19a3049863308a2aeb948', '[\"*\"]', NULL, NULL, '2024-10-09 12:09:00', '2024-10-09 12:09:00'),
(176, 'App\\Models\\User', 1, 'MyApp', '0dac3fdee9b8f28438063a4df208714b9e9bd84972c8cb8c4d7e249faa725718', '[\"*\"]', NULL, NULL, '2024-10-20 04:48:45', '2024-10-20 04:48:45'),
(177, 'App\\Models\\User', 1, 'MyApp', '0b291090bfaf4cbbbf3b5a1b43233d681b82e754b851e09cc2f0fd28290a7d7d', '[\"*\"]', NULL, NULL, '2024-10-20 06:01:54', '2024-10-20 06:01:54'),
(178, 'App\\Models\\User', 25, 'MyApp', '3e553aec5963430899d2c8900bfd29ab4ef858658461c2144f12cb03ece3eced', '[\"*\"]', NULL, NULL, '2024-10-20 06:02:34', '2024-10-20 06:02:34'),
(179, 'App\\Models\\User', 25, 'MyApp', 'd9dc000ca6198aed98872fb576c4f4659bc7545f3df2b7e0af7e507f31444a41', '[\"*\"]', NULL, NULL, '2024-10-20 06:09:22', '2024-10-20 06:09:22'),
(180, 'App\\Models\\User', 25, 'MyApp', 'b9f9357fb33187b84f583c9b44105375b350377e9a929512a4bf867e0a31cacd', '[\"*\"]', NULL, NULL, '2024-10-21 00:15:23', '2024-10-21 00:15:23'),
(181, 'App\\Models\\User', 25, 'MyApp', 'aa67c642c3bc01abcb14917169fd5ad8e095c599f6d79d99ee8a8c92443ae779', '[\"*\"]', NULL, NULL, '2024-10-21 00:26:22', '2024-10-21 00:26:22'),
(182, 'App\\Models\\User', 25, 'MyApp', 'e564ca9a66e4249648ace70705025e6965cc1bc79ec83c8fa158312e3e0e3093', '[\"*\"]', NULL, NULL, '2024-10-21 00:28:01', '2024-10-21 00:28:01'),
(183, 'App\\Models\\User', 25, 'MyApp', '89e1f3d114c2ce33ca6ded758edcf3f511431fc4f1f388215fb171ac881a3c06', '[\"*\"]', NULL, NULL, '2024-10-21 00:30:25', '2024-10-21 00:30:25'),
(184, 'App\\Models\\User', 25, 'MyApp', '52d72448e079429432ece0b16e2ade70eb8612e51aee31a68b99bf9d07cd8b2a', '[\"*\"]', NULL, NULL, '2024-10-22 00:56:35', '2024-10-22 00:56:35'),
(185, 'App\\Models\\User', 25, 'MyApp', 'f386e985f4a93c0f379f8ca19c82279e700bbc1334cea0f64976b2e0c707b9a1', '[\"*\"]', NULL, NULL, '2024-10-22 03:19:17', '2024-10-22 03:19:17'),
(186, 'App\\Models\\User', 25, 'MyApp', '27fedceddd7846c9a3f71f5787fd9eedfabb999364b23b7a1969bb1293f5f063', '[\"*\"]', NULL, NULL, '2024-10-22 03:56:00', '2024-10-22 03:56:00'),
(187, 'App\\Models\\User', 25, 'MyApp', '372d0191e6e248d039626a103c9a0cd73bf95cb183f7619cae56345618452170', '[\"*\"]', NULL, NULL, '2024-10-22 03:58:08', '2024-10-22 03:58:08'),
(188, 'App\\Models\\User', 25, 'MyApp', 'f1a736848bbe5c736314b8858ec3050cd52c47adaff72a7a51894eef3861b598', '[\"*\"]', NULL, NULL, '2024-10-22 04:02:18', '2024-10-22 04:02:18'),
(189, 'App\\Models\\User', 25, 'MyApp', '7d80818d4dba1c9993a76d79f0c5bd82be45824d396a6f663f3d7eca884f3fc2', '[\"*\"]', NULL, NULL, '2024-10-22 04:02:53', '2024-10-22 04:02:53'),
(190, 'App\\Models\\User', 25, 'MyApp', 'f3eb8a0673e4e994c9bfbb67cf9060158c68c9c6bda3b0fe3e9dc711bd5e4c4b', '[\"*\"]', NULL, NULL, '2024-10-22 04:03:19', '2024-10-22 04:03:19'),
(191, 'App\\Models\\User', 8, 'MyApp', 'ba989479f8e92592810de0c79ccd9916f49a3f96a5df194ae8cccd1e18960d8b', '[\"*\"]', NULL, NULL, '2024-10-22 04:13:49', '2024-10-22 04:13:49'),
(192, 'App\\Models\\User', 1, 'MyApp', '9bbcde36c12e9eb433db865c792cdcd417f92a9d4710e7303c673f8b281c856e', '[\"*\"]', NULL, NULL, '2024-10-22 06:26:37', '2024-10-22 06:26:37'),
(193, 'App\\Models\\User', 1, 'MyApp', '220cf9f61df838486debee904d5fc9aff8141fd1ec169c9366ccefcf629933c8', '[\"*\"]', NULL, NULL, '2024-10-22 06:28:16', '2024-10-22 06:28:16'),
(194, 'App\\Models\\User', 1, 'MyApp', '6af1f2292258e84244761cfde1b8333daea458aacfdda0939df92e0d8bf9f1c6', '[\"*\"]', NULL, NULL, '2024-10-22 10:52:29', '2024-10-22 10:52:29'),
(195, 'App\\Models\\User', 25, 'MyApp', '7388a6f913ac21c6d0f7f54bc56e749a1f3358137d3d1f46542d56172627ddf8', '[\"*\"]', NULL, NULL, '2024-10-22 11:45:19', '2024-10-22 11:45:19'),
(196, 'App\\Models\\User', 25, 'MyApp', '7ea81bca91045ecdd5f3d1e4c26db953da635c647f6aa1b08664a653a6828be7', '[\"*\"]', NULL, NULL, '2024-10-22 11:45:22', '2024-10-22 11:45:22'),
(197, 'App\\Models\\User', 25, 'MyApp', 'ea568d1248401de0163efed91900e572fe2ecbd587e689e2f51352694d60ed43', '[\"*\"]', NULL, NULL, '2024-10-22 12:33:32', '2024-10-22 12:33:32'),
(198, 'App\\Models\\User', 25, 'MyApp', 'd3af2e70bbaeb86315155c430e9795594524030d0ab8be716b98c2e0091eda39', '[\"*\"]', NULL, NULL, '2024-10-22 23:58:02', '2024-10-22 23:58:02'),
(199, 'App\\Models\\User', 25, 'MyApp', 'e11cb11ed06bea900030db7c9634e159ecd23c8aa13cfe12d66af8483a2d526b', '[\"*\"]', NULL, NULL, '2024-10-23 00:04:58', '2024-10-23 00:04:58'),
(200, 'App\\Models\\User', 25, 'MyApp', '6430b2b5fbdd431a741c277ea208bb048cef6221e77153d0505de782c769fb32', '[\"*\"]', NULL, NULL, '2024-10-23 00:31:15', '2024-10-23 00:31:15'),
(201, 'App\\Models\\User', 25, 'MyApp', '2f076c495815f13f74cadc1461f9c9cc091df3743cfb2ae736ea93835089b51a', '[\"*\"]', NULL, NULL, '2024-10-23 03:42:22', '2024-10-23 03:42:22'),
(202, 'App\\Models\\User', 25, 'MyApp', '95b691fee147797bc169bb203d096f700325f585fae7565fd13b85a8ec98d1af', '[\"*\"]', NULL, NULL, '2024-10-23 06:09:20', '2024-10-23 06:09:20');

-- --------------------------------------------------------

--
-- Table structure for table `purchase`
--

CREATE TABLE `purchase` (
  `id` int NOT NULL,
  `voucher_no` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'update accordingly tally voucher no',
  `voucher_type` tinyint NOT NULL,
  `credit_head` int DEFAULT '0',
  `total_amount` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT 'total_amount',
  `discount_amount` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT 'discount_amount',
  `gross_amount` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT 'gross after discount',
  `paid_amount` decimal(11,2) NOT NULL DEFAULT '0.00',
  `narration` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_id` int NOT NULL DEFAULT '0',
  `date` date DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchase`
--

INSERT INTO `purchase` (`id`, `voucher_no`, `voucher_type`, `credit_head`, `total_amount`, `discount_amount`, `gross_amount`, `paid_amount`, `narration`, `user_id`, `date`, `created_at`, `updated_at`) VALUES
(1, 'PUR2510-21', 1, 4, 5710.00, 0.00, 5710.00, 0.00, NULL, 1, '2025-10-13', '2025-10-13 07:33:42', '2025-10-13 07:33:42'),
(2, 'PUR2510-22', 1, 4, 1100.00, 0.00, 1100.00, 0.00, NULL, 1, '2025-10-01', '2025-10-13 08:08:33', '2025-10-13 08:08:33'),
(3, 'PUR2510-23', 1, 4, 1100.00, 0.00, 1100.00, 0.00, NULL, 1, '2025-10-01', '2025-10-13 08:10:37', '2025-10-13 08:10:37'),
(4, 'PUR2510-24', 1, 4, 1100.00, 0.00, 1100.00, 10.00, NULL, 1, '2025-10-01', '2025-10-13 08:11:28', '2025-10-13 08:11:28'),
(5, 'PUR2510-25', 1, 4, 1400.00, 0.00, 1400.00, 0.00, NULL, 1, '2025-10-01', '2025-10-13 08:52:03', '2025-10-13 08:52:03'),
(6, 'PUR2510-26', 1, 4, 1350.00, 0.00, 1350.00, 0.00, NULL, 1, '2025-10-01', '2025-10-13 08:58:25', '2025-10-13 08:58:25'),
(7, 'PUR2510-27', 1, 16, 120.00, 0.00, 120.00, 100.00, NULL, 1, '2025-10-20', '2025-10-20 01:32:22', '2025-10-20 01:32:22'),
(8, 'PUR2510-28', 1, 17, 581.00, 0.00, 581.00, 500.00, NULL, 1, '2025-10-28', '2025-10-28 08:22:20', '2025-10-28 08:22:20'),
(9, 'PUR2510-29', 1, 19, 600.00, 0.00, 600.00, 500.00, NULL, 1, '2025-10-28', '2025-10-28 08:54:47', '2025-10-28 08:54:47'),
(10, 'PUR2511-1', 1, 17, 1100.00, 0.00, 1100.00, 1000.00, 'Narration/Remarks', 1, '2025-11-01', '2025-11-01 06:26:51', '2025-11-01 06:26:52'),
(11, 'PUR2511-2', 1, 17, 2500.00, 0.00, 2500.00, 2000.00, NULL, 1, '2025-11-02', '2025-11-02 01:08:54', '2025-11-02 02:24:53'),
(12, 'PUR2511-3', 1, 20, 3550.00, 0.00, 3550.00, 3550.00, NULL, 1, '2025-11-05', '2025-11-05 00:07:48', '2025-11-05 00:36:54'),
(13, 'PUR2511-4', 1, 20, 525.00, 0.00, 525.00, 500.00, NULL, 1, '2025-11-06', '2025-11-06 23:14:30', '2025-11-06 23:14:30'),
(14, 'PUR2511-5', 1, 20, 1800.00, 0.00, 1800.00, 1500.00, NULL, 1, '2025-11-07', '2025-11-07 00:27:11', '2025-11-07 00:27:11'),
(15, 'PUR2511-6', 1, 16, 2200.00, 0.00, 2200.00, 0.00, NULL, 1, '2025-11-07', '2025-11-07 00:28:47', '2025-11-07 00:28:47'),
(16, 'PUR2511-7', 1, 21, 690.00, 0.00, 690.00, 500.00, 'nnn', 1, '2025-11-14', '2025-11-14 21:43:21', '2025-11-14 21:43:21'),
(17, 'PUR2511-8', 1, 11, 3200.00, 0.00, 3200.00, 3500.00, 'none', 1, '2025-11-15', '2025-11-15 23:10:14', '2025-11-15 23:10:14'),
(18, 'PUR2511-9', 1, 15, 360.00, 0.00, 360.00, 300.00, NULL, 1, '2025-11-16', '2025-11-16 01:09:53', '2025-11-16 01:09:53'),
(19, 'PUR2511-10', 1, 15, 450.00, 0.00, 450.00, 450.00, NULL, 1, '2025-11-16', '2025-11-16 01:12:06', '2025-11-16 01:12:06'),
(20, 'PUR2511-11', 1, 14, 300.00, 0.00, 300.00, 500.00, NULL, 1, '2025-11-16', '2025-11-16 01:12:47', '2025-11-16 01:12:47'),
(21, 'PUR2511-12', 1, 21, 390.00, 0.00, 390.00, 500.00, NULL, 1, '2025-11-17', '2025-11-17 00:13:35', '2025-11-17 00:13:35'),
(22, 'PUR2511-13', 1, 20, 505.00, 0.00, 505.00, 500.00, NULL, 1, '2025-11-17', '2025-11-17 00:15:11', '2025-11-17 00:15:11'),
(23, 'PUR2601-1', 1, 2, 0.00, 0.00, 0.00, 0.00, NULL, 1, '2026-01-16', '2026-01-16 20:02:27', '2026-01-16 20:02:27'),
(24, 'PUR2601-14', 1, 21, 325.00, 0.00, 325.00, 200.00, NULL, 1, '2026-01-17', '2026-01-17 17:24:08', '2026-01-17 17:24:09'),
(25, 'PUR2601-15', 1, 19, 685.00, 0.00, 685.00, 685.00, NULL, 1, '2026-01-19', '2026-01-19 22:01:22', '2026-01-19 22:01:22'),
(26, 'PUR2601-16', 1, 2, 340.00, 0.00, 340.00, 0.00, NULL, 1, '2026-01-19', '2026-01-19 22:06:30', '2026-01-19 22:06:30'),
(27, 'PUR2605-1', 1, 11, 660.00, 0.00, 660.00, 620.00, NULL, 1, '2026-05-07', '2026-05-07 03:59:12', '2026-05-07 03:59:12');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `layer` int DEFAULT '0',
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `layer`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'web', 1, 1, '2024-06-25 08:37:23', '2024-06-25 22:48:08'),
(2, 'NSM', 'web', 3, 1, '2024-06-25 05:47:23', '2024-08-28 07:19:52'),
(3, 'TSM', 'web', 6, 1, '2024-08-28 07:18:24', '2024-08-28 07:18:24'),
(4, 'RSM', 'web', 5, 1, '2024-08-28 07:18:47', '2024-08-28 07:18:47'),
(5, 'DSM', 'web', 4, 1, '2024-08-28 07:19:30', '2024-08-28 07:19:30'),
(6, 'Admin', 'web', 1, 1, '2024-09-01 12:24:04', '2024-09-01 12:24:04'),
(7, 'ZSM', 'web', 5, 1, '2024-11-03 23:54:40', '2024-11-03 23:54:40'),
(8, 'GM', 'web', 2, 1, '2024-11-11 01:04:27', '2024-11-11 01:04:27'),
(9, 'SR', 'web', 0, 1, '2025-10-21 20:38:24', '2025-10-21 20:38:24');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(22, 1),
(23, 1),
(24, 1),
(25, 1),
(26, 1),
(27, 1),
(28, 1),
(32, 1),
(33, 1),
(19, 2),
(20, 2),
(21, 2),
(22, 2),
(32, 2),
(33, 2),
(19, 3),
(20, 3),
(21, 3),
(22, 3),
(25, 3),
(26, 3),
(27, 3),
(32, 3),
(33, 3),
(19, 4),
(20, 4),
(21, 4),
(22, 4),
(23, 4),
(25, 4),
(32, 4),
(33, 4),
(19, 5),
(20, 5),
(21, 5),
(22, 5),
(23, 5),
(25, 5),
(32, 5),
(33, 5),
(1, 6),
(14, 6),
(15, 6),
(16, 6),
(18, 6),
(19, 6),
(20, 6),
(21, 6),
(22, 6),
(23, 6),
(24, 6),
(25, 6),
(26, 6),
(27, 6),
(28, 6),
(32, 6),
(33, 6),
(19, 7),
(20, 7),
(21, 7),
(22, 7),
(23, 7),
(25, 7),
(32, 7),
(33, 7),
(19, 8),
(20, 8),
(21, 8),
(22, 8),
(25, 8),
(26, 8),
(27, 8),
(32, 8),
(33, 8),
(21, 9),
(25, 9),
(26, 9);

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int NOT NULL,
  `voucher_no` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'update accordingly tally voucher no',
  `voucher_type` tinyint NOT NULL,
  `debit_head` int DEFAULT '0',
  `total_amount` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT 'total_amount',
  `discount_amount` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT 'discount_amount',
  `gross_amount` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT 'gross after discount',
  `paid_amount` decimal(11,2) NOT NULL DEFAULT '0.00',
  `narration` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_id` int NOT NULL DEFAULT '0',
  `date` date DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_items`
--

CREATE TABLE `stock_items` (
  `id` int NOT NULL,
  `alias` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'name_en',
  `name_bn` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit` int DEFAULT NULL,
  `salesRate` decimal(11,2) NOT NULL DEFAULT '0.00',
  `purchaseRate` decimal(11,2) NOT NULL DEFAULT '0.00',
  `manufacturer` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `barcode` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `part_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` decimal(11,2) DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_item_permission`
--

CREATE TABLE `stock_item_permission` (
  `stock_item_group` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `id` bigint NOT NULL,
  `user_id` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'PC', NULL, NULL),
(2, 'Kg', NULL, NULL),
(3, 'Ltr', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `supervisors` int NOT NULL DEFAULT '0',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `mobile`, `supervisors`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Alisoft', 'admin@alisoftbd.com', '01515293030', 0, NULL, '$2y$10$iCa1Pn01EmOZG8qZ.qUQ6u0pIJfy9Th0Z2iHBSmQhH..vr5npnfxS', 'vZAoBKDoDvuLMwHhZysWgULMTcsHyLKywnQB0Uct5YAEdsb584emVGMR6kzJ', '2024-06-10 12:21:16', '2024-09-02 06:14:05'),
(8, 'MD. ZUEL ALI', 'zuel.cse@gmail.com', '01738051123', 0, NULL, '$2y$10$iCa1Pn01EmOZG8qZ.qUQ6u0pIJfy9Th0Z2iHBSmQhH..vr5npnfxS', 'T725QirBTTEgUBk3yksF4yoltGVfEN921WV9DgOLgMI9t59ay9Hyp9Cn3N66', '2024-08-27 06:35:16', '2026-05-19 06:04:26'),
(24, 'Md. Jalal', 'jalal@gmail.com', '01734496418', 0, NULL, '$2y$10$7nxU9/oXk1SBTee/BKwPTOldkkhvn5214XGCY4Axogw5tkQqarH3u', NULL, '2024-10-17 03:36:42', '2026-05-19 06:06:02'),
(39, 'Liton', 'locknathpp88@gmail.com', '01712423272', 0, NULL, '$2y$10$P15109GmhoBEqkphXH6QwuFEv0IoZTPGipEwz5nglt3M/StdHNyau', NULL, '2024-11-11 00:02:33', '2026-05-19 06:03:50');

-- --------------------------------------------------------

--
-- Table structure for table `voucher`
--

CREATE TABLE `voucher` (
  `id` int NOT NULL,
  `date` date NOT NULL,
  `voucher_no` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `voucher_type` tinyint DEFAULT NULL,
  `total_amount` decimal(11,2) NOT NULL,
  `payment_mode` tinyint DEFAULT NULL COMMENT '1:Cash,\r\n2:Bank,\r\n3:adjustment',
  `narration_remarks` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `cheque_no` varchar(30) DEFAULT NULL,
  `cheque_date` date DEFAULT NULL,
  `user_id` int NOT NULL DEFAULT '0',
  `status` tinyint NOT NULL DEFAULT '0' COMMENT '-1:softDelete\r\n0:int\r\n1:approved\r\n2:Tally Updated',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `voucher`
--

INSERT INTO `voucher` (`id`, `date`, `voucher_no`, `voucher_type`, `total_amount`, `payment_mode`, `narration_remarks`, `cheque_no`, `cheque_date`, `user_id`, `status`, `created_at`, `updated_at`) VALUES
(1, '2026-05-20', 'JOV2605-1', 13, 72000.00, NULL, NULL, NULL, NULL, 39, 1, '2026-05-20 11:29:00', '2026-05-20 11:29:00'),
(2, '2026-05-20', 'JOV2605-2', 13, 15000.00, NULL, NULL, NULL, NULL, 8, 1, '2026-05-20 11:45:14', '2026-05-21 13:25:27');

-- --------------------------------------------------------

--
-- Table structure for table `voucher_numbers`
--

CREATE TABLE `voucher_numbers` (
  `id` int NOT NULL,
  `voucher_type_id` int NOT NULL DEFAULT '0',
  `ym` int NOT NULL DEFAULT '0' COMMENT 'year_month',
  `serial` int NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `voucher_numbers`
--

INSERT INTO `voucher_numbers` (`id`, `voucher_type_id`, `ym`, `serial`, `created_at`, `updated_at`) VALUES
(1, 3, 2605, 0, '2026-05-20 11:07:47', '2026-05-20 11:07:47'),
(2, 13, 2605, 2, '2026-05-20 11:26:46', '2026-05-20 11:45:14'),
(3, 1, 2605, 0, '2026-05-21 17:54:19', '2026-05-21 17:54:19');

-- --------------------------------------------------------

--
-- Table structure for table `voucher_types`
--

CREATE TABLE `voucher_types` (
  `id` int NOT NULL,
  `voucher_name` varchar(100) NOT NULL,
  `type` tinyint NOT NULL COMMENT '1:Purchase,\r\n2:Sales,\r\n3:Voucher',
  `voucher_prefix` varchar(20) NOT NULL,
  `voucher_prefix2` varchar(10) NOT NULL DEFAULT '0',
  `start_number` int NOT NULL,
  `goddown` varchar(50) DEFAULT NULL,
  `sales_account` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `voucher_types`
--

INSERT INTO `voucher_types` (`id`, `voucher_name`, `type`, `voucher_prefix`, `voucher_prefix2`, `start_number`, `goddown`, `sales_account`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Purchase', 1, 'PUR', '', 0, NULL, NULL, 1, '2026-05-20 14:33:43', '2026-05-20 14:33:43'),
(2, 'Purchase Return', 2, 'PRE', '', 0, NULL, NULL, 1, '2026-05-20 14:33:43', '2026-05-20 14:33:43'),
(3, 'Sales', 2, 'INV', '', 0, NULL, NULL, 1, '2026-05-20 14:33:43', '2026-05-20 14:33:43'),
(4, 'Sales Return', 1, 'SRE', '', 0, NULL, NULL, 1, '2026-05-20 14:33:43', '2026-05-20 14:33:43'),
(9, 'Cash Payment', 4, 'CPV', '', 0, NULL, NULL, 1, '2026-05-20 14:33:43', '2026-05-20 14:33:43'),
(10, 'Cash Receive', 4, 'CRV', '', 0, NULL, NULL, 1, '2026-05-20 14:33:43', '2026-05-20 14:33:43'),
(11, 'Bank Payment', 4, 'BPV', '', 0, NULL, NULL, 1, '2026-05-20 14:33:43', '2026-05-20 14:33:43'),
(12, 'Bank Receive', 4, 'BRV', '', 0, NULL, NULL, 1, '2026-05-20 14:33:43', '2026-05-20 14:33:43'),
(13, 'Adjustment', 4, 'JOV', '', 0, NULL, NULL, 1, '2026-05-20 14:33:43', '2026-05-20 14:33:43');

-- --------------------------------------------------------

--
-- Table structure for table `work_names`
--

CREATE TABLE `work_names` (
  `id` int NOT NULL,
  `debit_head` int NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `name_bn` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `work_names`
--

INSERT INTO `work_names` (`id`, `debit_head`, `name`, `name_bn`, `created_at`, `updated_at`) VALUES
(1, 15, 'Suresh 120ml', 'সুরেশ ১২0 মিলি', '2026-05-20 10:56:45', '2026-05-20 10:56:45'),
(2, 15, 'Suresh 250ml', 'সুরেশ 250 মিলি', '2026-05-20 10:57:11', '2026-05-20 10:57:11'),
(3, 15, 'Suresh 500ml', 'সুরেশ ৫00 মিলি', '2026-05-20 10:58:03', '2026-05-20 10:58:03');

-- --------------------------------------------------------

--
-- Table structure for table `work_types`
--

CREATE TABLE `work_types` (
  `id` int NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `name_bn` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `work_types`
--

INSERT INTO `work_types` (`id`, `name`, `name_bn`, `created_at`, `updated_at`) VALUES
(1, 'Box', 'বক্স', '2026-05-19 15:01:19', '2026-05-19 15:01:19'),
(2, 'Saree Box', 'শাড়ির বক্স', '2026-05-19 15:01:19', '2026-05-19 15:01:19'),
(3, 'Lungi box', 'লুঙ্গীর বক্স', '2026-05-19 15:01:19', '2026-05-19 15:01:19'),
(4, 'Parcel box', 'মিষ্টির বক্স', '2026-05-19 15:01:19', '2026-05-19 15:01:19'),
(5, 'Perfume Box', 'পারফিউম বক্স', '2026-05-19 15:01:19', '2026-05-19 15:01:19'),
(6, 'Label', 'লেবেল', '2026-05-19 15:01:19', '2026-05-19 15:01:19'),
(7, 'Fancy Label', 'ফ্যান্সি লেবেল', '2026-05-19 15:01:19', '2026-05-19 15:01:19'),
(8, 'Hologram', 'হলোগ্রাম', '2026-05-19 15:01:19', '2026-05-19 15:01:19'),
(9, 'Hologram Label', 'হলোগ্রাম লেবেল', '2026-05-19 15:01:19', '2026-05-19 15:01:19'),
(10, 'Metallic', 'মেটালিক', '2026-05-19 15:01:19', '2026-05-19 15:01:19'),
(11, 'Metallic Label', 'মেটালিক লেবেল', '2026-05-19 15:01:19', '2026-05-19 15:01:19'),
(12, 'Saree Label', 'শাড়ির লেবেল', '2026-05-19 15:01:19', '2026-05-19 15:01:19'),
(13, 'Large Saree Label', 'শাড়ির বড় লেবেল', '2026-05-19 15:01:19', '2026-05-19 15:01:19'),
(14, 'Small Saree Label', 'শাড়ির ছোট লেবেল', '2026-05-19 15:01:19', '2026-05-19 15:01:19'),
(15, 'Lungi Label', 'লুঙ্গীর লেবেল', '2026-05-19 15:01:19', '2026-05-19 15:01:19'),
(16, 'Large Lungi Label', 'লুঙ্গীর বড় লেবেল', '2026-05-19 15:01:19', '2026-05-19 15:01:19'),
(17, 'Small Lungi Label', 'লুঙ্গীর ছোট লেবেল', '2026-05-19 15:01:19', '2026-05-19 15:01:19'),
(18, 'Catalog', 'ক্যাটালগ', '2026-05-19 15:01:19', '2026-05-19 15:01:19'),
(19, 'Large Catalog', 'বড় ক্যাটালগ', '2026-05-19 15:01:19', '2026-05-19 15:01:19'),
(20, 'Small Catalog', 'ছোট ক্যাটালগ', '2026-05-19 15:01:19', '2026-05-19 15:01:19'),
(21, 'Ribbon (Fita)', 'ফিতা', '2026-05-19 15:01:19', '2026-05-19 15:01:19'),
(22, 'Tissue bag BP', 'টিস্যু ব্যাগ বি.পি', '2026-05-19 15:01:19', '2026-05-19 15:01:19'),
(23, 'Memo', 'মেমো', '2026-05-19 15:01:19', '2026-05-19 15:01:19'),
(24, 'Challan', 'চালান', '2026-05-19 15:01:19', '2026-05-19 15:01:19'),
(25, 'Money receipt', 'মানি রিসিট', '2026-05-19 15:01:19', '2026-05-19 15:01:19'),
(26, 'Pad', 'পেড', '2026-05-19 15:01:19', '2026-05-19 15:01:19'),
(27, 'Visiting card', 'ভিজিটিং কার্ড', '2026-05-19 15:20:33', '2026-05-19 15:20:33'),
(28, 'Handbill', 'হ্যান্ডবিল', '2026-05-19 15:01:19', '2026-05-19 15:01:19');

-- --------------------------------------------------------

--
-- Table structure for table `work_type_colors`
--

CREATE TABLE `work_type_colors` (
  `id` int NOT NULL,
  `work_type_id` int NOT NULL,
  `root_id` int NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `work_type_laminations`
--

CREATE TABLE `work_type_laminations` (
  `id` int NOT NULL,
  `work_type_id` int NOT NULL,
  `root_id` int NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `work_type_papers`
--

CREATE TABLE `work_type_papers` (
  `id` int NOT NULL,
  `work_type_id` int NOT NULL,
  `root_id` int NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `work_type_sizes`
--

CREATE TABLE `work_type_sizes` (
  `id` int NOT NULL,
  `work_type_id` int NOT NULL,
  `root_id` int NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `work_type_weights`
--

CREATE TABLE `work_type_weights` (
  `id` int NOT NULL,
  `work_type_id` int NOT NULL,
  `root_id` int NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `general_settings`
--
ALTER TABLE `general_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `group_main`
--
ALTER TABLE `group_main`
  ADD PRIMARY KEY (`id`),
  ADD KEY `alias` (`alias`);

--
-- Indexes for table `group_main_ac`
--
ALTER TABLE `group_main_ac`
  ADD PRIMARY KEY (`id`),
  ADD KEY `alias` (`alias`);

--
-- Indexes for table `group_sub`
--
ALTER TABLE `group_sub`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `main_group_id_2` (`main_group_id`,`name`),
  ADD UNIQUE KEY `alias` (`alias`),
  ADD KEY `main_group_id` (`main_group_id`);

--
-- Indexes for table `ledgers`
--
ALTER TABLE `ledgers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `alias` (`alias`),
  ADD KEY `main_group_id` (`main_group_id`),
  ADD KEY `sub_group_id` (`sub_group_id`);

--
-- Indexes for table `location_areas`
--
ALTER TABLE `location_areas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `location_regions`
--
ALTER TABLE `location_regions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `location_territorys`
--
ALTER TABLE `location_territorys`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `manufacturer`
--
ALTER TABLE `manufacturer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `master_colors`
--
ALTER TABLE `master_colors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `master_items`
--
ALTER TABLE `master_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `voucher_no` (`voucher_no`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `debit_head` (`debit_head`),
  ADD KEY `credit_head` (`credit_head`),
  ADD KEY `date` (`date`),
  ADD KEY `voucher_type` (`voucher_type`);

--
-- Indexes for table `master_laminations`
--
ALTER TABLE `master_laminations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `master_papers`
--
ALTER TABLE `master_papers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `master_sizes`
--
ALTER TABLE `master_sizes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `master_voucher`
--
ALTER TABLE `master_voucher`
  ADD PRIMARY KEY (`id`),
  ADD KEY `voucher_no` (`voucher_no`);

--
-- Indexes for table `master_weights`
--
ALTER TABLE `master_weights`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `oauth_access_tokens`
--
ALTER TABLE `oauth_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_access_tokens_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_auth_codes`
--
ALTER TABLE `oauth_auth_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_auth_codes_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_clients_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_refresh_tokens`
--
ALTER TABLE `oauth_refresh_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`);

--
-- Indexes for table `parent_permissions`
--
ALTER TABLE `parent_permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `purchase`
--
ALTER TABLE `purchase`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `voucher_no` (`voucher_no`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `voucher_no` (`voucher_no`),
  ADD KEY `debit_head` (`debit_head`),
  ADD KEY `date` (`date`);

--
-- Indexes for table `stock_items`
--
ALTER TABLE `stock_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `barcode` (`barcode`);

--
-- Indexes for table `stock_item_permission`
--
ALTER TABLE `stock_item_permission`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mobile` (`mobile`);

--
-- Indexes for table `voucher`
--
ALTER TABLE `voucher`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `voucher_no` (`voucher_no`);

--
-- Indexes for table `voucher_numbers`
--
ALTER TABLE `voucher_numbers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `voucher_type_id` (`voucher_type_id`,`ym`);

--
-- Indexes for table `voucher_types`
--
ALTER TABLE `voucher_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `work_names`
--
ALTER TABLE `work_names`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `name_bn` (`name_bn`);

--
-- Indexes for table `work_types`
--
ALTER TABLE `work_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `name_bn` (`name_bn`);

--
-- Indexes for table `work_type_colors`
--
ALTER TABLE `work_type_colors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `basic_id` (`root_id`),
  ADD KEY `work_type_id` (`work_type_id`);

--
-- Indexes for table `work_type_laminations`
--
ALTER TABLE `work_type_laminations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `basic_id` (`root_id`),
  ADD KEY `work_type_id` (`work_type_id`);

--
-- Indexes for table `work_type_papers`
--
ALTER TABLE `work_type_papers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `basic_id` (`root_id`),
  ADD KEY `work_type_id` (`work_type_id`);

--
-- Indexes for table `work_type_sizes`
--
ALTER TABLE `work_type_sizes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `basic_id` (`root_id`),
  ADD KEY `work_type_id` (`work_type_id`);

--
-- Indexes for table `work_type_weights`
--
ALTER TABLE `work_type_weights`
  ADD PRIMARY KEY (`id`),
  ADD KEY `basic_id` (`root_id`),
  ADD KEY `work_type_id` (`work_type_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `general_settings`
--
ALTER TABLE `general_settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `group_main`
--
ALTER TABLE `group_main`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `group_main_ac`
--
ALTER TABLE `group_main_ac`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `group_sub`
--
ALTER TABLE `group_sub`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `ledgers`
--
ALTER TABLE `ledgers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `location_areas`
--
ALTER TABLE `location_areas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `location_regions`
--
ALTER TABLE `location_regions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `location_territorys`
--
ALTER TABLE `location_territorys`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `manufacturer`
--
ALTER TABLE `manufacturer`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `master_colors`
--
ALTER TABLE `master_colors`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `master_items`
--
ALTER TABLE `master_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `master_laminations`
--
ALTER TABLE `master_laminations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `master_papers`
--
ALTER TABLE `master_papers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `master_sizes`
--
ALTER TABLE `master_sizes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `master_voucher`
--
ALTER TABLE `master_voucher`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `master_weights`
--
ALTER TABLE `master_weights`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `parent_permissions`
--
ALTER TABLE `parent_permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=203;

--
-- AUTO_INCREMENT for table `purchase`
--
ALTER TABLE `purchase`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_items`
--
ALTER TABLE `stock_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_item_permission`
--
ALTER TABLE `stock_item_permission`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=254;

--
-- AUTO_INCREMENT for table `voucher`
--
ALTER TABLE `voucher`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `voucher_numbers`
--
ALTER TABLE `voucher_numbers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `voucher_types`
--
ALTER TABLE `voucher_types`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `work_names`
--
ALTER TABLE `work_names`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `work_types`
--
ALTER TABLE `work_types`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `work_type_colors`
--
ALTER TABLE `work_type_colors`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `work_type_laminations`
--
ALTER TABLE `work_type_laminations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `work_type_papers`
--
ALTER TABLE `work_type_papers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `work_type_sizes`
--
ALTER TABLE `work_type_sizes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `work_type_weights`
--
ALTER TABLE `work_type_weights`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `work_type_colors`
--
ALTER TABLE `work_type_colors`
  ADD CONSTRAINT `work_type_colors_ibfk_1` FOREIGN KEY (`root_id`) REFERENCES `master_colors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `work_type_colors_ibfk_2` FOREIGN KEY (`work_type_id`) REFERENCES `work_types` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Constraints for table `work_type_sizes`
--
ALTER TABLE `work_type_sizes`
  ADD CONSTRAINT `work_type_sizes_ibfk_1` FOREIGN KEY (`root_id`) REFERENCES `master_sizes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `work_type_sizes_ibfk_2` FOREIGN KEY (`work_type_id`) REFERENCES `work_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `work_type_weights`
--
ALTER TABLE `work_type_weights`
  ADD CONSTRAINT `work_type_weights_ibfk_1` FOREIGN KEY (`root_id`) REFERENCES `master_weights` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `work_type_weights_ibfk_2` FOREIGN KEY (`work_type_id`) REFERENCES `work_types` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

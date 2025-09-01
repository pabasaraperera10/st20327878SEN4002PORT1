-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 01, 2025 at 05:06 PM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hospital_db`
--
CREATE DATABASE IF NOT EXISTS `hospital_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `hospital_db`;

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

DROP TABLE IF EXISTS `appointments`;
CREATE TABLE IF NOT EXISTS `appointments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `patient_id` int NOT NULL,
  `doctor_id` int DEFAULT NULL,
  `patient_name` varchar(100) DEFAULT NULL,
  `patient_age` int DEFAULT NULL,
  `appointment_date` date DEFAULT NULL,
  `appointment_time` time DEFAULT NULL,
  `status` enum('pending','confirmed','cancelled','completed') DEFAULT 'pending',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `patient_id` (`patient_id`),
  KEY `doctor_id` (`doctor_id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `patient_id`, `doctor_id`, `patient_name`, `patient_age`, `appointment_date`, `appointment_time`, `status`, `created_at`) VALUES
(15, 3, 1, 'siththaravi amaya', 22, '2025-09-06', '01:38:00', 'pending', '2025-08-28 23:38:16'),
(14, 3, 3, 'silumina abewardana', 34, '2025-08-28', '09:10:00', 'pending', '2025-08-21 15:11:04'),
(5, 1, 1, 'kaveeesha modini', 22, '2025-09-04', '12:07:00', 'confirmed', '2025-08-19 10:06:54'),
(16, 9, 4, 'himaya oshadi', 25, '2025-09-06', '10:40:00', 'confirmed', '2025-08-29 16:39:28'),
(13, 3, 2, 'nimesha mudungoda', 23, '2025-09-06', '13:46:00', 'pending', '2025-08-21 09:45:24'),
(12, 8, 2, 'Bavidu Shan', 23, '2025-09-04', '15:39:00', 'confirmed', '2025-08-20 21:38:57'),
(11, 7, 2, 'sithum abewardana', 56, '2025-08-26', '23:37:00', 'confirmed', '2025-08-20 21:37:33'),
(19, 12, 2, 'sithumina gunwardana', 33, '2025-08-30', '10:55:00', 'confirmed', '2025-08-29 16:54:59'),
(21, 10, 4, 'Ramani perera', 56, '2025-09-03', '13:00:00', 'confirmed', '2025-08-31 16:57:25'),
(22, 13, 4, 'kaveeesha siyume', 44, '2025-09-03', '13:04:00', 'confirmed', '2025-08-31 17:04:57'),
(23, 12, 4, 'pabasara sewwandi', 24, '2025-09-03', '12:46:00', 'pending', '2025-09-01 09:46:31');

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

DROP TABLE IF EXISTS `doctors`;
CREATE TABLE IF NOT EXISTS `doctors` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `specialty` varchar(50) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`id`, `user_id`, `full_name`, `specialty`, `phone`, `email`, `created_at`) VALUES
(1, 5, 'Dr John Smith', 'Cardiology', '0712345673', 'john.smith@example.com', '2025-08-17 09:04:46'),
(2, 6, 'Dr perera', 'ENT Surgeon', '0712345675', 'perera12@gmail.com', '2025-08-17 09:50:36'),
(3, 7, 'Dr.Abhaya Jayasekara', 'eye Surgeon', '7604512345', 'abayajayasekara12@gmail.com', '2025-08-17 14:10:12'),
(4, 8, 'Dr.gunawardana', 'Dermatologist', '0712345676', 'gunawardana123@gmail.com', '2025-08-28 23:42:20'),
(5, 11, 'Dr.Nuwan abayanayka', 'Cardiology', '0712356765', 'nuwanabayanayka@gmail.com', '2025-08-31 17:00:13'),
(6, 15, 'Dr.Aruna munasinha', 'physician', '0771883478', 'arunamunasinha@gmail.com', '2025-09-01 10:12:13');

-- --------------------------------------------------------

--
-- Table structure for table `doctor_availability`
--

DROP TABLE IF EXISTS `doctor_availability`;
CREATE TABLE IF NOT EXISTS `doctor_availability` (
  `id` int NOT NULL AUTO_INCREMENT,
  `doctor_id` int NOT NULL,
  `available_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `status` enum('available','unavailable') DEFAULT 'available',
  PRIMARY KEY (`id`),
  KEY `doctor_id` (`doctor_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `doctor_availability`
--

INSERT INTO `doctor_availability` (`id`, `doctor_id`, `available_date`, `start_time`, `end_time`, `created_at`, `status`) VALUES
(1, 5, '2025-08-30', '09:00:00', '12:00:00', '2025-08-17 09:06:53', 'available'),
(2, 6, '2025-08-30', '10:51:00', '12:00:00', '2025-08-17 09:51:42', 'available'),
(3, 7, '2025-08-30', '08:00:00', '10:00:00', '2025-08-17 14:11:11', 'available'),
(4, 8, '2025-09-03', '13:00:00', '16:00:00', '2025-08-28 23:43:23', 'available'),
(5, 11, '2025-09-05', '18:01:00', '22:02:00', '2025-08-31 17:01:16', 'available'),
(6, 15, '2025-10-01', '13:00:00', '16:00:00', '2025-09-01 10:14:04', 'available');

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

DROP TABLE IF EXISTS `patients`;
CREATE TABLE IF NOT EXISTS `patients` (
  `id` int NOT NULL AUTO_INCREMENT,
  `full_name` varchar(100) NOT NULL,
  `age` int DEFAULT NULL,
  `gender` enum('Male','Female') DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`id`, `full_name`, `age`, `gender`, `phone`, `email`, `address`, `created_at`) VALUES
(1, 'kaveeesha modini', 22, 'Female', '0712345675', 'kaveeshamodini@gmail.com', '278/56 nagahakotuwa road ,imbulgoda', '2025-08-19 10:06:54'),
(9, 'himaya oshadi', 25, 'Female', '0776633446', 'himayaoshadi12@gmail.com', '123/56 pahalayagoda,mirisswattha', '2025-08-29 16:39:28'),
(7, 'sithum abewardana', 56, 'Female', '0712345676', 'sithumabewardana@gmail.com', '278/56 phalayagoda,imbulgoda', '2025-08-20 21:37:33'),
(8, 'Bavidu Shan', 23, 'Female', '0712345677', 'baviduShan@gmail.com', '278/56 paliyagoda road ,colombo', '2025-08-20 21:38:57'),
(12, 'sithumina gunwardana', 33, 'Female', '0712356777', 'sithuminagunwardana12@gmail.com', '123/45 sanhidmawatha,gampha', '2025-08-29 16:54:59'),
(13, 'kaveeesha siyume', 44, 'Female', '0712345677', 'kaveeshasiyumi12@gmail.com', '278/57 nagahakotuwa road ,imbulgoda', '2025-08-31 17:04:57');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
CREATE TABLE IF NOT EXISTS `payments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `appointment_id` int NOT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `payment_status` enum('unpaid','late paid','paid') NOT NULL DEFAULT 'unpaid',
  `paid_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `appointment_id` (`appointment_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `appointment_id`, `amount`, `payment_status`, `paid_at`, `created_at`, `updated_at`) VALUES
(1, 1, 0.00, 'unpaid', NULL, '2025-08-19 04:23:52', '2025-08-19 04:23:52'),
(2, 2, 0.00, 'unpaid', NULL, '2025-08-19 04:24:43', '2025-08-19 04:24:43'),
(3, 3, 0.00, 'unpaid', NULL, '2025-08-19 04:30:27', '2025-08-19 04:30:27'),
(4, 4, 0.00, 'unpaid', NULL, '2025-08-19 04:30:57', '2025-08-19 04:30:57'),
(5, 13, 0.00, 'unpaid', NULL, '2025-08-21 04:15:24', '2025-08-21 04:15:24'),
(6, 14, 0.00, 'unpaid', NULL, '2025-08-21 09:41:04', '2025-08-21 09:41:04'),
(7, 15, 0.00, 'unpaid', NULL, '2025-08-28 18:08:16', '2025-08-28 18:08:16'),
(8, 20, 2000.00, 'paid', '2025-08-29 11:37:33', '2025-08-29 11:36:00', '2025-08-29 11:37:33'),
(9, 21, 2000.00, 'paid', '2025-08-31 12:03:02', '2025-08-31 11:27:25', '2025-08-31 12:03:02'),
(10, 23, 0.00, 'unpaid', NULL, '2025-09-01 04:16:31', '2025-09-01 04:16:31');

-- --------------------------------------------------------

--
-- Table structure for table `receptionists`
--

DROP TABLE IF EXISTS `receptionists`;
CREATE TABLE IF NOT EXISTS `receptionists` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','doctor','patient','receptionist') NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'admin1', 'admin123', 'admin'),
(2, 'doctor1', 'doctor123', 'doctor'),
(3, 'patient1', 'patient123', 'patient'),
(4, 'recept1', 'recept123', 'receptionist'),
(5, 'admin2', '$2y$10$I9CZvuDdKwl7CWe0sjfL6OcgT9ksMcjO9Nl5ATbIX4k0Vml6/6dJi', 'doctor'),
(6, 'admin3', '$2y$10$fhNM0VfU3K5ySE.SOWHf6OsOYU.yDzMA9zFvSQaZRdksPCP7JhTqq', 'doctor'),
(7, 'admin4', '$2y$10$PRXDSB3AOlO.Lyy2kd/QseZnanmp4.H2dZHweBUaIEmhat5AZ4sea', 'doctor'),
(8, 'admin6', '$2y$10$qTmxRIm4goQe9x1AMseTVO5WaDx0/ftk/4Z0qJ58yVODux0r4Dueu', 'doctor'),
(11, 'admin8', '$2y$10$bMRanN7KXp2TY7DIWtFAtuqAlvGsxAvvRgnWi1cJHuoZmJVuyHvXy', 'doctor'),
(10, 'hansani', 'abc1234', 'patient'),
(12, 'pabasara', 'paba@123', 'patient'),
(13, 'sewwandi', '12345', 'patient'),
(14, 'siyumi', '12#12', 'patient'),
(15, 'admin9', '$2y$10$cKhny91ldWlXTKUh67d0cu/pGQQwMEajaQLGdSyaaU8tYAby8m1YW', 'doctor');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

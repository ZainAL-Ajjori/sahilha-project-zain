-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 16 فبراير 2026 الساعة 13:57
-- إصدار الخادم: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sahilha_new_db`
--

-- --------------------------------------------------------

--
-- بنية الجدول `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_user_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `message_text` text NOT NULL,
  `is_from_service` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `full_name` varchar(255) NOT NULL,
  `service_type` varchar(100) NOT NULL,
  `location` varchar(100) NOT NULL,
  `price` varchar(100) DEFAULT 'حسب الاتفاق',
  `work_days` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT 'default.jpg',
  `total_rating` int(11) DEFAULT 0,
  `rating_count` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `services`
--

INSERT INTO `services` (`id`, `user_id`, `full_name`, `service_type`, `location`, `price`, `work_days`, `notes`, `username`, `password`, `image`, `total_rating`, `rating_count`, `created_at`) VALUES
(1, 1, 'صابرين وشاح', 'photography', 'gaza', '100 شيكل على الساعة', 'يوميا من 9 صباحا حتى 9 مساء', 'تصوير كاميرا احترافي بجودة عالية باسعار تناسب الجميع', 'sabreen', 'sabreen', 'default.jpg', 0, 0, '2026-02-15 20:58:20'),
(3, 2, 'حسن حمودة', 'transport', 'khanyounis', 'حسب المكان', 'يوميا من 9 صباحا حتى 9 مساء', 'لدينا توصيل لجميع مناطق غزة ونمتاز بالسرعة', 'hassan', '11112', 'default.jpg', 0, 0, '2026-02-16 11:30:37'),
(6, 3, 'محمد صبري', 'transport', 'gaza', 'حسب المكان', 'على مدار الساعة', 'نتميز بالسرعة بالوصول اليك', 'mohammed', '11112', 'default.jpg', 0, 0, '2026-02-16 11:31:57'),
(8, 4, 'احمد اللوح', 'transport', 'gaza', 'حسب المكان', 'متاح في اي وقت', 'توصيل سريع جدا لباب البيت', 'ahmed', '11112', 'default.jpg', 0, 0, '2026-02-16 12:22:32');

-- --------------------------------------------------------

--
-- بنية الجدول `site_users`
--

CREATE TABLE `site_users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `site_users`
--

INSERT INTO `site_users` (`id`, `full_name`, `username`, `password`, `phone`, `created_at`) VALUES
(1, '', 'sabreen', 'sabreen', NULL, '2026-02-15 20:56:47'),
(2, 'حسن حمودة', 'hassan', '11112', NULL, '2026-02-16 11:30:37'),
(3, 'محمد صبري', 'mohammed', '11112', NULL, '2026-02-16 11:31:57'),
(4, 'احمد اللوح', 'ahmed', '11112', NULL, '2026-02-16 12:22:32');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `site_users`
--
ALTER TABLE `site_users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `site_users`
--
ALTER TABLE `site_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

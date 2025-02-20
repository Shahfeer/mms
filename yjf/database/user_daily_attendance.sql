-- phpMyAdmin SQL Dump
-- version 4.4.15.10
-- https://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 22, 2022 at 07:43 AM
-- Server version: 8.0.27
-- PHP Version: 7.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `face_detect`
--

-- --------------------------------------------------------

--
-- Table structure for table `user_daily_attendance`
--

CREATE TABLE IF NOT EXISTS `user_daily_attendance` (
  `attendance_id` int NOT NULL,
  `face_id` varchar(50) NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `user_image` varchar(200) NOT NULL,
  `attendance_status` char(1) NOT NULL,
  `attn_entry_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `attn_flag` int NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `user_daily_attendance`
--

INSERT INTO `user_daily_attendance` (`attendance_id`, `face_id`, `user_name`, `user_image`, `attendance_status`, `attn_entry_date`, `attn_flag`) VALUES
(1, '13ca5193-bf00-4691-95c6-7f8ca75205a8', 'Lakshmikanthan', 'https://lakshmikanthan.s3.ap-south-1.amazonaws.com/face-collection/13ca5193-bf00-4691-95c6-7f8ca75205a8.jpg', 'Y', '2022-02-27 07:23:09', 1),
(2, 'd9e65f36-a752-402e-8119-6b2b3fa70ba6', 'lakshmikanthan', 'https://lakshmikanthan.s3.ap-south-1.amazonaws.com/face-collection/d9e65f36-a752-402e-8119-6b2b3fa70ba6.jpg', 'Y', '2022-03-01 08:11:35', 1),
(3, '05f0a0b5-96af-4187-941d-9243752ffd5f', 'Syed', 'https://lakshmikanthan.s3.ap-south-1.amazonaws.com/face-collection/05f0a0b5-96af-4187-941d-9243752ffd5f.jpg', 'Y', '2022-03-01 10:05:08', 1),
(4, 'f8f47264-70b9-4bc8-9b2f-4fcef26ffba4', 'syed', 'https://lakshmikanthan.s3.ap-south-1.amazonaws.com/face-collection/f8f47264-70b9-4bc8-9b2f-4fcef26ffba4.jpg', 'Y', '2022-03-01 10:06:19', 1),
(5, '4142b670-b1c6-45c5-89e4-548ac98801fc', 'Syed', 'https://lakshmikanthan.s3.ap-south-1.amazonaws.com/face-collection/4142b670-b1c6-45c5-89e4-548ac98801fc.jpg', 'Y', '2022-03-01 13:34:56', 1),
(6, '3227563f-3f1f-4fc2-bc61-43c57cbe22cd', 'Rajesh', 'https://lakshmikanthan.s3.ap-south-1.amazonaws.com/face-collection/3227563f-3f1f-4fc2-bc61-43c57cbe22cd.jpg', 'Y', '2022-03-01 13:39:39', 1),
(7, '2ac3f4b9-e838-400a-ae6c-74147d670948', 'Lakshmi kanthan', 'https://lakshmikanthan.s3.ap-south-1.amazonaws.com/face-collection/2ac3f4b9-e838-400a-ae6c-74147d670948.jpg', 'Y', '2022-03-02 05:28:58', 1),
(8, '65687cc6-ec60-41de-9c02-90c1e025d43a', 'Asar', 'https://lakshmikanthan.s3.ap-south-1.amazonaws.com/face-collection/65687cc6-ec60-41de-9c02-90c1e025d43a.jpg', 'Y', '2022-03-02 05:30:24', 1),
(9, '6f06e433-a2f4-40b5-8cea-3d641af7e21f', 'Arun', 'https://lakshmikanthan.s3.ap-south-1.amazonaws.com/face-collection/6f06e433-a2f4-40b5-8cea-3d641af7e21f.jpg', 'Y', '2022-03-02 05:33:18', 1),
(10, 'aeaae46d-b32f-4aab-827e-db8c2500a053', 'Asar', 'https://lakshmikanthan.s3.ap-south-1.amazonaws.com/face-collection/aeaae46d-b32f-4aab-827e-db8c2500a053.jpg', 'Y', '2022-03-02 05:36:10', 1),
(11, '80ec930a-4d47-485c-a850-ec37914936c9', 'Lakshmi kanthan', 'https://lakshmikanthan.s3.ap-south-1.amazonaws.com/face-collection/80ec930a-4d47-485c-a850-ec37914936c9.jpg', 'Y', '2022-03-02 05:38:40', 1),
(12, 'd8873202-a5a9-4d9c-8e84-8e2ba84cb824', 'Narasimha', 'https://lakshmikanthan.s3.ap-south-1.amazonaws.com/face-collection/d8873202-a5a9-4d9c-8e84-8e2ba84cb824.jpg', 'Y', '2022-03-02 07:46:42', 1),
(13, 'aeaae46d-b32f-4aab-827e-db8c2500a053', 'Asar', 'https://lakshmikanthan.s3.ap-south-1.amazonaws.com/face-collection/aeaae46d-b32f-4aab-827e-db8c2500a053.jpg', 'Y', '2022-03-03 05:34:05', 1),
(15, 'f81a411e-d7e3-4e4e-9e61-f8c3ae635e9f', 'lakshmikanthan', 'https://lakshmikanthan.s3.ap-south-1.amazonaws.com/face-collection/f81a411e-d7e3-4e4e-9e61-f8c3ae635e9f.jpg', 'Y', '2022-03-03 05:36:04', 1),
(17, '16746999-4e3c-4287-a5d1-f5fdf234cea3', 'GA', 'https://lakshmikanthan.s3.ap-south-1.amazonaws.com/face-collection/16746999-4e3c-4287-a5d1-f5fdf234cea3.jpg', 'Y', '2022-03-03 05:40:25', 1),
(18, 'f81a411e-d7e3-4e4e-9e61-f8c3ae635e9f', 'lakshmikanthan', 'https://lakshmikanthan.s3.ap-south-1.amazonaws.com/face-collection/f81a411e-d7e3-4e4e-9e61-f8c3ae635e9f.jpg', 'Y', '2022-03-05 05:13:12', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `user_daily_attendance`
--
ALTER TABLE `user_daily_attendance`
  ADD PRIMARY KEY (`attendance_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `user_daily_attendance`
--
ALTER TABLE `user_daily_attendance`
  MODIFY `attendance_id` int NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=19;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

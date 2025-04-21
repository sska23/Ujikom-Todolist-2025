-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 30, 2025 at 01:22 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `todolist`
--

-- --------------------------------------------------------

--
-- Table structure for table `subtasks`
--

CREATE TABLE `subtasks` (
  `subtask_id` int NOT NULL,
  `task_id` int NOT NULL,
  `subtask_label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `priority` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `deadline` date NOT NULL,
  `status` enum('open','close') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'open'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subtasks`
--

INSERT INTO `subtasks` (`subtask_id`, `task_id`, `subtask_label`, `priority`, `deadline`, `status`) VALUES
(60, 121, 'main ml', 'rendah', '2025-03-26', 'open'),
(61, 121, 'main ke rumah erni', 'tinggi', '2025-03-26', 'close'),
(64, 137, 'miya', 'rendah', '2025-03-24', 'open'),
(65, 137, 'layla', 'rendah', '2025-03-24', 'open'),
(66, 143, 'Gitar listrik', 'rendah', '2025-04-16', 'close'),
(67, 143, 'Gitar Akustik', 'tinggi', '2025-04-17', 'open'),
(68, 143, 'Ukulele', 'rendah', '2025-04-18', 'close');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `task_id` int NOT NULL,
  `user_id` int NOT NULL,
  `task_label` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `task_status` enum('open','close') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `priority` enum('rendah','tinggi') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'rendah',
  `deadline` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`task_id`, `user_id`, `task_label`, `task_status`, `create_date`, `priority`, `deadline`) VALUES
(121, 27, 'Jadwal hari senin', 'open', '2025-03-23 00:24:33', 'rendah', '2025-03-28 00:00:00'),
(124, 34, 'main badminton', 'open', '2025-03-29 00:41:16', 'rendah', '2025-03-27 00:00:00'),
(125, 35, 'Jadwal hari senin', 'open', '2025-03-29 00:43:17', 'rendah', '2025-03-31 00:00:00'),
(137, 40, 'main ml', 'open', '2025-03-29 07:48:12', 'rendah', '2025-03-29 00:00:00'),
(143, 1, 'Gitaran', 'open', '2025-03-29 23:21:59', 'tinggi', '2025-04-08 00:00:00'),
(149, 42, 'Jadwal hari senin', 'open', '2025-03-30 01:19:03', 'rendah', '2025-04-04 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `task_history`
--

CREATE TABLE `task_history` (
  `history_id` int NOT NULL,
  `task_id` int NOT NULL,
  `task_label` varchar(255) DEFAULT NULL,
  `priority` enum('rendah','tinggi') DEFAULT NULL,
  `deadline` date DEFAULT NULL,
  `user_id` int NOT NULL,
  `completed_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `task_history`
--

INSERT INTO `task_history` (`history_id`, `task_id`, `task_label`, `priority`, `deadline`, `user_id`, `completed_at`) VALUES
(7, 55, 'Jalan jalan', 'tinggi', '2025-02-19', 1, '2025-03-04 15:21:39'),
(10, 61, 'Liburan', 'tinggi', '2025-02-22', 1, '2025-03-04 15:21:39'),
(11, 56, 'main', 'rendah', '2025-02-21', 1, '2025-03-04 15:21:39'),
(19, 76, 'Jadwal hari rabu', 'tinggi', '2025-02-25', 1, '2025-03-04 15:21:39'),
(29, 88, 'Main', 'tinggi', '2025-02-17', 6, '2025-03-04 15:21:39'),
(30, 87, 'Jalan jalan', 'rendah', '2025-02-24', 6, '2025-03-04 15:21:39'),
(31, 92, 'Jadwal Pelajaran', 'rendah', '2025-02-23', 1, '2025-03-04 15:21:39'),
(32, 90, 'Jadwal hari minggu', 'tinggi', '2025-02-23', 1, '2025-03-04 15:21:39'),
(33, 91, 'Jadwal Belajar', 'rendah', '2025-02-25', 1, '2025-03-04 15:21:39'),
(34, 89, 'Produktif', 'tinggi', '2025-02-25', 1, '2025-03-04 15:21:39'),
(35, 93, 'Jadwal senin', 'rendah', '2025-02-25', 1, '2025-03-04 15:21:39'),
(36, 94, 'Jadwal Main', 'rendah', '2025-02-24', 1, '2025-03-04 15:21:39'),
(37, 95, 'jadwal', 'rendah', '2025-02-25', 1, '2025-03-04 15:21:39'),
(61, 120, 'main badminton', 'rendah', '2025-03-29', 27, '2025-03-23 00:25:07'),
(62, 122, 'ngopi ke kafe', 'tinggi', '2025-03-28', 1, '2025-03-23 00:25:45'),
(82, 148, 'mabar', 'rendah', '2025-03-25', 1, '2025-03-30 01:15:52'),
(83, 150, 'Gitaran', 'tinggi', '2025-04-08', 42, '2025-03-30 01:19:43');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`) VALUES
(1, 'siska', 'siska@gmail.com', '$2y$10$tjQHtok6RaZ3lCS0MnJd1e9WuFp751cJiPo/eyyX2tOW0rT4xN/76'),
(6, 'devia', 'devia3@gmail.com', '$2y$10$I.h5U3rA3Iv53NePnZlS..ceGViZPeJgAAQmA7Wn/23bU38aAroEG'),
(13, 'sahla', 'sahla@gmail.com', '$2y$10$C.OEI.r1LoMer1IQn6KhNeh.G1CqK5HLX978WwH7aHPIGNnWrCyJ.'),
(16, 'salmaa', 'salmaaa@gmail.com', '$2y$10$SakX//WiZnQUNgeJF5KQkOhdqx7VELPb1TT7defK0x6/KVmiuf7oq'),
(19, 'reisya', 'rei@gmail.com', '$2y$10$bTrunKskqOTGJzwpCwYjY.ap0VxO3xoVNd.wOI1kCzLAxJfR2wIBK'),
(27, 'miya', 'miya@gmail.com', '$2y$10$dFCiR3e8zvvd7i8nSd9q.eAN4kBLBN2UZd2PCAxFZWl3EVY3Dxa6K'),
(30, 'riri', 'siska@gmail.com', '$2y$10$FZulffeCf8kQ/ye68nKVXOs7l2.dglpeMOcyBKDN8/v/hoZRr.sDO'),
(34, 'kalula', 'kalula@gmail.com', '$2y$10$YaRTQr14mBXi4WFp7ZD8huJBHusKIX9OP3jJ0W46DhHdhFBiyWrw6'),
(35, 'kayla', 'kay@gmail.com', '$2y$10$ubrbh4jtNn/v65kmohgwKO8mgxgDKDt4dZmuhr9etPNeDx2s.DOHu'),
(36, 'matias', 'matias@gmail.com', '$2y$10$C/27n.9kpr7btK.vQ2zgtOgysEt7AgxfSb3RWB.eN0EBfUDBmqHMu'),
(37, 'kyle', 'kyle@gmail.com', '$2y$10$LB7qxrB3ZKcCg.NRnA3MWex750IxpKXAbft42LK/T5TPEwd38Mx3y'),
(39, 'rami', 'rami@gmail.com', '$2y$10$qFfS20wrBz0lzQgat6C3x.w01tBMqSRTZfZnf1yDQ5LwRlxiRQdDy'),
(40, 'safa', 'saf@gmail.com', '$2y$10$i7HvkRQ2WyrLiZETv31zXuTS.4svAZMaYqvGb7Bqayb7RwhGTaX8C'),
(41, 'mpi', 'mpi@gmail.com', '$2y$10$kLuTOs2Naakkc5IVSrj/7u6PFa1os0QADvqfyaDcBwuxB01O8iCTG'),
(42, 'mawar', 'mawar@gmail.com', '$2y$10$Hk8I7k.tEgWkjJv9fIH5yuCipa3VYDjcV8jCdU1MTfjLjOGtlubJ2');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `subtasks`
--
ALTER TABLE `subtasks`
  ADD PRIMARY KEY (`subtask_id`),
  ADD KEY `task_id` (`task_id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`task_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `task_history`
--
ALTER TABLE `task_history`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `fk_user_history` (`user_id`),
  ADD KEY `fk_task` (`task_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `subtasks`
--
ALTER TABLE `subtasks`
  MODIFY `subtask_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `task_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=151;

--
-- AUTO_INCREMENT for table `task_history`
--
ALTER TABLE `task_history`
  MODIFY `history_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `subtasks`
--
ALTER TABLE `subtasks`
  ADD CONSTRAINT `fk_sub_task` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`task_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `subtasks_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`task_id`) ON DELETE CASCADE;

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `task_history`
--
ALTER TABLE `task_history`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 22, 2025 at 11:08 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `job_allocation`
--

-- --------------------------------------------------------

--
-- Table structure for table `deans`
--

CREATE TABLE `deans` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `active_mode` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `deans`
--

INSERT INTO `deans` (`id`, `name`, `active_mode`) VALUES
(4, 'yogesh', 0);

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`) VALUES
(1, 'Aeronautical Engineering'),
(2, 'Biomedical Engineering'),
(3, 'Civil Engineering'),
(4, 'Computer Science & Engineering'),
(5, 'Computer Science & Engineering (Cyber Security)'),
(6, 'Computer Science & Engineering (Artificial Intelligence and Machine Learning)'),
(7, 'Electronics and Communication Engineering'),
(8, 'Electrical and Electronics Engineering'),
(9, 'Mechanical Engineering'),
(10, 'Bio-Technology'),
(11, 'Chemical Engineering'),
(12, 'Information Technology'),
(13, 'AI and Data Science'),
(14, 'Computer Science and Business Systems'),
(15, 'Architecture'),
(16, 'Communication System'),
(17, 'Computer Science Engineering'),
(18, 'Power System Engineering'),
(19, 'Engineering Design'),
(20, 'Structural Engineering'),
(21, 'MBA'),
(22, 'MCA'),
(23, 'B.SC');

-- --------------------------------------------------------

--
-- Table structure for table `faculties`
--

CREATE TABLE `faculties` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `department` varchar(100) NOT NULL,
  `active_mode` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculties`
--

INSERT INTO `faculties` (`id`, `name`, `department`, `active_mode`) VALUES
(6, 'swamydass', 'MCA', 0),
(7, 'bhavan', 'MCA', 0),
(8, 'anandh', 'Communication System', 0),
(9, 'jawhar', 'Biomedical Engineering', 0),
(10, 'rajeshwari', 'Civil Engineering', 0),
(11, 'mohan', 'MBA', 0),
(12, 'harish', 'Computer Science & Engineering', 0);

-- --------------------------------------------------------

--
-- Table structure for table `recipients`
--

CREATE TABLE `recipients` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recipients`
--

INSERT INTO `recipients` (`id`, `name`) VALUES
(1, 'Dean'),
(2, 'Department');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `priority` enum('Low','Medium','High') DEFAULT 'Medium',
  `due_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `soft_deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `title`, `description`, `priority`, `due_date`, `created_at`, `soft_deleted`) VALUES
(1, 'fill in the blank', '1234', 'Medium', '2025-07-23', '2025-07-20 17:43:59', 0),
(3, 'task1', 'abcd', 'Low', '2025-07-31', '2025-07-21 10:15:18', 0),
(4, 'task2', 'bgc', 'High', '2025-07-29', '2025-07-21 10:28:08', 0),
(5, 'dummy task', 'this is dummy task ', 'Medium', '2025-07-29', '2025-07-21 14:10:31', 0),
(6, 'my task my my ruyles ', 'lokhjgfb', 'Medium', '2025-07-23', '2025-07-22 08:22:51', 0);

-- --------------------------------------------------------

--
-- Table structure for table `task_attachments`
--

CREATE TABLE `task_attachments` (
  `id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `uploaded_by` varchar(100) DEFAULT 'principal',
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `task_attachments`
--

INSERT INTO `task_attachments` (`id`, `task_id`, `file_path`, `uploaded_by`, `uploaded_at`) VALUES
(1, 1, 'uploads/tasks/1753033439_ABOUT THE COMPANY.docx', 'principal', '2025-07-20 17:43:59'),
(5, 4, 'uploads/tasks/1753093688_activity9.pdf', 'principal', '2025-07-21 10:28:08'),
(6, 5, 'uploads/tasks/1753107031_dbms 6.docx', 'principal', '2025-07-21 14:10:31'),
(7, 6, 'uploads/tasks/1753172571_deepdetect.docx', 'principal', '2025-07-22 08:22:51');

-- --------------------------------------------------------

--
-- Table structure for table `task_recipients`
--

CREATE TABLE `task_recipients` (
  `id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `recipient_name` varchar(255) NOT NULL,
  `recipient_type` varchar(100) DEFAULT 'Faculty',
  `status` enum('Pending','In Progress','Completed','Overdue') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `task_recipients`
--

INSERT INTO `task_recipients` (`id`, `task_id`, `recipient_name`, `recipient_type`, `status`, `created_at`) VALUES
(1, 1, 'jawhar (Biomedical Engineering)', 'Faculty', 'In Progress', '2025-07-20 17:43:59'),
(4, 3, 'anandh (Aeronautical Engineering)', 'Faculty', 'Pending', '2025-07-21 10:16:07'),
(5, 4, 'swamydass (MCA)', 'Faculty', 'Pending', '2025-07-21 10:28:08'),
(6, 5, 'yogesh (Dean)', 'Dean', 'Pending', '2025-07-21 14:10:31'),
(7, 6, 'jawhar (Biomedical Engineering)', 'Faculty', 'In Progress', '2025-07-22 08:22:51');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','principal','dean','hod','staff') NOT NULL,
  `active_mode` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `active_mode`) VALUES
(1, 'DR.R.Radhakrishnan', 'principal@gmail.com', '#123!e10adc3949ba59abbe56e057f20f883e@#$%)(jjdxbh', 'principal', 0),
(3, 'admin', 'admin@gmail.com', '#123!827ccb0eea8a706c4c34a16891f84e7b@#$%)(jjdxbh', 'admin', 0),
(6, 'bhavan', 'bhavan@gmail.com', '#123!827ccb0eea8a706c4c34a16891f84e7b@#$%)(jjdxbh', 'staff', 0),
(8, 'kathir', 'kathir@gmail.com', '#123!827ccb0eea8a706c4c34a16891f84e7b@#$%)(jjdxbh', 'dean', 0),
(9, 'jawhar', 'jawhar@gmail.com', '#123!827ccb0eea8a706c4c34a16891f84e7b@#$%)(jjdxbh', 'staff', 0),
(10, 'rajeshwari', 'rajesh@gmail.com', '#123!827ccb0eea8a706c4c34a16891f84e7b@#$%)(jjdxbh', 'staff', 0),
(11, 'yogesh', 'yogesh@gmail.com', '#123!827ccb0eea8a706c4c34a16891f84e7b@#$%)(jjdxbh', 'dean', 0),
(13, 'mohan', 'mohan@gmail.com', '#123!827ccb0eea8a706c4c34a16891f84e7b@#$%)(jjdxbh', 'staff', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `deans`
--
ALTER TABLE `deans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `faculties`
--
ALTER TABLE `faculties`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `recipients`
--
ALTER TABLE `recipients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `task_attachments`
--
ALTER TABLE `task_attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_id` (`task_id`);

--
-- Indexes for table `task_recipients`
--
ALTER TABLE `task_recipients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_id` (`task_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `deans`
--
ALTER TABLE `deans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `faculties`
--
ALTER TABLE `faculties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `recipients`
--
ALTER TABLE `recipients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `task_attachments`
--
ALTER TABLE `task_attachments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `task_recipients`
--
ALTER TABLE `task_recipients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `task_attachments`
--
ALTER TABLE `task_attachments`
  ADD CONSTRAINT `task_attachments_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `task_recipients`
--
ALTER TABLE `task_recipients`
  ADD CONSTRAINT `task_recipients_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

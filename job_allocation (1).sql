-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 31, 2025 at 01:15 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

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
(23, 'B.SC'),
(24, 'MSC'),
(25, 'bca(computer)'),
(26, 'commerce');

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
(12, 'harish', 'Computer Science & Engineering', 0),
(13, 'naveen', 'Architecture', 0),
(14, 'harisht', 'MBA', 0),
(15, 'kiran', 'Computer Science & Engineering', 0),
(16, 'kiran', 'Computer Science & Engineering', 0),
(17, 'kiran', 'Computer Science & Engineering', 0),
(18, 'dhanush', 'Structural Engineering', 0);

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `name`) VALUES
(10, 'dhanush'),
(7, 'heads'),
(9, 'staffs');

-- --------------------------------------------------------

--
-- Table structure for table `group_members`
--

CREATE TABLE `group_members` (
  `id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `group_members`
--

INSERT INTO `group_members` (`id`, `group_id`, `user_id`, `added_at`) VALUES
(4, 7, 6, '2025-07-24 06:34:53'),
(5, 7, 11, '2025-07-24 06:34:53'),
(6, 7, 9, '2025-07-24 06:34:53'),
(10, 9, 6, '2025-07-24 16:53:28'),
(11, 10, 21, '2025-07-25 09:12:54'),
(12, 10, 6, '2025-07-25 09:12:54');

-- --------------------------------------------------------

--
-- Table structure for table `otp_verifications`
--

CREATE TABLE `otp_verifications` (
  `id` int(11) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `otp` varchar(10) DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(2, 'Department'),
(4, 'Groups');

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
  `soft_deleted` tinyint(1) DEFAULT 0,
  `comment` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `title`, `description`, `priority`, `due_date`, `created_at`, `soft_deleted`, `comment`) VALUES
(1, 'fill in the blank', '1234', 'Medium', '2025-07-21', '2025-07-20 17:43:59', 0, NULL),
(3, 'task1', 'abcd', 'Low', '2025-07-31', '2025-07-21 10:15:18', 0, NULL),
(4, 'task2', 'bgc', 'High', '2025-07-29', '2025-07-21 10:28:08', 0, NULL),
(5, 'dummy task', 'this is dummy task ', 'Medium', '2025-07-29', '2025-07-21 14:10:31', 0, NULL),
(7, 'finish ', '1234 finish', 'Medium', '2025-08-01', '2025-07-23 09:45:11', 0, NULL),
(8, 'test task', 'description', 'Medium', '2025-07-24', '2025-07-23 10:44:48', 0, NULL),
(9, 'correct ui', 'bhvn', 'Medium', '2025-08-09', '2025-07-23 15:20:09', 0, 'I am not satisfied with your submission. Please resubmit.'),
(10, 'finish all works ', 'this is used to complete the tasks ', 'Medium', '2025-07-31', '2025-07-24 08:07:58', 0, 'I am not satisfied with your submission. Please resubmit.'),
(11, 'tsk 26', 'tsk tsku', 'Medium', '2025-07-25', '2025-07-24 10:58:46', 0, NULL),
(12, 'structure', 'add', 'Medium', '2025-07-30', '2025-07-25 09:14:30', 0, NULL),
(13, 'test task1', 'description', 'Medium', '2025-07-30', '2025-07-29 03:23:09', 0, 'I am not satisfied with your submission. Please resubmit.'),
(14, 'task1234', '1234', 'Medium', '2025-08-01', '2025-07-29 03:55:10', 0, 'I am not satisfied with your submission. Please resubmit.'),
(15, 'task for test ', 'ujyhfg', 'High', '2025-08-08', '2025-07-29 05:53:23', 0, NULL),
(18, 'mail', 'mail', 'Medium', '2025-08-01', '2025-07-29 06:36:04', 0, 'I am not satisfied with your submission. Please resubmit.'),
(20, 'test for mail', 'testing', 'High', '2025-07-31', '2025-07-29 06:37:29', 0, NULL),
(21, 'final task 1', 'finish properly ', 'High', '2025-07-31', '2025-07-29 10:18:04', 0, NULL),
(22, 'test task', '6787', 'Medium', '2025-07-31', '2025-07-29 10:59:19', 0, NULL),
(23, 'test task for mail', 'mail', 'Medium', '2025-08-05', '2025-07-31 10:45:42', 0, NULL),
(24, 'Task Bhavan 18', '18', 'High', '2025-08-04', '2025-07-31 10:57:32', 0, NULL);

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
(9, 7, 'uploads/tasks/1753263911_job_allocation (4).sql', 'principal', '2025-07-23 09:45:11'),
(10, 7, 'uploads/tasks/1753263911_nextpage.png', 'principal', '2025-07-23 09:45:11'),
(11, 7, 'uploads/tasks/1753263911_stafpage.png', 'principal', '2025-07-23 09:45:11'),
(12, 7, 'uploads/tasks/1753264010_stafpage.png', 'staff', '2025-07-23 09:46:50'),
(13, 8, 'uploads/tasks/1753267488_LIT_Internship_JD.docx (1).pdf', 'principal', '2025-07-23 10:44:48'),
(14, 8, 'uploads/tasks/1753267922_job_allocation (4).sql', 'staff', '2025-07-23 10:52:02'),
(15, 9, 'uploads/tasks/1753284009_file.docx', 'principal', '2025-07-23 15:20:09'),
(16, 9, 'uploads/tasks/1753284530_importnt.txt', 'staff', '2025-07-23 15:28:50'),
(17, 10, 'uploads/tasks/1753344478_WhatsApp Image 2025-07-21 at 20.11.56_b1944261.jpg', 'principal', '2025-07-24 08:07:58'),
(18, 10, 'uploads/tasks/1753344478_file.docx', 'principal', '2025-07-24 08:07:58'),
(19, 11, 'uploads/tasks/1753354726_Screenshot (157).png', 'principal', '2025-07-24 10:58:46'),
(20, 12, 'uploads/tasks/1753434870_Untitled diagram _ Mermaid Chart-2025-07-25-064440.png', 'principal', '2025-07-25 09:14:30'),
(21, 13, 'uploads/tasks/1753759389_Untitled diagram _ Mermaid Chart-2025-07-25-064440.png', 'principal', '2025-07-29 03:23:09'),
(22, 14, 'uploads/tasks/1753761310_Untitled diagram _ Mermaid Chart-2025-07-25-062251.png', 'principal', '2025-07-29 03:55:10'),
(23, 15, 'uploads/tasks/1753768403_1000019636.jpg', 'principal', '2025-07-29 05:53:23'),
(25, 20, 'uploads/tasks/1753771049_1000019636.jpg', 'principal', '2025-07-29 06:37:29'),
(26, 21, 'uploads/tasks/1753784284_Maths_Written_Notes_rotated.pdf', 'principal', '2025-07-29 10:18:04'),
(27, 21, 'uploads/tasks/1753784284_bigdata_error_script.ipynb', 'principal', '2025-07-29 10:18:04'),
(28, 22, 'uploads/tasks/1753786759_Untitled diagram _ Mermaid Chart-2025-07-25-064440.png', 'principal', '2025-07-29 10:59:19'),
(29, 23, 'uploads/tasks/1753958742_1000019636.jpg', 'principal', '2025-07-31 10:45:42');

-- --------------------------------------------------------

--
-- Table structure for table `task_recipients`
--

CREATE TABLE `task_recipients` (
  `id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `recipient_name` varchar(255) NOT NULL,
  `recipient_type` varchar(100) DEFAULT 'Faculty',
  `status` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `task_recipients`
--

INSERT INTO `task_recipients` (`id`, `task_id`, `recipient_name`, `recipient_type`, `status`, `created_at`) VALUES
(4, 3, 'anandh (Aeronautical Engineering)', 'Faculty', 'Pending', '2025-07-21 10:16:07'),
(5, 4, 'swamydass (MCA)', 'Faculty', 'overdue', '2025-07-21 10:28:08'),
(6, 5, 'yogesh (Dean)', 'Dean', 'Completed', '2025-07-21 14:10:31'),
(8, 1, 'jawhar (Biomedical Engineering)', 'Faculty', 'Overdue', '2025-07-22 10:51:03'),
(9, 7, 'jawhar (Biomedical Engineering)', 'Faculty', 'Pending', '2025-07-23 09:45:11'),
(10, 8, 'rajeshwari (Civil Engineering)', 'Faculty', 'Completed', '2025-07-23 10:44:48'),
(12, 9, 'bhavan (MCA)', 'Faculty', 'In Progress', '2025-07-23 18:07:50'),
(13, 10, 'harisht (MBA)', 'Faculty', 'In Progress', '2025-07-24 08:07:58'),
(14, 11, 'bhavan (MCA)', 'Faculty', 'Completed', '2025-07-24 10:58:46'),
(15, 12, 'dhanush (Structural Engineering)', 'Faculty', 'overdue', '2025-07-25 09:14:30'),
(16, 13, 'rajeshwari (Civil Engineering)', 'Faculty', 'Completed', '2025-07-29 03:23:09'),
(17, 14, 'rajeshwari (Civil Engineering)', 'Faculty', 'Waiting for Principal Action', '2025-07-29 03:55:10'),
(18, 15, 'harisht (MBA)', 'Faculty', 'Pending', '2025-07-29 05:53:23'),
(21, 18, 'bhavan (MCA)', 'Faculty', 'Completed', '2025-07-29 06:36:04'),
(23, 20, 'harisht (MBA)', 'Faculty', 'Pending', '2025-07-29 06:37:29'),
(25, 21, 'bhavan (MCA)', 'Faculty', 'Completed', '2025-07-29 10:19:53'),
(26, 22, 'bhavan (MCA)', 'Faculty', 'Pending', '2025-07-29 10:59:19'),
(27, 23, 'bhavan (MCA)', 'Faculty', 'Completed', '2025-07-31 10:45:42'),
(28, 24, 'bhavan (MCA)', 'Faculty', 'Completed', '2025-07-31 10:57:32');

-- --------------------------------------------------------

--
-- Table structure for table `task_submissions`
--

CREATE TABLE `task_submissions` (
  `id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `recipient_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `submitted_by` varchar(100) DEFAULT 'staff',
  `submitted_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `task_submissions`
--

INSERT INTO `task_submissions` (`id`, `task_id`, `recipient_id`, `description`, `file_path`, `submitted_by`, `submitted_at`) VALUES
(1, 6, 7, 'i finished my work ', 'uploads/tasks/1753263181_stafpage.png', 'staff', '2025-07-23 15:03:01'),
(2, 7, 9, '12345', 'uploads/tasks/1753264010_stafpage.png', 'staff', '2025-07-23 15:16:50'),
(3, 8, 10, 'work completed ', 'uploads/tasks/1753267922_job_allocation (4).sql', 'staff', '2025-07-23 16:22:02'),
(20, 5, 6, 'i am done here i ttched ', 'uploads/tasks/1753293124_dashboard.png,uploads/tasks/1753293124_Screenshot 2025-07-17 094713.png', 'staff', '2025-07-23 23:22:04'),
(21, 9, 11, 'i bhavan@gmail.combhavan@gmail.combhavan@gmail.comm', 'uploads/tasks/1753293988_nextpage.png', 'staff', '2025-07-23 23:36:28'),
(23, 10, 13, 'i finished your work', 'uploads/tasks/1753344567_BLOCKCHAIN CLOUD DATA SECURITY -FIRST REVIEW.docx', 'staff', '2025-07-24 13:39:27'),
(24, 11, 14, 'finished', 'uploads/tasks/1753357836_Screenshot (163).png', 'staff', '2025-07-24 17:20:36'),
(25, 9, 12, 'hello', 'uploads/tasks/1753367239_Screenshot (202).png', 'staff', '2025-07-24 19:57:19'),
(27, 13, 16, 'completed', 'uploads/tasks/1753759685_Untitled diagram _ Mermaid Chart-2025-07-25-062251.png', 'staff', '2025-07-29 08:58:05'),
(29, 14, 17, 'completed', 'uploads/tasks/1753761702_Untitled diagram _ Mermaid Chart-2025-07-25-062251.png', 'staff', '2025-07-29 09:31:42'),
(33, 18, 21, 'this task i finally finished ', '', 'staff', '2025-07-29 13:09:53'),
(34, 21, 25, 'finished ', 'uploads/tasks/1753784483_Untitled diagram _ Mermaid Chart-2025-07-25-061641.png', 'staff', '2025-07-29 15:51:23'),
(35, 23, 27, 'finished', 'uploads/tasks/1753958818_TaskReports.pdf', 'staff', '2025-07-31 16:16:58'),
(36, 24, 28, 'Task Completed bHAVN', '', 'staff', '2025-07-31 16:28:41');

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
(1, 'DR.R.Radhakrishnan', 'sahana.mca2024@adhiyamaan.in', '#123!827ccb0eea8a706c4c34a16891f84e7b@#$%)(jjdxbh', 'principal', 0),
(3, 'admin', 'admin@gmail.com', '#123!827ccb0eea8a706c4c34a16891f84e7b@#$%)(jjdxbh', 'admin', 0),
(6, 'bhavan', 'bhavan.mca2024@adhiyamaan.in', '#123!ad846fd1138e66a1cacd0fb4b8644671@#$%)(jjdxbh', 'staff', 0),
(9, 'jawhar', 'jawhar123@gmail.com', '#123!827ccb0eea8a706c4c34a16891f84e7b@#$%)(jjdxbh', 'staff', 0),
(10, 'rajeshwari', 'rajesh@gmail.com', '#123!827ccb0eea8a706c4c34a16891f84e7b@#$%)(jjdxbh', 'staff', 0),
(11, 'yogesh', 'yogesh@gmail.com', '#123!827ccb0eea8a706c4c34a16891f84e7b@#$%)(jjdxbh', 'dean', 0),
(13, 'mohan', 'mohn@gmail.com', '#123!827ccb0eea8a706c4c34a16891f84e7b@#$%)(jjdxbh', 'staff', 0),
(16, 'naveen', 'naveen@gmail.com', '#123!827ccb0eea8a706c4c34a16891f84e7b@#$%)(jjdxbh', 'staff', 0),
(17, 'harisht', 'harish.t.mca2024@adhiyamaan.in', '#123!827ccb0eea8a706c4c34a16891f84e7b@#$%)(jjdxbh', 'staff', 0),
(20, 'kiran', 'kiran@gmail.com', '#123!827ccb0eea8a706c4c34a16891f84e7b@#$%)(jjdxbh', 'staff', 0),
(21, 'dhanush', 'dha@gmail.com', '#123!827ccb0eea8a706c4c34a16891f84e7b@#$%)(jjdxbh', 'staff', 0);

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
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `group_members`
--
ALTER TABLE `group_members`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `group_id` (`group_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `otp_verifications`
--
ALTER TABLE `otp_verifications`
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
-- Indexes for table `task_submissions`
--
ALTER TABLE `task_submissions`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `faculties`
--
ALTER TABLE `faculties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `group_members`
--
ALTER TABLE `group_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `otp_verifications`
--
ALTER TABLE `otp_verifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `recipients`
--
ALTER TABLE `recipients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `task_attachments`
--
ALTER TABLE `task_attachments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `task_recipients`
--
ALTER TABLE `task_recipients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `task_submissions`
--
ALTER TABLE `task_submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `group_members`
--
ALTER TABLE `group_members`
  ADD CONSTRAINT `group_members_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `group_members_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

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

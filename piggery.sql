-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 03, 2023 at 11:10 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `piggery`
--

-- --------------------------------------------------------

--
-- Table structure for table `breeds`
--

CREATE TABLE `breeds` (
  `breed_id` int(11) NOT NULL,
  `breed_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `breeds`
--

INSERT INTO `breeds` (`breed_id`, `breed_name`) VALUES
(1, 'Large White'),
(2, 'Landrace'),
(3, 'Duroc'),
(4, 'Yorkshire'),
(5, 'Berkshire'),
(6, 'Hampshire'),
(7, 'Native Pig'),
(8, 'Pie Train'),
(9, 'Crossbreed');

-- --------------------------------------------------------

--
-- Table structure for table `certifications`
--

CREATE TABLE `certifications` (
  `certification_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `certification_name` varchar(255) NOT NULL,
  `certification_description` longtext NOT NULL,
  `date_acquired` date NOT NULL,
  `certification_path` longtext NOT NULL,
  `date_added` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `certifications`
--

INSERT INTO `certifications` (`certification_id`, `user_id`, `certification_name`, `certification_description`, `date_acquired`, `certification_path`, `date_added`) VALUES
(1, 181, 'NC II', 'This is NC II', '2021-11-10', '0-creative-birthday-cards.png', '2021-11-12'),
(2, 181, 'qeqwe', 'qweqwe', '2021-11-06', '191132522_310121903930149_4001911070761848587_n.jpg', '2021-11-09'),
(3, 181, 'qw', 'eqwe', '2021-11-03', '191740307_333431654959134_5254781040418540567_n.jpg', '2021-11-09');

-- --------------------------------------------------------

--
-- Table structure for table `feeding_guides`
--

CREATE TABLE `feeding_guides` (
  `guide_id` int(11) NOT NULL,
  `guide_name` varchar(255) NOT NULL,
  `guide` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `feeding_guides`
--

INSERT INTO `feeding_guides` (`guide_id`, `guide_name`, `guide`) VALUES
(1, 'FOR  PUREBREED  AND MIX BREED FATTENERS', '<p>\r\n    Phase 1: Day 0 - Day 21\r\n</p>\r\n<p>\r\n    Morning Feeding (6:00 - 7:00 am)\r\n</p>\r\n<ul type=\"disc\">\r\n    <li>\r\n        Feed       Type: Pre-Starter Feed\r\n    </li>\r\n    <li>\r\n        Daily       Feed Amount: 0.5 kg/pig\r\n    </li>\r\n    <li>\r\n        Nutritional       Content: 20% protein, 4% fat, 12% fiber\r\n    </li>\r\n    <li>\r\n        Water       Intake: Provide fresh water ad libitum (free access)\r\n    </li>\r\n</ul>\r\n<p>\r\n    Afternoon Feeding (4:30 - 5:00 pm)\r\n</p>\r\n<ul type=\"disc\">\r\n    <li>\r\n        Feed       Type: Pre-Starter Feed\r\n    </li>\r\n    <li>\r\n        Daily       Feed Amount: 0.5 kg/pig\r\n    </li>\r\n    <li>\r\n        Nutritional       Content: 20% protein, 4% fat, 12% fiber\r\n    </li>\r\n    <li>\r\n        Water       Intake: Provide fresh water ad libitum (free access)\r\n    </li>\r\n</ul>\r\n<p>\r\n    Reminders:\r\n</p>\r\n<ul type=\"disc\">\r\n    <li>\r\n        Ensure       clean and dry feeding areas.\r\n    </li>\r\n    <li>\r\n        Monitor       pig health and behavior.\r\n    </li>\r\n    <li>\r\n        Check       water supply regularly.\r\n    </li>\r\n</ul>\r\n<p>\r\n    Advice:\r\n</p>\r\n<ul type=\"disc\">\r\n    <li>\r\n        Weigh       your pigs at least once a week to track their growth.\r\n    </li>\r\n    <li>\r\n        Observe       for any signs of illness and seek veterinary care if\r\n        necessary.\r\n    </li>\r\n    <li>\r\n        Maintain       a consistent feeding schedule.\r\n    </li>\r\n</ul>\r\n<p>\r\n    Remarks:\r\n</p>\r\n<ul type=\"disc\">\r\n    <li>\r\n        Pigs       in this phase should be weaned and require a high-quality\r\n        pre-starter feed       for optimal growth.\r\n    </li>\r\n</ul>\r\n<p>\r\n    Phase 2: Day 22 - Day 42\r\n</p>\r\n<p>\r\n    Morning Feeding (6:00 - 7:00 am)\r\n</p>\r\n<ul type=\"disc\">\r\n    <li>\r\n        Feed       Type: Starter Feed\r\n    </li>\r\n    <li>\r\n        Daily       Feed Amount: 0.5 kg/pig\r\n    </li>\r\n    <li>\r\n        Nutritional       Content: 18% protein, 3% fat, 12% fiber\r\n    </li>\r\n    <li>\r\n        Water       Intake: Provide fresh water ad libitum (free access)\r\n    </li>\r\n</ul>\r\n<p>\r\n    Afternoon Feeding (4:30 - 5:00 pm)\r\n</p>\r\n<ul type=\"disc\">\r\n    <li>\r\n        Feed       Type: Starter Feed\r\n    </li>\r\n    <li>\r\n        Daily       Feed Amount: 0.5 kg/pig\r\n    </li>\r\n    <li>\r\n        Nutritional       Content: 18% protein, 3% fat, 12% fiber\r\n    </li>\r\n    <li>\r\n        Water       Intake: Provide fresh water ad libitum (free access)\r\n    </li>\r\n</ul>\r\n<p>\r\n    Reminders:\r\n</p>\r\n<ul type=\"disc\">\r\n    <li>\r\n        Maintain       clean and dry feeding areas.\r\n    </li>\r\n    <li>\r\n        Continue       to monitor pig health and behavior.\r\n    </li>\r\n    <li>\r\n        Check       water supply regularly.\r\n    </li>\r\n</ul>\r\n<p>\r\n    Advice:\r\n</p>\r\n<ul type=\"disc\">\r\n    <li>\r\n        Continue       to weigh your pigs at least once a week to track their\r\n        growth.\r\n    </li>\r\n    <li>\r\n        Observe       for any signs of illness and seek veterinary care if\r\n        necessary.\r\n    </li>\r\n    <li>\r\n        Stick       to a consistent feeding schedule.\r\n    </li>\r\n</ul>\r\n<p>\r\n    Remarks:\r\n</p>\r\n<ul type=\"disc\">\r\n    <li>\r\n        Pigs       in this phase are still in the starter stage and should\r\n        continue to       receive the same starter feed for optimal growth.\r\n    </li>\r\n</ul>\r\n<p>\r\n    Phase 3: Day 43 - Day 63\r\n</p>\r\n<p>\r\n    Morning Feeding (6:00 - 7:00 am)\r\n</p>\r\n<ul type=\"disc\">\r\n    <li>\r\n        Feed       Type: Grower Feed\r\n    </li>\r\n    <li>\r\n        Daily       Feed Amount: 1 kg/pig\r\n    </li>\r\n    <li>\r\n        Nutritional       Content: 16% protein, 2.5% fat, 10% fiber\r\n    </li>\r\n    <li>\r\n        Water       Intake: Provide fresh water ad libitum (free access)\r\n    </li>\r\n</ul>\r\n<p>\r\n    Afternoon Feeding (4:30 - 5:00 pm)\r\n</p>\r\n<ul type=\"disc\">\r\n    <li>\r\n        Feed       Type: Grower Feed\r\n    </li>\r\n    <li>\r\n        Daily       Feed Amount: 1 kg/pig\r\n    </li>\r\n    <li>\r\n        Nutritional       Content: 16% protein, 2.5% fat, 10% fiber\r\n    </li>\r\n    <li>\r\n        Water       Intake: Provide fresh water ad libitum (free access)\r\n    </li>\r\n</ul>\r\n<p>\r\n    Reminders:\r\n</p>\r\n<ul type=\"disc\">\r\n    <li>\r\n        Ensure       the pigpen remains clean and dry.\r\n    </li>\r\n    <li>\r\n        Continue       to monitor pig health and behavior.\r\n    </li>\r\n    <li>\r\n        Check       water supply regularly.\r\n    </li>\r\n</ul>\r\n<p>\r\n    Advice:\r\n</p>\r\n<ul type=\"disc\">\r\n    <li>\r\n        Adjust       feed to the new grower feed formula as specified.\r\n    </li>\r\n    <li>\r\n        Keep       records of feed consumption, growth rates, and health\r\n        observations.\r\n    </li>\r\n    <li>\r\n        Maintain       a well-ventilated environment and provide adequate\r\n        shelter from extreme       weather conditions.\r\n    </li>\r\n</ul>\r\n<p>\r\n    Remarks:\r\n</p>\r\n<ul type=\"disc\">\r\n    <li>\r\n        Pigs       in Phase 3 have grown and need a different, lower-protein\r\n        grower feed to       support their development.\r\n    </li>\r\n</ul>\r\n<p>\r\n    Phase 4: Day 64 - Day 84\r\n</p>\r\n<p>\r\n    Morning Feeding (6:00 - 7:00 am)\r\n</p>\r\n<ul type=\"disc\">\r\n    <li>\r\n        Feed       Type: Grower Feed\r\n    </li>\r\n    <li>\r\n        Daily       Feed Amount: 1.5 kg/pig\r\n    </li>\r\n    <li>\r\n        Nutritional       Content: 15% protein, 2.5% fat, 10% fiber\r\n    </li>\r\n    <li>\r\n        Water       Intake: Provide fresh water ad libitum (free access)\r\n    </li>\r\n</ul>\r\n<p>\r\n    Afternoon Feeding (4:30 - 5:00 pm)\r\n</p>\r\n<ul type=\"disc\">\r\n    <li>\r\n        Feed       Type: Grower Feed\r\n    </li>\r\n    <li>\r\n        Daily       Feed Amount: 1.5 kg/pig\r\n    </li>\r\n    <li>\r\n        Nutritional       Content: 15% protein, 2.5% fat, 10% fiber\r\n    </li>\r\n    <li>\r\n        Water       Intake: Provide fresh water ad libitum (free access)\r\n    </li>\r\n</ul>\r\n<p>\r\n    Reminders:\r\n</p>\r\n<ul type=\"disc\">\r\n    <li>\r\n        Maintain       clean and dry feeding areas.\r\n    </li>\r\n    <li>\r\n        Continue       to monitor pig health and behavior.\r\n    </li>\r\n    <li>\r\n        Check       water supply regularly.\r\n    </li>\r\n</ul>\r\n<p>\r\n    Advice:\r\n</p>\r\n<ul type=\"disc\">\r\n    <li>\r\n        Gradually       increase the daily feed amount to 1.5 kg/pig to meet\r\n        their growing needs.\r\n    </li>\r\n    <li>\r\n        Keep       records of feed consumption, growth rates, and health\r\n        observations.\r\n    </li>\r\n    <li>\r\n        Provide       a comfortable and stress-free environment for the pigs.\r\n    </li>\r\n</ul>\r\n<p>\r\n    Remarks:\r\n</p>\r\n<ul type=\"disc\">\r\n    <li>\r\n        Pigs       in Phase 4 are in the growing phase and should be fed a\r\n        lower-protein,       higher-fiber grower feed to support their\r\n        development.\r\n    </li>\r\n</ul>\r\n<p>\r\n    Phase 5: Day 85 - Day 105\r\n</p>\r\n<p>\r\n    Morning Feeding (6:00 - 7:00 am)\r\n</p>\r\n<ul type=\"disc\">\r\n    <li>\r\n        Feed       Type: Finisher Feed\r\n    </li>\r\n    <li>\r\n        Daily       Feed Amount: 2 kg/pig\r\n    </li>\r\n    <li>\r\n        Nutritional       Content: 14% protein, 2.5% fat, 9% fiber\r\n    </li>\r\n    <li>\r\n        Water       Intake: Provide fresh water ad libitum (free access)\r\n    </li>\r\n</ul>\r\n<p>\r\n    Afternoon Feeding (4:30 - 5:00 pm)\r\n</p>\r\n<ul type=\"disc\">\r\n    <li>\r\n        Feed       Type: Finisher Feed\r\n    </li>\r\n    <li>\r\n        Daily       Feed Amount: 2 kg/pig\r\n    </li>\r\n    <li>\r\n        Nutritional       Content: 14% protein, 2.5% fat, 9% fiber\r\n    </li>\r\n    <li>\r\n        Water       Intake: Provide fresh water ad libitum (free access)\r\n    </li>\r\n</ul>\r\n<p>\r\n    Reminders:\r\n</p>\r\n<ul type=\"disc\">\r\n    <li>\r\n        Maintain       clean and dry feeding areas.\r\n    </li>\r\n    <li>\r\n        Continue       to monitor pig health and behavior.\r\n    </li>\r\n    <li>\r\n        Check       water supply regularly.\r\n    </li>\r\n</ul>\r\n<p>\r\n    Advice:\r\n</p>\r\n<ul type=\"disc\">\r\n    <li>\r\n        Transition       to the finisher feed formula as specified.\r\n    </li>\r\n    <li>\r\n        Keep       records of feed consumption, growth rates, and health\r\n        observations.\r\n    </li>\r\n    <li>\r\n        Ensure       proper ventilation and temperature control in the pigpen.\r\n    </li>\r\n</ul>\r\n<p>\r\n    Remarks:\r\n</p>\r\n<ul type=\"disc\">\r\n    <li>\r\n        Pigs       in Phase 5 are in the finishing stage and should be fed a\r\n        lower-protein,       higher-fiber finisher feed to prepare them for\r\n        market weight.\r\n    </li>\r\n</ul>\r\n<p>\r\n    Phase 6: Day 106 - Day 120\r\n</p>\r\n<p>\r\n    Morning Feeding (6:00 - 7:00 am)\r\n</p>\r\n<ul type=\"disc\">\r\n    <li>\r\n        Feed       Type: Finisher Feed\r\n    </li>\r\n    <li>\r\n        Daily       Feed Amount: 2.5 kg/pig\r\n    </li>\r\n    <li>\r\n        Nutritional       Content: 14% protein, 2.5% fat, 9% fiber\r\n    </li>\r\n    <li>\r\n        Water       Intake: Provide fresh water ad libitum (free access)\r\n    </li>\r\n</ul>\r\n<p>\r\n    Afternoon Feeding (4:30 - 5:00 pm)\r\n</p>\r\n<ul type=\"disc\">\r\n    <li>\r\n        Feed       Type: Finisher Feed\r\n    </li>\r\n    <li>\r\n        Daily       Feed Amount: 2.5 kg/pig\r\n    </li>\r\n    <li>\r\n        Nutritional       Content: 14% protein, 2.5% fat, 9% fiber\r\n    </li>\r\n    <li>\r\n        Water       Intake: Provide fresh water ad libitum (free access)\r\n    </li>\r\n</ul>\r\n<p>\r\n    Reminders:\r\n</p>\r\n<ul type=\"disc\">\r\n    <li>\r\n        Maintain       clean and dry feeding areas.\r\n    </li>\r\n    <li>\r\n        Continue       to monitor pig health and behavior.\r\n    </li>\r\n    <li>\r\n        Check       water supply regularly.\r\n    </li>\r\n</ul>\r\n<p>\r\n    Advice:\r\n</p>\r\n<ul type=\"disc\">\r\n    <li>\r\n        Continue       feeding the finisher feed formula as specified.\r\n    </li>\r\n    <li>\r\n        Keep       records of feed consumption, growth rates, and health\r\n        observations.\r\n    </li>\r\n    <li>\r\n        Prepare       for market or further processing as needed.\r\n    </li>\r\n</ul>\r\n<p>\r\n    Remarks:\r\n</p>\r\n<ul type=\"disc\">\r\n    <li>\r\n        Pigs       in Phase 6 are in the final stages of fattening and should\r\n        continue to       receive the finisher feed to reach their target weight\r\n        for market.\r\n    </li>\r\n</ul>'),
(2, 'FOR  PUREBREED  AND MIX BREED FATTENERS', 'Phase 1: Day 0 - Day 21\r\nMorning Feeding (6:00 - 7:00 am)\r\n•	Feed Type: Pre-Starter Feed\r\n•	Daily Feed Amount: 0.5 kg/pig\r\n•	Nutritional Content: 20% protein, 4% fat, 12% fiber\r\n•	Water Intake: Provide fresh water ad libitum (free access)\r\nAfternoon Feeding (4:30 - 5:00 pm)\r\n•	Feed Type: Pre-Starter Feed\r\n•	Daily Feed Amount: 0.5 kg/pig\r\n•	Nutritional Content: 20% protein, 4% fat, 12% fiber\r\n•	Water Intake: Provide fresh water ad libitum (free access)\r\nReminders:\r\n•	Ensure clean and dry feeding areas.\r\n•	Monitor pig health and behavior.\r\n•	Check water supply regularly.\r\nAdvice:\r\n•	Weigh your pigs at least once a week to track their growth.\r\n•	Observe for any signs of illness and seek veterinary care if necessary.\r\n•	Maintain a consistent feeding schedule.\r\nRemarks:\r\n•	Pigs in this phase should be weaned and require a high-quality pre-starter feed for optimal growth.\r\nPhase 2: Day 22 - Day 42\r\nMorning Feeding (6:00 - 7:00 am)\r\n•	Feed Type: Starter Feed\r\n•	Daily Feed Amount: 0.5 kg/pig\r\n•	Nutritional Content: 18% protein, 3% fat, 12% fiber\r\n•	Water Intake: Provide fresh water ad libitum (free access)\r\nAfternoon Feeding (4:30 - 5:00 pm)\r\n•	Feed Type: Starter Feed\r\n•	Daily Feed Amount: 0.5 kg/pig\r\n•	Nutritional Content: 18% protein, 3% fat, 12% fiber\r\n•	Water Intake: Provide fresh water ad libitum (free access)\r\nReminders:\r\n•	Maintain clean and dry feeding areas.\r\n•	Continue to monitor pig health and behavior.\r\n•	Check water supply regularly.\r\nAdvice:\r\n•	Continue to weigh your pigs at least once a week to track their growth.\r\n•	Observe for any signs of illness and seek veterinary care if necessary.\r\n•	Stick to a consistent feeding schedule.\r\nRemarks:\r\n•	Pigs in this phase are still in the starter stage and should continue to receive the same starter feed for optimal growth.\r\nPhase 3: Day 43 - Day 63\r\nMorning Feeding (6:00 - 7:00 am)\r\n•	Feed Type: Grower Feed\r\n•	Daily Feed Amount: 1 kg/pig\r\n•	Nutritional Content: 16% protein, 2.5% fat, 10% fiber\r\n•	Water Intake: Provide fresh water ad libitum (free access)\r\nAfternoon Feeding (4:30 - 5:00 pm)\r\n•	Feed Type: Grower Feed\r\n•	Daily Feed Amount: 1 kg/pig\r\n•	Nutritional Content: 16% protein, 2.5% fat, 10% fiber\r\n•	Water Intake: Provide fresh water ad libitum (free access)\r\nReminders:\r\n•	Ensure the pigpen remains clean and dry.\r\n•	Continue to monitor pig health and behavior.\r\n•	Check water supply regularly.\r\nAdvice:\r\n•	Adjust feed to the new grower feed formula as specified.\r\n•	Keep records of feed consumption, growth rates, and health observations.\r\n•	Maintain a well-ventilated environment and provide adequate shelter from extreme weather conditions.\r\nRemarks:\r\n•	Pigs in Phase 3 have grown and need a different, lower-protein grower feed to support their development.\r\nPhase 4: Day 64 - Day 84\r\nMorning Feeding (6:00 - 7:00 am)\r\n•	Feed Type: Grower Feed\r\n•	Daily Feed Amount: 1.5 kg/pig\r\n•	Nutritional Content: 15% protein, 2.5% fat, 10% fiber\r\n•	Water Intake: Provide fresh water ad libitum (free access)\r\nAfternoon Feeding (4:30 - 5:00 pm)\r\n•	Feed Type: Grower Feed\r\n•	Daily Feed Amount: 1.5 kg/pig\r\n•	Nutritional Content: 15% protein, 2.5% fat, 10% fiber\r\n•	Water Intake: Provide fresh water ad libitum (free access)\r\nReminders:\r\n•	Maintain clean and dry feeding areas.\r\n•	Continue to monitor pig health and behavior.\r\n•	Check water supply regularly.\r\nAdvice:\r\n•	Gradually increase the daily feed amount to 1.5 kg/pig to meet their growing needs.\r\n•	Keep records of feed consumption, growth rates, and health observations.\r\n•	Provide a comfortable and stress-free environment for the pigs.\r\nRemarks:\r\n•	Pigs in Phase 4 are in the growing phase and should be fed a lower-protein, higher-fiber grower feed to support their development.\r\nPhase 5: Day 85 - Day 105\r\nMorning Feeding (6:00 - 7:00 am)\r\n•	Feed Type: Finisher Feed\r\n•	Daily Feed Amount: 2 kg/pig\r\n•	Nutritional Content: 14% protein, 2.5% fat, 9% fiber\r\n•	Water Intake: Provide fresh water ad libitum (free access)\r\nAfternoon Feeding (4:30 - 5:00 pm)\r\n•	Feed Type: Finisher Feed\r\n•	Daily Feed Amount: 2 kg/pig\r\n•	Nutritional Content: 14% protein, 2.5% fat, 9% fiber\r\n•	Water Intake: Provide fresh water ad libitum (free access)\r\nReminders:\r\n•	Maintain clean and dry feeding areas.\r\n•	Continue to monitor pig health and behavior.\r\n•	Check water supply regularly.\r\nAdvice:\r\n•	Transition to the finisher feed formula as specified.\r\n•	Keep records of feed consumption, growth rates, and health observations.\r\n•	Ensure proper ventilation and temperature control in the pigpen.\r\nRemarks:\r\n•	Pigs in Phase 5 are in the finishing stage and should be fed a lower-protein, higher-fiber finisher feed to prepare them for market weight.\r\nPhase 6: Day 106 - Day 120\r\nMorning Feeding (6:00 - 7:00 am)\r\n•	Feed Type: Finisher Feed\r\n•	Daily Feed Amount: 2.5 kg/pig\r\n•	Nutritional Content: 14% protein, 2.5% fat, 9% fiber\r\n•	Water Intake: Provide fresh water ad libitum (free access)\r\nAfternoon Feeding (4:30 - 5:00 pm)\r\n•	Feed Type: Finisher Feed\r\n•	Daily Feed Amount: 2.5 kg/pig\r\n•	Nutritional Content: 14% protein, 2.5% fat, 9% fiber\r\n•	Water Intake: Provide fresh water ad libitum (free access)\r\nReminders:\r\n•	Maintain clean and dry feeding areas.\r\n•	Continue to monitor pig health and behavior.\r\n•	Check water supply regularly.\r\nAdvice:\r\n•	Continue feeding the finisher feed formula as specified.\r\n•	Keep records of feed consumption, growth rates, and health observations.\r\n•	Prepare for market or further processing as needed.\r\nRemarks:\r\n•	Pigs in Phase 6 are in the final stages of fattening and should continue to receive the finisher feed to reach their target weight for market.\r\n');

-- --------------------------------------------------------

--
-- Table structure for table `feeds`
--

CREATE TABLE `feeds` (
  `feed_id` int(11) NOT NULL,
  `feed_brand` varchar(255) NOT NULL,
  `nutritional_content` longtext NOT NULL,
  `storage_protocol` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `feeds`
--

INSERT INTO `feeds` (`feed_id`, `feed_brand`, `nutritional_content`, `storage_protocol`) VALUES
(1, 'Unifeeds', 'Vitamins V', 'Store in cold temperature'),
(2, 'Ultrapack', 'Vitamin X', 'Store in storage room'),
(3, 'B-meg', 'Vitamins L', 'Store outside'),
(4, 'Uno', 'Vitamin Z', 'Store inside');

-- --------------------------------------------------------

--
-- Table structure for table `feed_quality_logs`
--

CREATE TABLE `feed_quality_logs` (
  `message_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` longtext NOT NULL,
  `sender_id` int(11) NOT NULL,
  `recipient_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `time_stamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `feed_quality_logs`
--

INSERT INTO `feed_quality_logs` (`message_id`, `user_id`, `message`, `sender_id`, `recipient_id`, `job_id`, `time_stamp`) VALUES
(25, 187, 'Brand: Unifeeds<br>Ingredients: qweqw<br>Feed Formulation: rqwwqr', 187, 0, 0, '2023-09-21 02:24:35'),
(26, 187, 'Nutritional Content: Vitamins V<br>Storage Control Protocol: Store in cold temperature', 0, 0, 0, '2023-09-21 02:24:35'),
(27, 187, 'Brand: Ultrapack<br>Ingredients: wqe<br>Feed Formulation: qwe', 187, 0, 0, '2023-09-21 02:31:57'),
(28, 187, 'Nutritional Content: Vitamin X<br>Storage Control Protocol: Store in storage room', 0, 0, 0, '2023-09-21 02:31:57');

-- --------------------------------------------------------

--
-- Table structure for table `feed_types`
--

CREATE TABLE `feed_types` (
  `feed_type_id` int(11) NOT NULL,
  `feed_type_name` varchar(255) NOT NULL,
  `purpose` longtext NOT NULL,
  `remarks` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `feed_types`
--

INSERT INTO `feed_types` (`feed_type_id`, `feed_type_name`, `purpose`, `remarks`) VALUES
(1, 'Pre-starter', '', ''),
(2, 'Starter', '', ''),
(3, 'Grower', '', ''),
(4, 'Finisher', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `ingredients`
--

CREATE TABLE `ingredients` (
  `ingredient_id` int(11) NOT NULL,
  `ingredient_name` varchar(255) NOT NULL,
  `feed_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `job_id` int(11) NOT NULL,
  `service_seeker_id` int(11) NOT NULL,
  `service_worker_id` int(11) NOT NULL,
  `job_title` longtext NOT NULL,
  `job_description` longtext NOT NULL,
  `compensation` float NOT NULL,
  `job_status` varchar(255) NOT NULL,
  `rate` int(11) NOT NULL,
  `rate_comment` longtext NOT NULL,
  `payment_status` varchar(255) NOT NULL,
  `cancel_reason` longtext NOT NULL,
  `date_added` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`job_id`, `service_seeker_id`, `service_worker_id`, `job_title`, `job_description`, `compensation`, `job_status`, `rate`, `rate_comment`, `payment_status`, `cancel_reason`, `date_added`) VALUES
(1, 185, 181, 'qe', 'qwe', 123, 'Completed', 4, 'qweqwrasasfasf', 'Paid', '', '2022-02-17'),
(2, 185, 181, 'qwe', 'qweq', 123, 'Completed', 3, 'Good job girl', '', '', '2022-02-17'),
(3, 185, 186, 'Kalot some shit', 'qweqe', 24, 'Cancelled', 0, '', '', 'Wala lang', '2022-02-25'),
(4, 185, 181, 'Install wiring system', 'Install the full wire system in the whole house', 1000, 'Completed', 5, 'Good Job, I really you so much', 'Paid', '', '2022-03-07');

-- --------------------------------------------------------

--
-- Table structure for table `nutritional_logs`
--

CREATE TABLE `nutritional_logs` (
  `message_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` longtext NOT NULL,
  `sender_id` int(11) NOT NULL,
  `recipient_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `time_stamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pigs`
--

CREATE TABLE `pigs` (
  `pig_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `pig_name` varchar(255) NOT NULL,
  `birthdate` date NOT NULL,
  `purpose` longtext NOT NULL,
  `gender` varchar(255) NOT NULL,
  `weight` float NOT NULL,
  `life_status` varchar(255) NOT NULL,
  `maternity_status` varchar(255) NOT NULL,
  `pig_pic` longtext NOT NULL,
  `feed_brand` varchar(255) NOT NULL,
  `pig_breed` varchar(255) NOT NULL,
  `date_added` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pigs`
--

INSERT INTO `pigs` (`pig_id`, `user_id`, `pig_name`, `birthdate`, `purpose`, `gender`, `weight`, `life_status`, `maternity_status`, `pig_pic`, `feed_brand`, `pig_breed`, `date_added`) VALUES
(1, 187, 'babsy', '2023-09-12', 'ihaw', 'Female', 12, 'Alive', '', 'IMG_20230720_101315.jpg', 'tahop', 'Large White', '2023-09-14'),
(2, 187, 'adasd', '2023-09-12', 'qwe', 'Female', 213, 'Alive', '', '', 'qwe', 'eqwe', '2023-09-14'),
(3, 187, 'qweqwe', '2023-09-13', 'qwrqwr', 'Female', 123, 'Alive', '', '', 'qwrwqr', 'qweqw', '2023-09-14'),
(4, 187, 'qweqwe', '2023-10-01', 'qwe', 'Male', 23, 'Alive', 'Not Pregnant', '', '', 'Land Race', '2023-10-02');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`) VALUES
(1, 'Administrator'),
(2, 'Service Worker'),
(3, 'Service Finder'),
(4, 'Dean'),
(5, 'Instructor'),
(6, 'Student'),
(7, 'Staff');

-- --------------------------------------------------------

--
-- Table structure for table `trainings`
--

CREATE TABLE `trainings` (
  `training_id` int(11) NOT NULL,
  `title` longtext NOT NULL,
  `description` longtext NOT NULL,
  `filename` longtext NOT NULL,
  `date_added` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `trainings`
--

INSERT INTO `trainings` (`training_id`, `title`, `description`, `filename`, `date_added`) VALUES
(1, 'qwe', 'qwe', 'Virgin-Coconut-Oil-Benefits.jpg', '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `middlename` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `gender` varchar(255) NOT NULL,
  `region` varchar(255) NOT NULL,
  `province` longtext NOT NULL,
  `municipality` varchar(255) NOT NULL,
  `barangay` varchar(255) NOT NULL,
  `street` longtext NOT NULL,
  `address` longtext NOT NULL,
  `birthdate` date NOT NULL,
  `email` varchar(255) NOT NULL,
  `contact_number` int(11) NOT NULL,
  `user_status` varchar(255) NOT NULL,
  `profile_pic` varchar(255) NOT NULL,
  `valid_id` longtext NOT NULL,
  `occupation` longtext NOT NULL,
  `website` longtext NOT NULL,
  `twitter` longtext NOT NULL,
  `instagram` longtext NOT NULL,
  `facebook` longtext NOT NULL,
  `qr_code` varchar(255) NOT NULL,
  `token` longtext NOT NULL,
  `date_created` date NOT NULL,
  `date_changed` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `role_id`, `username`, `password`, `firstname`, `middlename`, `lastname`, `gender`, `region`, `province`, `municipality`, `barangay`, `street`, `address`, `birthdate`, `email`, `contact_number`, `user_status`, `profile_pic`, `valid_id`, `occupation`, `website`, `twitter`, `instagram`, `facebook`, `qr_code`, `token`, `date_created`, `date_changed`) VALUES
(34, 1, 'admin', '21232f297a57a5a743894a0e4a801fc3', '(Admin)', '', '', 'Male', '', '', '', '', '', '', '0000-00-00', 'tagsipefraim@gmail.com', 0, 'Active', '', '', '', '', '', '', '', '', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMzQiLCJyb2xlX2lkIjoiMSIsInVzZXJuYW1lIjoiYWRtaW4iLCJmaXJzdG5hbWUiOiJKb2ppZSIsIm1pZGRsZW5hbWUiOiIwIiwibGFzdG5hbWUiOiJFbXBhbmciLCJpZF9udW1iZXIiOiIwIiwiZ2VuZGVyIjoiTWFsZSIsImFkZHJlc3MiOiIiLCJlbWFpbCI6IiIsImNvbnRhY3RfbnVtYmVyIjoiMCIsImRlc2lnbmF0aW9uX2lkIjoiMCIsImpvYl9zdGF0dXNfaWQiOiIxIiwiY291cnNlX2lkIjoiMCIsImRlcGFydG1lbnRfaWQiOiIwIiwicHJvZmlsZV9waWMiOiJKb2ppZUVtcGFuZy5qcGciLCJzdGF0dXMiOiIiLCJ5ZWFyX2xldmVsX2lkIjoiMCIsInRva2VuIjoiIiwiZGF0ZV9jcmVhdGVkIjoiMjAxOC0wMy0wOCJ9.tVY90NoicPeg2sh9mCY_NuirLUtBDmrbWSTLU18GycI', '2018-03-08', '0000-00-00'),
(182, 2, 'qw', '8e9d6cb8e2981fae79794b77cb7c67dd', 'qwe', 'qwe', 'qwe', 'Female', 'REGION II', 'CAGAYAN', 'ALLACAPAN', 'BINOBONGAN', 'qwe', '', '2021-10-07', 'qwe@qwe', 23123, '', '', '', '', '', '', '', '', '', '', '0000-00-00', '0000-00-00'),
(183, 2, 'awd', '1f73402c644002a7ea3c9532e8ba4139', 'Joy', 'qwe', 'Full', 'Female', 'REGION XI', 'DAVAO (DAVAO DEL NORTE)', 'PANABO CITY', 'KIOTOY', 'qweqwe', '', '2021-10-25', 'qwe@qwe', 123, '', '', '', 'Laundry', '', '', '', '', '', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMTgzIiwicm9sZV9pZCI6IjIiLCJ1c2VybmFtZSI6ImF3ZCIsImZpcnN0bmFtZSI6ImF3ZCIsIm1pZGRsZW5hbWUiOiJxd2UiLCJsYXN0bmFtZSI6InF3ZSIsImdlbmRlciI6IkZlbWFsZSIsInJlZ2lvbiI6IlJFR0lPTiBYSSIsInByb3ZpbmNlIjoiREFWQU8gKERBVkFPIERFTCBOT1JURSkiLCJtdW5pY2lwYWxpdHkiOiJQQU5BQk8gQ0lUWSIsImJhcmFuZ2F5IjoiS0lPVE9ZIiwic3RyZWV0IjoicXdlcXdlIiwiYWRkcmVzcyI6IiIsImJpcnRoZGF0ZSI6IjIwMjEtMTAtMjUiLCJlbWFpbCI6InF3ZUBxd2UiLCJjb250YWN0X251bWJlciI6IjEyMyIsInVzZXJfc3RhdHVzIjoiIiwicHJvZmlsZV9waWMiOiIiLCJ2YWxpZF9pZCI6IiIsInFyX2NvZGUiOiIiLCJ0b2tlbiI6IiIsImRhdGVfY3JlYXRlZCI6IjAwMDAtMDAtMDAiLCJkYXRlX2NoYW5nZWQiOiIwMDAwLTAwLTAwIn0.WQKBnfwLUW9NVwuQ4hnmbbfRl1LZKQuggJnA6LTQ7vY', '0000-00-00', '0000-00-00'),
(181, 2, 'qwe', '76d80224611fc919a5d54f0ff9fba446', 'Gary', 'qwe', 'Umbac', 'Female', 'REGION XIII', 'AGUSAN DEL SUR', 'SIBAGAT', 'POBLACION', 'qweqew', '', '2021-10-27', 'qwe@wqe', 213123, 'Active', 'received_8086675063037601.jpeg', '', 'Electrician', 'www.fboy.com', '', '', '', '', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMTgxIiwicm9sZV9pZCI6IjIiLCJ1c2VybmFtZSI6InF3ZSIsImZpcnN0bmFtZSI6InF3ZSIsIm1pZGRsZW5hbWUiOiJxd2UiLCJsYXN0bmFtZSI6InF3ZSIsImdlbmRlciI6IkZlbWFsZSIsInJlZ2lvbiI6IlJFR0lPTiBYSUlJIiwicHJvdmluY2UiOiJBR1VTQU4gREVMIFNVUiIsIm11bmljaXBhbGl0eSI6IlNJQkFHQVQiLCJiYXJhbmdheSI6IlBPQkxBQ0lPTiIsInN0cmVldCI6InF3ZXFldyIsImFkZHJlc3MiOiIiLCJiaXJ0aGRhdGUiOiIyMDIxLTEwLTI3IiwiZW1haWwiOiJxd2VAd3FlIiwiY29udGFjdF9udW1iZXIiOiIyMTMxMjMiLCJ1c2VyX3N0YXR1cyI6IiIsInByb2ZpbGVfcGljIjoiIiwidmFsaWRfaWQiOiIiLCJxcl9jb2RlIjoiIiwidG9rZW4iOiIiLCJkYXRlX2NyZWF0ZWQiOiIwMDAwLTAwLTAwIiwiZGF0ZV9jaGFuZ2VkIjoiMDAwMC0wMC0wMCJ9.aruNqwQEfJJeIcjYO4s9bfztUXDftqzumlvK7k2FetI', '0000-00-00', '0000-00-00'),
(184, 3, 'asd', '7815696ecbf1c96e6894b779456d330e', 'Mark', 'asd', 'Villar', 'Female', 'REGION IX', 'ZAMBOANGA DEL NORTE', 'POLANCO', 'MAGANGON', 'qwe', '', '2021-11-10', 'qwr@wqe', 21312, 'Pending', '', '', '', '', '', '', '', '', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMTg0Iiwicm9sZV9pZCI6IjMiLCJ1c2VybmFtZSI6ImFzZCIsImZpcnN0bmFtZSI6ImFzZCIsIm1pZGRsZW5hbWUiOiJhc2QiLCJsYXN0bmFtZSI6ImFzZCIsImdlbmRlciI6IiIsInJlZ2lvbiI6IlJFR0lPTiBJWCIsInByb3ZpbmNlIjoiWkFNQk9BTkdBIERFTCBOT1JURSIsIm11bmljaXBhbGl0eSI6IlBPTEFOQ08iLCJiYXJhbmdheSI6Ik1BR0FOR09OIiwic3RyZWV0IjoicXdlIiwiYWRkcmVzcyI6IiIsImJpcnRoZGF0ZSI6IjIwMjEtMTEtMTAiLCJlbWFpbCI6InF3ckB3cWUiLCJjb250YWN0X251bWJlciI6IjIxMzEyIiwidXNlcl9zdGF0dXMiOiIiLCJwcm9maWxlX3BpYyI6IiIsInZhbGlkX2lkIjoiIiwib2NjdXBhdGlvbiI6IiIsIndlYnNpdGUiOiIiLCJ0d2l0dGVyIjoiIiwiaW5zdGFncmFtIjoiIiwiZmFjZWJvb2siOiIiLCJxcl9jb2RlIjoiIiwidG9rZW4iOiIiLCJkYXRlX2NyZWF0ZWQiOiIwMDAwLTAwLTAwIiwiZGF0ZV9jaGFuZ2VkIjoiMDAwMC0wMC0wMCJ9.TGNbFnl-DWGAohx1NwSqcP_cDpCMIMFVwaF4OmeY2xU', '0000-00-00', '0000-00-00'),
(185, 3, 'zxc', '5fa72358f0b4fb4f2c5d7de8c9a41846', 'Jenna', 'zxc', 'Hazel', 'Female', 'REGION XII', 'SARANGANI', 'GLAN', 'GLAN PADIDU', 'qwe', '', '2021-11-04', 'zxc@zxc', 123124124, 'Active', 'shop1.jpg', '190891091_908715289705955_6547145448834188815_n.jpg', '', 'w', '', '', '', '', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMTg1Iiwicm9sZV9pZCI6IjMiLCJ1c2VybmFtZSI6Inp4YyIsImZpcnN0bmFtZSI6Inp4YyIsIm1pZGRsZW5hbWUiOiJ6eGMiLCJsYXN0bmFtZSI6Inp4YyIsImdlbmRlciI6IkZlbWFsZSIsInJlZ2lvbiI6IlJFR0lPTiBYSUkiLCJwcm92aW5jZSI6IlNBUkFOR0FOSSIsIm11bmljaXBhbGl0eSI6IkdMQU4iLCJiYXJhbmdheSI6IkdMQU4gUEFESURVIiwic3RyZWV0IjoicXdlIiwiYWRkcmVzcyI6IiIsImJpcnRoZGF0ZSI6IjIwMjEtMTEtMDQiLCJlbWFpbCI6Inp4Y0B6eGMiLCJjb250YWN0X251bWJlciI6IjEyMzEyNDEyNCIsInVzZXJfc3RhdHVzIjoiIiwicHJvZmlsZV9waWMiOiIiLCJ2YWxpZF9pZCI6IiIsIm9jY3VwYXRpb24iOiJNYW5pY3VyZSIsIndlYnNpdGUiOiIiLCJ0d2l0dGVyIjoiIiwiaW5zdGFncmFtIjoiIiwiZmFjZWJvb2siOiIiLCJxcl9jb2RlIjoiIiwidG9rZW4iOiIiLCJkYXRlX2NyZWF0ZWQiOiIwMDAwLTAwLTAwIiwiZGF0ZV9jaGFuZ2VkIjoiMDAwMC0wMC0wMCJ9.zTi3vMF7sb1RcVu8x8d_8rYnabh-vzLQuTMSP9fqiiE', '0000-00-00', '0000-00-00'),
(186, 2, 'asdf', '912ec803b2ce49e4a541068d495ab570', 'asdf', 'asd', 'asd', 'Male', 'REGION VIII', 'BILIRAN', 'CAIBIRAN', 'MAURANG', 'qweqw', '', '2021-11-02', 'asd@awd', 2123, 'Active', '', '', 'wingman', '', '', '', '', '', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMTg2Iiwicm9sZV9pZCI6IjIiLCJ1c2VybmFtZSI6ImFzZGYiLCJmaXJzdG5hbWUiOiJhc2RmIiwibWlkZGxlbmFtZSI6ImFzZCIsImxhc3RuYW1lIjoiYXNkIiwiZ2VuZGVyIjoiTWFsZSIsInJlZ2lvbiI6IlJFR0lPTiBWSUlJIiwicHJvdmluY2UiOiJCSUxJUkFOIiwibXVuaWNpcGFsaXR5IjoiQ0FJQklSQU4iLCJiYXJhbmdheSI6Ik1BVVJBTkciLCJzdHJlZXQiOiJxd2VxdyIsImFkZHJlc3MiOiIiLCJiaXJ0aGRhdGUiOiIyMDIxLTExLTAyIiwiZW1haWwiOiJhc2RAYXdkIiwiY29udGFjdF9udW1iZXIiOiIyMTIzIiwidXNlcl9zdGF0dXMiOiIiLCJwcm9maWxlX3BpYyI6IiIsInZhbGlkX2lkIjoiIiwib2NjdXBhdGlvbiI6IndpbmdtYW4iLCJ3ZWJzaXRlIjoiIiwidHdpdHRlciI6IiIsImluc3RhZ3JhbSI6IiIsImZhY2Vib29rIjoiIiwicXJfY29kZSI6IiIsInRva2VuIjoiIiwiZGF0ZV9jcmVhdGVkIjoiMDAwMC0wMC0wMCIsImRhdGVfY2hhbmdlZCI6IjAwMDAtMDAtMDAifQ.PIkhRQojpGh5zCQAr-uz-1eIsU5tQeU_UKWr7NelQtI', '0000-00-00', '0000-00-00'),
(187, 2, 'qwert123', '824a67f29e97b8798a9df7f00189f3e1', 'qwert', 'qwert', 'qwert', '', 'REGION XIII', 'AGUSAN DEL SUR', 'LORETO', 'SANTA TERESA', 'qweqwe', '', '2023-09-12', 'asdasd@wqeqwe', 213214124, '', '81608472f6c44dddb631d9ff84ae4780.jpg', '', '', '', '', '', '', '', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMTg3Iiwicm9sZV9pZCI6IjAiLCJ1c2VybmFtZSI6InF3ZXJ0MTIzIiwiZmlyc3RuYW1lIjoicXdlcnQiLCJtaWRkbGVuYW1lIjoicXdlcnQiLCJsYXN0bmFtZSI6InF3ZXJ0IiwiZ2VuZGVyIjoiIiwicmVnaW9uIjoiUkVHSU9OIFhJSUkiLCJwcm92aW5jZSI6IkFHVVNBTiBERUwgU1VSIiwibXVuaWNpcGFsaXR5IjoiTE9SRVRPIiwiYmFyYW5nYXkiOiJTQU5UQSBURVJFU0EiLCJzdHJlZXQiOiJxd2Vxd2UiLCJhZGRyZXNzIjoiIiwiYmlydGhkYXRlIjoiMjAyMy0wOS0xMiIsImVtYWlsIjoiYXNkYXNkQHdxZXF3ZSIsImNvbnRhY3RfbnVtYmVyIjoiMjEzMjE0MTI0IiwidXNlcl9zdGF0dXMiOiIiLCJwcm9maWxlX3BpYyI6IiIsInZhbGlkX2lkIjoiIiwib2NjdXBhdGlvbiI6IiIsIndlYnNpdGUiOiIiLCJ0d2l0dGVyIjoiIiwiaW5zdGFncmFtIjoiIiwiZmFjZWJvb2siOiIiLCJxcl9jb2RlIjoiIiwidG9rZW4iOiIiLCJkYXRlX2NyZWF0ZWQiOiIwMDAwLTAwLTAwIiwiZGF0ZV9jaGFuZ2VkIjoiMDAwMC0wMC0wMCJ9.IIw6eFitGdCmgHO5NrqurrfZyKXL28S2xdFmxy6DY_g', '0000-00-00', '0000-00-00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `breeds`
--
ALTER TABLE `breeds`
  ADD PRIMARY KEY (`breed_id`);

--
-- Indexes for table `certifications`
--
ALTER TABLE `certifications`
  ADD PRIMARY KEY (`certification_id`);

--
-- Indexes for table `feeding_guides`
--
ALTER TABLE `feeding_guides`
  ADD PRIMARY KEY (`guide_id`);

--
-- Indexes for table `feeds`
--
ALTER TABLE `feeds`
  ADD PRIMARY KEY (`feed_id`);

--
-- Indexes for table `feed_quality_logs`
--
ALTER TABLE `feed_quality_logs`
  ADD PRIMARY KEY (`message_id`);

--
-- Indexes for table `feed_types`
--
ALTER TABLE `feed_types`
  ADD PRIMARY KEY (`feed_type_id`);

--
-- Indexes for table `ingredients`
--
ALTER TABLE `ingredients`
  ADD PRIMARY KEY (`ingredient_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`job_id`);

--
-- Indexes for table `nutritional_logs`
--
ALTER TABLE `nutritional_logs`
  ADD PRIMARY KEY (`message_id`);

--
-- Indexes for table `pigs`
--
ALTER TABLE `pigs`
  ADD PRIMARY KEY (`pig_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `trainings`
--
ALTER TABLE `trainings`
  ADD PRIMARY KEY (`training_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `breeds`
--
ALTER TABLE `breeds`
  MODIFY `breed_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `certifications`
--
ALTER TABLE `certifications`
  MODIFY `certification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `feeding_guides`
--
ALTER TABLE `feeding_guides`
  MODIFY `guide_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `feeds`
--
ALTER TABLE `feeds`
  MODIFY `feed_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `feed_quality_logs`
--
ALTER TABLE `feed_quality_logs`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `feed_types`
--
ALTER TABLE `feed_types`
  MODIFY `feed_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `ingredients`
--
ALTER TABLE `ingredients`
  MODIFY `ingredient_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `job_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `nutritional_logs`
--
ALTER TABLE `nutritional_logs`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pigs`
--
ALTER TABLE `pigs`
  MODIFY `pig_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `trainings`
--
ALTER TABLE `trainings`
  MODIFY `training_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=188;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

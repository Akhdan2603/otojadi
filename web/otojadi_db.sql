SET collation_connection = 'utf8mb4_general_ci';
SET collation_server = 'utf8mb4_general_ci';
SET character_set_results = 'utf8mb4';
SET character_set_server = 'utf8mb4';

-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 09, 2025 at 11:10 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `otojadi_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `favorite`
--

CREATE TABLE `favorite` (
  `id_user` int NOT NULL,
  `id_barang` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `favorite`
--

INSERT INTO `favorite` (`id_user`, `id_barang`) VALUES
(1, 3),
(1, 1),
(1, 2),
(1, 4),
(2, 4),
(3, 2),
(3, 4);

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id` int NOT NULL,
  `kategori` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id`, `kategori`) VALUES
(1, 'Education'),
(2, 'Halloween'),
(3, 'Commercial'),
(4, 'Games'),
(5, 'Music'),
(6, 'Minimalist'),
(7, 'Event'),
(8, 'Art');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int NOT NULL,
  `id_kategori` int NOT NULL,
  `id_type` int NOT NULL,
  `nama_produk` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `gambar` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `link` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `description` text COLLATE utf8mb4_general_ci,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `id_kategori`, `id_type`, `nama_produk`, `gambar`, `link`, `description`, `created_at`, `updated_at`) VALUES
(2, 2, 2, 'Spooky Halloween Flyer – Editable Canva Template | Costume Party & Event Poster', 'Spooky Halloween Flyer – Editable Canva Template  Costume Party & Event Poster.png', 'https://www.canva.com/design/DAG1q_HDj5w/lrkeGuVDKpQlWtqi6wm9jQ/edit?utm_content=DAG1q_HDj5w&utm_campaign=designshare&utm_medium=link2&utm_source=sharebutton', 'Creative Canva Templates for Spooky Celebrations 👻✨\r\n\r\nDigital file type(s): link canva\r\n\r\nSet the scene for a thrilling celebration with the Spooky Halloween Flyer Canva Template! Featuring playful ghosts, pumpkins, and eerie red accents, this design is perfect for Halloween parties, costume contests, haunted house events, or spooky-themed gatherings. Customize it easily in Canva to make your event unforgettable!\r\n\r\n🕸️ WHAT\'S INCLUDED\r\n\r\nSpooky Halloween Flyer Canva Template\r\na direct Canva link (view-only – make a copy to edit and use)\r\n🌟 Canva Access\r\nYou only need a free Canva account to open, copy, and edit this template.\r\n\r\n✏️ WHAT YOU CAN EDIT\r\n\r\nAll text (event title, date, time, and tagline)\r\nFonts, colors, and graphics\r\nReplace icons or add your own logo and details\r\n📥 DOWNLOAD OPTIONS\r\n\r\nPDF – Ideal for printing as a poster or flyer\r\nJPEG/PNG – Great for sharing on Instagram, Facebook, or WhatsApp\r\n🔁 REFUND POLICY\r\n⚠️ Digital item – all sales are final. No refunds or exchanges once the file has been accessed.\r\n\r\n📜 TERMS OF USE\r\n\r\nFor personal or event use only\r\nDo not resell, redistribute, or reproduce this design\r\nBuyer is responsible for printing\r\nAll designs remain the intellectual property of otojadi', '2025-10-25 22:33:06', '2025-10-25 22:33:06'),
(3, 5, 2, 'Opera Show Flyer – Editable Canva Template | Concert, Music & Event Poster', 'Opera Show Flyer.png', 'https://www.canva.com/design/DAG0DvvjPBQ/b8GlixNbYENX3Y8guj-QHg/edit?utm_content=DAG0DvvjPBQ&utm_campaign=designshare&utm_medium=link2&utm_source=sharebutton', 'Elegant Canva Templates for Events & Creators 🎶✨\r\n\r\nDigital type(s): link canva\r\n\r\nPromote your music event in style with the Opera Show Canva Template – a sleek and professional flyer design perfect for concerts, operas, recitals, theatre shows, and cultural events. Easily customize in Canva with your event details, performer names, and ticket information to create a stunning poster in minutes.\r\n\r\n🌟 Canva Access\r\nA free Canva account is all you need to open, copy, and customize the template.\r\n\r\n✏️ WHAT YOU CAN EDIT\r\n\r\nAll text fields (event title, dates, performers, ticket price, location)\r\nFonts, colors, and images\r\nLayout and design elements to match your branding\r\n📥 DOWNLOAD OPTIONS\r\n\r\nPDF – Perfect for high-quality printing (flyers/posters)\r\nJPEG/PNG – Great for sharing digitally on social media or email\r\n🔁 REFUND POLICY\r\n⚠️ This is a digital product – no refunds or exchanges once the file has been accessed.\r\n\r\n📜 TERMS OF USE\r\n\r\nPersonal or business use allowed (for your events)\r\nYou may not resell, redistribute, or reproduce this template in any form\r\nPrinting is the responsibility of the buyer\r\n\r\nAll designs remain the intellectual property of otojadi', '2025-10-25 22:33:06', '2025-10-25 22:33:06'),
(4, 4, 1, 'Pixel Night: Retro Game Aesthetic (Presentation Canva Template with Animation)', 'pixel1.png,pixel2.png,pixel3.png,pixel4.png,pixel5.png,pixel6.png,pixel7.png,pixel8.png,pixel9.png,pixel10.png', 'https://www.canva.com/design/DAGzqwEVIvM/DdK97wxWsOEAfw0YPHu2hw/edit?utm_content=DAGzqwEVIvM&utm_campaign=designshare&utm_medium=link2&utm_source=sharebutton', 'Pixel Night: Retro Game Aesthetic ✨\r\n\r\nDigital file type(s)﻿: Canva link\r\n\r\nBring a vibrant retro-gaming vibe to your presentation with Pixel Night: Retro Game Aesthetic – a Canva template designed with colorful pixel art and animated creative elements. Perfect for students (elementary to university), content creators, or anyone who wants to deliver a fun, game-inspired presentation that stands out.\r\n\r\n🌌 WHAT\'S INCLUDED\r\n\r\nPixel Night: Retro Game Aesthetic\r\nCanva link\r\nFormat: Presentation (16:9)\r\n10 creative slides with diverse layouts and built-in animations\r\n\r\n🎨 CANVA ACCESS\r\n\r\nOnly a free Canva account is required to open, copy, and edit the template.\r\n\r\n✏️ WHAT YOU CAN EDIT\r\n\r\nAll text fields (titles, subtitles, bullet points, etc.)\r\nColors, fonts, and graphic elements\r\nAdd, remove, or customize elements freely once copied to your Canva account\r\n\r\n🖱️ HOW TO USE IN CANVA\r\n\r\nLog in to your Canva account\r\nClick File in the top left corner\r\nSelect Make a copy\r\nStart editing and customizing as you like\r\n💡 Note: Animations can only be viewed and edited directly in Canva.\r\n\r\n📥 DOWNLOAD OPTIONS\r\n\r\nPDF – For offline presentations or printing\r\nJPEG/PNG – Perfect for digital sharing\r\n\r\n🔁 REFUND POLICY\r\n\r\n⚠️ As this is a digital product, all sales are final. No refunds or exchanges will be provided once the file has been accessed.\r\n\r\n📜 TERMS OF USE\r\n\r\nPersonal Use Only\r\nReselling, redistributing, or reproducing the template in any form is not allowed\r\nPrinting and usage are the buyer’s responsibility\r\nAll designs remain the intellectual property of otojadi\r\n\r\n🙏 Thank you so much for supporting our work!', '2025-10-26 21:21:58', '2025-10-26 21:21:58'),
(7, 6, 2, 'Brown & White Minimalist Coffee Beans Discount Promotion Instagram Post', 'pixel1.png', 'https://www.canva.com/design/DAGzrMfA91Q/52lSZigNKQzqWXV7GjCoWw/edit?utm_content=DAGzrMfA91Q&utm_campaign=designshare&utm_medium=link2&utm_source=sharebutton', 'Stylish Canva Templates for Businesses & Creators ✨\r\nDigital download\r\nDigital file type(s): link canva\r\n\r\nPromote your café or coffee brand with style using the  Minimalist Coffee  Canva Template – a clean and modern Instagram post design perfect for coffee shops, cafés, and beverage promotions. Whether you’re announcing product, new menu items, or special events, this template makes your brand look professional and eye-catching.\r\n\r\n☕ WHAT\'S INCLUDED\r\n\r\nMinimalist Coffee  Canva Template\r\nlink canva\r\nStandard Instagram Post size: 1080 x 1350 px\r\n🌟 Canva Access\r\nOnly a free Canva account is needed to open, copy, and edit the template.\r\n\r\n✏️ WHAT YOU CAN EDIT\r\n\r\nAll text fields (shop name, call-to-action, social media handle)\r\nFonts, colors, and graphics\r\nReplace coffee image or background with your own brand visuals\r\n📥 DOWNLOAD OPTIONS\r\n\r\nJPEG/PNG – Optimized for Instagram feed or story\r\nPDF – For high-resolution use\r\n🔁 REFUND POLICY\r\n⚠️ This is a digital product. No refunds or exchanges once the file has been accessed.\r\n\r\n📜 TERMS OF USE\r\n\r\nPersonal and business use allowed (for your shop/brand)\r\nYou may not resell, redistribute, or reproduce the template in any form\r\nPrinting is your responsibility if needed\r\nAll designs remain the intellectual property of otojadi\r\n\r\nThank you for supporting us', '2025-11-04 11:27:56', '2025-11-04 11:46:59');

-- --------------------------------------------------------

--
-- Table structure for table `type`
--

CREATE TABLE `type` (
  `id` int NOT NULL,
  `nama_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `type`
--

INSERT INTO `type` (`id`, `nama_type`) VALUES
(1, 'PowerPoint'),
(2, 'Poster'),
(3, 'CV'),
(4, 'Video'),
(5, 'Social Media'),
(6, 'Infographic'),
(7, 'Invitation'),
(8, 'Website');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int NOT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `alamat` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `telp` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jenis_kelamin` enum('Male','Female','Rather not disclose') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tgl_lahir` date DEFAULT NULL,
  `poto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'default_pp.jpg',
  `peran` enum('user','admin') COLLATE utf8mb4_general_ci NOT NULL,
  `is_subscribed` tinyint(1) DEFAULT '0',
  `subscription_start` date DEFAULT NULL,
  `subscription_end` date DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `nama`, `email`, `password`, `alamat`, `telp`, `jenis_kelamin`, `tgl_lahir`, `poto`, `peran`, `is_subscribed`, `subscription_start`, `subscription_end`, `created_at`, `updated_at`) VALUES
(1, 'cuk', 'cuk@gmail.com', '202cb962ac59075b964b07152d234b70', '123', '123', 'Male', '2025-10-25', 'upload uts si.PNG', 'user', 0, NULL, NULL, '2025-10-25 23:13:12', '2025-11-07 09:40:28'),
(2, 'tes', 'tes@gmail.com', '202cb962ac59075b964b07152d234b70', NULL, NULL, NULL, '2025-10-01', 'default_pp.jpg', 'user', 1, '2025-10-26', '2025-11-05', '2025-10-26 23:25:05', '2025-11-03 16:38:24'),
(4, 'admin', 'admin@gmail.com', '0192023a7bbd73250516f069df18b500', NULL, NULL, NULL, NULL, 'default_pp.jpg', 'admin', 1, '2025-11-01', '2036-02-29', '2025-11-03 20:02:33', '2025-11-03 20:56:03');

-- --------------------------------------------------------

--
-- Table structure for table `workspaces`
--

CREATE TABLE `workspaces` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `projek_name` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `workspace_items`
--

CREATE TABLE `workspace_items` (
  `id` int NOT NULL,
  `workspace_id` int NOT NULL,
  `product_id` int NOT NULL,
  `added_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `favorite`
--
ALTER TABLE `favorite`
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_barang` (`id_barang`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_kategori` (`id_kategori`),
  ADD KEY `id_type` (`id_type`);

--
-- Indexes for table `type`
--
ALTER TABLE `type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `workspaces`
--
ALTER TABLE `workspaces`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_workspace_user` (`user_id`);

--
-- Indexes for table `workspace_items`
--
ALTER TABLE `workspace_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_item_workspace` (`workspace_id`),
  ADD KEY `fk_item_product` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `type`
--
ALTER TABLE `type`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `workspaces`
--
ALTER TABLE `workspaces`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `workspace_items`
--
ALTER TABLE `workspace_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `workspaces`
--
ALTER TABLE `workspaces`
  ADD CONSTRAINT `fk_workspace_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `workspace_items`
--
ALTER TABLE `workspace_items`
  ADD CONSTRAINT `fk_item_product` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_item_workspace` FOREIGN KEY (`workspace_id`) REFERENCES `workspaces` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

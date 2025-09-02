-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- ホスト: 127.0.0.1
-- 生成日時: 2025-09-02 07:00:23
-- サーバのバージョン： 10.4.32-MariaDB
-- PHP のバージョン: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `kurashinavi`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- テーブルのデータのダンプ `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-06-17 04:56:40');

-- --------------------------------------------------------

--
-- テーブルの構造 `community_events`
--

CREATE TABLE `community_events` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `event_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `location` varchar(200) NOT NULL,
  `category` enum('language_exchange','cultural_experience','sports','social','other') NOT NULL,
  `tags` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tags`)),
  `max_participants` int(11) DEFAULT NULL,
  `current_participants` int(11) DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('upcoming','ongoing','completed','cancelled') DEFAULT 'upcoming',
  `featured` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- テーブルのデータのダンプ `community_events`
--

INSERT INTO `community_events` (`id`, `title`, `description`, `event_date`, `start_time`, `end_time`, `location`, `category`, `tags`, `max_participants`, `current_participants`, `image`, `status`, `featured`, `created_at`, `updated_at`) VALUES
(1, '日本語交流会', '日本語を練習しながら、新しい友達を作りましょう。初心者も大歓迎です。', '2025-06-15', '14:00:00', '16:00:00', '新宿区文化センター', 'language_exchange', '[\"初心者歓迎\", \"日本語\", \"英語\"]', 30, 2, NULL, 'upcoming', 0, '2025-06-30 01:42:55', '2025-07-15 06:02:02'),
(2, '文化体験イベント', '茶道、書道、着物体験など、日本の伝統文化を体験できます。', '2025-06-20', '10:00:00', '15:00:00', '浅草文化センター', 'cultural_experience', '[\"人気\", \"茶道\", \"書道\", \"着物\"]', 25, 2, NULL, 'upcoming', 1, '2025-06-30 01:42:55', '2025-07-15 06:01:37'),
(3, '地域交流パーティー', '地域の日本人と外国人との交流パーティー。料理の持ち寄りも歓迎です。', '2025-06-25', '18:00:00', '20:00:00', '渋谷区民会館', 'social', '[\"新規\", \"料理\", \"交流\"]', 50, 1, NULL, 'upcoming', 0, '2025-06-30 01:42:55', '2025-06-30 03:16:52');

-- --------------------------------------------------------

--
-- テーブルの構造 `community_groups`
--

CREATE TABLE `community_groups` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `category` enum('language_exchange','cultural_activities','sports','hobbies','study','other') NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `member_count` int(11) DEFAULT 0,
  `max_members` int(11) DEFAULT NULL,
  `meeting_frequency` varchar(100) DEFAULT NULL,
  `tags` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tags`)),
  `status` enum('active','inactive','full') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- テーブルのデータのダンプ `community_groups`
--

INSERT INTO `community_groups` (`id`, `name`, `description`, `category`, `icon`, `member_count`, `max_members`, `meeting_frequency`, `tags`, `status`, `created_at`, `updated_at`) VALUES
(1, '言語交換グループ', '日本語と英語の言語交換パートナーを見つけましょう。', 'language_exchange', 'images/community-icon.svg', 1, NULL, '週1回', '[\"日本語\", \"英語\", \"初心者歓迎\"]', 'active', '2025-06-30 01:42:55', '2025-06-30 03:16:58'),
(2, '文化活動グループ', '日本の伝統文化を学び、体験する活動を行っています。', 'cultural_activities', 'images/culture-icon.svg', 2, NULL, '月2回', '[\"茶道\", \"書道\", \"着物\"]', 'active', '2025-06-30 01:42:55', '2025-07-15 06:01:59'),
(3, 'スポーツ交流グループ', 'サッカー、バスケットボール、テニスなど、様々なスポーツを楽しめます。', 'sports', 'images/sports-icon.svg', 1, NULL, '週末', '[\"サッカー\", \"テニス\", \"バスケ\"]', 'active', '2025-06-30 01:42:55', '2025-06-30 03:19:12');

-- --------------------------------------------------------

--
-- テーブルの構造 `community_posts`
--

CREATE TABLE `community_posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(100) NOT NULL,
  `content` text DEFAULT NULL,
  `status` enum('active','inactive','reported') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- テーブルの構造 `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `subject` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `status` enum('pending','read','replied') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- テーブルのデータのダンプ `contacts`
--

INSERT INTO `contacts` (`id`, `name`, `email`, `phone`, `subject`, `message`, `status`, `created_at`, `updated_at`) VALUES
(1, 'alii', '240006ah@yse-c.net', '07090920446', 'general', 'asda', 'pending', '2025-06-10 07:13:49', '2025-06-10 07:13:49'),
(2, 'rija', 'rija@gmail.com', '0222222', 'general', '良いよ', '', '2025-06-10 07:22:21', '2025-07-01 06:11:08'),
(3, 'asda', 'asd@gmail.com', 'asda', 'general', 'asdsad', '', '2025-06-17 06:20:56', '2025-06-17 06:25:38'),
(4, '43543', 'ali@gmail.com', '07090920446', 'service', 'szdf', '', '2025-07-01 05:12:47', '2025-07-01 06:11:01');

-- --------------------------------------------------------

--
-- テーブルの構造 `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(200) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `status` enum('unread','read','replied') DEFAULT 'unread',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- テーブルの構造 `event_participants`
--

CREATE TABLE `event_participants` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` enum('registered','attended','cancelled') DEFAULT 'registered',
  `registered_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- テーブルのデータのダンプ `event_participants`
--

INSERT INTO `event_participants` (`id`, `event_id`, `user_id`, `status`, `registered_at`) VALUES
(4, 2, 35, 'registered', '2025-07-15 06:01:37'),
(5, 1, 35, 'registered', '2025-07-15 06:02:02');

--
-- トリガ `event_participants`
--
DELIMITER $$
CREATE TRIGGER `update_event_participants_count` AFTER INSERT ON `event_participants` FOR EACH ROW BEGIN
    UPDATE community_events 
    SET current_participants = (
        SELECT COUNT(*) 
        FROM event_participants 
        WHERE event_id = NEW.event_id AND status != 'cancelled'
    )
    WHERE id = NEW.event_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- テーブルの構造 `forum_categories`
--

CREATE TABLE `forum_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `topic_count` int(11) DEFAULT 0,
  `view_count` int(11) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `order_number` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- テーブルのデータのダンプ `forum_categories`
--

INSERT INTO `forum_categories` (`id`, `name`, `description`, `icon`, `topic_count`, `view_count`, `status`, `order_number`, `created_at`, `updated_at`) VALUES
(1, '生活相談', '住まい、医療、教育など、生活に関する質問や相談', 'fas fa-home', 45, 1200, 'active', 1, '2025-06-30 01:42:55', '2025-06-30 01:42:55'),
(2, '趣味・交流', '趣味の共有や友達作りのためのトピック', 'fas fa-heart', 78, 2500, 'active', 2, '2025-06-30 01:42:55', '2025-06-30 01:42:55'),
(3, '地域情報', '地域のイベントやおすすめスポットの情報', 'fas fa-map-marked-alt', 32, 1800, 'active', 3, '2025-06-30 01:42:55', '2025-06-30 01:42:55');

-- --------------------------------------------------------

--
-- テーブルの構造 `forum_replies`
--

CREATE TABLE `forum_replies` (
  `id` int(11) NOT NULL,
  `topic_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `content` text NOT NULL,
  `status` enum('active','deleted') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- テーブルの構造 `forum_topics`
--

CREATE TABLE `forum_topics` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(200) NOT NULL,
  `content` text NOT NULL,
  `view_count` int(11) DEFAULT 0,
  `reply_count` int(11) DEFAULT 0,
  `status` enum('active','locked','deleted') DEFAULT 'active',
  `pinned` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- トリガ `forum_topics`
--
DELIMITER $$
CREATE TRIGGER `update_forum_topic_count` AFTER INSERT ON `forum_topics` FOR EACH ROW BEGIN
    UPDATE forum_categories 
    SET topic_count = (
        SELECT COUNT(*) 
        FROM forum_topics 
        WHERE category_id = NEW.category_id AND status = 'active'
    )
    WHERE id = NEW.category_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- テーブルの構造 `group_members`
--

CREATE TABLE `group_members` (
  `id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `role` enum('member','moderator','admin') DEFAULT 'member',
  `joined_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('active','inactive','banned') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- テーブルのデータのダンプ `group_members`
--

INSERT INTO `group_members` (`id`, `group_id`, `user_id`, `role`, `joined_at`, `status`) VALUES
(4, 2, 35, 'member', '2025-07-15 06:01:59', 'active');

--
-- トリガ `group_members`
--
DELIMITER $$
CREATE TRIGGER `update_group_members_count` AFTER INSERT ON `group_members` FOR EACH ROW BEGIN
    UPDATE community_groups 
    SET member_count = (
        SELECT COUNT(*) 
        FROM group_members 
        WHERE group_id = NEW.group_id AND status = 'active'
    )
    WHERE id = NEW.group_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- テーブルの構造 `guide`
--

CREATE TABLE `guide` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `content` text DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- テーブルのデータのダンプ `guide`
--

INSERT INTO `guide` (`id`, `title`, `content`, `category`, `status`, `created_at`, `updated_at`) VALUES
(4, '必要な書類', '日本で住居を借りる際には、以下の書類が一般的に必要となります。それぞれの書類の役割や注意点も合わせてご説明します。\r\n\r\n在留カード（Zairyū Card）\r\n日本に中長期滞在する外国人に発行される身分証明書です。契約時に本人確認として必ず提示が求められます。\r\n\r\nパスポート（Passport）\r\n国籍や本人確認のために必要です。契約書類への記載内容と一致しているか確認されます。\r\n\r\n収入証明書（Shūnyū Shōmeisho）\r\n給与明細や源泉徴収票、雇用証明書などが該当します。家賃を支払う能力があるかを証明するために提出します。\r\n※学生の場合は、親の収入証明や奨学金証明書が必要な場合もあります。\r\n\r\n連帯保証人（Rentai Hoshōnin）\r\n家賃の支払いが滞った場合に代わりに支払う責任を持つ人です。日本在住の親族や勤務先の上司などが一般的ですが、難しい場合は「保証会社」を利用することもできます。', 'housing', 'active', '2025-06-23 02:58:29', '2025-06-24 05:05:38'),
(5, '費用', '敷金（2ヶ月分）\n礼金（1-2ヶ月分）\n仲介手数料（1ヶ月分）\n火災保険', 'housing', 'active', '2025-06-23 02:58:29', '2025-06-23 02:58:29'),
(6, '便利な情報', '物件探しは、駅からの距離や周辺施設も確認しましょう。また、契約前に内見することをお勧めします。', 'housing', 'active', '2025-06-23 02:58:29', '2025-06-23 02:58:29'),
(7, '健康保険', '国民健康保険\n社会保険\n保険証の取得方法', 'healthcare', 'active', '2025-06-23 02:58:29', '2025-06-23 02:58:29'),
(8, '病院の利用方法', '予約方法\n診察料金\n薬の処方', 'healthcare', 'active', '2025-06-23 02:58:29', '2025-06-23 02:58:29'),
(9, '緊急時の対応', '夜間や休日の急病の場合は、救急病院を利用できます。救急車は119番に電話してください。', 'healthcare', 'active', '2025-06-23 02:58:29', '2025-06-23 02:58:29'),
(10, '学校の種類', '公立学校\n私立学校\nインターナショナルスクール', 'education', 'active', '2025-06-23 02:58:29', '2025-06-23 02:58:29'),
(11, '入学手続き', '必要な書類\n入学試験\n学費', 'education', 'active', '2025-06-23 02:58:29', '2025-06-23 02:58:29'),
(12, '学校選びのポイント', '学校の教育方針、通学時間、学費、言語サポート体制などを確認しましょう。', 'education', 'active', '2025-06-23 02:58:29', '2025-06-23 02:58:29'),
(13, '住民登録', '転入届\nマイナンバー\n住民票', 'admin', 'active', '2025-06-23 02:58:29', '2025-06-23 02:58:29'),
(14, '各種届出', '婚姻届\n出生届\n死亡届', 'admin', 'active', '2025-06-23 02:58:29', '2025-06-23 02:58:29'),
(15, '手続きの注意点', '手続きには本人確認書類が必要です。また、期限がある届出は早めに準備しましょう。', 'admin', 'active', '2025-06-23 02:58:29', '2025-06-23 02:58:29');

-- --------------------------------------------------------

--
-- テーブルの構造 `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `icon` varchar(255) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `items` text DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `order_number` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- テーブルのデータのダンプ `services`
--

INSERT INTO `services` (`id`, `title`, `description`, `status`, `created_at`, `updated_at`, `icon`, `category`, `items`, `link`, `order_number`) VALUES
(1, '生活ガイド', '日本での生活に必要な情報を提供します', 'active', '2025-06-23 01:11:04', '2025-06-23 01:11:04', 'images/life-guide-icon.svg', 'main', '[\"住居探しのサポート\", \"医療機関の案内\", \"教育機関の情報提供\", \"行政手続きのサポート\"]', 'guide.php', 1),
(2, '仕事サポート', '就職活動から職場でのサポートまで', 'active', '2025-06-23 01:11:04', '2025-06-23 01:11:04', 'images/work-support-icon.svg', 'main', '[\"就職活動支援\", \"ビザ申請サポート\", \"職場でのコミュニケーション支援\", \"キャリアカウンセリング\"]', '#contact', 2),
(3, 'コミュニティ', '地域交流と文化活動のサポート', 'active', '2025-06-23 01:11:04', '2025-06-23 01:11:04', 'images/community-icon.svg', 'main', '[\"言語交換グループ\", \"文化活動グループ\", \"スポーツ交流グループ\", \"地域交流イベント\"]', 'community.php', 3);

-- --------------------------------------------------------

--
-- テーブルの構造 `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- テーブルのデータのダンプ `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `full_name`, `role`, `created_at`, `updated_at`) VALUES
(3, 'alifauzi21', 'alifauzi.main@gmail.com', '$2y$10$9IbmdAc28OvtamcBGCRkSOmREoLS7f97GBj8DhPqZTkqF0MSHnh2m', 'アリ', 'user', '2025-06-17 06:09:44', '2025-06-17 06:09:44'),
(35, 'alifauzi', 'alifauzi.coding@gmail.com', '$2y$10$uBR6Rf6KF67kdf6tWN390eA.tcpj46FBuB7VnXtEf4I7xVMs78.R.', 'Ali Fauzi', 'admin', '2025-06-17 06:38:34', '2025-06-23 03:46:22');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- テーブルのインデックス `community_events`
--
ALTER TABLE `community_events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_category` (`category`),
  ADD KEY `idx_event_date` (`event_date`),
  ADD KEY `idx_status` (`status`);

--
-- テーブルのインデックス `community_groups`
--
ALTER TABLE `community_groups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_category` (`category`),
  ADD KEY `idx_status` (`status`);

--
-- テーブルのインデックス `community_posts`
--
ALTER TABLE `community_posts`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `event_participants`
--
ALTER TABLE `event_participants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_event_user` (`event_id`,`user_id`),
  ADD KEY `idx_event_id` (`event_id`),
  ADD KEY `idx_user_id` (`user_id`);

--
-- テーブルのインデックス `forum_categories`
--
ALTER TABLE `forum_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_order` (`order_number`);

--
-- テーブルのインデックス `forum_replies`
--
ALTER TABLE `forum_replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_topic_id` (`topic_id`),
  ADD KEY `idx_user_id` (`user_id`);

--
-- テーブルのインデックス `forum_topics`
--
ALTER TABLE `forum_topics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_category_id` (`category_id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_status` (`status`);

--
-- テーブルのインデックス `group_members`
--
ALTER TABLE `group_members`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_group_user` (`group_id`,`user_id`),
  ADD KEY `idx_group_id` (`group_id`),
  ADD KEY `idx_user_id` (`user_id`);

--
-- テーブルのインデックス `guide`
--
ALTER TABLE `guide`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- テーブルの AUTO_INCREMENT `community_events`
--
ALTER TABLE `community_events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- テーブルの AUTO_INCREMENT `community_groups`
--
ALTER TABLE `community_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- テーブルの AUTO_INCREMENT `community_posts`
--
ALTER TABLE `community_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- テーブルの AUTO_INCREMENT `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `event_participants`
--
ALTER TABLE `event_participants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- テーブルの AUTO_INCREMENT `forum_categories`
--
ALTER TABLE `forum_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- テーブルの AUTO_INCREMENT `forum_replies`
--
ALTER TABLE `forum_replies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `forum_topics`
--
ALTER TABLE `forum_topics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `group_members`
--
ALTER TABLE `group_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- テーブルの AUTO_INCREMENT `guide`
--
ALTER TABLE `guide`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- テーブルの AUTO_INCREMENT `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- テーブルの AUTO_INCREMENT `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- ダンプしたテーブルの制約
--

--
-- テーブルの制約 `event_participants`
--
ALTER TABLE `event_participants`
  ADD CONSTRAINT `event_participants_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `community_events` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `event_participants_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- テーブルの制約 `forum_replies`
--
ALTER TABLE `forum_replies`
  ADD CONSTRAINT `forum_replies_ibfk_1` FOREIGN KEY (`topic_id`) REFERENCES `forum_topics` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `forum_replies_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- テーブルの制約 `forum_topics`
--
ALTER TABLE `forum_topics`
  ADD CONSTRAINT `forum_topics_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `forum_categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `forum_topics_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- テーブルの制約 `group_members`
--
ALTER TABLE `group_members`
  ADD CONSTRAINT `group_members_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `community_groups` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `group_members_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

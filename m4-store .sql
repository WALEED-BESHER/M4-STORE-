-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 27 أكتوبر 2025 الساعة 19:44
-- إصدار الخادم: 10.4.18-MariaDB
-- PHP Version: 7.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `m4-store`
--

-- --------------------------------------------------------

--
-- بنية الجدول `login_attempts`
--

CREATE TABLE `login_attempts` (
  `id` int(11) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `attempt_time` datetime DEFAULT NULL,
  `attempts` int(11) DEFAULT 1,
  `ban_until` datetime DEFAULT NULL,
  `ban_level` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- بنية الجدول `messages`
--

CREATE TABLE `messages` (
  `ID` int(11) NOT NULL,
  `NAME` varchar(255) NOT NULL,
  `PHONE` varchar(30) NOT NULL DEFAULT '+967',
  `EMAIL` varchar(255) NOT NULL,
  `MESSAGE` text NOT NULL,
  `CREATE_AT` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- إرجاع أو استيراد بيانات الجدول `messages`
--

INSERT INTO `messages` (`ID`, `NAME`, `PHONE`, `EMAIL`, `MESSAGE`, `CREATE_AT`) VALUES
(2, 'waleed bisher', '+967770411921', 'betobisher@gmail.com', 'i am beto bisher in atan', '2025-03-18 03:03:38'),
(3, 'waleed', '770411921', 'betobisher@gmail.com', '770411921helo', '2025-03-18 03:11:43'),
(4, 'waleed', '733311989', 'saddambir@gmail.com', '7978kjikkk', '2025-03-18 03:14:07'),
(5, 'wal', '77777', 'betosad@gmail.com', 'betosad@gmail.com', '2025-03-18 03:31:02'),
(6, 'mmmm', '777', 'betosad@gmail.com', 'betosad@gmail.com', '2025-03-18 03:31:17'),
(7, 'nnnn', '88888', 'betosad@gmail.com', 'betosad@gmail.com', '2025-03-18 03:31:35'),
(8, 'dddddd', '4444444', 'ndndnnd@gmail.com', 'ndddddddddd', '2025-03-18 03:42:13'),
(9, 'jxjxjxj', '7777777777777', 'ndndnnd@gmail.com', 'ndndnnd@gmail.com', '2025-03-18 03:42:30'),
(10, 'wassem', '770411921', 'bessanmoh@gmail.com', '778899654123', '2025-03-19 00:06:29'),
(11, 'waleed abdullah', '967770411921', 'betosad778@gmail.com', 'hello beto', '2025-04-06 19:53:24');

-- --------------------------------------------------------

--
-- بنية الجدول `notifications`
--

CREATE TABLE `notifications` (
  `ID` int(11) NOT NULL,
  `USER_ID` int(11) DEFAULT NULL,
  `MASSAGE` text NOT NULL,
  `is_read` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `ORDER_ID` int(11) DEFAULT NULL,
  `STATUSS` varchar(15) NOT NULL DEFAULT 'private'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- إرجاع أو استيراد بيانات الجدول `notifications`
--

INSERT INTO `notifications` (`ID`, `USER_ID`, `MASSAGE`, `is_read`, `created_at`, `ORDER_ID`, `STATUSS`) VALUES
(8, 32, 'لقد تم قبول طلبك رقم 32 وسيتم التوصيل في اسرع وقت ممكن وهذا الكود 598-043 لا تعطيه الموصل الا حين تستلم بضاعتك ولسنا متحملين اي مسوليه اذا عطيته قبل تسليم شحنتك و شكرا لكم ولثقتكم بنا', 1, '2025-03-31 20:48:40', 32, 'private'),
(9, 32, 'تم توصيل طلبك بنجاح', 1, '2025-03-31 20:53:40', 32, 'private'),
(10, 47, 'لقد تم قبول طلبك رقم 33 وسيتم التوصيل في اسرع وقت ممكن وهذا الكود 651-515 لا تعطيه الموصل الا حين تستلم بضاعتك ولسنا متحملين اي مسوليه اذا عطيته قبل تسليم شحنتك و شكرا لكم ولثقتكم بنا', 1, '2025-04-01 21:38:53', 33, 'private'),
(11, 47, 'تم توصيل طلبك بنجاح', 1, '2025-04-01 22:45:00', 33, 'private'),
(15, 32, 'لقد تم قبول طلبك رقم 37 وسيتم التوصيل في اسرع وقت ممكن وهذا الكود 954-914 لا تعطيه الموصل الا حين تستلم بضاعتك ولسنا متحملين اي مسوليه اذا عطيته قبل تسليم شحنتك و شكرا لكم ولثقتكم بنا', 1, '2025-04-02 22:04:56', 37, 'private'),
(52, 32, 'waleedo', 1, '2025-04-04 21:03:40', NULL, 'public'),
(53, 42, 'waleedo', 0, '2025-04-04 21:03:40', NULL, 'public'),
(54, 41, 'waleedo', 0, '2025-04-04 21:03:40', NULL, 'public'),
(55, 46, 'waleedo', 1, '2025-04-04 21:03:40', NULL, 'public'),
(56, 34, 'waleedo', 1, '2025-04-04 21:03:40', NULL, 'public'),
(57, 44, 'waleedo', 0, '2025-04-04 21:03:40', NULL, 'public'),
(58, 48, 'waleedo', 0, '2025-04-04 21:03:40', NULL, 'public'),
(90, 46, 'تم ارسال طلبك بنجاح! وطلبك رقم 43  تحت المراجعه سيتم ارسال اشعار اليك في حال الموافقه او الرفض', 0, '2025-04-06 20:19:37', 43, 'private'),
(91, 32, 'تم ارسال طلبك بنجاح! وطلبك رقم 44  تحت المراجعه سيتم ارسال اشعار اليك في حال الموافقه او الرفض', 1, '2025-04-09 20:36:25', 44, 'private'),
(92, 32, 'تم ارسال طلبك بنجاح! وطلبك رقم 45  تحت المراجعه سيتم ارسال اشعار اليك في حال الموافقه او الرفض', 1, '2025-04-09 20:39:55', 45, 'private'),
(93, 32, 'تم ارسال طلبك بنجاح! وطلبك رقم 46  تحت المراجعه سيتم ارسال اشعار اليك في حال الموافقه او الرفض', 1, '2025-04-09 21:49:54', 46, 'private'),
(94, 32, 'تم ارسال طلبك بنجاح! وطلبك رقم 47  تحت المراجعه سيتم ارسال اشعار اليك في حال الموافقه او الرفض', 1, '2025-04-10 18:35:16', 47, 'private'),
(95, 32, 'تم ارسال طلبك بنجاح! وطلبك رقم 48  تحت المراجعه سيتم ارسال اشعار اليك في حال الموافقه او الرفض', 1, '2025-04-10 18:52:54', 48, 'private');

-- --------------------------------------------------------

--
-- بنية الجدول `orders`
--

CREATE TABLE `orders` (
  `ID` int(11) NOT NULL,
  `USER_ID` int(11) DEFAULT NULL,
  `TOTAL_AMOUNT` decimal(10,2) DEFAULT NULL,
  `CREATED_AT` timestamp NOT NULL DEFAULT current_timestamp(),
  `USER_FULL_NAME` varchar(100) NOT NULL,
  `ADDRESS` varchar(255) NOT NULL,
  `PHONE` varchar(20) NOT NULL,
  `PAYMENT_METHOD` varchar(50) NOT NULL,
  `TRANSFER_ID` varchar(50) DEFAULT '-',
  `TRANSFER_NETWORK` varchar(50) DEFAULT '-',
  `WALLET_TYPE` varchar(50) DEFAULT '-',
  `WALLET_ID` varchar(50) DEFAULT '-',
  `STATUS` varchar(50) NOT NULL DEFAULT 'pending',
  `VERIFICATION_CODE` varchar(10) DEFAULT NULL,
  `DELIVERED_AT` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- إرجاع أو استيراد بيانات الجدول `orders`
--

INSERT INTO `orders` (`ID`, `USER_ID`, `TOTAL_AMOUNT`, `CREATED_AT`, `USER_FULL_NAME`, `ADDRESS`, `PHONE`, `PAYMENT_METHOD`, `TRANSFER_ID`, `TRANSFER_NETWORK`, `WALLET_TYPE`, `WALLET_ID`, `STATUS`, `VERIFICATION_CODE`, `DELIVERED_AT`) VALUES
(32, 32, '3000.00', '2025-03-31 20:48:17', 'الوليد بشر', 'عطان بيروت', '770411921', 'cod', '-', '-', '-', '-', 'done', '598-043', '2025-03-31 20:53:40'),
(33, 47, '1200.00', '2025-04-01 21:37:42', '1122', 'mmm', ';kj', 'cod', '-', '-', '-', '-', 'done', '651-515', '2025-04-01 22:45:00'),
(34, 47, '6500.00', '2025-04-01 22:50:23', 'الوليد عبدالله عبدالكريم بشر', 'عطان', '777627525', 'cod', '-', '-', '-', '-', 'done', '644-918', '2025-04-02 20:15:49'),
(37, 32, '3000.00', '2025-04-02 22:00:15', 'ةة', 'اه', '7852', 'cod', '-', '-', '-', '-', 'delevring', '954-914', '2025-04-02 22:00:15'),
(38, 32, '1200.00', '2025-04-02 22:00:35', 'ةئة', 'نئن', '785', 'cod', '-', '-', '-', '-', 'delevring', '908-175', '2025-04-02 22:00:35'),
(42, 32, '4200.00', '2025-04-02 22:34:38', 'وبو', 'ةب', 'ةبةب', 'cod', '-', '-', '-', '-', 'pending', NULL, '2025-04-02 22:34:38'),
(43, 46, '6500.00', '2025-04-06 20:19:37', 'hgj', 'sanaa', '967770411921', 'cod', '-', '-', '-', '-', 'pending', NULL, '2025-04-06 20:19:37'),
(44, 32, '1200.00', '2025-04-09 20:36:25', 'cmcmc', 'mcmcmc', '967775617653', 'cod', '-', '-', '-', '-', 'pending', NULL, '2025-04-09 20:36:25'),
(45, 32, '8200.00', '2025-04-09 20:39:55', 'xmmx', 'xmxm', '967775617659', 'cod', '-', '-', '-', '-', 'pending', NULL, '2025-04-09 20:39:55'),
(46, 32, '3000.00', '2025-04-09 21:49:54', 'mmm', 'wkuhx u\'hk', '967775506648', 'transfer', '112233', 'hb fast', '-', '-', 'pending', NULL, '2025-04-09 21:49:54'),
(47, 32, '3000.00', '2025-04-10 18:35:16', 'صالح محد النصيري', 'صنعاء جوار الكميم', '967775506648', 'transfer', '12121563', 'جييب', '-', '-', 'pending', NULL, '2025-04-10 18:35:16'),
(48, 32, '3000.00', '2025-04-10 18:52:54', 'صالح محد النصيري', 'ستتستست', '967775508846', 'wallet', '-', '-', 'سةسةس', '111222255', 'pending', NULL, '2025-04-10 18:52:54');

-- --------------------------------------------------------

--
-- بنية الجدول `order_items`
--

CREATE TABLE `order_items` (
  `ID` int(11) NOT NULL,
  `ORDER_ID` int(11) DEFAULT NULL,
  `PRODUCT_ID` int(11) DEFAULT NULL,
  `QUANTITY` int(11) DEFAULT NULL,
  `PRICE` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- إرجاع أو استيراد بيانات الجدول `order_items`
--

INSERT INTO `order_items` (`ID`, `ORDER_ID`, `PRODUCT_ID`, `QUANTITY`, `PRICE`) VALUES
(31, 32, 32, 1, '3000.00'),
(32, 33, 29, 1, '1200.00'),
(33, 34, 33, 1, '6500.00'),
(36, 37, 32, 1, '3000.00'),
(37, 38, 29, 1, '1200.00'),
(41, 42, 29, 1, '1200.00'),
(42, 42, 32, 1, '3000.00'),
(43, 43, 33, 1, '6500.00'),
(44, 44, 29, 1, '1200.00'),
(45, 45, 33, 1, '6500.00'),
(46, 45, 26, 1, '1700.00'),
(47, 46, 32, 1, '3000.00'),
(48, 47, 32, 1, '3000.00'),
(49, 48, 32, 1, '3000.00');

-- --------------------------------------------------------

--
-- بنية الجدول `products`
--

CREATE TABLE `products` (
  `ID` int(2) NOT NULL,
  `TITLE` text CHARACTER SET utf8 NOT NULL,
  `PRICE` int(11) NOT NULL,
  `OLD_PRICE` int(12) NOT NULL,
  `IMAGE` varchar(80) NOT NULL,
  `DESCRIPTION` text CHARACTER SET utf8 DEFAULT NULL,
  `TYPE` text DEFAULT NULL,
  `WEIGHT` text NOT NULL,
  `MADE` text NOT NULL,
  `AddDate` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- إرجاع أو استيراد بيانات الجدول `products`
--

INSERT INTO `products` (`ID`, `TITLE`, `PRICE`, `OLD_PRICE`, `IMAGE`, `DESCRIPTION`, `TYPE`, `WEIGHT`, `MADE`, `AddDate`) VALUES
(25, 'مسدس كولت 1911 (Colt 1911)', 1900, 2300, '45.jpg', 'لماذا يعتبر 1911 من أشهر المسدسات في العالم؟\r\nتاريخ عسكري طويل (كان السلاح الشخصي الأساسي للجيش الأمريكي من 1911 إلى 1985).\r\nشعبيته بين هواة الأسلحة والمحترفين (لتصميمه المتين ودقته).\r\nتأثيره الكبير على تصميم المسدسات الحديثة. * عيار: 45\r\n* السعة: 7* السعة: 10 طلقات\r\n', 'مسدس نصف آلي(سلاح خفيف)', '1.4 كيلو', 'الولايات المتحدة الامريكيه والشركه المصنعه :Colt\'s Manufacturing Company', '2025-03-19 05:42:16'),
(26, 'مسدس Beretta M9A3 الأمريكي - المواصفات الكاملة', 1700, 2000, 'Berita.jpg', '* عيار: 9×19\r\n* الطول: 217 ملم\r\n* السعة: 17 طلقة \r\n', 'مسدس نصف آلي (سلاح خفيف)', '1.1 كيلو', 'الولايات المتحدة الامريكيه المصنع :Beretta USA', '2025-03-19 05:42:30'),
(27, 'بورجنيف (Borzhnif) القناصة الروسية', 5200, 4500, 'Bergenof.jpg', '* العيار: 7.62×54mmR\r\n* المدى الفعال: 800–1000 متر\r\n* السعة: 10 طلقات\r\n* الاستخدامات:\r\n1.عمليات القنص العسكري والشرطي.\r\n2.مكافحة الإرهاب.\r\n3.حرب المدن (بفضل دقتها في المسافات المتوسطة والطويلة).', 'هي بندقية قنص شبه أوتوماتيكية (سلاح متوسط)', '6.5 كيلو', 'روسيا 🇷🇺 اسم المصنع :مجموعة كالاشنيكوف (Kalashnikov Concern) ', '2025-03-22 05:31:53'),
(28, 'قنابل يدوي صنع محلي ', 20, 25, 'boomb.jpg', 'قنبله يدوي صنع محلى تتميز بسهوله الاستخدام وصاعق امن ضد الصدمات', 'سلاح فتاك', '0.3 كيلو جرام', 'اليمن', '2025-03-22 05:35:28'),
(29, 'مسدس روجر-57 (Ruger-57) امريكي', 1200, 1500, 'Desert.jpg', '*يتميز بمعدل إطلاق سريع وقدرة عالية على حمل طلقات أكثر مقارنةً ببعض المسدسات ويتمتع المسدس بسرعات عالية للطلقات، مما يجعله فعالًا في العديد من الاستخدامات\r\n*العيار: 5.7×28mm\r\n*الطول: 19.7 سم\r\n*السعة: 20 طلقات ويمكن أن تصل السعة إلى 30 طلقات', ' مسدس نصف الي (سلاح خفيف)', '0.75 كجم', 'الولايات المتحدة الأمريكية .من مصنع (Sturm, Ruger & Co.)', '2025-03-22 05:43:21'),
(32, 'موتمر نيكل بشنطه دايره 10 او ما يسمى بي (فرخ الشبح البلغاري)', 3000, 3100, 'Motamarn.jpg', 'هي بندقية اقتحام من عيار 5.45 × 39 ملم طورها الروس في بداية السبعينات عبر القيام بتجارب عديدة لتحسين سلاح الكلاشنكوف وقاموا بتغيير عياره من 7.62 × 39 ملم إلى 5.45 × 39 ملم، وهذا الأخير أخف من الأول بنسبة 50%. 900 م/ث. المؤثر - المجدي - الأقصى : المدى المؤثر: 600 متر', 'كلاشنكوف (سلاح خفيف)', '2كيلو جرام', ' بلغاريا تحديدا مصنع أرسنال البلغاري', '2025-03-23 04:00:45'),
(33, 'm4 امريكي وكاله', 6000, 6300, 'M41.jpg', 'ام فور امريكي مع المعدات استخدام 5 % ', 'كلاشنكوف (سلاح خفيف)', '2كيلو جرام', 'امريكاء ', '2025-03-26 02:05:12');

-- --------------------------------------------------------

--
-- بنية الجدول `sales`
--

CREATE TABLE `sales` (
  `ID` int(11) NOT NULL,
  `ORDERS_ID` int(11) DEFAULT NULL,
  `AMOUNT` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- إرجاع أو استيراد بيانات الجدول `sales`
--

INSERT INTO `sales` (`ID`, `ORDERS_ID`, `AMOUNT`, `created_at`) VALUES
(32, 32, '3000.00', '2025-03-31 20:48:17'),
(33, 33, '1200.00', '2025-04-01 21:37:42'),
(34, 34, '6500.00', '2025-04-01 22:50:23'),
(37, 37, '3000.00', '2025-04-02 22:00:15'),
(38, 38, '1200.00', '2025-04-02 22:00:35'),
(42, 42, '4200.00', '2025-04-02 22:34:38'),
(43, 43, '6500.00', '2025-04-06 20:19:37'),
(44, 44, '1200.00', '2025-04-09 20:36:25'),
(45, 45, '8200.00', '2025-04-09 20:39:55'),
(46, 46, '3000.00', '2025-04-09 21:49:54'),
(47, 47, '3000.00', '2025-04-10 18:35:16'),
(48, 48, '3000.00', '2025-04-10 18:52:54');

-- --------------------------------------------------------

--
-- بنية الجدول `users`
--

CREATE TABLE `users` (
  `ID` int(11) NOT NULL,
  `FIRST_NAME` varchar(20) NOT NULL,
  `LAST_NAME` varchar(20) DEFAULT NULL,
  `USERNAME` varchar(15) NOT NULL,
  `PHONE` varchar(13) NOT NULL,
  `EMAIL` varchar(35) NOT NULL,
  `PASSWORD` varchar(70) NOT NULL,
  `Role` varchar(10) NOT NULL DEFAULT 'user',
  `ACTIVE` int(2) NOT NULL DEFAULT 1,
  `MASTER` int(5) NOT NULL DEFAULT 0,
  `DELIVERY` int(5) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- إرجاع أو استيراد بيانات الجدول `users`
--

INSERT INTO `users` (`ID`, `FIRST_NAME`, `LAST_NAME`, `USERNAME`, `PHONE`, `EMAIL`, `PASSWORD`, `Role`, `ACTIVE`, `MASTER`, `DELIVERY`) VALUES
(32, 'ALWALEED', 'BISHER', '48wa_r', '967770411921', 'BETOSAD771@gmail.com', 'df40bb87c05b8fc3385630fff6ca0145d0ca5cda', 'admin', 1, 1, 1),
(34, 'sami mohammed', 'alborani', 'sami', '967770411921', 'sami7@gmail.com', '6ba92875148bf292f766e69bacdbbb9b1dd5ba89', 'admin', 1, 0, 1),
(41, 'njwqjwb', 'nwfnf', 'mwmwmw', '967770411921', 'mncncnal@gmail.com', '0435feefded18936825bed3cd512a64539f30f35', 'user', 1, 0, 1),
(42, 'kcnn', 'cmmmmm', 'mcmcmcw', '967770511921', 'mwmmmwmmm@nmn.com', '6b675b949629b35ee86e79094a8a13797e3507e2', 'user', 1, 0, 0),
(44, 'صالح', 'شهاب', 'sammer', '967777790220', 'bndjke@hotmail.com', '00fd4b4549a1094aae926ef62e9dbd3cdcc2e456', 'user', 1, 0, 1),
(46, 'saddam', 'beshr', 'saddam', '967771164635', 'saddambesher71@gmail.com', '3acd0be86de7dcccdbf91b20f94a68cea535922d', 'user', 1, 0, 1),
(47, 'waleed', 'besher', 'waleedo', '967775506647', 'saoppemjo@gmail.com', '3acd0be86de7dcccdbf91b20f94a68cea535922d', 'user', 1, 0, 0),
(48, 'nxnxnx', '', 'sardam', '967777790221', 'dfyipkao3@gmail.com', '00fd4b4549a1094aae926ef62e9dbd3cdcc2e456', 'user', 1, 0, 0),
(49, 'waleed', 'sammer', '58ropem', '967775566325', 'mniplmiegh@gmail.com', '3acd0be86de7dcccdbf91b20f94a68cea535922d', 'user', 1, 0, 0),
(50, 'md58', 'mmm', 'djuid', '967775506647', 'nckifyd45@gmail.com', '3acd0be86de7dcccdbf91b20f94a68cea535922d', 'user', 1, 0, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `USER_ID` (`USER_ID`),
  ADD KEY `fk_order` (`ORDER_ID`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ORDER_ID` (`ORDER_ID`),
  ADD KEY `PRODUCT_ID` (`PRODUCT_ID`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ORDERS_ID` (`ORDERS_ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `USERNAME` (`USERNAME`),
  ADD UNIQUE KEY `USERNAME_2` (`USERNAME`),
  ADD UNIQUE KEY `EMAIL` (`EMAIL`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `ID` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- قيود الجداول المحفوظة
--

--
-- القيود للجدول `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `fk_order` FOREIGN KEY (`ORDER_ID`) REFERENCES `orders` (`ID`),
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`USER_ID`) REFERENCES `users` (`ID`) ON DELETE CASCADE;

--
-- القيود للجدول `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`ORDER_ID`) REFERENCES `orders` (`ID`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`PRODUCT_ID`) REFERENCES `products` (`ID`);

--
-- القيود للجدول `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`ORDERS_ID`) REFERENCES `orders` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

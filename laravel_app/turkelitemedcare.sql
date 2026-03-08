-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 02, 2026 at 04:04 AM
-- Server version: 5.7.24
-- PHP Version: 8.3.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `turkelitemedcare`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `patient_guide_id` bigint(20) UNSIGNED DEFAULT NULL,
  `service_id` bigint(20) UNSIGNED DEFAULT NULL,
  `type` enum('analysis','booking') COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `appointment_date` date DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci,
  `head_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `patient_guide_id`, `service_id`, `type`, `name`, `email`, `phone`, `appointment_date`, `message`, `head_image`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 'booking', 'Salman Azhar', 'salmanazhar8500@gmail.com', '123456789', '2026-02-27', 'AOa', NULL, '2026-02-26 16:01:14', '2026-02-26 16:01:14'),
(2, 1, 11, 'booking', 'Salman Azhar', 'salmanazhar8500@gmail.com', '123456789', '2026-02-27', 'KAsa ho bhai', NULL, '2026-02-26 16:21:45', '2026-02-26 16:21:45'),
(3, 1, 44, 'booking', 'Salman Azhar', 'salmanazhar8500@gmail.com', '123456789', '2026-02-27', 'asasxasxasx', NULL, '2026-02-26 16:23:07', '2026-02-26 16:23:07');

-- --------------------------------------------------------

--
-- Table structure for table `contact_submissions`
--

CREATE TABLE `contact_submissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `home_settings`
--

CREATE TABLE `home_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hero_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hero_subtitle` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hero_description` text COLLATE utf8mb4_unicode_ci,
  `hero_button_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hero_button_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hero_button_text_secondary` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hero_button_link_secondary` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hero_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hero_video` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hero_video_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hero_items` json DEFAULT NULL,
  `about_data` json DEFAULT NULL,
  `about_page_data` json DEFAULT NULL,
  `contact_data` json DEFAULT NULL,
  `services_data` json DEFAULT NULL,
  `whatwedo_data` json DEFAULT NULL,
  `causes_data` json DEFAULT NULL,
  `whychoose_data` json DEFAULT NULL,
  `howitwork_data` json DEFAULT NULL,
  `testimonials_data` json DEFAULT NULL,
  `gallery_data` json DEFAULT NULL,
  `lasthope_data` json DEFAULT NULL,
  `mail_data` json DEFAULT NULL,
  `header_footer_data` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `home_settings`
--

INSERT INTO `home_settings` (`id`, `hero_title`, `hero_subtitle`, `hero_description`, `hero_button_text`, `hero_button_link`, `hero_button_text_secondary`, `hero_button_link_secondary`, `hero_image`, `hero_video`, `hero_video_url`, `hero_items`, `about_data`, `about_page_data`, `contact_data`, `services_data`, `whatwedo_data`, `causes_data`, `whychoose_data`, `howitwork_data`, `testimonials_data`, `gallery_data`, `lasthope_data`, `mail_data`, `header_footer_data`, `created_at`, `updated_at`) VALUES
(1, 'Transforming Healthcare Into A Seamless Experience', 'Affordable. Trusted. International Standard Care.', 'We help patients from around the world access high-quality medical treatments with complete travel assistance and personalized care.', 'Book Consultation', '/contact-us', 'play video', 'href=\"https://www.youtube.com/watch?v=Y-x0efG1seA\"', 'site-settings/hero/E93KrV2ja0ks29IKTcA8n5WyPqnaX4NbksrENjnr.jpg', NULL, 'https://demo.awaikenthemes.com/assets/videos/lenity-video.mp4', '[\"Free Consultation\", \"Expert Surgeons\", \"All-Inclusive Packages\", \"24/7 Support\"]', '{\"about_image\": \"site-settings/about/bSm5wWYkyrrlURUvhqLca6DlQyQoWmPbSOqYMyDa.jpg\", \"about_title\": \"about us\", \"about_years\": \"18\", \"about_image2\": \"site-settings/about/N2R82uMNkMWm3JsYq8PNYISj6lTzN5O9SyVtDPji.jpg\", \"about_doctors\": \"42\", \"about_subtitle\": \"Your Trusted Medical Tourism Partner\", \"about_description\": \"We connect international patients with internationally accredited hospitals and highly experienced doctors. From the moment you contact us until your safe return home, we manage everything with professionalism and care.\", \"about_support_text\": \"Deliver safe, affordable, and world-class healthcare without stress.\", \"about_support_title\": \"Our mission is simple:\", \"about_support_description\": \"Deliver safe, affordable, and world-class healthcare without stress.\"}', NULL, '{\"contact_email\": \"salmanazhar8500@gmail.com\", \"contact_phone\": \"+32123456789\", \"social_twitter\": \"https://facebook.com/yourpage\", \"social_youtube\": \"https://facebook.com/yourpage\", \"contact_address\": \"street 2\", \"social_facebook\": \"https://facebook.com/yourpage\", \"social_linkedin\": \"https://facebook.com/yourpage\", \"social_whatsapp\": \"1234567890\", \"social_instagram\": \"https://facebook.com/yourpage\"}', '{\"services_title\": \"Our Medical Treatments\", \"services_subtitle\": \"SERVICES\", \"services_description\": \"Our services include internationally recognised treatments such as hair transplants, cosmetic surgery, dental care, and weight loss procedures. Performed by certified specialists using the latest technology, we ensure safe, high-quality outcomes for international patients.\", \"services_footer_text\": \"Need guidance before treatment? Speak with our care team today\", \"services_footer_phone\": \"+92 300 1234567\"}', '{\"features\": [{\"desc\": \"In-depth assessments and practical recommendations based on your condition and history.\", \"icon\": \"fas fa-user-md\", \"title\": \"Specialist Consultations\"}, {\"desc\": \"Treatment plans built on proper evaluation, not guesswork.\", \"icon\": \"fas fa-vials\", \"title\": \"Diagnostic-Led Decisions\"}, {\"desc\": \"Regular follow-up and plan refinement to improve outcomes over time.\", \"icon\": \"fas fa-notes-medical\", \"title\": \"Aftercare & Monitoring\"}], \"whatwedo_title\": \"Complete care planning under one roof\", \"whatwedo_image1\": \"site-settings/whatwedo/CkvTqM6zFhyULReIlDuonnPUsUBK1Qin8qBAn3NS.jpg\", \"whatwedo_image2\": \"site-settings/whatwedo/ld5kVJqHirgwHWvYVIf7oBgEFiCtXmn3ZqhWf0EO.jpg\", \"whatwedo_subtitle\": \"what we do\"}', '{\"items\": [{\"desc\": \"Hello\", \"goal\": \"25000\", \"link\": \"https://facebook.com/yourpage\", \"image\": \"site-settings/causes/DHmhRoeZNsb7XEjBO3qNBc7nyx9WYA37Y42lSa5i.jpg\", \"title\": \"All\", \"raised\": \"10000\", \"category\": \"Eduaction\"}], \"causes_title\": \"Supporting\", \"causes_subtitle\": \"Our Causes\", \"causes_description\": \"Hello\"}', '{\"whychoose_title\": \"Excellence. Safety. Transparency.\", \"whychoose_image1\": \"site-settings/whychoose/Gip8dFZRzlTIESXmPgiek3Ye0lIeuRABIFFk3nAo.jpg\", \"whychoose_image2\": \"site-settings/whychoose/VTmANO0tLD3n1pWhBcgUQpScH0eeVilBETBcqHui.jpg\", \"whychoose_points\": [\"Internationally Accredited Hospitals\", \"Experienced Specialists\", \"All-Inclusive Packages\", \"Transparent Pricing\"], \"whychoose_subtitle\": \"Why Choose Us\", \"whychoose_description\": \"We are a trusted medical tourism provider offering affordable, high-quality treatments in internationally accredited hospitals. Our experienced surgeons, advanced medical technology, and transparent pricing ensure safe and successful outcomes for international patients. From free consultation to post-treatment follow-up, we manage your entire medical journey with professionalism and care, making world-class healthcare accessible and stress-free.\", \"whychoose_counter1_label\": \"9\", \"whychoose_counter2_label\": \"thutd\", \"whychoose_counter3_label\": null, \"whychoose_counter1_number\": \"25\", \"whychoose_counter2_number\": \"47\", \"whychoose_counter3_number\": null}', '{\"steps\": [{\"desc\": \"Share your medical details and photos with our experts.\", \"image\": null, \"title\": \"Free Consultation\"}, {\"desc\": \"We provide a tailored treatment plan and clear pricing.\", \"image\": null, \"title\": \"Personalized Plan\"}, {\"desc\": \"We arrange accommodation, transfers, and hospital booking.\", \"image\": null, \"title\": \"Travel & Treatment\"}, {\"desc\": \"Continuous support even after your treatment.\", \"image\": null, \"title\": \"Recovery & Follow-Up\"}], \"howitwork_title\": \"Working Process\", \"howitwork_subtitle\": \"How It Works\", \"howitwork_button_link\": \"/patient-guide/book-free-consultation\", \"howitwork_button_text\": \"Free Booking\", \"howitwork_description\": null}', NULL, NULL, NULL, '{\"host\": \"sandbox.smtp.mailtrap.io\", \"port\": \"587\", \"password\": \"7d575a8a5f53d7\", \"username\": \"25241bb8de3468\", \"from_name\": \"turkelitemedcare\", \"encryption\": \"\", \"admin_email\": \"salmanazhar8500@gmail.com\", \"from_address\": \"salmanazhar8500@gmail.com\"}', '{\"footer_email\": \"info@domainname.com\", \"footer_phone\": \"+123 456 789\", \"header_phone\": \"(+01) 789 987 645\", \"website_name\": \"Turkelitemedcare\", \"website_tagline\": \"Trusted care for every patient.\", \"footer_copyright\": \"Copyright © 2025 All Rights Reserved.\", \"header_help_text\": \"need help !\", \"footer_about_text\": \"Committed to compassionate care and better outcomes.\", \"footer_phone_label\": \"Toll free customer care\", \"footer_support_label\": \"Need live support!\"}', '2026-02-25 19:28:06', '2026-02-26 17:56:53');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2026_02_24_000001_add_is_admin_to_users_table', 1),
(6, '2026_02_25_000001_create_pages_table', 2),
(7, '2026_02_25_000002_create_services_table', 3),
(8, '2026_02_26_000003_create_home_settings_table', 4),
(9, '2026_02_26_000004_add_section_columns_to_home_settings_table', 5),
(10, '2026_02_26_000005_add_whychoose_data_to_home_settings_table', 6),
(11, '2026_02_26_000006_add_howitwork_data_to_home_settings_table', 7),
(12, '2026_02_26_000007_add_testimonials_gallery_lasthope_to_home_settings_table', 8),
(13, '2026_02_26_000005_add_description_and_image_to_services_table', 9),
(14, '2026_02_26_000008_add_about_page_data_to_home_settings_table', 9),
(15, '2026_02_26_000006_add_detail_content_to_services_table', 10),
(16, '2026_02_27_000009_create_patient_guides_table', 11),
(17, '2026_02_27_000010_add_content_columns_to_patient_guides_table', 12),
(18, '2026_02_27_000011_create_appointments_table', 13),
(19, '2026_02_27_000012_add_mail_data_to_home_settings_table', 13),
(20, '2026_02_27_000013_add_header_footer_data_to_home_settings_table', 14),
(21, '2026_02_27_000014_create_contact_submissions_table', 15);

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patient_guides`
--

CREATE TABLE `patient_guides` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parentid` bigint(20) UNSIGNED DEFAULT NULL,
  `type` enum('main','child','prechild') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'main',
  `detail_content` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `patient_guides`
--

INSERT INTO `patient_guides` (`id`, `name`, `description`, `slug`, `parentid`, `type`, `detail_content`, `created_at`, `updated_at`) VALUES
(1, 'Get a Free Consultation', '<p>Fill our Online Consultation form and our Specialists will contact you as soon as possible. Your photos will undergo a hair analysis and drawn a planning. Our Specialists will contact you as soon as possible to inform you about doctor&rsquo;s consultation and will answer all of your questions.</p>\r\n\r\n<p>If you are eligible for a hair transplant, our Specialist will assist you to book your surgery. You can use the contact number and reach us on whatsapp to get a Free Online Consultation. Your information will be kept confidential and will not be shared with third parties.</p>\r\n\r\n<p><strong>If we haven&rsquo;t answered your question already give us a call at<br />\r\n<a href=\"tel:+90%20537%20376%2069%2019\">+90 537 376 69 19</a></strong></p>', 'book-free-consultation', NULL, 'main', '{\"content_text\": \"\", \"content_title\": \"\", \"sidebar_title\": \"\"}', '2026-02-26 14:47:05', '2026-02-26 15:12:21'),
(2, 'Online Hair Analysis', '<p>Get to know if it will be possible for you to have a hair transplant and get a planning drawing with a Free Online Consultation from Clinic Expert doctors.</p>\r\n\r\n<p>Upload your photos to our Online Consultation Module and our Hair Transplant Specialists will contact you as soon as the Consultation is completed.</p>\r\n\r\n<p><strong>If we haven&rsquo;t answered your question already give us a call at<br />\r\n<a href=\"tel:+905373766919\">+905373766919</a></strong></p>', 'online-hair-analysis', NULL, 'main', '{\"head_image\": null, \"content_text\": \"\", \"content_title\": \"\", \"sidebar_title\": \"\", \"tab_one_label\": \"Online Hair Analysis\", \"tab_one_title\": \"Get a Free Online Hair Analysis\", \"tab_two_label\": \"Booking\", \"tab_two_title\": \"Book Consultation\"}', '2026-02-26 14:47:22', '2026-02-26 15:43:53');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `detail_content` json DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parentid` bigint(20) UNSIGNED DEFAULT NULL,
  `type` enum('main','child','prechild') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'child',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `name`, `description`, `image`, `detail_content`, `slug`, `parentid`, `type`, `created_at`, `updated_at`) VALUES
(2, 'Hair Transplants', NULL, NULL, NULL, 'hair-transplants', NULL, 'main', '2026-02-25 13:42:45', '2026-02-26 13:03:35'),
(4, 'DHI Pro Hair Transplan', '<p>DHI Pro Hair Transplant, hair restoration ki duniya ka ek advanced aur modern solution hai. Ye technique traditional DHI-CHOI method ka upgraded aur improved version hai, jo behtar results aur zyada natural hair growth provide karti hai.</p>\r\n\r\n<p>Turkey ke top scientists aur experienced doctors ne mil kar is technology ko develop kiya hai. Is process mein advanced research, low-level laser technology aur Platelet-Rich Plasma (PRP) ka smart use kiya jata hai, jiska maqsad hair follicles ko activate karna, existing hair ko strong banana aur hair loss ke process ko control karna hai.</p>\r\n\r\n<p>DHI Pro Hair Transplant na sirf naye baalon ki growth ko promote karta hai, balkay lost hair ko wapas lane aur overall hair density ko improve karne mein bhi madad karta hai &mdash; wo bhi safe, effective aur long-lasting results ke sath.</p>', 'site-settings/services/o8HDsfgMv67KObpkAmRRHdGD9cRvcPyuMHxKstxo.jpg', NULL, 'dhi-pro-hair-transplan', 2, 'child', '2026-02-25 14:07:30', '2026-03-01 17:59:55'),
(5, 'DHI Hair Transplant', NULL, NULL, NULL, 'dhi-hair-transplant', 2, 'child', '2026-02-25 14:13:44', '2026-02-25 14:13:44'),
(6, 'FUE Hair Transplant', NULL, NULL, NULL, 'fue-hair-transplant', 2, 'child', '2026-02-25 14:14:15', '2026-02-25 14:14:15'),
(7, 'Unshaven Hair Transplant', NULL, NULL, NULL, 'unshaven-hair-transplant', 2, 'child', '2026-02-25 14:19:13', '2026-02-25 14:19:13'),
(8, 'Women’s Hair Transplant', NULL, NULL, NULL, 'womens-hair-transplant', 2, 'child', '2026-02-25 14:21:57', '2026-02-25 14:21:57'),
(9, 'Beard and Mustache Transplant', NULL, NULL, NULL, 'beard-and-mustache-transplant', 2, 'child', '2026-02-25 14:22:31', '2026-02-25 14:22:31'),
(10, 'Eyebrow Transplant Procedure', NULL, NULL, NULL, 'eyebrow-transplant-procedure', 2, 'child', '2026-02-25 14:24:42', '2026-02-25 14:24:42'),
(11, 'Plastic Surgery', NULL, NULL, NULL, 'plastic-surgery', NULL, 'main', '2026-02-25 14:29:29', '2026-02-25 14:29:29'),
(12, 'Body Contouring Surgery', NULL, NULL, NULL, 'body-contouring-surgery', 11, 'child', '2026-02-25 14:31:39', '2026-02-25 14:31:39'),
(13, 'Abdominoplasty', NULL, NULL, NULL, 'abdominoplasty', 12, 'prechild', '2026-02-25 14:32:36', '2026-02-25 14:32:36'),
(14, 'Body Lift', NULL, NULL, NULL, 'body-lift', 12, 'prechild', '2026-02-25 14:36:57', '2026-02-25 14:36:57'),
(15, 'Buttocks Augmentation', NULL, NULL, NULL, 'buttocks-augmentation', 12, 'prechild', '2026-02-25 14:37:30', '2026-02-25 14:37:30'),
(16, 'Genital Aesthetics', NULL, NULL, NULL, 'genital-aesthetics', 12, 'prechild', '2026-02-25 14:37:51', '2026-02-25 14:37:51'),
(17, 'Liposuction', NULL, NULL, NULL, 'liposuction', 12, 'prechild', '2026-02-25 14:38:09', '2026-02-25 14:38:09'),
(18, 'Mommy Makeover', NULL, NULL, NULL, 'mommy-makeover', 12, 'prechild', '2026-02-25 14:38:27', '2026-02-25 14:38:27'),
(19, 'Breast Aesthetics', NULL, NULL, NULL, 'breast-aesthetics', 11, 'child', '2026-02-25 14:39:01', '2026-02-25 14:39:01'),
(20, 'Breast Asymmetry Correction', NULL, NULL, NULL, 'breast-asymmetry-correction', 19, 'prechild', '2026-02-25 14:39:28', '2026-02-25 14:39:28'),
(21, 'Breast Augmentation', NULL, NULL, NULL, 'breast-augmentation', 19, 'prechild', '2026-02-25 14:40:01', '2026-02-25 14:40:01'),
(22, 'Breast Lift', NULL, NULL, NULL, 'breast-lift', 19, 'prechild', '2026-02-25 14:40:49', '2026-02-25 14:40:49'),
(23, 'Breast Reduction', NULL, NULL, NULL, 'breast-reduction', 19, 'prechild', '2026-02-25 14:41:05', '2026-02-25 14:41:05'),
(24, 'Gynecomastia', NULL, NULL, NULL, 'gynecomastia', 19, 'prechild', '2026-02-25 14:41:21', '2026-02-25 14:41:21'),
(25, 'Facial Plastic Surgery', NULL, NULL, NULL, 'facial-plastic-surgery', 11, 'child', '2026-02-25 14:41:50', '2026-02-25 14:41:50'),
(26, 'Bichectomy Surgery', NULL, NULL, NULL, 'bichectomy-surgery', 25, 'prechild', '2026-02-25 14:42:10', '2026-02-25 14:42:10'),
(27, 'Eyelid Surgery', NULL, NULL, NULL, 'eyelid-surgery', 25, 'prechild', '2026-02-25 14:42:42', '2026-02-25 14:42:42'),
(28, 'Facelift Surgery', NULL, NULL, NULL, 'facelift-surgery', 25, 'prechild', '2026-02-25 14:43:15', '2026-02-25 14:43:15'),
(29, 'Neck Lift', NULL, NULL, NULL, 'neck-lift', 25, 'prechild', '2026-02-25 14:43:33', '2026-02-25 14:43:33'),
(30, 'Ear Correction Surgery (Otoplasty)', NULL, NULL, NULL, 'ear-correction-surgery-otoplasty', 25, 'prechild', '2026-02-25 14:43:48', '2026-02-25 14:43:48'),
(32, 'Nose Surgery', NULL, NULL, NULL, 'nose-surgery', 11, 'child', '2026-02-25 14:48:07', '2026-02-25 14:48:07'),
(33, 'Rhinoplasty', NULL, NULL, NULL, 'rhinoplasty', 32, 'prechild', '2026-02-25 14:48:25', '2026-02-25 14:48:25'),
(34, 'Non-Surgical Aesthetics', NULL, NULL, NULL, 'non-surgical-aesthetics', NULL, 'main', '2026-02-25 14:48:45', '2026-02-25 14:48:45'),
(35, 'Facial Rejuvenation', NULL, NULL, NULL, 'facial-rejuvenation', 34, 'child', '2026-02-25 14:54:16', '2026-02-25 14:54:16'),
(36, 'Filler and Injections', NULL, NULL, NULL, 'filler-and-injections', 34, 'child', '2026-02-25 14:54:31', '2026-02-25 14:54:31'),
(37, 'Non-Surgical Face Lift', NULL, NULL, NULL, 'non-surgical-face-lift', 34, 'child', '2026-02-25 14:54:44', '2026-02-25 14:54:44'),
(38, 'PRP and Mesotherapy', NULL, NULL, NULL, 'prp-and-mesotherapy', 34, 'child', '2026-02-25 14:54:59', '2026-02-25 14:54:59'),
(40, 'Obesity Surgery', NULL, NULL, NULL, 'obesity-surgery', NULL, 'main', '2026-02-25 14:55:35', '2026-02-25 14:55:35'),
(41, 'Gastric Balloon', NULL, NULL, NULL, 'gastric-balloon', 40, 'child', '2026-02-25 14:55:55', '2026-02-25 14:55:55'),
(42, 'Gastric Bypass', NULL, NULL, NULL, 'gastric-bypass', 40, 'child', '2026-02-25 14:56:08', '2026-02-25 14:56:08'),
(43, 'Gastric Sleeve', NULL, NULL, NULL, 'gastric-sleeve', 40, 'child', '2026-02-25 14:56:28', '2026-02-25 14:56:28'),
(44, 'Dental Aesthetics', NULL, NULL, NULL, 'dental-aesthetics', NULL, 'main', '2026-02-25 14:56:55', '2026-02-25 14:56:55'),
(45, 'E-Max Veneers', '<p>When you need a crown on a tooth that appears prominently in your smile, you want to make sure that the crown looks as natural as possible.&nbsp;<strong>E-max veneers are a revolutionary solution for a natural smile.</strong>&nbsp;Based on 100% porcelain, Emax veneers can be used in cases where cutting teeth, cuspid teeth, and small coted teeth.</p>\r\n\r\n<p>This means that they are less likely to crack, chip or break. When cared for properly, they have the potential to last for decades &ndash; and possibly a lifetime! E-max veneers look more natural than traditional porcelain or zirconium because the material is slightly translucent, just like natural tooth enamel. This gives the crown a more natural look. They can also be tinted to match the color of your natural teeth to a point that&nbsp;so even from a close-up, no one will be able to tell that your Emax crown is not a natural tooth!</p>', 'site-settings/services/NxHc6hddmHOBgCcKgKzniKLrtbci5xQ5Rhofra6e.webp', '{\"faqs\": [{\"answer\": \"<p>It is a homogeneous lithium bisilicate porcelain that provides an outstanding aesthetic and precise compatibility for patients with defective teeth. Doctors and patients certify that E-max veneers offer high high-quality outcomes. Moreover, this adjustment allows you to work in accordance with the requirements of conservative dentistry. Depending on the patient&rsquo;s case, restorations may be clad in a very aesthetic way. This is where E-max veneers really shine! Their natural transparency adds an extra level of depth to their natural appearance, allowing them to appear just like real teeth even up close.</p>\\r\\n\\r\\n<p>The material&rsquo;s outstanding performance is based on a combination of excellent bending strength and high fracture toughness adjusted to specific dental requirements, and with E-max, you can offer your patients beautiful restorations that exhibit high mechanical strength. Emax crowns provide more than enough durability for your front teeth and look just like natural teeth, so they are an ideal solution for your front teeth. Not only do they blend into your smile, they actively contribute to the beauty of your smile!</p>\\r\\n\\r\\n<p>Its unique installation also provides a medium flexural strength of 500 MPa. It makes it the ideal high strength solution for anterior and posterior crowns, veneers, inlays, fillings, screw implant crowns, and three-module anterior bridges, or restorations using&nbsp; small dimensional preparation.</p>\", \"question\": \"What Is the Composition of E-Max?\"}, {\"answer\": \"<p>Emax is an excellent combination of beauty as well as strength to repair such teeth, in a way that you will say goodbye to stained teeth with this installation. For those who have stained teeth, these all-porcelain fittings are highly transparent and have excellent resistance to fracture.&nbsp; The crooked front teeth are strong candidates for Emax, and filled teeth are also candidates because they tend to become brittle.</p>\", \"question\": \"Am I Suitable for E-Max Veneers?\"}, {\"answer\": \"<p>One of the best options for restoring your front teeth, long-term success and scientifically documented results.</p>\\r\\n\\r\\n<p>Less likely to crack or break</p>\\r\\n\\r\\n<p>The unsightly gray line of the base mineral will not be visible at the gum lines, and the formula will look exactly like your natural teeth.</p>\\r\\n\\r\\n<p>They are more transparent than zirconia crowns so that they match your natural teeth</p>\\r\\n\\r\\n<p>Ideal for restoration of front teeth in particular.</p>\\r\\n\\r\\n<p>Strength and durability as it is made of lithium disilicate, which is a vitreous ceramic and has excellent strength.</p>\", \"question\": \"Advantages of the E-Max Formula\"}, {\"answer\": \"<p>The formula can be prepared quickly and easily in the dental office.</p>\\r\\n\\r\\n<p>Your dentist will first prepare your teeth by removing a thin layer of the natural tooth structure.</p>\\r\\n\\r\\n<p>Next, an impression will be made of your teeth with the help of an intraoral camera.</p>\\r\\n\\r\\n<p>This installation is then transmitted to a computer that controls the grinding actions. Then your dentist matches the shade of the crown with your natural teeth.</p>\\r\\n\\r\\n<p>Finally, a milling machine cuts the homogeneous mass of the lithium disilicate to produce the desired shape of the prosthesis.</p>\\r\\n\\r\\n<p>Since the formula is highly strong because it is one single block.</p>\\r\\n\\r\\n<p>Next, your dentist will adjust the e-max veneers to your tooth. He will make any subtle adjustments if necessary, and stick it to your teeth.</p>\\r\\n\\r\\n<p>Eventually, you get a great smile with no waiting period or multiple appointments, or unpleasant temporary teeth.</p>\", \"question\": \"How to Install the E-Max Veneers ?\"}, {\"answer\": \"<p>To make their patients happy, Turkish dentists do their best. Hotel stays and online tests are just a few of the options that dentists give to their clients. Affordable and trustworthy, getting help from a&nbsp;<a href=\\\"https://clinicexpert.com/dentist-in-turkey/\\\">dentist in Turkey</a>&nbsp;is available for all budget levels. Facilities with cutting-edge technology allow you to take advantage of high-quality services.</p>\\r\\n\\r\\n<p>For more information, our medical consultant will provide you will all the details you need to know to get a guaranteed experience with Clinicexpert.</p>\", \"question\": \"Why Should You Choose Turkey for E-Max?\"}], \"steps\": [{\"icon\": null, \"title\": \"hello\", \"description\": \"hello\"}], \"cta_text\": null, \"features\": [{\"icon\": \"<svg xmlns=\", \"image\": \"site-settings/services/features/tgCLdla5EvEL1KE9NSATSXZXumlikfHMJzxIaxyi.jpg\", \"title\": \"primary care services\", \"description\": \"primary care services2\"}, {\"icon\": \"<svg xmlns=\", \"image\": \"site-settings/services/features/gfPMfNYeiZjANt68HrcAv3dx34Kbz2xgnKH3uhps.jpg\", \"title\": \"primary care services1\", \"description\": \"primary care services3\"}], \"cta_image\": \"site-settings/services/cta/3fH8JCGPAgnQmykoYPtjBjoxhsUt4L4hN3Ff5BV0.webp\", \"cta_title\": null, \"faq_title\": null, \"highlights\": [\"c\"], \"steps_text\": null, \"faq_heading\": \"Frequently asked questions\", \"steps_title\": null, \"sidebar_title\": null, \"steps_heading\": \"Frequently asked\", \"features_title\": \"Feature\", \"cta_button_text\": \"Book Consulatan\", \"highlights_text\": \"vdcscbasc\", \"highlights_title\": \"kia kas\"}', 'e-max-veneers', NULL, 'main', '2026-02-25 18:10:53', '2026-02-26 14:29:26');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `is_admin`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Salman Azhar', 'salmanazhar8500@gmail.com', NULL, '$2y$10$2Yo61crCssofjWPelod09OQycsTJy9WzGSDmMyAW0czPflCsMhEiG', 1, NULL, '2026-02-24 14:30:08', '2026-02-24 14:30:08');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `appointments_patient_guide_id_foreign` (`patient_guide_id`),
  ADD KEY `appointments_service_id_foreign` (`service_id`);

--
-- Indexes for table `contact_submissions`
--
ALTER TABLE `contact_submissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `home_settings`
--
ALTER TABLE `home_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pages_slug_unique` (`slug`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `patient_guides`
--
ALTER TABLE `patient_guides`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `patient_guides_slug_unique` (`slug`),
  ADD KEY `patient_guides_parentid_foreign` (`parentid`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `services_slug_unique` (`slug`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `contact_submissions`
--
ALTER TABLE `contact_submissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `home_settings`
--
ALTER TABLE `home_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `patient_guides`
--
ALTER TABLE `patient_guides`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_patient_guide_id_foreign` FOREIGN KEY (`patient_guide_id`) REFERENCES `patient_guides` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `appointments_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `patient_guides`
--
ALTER TABLE `patient_guides`
  ADD CONSTRAINT `patient_guides_parentid_foreign` FOREIGN KEY (`parentid`) REFERENCES `patient_guides` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

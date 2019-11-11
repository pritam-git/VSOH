-- phpMyAdmin SQL Dump
-- version 4.6.6
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Erstellungszeit: 25. Jan 2019 um 11:05
-- Server-Version: 5.7.20-0ubuntu0.16.04.1
-- PHP-Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `test1`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `action`
--

CREATE TABLE `action` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(120) COLLATE utf8_bin NOT NULL,
  `resource` varchar(255) COLLATE utf8_bin NOT NULL,
  `controller_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL,
  `is_allowed` tinyint(1) DEFAULT '0',
  `is_action_method` tinyint(1) DEFAULT '0',
  `is_html_view` tinyint(1) DEFAULT '0',
  `is_ajax_view` tinyint(1) DEFAULT '0',
  `is_json_view` tinyint(1) DEFAULT '0',
  `layout` varchar(120) COLLATE utf8_bin DEFAULT NULL,
  `content_partial` varchar(120) COLLATE utf8_bin DEFAULT NULL,
  `canonical_lang` varchar(2) COLLATE utf8_bin DEFAULT NULL,
  `robots` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `action_translation`
--

CREATE TABLE `action_translation` (
  `id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `title` varchar(120) COLLATE utf8_bin DEFAULT NULL,
  `headline` varchar(120) COLLATE utf8_bin DEFAULT NULL,
  `subheadline` varchar(120) COLLATE utf8_bin DEFAULT NULL,
  `keywords` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `description` mediumtext COLLATE utf8_bin,
  `content` mediumtext COLLATE utf8_bin,
  `lang` char(2) COLLATE utf8_bin NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `activation`
--

CREATE TABLE `activation` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `disabled` tinyint(1) DEFAULT '0',
  `activation_code` varchar(32) COLLATE utf8_bin NOT NULL,
  `target` varchar(255) COLLATE utf8_bin NOT NULL,
  `target_id` bigint(20) UNSIGNED NOT NULL,
  `redirect_url` mediumtext COLLATE utf8_bin,
  `remote_ip` varchar(15) COLLATE utf8_bin DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  `used_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `address`
--

CREATE TABLE `address` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_order_id` bigint(20) UNSIGNED NOT NULL,
  `house_type_id` bigint(20) UNSIGNED DEFAULT NULL,
  `etage_id` bigint(20) UNSIGNED DEFAULT NULL,
  `lift` tinyint(1) DEFAULT '0',
  `barrier_free` tinyint(1) DEFAULT '0',
  `room_number` bigint(20) DEFAULT NULL,
  `working_hours_from` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `working_hours_till` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `pause_hours_from` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `pause_hours_till` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `return_of_empties` bigint(20) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `assembly`
--

CREATE TABLE `assembly` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(120) COLLATE utf8_bin NOT NULL,
  `publish_datetime` datetime DEFAULT NULL,
  `fr_media_id` bigint(20) UNSIGNED DEFAULT NULL,
  `de_media_id` bigint(20) UNSIGNED DEFAULT NULL,
  `fr_presentation_media_id` bigint(20) UNSIGNED DEFAULT NULL,
  `de_presentation_media_id` bigint(20) UNSIGNED DEFAULT NULL,
  `published` tinyint(1) DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `assembly_m2n_department`
--

CREATE TABLE `assembly_m2n_department` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `assembly_id` bigint(20) UNSIGNED NOT NULL,
  `department_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `assembly_translation`
--

CREATE TABLE `assembly_translation` (
  `id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `ushort` varchar(120) COLLATE utf8_bin NOT NULL,
  `uname` varchar(120) COLLATE utf8_bin NOT NULL,
  `title` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `description` mediumtext COLLATE utf8_bin,
  `content` mediumtext COLLATE utf8_bin,
  `lang` char(2) COLLATE utf8_bin NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `backend_admin_boxes`
--

CREATE TABLE `backend_admin_boxes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(45) COLLATE utf8_bin NOT NULL,
  `name` varchar(45) COLLATE utf8_bin NOT NULL,
  `position` bigint(20) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `backend_admin_boxes_action`
--

CREATE TABLE `backend_admin_boxes_action` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(120) COLLATE utf8_bin NOT NULL,
  `model_name_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action_id` bigint(20) UNSIGNED NOT NULL,
  `count_not_new` tinyint(1) DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `backend_admin_boxes_action_m2n_backend_admin_boxes`
--

CREATE TABLE `backend_admin_boxes_action_m2n_backend_admin_boxes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `backend_admin_boxes_id` bigint(20) UNSIGNED NOT NULL,
  `backend_admin_boxes_action_id` bigint(20) UNSIGNED NOT NULL,
  `position` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `backend_admin_boxes_action_translation`
--

CREATE TABLE `backend_admin_boxes_action_translation` (
  `id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `name` varchar(120) COLLATE utf8_bin NOT NULL,
  `title` varchar(120) COLLATE utf8_bin NOT NULL,
  `lang` char(2) COLLATE utf8_bin NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `backend_admin_boxes_translation`
--

CREATE TABLE `backend_admin_boxes_translation` (
  `id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `title` varchar(120) COLLATE utf8_bin DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `lang` char(2) COLLATE utf8_bin NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `background_image`
--

CREATE TABLE `background_image` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(120) COLLATE utf8_bin NOT NULL,
  `name` varchar(120) COLLATE utf8_bin NOT NULL,
  `action_id` bigint(20) UNSIGNED NOT NULL,
  `media_image_id` bigint(20) UNSIGNED NOT NULL,
  `background_attachment` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `background_size` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `blog`
--

CREATE TABLE `blog` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(120) COLLATE utf8_bin NOT NULL,
  `publish_datetime` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `blog_translation`
--

CREATE TABLE `blog_translation` (
  `id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `ushort` varchar(120) COLLATE utf8_bin NOT NULL,
  `uname` varchar(120) COLLATE utf8_bin NOT NULL,
  `title` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `description` mediumtext COLLATE utf8_bin,
  `content` mediumtext COLLATE utf8_bin,
  `lang` char(2) COLLATE utf8_bin NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `commission`
--

CREATE TABLE `commission` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(45) COLLATE utf8_bin NOT NULL,
  `name` varchar(45) COLLATE utf8_bin NOT NULL,
  `media_image_id` bigint(20) UNSIGNED NOT NULL,
  `position` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `commission_m2n_media_image`
--

CREATE TABLE `commission_m2n_media_image` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `commission_id` bigint(20) UNSIGNED NOT NULL,
  `media_image_id` bigint(20) UNSIGNED NOT NULL,
  `position` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `commission_translation`
--

CREATE TABLE `commission_translation` (
  `id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `title` varchar(45) COLLATE utf8_bin NOT NULL,
  `description` varchar(120) COLLATE utf8_bin NOT NULL,
  `lang` char(2) COLLATE utf8_bin NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `content_box`
--

CREATE TABLE `content_box` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(120) COLLATE utf8_bin NOT NULL,
  `name` varchar(120) COLLATE utf8_bin NOT NULL,
  `media_image_id` bigint(20) UNSIGNED NOT NULL,
  `rollover_media_image_id` bigint(20) UNSIGNED NOT NULL,
  `action_id` bigint(20) UNSIGNED DEFAULT NULL,
  `position` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `content_box_translation`
--

CREATE TABLE `content_box_translation` (
  `id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `title` varchar(120) COLLATE utf8_bin DEFAULT NULL,
  `content` mediumtext COLLATE utf8_bin,
  `lang` char(2) COLLATE utf8_bin NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `contract_type`
--

CREATE TABLE `contract_type` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(120) COLLATE utf8_bin NOT NULL,
  `name` varchar(120) COLLATE utf8_bin NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `contract_type_translation`
--

CREATE TABLE `contract_type_translation` (
  `id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `title` varchar(120) COLLATE utf8_bin DEFAULT NULL,
  `lang` char(2) COLLATE utf8_bin NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `controller`
--

CREATE TABLE `controller` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(64) COLLATE utf8_bin NOT NULL,
  `resource` varchar(128) COLLATE utf8_bin NOT NULL,
  `module_id` bigint(20) UNSIGNED NOT NULL,
  `description` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `country`
--

CREATE TABLE `country` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `iso_2` varchar(2) COLLATE utf8_bin NOT NULL,
  `iso_3` varchar(3) COLLATE utf8_bin NOT NULL,
  `territory_iso_nr` bigint(20) UNSIGNED DEFAULT NULL,
  `name_local` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `country_translation`
--

CREATE TABLE `country_translation` (
  `id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `lang` char(2) COLLATE utf8_bin NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `country_zone`
--

CREATE TABLE `country_zone` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `country_iso_3` varchar(3) COLLATE utf8_bin NOT NULL,
  `zone_code` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `name_local` varchar(128) COLLATE utf8_bin DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `country_zone_translation`
--

CREATE TABLE `country_zone_translation` (
  `id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `name` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `lang` char(2) COLLATE utf8_bin NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `coupon`
--

CREATE TABLE `coupon` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) COLLATE utf8_bin NOT NULL,
  `value` decimal(10,2) NOT NULL,
  `is_used` tinyint(1) DEFAULT '0',
  `entity_user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `end_of_validity` datetime NOT NULL,
  `original_value` decimal(10,2) NOT NULL,
  `customer_email` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `customer_phone` varchar(70) COLLATE utf8_bin DEFAULT NULL,
  `customer_firstname` varchar(150) COLLATE utf8_bin DEFAULT NULL,
  `customer_lastname` varchar(150) COLLATE utf8_bin DEFAULT NULL,
  `customer_street` varchar(180) COLLATE utf8_bin DEFAULT NULL,
  `customer_street_number` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `customer_zip` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `customer_city` varchar(180) COLLATE utf8_bin DEFAULT NULL,
  `customer_country` varchar(180) COLLATE utf8_bin DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `coupon_history`
--

CREATE TABLE `coupon_history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `coupon_id` bigint(20) UNSIGNED NOT NULL,
  `value` decimal(10,2) NOT NULL,
  `product_order_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cross_sell`
--

CREATE TABLE `cross_sell` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product1_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product2_id` bigint(20) UNSIGNED DEFAULT NULL,
  `counter` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `currency`
--

CREATE TABLE `currency` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `iso_3` varchar(3) COLLATE utf8_bin NOT NULL,
  `iso_nr` bigint(20) DEFAULT NULL,
  `symbol_left` varchar(12) COLLATE utf8_bin DEFAULT NULL,
  `symbol_right` varchar(12) COLLATE utf8_bin DEFAULT NULL,
  `sub_divisor` bigint(20) DEFAULT NULL,
  `sub_symbol_left` varchar(12) COLLATE utf8_bin DEFAULT NULL,
  `sub_symbol_right` varchar(12) COLLATE utf8_bin DEFAULT NULL,
  `thousands_point` varchar(1) COLLATE utf8_bin DEFAULT NULL,
  `decimal_point` varchar(1) COLLATE utf8_bin DEFAULT NULL,
  `decimal_digits` bigint(20) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `currency_translation`
--

CREATE TABLE `currency_translation` (
  `id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `name` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `sub_name` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `lang` char(2) COLLATE utf8_bin NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dates`
--

CREATE TABLE `dates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(120) COLLATE utf8_bin NOT NULL,
  `publish_datetime` datetime DEFAULT NULL,
  `closed_registration_date` date NOT NULL,
  `fr_media_id` bigint(20) UNSIGNED DEFAULT NULL,
  `de_media_id` bigint(20) UNSIGNED DEFAULT NULL,
  `fr_presentation_media_id` bigint(20) UNSIGNED DEFAULT NULL,
  `de_presentation_media_id` bigint(20) UNSIGNED DEFAULT NULL,
  `published` tinyint(1) DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dates_m2n_department`
--

CREATE TABLE `dates_m2n_department` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dates_id` bigint(20) UNSIGNED NOT NULL,
  `department_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dates_participants`
--

CREATE TABLE `dates_participants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `participant_name` varchar(120) COLLATE utf8_bin NOT NULL,
  `date_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dates_translation`
--

CREATE TABLE `dates_translation` (
  `id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `ushort` varchar(120) COLLATE utf8_bin NOT NULL,
  `uname` varchar(120) COLLATE utf8_bin NOT NULL,
  `title` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `description` mediumtext COLLATE utf8_bin,
  `content` mediumtext COLLATE utf8_bin,
  `lang` char(2) COLLATE utf8_bin NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `delivery_time`
--

CREATE TABLE `delivery_time` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `address_id` bigint(20) UNSIGNED NOT NULL,
  `delivery_date` varchar(45) COLLATE utf8_bin NOT NULL,
  `from_hour` varchar(45) COLLATE utf8_bin NOT NULL,
  `till_hour` varchar(45) COLLATE utf8_bin NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `department`
--

CREATE TABLE `department` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(120) COLLATE utf8_bin NOT NULL,
  `name` varchar(120) COLLATE utf8_bin NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `department_translation`
--

CREATE TABLE `department_translation` (
  `id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `title` varchar(120) COLLATE utf8_bin DEFAULT NULL,
  `lang` char(2) COLLATE utf8_bin NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `doctrine_cache`
--

CREATE TABLE `doctrine_cache` (
  `id` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `data` longblob,
  `expire` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `email_template`
--

CREATE TABLE `email_template` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(45) COLLATE utf8_bin NOT NULL,
  `name` varchar(45) COLLATE utf8_bin NOT NULL,
  `html_body_css_style` mediumtext COLLATE utf8_bin,
  `html_paragraph_css_style` mediumtext COLLATE utf8_bin,
  `html_headline_css_style` mediumtext COLLATE utf8_bin,
  `html_data_css_style` mediumtext COLLATE utf8_bin,
  `html_dataline_label_css_style` mediumtext COLLATE utf8_bin,
  `html_dataline_data_css_style` mediumtext COLLATE utf8_bin,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `email_template_m2n_email_template_part`
--

CREATE TABLE `email_template_m2n_email_template_part` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email_template_id` bigint(20) UNSIGNED NOT NULL,
  `email_template_part_id` bigint(20) UNSIGNED NOT NULL,
  `position` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `email_template_part`
--

CREATE TABLE `email_template_part` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(45) COLLATE utf8_bin NOT NULL,
  `name` varchar(45) COLLATE utf8_bin NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `email_template_part_translation`
--

CREATE TABLE `email_template_part_translation` (
  `id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `content_plain` mediumtext COLLATE utf8_bin,
  `content_html` mediumtext COLLATE utf8_bin,
  `lang` char(2) COLLATE utf8_bin NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `email_template_replacement`
--

CREATE TABLE `email_template_replacement` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(120) COLLATE utf8_bin NOT NULL,
  `name` varchar(120) COLLATE utf8_bin NOT NULL,
  `value` mediumtext COLLATE utf8_bin,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `email_template_translation`
--

CREATE TABLE `email_template_translation` (
  `id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `subject` mediumtext COLLATE utf8_bin,
  `content_plain` mediumtext COLLATE utf8_bin,
  `content_html` mediumtext COLLATE utf8_bin,
  `lang` char(2) COLLATE utf8_bin NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `entity`
--

CREATE TABLE `entity` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ch_code` varchar(45) COLLATE utf8_bin NOT NULL,
  `disabled` tinyint(1) DEFAULT '0',
  `disabled_reset_hash` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `role_id` int(10) UNSIGNED NOT NULL,
  `parent_user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `department_id` bigint(20) UNSIGNED DEFAULT NULL,
  `login` varchar(32) COLLATE utf8_bin NOT NULL,
  `password` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `password_reset_hash` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `password_attempt` int(10) UNSIGNED DEFAULT '0',
  `company` varchar(120) COLLATE utf8_bin DEFAULT NULL,
  `salutation_id` bigint(20) UNSIGNED DEFAULT NULL,
  `firstname` varchar(80) COLLATE utf8_bin DEFAULT NULL,
  `lastname` varchar(80) COLLATE utf8_bin DEFAULT NULL,
  `title` varchar(80) COLLATE utf8_bin DEFAULT NULL,
  `street` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `street_number` varchar(8) COLLATE utf8_bin DEFAULT NULL,
  `address_line_1` varchar(80) COLLATE utf8_bin DEFAULT NULL,
  `address_line_2` varchar(80) COLLATE utf8_bin DEFAULT NULL,
  `zip` varchar(16) COLLATE utf8_bin DEFAULT NULL,
  `city` varchar(80) COLLATE utf8_bin DEFAULT NULL,
  `kanton_id` bigint(20) UNSIGNED DEFAULT NULL,
  `country_id` bigint(20) UNSIGNED DEFAULT NULL,
  `contract_type_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sales_statistics_id` bigint(20) UNSIGNED DEFAULT NULL,
  `spoken_language` varchar(2) COLLATE utf8_bin DEFAULT NULL,
  `billing_company` varchar(120) COLLATE utf8_bin DEFAULT NULL,
  `billing_firstname` varchar(80) COLLATE utf8_bin DEFAULT NULL,
  `billing_lastname` varchar(80) COLLATE utf8_bin DEFAULT NULL,
  `billing_street` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `billing_street_number` varchar(8) COLLATE utf8_bin DEFAULT NULL,
  `billing_address_line_1` varchar(80) COLLATE utf8_bin DEFAULT NULL,
  `billing_address_line_2` varchar(80) COLLATE utf8_bin DEFAULT NULL,
  `billing_zip` varchar(16) COLLATE utf8_bin DEFAULT NULL,
  `billing_city` varchar(80) COLLATE utf8_bin DEFAULT NULL,
  `billing_country_id` bigint(20) UNSIGNED DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_bin NOT NULL,
  `mobile` varchar(80) COLLATE utf8_bin DEFAULT NULL,
  `phone` varchar(80) COLLATE utf8_bin DEFAULT NULL,
  `fax` varchar(80) COLLATE utf8_bin DEFAULT NULL,
  `www` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `manager_phone` varchar(80) COLLATE utf8_bin DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `last_logout` datetime DEFAULT NULL,
  `latitude` decimal(15,10) DEFAULT NULL,
  `longitude` decimal(15,10) DEFAULT NULL,
  `activated_at` datetime DEFAULT NULL,
  `media_image_id` bigint(20) UNSIGNED DEFAULT NULL,
  `login_with_http_user_agent` mediumtext COLLATE utf8_bin,
  `login_with_http_accept` mediumtext COLLATE utf8_bin,
  `login_with_http_accept_charset` mediumtext COLLATE utf8_bin,
  `login_with_http_accept_encoding` mediumtext COLLATE utf8_bin,
  `login_with_http_accept_language` mediumtext COLLATE utf8_bin,
  `login_with_remote_addr` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `use_all_paymentservices` tinyint(1) DEFAULT '0',
  `company_name` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `commercial_register_id` varchar(64) COLLATE utf8_bin DEFAULT NULL,
  `vat_tax_id` varchar(64) COLLATE utf8_bin DEFAULT NULL,
  `managing_director` varchar(128) COLLATE utf8_bin DEFAULT NULL,
  `branch` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `contact_name` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `contact_position` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `entity_log`
--

CREATE TABLE `entity_log` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `entity_id` bigint(20) UNSIGNED NOT NULL,
  `entity_log_message_id` bigint(20) UNSIGNED NOT NULL,
  `latitude` decimal(15,10) DEFAULT NULL,
  `longitude` decimal(15,10) DEFAULT NULL,
  `accuracy` decimal(10,5) DEFAULT NULL,
  `altitude` decimal(14,7) DEFAULT NULL,
  `altitude_accuracy` decimal(12,6) DEFAULT NULL,
  `heading` decimal(10,3) DEFAULT NULL,
  `speed` decimal(12,4) DEFAULT NULL,
  `remote_ip` varchar(120) COLLATE utf8_bin DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `entity_log_message`
--

CREATE TABLE `entity_log_message` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(255) COLLATE utf8_bin NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `entity_model_list_config`
--

CREATE TABLE `entity_model_list_config` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(120) COLLATE utf8_bin NOT NULL,
  `model_list_id` bigint(20) UNSIGNED NOT NULL,
  `entity_id` bigint(20) UNSIGNED NOT NULL,
  `rp` smallint(6) DEFAULT NULL,
  `sortname` mediumtext COLLATE utf8_bin,
  `sortorder` varchar(4) COLLATE utf8_bin DEFAULT NULL,
  `qtype` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `query` mediumtext COLLATE utf8_bin,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `etage`
--

CREATE TABLE `etage` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `value` varchar(125) COLLATE utf8_bin NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `events`
--

CREATE TABLE `events` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(45) COLLATE utf8_bin NOT NULL,
  `name` varchar(45) COLLATE utf8_bin NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `faq`
--

CREATE TABLE `faq` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(120) COLLATE utf8_bin NOT NULL,
  `name` varchar(120) COLLATE utf8_bin NOT NULL,
  `disabled` tinyint(1) DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `faq_translation`
--

CREATE TABLE `faq_translation` (
  `id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `question` mediumtext COLLATE utf8_bin NOT NULL,
  `answer` mediumtext COLLATE utf8_bin NOT NULL,
  `lang` char(2) COLLATE utf8_bin NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `flyer`
--

CREATE TABLE `flyer` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(255) COLLATE utf8_bin NOT NULL,
  `media_image_id` bigint(20) UNSIGNED NOT NULL,
  `media_id` bigint(20) UNSIGNED DEFAULT NULL,
  `catalog` tinyint(1) DEFAULT '0',
  `hidden` tinyint(1) DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `flyer_images`
--

CREATE TABLE `flyer_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(45) COLLATE utf8_bin NOT NULL,
  `flyer_id` bigint(20) UNSIGNED NOT NULL,
  `media_image_id` bigint(20) UNSIGNED NOT NULL,
  `position` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `flyer_translation`
--

CREATE TABLE `flyer_translation` (
  `id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `title` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `lang` char(2) COLLATE utf8_bin NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `holiday`
--

CREATE TABLE `holiday` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(120) COLLATE utf8_bin NOT NULL,
  `name` varchar(120) COLLATE utf8_bin NOT NULL,
  `date` date NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `hours`
--

CREATE TABLE `hours` (
  `id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `from_hour` varchar(45) COLLATE utf8_bin NOT NULL,
  `till_hour` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `house_types`
--

CREATE TABLE `house_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `value` varchar(125) COLLATE utf8_bin NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `image_config`
--

CREATE TABLE `image_config` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(120) COLLATE utf8_bin NOT NULL,
  `name` varchar(120) COLLATE utf8_bin NOT NULL,
  `media_image_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `info_page`
--

CREATE TABLE `info_page` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(120) COLLATE utf8_bin NOT NULL,
  `name` varchar(120) COLLATE utf8_bin NOT NULL,
  `info_page_id` bigint(20) UNSIGNED DEFAULT NULL,
  `position` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `info_page_translation`
--

CREATE TABLE `info_page_translation` (
  `id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `keywords` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `description` mediumtext COLLATE utf8_bin,
  `menu_name` varchar(120) COLLATE utf8_bin NOT NULL,
  `title` varchar(120) COLLATE utf8_bin DEFAULT NULL,
  `headline` varchar(120) COLLATE utf8_bin DEFAULT NULL,
  `subheadline` varchar(120) COLLATE utf8_bin DEFAULT NULL,
  `content` mediumtext COLLATE utf8_bin,
  `lang` char(2) COLLATE utf8_bin NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `kanton`
--

CREATE TABLE `kanton` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(120) COLLATE utf8_bin NOT NULL,
  `name` varchar(120) COLLATE utf8_bin NOT NULL,
  `sales_statistics_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `language`
--

CREATE TABLE `language` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `iso_2` varchar(2) COLLATE utf8_bin NOT NULL,
  `country_iso_2` varchar(2) COLLATE utf8_bin DEFAULT NULL,
  `collalte_locale` mediumtext COLLATE utf8_bin,
  `name_local` mediumtext COLLATE utf8_bin NOT NULL,
  `is_sacred` tinyint(1) DEFAULT '0',
  `is_constructed` tinyint(1) DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `language_translation`
--

CREATE TABLE `language_translation` (
  `id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `name` mediumtext COLLATE utf8_bin,
  `lang` char(2) COLLATE utf8_bin NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `media`
--

CREATE TABLE `media` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(255) COLLATE utf8_bin NOT NULL,
  `file_name` varchar(255) COLLATE utf8_bin NOT NULL,
  `entity_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL,
  `media_type_id` bigint(20) UNSIGNED NOT NULL,
  `media_folder_id` bigint(20) UNSIGNED DEFAULT NULL,
  `file_size` bigint(20) UNSIGNED NOT NULL,
  `mime_type` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `channels` smallint(6) DEFAULT NULL,
  `width` bigint(20) UNSIGNED DEFAULT NULL,
  `height` bigint(20) UNSIGNED DEFAULT NULL,
  `author` varchar(120) COLLATE utf8_bin DEFAULT NULL,
  `copyright` varchar(120) COLLATE utf8_bin DEFAULT NULL,
  `latitude` decimal(15,10) DEFAULT NULL,
  `longitude` decimal(15,10) DEFAULT NULL,
  `media_image_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `media_config`
--

CREATE TABLE `media_config` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(120) COLLATE utf8_bin NOT NULL,
  `name` varchar(120) COLLATE utf8_bin NOT NULL,
  `media_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `media_folder`
--

CREATE TABLE `media_folder` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `media_folder_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `media_image_m2n_action`
--

CREATE TABLE `media_image_m2n_action` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(120) COLLATE utf8_bin NOT NULL,
  `name` varchar(120) COLLATE utf8_bin NOT NULL,
  `media_image_id` bigint(20) UNSIGNED NOT NULL,
  `action_id` bigint(20) UNSIGNED NOT NULL,
  `position` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `media_image_m2n_action_translation`
--

CREATE TABLE `media_image_m2n_action_translation` (
  `id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `title` varchar(120) COLLATE utf8_bin DEFAULT NULL,
  `content` mediumtext COLLATE utf8_bin,
  `lang` char(2) COLLATE utf8_bin NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `media_image_m2n_info_page`
--

CREATE TABLE `media_image_m2n_info_page` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `media_image_id` bigint(20) UNSIGNED NOT NULL,
  `info_page_id` bigint(20) UNSIGNED NOT NULL,
  `position` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `media_translation`
--

CREATE TABLE `media_translation` (
  `id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `keywords` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `description` mediumtext COLLATE utf8_bin,
  `lang` char(2) COLLATE utf8_bin NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `media_type`
--

CREATE TABLE `media_type` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(16) COLLATE utf8_bin NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `media_type_translation`
--

CREATE TABLE `media_type_translation` (
  `id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `name` varchar(16) COLLATE utf8_bin DEFAULT NULL,
  `description` mediumtext COLLATE utf8_bin,
  `lang` char(2) COLLATE utf8_bin NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `meta_configuration`
--

CREATE TABLE `meta_configuration` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(120) COLLATE utf8_bin NOT NULL,
  `name` varchar(120) COLLATE utf8_bin NOT NULL,
  `value` mediumtext COLLATE utf8_bin,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `model_column_name`
--

CREATE TABLE `model_column_name` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `model_name_id` bigint(20) UNSIGNED NOT NULL,
  `model_column_name_edit_as_id` bigint(20) UNSIGNED DEFAULT NULL,
  `position` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `model_column_name_edit_as`
--

CREATE TABLE `model_column_name_edit_as` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(128) COLLATE utf8_bin NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `model_column_name_translation`
--

CREATE TABLE `model_column_name_translation` (
  `id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `display` varchar(255) COLLATE utf8_bin NOT NULL,
  `lang` char(2) COLLATE utf8_bin NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `model_list`
--

CREATE TABLE `model_list` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `name_short` varchar(120) COLLATE utf8_bin NOT NULL,
  `default_sort` varchar(255) COLLATE utf8_bin NOT NULL,
  `button_edit` tinyint(1) DEFAULT '0',
  `button_add` tinyint(1) DEFAULT '0',
  `button_delete` tinyint(1) DEFAULT '0',
  `width` bigint(20) NOT NULL,
  `height` bigint(20) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `model_list_column`
--

CREATE TABLE `model_list_column` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `search_like` tinyint(1) DEFAULT '0',
  `width` bigint(20) NOT NULL,
  `model_list_id` bigint(20) UNSIGNED NOT NULL,
  `model_column_name_id` bigint(20) UNSIGNED NOT NULL,
  `model_list_connection_id` bigint(20) UNSIGNED DEFAULT NULL,
  `position` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `model_list_column_export`
--

CREATE TABLE `model_list_column_export` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `model_list_export_id` bigint(20) UNSIGNED NOT NULL,
  `column_name` varchar(120) COLLATE utf8_bin NOT NULL,
  `width` double(18,2) NOT NULL,
  `show_column` tinyint(1) DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `model_list_connection`
--

CREATE TABLE `model_list_connection` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(45) COLLATE utf8_bin NOT NULL,
  `join_on_short` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `name_alias` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `model_name_id` bigint(20) UNSIGNED NOT NULL,
  `replace_with_column` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `is_foreign` tinyint(1) DEFAULT '0',
  `local_key` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `foreign_key` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `model_list_edit_ignore`
--

CREATE TABLE `model_list_edit_ignore` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `model_column_name_id` bigint(20) UNSIGNED NOT NULL,
  `model_list_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `model_list_export`
--

CREATE TABLE `model_list_export` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `model_list_id` bigint(20) UNSIGNED NOT NULL,
  `entity_id` bigint(20) UNSIGNED NOT NULL,
  `export_type` varchar(120) COLLATE utf8_bin DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `model_list_translation`
--

CREATE TABLE `model_list_translation` (
  `id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `title` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `lang` char(2) COLLATE utf8_bin NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `model_list_where`
--

CREATE TABLE `model_list_where` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `value` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `use_like` varchar(255) COLLATE utf8_bin NOT NULL,
  `model_list_id` bigint(20) UNSIGNED NOT NULL,
  `model_column_name_id` bigint(20) UNSIGNED NOT NULL,
  `model_list_connection_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `model_marked_for_editor`
--

CREATE TABLE `model_marked_for_editor` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `model_name_id` bigint(20) UNSIGNED NOT NULL,
  `referenced_id` bigint(20) UNSIGNED NOT NULL,
  `active` tinyint(1) DEFAULT '0',
  `entity_id` bigint(20) UNSIGNED NOT NULL,
  `identifier` varchar(120) COLLATE utf8_bin DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `model_name`
--

CREATE TABLE `model_name` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `module`
--

CREATE TABLE `module` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(64) COLLATE utf8_bin NOT NULL,
  `description` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `navigation`
--

CREATE TABLE `navigation` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `navigation_id` bigint(20) UNSIGNED DEFAULT NULL,
  `short` varchar(255) COLLATE utf8_bin NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `title` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `action_resource` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `role_short` varchar(16) COLLATE utf8_bin NOT NULL,
  `dynamic` varchar(120) COLLATE utf8_bin DEFAULT NULL,
  `visible` tinyint(1) DEFAULT '0',
  `show_all` tinyint(1) DEFAULT '0',
  `show_all_loggedin` tinyint(1) DEFAULT '0',
  `target` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `uri` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `css_class` varchar(120) COLLATE utf8_bin DEFAULT NULL,
  `default_language` varchar(2) COLLATE utf8_bin DEFAULT NULL,
  `do_not_translate` tinyint(1) DEFAULT '0',
  `position` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `news`
--

CREATE TABLE `news` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(120) COLLATE utf8_bin NOT NULL,
  `publish_datetime` datetime DEFAULT NULL,
  `fr_media_id` bigint(20) UNSIGNED DEFAULT NULL,
  `de_media_id` bigint(20) UNSIGNED DEFAULT NULL,
  `fr_presentation_media_id` bigint(20) UNSIGNED DEFAULT NULL,
  `de_presentation_media_id` bigint(20) UNSIGNED DEFAULT NULL,
  `published` tinyint(1) DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `newsletter`
--

CREATE TABLE `newsletter` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(120) COLLATE utf8_bin NOT NULL,
  `name` varchar(120) COLLATE utf8_bin NOT NULL,
  `media_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `newsletter_subscriber`
--

CREATE TABLE `newsletter_subscriber` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `firstname` varchar(120) COLLATE utf8_bin DEFAULT NULL,
  `lastname` varchar(120) COLLATE utf8_bin DEFAULT NULL,
  `lang` varchar(2) COLLATE utf8_bin DEFAULT NULL,
  `salutation_id` bigint(20) UNSIGNED DEFAULT NULL,
  `newsletter_subscriber_type_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `newsletter_subscriber_type`
--

CREATE TABLE `newsletter_subscriber_type` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(120) COLLATE utf8_bin NOT NULL,
  `name` varchar(120) COLLATE utf8_bin NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `newsletter_translation`
--

CREATE TABLE `newsletter_translation` (
  `id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `title` varchar(120) COLLATE utf8_bin NOT NULL,
  `content_plain` mediumtext COLLATE utf8_bin,
  `content_html` mediumtext COLLATE utf8_bin,
  `lang` char(2) COLLATE utf8_bin NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `news_m2n_department`
--

CREATE TABLE `news_m2n_department` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `news_id` bigint(20) UNSIGNED NOT NULL,
  `department_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `news_translation`
--

CREATE TABLE `news_translation` (
  `id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `ushort` varchar(120) COLLATE utf8_bin NOT NULL,
  `uname` varchar(120) COLLATE utf8_bin NOT NULL,
  `title` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `description` mediumtext COLLATE utf8_bin,
  `content` mediumtext COLLATE utf8_bin,
  `lang` char(2) COLLATE utf8_bin NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `otc`
--

CREATE TABLE `otc` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(120) COLLATE utf8_bin NOT NULL,
  `publish_datetime` datetime DEFAULT NULL,
  `fr_media_id` bigint(20) UNSIGNED DEFAULT NULL,
  `de_media_id` bigint(20) UNSIGNED DEFAULT NULL,
  `fr_presentation_media_id` bigint(20) UNSIGNED DEFAULT NULL,
  `de_presentation_media_id` bigint(20) UNSIGNED DEFAULT NULL,
  `published` tinyint(1) DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `otc_m2n_department`
--

CREATE TABLE `otc_m2n_department` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `otc_id` bigint(20) UNSIGNED NOT NULL,
  `department_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `otc_translation`
--

CREATE TABLE `otc_translation` (
  `id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `ushort` varchar(120) COLLATE utf8_bin NOT NULL,
  `uname` varchar(120) COLLATE utf8_bin NOT NULL,
  `title` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `description` mediumtext COLLATE utf8_bin,
  `content` mediumtext COLLATE utf8_bin,
  `lang` char(2) COLLATE utf8_bin NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `param_translator`
--

CREATE TABLE `param_translator` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `param` varchar(255) COLLATE utf8_bin NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `param_translator_translation`
--

CREATE TABLE `param_translator_translation` (
  `id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `uparam` varchar(255) COLLATE utf8_bin NOT NULL,
  `lang` char(2) COLLATE utf8_bin NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `payment_service`
--

CREATE TABLE `payment_service` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(80) COLLATE utf8_bin NOT NULL,
  `name` varchar(80) COLLATE utf8_bin NOT NULL,
  `visible` tinyint(1) DEFAULT '0',
  `percent` smallint(6) DEFAULT NULL,
  `fixed` decimal(10,2) DEFAULT NULL,
  `cant_be_null` tinyint(1) DEFAULT '0',
  `class_name` varchar(120) COLLATE utf8_bin NOT NULL,
  `need_bank_account_infos` tinyint(1) DEFAULT '0',
  `need_creditcard_infos` tinyint(1) DEFAULT '0',
  `has_internal_review` tinyint(1) DEFAULT '0',
  `has_payment_after_order` tinyint(1) DEFAULT '0',
  `payment_after_delivery` tinyint(1) DEFAULT '0',
  `payment_days` bigint(20) UNSIGNED DEFAULT NULL,
  `media_image_id` bigint(20) UNSIGNED DEFAULT NULL,
  `position` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `payment_service_translation`
--

CREATE TABLE `payment_service_translation` (
  `id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `title` varchar(255) COLLATE utf8_bin NOT NULL,
  `content` mediumtext COLLATE utf8_bin,
  `lang` char(2) COLLATE utf8_bin NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `plugin`
--

CREATE TABLE `plugin` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(45) COLLATE utf8_bin NOT NULL,
  `name` varchar(45) COLLATE utf8_bin NOT NULL,
  `description` mediumtext COLLATE utf8_bin NOT NULL,
  `version` varchar(45) COLLATE utf8_bin NOT NULL,
  `author` varchar(255) COLLATE utf8_bin NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `producer`
--

CREATE TABLE `producer` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(45) COLLATE utf8_bin NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `url` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `media_image_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` mediumtext COLLATE utf8_bin,
  `position` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `product`
--

CREATE TABLE `product` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(255) COLLATE utf8_bin NOT NULL,
  `media_image_id` bigint(20) UNSIGNED DEFAULT NULL,
  `media_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_unit_id` bigint(20) UNSIGNED DEFAULT NULL,
  `taxes_id` bigint(20) UNSIGNED NOT NULL,
  `refund_id` bigint(20) UNSIGNED DEFAULT NULL,
  `producer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_number` varchar(25) COLLATE utf8_bin NOT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `unit_count` bigint(20) UNSIGNED DEFAULT NULL,
  `shipping_cost_factor` bigint(20) DEFAULT NULL,
  `top_product` tinyint(1) DEFAULT '0',
  `sold_out` tinyint(1) DEFAULT '0',
  `hidden` tinyint(1) DEFAULT '0',
  `new` tinyint(1) DEFAULT '0',
  `tip` tinyint(1) DEFAULT '0',
  `product_status_id` bigint(20) UNSIGNED DEFAULT NULL,
  `need_media_upload` tinyint(1) DEFAULT '0',
  `delivery_days` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `in_stock` bigint(20) DEFAULT '0',
  `no_stock_change` tinyint(1) DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `products_options`
--

CREATE TABLE `products_options` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `product_options_id` bigint(20) UNSIGNED NOT NULL,
  `position` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `product_group`
--

CREATE TABLE `product_group` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(45) COLLATE utf8_bin NOT NULL,
  `product_group_id` bigint(20) UNSIGNED DEFAULT NULL,
  `media_image_id` bigint(20) UNSIGNED DEFAULT NULL,
  `small_media_image_id` bigint(20) UNSIGNED DEFAULT NULL,
  `show_in_shop` tinyint(1) DEFAULT '0',
  `position` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `product_group_translation`
--

CREATE TABLE `product_group_translation` (
  `id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `description` mediumtext COLLATE utf8_bin,
  `ushort` varchar(120) COLLATE utf8_bin NOT NULL,
  `name` varchar(120) COLLATE utf8_bin NOT NULL,
  `menu_name` varchar(120) COLLATE utf8_bin NOT NULL,
  `title` varchar(120) COLLATE utf8_bin NOT NULL,
  `meta_keywords` varchar(120) COLLATE utf8_bin DEFAULT NULL,
  `meta_title` varchar(120) COLLATE utf8_bin DEFAULT NULL,
  `meta_description` varchar(120) COLLATE utf8_bin DEFAULT NULL,
  `lang` char(2) COLLATE utf8_bin NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `product_information`
--

CREATE TABLE `product_information` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(120) COLLATE utf8_bin NOT NULL,
  `name` varchar(120) COLLATE utf8_bin NOT NULL,
  `position` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `product_information_translation`
--

CREATE TABLE `product_information_translation` (
  `id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `menu_name` varchar(120) COLLATE utf8_bin NOT NULL,
  `headline` varchar(120) COLLATE utf8_bin DEFAULT NULL,
  `subheadline` varchar(120) COLLATE utf8_bin DEFAULT NULL,
  `meta_title` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `meta_keywords` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `meta_description` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `content` mediumtext COLLATE utf8_bin,
  `lang` char(2) COLLATE utf8_bin NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `product_m2n_media_image`
--

CREATE TABLE `product_m2n_media_image` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `media_image_id` bigint(20) UNSIGNED NOT NULL,
  `position` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `product_m2n_product_group`
--

CREATE TABLE `product_m2n_product_group` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `product_group_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `product_m2n_product_option_model`
--

CREATE TABLE `product_m2n_product_option_model` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `product_option_model_id` bigint(20) UNSIGNED NOT NULL,
  `position` bigint(20) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `product_m2n_product_option_model_values`
--

CREATE TABLE `product_m2n_product_option_model_values` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_m2n_product_option_model_id` bigint(20) UNSIGNED NOT NULL,
  `referenced_id` bigint(20) UNSIGNED DEFAULT NULL,
  `disabled` tinyint(1) DEFAULT '0',
  `price` decimal(10,2) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `product_m2n_tag`
--

CREATE TABLE `product_m2n_tag` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `tag_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `product_options`
--

CREATE TABLE `product_options` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(45) COLLATE utf8_bin NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `product_options_amount_liquid`
--

CREATE TABLE `product_options_amount_liquid` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(120) COLLATE utf8_bin NOT NULL,
  `name` varchar(120) COLLATE utf8_bin NOT NULL,
  `position` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `product_options_amount_piece`
--

CREATE TABLE `product_options_amount_piece` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(120) COLLATE utf8_bin NOT NULL,
  `name` varchar(120) COLLATE utf8_bin NOT NULL,
  `position` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `product_options_color`
--

CREATE TABLE `product_options_color` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(120) COLLATE utf8_bin NOT NULL,
  `name` varchar(120) COLLATE utf8_bin NOT NULL,
  `position` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `product_options_form`
--

CREATE TABLE `product_options_form` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(120) COLLATE utf8_bin NOT NULL,
  `name` varchar(120) COLLATE utf8_bin NOT NULL,
  `position` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `product_options_general`
--

CREATE TABLE `product_options_general` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(120) COLLATE utf8_bin NOT NULL,
  `name` varchar(120) COLLATE utf8_bin NOT NULL,
  `position` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `product_options_m2n_product_option_items`
--

CREATE TABLE `product_options_m2n_product_option_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_options_id` bigint(20) UNSIGNED NOT NULL,
  `product_option_items_id` bigint(20) UNSIGNED NOT NULL,
  `position` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `product_options_measure_circumference`
--

CREATE TABLE `product_options_measure_circumference` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(120) COLLATE utf8_bin NOT NULL,
  `name` varchar(120) COLLATE utf8_bin NOT NULL,
  `position` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `product_options_measure_depth`
--

CREATE TABLE `product_options_measure_depth` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(120) COLLATE utf8_bin NOT NULL,
  `name` varchar(120) COLLATE utf8_bin NOT NULL,
  `position` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `product_options_measure_diameter`
--

CREATE TABLE `product_options_measure_diameter` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(120) COLLATE utf8_bin NOT NULL,
  `name` varchar(120) COLLATE utf8_bin NOT NULL,
  `position` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `product_options_measure_height`
--

CREATE TABLE `product_options_measure_height` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(120) COLLATE utf8_bin NOT NULL,
  `name` varchar(120) COLLATE utf8_bin NOT NULL,
  `position` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `product_options_measure_length`
--

CREATE TABLE `product_options_measure_length` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(120) COLLATE utf8_bin NOT NULL,
  `name` varchar(120) COLLATE utf8_bin NOT NULL,
  `position` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `product_options_measure_width`
--

CREATE TABLE `product_options_measure_width` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(120) COLLATE utf8_bin NOT NULL,
  `name` varchar(120) COLLATE utf8_bin NOT NULL,
  `position` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `product_options_size`
--

CREATE TABLE `product_options_size` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(120) COLLATE utf8_bin NOT NULL,
  `name` varchar(120) COLLATE utf8_bin NOT NULL,
  `position` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `product_options_size_clothes_bra_cup`
--

CREATE TABLE `product_options_size_clothes_bra_cup` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(120) COLLATE utf8_bin NOT NULL,
  `name` varchar(120) COLLATE utf8_bin NOT NULL,
  `position` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `product_options_size_clothes_bra_size`
--

CREATE TABLE `product_options_size_clothes_bra_size` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(120) COLLATE utf8_bin NOT NULL,
  `name` varchar(120) COLLATE utf8_bin NOT NULL,
  `position` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `product_options_size_clothes_general`
--

CREATE TABLE `product_options_size_clothes_general` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(120) COLLATE utf8_bin NOT NULL,
  `name` varchar(120) COLLATE utf8_bin NOT NULL,
  `position` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `product_options_size_clothes_girth`
--

CREATE TABLE `product_options_size_clothes_girth` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(120) COLLATE utf8_bin NOT NULL,
  `name` varchar(120) COLLATE utf8_bin NOT NULL,
  `position` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `product_options_size_shoes`
--

CREATE TABLE `product_options_size_shoes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(120) COLLATE utf8_bin NOT NULL,
  `name` varchar(120) COLLATE utf8_bin NOT NULL,
  `position` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `product_options_translation`
--

CREATE TABLE `product_options_translation` (
  `id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `title` varchar(150) COLLATE utf8_bin NOT NULL,
  `sub_title` varchar(150) COLLATE utf8_bin NOT NULL,
  `lang` char(2) COLLATE utf8_bin NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `product_options_weight`
--

CREATE TABLE `product_options_weight` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(120) COLLATE utf8_bin NOT NULL,
  `name` varchar(120) COLLATE utf8_bin NOT NULL,
  `position` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `product_option_items`
--

CREATE TABLE `product_option_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(45) COLLATE utf8_bin NOT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `media_image_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `product_option_items_translation`
--

CREATE TABLE `product_option_items_translation` (
  `id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `name` varchar(150) COLLATE utf8_bin NOT NULL,
  `lang` char(2) COLLATE utf8_bin NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `product_option_model`
--

CREATE TABLE `product_option_model` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(120) COLLATE utf8_bin NOT NULL,
  `name` varchar(120) COLLATE utf8_bin NOT NULL,
  `model_name_id` bigint(20) UNSIGNED NOT NULL,
  `position` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `product_option_model_translation`
--

CREATE TABLE `product_option_model_translation` (
  `id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `title` varchar(150) COLLATE utf8_bin NOT NULL,
  `lang` char(2) COLLATE utf8_bin NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `product_order`
--

CREATE TABLE `product_order` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `entity_user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `company` varchar(120) COLLATE utf8_bin DEFAULT NULL,
  `firstname` varchar(80) COLLATE utf8_bin DEFAULT NULL,
  `lastname` varchar(80) COLLATE utf8_bin DEFAULT NULL,
  `billing_number` varchar(120) COLLATE utf8_bin DEFAULT NULL,
  `sum_price` decimal(7,2) DEFAULT NULL,
  `shipping_costs` decimal(7,2) DEFAULT NULL,
  `payment_costs` decimal(7,2) DEFAULT NULL,
  `billing_street` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `billing_street_number` varchar(8) COLLATE utf8_bin DEFAULT NULL,
  `billing_address_line_1` varchar(80) COLLATE utf8_bin DEFAULT NULL,
  `billing_address_line_2` varchar(80) COLLATE utf8_bin DEFAULT NULL,
  `billing_zip` varchar(16) COLLATE utf8_bin DEFAULT NULL,
  `billing_city` varchar(80) COLLATE utf8_bin DEFAULT NULL,
  `billing_country_id` bigint(20) UNSIGNED DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `phone` varchar(80) COLLATE utf8_bin DEFAULT NULL,
  `mobile` varchar(80) COLLATE utf8_bin DEFAULT NULL,
  `comment` mediumtext COLLATE utf8_bin,
  `payment_service_id` bigint(20) UNSIGNED DEFAULT NULL,
  `state_wait_for_payment` tinyint(1) DEFAULT '0',
  `state_dispose` tinyint(1) DEFAULT '0',
  `state_shipped` tinyint(1) DEFAULT '0',
  `delivery_company` varchar(120) COLLATE utf8_bin DEFAULT NULL,
  `delivery_firstname` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `delivery_lastname` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `delivery_street` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `delivery_street_number` varchar(8) COLLATE utf8_bin DEFAULT NULL,
  `delivery_address_line_1` varchar(80) COLLATE utf8_bin DEFAULT NULL,
  `delivery_address_line_2` varchar(80) COLLATE utf8_bin DEFAULT NULL,
  `delivery_zip` varchar(16) COLLATE utf8_bin DEFAULT NULL,
  `delivery_city` varchar(80) COLLATE utf8_bin DEFAULT NULL,
  `delivery_country_id` bigint(20) UNSIGNED DEFAULT NULL,
  `bank_account_name` varchar(80) COLLATE utf8_bin DEFAULT NULL,
  `bank_account_number` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `bank_account_iban` varchar(120) COLLATE utf8_bin DEFAULT NULL,
  `bank_account_bic` varchar(120) COLLATE utf8_bin DEFAULT NULL,
  `bank_identification_code` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `bank_name` varchar(80) COLLATE utf8_bin DEFAULT NULL,
  `card_number` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `valid_to` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `verification_code` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `coupon_id` bigint(20) UNSIGNED DEFAULT NULL,
  `note` mediumtext COLLATE utf8_bin,
  `intern_comment` mediumtext COLLATE utf8_bin,
  `cancelled` tinyint(1) DEFAULT '0',
  `order_mail_send` tinyint(1) DEFAULT '0',
  `ship_mail_send` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `product_order_item`
--

CREATE TABLE `product_order_item` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_order_id` bigint(20) UNSIGNED NOT NULL,
  `price` decimal(7,2) DEFAULT NULL,
  `quantity` bigint(20) UNSIGNED DEFAULT NULL,
  `taxes_rate` mediumint(8) UNSIGNED DEFAULT NULL,
  `name` mediumtext COLLATE utf8_bin,
  `product_unit_short` mediumtext COLLATE utf8_bin,
  `product_short` varchar(125) COLLATE utf8_bin DEFAULT NULL,
  `refund_value` decimal(7,2) DEFAULT NULL,
  `product_number` varchar(25) COLLATE utf8_bin DEFAULT NULL,
  `media_image_id` bigint(20) UNSIGNED DEFAULT NULL,
  `media_id` bigint(20) UNSIGNED DEFAULT NULL,
  `delivery_date` date DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `product_order_item_units`
--

CREATE TABLE `product_order_item_units` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_order_item_id` bigint(20) UNSIGNED DEFAULT NULL,
  `form_short` mediumtext COLLATE utf8_bin,
  `form_value` mediumtext COLLATE utf8_bin,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `product_rating`
--

CREATE TABLE `product_rating` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `rating_counter` bigint(20) UNSIGNED DEFAULT NULL,
  `average` bigint(20) UNSIGNED DEFAULT NULL,
  `summed_ratings` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `product_status`
--

CREATE TABLE `product_status` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(120) COLLATE utf8_bin NOT NULL,
  `name` varchar(120) COLLATE utf8_bin NOT NULL,
  `color_code` varchar(120) COLLATE utf8_bin DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `product_status_translation`
--

CREATE TABLE `product_status_translation` (
  `id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `info` varchar(120) COLLATE utf8_bin DEFAULT NULL,
  `lang` char(2) COLLATE utf8_bin NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `product_translation`
--

CREATE TABLE `product_translation` (
  `id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `ushort` varchar(255) COLLATE utf8_bin NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `title` varchar(255) COLLATE utf8_bin NOT NULL,
  `subtitle` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `description` mediumtext COLLATE utf8_bin,
  `material` mediumtext COLLATE utf8_bin NOT NULL,
  `meta_title` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `meta_description` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `meta_keywords` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `lang` char(2) COLLATE utf8_bin NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `product_unit`
--

CREATE TABLE `product_unit` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(45) COLLATE utf8_bin NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `product_unit_translation`
--

CREATE TABLE `product_unit_translation` (
  `id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `name` varchar(128) COLLATE utf8_bin NOT NULL,
  `lang` char(2) COLLATE utf8_bin NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `protocol`
--

CREATE TABLE `protocol` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(45) COLLATE utf8_bin NOT NULL,
  `name` varchar(45) COLLATE utf8_bin NOT NULL,
  `commission_id` bigint(20) UNSIGNED NOT NULL,
  `publish_datetime` datetime DEFAULT NULL,
  `fr_media_id` bigint(20) UNSIGNED DEFAULT NULL,
  `de_media_id` bigint(20) UNSIGNED DEFAULT NULL,
  `fr_presentation_media_id` bigint(20) UNSIGNED DEFAULT NULL,
  `de_presentation_media_id` bigint(20) UNSIGNED DEFAULT NULL,
  `published` tinyint(1) DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `protocol_m2n_department`
--

CREATE TABLE `protocol_m2n_department` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `protocol_id` bigint(20) UNSIGNED NOT NULL,
  `department_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `protocol_translation`
--

CREATE TABLE `protocol_translation` (
  `id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `title` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `description` varchar(120) COLLATE utf8_bin DEFAULT NULL,
  `content` mediumtext COLLATE utf8_bin,
  `lang` char(2) COLLATE utf8_bin NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `refund`
--

CREATE TABLE `refund` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(45) COLLATE utf8_bin NOT NULL,
  `value` decimal(10,2) DEFAULT NULL,
  `name` varchar(80) COLLATE utf8_bin DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `remember_media_folder`
--

CREATE TABLE `remember_media_folder` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `model_column_name_id` bigint(20) UNSIGNED NOT NULL,
  `entity_id` bigint(20) UNSIGNED NOT NULL,
  `media_folder_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `resource_translator`
--

CREATE TABLE `resource_translator` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `resource` varchar(255) COLLATE utf8_bin NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `resource_translator_translation`
--

CREATE TABLE `resource_translator_translation` (
  `id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `uresource` varchar(255) COLLATE utf8_bin NOT NULL,
  `lang` char(2) COLLATE utf8_bin NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `role`
--

CREATE TABLE `role` (
  `id` int(10) UNSIGNED NOT NULL,
  `short` varchar(16) COLLATE utf8_bin NOT NULL,
  `role_id` int(10) UNSIGNED DEFAULT NULL,
  `disabled` tinyint(1) DEFAULT '0',
  `default_action_resource` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `role_translation`
--

CREATE TABLE `role_translation` (
  `id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `name` varchar(120) COLLATE utf8_bin NOT NULL,
  `description` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `lang` char(2) COLLATE utf8_bin NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `sales_statistics`
--

CREATE TABLE `sales_statistics` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(45) COLLATE utf8_bin NOT NULL,
  `name` varchar(45) COLLATE utf8_bin NOT NULL,
  `media_image_id` bigint(20) UNSIGNED NOT NULL,
  `position` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `sales_statistics_translation`
--

CREATE TABLE `sales_statistics_translation` (
  `id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `title` varchar(45) COLLATE utf8_bin NOT NULL,
  `description` varchar(120) COLLATE utf8_bin NOT NULL,
  `lang` char(2) COLLATE utf8_bin NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `salutation`
--

CREATE TABLE `salutation` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `disabled` tinyint(1) DEFAULT '0',
  `is_male` tinyint(1) DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `salutation_translation`
--

CREATE TABLE `salutation_translation` (
  `id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `name` varchar(120) COLLATE utf8_bin NOT NULL,
  `lang` char(2) COLLATE utf8_bin NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `shipping_cost`
--

CREATE TABLE `shipping_cost` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(45) COLLATE utf8_bin NOT NULL,
  `name` varchar(150) COLLATE utf8_bin NOT NULL,
  `cost` decimal(10,2) DEFAULT NULL,
  `factor` bigint(20) DEFAULT NULL,
  `from_factor` tinyint(1) DEFAULT '0',
  `till_factor` tinyint(1) DEFAULT '0',
  `equal_factor` tinyint(1) DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `shipping_country`
--

CREATE TABLE `shipping_country` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `country_id` bigint(20) UNSIGNED NOT NULL,
  `costs` decimal(7,2) DEFAULT NULL,
  `consistent_costs` tinyint(1) DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `site_config`
--

CREATE TABLE `site_config` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(120) COLLATE utf8_bin NOT NULL,
  `name` varchar(120) COLLATE utf8_bin NOT NULL,
  `value` mediumtext COLLATE utf8_bin,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `site_rights`
--

CREATE TABLE `site_rights` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(45) COLLATE utf8_bin NOT NULL,
  `name` varchar(45) COLLATE utf8_bin NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `site_rights_translation`
--

CREATE TABLE `site_rights_translation` (
  `id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `email_text_plain` mediumtext COLLATE utf8_bin,
  `email_text_html` mediumtext COLLATE utf8_bin,
  `website_text_html` mediumtext COLLATE utf8_bin,
  `lang` char(2) COLLATE utf8_bin NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tag`
--

CREATE TABLE `tag` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(120) COLLATE utf8_bin NOT NULL,
  `name` varchar(120) COLLATE utf8_bin NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `taxes`
--

CREATE TABLE `taxes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `value` smallint(5) UNSIGNED NOT NULL,
  `name` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `team`
--

CREATE TABLE `team` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(120) COLLATE utf8_bin NOT NULL,
  `name` varchar(120) COLLATE utf8_bin NOT NULL,
  `media_image_id` bigint(20) UNSIGNED DEFAULT NULL,
  `position` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `team_translation`
--

CREATE TABLE `team_translation` (
  `id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `title` varchar(120) COLLATE utf8_bin DEFAULT NULL,
  `content` mediumtext COLLATE utf8_bin,
  `lang` char(2) COLLATE utf8_bin NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `temporary_offer`
--

CREATE TABLE `temporary_offer` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `from_date` datetime NOT NULL,
  `till_date` datetime NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `territory`
--

CREATE TABLE `territory` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `iso_nr` bigint(20) UNSIGNED NOT NULL,
  `territory_iso_nr` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `territory_translation`
--

CREATE TABLE `territory_translation` (
  `id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `lang` char(2) COLLATE utf8_bin NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tinymce_template`
--

CREATE TABLE `tinymce_template` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(120) COLLATE utf8_bin NOT NULL,
  `name` varchar(120) COLLATE utf8_bin NOT NULL,
  `content` mediumtext COLLATE utf8_bin NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `translator`
--

CREATE TABLE `translator` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(255) COLLATE utf8_bin NOT NULL,
  `untranslated` tinyint(1) DEFAULT '0',
  `comment` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `translator_translation`
--

CREATE TABLE `translator_translation` (
  `id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `text` mediumtext COLLATE utf8_bin,
  `lang` char(2) COLLATE utf8_bin NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `action`
--
ALTER TABLE `action`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `resource` (`resource`),
  ADD KEY `controller_id_idx_idx` (`controller_id`),
  ADD KEY `name_idx_idx` (`name`),
  ADD KEY `role_id_idx_idx` (`role_id`);

--
-- Indizes für die Tabelle `action_translation`
--
ALTER TABLE `action_translation`
  ADD PRIMARY KEY (`id`,`lang`);

--
-- Indizes für die Tabelle `activation`
--
ALTER TABLE `activation`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `activation_code` (`activation_code`);

--
-- Indizes für die Tabelle `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`id`),
  ADD KEY `etage_id_idx` (`etage_id`),
  ADD KEY `house_type_id_idx` (`house_type_id`),
  ADD KEY `product_order_id_idx` (`product_order_id`);

--
-- Indizes für die Tabelle `assembly`
--
ALTER TABLE `assembly`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`),
  ADD KEY `fr_media_id_idx_idx` (`fr_media_id`),
  ADD KEY `de_media_id_idx_idx` (`de_media_id`),
  ADD KEY `fr_presentation_media_id_idx_idx` (`fr_presentation_media_id`),
  ADD KEY `de_presentation_media_id_idx_idx` (`de_presentation_media_id`);

--
-- Indizes für die Tabelle `assembly_m2n_department`
--
ALTER TABLE `assembly_m2n_department`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assembly_id_idx_idx` (`assembly_id`),
  ADD KEY `department_id_idx_idx` (`department_id`);

--
-- Indizes für die Tabelle `assembly_translation`
--
ALTER TABLE `assembly_translation`
  ADD PRIMARY KEY (`id`,`lang`);

--
-- Indizes für die Tabelle `backend_admin_boxes`
--
ALTER TABLE `backend_admin_boxes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`);

--
-- Indizes für die Tabelle `backend_admin_boxes_action`
--
ALTER TABLE `backend_admin_boxes_action`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`),
  ADD KEY `model_name_id_idx` (`model_name_id`),
  ADD KEY `action_id_idx_idx` (`action_id`);

--
-- Indizes für die Tabelle `backend_admin_boxes_action_m2n_backend_admin_boxes`
--
ALTER TABLE `backend_admin_boxes_action_m2n_backend_admin_boxes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `backend_admin_boxes_action_id_idx` (`backend_admin_boxes_action_id`),
  ADD KEY `backend_admin_boxes_id_idx` (`backend_admin_boxes_id`),
  ADD KEY `position_idx_idx` (`position`);

--
-- Indizes für die Tabelle `backend_admin_boxes_action_translation`
--
ALTER TABLE `backend_admin_boxes_action_translation`
  ADD PRIMARY KEY (`id`,`lang`);

--
-- Indizes für die Tabelle `backend_admin_boxes_translation`
--
ALTER TABLE `backend_admin_boxes_translation`
  ADD PRIMARY KEY (`id`,`lang`);

--
-- Indizes für die Tabelle `background_image`
--
ALTER TABLE `background_image`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`),
  ADD KEY `media_image_id_idx_idx` (`media_image_id`),
  ADD KEY `action_id_idx_idx` (`action_id`);

--
-- Indizes für die Tabelle `blog`
--
ALTER TABLE `blog`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`);

--
-- Indizes für die Tabelle `blog_translation`
--
ALTER TABLE `blog_translation`
  ADD PRIMARY KEY (`id`,`lang`);

--
-- Indizes für die Tabelle `commission`
--
ALTER TABLE `commission`
  ADD PRIMARY KEY (`id`),
  ADD KEY `media_image_id_idx_idx` (`media_image_id`);

--
-- Indizes für die Tabelle `commission_m2n_media_image`
--
ALTER TABLE `commission_m2n_media_image`
  ADD PRIMARY KEY (`id`),
  ADD KEY `commission_id_idx_idx` (`commission_id`),
  ADD KEY `media_image_id_idx_idx` (`media_image_id`);

--
-- Indizes für die Tabelle `commission_translation`
--
ALTER TABLE `commission_translation`
  ADD PRIMARY KEY (`id`,`lang`);

--
-- Indizes für die Tabelle `content_box`
--
ALTER TABLE `content_box`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`),
  ADD KEY `media_image_id_idx_idx` (`media_image_id`),
  ADD KEY `action_id_idx_idx` (`action_id`),
  ADD KEY `position_idx_idx` (`position`),
  ADD KEY `rollover_media_image_id_idx_idx` (`rollover_media_image_id`);

--
-- Indizes für die Tabelle `content_box_translation`
--
ALTER TABLE `content_box_translation`
  ADD PRIMARY KEY (`id`,`lang`);

--
-- Indizes für die Tabelle `contract_type`
--
ALTER TABLE `contract_type`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`);

--
-- Indizes für die Tabelle `contract_type_translation`
--
ALTER TABLE `contract_type_translation`
  ADD PRIMARY KEY (`id`,`lang`);

--
-- Indizes für die Tabelle `controller`
--
ALTER TABLE `controller`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `resource` (`resource`),
  ADD KEY `module_id_idx_idx` (`module_id`),
  ADD KEY `name_idx_idx` (`name`);

--
-- Indizes für die Tabelle `country`
--
ALTER TABLE `country`
  ADD PRIMARY KEY (`id`),
  ADD KEY `territory_iso_nr_idx_idx` (`territory_iso_nr`),
  ADD KEY `iso_2_idx` (`iso_2`),
  ADD KEY `iso_3_idx` (`iso_3`);

--
-- Indizes für die Tabelle `country_translation`
--
ALTER TABLE `country_translation`
  ADD PRIMARY KEY (`id`,`lang`);

--
-- Indizes für die Tabelle `country_zone`
--
ALTER TABLE `country_zone`
  ADD PRIMARY KEY (`id`),
  ADD KEY `country_iso_3_idx_idx` (`country_iso_3`);

--
-- Indizes für die Tabelle `country_zone_translation`
--
ALTER TABLE `country_zone_translation`
  ADD PRIMARY KEY (`id`,`lang`);

--
-- Indizes für die Tabelle `coupon`
--
ALTER TABLE `coupon`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `user_id_idx` (`entity_user_id`);

--
-- Indizes für die Tabelle `coupon_history`
--
ALTER TABLE `coupon_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_order_id_idx` (`product_order_id`),
  ADD KEY `coupon_id_idx` (`coupon_id`);

--
-- Indizes für die Tabelle `cross_sell`
--
ALTER TABLE `cross_sell`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product1_id_idx` (`product1_id`),
  ADD KEY `product2_id_idx` (`product2_id`);

--
-- Indizes für die Tabelle `currency`
--
ALTER TABLE `currency`
  ADD PRIMARY KEY (`id`),
  ADD KEY `iso_3_idx_idx` (`iso_3`),
  ADD KEY `iso_nr_idx_idx` (`iso_nr`);

--
-- Indizes für die Tabelle `currency_translation`
--
ALTER TABLE `currency_translation`
  ADD PRIMARY KEY (`id`,`lang`);

--
-- Indizes für die Tabelle `dates`
--
ALTER TABLE `dates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`),
  ADD KEY `fr_media_id_idx_idx` (`fr_media_id`),
  ADD KEY `de_media_id_idx_idx` (`de_media_id`),
  ADD KEY `fr_presentation_media_id_idx_idx` (`fr_presentation_media_id`),
  ADD KEY `de_presentation_media_id_idx_idx` (`de_presentation_media_id`);

--
-- Indizes für die Tabelle `dates_m2n_department`
--
ALTER TABLE `dates_m2n_department`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dates_id_idx_idx` (`dates_id`),
  ADD KEY `department_id_idx_idx` (`department_id`);

--
-- Indizes für die Tabelle `dates_participants`
--
ALTER TABLE `dates_participants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id_idx_idx` (`user_id`),
  ADD KEY `date_id_idx_idx` (`date_id`);

--
-- Indizes für die Tabelle `dates_translation`
--
ALTER TABLE `dates_translation`
  ADD PRIMARY KEY (`id`,`lang`);

--
-- Indizes für die Tabelle `delivery_time`
--
ALTER TABLE `delivery_time`
  ADD PRIMARY KEY (`id`),
  ADD KEY `address_id_idx` (`address_id`);

--
-- Indizes für die Tabelle `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`);

--
-- Indizes für die Tabelle `department_translation`
--
ALTER TABLE `department_translation`
  ADD PRIMARY KEY (`id`,`lang`);

--
-- Indizes für die Tabelle `doctrine_cache`
--
ALTER TABLE `doctrine_cache`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `email_template`
--
ALTER TABLE `email_template`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`);

--
-- Indizes für die Tabelle `email_template_m2n_email_template_part`
--
ALTER TABLE `email_template_m2n_email_template_part`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email_template_id_idx` (`email_template_id`),
  ADD KEY `email_template_part_id_idx` (`email_template_part_id`),
  ADD KEY `position_idx_idx` (`position`);

--
-- Indizes für die Tabelle `email_template_part`
--
ALTER TABLE `email_template_part`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`);

--
-- Indizes für die Tabelle `email_template_part_translation`
--
ALTER TABLE `email_template_part_translation`
  ADD PRIMARY KEY (`id`,`lang`);

--
-- Indizes für die Tabelle `email_template_replacement`
--
ALTER TABLE `email_template_replacement`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`);

--
-- Indizes für die Tabelle `email_template_translation`
--
ALTER TABLE `email_template_translation`
  ADD PRIMARY KEY (`id`,`lang`);

--
-- Indizes für die Tabelle `entity`
--
ALTER TABLE `entity`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login` (`login`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `disabled_idx` (`disabled`),
  ADD KEY `password_idx` (`password`),
  ADD KEY `firstname_idx` (`firstname`),
  ADD KEY `lastname_idx` (`lastname`),
  ADD KEY `street_idx` (`street`),
  ADD KEY `zip_idx` (`zip`),
  ADD KEY `city_idx` (`city`),
  ADD KEY `country_idx` (`country_id`),
  ADD KEY `phone_idx` (`phone`),
  ADD KEY `fax_idx` (`fax`),
  ADD KEY `www_idx` (`www`),
  ADD KEY `media_image_id_idx` (`media_image_id`),
  ADD KEY `role_id_idx_idx` (`role_id`),
  ADD KEY `salutation_id_idx_idx` (`salutation_id`),
  ADD KEY `billing_country_id_idx_idx` (`billing_country_id`),
  ADD KEY `parent_user_id_idx_idx` (`parent_user_id`),
  ADD KEY `department_id_idx_idx` (`department_id`),
  ADD KEY `kanton_idx` (`kanton_id`),
  ADD KEY `contract_type_id_idx_idx` (`contract_type_id`),
  ADD KEY `sales_statistics_id_idx_idx` (`sales_statistics_id`),
  ADD KEY `entity_role_id_idx` (`role_id`);

--
-- Indizes für die Tabelle `entity_log`
--
ALTER TABLE `entity_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `entity_log_message_id_idx_idx` (`entity_log_message_id`),
  ADD KEY `entity_id_idx_idx` (`entity_id`);

--
-- Indizes für die Tabelle `entity_log_message`
--
ALTER TABLE `entity_log_message`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`);

--
-- Indizes für die Tabelle `entity_model_list_config`
--
ALTER TABLE `entity_model_list_config`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`),
  ADD KEY `model_list_id_idx_idx` (`model_list_id`),
  ADD KEY `entity_id_idx_idx` (`entity_id`);

--
-- Indizes für die Tabelle `etage`
--
ALTER TABLE `etage`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `faq`
--
ALTER TABLE `faq`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`);

--
-- Indizes für die Tabelle `faq_translation`
--
ALTER TABLE `faq_translation`
  ADD PRIMARY KEY (`id`,`lang`);

--
-- Indizes für die Tabelle `flyer`
--
ALTER TABLE `flyer`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`),
  ADD KEY `media_image_idx_idx` (`media_image_id`),
  ADD KEY `media_id_idx_idx` (`media_id`);

--
-- Indizes für die Tabelle `flyer_images`
--
ALTER TABLE `flyer_images`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`),
  ADD KEY `flyer_id_idx` (`flyer_id`),
  ADD KEY `media_image_id_idx` (`media_image_id`),
  ADD KEY `position_idx_idx` (`position`);

--
-- Indizes für die Tabelle `flyer_translation`
--
ALTER TABLE `flyer_translation`
  ADD PRIMARY KEY (`id`,`lang`);

--
-- Indizes für die Tabelle `holiday`
--
ALTER TABLE `holiday`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`);

--
-- Indizes für die Tabelle `hours`
--
ALTER TABLE `hours`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `house_types`
--
ALTER TABLE `house_types`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `image_config`
--
ALTER TABLE `image_config`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`),
  ADD KEY `media_image_id_idx_idx` (`media_image_id`);

--
-- Indizes für die Tabelle `info_page`
--
ALTER TABLE `info_page`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`),
  ADD KEY `position_idx_idx` (`position`),
  ADD KEY `info_page_id_idx_idx` (`info_page_id`);

--
-- Indizes für die Tabelle `info_page_translation`
--
ALTER TABLE `info_page_translation`
  ADD PRIMARY KEY (`id`,`lang`);

--
-- Indizes für die Tabelle `kanton`
--
ALTER TABLE `kanton`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`),
  ADD KEY `sales_statistics_id_idx_idx` (`sales_statistics_id`);

--
-- Indizes für die Tabelle `language`
--
ALTER TABLE `language`
  ADD PRIMARY KEY (`id`),
  ADD KEY `country_iso_2_idx_idx` (`country_iso_2`),
  ADD KEY `iso_2_idx` (`iso_2`);

--
-- Indizes für die Tabelle `language_translation`
--
ALTER TABLE `language_translation`
  ADD PRIMARY KEY (`id`,`lang`);

--
-- Indizes für die Tabelle `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`),
  ADD KEY `media_folder_id_idx_idx` (`media_folder_id`),
  ADD KEY `media_type_id_idx_idx` (`media_type_id`),
  ADD KEY `role_id_idx_idx` (`role_id`),
  ADD KEY `entity_id_idx_idx` (`entity_id`),
  ADD KEY `width_idx` (`width`),
  ADD KEY `height_idx` (`height`),
  ADD KEY `media_image_id_idx` (`media_image_id`);

--
-- Indizes für die Tabelle `media_config`
--
ALTER TABLE `media_config`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`),
  ADD KEY `media_id_idx_idx` (`media_id`);

--
-- Indizes für die Tabelle `media_folder`
--
ALTER TABLE `media_folder`
  ADD PRIMARY KEY (`id`),
  ADD KEY `media_folder_id_idx_idx` (`media_folder_id`);

--
-- Indizes für die Tabelle `media_image_m2n_action`
--
ALTER TABLE `media_image_m2n_action`
  ADD PRIMARY KEY (`id`),
  ADD KEY `media_image_id_idx_idx` (`media_image_id`),
  ADD KEY `action_id_idx_idx` (`action_id`),
  ADD KEY `position_idx_idx` (`position`);

--
-- Indizes für die Tabelle `media_image_m2n_action_translation`
--
ALTER TABLE `media_image_m2n_action_translation`
  ADD PRIMARY KEY (`id`,`lang`);

--
-- Indizes für die Tabelle `media_image_m2n_info_page`
--
ALTER TABLE `media_image_m2n_info_page`
  ADD PRIMARY KEY (`id`),
  ADD KEY `info_page_id_idx_idx` (`info_page_id`),
  ADD KEY `media_image_id_idx_idx` (`media_image_id`),
  ADD KEY `position_idx_idx` (`position`);

--
-- Indizes für die Tabelle `media_translation`
--
ALTER TABLE `media_translation`
  ADD PRIMARY KEY (`id`,`lang`);

--
-- Indizes für die Tabelle `media_type`
--
ALTER TABLE `media_type`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`);

--
-- Indizes für die Tabelle `media_type_translation`
--
ALTER TABLE `media_type_translation`
  ADD PRIMARY KEY (`id`,`lang`);

--
-- Indizes für die Tabelle `meta_configuration`
--
ALTER TABLE `meta_configuration`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`);

--
-- Indizes für die Tabelle `model_column_name`
--
ALTER TABLE `model_column_name`
  ADD PRIMARY KEY (`id`),
  ADD KEY `model_column_id_idx` (`model_name_id`),
  ADD KEY `model_column_name_edit_as_id_idx` (`model_column_name_edit_as_id`),
  ADD KEY `position_idx_idx` (`position`);

--
-- Indizes für die Tabelle `model_column_name_edit_as`
--
ALTER TABLE `model_column_name_edit_as`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `model_column_name_translation`
--
ALTER TABLE `model_column_name_translation`
  ADD PRIMARY KEY (`id`,`lang`);

--
-- Indizes für die Tabelle `model_list`
--
ALTER TABLE `model_list`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `model_list_column`
--
ALTER TABLE `model_list_column`
  ADD PRIMARY KEY (`id`),
  ADD KEY `model_list_id_idx` (`model_list_id`),
  ADD KEY `model_column_name_id_idx` (`model_column_name_id`),
  ADD KEY `model_list_connction_id_idx` (`model_list_connection_id`),
  ADD KEY `position_idx_idx` (`position`);

--
-- Indizes für die Tabelle `model_list_column_export`
--
ALTER TABLE `model_list_column_export`
  ADD PRIMARY KEY (`id`),
  ADD KEY `model_list_export_id_idx_idx` (`model_list_export_id`);

--
-- Indizes für die Tabelle `model_list_connection`
--
ALTER TABLE `model_list_connection`
  ADD PRIMARY KEY (`id`),
  ADD KEY `model_name_id_idx` (`model_name_id`);

--
-- Indizes für die Tabelle `model_list_edit_ignore`
--
ALTER TABLE `model_list_edit_ignore`
  ADD PRIMARY KEY (`id`),
  ADD KEY `model_column_name_id_idx` (`model_column_name_id`),
  ADD KEY `model_list_id_idx` (`model_list_id`);

--
-- Indizes für die Tabelle `model_list_export`
--
ALTER TABLE `model_list_export`
  ADD PRIMARY KEY (`id`),
  ADD KEY `model_list_id_idx_idx` (`model_list_id`),
  ADD KEY `entity_id_idx_idx` (`entity_id`);

--
-- Indizes für die Tabelle `model_list_translation`
--
ALTER TABLE `model_list_translation`
  ADD PRIMARY KEY (`id`,`lang`);

--
-- Indizes für die Tabelle `model_list_where`
--
ALTER TABLE `model_list_where`
  ADD PRIMARY KEY (`id`),
  ADD KEY `model_list_id_idx` (`model_list_id`),
  ADD KEY `model_column_name_id_idx` (`model_column_name_id`),
  ADD KEY `model_list_connction_id_idx` (`model_list_connection_id`);

--
-- Indizes für die Tabelle `model_marked_for_editor`
--
ALTER TABLE `model_marked_for_editor`
  ADD PRIMARY KEY (`id`),
  ADD KEY `entity_id_idx_idx` (`entity_id`),
  ADD KEY `model_name_id_idx_idx` (`model_name_id`);

--
-- Indizes für die Tabelle `model_name`
--
ALTER TABLE `model_name`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `module`
--
ALTER TABLE `module`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indizes für die Tabelle `navigation`
--
ALTER TABLE `navigation`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`),
  ADD KEY `navigation_id_idx_idx` (`navigation_id`),
  ADD KEY `role_short_idx_idx` (`role_short`),
  ADD KEY `position_idx_idx` (`position`),
  ADD KEY `action_resource_idx_idx` (`action_resource`);

--
-- Indizes für die Tabelle `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`),
  ADD KEY `fr_media_id_idx_idx` (`fr_media_id`),
  ADD KEY `de_media_id_idx_idx` (`de_media_id`),
  ADD KEY `fr_presentation_media_id_idx_idx` (`fr_presentation_media_id`),
  ADD KEY `de_presentation_media_id_idx_idx` (`de_presentation_media_id`);

--
-- Indizes für die Tabelle `newsletter`
--
ALTER TABLE `newsletter`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`),
  ADD KEY `media_id_idx_idx` (`media_id`);

--
-- Indizes für die Tabelle `newsletter_subscriber`
--
ALTER TABLE `newsletter_subscriber`
  ADD PRIMARY KEY (`id`),
  ADD KEY `newsletter_subscriber_type_id_idx_idx` (`newsletter_subscriber_type_id`),
  ADD KEY `salutation_id_idx_idx` (`salutation_id`);

--
-- Indizes für die Tabelle `newsletter_subscriber_type`
--
ALTER TABLE `newsletter_subscriber_type`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`);

--
-- Indizes für die Tabelle `newsletter_translation`
--
ALTER TABLE `newsletter_translation`
  ADD PRIMARY KEY (`id`,`lang`);

--
-- Indizes für die Tabelle `news_m2n_department`
--
ALTER TABLE `news_m2n_department`
  ADD PRIMARY KEY (`id`),
  ADD KEY `news_id_idx_idx` (`news_id`),
  ADD KEY `department_id_idx_idx` (`department_id`);

--
-- Indizes für die Tabelle `news_translation`
--
ALTER TABLE `news_translation`
  ADD PRIMARY KEY (`id`,`lang`);

--
-- Indizes für die Tabelle `otc`
--
ALTER TABLE `otc`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`),
  ADD KEY `fr_media_id_idx_idx` (`fr_media_id`),
  ADD KEY `de_media_id_idx_idx` (`de_media_id`),
  ADD KEY `fr_presentation_media_id_idx_idx` (`fr_presentation_media_id`),
  ADD KEY `de_presentation_media_id_idx_idx` (`de_presentation_media_id`);

--
-- Indizes für die Tabelle `otc_m2n_department`
--
ALTER TABLE `otc_m2n_department`
  ADD PRIMARY KEY (`id`),
  ADD KEY `otc_id_idx_idx` (`otc_id`),
  ADD KEY `department_id_idx_idx` (`department_id`);

--
-- Indizes für die Tabelle `otc_translation`
--
ALTER TABLE `otc_translation`
  ADD PRIMARY KEY (`id`,`lang`);

--
-- Indizes für die Tabelle `param_translator`
--
ALTER TABLE `param_translator`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `param` (`param`);

--
-- Indizes für die Tabelle `param_translator_translation`
--
ALTER TABLE `param_translator_translation`
  ADD PRIMARY KEY (`id`,`lang`);

--
-- Indizes für die Tabelle `payment_service`
--
ALTER TABLE `payment_service`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`),
  ADD KEY `media_image_id_idx_idx` (`media_image_id`);

--
-- Indizes für die Tabelle `payment_service_translation`
--
ALTER TABLE `payment_service_translation`
  ADD PRIMARY KEY (`id`,`lang`);

--
-- Indizes für die Tabelle `plugin`
--
ALTER TABLE `plugin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`);

--
-- Indizes für die Tabelle `producer`
--
ALTER TABLE `producer`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`),
  ADD KEY `media_image_id_idx_idx` (`media_image_id`);

--
-- Indizes für die Tabelle `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`),
  ADD KEY `media_image_id_idx` (`media_image_id`),
  ADD KEY `product_unit_id_idx` (`product_unit_id`),
  ADD KEY `taxes_id_idx` (`taxes_id`),
  ADD KEY `refund_id_idx` (`refund_id`),
  ADD KEY `producer_id_idx` (`producer_id`),
  ADD KEY `media_id_idx_idx` (`media_id`),
  ADD KEY `product_status_id_idx_idx` (`product_status_id`);

--
-- Indizes für die Tabelle `products_options`
--
ALTER TABLE `products_options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id_idx_idx` (`product_id`),
  ADD KEY `product_options_id_idx_idx` (`product_options_id`);

--
-- Indizes für die Tabelle `product_group`
--
ALTER TABLE `product_group`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`),
  ADD KEY `product_group_id_idx` (`product_group_id`),
  ADD KEY `media_image_id_idx` (`media_image_id`),
  ADD KEY `position_idx_idx` (`position`),
  ADD KEY `small_media_image_id_idx_idx` (`small_media_image_id`);

--
-- Indizes für die Tabelle `product_group_translation`
--
ALTER TABLE `product_group_translation`
  ADD PRIMARY KEY (`id`,`lang`);

--
-- Indizes für die Tabelle `product_information`
--
ALTER TABLE `product_information`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `product_information_translation`
--
ALTER TABLE `product_information_translation`
  ADD PRIMARY KEY (`id`,`lang`);

--
-- Indizes für die Tabelle `product_m2n_media_image`
--
ALTER TABLE `product_m2n_media_image`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id_idx` (`product_id`),
  ADD KEY `media_image_id_idx` (`media_image_id`);

--
-- Indizes für die Tabelle `product_m2n_product_group`
--
ALTER TABLE `product_m2n_product_group`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id_idx` (`product_id`),
  ADD KEY `product_group_id_idx` (`product_group_id`);

--
-- Indizes für die Tabelle `product_m2n_product_option_model`
--
ALTER TABLE `product_m2n_product_option_model`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id_idx` (`product_id`),
  ADD KEY `product_option_model_id_idx` (`product_option_model_id`),
  ADD KEY `position_idx_idx` (`position`);

--
-- Indizes für die Tabelle `product_m2n_product_option_model_values`
--
ALTER TABLE `product_m2n_product_option_model_values`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_m2n_product_option_model_id_idx_idx` (`product_m2n_product_option_model_id`);

--
-- Indizes für die Tabelle `product_m2n_tag`
--
ALTER TABLE `product_m2n_tag`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id_idx` (`product_id`),
  ADD KEY `tag_id_idx` (`tag_id`);

--
-- Indizes für die Tabelle `product_options`
--
ALTER TABLE `product_options`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`);

--
-- Indizes für die Tabelle `product_options_amount_liquid`
--
ALTER TABLE `product_options_amount_liquid`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`);

--
-- Indizes für die Tabelle `product_options_amount_piece`
--
ALTER TABLE `product_options_amount_piece`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`);

--
-- Indizes für die Tabelle `product_options_color`
--
ALTER TABLE `product_options_color`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`);

--
-- Indizes für die Tabelle `product_options_form`
--
ALTER TABLE `product_options_form`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`);

--
-- Indizes für die Tabelle `product_options_general`
--
ALTER TABLE `product_options_general`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`);

--
-- Indizes für die Tabelle `product_options_m2n_product_option_items`
--
ALTER TABLE `product_options_m2n_product_option_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_options_id_idx_idx` (`product_options_id`),
  ADD KEY `product_option_items_id_idx_idx` (`product_option_items_id`);

--
-- Indizes für die Tabelle `product_options_measure_circumference`
--
ALTER TABLE `product_options_measure_circumference`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`);

--
-- Indizes für die Tabelle `product_options_measure_depth`
--
ALTER TABLE `product_options_measure_depth`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`);

--
-- Indizes für die Tabelle `product_options_measure_diameter`
--
ALTER TABLE `product_options_measure_diameter`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`);

--
-- Indizes für die Tabelle `product_options_measure_height`
--
ALTER TABLE `product_options_measure_height`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`);

--
-- Indizes für die Tabelle `product_options_measure_length`
--
ALTER TABLE `product_options_measure_length`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`);

--
-- Indizes für die Tabelle `product_options_measure_width`
--
ALTER TABLE `product_options_measure_width`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`);

--
-- Indizes für die Tabelle `product_options_size`
--
ALTER TABLE `product_options_size`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`);

--
-- Indizes für die Tabelle `product_options_size_clothes_bra_cup`
--
ALTER TABLE `product_options_size_clothes_bra_cup`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`);

--
-- Indizes für die Tabelle `product_options_size_clothes_bra_size`
--
ALTER TABLE `product_options_size_clothes_bra_size`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`);

--
-- Indizes für die Tabelle `product_options_size_clothes_general`
--
ALTER TABLE `product_options_size_clothes_general`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`);

--
-- Indizes für die Tabelle `product_options_size_clothes_girth`
--
ALTER TABLE `product_options_size_clothes_girth`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`);

--
-- Indizes für die Tabelle `product_options_size_shoes`
--
ALTER TABLE `product_options_size_shoes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`);

--
-- Indizes für die Tabelle `product_options_translation`
--
ALTER TABLE `product_options_translation`
  ADD PRIMARY KEY (`id`,`lang`);

--
-- Indizes für die Tabelle `product_options_weight`
--
ALTER TABLE `product_options_weight`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`);

--
-- Indizes für die Tabelle `product_option_items`
--
ALTER TABLE `product_option_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`),
  ADD KEY `media_image_id_idx_idx` (`media_image_id`);

--
-- Indizes für die Tabelle `product_option_items_translation`
--
ALTER TABLE `product_option_items_translation`
  ADD PRIMARY KEY (`id`,`lang`);

--
-- Indizes für die Tabelle `product_option_model`
--
ALTER TABLE `product_option_model`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`),
  ADD KEY `model_name_id_idx_idx` (`model_name_id`);

--
-- Indizes für die Tabelle `product_option_model_translation`
--
ALTER TABLE `product_option_model_translation`
  ADD PRIMARY KEY (`id`,`lang`);

--
-- Indizes für die Tabelle `product_order`
--
ALTER TABLE `product_order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `coupon_id_idx_idx` (`coupon_id`),
  ADD KEY `delivery_country_id_idx_idx` (`delivery_country_id`),
  ADD KEY `billing_country_id_idx_idx` (`billing_country_id`),
  ADD KEY `payment_service_id_idx_idx` (`payment_service_id`),
  ADD KEY `entity_user_id_idx_idx` (`entity_user_id`);

--
-- Indizes für die Tabelle `product_order_item`
--
ALTER TABLE `product_order_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_order_id_idx` (`product_order_id`),
  ADD KEY `media_image_id_idx_idx` (`media_image_id`),
  ADD KEY `media_id_idx_idx` (`media_id`);

--
-- Indizes für die Tabelle `product_order_item_units`
--
ALTER TABLE `product_order_item_units`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_order_item_id_idx` (`product_order_item_id`);

--
-- Indizes für die Tabelle `product_rating`
--
ALTER TABLE `product_rating`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id_idx` (`product_id`);

--
-- Indizes für die Tabelle `product_status`
--
ALTER TABLE `product_status`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `product_status_translation`
--
ALTER TABLE `product_status_translation`
  ADD PRIMARY KEY (`id`,`lang`);

--
-- Indizes für die Tabelle `product_translation`
--
ALTER TABLE `product_translation`
  ADD PRIMARY KEY (`id`,`lang`);

--
-- Indizes für die Tabelle `product_unit`
--
ALTER TABLE `product_unit`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`);

--
-- Indizes für die Tabelle `product_unit_translation`
--
ALTER TABLE `product_unit_translation`
  ADD PRIMARY KEY (`id`,`lang`);

--
-- Indizes für die Tabelle `protocol`
--
ALTER TABLE `protocol`
  ADD PRIMARY KEY (`id`),
  ADD KEY `commission_id_idx_idx` (`commission_id`),
  ADD KEY `fr_media_id_idx_idx` (`fr_media_id`),
  ADD KEY `de_media_id_idx_idx` (`de_media_id`),
  ADD KEY `fr_presentation_media_id_idx_idx` (`fr_presentation_media_id`),
  ADD KEY `de_presentation_media_id_idx_idx` (`de_presentation_media_id`);

--
-- Indizes für die Tabelle `protocol_m2n_department`
--
ALTER TABLE `protocol_m2n_department`
  ADD PRIMARY KEY (`id`),
  ADD KEY `protocol_id_idx_idx` (`protocol_id`),
  ADD KEY `department_id_idx_idx` (`department_id`);

--
-- Indizes für die Tabelle `protocol_translation`
--
ALTER TABLE `protocol_translation`
  ADD PRIMARY KEY (`id`,`lang`);

--
-- Indizes für die Tabelle `refund`
--
ALTER TABLE `refund`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`);

--
-- Indizes für die Tabelle `remember_media_folder`
--
ALTER TABLE `remember_media_folder`
  ADD PRIMARY KEY (`id`),
  ADD KEY `entity_id_idx_idx` (`entity_id`),
  ADD KEY `media_folder_id_idx_idx` (`media_folder_id`),
  ADD KEY `model_column_name_id_idx_idx` (`model_column_name_id`);

--
-- Indizes für die Tabelle `resource_translator`
--
ALTER TABLE `resource_translator`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `resource` (`resource`);

--
-- Indizes für die Tabelle `resource_translator_translation`
--
ALTER TABLE `resource_translator_translation`
  ADD PRIMARY KEY (`id`,`lang`);

--
-- Indizes für die Tabelle `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`),
  ADD KEY `short_idx` (`short`),
  ADD KEY `role_id_idx` (`role_id`);

--
-- Indizes für die Tabelle `role_translation`
--
ALTER TABLE `role_translation`
  ADD PRIMARY KEY (`id`,`lang`);

--
-- Indizes für die Tabelle `sales_statistics`
--
ALTER TABLE `sales_statistics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `media_image_id_idx_idx` (`media_image_id`);

--
-- Indizes für die Tabelle `sales_statistics_translation`
--
ALTER TABLE `sales_statistics_translation`
  ADD PRIMARY KEY (`id`,`lang`);

--
-- Indizes für die Tabelle `salutation`
--
ALTER TABLE `salutation`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `salutation_translation`
--
ALTER TABLE `salutation_translation`
  ADD PRIMARY KEY (`id`,`lang`);

--
-- Indizes für die Tabelle `shipping_cost`
--
ALTER TABLE `shipping_cost`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`);

--
-- Indizes für die Tabelle `shipping_country`
--
ALTER TABLE `shipping_country`
  ADD PRIMARY KEY (`id`),
  ADD KEY `country_id_idx_idx` (`country_id`);

--
-- Indizes für die Tabelle `site_config`
--
ALTER TABLE `site_config`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`);

--
-- Indizes für die Tabelle `site_rights`
--
ALTER TABLE `site_rights`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`);

--
-- Indizes für die Tabelle `site_rights_translation`
--
ALTER TABLE `site_rights_translation`
  ADD PRIMARY KEY (`id`,`lang`);

--
-- Indizes für die Tabelle `tag`
--
ALTER TABLE `tag`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`);

--
-- Indizes für die Tabelle `taxes`
--
ALTER TABLE `taxes`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `team`
--
ALTER TABLE `team`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`),
  ADD KEY `media_image_id_idx_idx` (`media_image_id`),
  ADD KEY `position_idx_idx` (`position`);

--
-- Indizes für die Tabelle `team_translation`
--
ALTER TABLE `team_translation`
  ADD PRIMARY KEY (`id`,`lang`);

--
-- Indizes für die Tabelle `temporary_offer`
--
ALTER TABLE `temporary_offer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id_idx` (`product_id`);

--
-- Indizes für die Tabelle `territory`
--
ALTER TABLE `territory`
  ADD PRIMARY KEY (`id`),
  ADD KEY `territory_iso_nr_idx_idx` (`territory_iso_nr`),
  ADD KEY `iso_nr_idx` (`iso_nr`);

--
-- Indizes für die Tabelle `territory_translation`
--
ALTER TABLE `territory_translation`
  ADD PRIMARY KEY (`id`,`lang`);

--
-- Indizes für die Tabelle `tinymce_template`
--
ALTER TABLE `tinymce_template`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`);

--
-- Indizes für die Tabelle `translator`
--
ALTER TABLE `translator`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`);

--
-- Indizes für die Tabelle `translator_translation`
--
ALTER TABLE `translator_translation`
  ADD PRIMARY KEY (`id`,`lang`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `action`
--
ALTER TABLE `action`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `activation`
--
ALTER TABLE `activation`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `address`
--
ALTER TABLE `address`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `assembly`
--
ALTER TABLE `assembly`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `assembly_m2n_department`
--
ALTER TABLE `assembly_m2n_department`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `backend_admin_boxes`
--
ALTER TABLE `backend_admin_boxes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `backend_admin_boxes_action`
--
ALTER TABLE `backend_admin_boxes_action`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `backend_admin_boxes_action_m2n_backend_admin_boxes`
--
ALTER TABLE `backend_admin_boxes_action_m2n_backend_admin_boxes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `background_image`
--
ALTER TABLE `background_image`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `blog`
--
ALTER TABLE `blog`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `commission`
--
ALTER TABLE `commission`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `commission_m2n_media_image`
--
ALTER TABLE `commission_m2n_media_image`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `content_box`
--
ALTER TABLE `content_box`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `contract_type`
--
ALTER TABLE `contract_type`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `controller`
--
ALTER TABLE `controller`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `country`
--
ALTER TABLE `country`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `country_zone`
--
ALTER TABLE `country_zone`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `coupon`
--
ALTER TABLE `coupon`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `coupon_history`
--
ALTER TABLE `coupon_history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `cross_sell`
--
ALTER TABLE `cross_sell`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `currency`
--
ALTER TABLE `currency`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `dates`
--
ALTER TABLE `dates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `dates_m2n_department`
--
ALTER TABLE `dates_m2n_department`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `dates_participants`
--
ALTER TABLE `dates_participants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `delivery_time`
--
ALTER TABLE `delivery_time`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `department`
--
ALTER TABLE `department`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `email_template`
--
ALTER TABLE `email_template`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `email_template_m2n_email_template_part`
--
ALTER TABLE `email_template_m2n_email_template_part`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `email_template_part`
--
ALTER TABLE `email_template_part`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `email_template_replacement`
--
ALTER TABLE `email_template_replacement`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `entity`
--
ALTER TABLE `entity`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `entity_log`
--
ALTER TABLE `entity_log`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `entity_log_message`
--
ALTER TABLE `entity_log_message`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `entity_model_list_config`
--
ALTER TABLE `entity_model_list_config`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `etage`
--
ALTER TABLE `etage`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `events`
--
ALTER TABLE `events`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `faq`
--
ALTER TABLE `faq`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `flyer`
--
ALTER TABLE `flyer`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `flyer_images`
--
ALTER TABLE `flyer_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `holiday`
--
ALTER TABLE `holiday`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `house_types`
--
ALTER TABLE `house_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `image_config`
--
ALTER TABLE `image_config`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `info_page`
--
ALTER TABLE `info_page`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `kanton`
--
ALTER TABLE `kanton`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `language`
--
ALTER TABLE `language`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `media`
--
ALTER TABLE `media`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `media_config`
--
ALTER TABLE `media_config`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `media_folder`
--
ALTER TABLE `media_folder`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `media_image_m2n_action`
--
ALTER TABLE `media_image_m2n_action`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `media_image_m2n_info_page`
--
ALTER TABLE `media_image_m2n_info_page`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `media_type`
--
ALTER TABLE `media_type`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `meta_configuration`
--
ALTER TABLE `meta_configuration`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `model_column_name`
--
ALTER TABLE `model_column_name`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `model_column_name_edit_as`
--
ALTER TABLE `model_column_name_edit_as`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `model_list`
--
ALTER TABLE `model_list`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `model_list_column`
--
ALTER TABLE `model_list_column`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `model_list_column_export`
--
ALTER TABLE `model_list_column_export`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `model_list_connection`
--
ALTER TABLE `model_list_connection`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `model_list_edit_ignore`
--
ALTER TABLE `model_list_edit_ignore`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `model_list_export`
--
ALTER TABLE `model_list_export`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `model_list_where`
--
ALTER TABLE `model_list_where`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `model_marked_for_editor`
--
ALTER TABLE `model_marked_for_editor`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `model_name`
--
ALTER TABLE `model_name`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `module`
--
ALTER TABLE `module`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `navigation`
--
ALTER TABLE `navigation`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `news`
--
ALTER TABLE `news`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `newsletter`
--
ALTER TABLE `newsletter`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `newsletter_subscriber`
--
ALTER TABLE `newsletter_subscriber`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `newsletter_subscriber_type`
--
ALTER TABLE `newsletter_subscriber_type`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `news_m2n_department`
--
ALTER TABLE `news_m2n_department`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `otc`
--
ALTER TABLE `otc`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `otc_m2n_department`
--
ALTER TABLE `otc_m2n_department`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `param_translator`
--
ALTER TABLE `param_translator`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `payment_service`
--
ALTER TABLE `payment_service`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `plugin`
--
ALTER TABLE `plugin`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `producer`
--
ALTER TABLE `producer`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `product`
--
ALTER TABLE `product`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `products_options`
--
ALTER TABLE `products_options`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `product_group`
--
ALTER TABLE `product_group`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `product_information`
--
ALTER TABLE `product_information`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `product_m2n_media_image`
--
ALTER TABLE `product_m2n_media_image`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `product_m2n_product_group`
--
ALTER TABLE `product_m2n_product_group`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `product_m2n_product_option_model`
--
ALTER TABLE `product_m2n_product_option_model`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `product_m2n_product_option_model_values`
--
ALTER TABLE `product_m2n_product_option_model_values`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `product_m2n_tag`
--
ALTER TABLE `product_m2n_tag`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `product_options`
--
ALTER TABLE `product_options`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `product_options_amount_liquid`
--
ALTER TABLE `product_options_amount_liquid`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `product_options_amount_piece`
--
ALTER TABLE `product_options_amount_piece`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `product_options_color`
--
ALTER TABLE `product_options_color`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `product_options_form`
--
ALTER TABLE `product_options_form`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `product_options_general`
--
ALTER TABLE `product_options_general`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `product_options_m2n_product_option_items`
--
ALTER TABLE `product_options_m2n_product_option_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `product_options_measure_circumference`
--
ALTER TABLE `product_options_measure_circumference`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `product_options_measure_depth`
--
ALTER TABLE `product_options_measure_depth`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `product_options_measure_diameter`
--
ALTER TABLE `product_options_measure_diameter`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `product_options_measure_height`
--
ALTER TABLE `product_options_measure_height`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `product_options_measure_length`
--
ALTER TABLE `product_options_measure_length`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `product_options_measure_width`
--
ALTER TABLE `product_options_measure_width`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `product_options_size`
--
ALTER TABLE `product_options_size`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `product_options_size_clothes_bra_cup`
--
ALTER TABLE `product_options_size_clothes_bra_cup`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `product_options_size_clothes_bra_size`
--
ALTER TABLE `product_options_size_clothes_bra_size`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `product_options_size_clothes_general`
--
ALTER TABLE `product_options_size_clothes_general`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `product_options_size_clothes_girth`
--
ALTER TABLE `product_options_size_clothes_girth`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `product_options_size_shoes`
--
ALTER TABLE `product_options_size_shoes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `product_options_weight`
--
ALTER TABLE `product_options_weight`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `product_option_items`
--
ALTER TABLE `product_option_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `product_option_model`
--
ALTER TABLE `product_option_model`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `product_order`
--
ALTER TABLE `product_order`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `product_order_item`
--
ALTER TABLE `product_order_item`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `product_order_item_units`
--
ALTER TABLE `product_order_item_units`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `product_rating`
--
ALTER TABLE `product_rating`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `product_status`
--
ALTER TABLE `product_status`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `product_unit`
--
ALTER TABLE `product_unit`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `protocol`
--
ALTER TABLE `protocol`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `protocol_m2n_department`
--
ALTER TABLE `protocol_m2n_department`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `refund`
--
ALTER TABLE `refund`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `remember_media_folder`
--
ALTER TABLE `remember_media_folder`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `resource_translator`
--
ALTER TABLE `resource_translator`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `role`
--
ALTER TABLE `role`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `sales_statistics`
--
ALTER TABLE `sales_statistics`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `salutation`
--
ALTER TABLE `salutation`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `shipping_cost`
--
ALTER TABLE `shipping_cost`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `shipping_country`
--
ALTER TABLE `shipping_country`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `site_config`
--
ALTER TABLE `site_config`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `site_rights`
--
ALTER TABLE `site_rights`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `tag`
--
ALTER TABLE `tag`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `taxes`
--
ALTER TABLE `taxes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `team`
--
ALTER TABLE `team`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `temporary_offer`
--
ALTER TABLE `temporary_offer`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `territory`
--
ALTER TABLE `territory`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `tinymce_template`
--
ALTER TABLE `tinymce_template`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `translator`
--
ALTER TABLE `translator`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `action`
--
ALTER TABLE `action`
  ADD CONSTRAINT `action_controller_id_controller_id` FOREIGN KEY (`controller_id`) REFERENCES `controller` (`id`),
  ADD CONSTRAINT `action_role_id_role_id` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`);

--
-- Constraints der Tabelle `action_translation`
--
ALTER TABLE `action_translation`
  ADD CONSTRAINT `action_translation_id_action_id` FOREIGN KEY (`id`) REFERENCES `action` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `address`
--
ALTER TABLE `address`
  ADD CONSTRAINT `address_etage_id_etage_id` FOREIGN KEY (`etage_id`) REFERENCES `etage` (`id`),
  ADD CONSTRAINT `address_house_type_id_house_types_id` FOREIGN KEY (`house_type_id`) REFERENCES `house_types` (`id`),
  ADD CONSTRAINT `address_product_order_id_product_order_id` FOREIGN KEY (`product_order_id`) REFERENCES `product_order` (`id`);

--
-- Constraints der Tabelle `assembly`
--
ALTER TABLE `assembly`
  ADD CONSTRAINT `assembly_de_media_id_media_id` FOREIGN KEY (`de_media_id`) REFERENCES `media` (`id`),
  ADD CONSTRAINT `assembly_de_presentation_media_id_media_id` FOREIGN KEY (`de_presentation_media_id`) REFERENCES `media` (`id`),
  ADD CONSTRAINT `assembly_fr_media_id_media_id` FOREIGN KEY (`fr_media_id`) REFERENCES `media` (`id`),
  ADD CONSTRAINT `assembly_fr_presentation_media_id_media_id` FOREIGN KEY (`fr_presentation_media_id`) REFERENCES `media` (`id`);

--
-- Constraints der Tabelle `assembly_m2n_department`
--
ALTER TABLE `assembly_m2n_department`
  ADD CONSTRAINT `assembly_m2n_department_assembly_id_assembly_id` FOREIGN KEY (`assembly_id`) REFERENCES `assembly` (`id`),
  ADD CONSTRAINT `assembly_m2n_department_department_id_department_id` FOREIGN KEY (`department_id`) REFERENCES `department` (`id`);

--
-- Constraints der Tabelle `assembly_translation`
--
ALTER TABLE `assembly_translation`
  ADD CONSTRAINT `assembly_translation_id_assembly_id` FOREIGN KEY (`id`) REFERENCES `assembly` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `backend_admin_boxes_action`
--
ALTER TABLE `backend_admin_boxes_action`
  ADD CONSTRAINT `backend_admin_boxes_action_action_id_action_id` FOREIGN KEY (`action_id`) REFERENCES `action` (`id`),
  ADD CONSTRAINT `backend_admin_boxes_action_model_name_id_model_name_id` FOREIGN KEY (`model_name_id`) REFERENCES `model_name` (`id`);

--
-- Constraints der Tabelle `backend_admin_boxes_action_m2n_backend_admin_boxes`
--
ALTER TABLE `backend_admin_boxes_action_m2n_backend_admin_boxes`
  ADD CONSTRAINT `bbbi` FOREIGN KEY (`backend_admin_boxes_action_id`) REFERENCES `backend_admin_boxes_action` (`id`),
  ADD CONSTRAINT `bbbi_1` FOREIGN KEY (`backend_admin_boxes_id`) REFERENCES `backend_admin_boxes` (`id`);

--
-- Constraints der Tabelle `backend_admin_boxes_action_translation`
--
ALTER TABLE `backend_admin_boxes_action_translation`
  ADD CONSTRAINT `bibi_1` FOREIGN KEY (`id`) REFERENCES `backend_admin_boxes_action` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `backend_admin_boxes_translation`
--
ALTER TABLE `backend_admin_boxes_translation`
  ADD CONSTRAINT `backend_admin_boxes_translation_id_backend_admin_boxes_id` FOREIGN KEY (`id`) REFERENCES `backend_admin_boxes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `background_image`
--
ALTER TABLE `background_image`
  ADD CONSTRAINT `background_image_action_id_action_id` FOREIGN KEY (`action_id`) REFERENCES `action` (`id`),
  ADD CONSTRAINT `background_image_media_image_id_media_id` FOREIGN KEY (`media_image_id`) REFERENCES `media` (`id`);

--
-- Constraints der Tabelle `blog_translation`
--
ALTER TABLE `blog_translation`
  ADD CONSTRAINT `blog_translation_id_blog_id` FOREIGN KEY (`id`) REFERENCES `blog` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `commission`
--
ALTER TABLE `commission`
  ADD CONSTRAINT `commission_media_image_id_media_id` FOREIGN KEY (`media_image_id`) REFERENCES `media` (`id`);

--
-- Constraints der Tabelle `commission_m2n_media_image`
--
ALTER TABLE `commission_m2n_media_image`
  ADD CONSTRAINT `commission_m2n_media_image_commission_id_commission_id` FOREIGN KEY (`commission_id`) REFERENCES `commission` (`id`),
  ADD CONSTRAINT `commission_m2n_media_image_media_image_id_media_id` FOREIGN KEY (`media_image_id`) REFERENCES `media` (`id`);

--
-- Constraints der Tabelle `commission_translation`
--
ALTER TABLE `commission_translation`
  ADD CONSTRAINT `commission_translation_id_commission_id` FOREIGN KEY (`id`) REFERENCES `commission` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `content_box`
--
ALTER TABLE `content_box`
  ADD CONSTRAINT `content_box_action_id_action_id` FOREIGN KEY (`action_id`) REFERENCES `action` (`id`),
  ADD CONSTRAINT `content_box_media_image_id_media_id` FOREIGN KEY (`media_image_id`) REFERENCES `media` (`id`),
  ADD CONSTRAINT `content_box_rollover_media_image_id_media_id` FOREIGN KEY (`rollover_media_image_id`) REFERENCES `media` (`id`);

--
-- Constraints der Tabelle `content_box_translation`
--
ALTER TABLE `content_box_translation`
  ADD CONSTRAINT `content_box_translation_id_content_box_id` FOREIGN KEY (`id`) REFERENCES `content_box` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `contract_type_translation`
--
ALTER TABLE `contract_type_translation`
  ADD CONSTRAINT `contract_type_translation_id_contract_type_id` FOREIGN KEY (`id`) REFERENCES `contract_type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `controller`
--
ALTER TABLE `controller`
  ADD CONSTRAINT `controller_module_id_module_id` FOREIGN KEY (`module_id`) REFERENCES `module` (`id`);

--
-- Constraints der Tabelle `country`
--
ALTER TABLE `country`
  ADD CONSTRAINT `country_territory_iso_nr_territory_iso_nr` FOREIGN KEY (`territory_iso_nr`) REFERENCES `territory` (`iso_nr`);

--
-- Constraints der Tabelle `country_translation`
--
ALTER TABLE `country_translation`
  ADD CONSTRAINT `country_translation_id_country_id` FOREIGN KEY (`id`) REFERENCES `country` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `country_zone`
--
ALTER TABLE `country_zone`
  ADD CONSTRAINT `country_zone_country_iso_3_country_iso_3` FOREIGN KEY (`country_iso_3`) REFERENCES `country` (`iso_3`);

--
-- Constraints der Tabelle `country_zone_translation`
--
ALTER TABLE `country_zone_translation`
  ADD CONSTRAINT `country_zone_translation_id_country_zone_id` FOREIGN KEY (`id`) REFERENCES `country_zone` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `coupon`
--
ALTER TABLE `coupon`
  ADD CONSTRAINT `coupon_entity_user_id_entity_id` FOREIGN KEY (`entity_user_id`) REFERENCES `entity` (`id`);

--
-- Constraints der Tabelle `coupon_history`
--
ALTER TABLE `coupon_history`
  ADD CONSTRAINT `coupon_history_coupon_id_coupon_id` FOREIGN KEY (`coupon_id`) REFERENCES `coupon` (`id`),
  ADD CONSTRAINT `coupon_history_product_order_id_product_order_id` FOREIGN KEY (`product_order_id`) REFERENCES `product_order` (`id`);

--
-- Constraints der Tabelle `cross_sell`
--
ALTER TABLE `cross_sell`
  ADD CONSTRAINT `cross_sell_product1_id_product_id` FOREIGN KEY (`product1_id`) REFERENCES `product` (`id`),
  ADD CONSTRAINT `cross_sell_product2_id_product_id` FOREIGN KEY (`product2_id`) REFERENCES `product` (`id`);

--
-- Constraints der Tabelle `currency_translation`
--
ALTER TABLE `currency_translation`
  ADD CONSTRAINT `currency_translation_id_currency_id` FOREIGN KEY (`id`) REFERENCES `currency` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `dates`
--
ALTER TABLE `dates`
  ADD CONSTRAINT `dates_de_media_id_media_id` FOREIGN KEY (`de_media_id`) REFERENCES `media` (`id`),
  ADD CONSTRAINT `dates_de_presentation_media_id_media_id` FOREIGN KEY (`de_presentation_media_id`) REFERENCES `media` (`id`),
  ADD CONSTRAINT `dates_fr_media_id_media_id` FOREIGN KEY (`fr_media_id`) REFERENCES `media` (`id`),
  ADD CONSTRAINT `dates_fr_presentation_media_id_media_id` FOREIGN KEY (`fr_presentation_media_id`) REFERENCES `media` (`id`);

--
-- Constraints der Tabelle `dates_m2n_department`
--
ALTER TABLE `dates_m2n_department`
  ADD CONSTRAINT `dates_m2n_department_dates_id_dates_id` FOREIGN KEY (`dates_id`) REFERENCES `dates` (`id`),
  ADD CONSTRAINT `dates_m2n_department_department_id_department_id` FOREIGN KEY (`department_id`) REFERENCES `department` (`id`);

--
-- Constraints der Tabelle `dates_participants`
--
ALTER TABLE `dates_participants`
  ADD CONSTRAINT `dates_participants_date_id_dates_id` FOREIGN KEY (`date_id`) REFERENCES `dates` (`id`),
  ADD CONSTRAINT `dates_participants_user_id_entity_id` FOREIGN KEY (`user_id`) REFERENCES `entity` (`id`);

--
-- Constraints der Tabelle `dates_translation`
--
ALTER TABLE `dates_translation`
  ADD CONSTRAINT `dates_translation_id_dates_id` FOREIGN KEY (`id`) REFERENCES `dates` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `delivery_time`
--
ALTER TABLE `delivery_time`
  ADD CONSTRAINT `delivery_time_address_id_address_id` FOREIGN KEY (`address_id`) REFERENCES `address` (`id`);

--
-- Constraints der Tabelle `department_translation`
--
ALTER TABLE `department_translation`
  ADD CONSTRAINT `department_translation_id_department_id` FOREIGN KEY (`id`) REFERENCES `department` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `email_template_m2n_email_template_part`
--
ALTER TABLE `email_template_m2n_email_template_part`
  ADD CONSTRAINT `eeei` FOREIGN KEY (`email_template_id`) REFERENCES `email_template` (`id`),
  ADD CONSTRAINT `eeei_1` FOREIGN KEY (`email_template_part_id`) REFERENCES `email_template_part` (`id`);

--
-- Constraints der Tabelle `email_template_part_translation`
--
ALTER TABLE `email_template_part_translation`
  ADD CONSTRAINT `email_template_part_translation_id_email_template_part_id` FOREIGN KEY (`id`) REFERENCES `email_template_part` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `email_template_translation`
--
ALTER TABLE `email_template_translation`
  ADD CONSTRAINT `email_template_translation_id_email_template_id` FOREIGN KEY (`id`) REFERENCES `email_template` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `entity`
--
ALTER TABLE `entity`
  ADD CONSTRAINT `entity_billing_country_id_country_id` FOREIGN KEY (`billing_country_id`) REFERENCES `country` (`id`),
  ADD CONSTRAINT `entity_contract_type_id_contract_type_id` FOREIGN KEY (`contract_type_id`) REFERENCES `contract_type` (`id`),
  ADD CONSTRAINT `entity_country_id_country_id` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`),
  ADD CONSTRAINT `entity_department_id_department_id` FOREIGN KEY (`department_id`) REFERENCES `department` (`id`),
  ADD CONSTRAINT `entity_kanton_id_kanton_id` FOREIGN KEY (`kanton_id`) REFERENCES `kanton` (`id`),
  ADD CONSTRAINT `entity_media_image_id_media_id` FOREIGN KEY (`media_image_id`) REFERENCES `media` (`id`),
  ADD CONSTRAINT `entity_parent_user_id_entity_id` FOREIGN KEY (`parent_user_id`) REFERENCES `entity` (`id`),
  ADD CONSTRAINT `entity_role_id_role_id` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`),
  ADD CONSTRAINT `entity_sales_statistics_id_sales_statistics_id` FOREIGN KEY (`sales_statistics_id`) REFERENCES `sales_statistics` (`id`),
  ADD CONSTRAINT `entity_salutation_id_salutation_id` FOREIGN KEY (`salutation_id`) REFERENCES `salutation` (`id`);

--
-- Constraints der Tabelle `entity_log`
--
ALTER TABLE `entity_log`
  ADD CONSTRAINT `entity_log_entity_id_entity_id` FOREIGN KEY (`entity_id`) REFERENCES `entity` (`id`),
  ADD CONSTRAINT `entity_log_entity_log_message_id_entity_log_message_id` FOREIGN KEY (`entity_log_message_id`) REFERENCES `entity_log_message` (`id`);

--
-- Constraints der Tabelle `entity_model_list_config`
--
ALTER TABLE `entity_model_list_config`
  ADD CONSTRAINT `entity_model_list_config_entity_id_entity_id` FOREIGN KEY (`entity_id`) REFERENCES `entity` (`id`),
  ADD CONSTRAINT `entity_model_list_config_model_list_id_model_list_id` FOREIGN KEY (`model_list_id`) REFERENCES `model_list` (`id`);

--
-- Constraints der Tabelle `faq_translation`
--
ALTER TABLE `faq_translation`
  ADD CONSTRAINT `faq_translation_id_faq_id` FOREIGN KEY (`id`) REFERENCES `faq` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `flyer`
--
ALTER TABLE `flyer`
  ADD CONSTRAINT `flyer_media_id_media_id` FOREIGN KEY (`media_id`) REFERENCES `media` (`id`),
  ADD CONSTRAINT `flyer_media_image_id_media_id` FOREIGN KEY (`media_image_id`) REFERENCES `media` (`id`);

--
-- Constraints der Tabelle `flyer_images`
--
ALTER TABLE `flyer_images`
  ADD CONSTRAINT `flyer_images_flyer_id_flyer_id` FOREIGN KEY (`flyer_id`) REFERENCES `flyer` (`id`),
  ADD CONSTRAINT `flyer_images_media_image_id_media_id` FOREIGN KEY (`media_image_id`) REFERENCES `media` (`id`);

--
-- Constraints der Tabelle `flyer_translation`
--
ALTER TABLE `flyer_translation`
  ADD CONSTRAINT `flyer_translation_id_flyer_id` FOREIGN KEY (`id`) REFERENCES `flyer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `image_config`
--
ALTER TABLE `image_config`
  ADD CONSTRAINT `image_config_media_image_id_media_id` FOREIGN KEY (`media_image_id`) REFERENCES `media` (`id`);

--
-- Constraints der Tabelle `info_page`
--
ALTER TABLE `info_page`
  ADD CONSTRAINT `info_page_info_page_id_info_page_id` FOREIGN KEY (`info_page_id`) REFERENCES `info_page` (`id`);

--
-- Constraints der Tabelle `info_page_translation`
--
ALTER TABLE `info_page_translation`
  ADD CONSTRAINT `info_page_translation_id_info_page_id` FOREIGN KEY (`id`) REFERENCES `info_page` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `kanton`
--
ALTER TABLE `kanton`
  ADD CONSTRAINT `kanton_sales_statistics_id_sales_statistics_id` FOREIGN KEY (`sales_statistics_id`) REFERENCES `sales_statistics` (`id`);

--
-- Constraints der Tabelle `language`
--
ALTER TABLE `language`
  ADD CONSTRAINT `language_country_iso_2_country_iso_2` FOREIGN KEY (`country_iso_2`) REFERENCES `country` (`iso_2`);

--
-- Constraints der Tabelle `language_translation`
--
ALTER TABLE `language_translation`
  ADD CONSTRAINT `language_translation_id_language_id` FOREIGN KEY (`id`) REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `media`
--
ALTER TABLE `media`
  ADD CONSTRAINT `media_entity_id_entity_id` FOREIGN KEY (`entity_id`) REFERENCES `entity` (`id`),
  ADD CONSTRAINT `media_media_folder_id_media_folder_id` FOREIGN KEY (`media_folder_id`) REFERENCES `media_folder` (`id`),
  ADD CONSTRAINT `media_media_image_id_media_id` FOREIGN KEY (`media_image_id`) REFERENCES `media` (`id`),
  ADD CONSTRAINT `media_media_type_id_media_type_id` FOREIGN KEY (`media_type_id`) REFERENCES `media_type` (`id`),
  ADD CONSTRAINT `media_role_id_role_id` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`);

--
-- Constraints der Tabelle `media_config`
--
ALTER TABLE `media_config`
  ADD CONSTRAINT `media_config_media_id_media_id` FOREIGN KEY (`media_id`) REFERENCES `media` (`id`);

--
-- Constraints der Tabelle `media_folder`
--
ALTER TABLE `media_folder`
  ADD CONSTRAINT `media_folder_media_folder_id_media_folder_id` FOREIGN KEY (`media_folder_id`) REFERENCES `media_folder` (`id`);

--
-- Constraints der Tabelle `media_image_m2n_action`
--
ALTER TABLE `media_image_m2n_action`
  ADD CONSTRAINT `media_image_m2n_action_action_id_action_id` FOREIGN KEY (`action_id`) REFERENCES `action` (`id`),
  ADD CONSTRAINT `media_image_m2n_action_media_image_id_media_id` FOREIGN KEY (`media_image_id`) REFERENCES `media` (`id`);

--
-- Constraints der Tabelle `media_image_m2n_action_translation`
--
ALTER TABLE `media_image_m2n_action_translation`
  ADD CONSTRAINT `media_image_m2n_action_translation_id_media_image_m2n_action_id` FOREIGN KEY (`id`) REFERENCES `media_image_m2n_action` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `media_image_m2n_info_page`
--
ALTER TABLE `media_image_m2n_info_page`
  ADD CONSTRAINT `media_image_m2n_info_page_info_page_id_info_page_id` FOREIGN KEY (`info_page_id`) REFERENCES `info_page` (`id`),
  ADD CONSTRAINT `media_image_m2n_info_page_media_image_id_media_id` FOREIGN KEY (`media_image_id`) REFERENCES `media` (`id`);

--
-- Constraints der Tabelle `media_translation`
--
ALTER TABLE `media_translation`
  ADD CONSTRAINT `media_translation_id_media_id` FOREIGN KEY (`id`) REFERENCES `media` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `media_type_translation`
--
ALTER TABLE `media_type_translation`
  ADD CONSTRAINT `media_type_translation_id_media_type_id` FOREIGN KEY (`id`) REFERENCES `media_type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `model_column_name`
--
ALTER TABLE `model_column_name`
  ADD CONSTRAINT `mmmi_1` FOREIGN KEY (`model_column_name_edit_as_id`) REFERENCES `model_column_name_edit_as` (`id`),
  ADD CONSTRAINT `model_column_name_model_name_id_model_name_id` FOREIGN KEY (`model_name_id`) REFERENCES `model_name` (`id`);

--
-- Constraints der Tabelle `model_column_name_translation`
--
ALTER TABLE `model_column_name_translation`
  ADD CONSTRAINT `model_column_name_translation_id_model_column_name_id` FOREIGN KEY (`id`) REFERENCES `model_column_name` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `model_list_column`
--
ALTER TABLE `model_list_column`
  ADD CONSTRAINT `mmmi` FOREIGN KEY (`model_list_connection_id`) REFERENCES `model_list_connection` (`id`),
  ADD CONSTRAINT `model_list_column_model_column_name_id_model_column_name_id` FOREIGN KEY (`model_column_name_id`) REFERENCES `model_column_name` (`id`),
  ADD CONSTRAINT `model_list_column_model_list_id_model_list_id` FOREIGN KEY (`model_list_id`) REFERENCES `model_list` (`id`);

--
-- Constraints der Tabelle `model_list_column_export`
--
ALTER TABLE `model_list_column_export`
  ADD CONSTRAINT `mmmi_3` FOREIGN KEY (`model_list_export_id`) REFERENCES `model_list_export` (`id`);

--
-- Constraints der Tabelle `model_list_connection`
--
ALTER TABLE `model_list_connection`
  ADD CONSTRAINT `model_list_connection_model_name_id_model_name_id` FOREIGN KEY (`model_name_id`) REFERENCES `model_name` (`id`);

--
-- Constraints der Tabelle `model_list_edit_ignore`
--
ALTER TABLE `model_list_edit_ignore`
  ADD CONSTRAINT `model_list_edit_ignore_model_column_name_id_model_column_name_id` FOREIGN KEY (`model_column_name_id`) REFERENCES `model_column_name` (`id`),
  ADD CONSTRAINT `model_list_edit_ignore_model_list_id_model_list_id` FOREIGN KEY (`model_list_id`) REFERENCES `model_list` (`id`);

--
-- Constraints der Tabelle `model_list_export`
--
ALTER TABLE `model_list_export`
  ADD CONSTRAINT `model_list_export_entity_id_entity_id` FOREIGN KEY (`entity_id`) REFERENCES `entity` (`id`),
  ADD CONSTRAINT `model_list_export_model_list_id_model_list_id` FOREIGN KEY (`model_list_id`) REFERENCES `model_list` (`id`);

--
-- Constraints der Tabelle `model_list_translation`
--
ALTER TABLE `model_list_translation`
  ADD CONSTRAINT `model_list_translation_id_model_list_id` FOREIGN KEY (`id`) REFERENCES `model_list` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `model_list_where`
--
ALTER TABLE `model_list_where`
  ADD CONSTRAINT `mmmi_2` FOREIGN KEY (`model_list_connection_id`) REFERENCES `model_list_connection` (`id`),
  ADD CONSTRAINT `model_list_where_model_column_name_id_model_column_name_id` FOREIGN KEY (`model_column_name_id`) REFERENCES `model_column_name` (`id`),
  ADD CONSTRAINT `model_list_where_model_list_id_model_list_id` FOREIGN KEY (`model_list_id`) REFERENCES `model_list` (`id`);

--
-- Constraints der Tabelle `model_marked_for_editor`
--
ALTER TABLE `model_marked_for_editor`
  ADD CONSTRAINT `model_marked_for_editor_entity_id_entity_id` FOREIGN KEY (`entity_id`) REFERENCES `entity` (`id`),
  ADD CONSTRAINT `model_marked_for_editor_model_name_id_model_name_id` FOREIGN KEY (`model_name_id`) REFERENCES `model_name` (`id`);

--
-- Constraints der Tabelle `navigation`
--
ALTER TABLE `navigation`
  ADD CONSTRAINT `navigation_action_resource_action_resource` FOREIGN KEY (`action_resource`) REFERENCES `action` (`resource`),
  ADD CONSTRAINT `navigation_navigation_id_navigation_id` FOREIGN KEY (`navigation_id`) REFERENCES `navigation` (`id`),
  ADD CONSTRAINT `navigation_role_short_role_short` FOREIGN KEY (`role_short`) REFERENCES `role` (`short`);

--
-- Constraints der Tabelle `news`
--
ALTER TABLE `news`
  ADD CONSTRAINT `news_de_media_id_media_id` FOREIGN KEY (`de_media_id`) REFERENCES `media` (`id`),
  ADD CONSTRAINT `news_de_presentation_media_id_media_id` FOREIGN KEY (`de_presentation_media_id`) REFERENCES `media` (`id`),
  ADD CONSTRAINT `news_fr_media_id_media_id` FOREIGN KEY (`fr_media_id`) REFERENCES `media` (`id`),
  ADD CONSTRAINT `news_fr_presentation_media_id_media_id` FOREIGN KEY (`fr_presentation_media_id`) REFERENCES `media` (`id`);

--
-- Constraints der Tabelle `newsletter`
--
ALTER TABLE `newsletter`
  ADD CONSTRAINT `newsletter_media_id_media_id` FOREIGN KEY (`media_id`) REFERENCES `media` (`id`);

--
-- Constraints der Tabelle `newsletter_subscriber`
--
ALTER TABLE `newsletter_subscriber`
  ADD CONSTRAINT `newsletter_subscriber_salutation_id_salutation_id` FOREIGN KEY (`salutation_id`) REFERENCES `salutation` (`id`),
  ADD CONSTRAINT `nnni` FOREIGN KEY (`newsletter_subscriber_type_id`) REFERENCES `newsletter_subscriber_type` (`id`);

--
-- Constraints der Tabelle `newsletter_translation`
--
ALTER TABLE `newsletter_translation`
  ADD CONSTRAINT `newsletter_translation_id_newsletter_id` FOREIGN KEY (`id`) REFERENCES `newsletter` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `news_m2n_department`
--
ALTER TABLE `news_m2n_department`
  ADD CONSTRAINT `news_m2n_department_department_id_department_id` FOREIGN KEY (`department_id`) REFERENCES `department` (`id`),
  ADD CONSTRAINT `news_m2n_department_news_id_news_id` FOREIGN KEY (`news_id`) REFERENCES `news` (`id`);

--
-- Constraints der Tabelle `news_translation`
--
ALTER TABLE `news_translation`
  ADD CONSTRAINT `news_translation_id_news_id` FOREIGN KEY (`id`) REFERENCES `news` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `otc`
--
ALTER TABLE `otc`
  ADD CONSTRAINT `otc_de_media_id_media_id` FOREIGN KEY (`de_media_id`) REFERENCES `media` (`id`),
  ADD CONSTRAINT `otc_de_presentation_media_id_media_id` FOREIGN KEY (`de_presentation_media_id`) REFERENCES `media` (`id`),
  ADD CONSTRAINT `otc_fr_media_id_media_id` FOREIGN KEY (`fr_media_id`) REFERENCES `media` (`id`),
  ADD CONSTRAINT `otc_fr_presentation_media_id_media_id` FOREIGN KEY (`fr_presentation_media_id`) REFERENCES `media` (`id`);

--
-- Constraints der Tabelle `otc_m2n_department`
--
ALTER TABLE `otc_m2n_department`
  ADD CONSTRAINT `otc_m2n_department_department_id_department_id` FOREIGN KEY (`department_id`) REFERENCES `department` (`id`),
  ADD CONSTRAINT `otc_m2n_department_otc_id_otc_id` FOREIGN KEY (`otc_id`) REFERENCES `otc` (`id`);

--
-- Constraints der Tabelle `otc_translation`
--
ALTER TABLE `otc_translation`
  ADD CONSTRAINT `otc_translation_id_otc_id` FOREIGN KEY (`id`) REFERENCES `otc` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `param_translator_translation`
--
ALTER TABLE `param_translator_translation`
  ADD CONSTRAINT `param_translator_translation_id_param_translator_id` FOREIGN KEY (`id`) REFERENCES `param_translator` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `payment_service`
--
ALTER TABLE `payment_service`
  ADD CONSTRAINT `payment_service_media_image_id_media_id` FOREIGN KEY (`media_image_id`) REFERENCES `media` (`id`);

--
-- Constraints der Tabelle `payment_service_translation`
--
ALTER TABLE `payment_service_translation`
  ADD CONSTRAINT `payment_service_translation_id_payment_service_id` FOREIGN KEY (`id`) REFERENCES `payment_service` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `producer`
--
ALTER TABLE `producer`
  ADD CONSTRAINT `producer_media_image_id_media_id` FOREIGN KEY (`media_image_id`) REFERENCES `media` (`id`);

--
-- Constraints der Tabelle `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_media_id_media_id` FOREIGN KEY (`media_id`) REFERENCES `media` (`id`),
  ADD CONSTRAINT `product_media_image_id_media_id` FOREIGN KEY (`media_image_id`) REFERENCES `media` (`id`),
  ADD CONSTRAINT `product_producer_id_producer_id` FOREIGN KEY (`producer_id`) REFERENCES `producer` (`id`),
  ADD CONSTRAINT `product_product_status_id_product_status_id` FOREIGN KEY (`product_status_id`) REFERENCES `product_status` (`id`),
  ADD CONSTRAINT `product_product_unit_id_product_unit_id` FOREIGN KEY (`product_unit_id`) REFERENCES `product_unit` (`id`),
  ADD CONSTRAINT `product_refund_id_refund_id` FOREIGN KEY (`refund_id`) REFERENCES `refund` (`id`),
  ADD CONSTRAINT `product_taxes_id_taxes_id` FOREIGN KEY (`taxes_id`) REFERENCES `taxes` (`id`);

--
-- Constraints der Tabelle `products_options`
--
ALTER TABLE `products_options`
  ADD CONSTRAINT `products_options_product_id_product_id` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`),
  ADD CONSTRAINT `products_options_product_options_id_product_options_id` FOREIGN KEY (`product_options_id`) REFERENCES `product_options` (`id`);

--
-- Constraints der Tabelle `product_group`
--
ALTER TABLE `product_group`
  ADD CONSTRAINT `product_group_media_image_id_media_id` FOREIGN KEY (`media_image_id`) REFERENCES `media` (`id`),
  ADD CONSTRAINT `product_group_product_group_id_product_group_id` FOREIGN KEY (`product_group_id`) REFERENCES `product_group` (`id`),
  ADD CONSTRAINT `product_group_small_media_image_id_media_id` FOREIGN KEY (`small_media_image_id`) REFERENCES `media` (`id`);

--
-- Constraints der Tabelle `product_group_translation`
--
ALTER TABLE `product_group_translation`
  ADD CONSTRAINT `product_group_translation_id_product_group_id` FOREIGN KEY (`id`) REFERENCES `product_group` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `product_information_translation`
--
ALTER TABLE `product_information_translation`
  ADD CONSTRAINT `product_information_translation_id_product_information_id` FOREIGN KEY (`id`) REFERENCES `product_information` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `product_m2n_media_image`
--
ALTER TABLE `product_m2n_media_image`
  ADD CONSTRAINT `product_m2n_media_image_media_image_id_media_id` FOREIGN KEY (`media_image_id`) REFERENCES `media` (`id`),
  ADD CONSTRAINT `product_m2n_media_image_product_id_product_id` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

--
-- Constraints der Tabelle `product_m2n_product_group`
--
ALTER TABLE `product_m2n_product_group`
  ADD CONSTRAINT `product_m2n_product_group_product_group_id_product_group_id` FOREIGN KEY (`product_group_id`) REFERENCES `product_group` (`id`),
  ADD CONSTRAINT `product_m2n_product_group_product_id_product_id` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

--
-- Constraints der Tabelle `product_m2n_product_option_model`
--
ALTER TABLE `product_m2n_product_option_model`
  ADD CONSTRAINT `pppi` FOREIGN KEY (`product_option_model_id`) REFERENCES `product_option_model` (`id`),
  ADD CONSTRAINT `product_m2n_product_option_model_product_id_product_id` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

--
-- Constraints der Tabelle `product_m2n_product_option_model_values`
--
ALTER TABLE `product_m2n_product_option_model_values`
  ADD CONSTRAINT `pppi_2` FOREIGN KEY (`product_m2n_product_option_model_id`) REFERENCES `product_m2n_product_option_model` (`id`);

--
-- Constraints der Tabelle `product_m2n_tag`
--
ALTER TABLE `product_m2n_tag`
  ADD CONSTRAINT `product_m2n_tag_product_id_product_id` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`),
  ADD CONSTRAINT `product_m2n_tag_tag_id_tag_id` FOREIGN KEY (`tag_id`) REFERENCES `tag` (`id`);

--
-- Constraints der Tabelle `product_options_m2n_product_option_items`
--
ALTER TABLE `product_options_m2n_product_option_items`
  ADD CONSTRAINT `pppi_3` FOREIGN KEY (`product_options_id`) REFERENCES `product_options` (`id`),
  ADD CONSTRAINT `pppi_4` FOREIGN KEY (`product_option_items_id`) REFERENCES `product_option_items` (`id`);

--
-- Constraints der Tabelle `product_options_translation`
--
ALTER TABLE `product_options_translation`
  ADD CONSTRAINT `product_options_translation_id_product_options_id` FOREIGN KEY (`id`) REFERENCES `product_options` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `product_option_items`
--
ALTER TABLE `product_option_items`
  ADD CONSTRAINT `product_option_items_media_image_id_media_id` FOREIGN KEY (`media_image_id`) REFERENCES `media` (`id`);

--
-- Constraints der Tabelle `product_option_items_translation`
--
ALTER TABLE `product_option_items_translation`
  ADD CONSTRAINT `product_option_items_translation_id_product_option_items_id` FOREIGN KEY (`id`) REFERENCES `product_option_items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `product_option_model`
--
ALTER TABLE `product_option_model`
  ADD CONSTRAINT `product_option_model_model_name_id_model_name_id` FOREIGN KEY (`model_name_id`) REFERENCES `model_name` (`id`);

--
-- Constraints der Tabelle `product_option_model_translation`
--
ALTER TABLE `product_option_model_translation`
  ADD CONSTRAINT `product_option_model_translation_id_product_option_model_id` FOREIGN KEY (`id`) REFERENCES `product_option_model` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `product_order`
--
ALTER TABLE `product_order`
  ADD CONSTRAINT `product_order_billing_country_id_country_id` FOREIGN KEY (`billing_country_id`) REFERENCES `country` (`id`),
  ADD CONSTRAINT `product_order_coupon_id_coupon_id` FOREIGN KEY (`coupon_id`) REFERENCES `coupon` (`id`),
  ADD CONSTRAINT `product_order_delivery_country_id_country_id` FOREIGN KEY (`delivery_country_id`) REFERENCES `country` (`id`),
  ADD CONSTRAINT `product_order_entity_user_id_entity_id` FOREIGN KEY (`entity_user_id`) REFERENCES `entity` (`id`),
  ADD CONSTRAINT `product_order_payment_service_id_payment_service_id` FOREIGN KEY (`payment_service_id`) REFERENCES `payment_service` (`id`);

--
-- Constraints der Tabelle `product_order_item`
--
ALTER TABLE `product_order_item`
  ADD CONSTRAINT `product_order_item_media_id_media_id` FOREIGN KEY (`media_id`) REFERENCES `media` (`id`),
  ADD CONSTRAINT `product_order_item_media_image_id_media_id` FOREIGN KEY (`media_image_id`) REFERENCES `media` (`id`),
  ADD CONSTRAINT `product_order_item_product_order_id_product_order_id` FOREIGN KEY (`product_order_id`) REFERENCES `product_order` (`id`);

--
-- Constraints der Tabelle `product_order_item_units`
--
ALTER TABLE `product_order_item_units`
  ADD CONSTRAINT `pppi_1` FOREIGN KEY (`product_order_item_id`) REFERENCES `product_order_item` (`id`);

--
-- Constraints der Tabelle `product_rating`
--
ALTER TABLE `product_rating`
  ADD CONSTRAINT `product_rating_product_id_product_id` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

--
-- Constraints der Tabelle `product_status_translation`
--
ALTER TABLE `product_status_translation`
  ADD CONSTRAINT `product_status_translation_id_product_status_id` FOREIGN KEY (`id`) REFERENCES `product_status` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `product_translation`
--
ALTER TABLE `product_translation`
  ADD CONSTRAINT `product_translation_id_product_id` FOREIGN KEY (`id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `product_unit_translation`
--
ALTER TABLE `product_unit_translation`
  ADD CONSTRAINT `product_unit_translation_id_product_unit_id` FOREIGN KEY (`id`) REFERENCES `product_unit` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `protocol`
--
ALTER TABLE `protocol`
  ADD CONSTRAINT `protocol_commission_id_commission_id` FOREIGN KEY (`commission_id`) REFERENCES `commission` (`id`),
  ADD CONSTRAINT `protocol_de_media_id_media_id` FOREIGN KEY (`de_media_id`) REFERENCES `media` (`id`),
  ADD CONSTRAINT `protocol_de_presentation_media_id_media_id` FOREIGN KEY (`de_presentation_media_id`) REFERENCES `media` (`id`),
  ADD CONSTRAINT `protocol_fr_media_id_media_id` FOREIGN KEY (`fr_media_id`) REFERENCES `media` (`id`),
  ADD CONSTRAINT `protocol_fr_presentation_media_id_media_id` FOREIGN KEY (`fr_presentation_media_id`) REFERENCES `media` (`id`);

--
-- Constraints der Tabelle `protocol_m2n_department`
--
ALTER TABLE `protocol_m2n_department`
  ADD CONSTRAINT `protocol_m2n_department_department_id_department_id` FOREIGN KEY (`department_id`) REFERENCES `department` (`id`),
  ADD CONSTRAINT `protocol_m2n_department_protocol_id_protocol_id` FOREIGN KEY (`protocol_id`) REFERENCES `protocol` (`id`);

--
-- Constraints der Tabelle `protocol_translation`
--
ALTER TABLE `protocol_translation`
  ADD CONSTRAINT `protocol_translation_id_protocol_id` FOREIGN KEY (`id`) REFERENCES `protocol` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `remember_media_folder`
--
ALTER TABLE `remember_media_folder`
  ADD CONSTRAINT `remember_media_folder_entity_id_entity_id` FOREIGN KEY (`entity_id`) REFERENCES `entity` (`id`),
  ADD CONSTRAINT `remember_media_folder_media_folder_id_media_folder_id` FOREIGN KEY (`media_folder_id`) REFERENCES `media_folder` (`id`),
  ADD CONSTRAINT `remember_media_folder_model_column_name_id_model_column_name_id` FOREIGN KEY (`model_column_name_id`) REFERENCES `model_column_name` (`id`);

--
-- Constraints der Tabelle `resource_translator_translation`
--
ALTER TABLE `resource_translator_translation`
  ADD CONSTRAINT `resource_translator_translation_id_resource_translator_id` FOREIGN KEY (`id`) REFERENCES `resource_translator` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `role`
--
ALTER TABLE `role`
  ADD CONSTRAINT `role_role_id_role_id` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`);

--
-- Constraints der Tabelle `role_translation`
--
ALTER TABLE `role_translation`
  ADD CONSTRAINT `role_translation_id_role_id` FOREIGN KEY (`id`) REFERENCES `role` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `sales_statistics`
--
ALTER TABLE `sales_statistics`
  ADD CONSTRAINT `sales_statistics_media_image_id_media_id` FOREIGN KEY (`media_image_id`) REFERENCES `media` (`id`);

--
-- Constraints der Tabelle `sales_statistics_translation`
--
ALTER TABLE `sales_statistics_translation`
  ADD CONSTRAINT `sales_statistics_translation_id_sales_statistics_id` FOREIGN KEY (`id`) REFERENCES `sales_statistics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `salutation_translation`
--
ALTER TABLE `salutation_translation`
  ADD CONSTRAINT `salutation_translation_id_salutation_id` FOREIGN KEY (`id`) REFERENCES `salutation` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `shipping_country`
--
ALTER TABLE `shipping_country`
  ADD CONSTRAINT `shipping_country_country_id_country_id` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`);

--
-- Constraints der Tabelle `site_rights_translation`
--
ALTER TABLE `site_rights_translation`
  ADD CONSTRAINT `site_rights_translation_id_site_rights_id` FOREIGN KEY (`id`) REFERENCES `site_rights` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `team`
--
ALTER TABLE `team`
  ADD CONSTRAINT `team_media_image_id_media_id` FOREIGN KEY (`media_image_id`) REFERENCES `media` (`id`);

--
-- Constraints der Tabelle `team_translation`
--
ALTER TABLE `team_translation`
  ADD CONSTRAINT `team_translation_id_team_id` FOREIGN KEY (`id`) REFERENCES `team` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `temporary_offer`
--
ALTER TABLE `temporary_offer`
  ADD CONSTRAINT `temporary_offer_product_id_product_id` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

--
-- Constraints der Tabelle `territory`
--
ALTER TABLE `territory`
  ADD CONSTRAINT `territory_territory_iso_nr_territory_iso_nr` FOREIGN KEY (`territory_iso_nr`) REFERENCES `territory` (`iso_nr`);

--
-- Constraints der Tabelle `territory_translation`
--
ALTER TABLE `territory_translation`
  ADD CONSTRAINT `territory_translation_id_territory_id` FOREIGN KEY (`id`) REFERENCES `territory` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `translator_translation`
--
ALTER TABLE `translator_translation`
  ADD CONSTRAINT `translator_translation_id_translator_id` FOREIGN KEY (`id`) REFERENCES `translator` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

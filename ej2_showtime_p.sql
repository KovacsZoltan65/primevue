-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1:3306
-- Létrehozás ideje: 2025. Feb 10. 05:46
-- Kiszolgáló verziója: 8.3.0
-- PHP verzió: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Adatbázis: `ej2_showtime_p`
--

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `attendance_abscence_types`
--

DROP TABLE IF EXISTS `attendance_abscence_types`;
CREATE TABLE IF NOT EXISTS `attendance_abscence_types` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `attendance_abscence_types_core_firms_rel`
--

DROP TABLE IF EXISTS `attendance_abscence_types_core_firms_rel`;
CREATE TABLE IF NOT EXISTS `attendance_abscence_types_core_firms_rel` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `attendance_abscence_types_id` int UNSIGNED NOT NULL,
  `core_firms_id` int UNSIGNED NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `bonus_status` tinyint NOT NULL DEFAULT '0' COMMENT 'bónuszt kell rá számítani',
  `worktime_status` tinyint NOT NULL DEFAULT '0' COMMENT 'munkaidőkeretbe beleszámít',
  `core_other_incomes_id` int UNSIGNED DEFAULT NULL,
  `authorization` varchar(120) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '' COMMENT 'távollét rögzítés jogosultághoz kötve, jog_csoportok',
  PRIMARY KEY (`id`),
  KEY `key4018` (`attendance_abscence_types_id`),
  KEY `key4019` (`core_firms_id`),
  KEY `key2077` (`core_other_incomes_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `attendance_access_control_systems`
--

DROP TABLE IF EXISTS `attendance_access_control_systems`;
CREATE TABLE IF NOT EXISTS `attendance_access_control_systems` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `attendance_access_control_systems_calendar_rel`
--

DROP TABLE IF EXISTS `attendance_access_control_systems_calendar_rel`;
CREATE TABLE IF NOT EXISTS `attendance_access_control_systems_calendar_rel` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `calendar_id` int UNSIGNED NOT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `processed_status` smallint NOT NULL DEFAULT '0',
  `error_message` json DEFAULT NULL COMMENT 'Hiba leírása',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `key2057` (`calendar_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `attendance_access_control_systems_extra_addresses`
--

DROP TABLE IF EXISTS `attendance_access_control_systems_extra_addresses`;
CREATE TABLE IF NOT EXISTS `attendance_access_control_systems_extra_addresses` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `core_firms_id` int UNSIGNED NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL COMMENT 'e-mail cím',
  PRIMARY KEY (`id`),
  KEY `key20031` (`core_firms_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `attendance_access_control_systems_rules`
--

DROP TABLE IF EXISTS `attendance_access_control_systems_rules`;
CREATE TABLE IF NOT EXISTS `attendance_access_control_systems_rules` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `core_firms_id` int UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL COMMENT 'szabály neve',
  `arrive_tolerance_time_minus` tinyint DEFAULT NULL COMMENT 'érkezés - minus tolerancia idő',
  `arrive_tolerance_time_plus` tinyint DEFAULT NULL COMMENT 'érkezés - plus tolerancia idő',
  `arrive_tolerance_time_out_boss_rejection` tinyint(1) NOT NULL DEFAULT '0',
  `arrive_tolerance_times_active` tinyint(1) NOT NULL DEFAULT '0',
  `arrive_rounding` tinyint NOT NULL COMMENT 'érkezés - kerekítés',
  `arrive_double_data` tinyint NOT NULL COMMENT 'érkezés - dupla adat érkezésekor',
  `leave_tolerance_time_minus` tinyint DEFAULT NULL COMMENT 'távozás - minus tolerancia idő',
  `leave_tolerance_time_plus` tinyint DEFAULT NULL COMMENT 'távozás - plus tolerancia idő',
  `leave_tolerance_time_out_boss_rejection` tinyint(1) NOT NULL DEFAULT '0',
  `leave_tolerance_times_active` tinyint(1) NOT NULL DEFAULT '0',
  `leave_rounding` tinyint NOT NULL COMMENT 'távozás - kerekítés',
  `leave_double_data` tinyint NOT NULL COMMENT 'távozás - dupla adat érkezésekor',
  `over_work_handle` tinyint DEFAULT NULL COMMENT 'Túlmunka kezelése mint',
  `fault_sign` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'hiba jelzés',
  `double_checking_time` tinyint NOT NULL COMMENT 'dupla lehúzások vizsgálata ezen időben',
  PRIMARY KEY (`id`),
  KEY `key2058` (`core_firms_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `attendance_approval_buttons`
--

DROP TABLE IF EXISTS `attendance_approval_buttons`;
CREATE TABLE IF NOT EXISTS `attendance_approval_buttons` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `core_email_notifications_id` int UNSIGNED NOT NULL COMMENT 'email üzenet azonosítója',
  `legalentity_id` int UNSIGNED NOT NULL COMMENT 'jogviszony azonosítója',
  `token` varchar(42) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL COMMENT 'Token egyedi azonosító',
  `yes` varchar(2) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL COMMENT 'Jóváhagyás azonosítója',
  `no` varchar(2) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL COMMENT 'Jóváhagyás elutasításának azonosítója',
  `use_status` int NOT NULL DEFAULT '0' COMMENT 'Használat',
  `expires_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `key12798` (`legalentity_id`),
  KEY `key12799` (`core_email_notifications_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `attendance_approval_links`
--

DROP TABLE IF EXISTS `attendance_approval_links`;
CREATE TABLE IF NOT EXISTS `attendance_approval_links` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `token` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL COMMENT 'Token egyedi azonosító',
  `user_id` int UNSIGNED NOT NULL COMMENT 'jogosult felhasználó azonosítója',
  `legalentity_id` int UNSIGNED NOT NULL COMMENT 'jogviszony azonosítója',
  `core_email_notifications_id` int UNSIGNED NOT NULL COMMENT 'email üzenet azonosítója',
  `month` varchar(7) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL COMMENT 'A jóváhagyandó hónap (éééé-hh)',
  `expires_at` datetime DEFAULT NULL COMMENT 'a token lejárati dátuma (éééé-hh-nn óó:pp:ss)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `attendance_approval_links_token_unique` (`token`),
  KEY `key7006` (`user_id`),
  KEY `key7007` (`legalentity_id`),
  KEY `key7008` (`core_email_notifications_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `attendance_attendance_entity_selector_settings`
--

DROP TABLE IF EXISTS `attendance_attendance_entity_selector_settings`;
CREATE TABLE IF NOT EXISTS `attendance_attendance_entity_selector_settings` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `core_firms_id` int UNSIGNED NOT NULL,
  `column_settings` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `column_settings_label` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `column_settings_class` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `column_settings_hide` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `attendance_attendance_sheet_export_settings`
--

DROP TABLE IF EXISTS `attendance_attendance_sheet_export_settings`;
CREATE TABLE IF NOT EXISTS `attendance_attendance_sheet_export_settings` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `core_firms_id` int UNSIGNED NOT NULL,
  `header_settings` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `body_settings` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `footer_settings` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key1012` (`core_firms_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `attendance_attendance_sheet_settings`
--

DROP TABLE IF EXISTS `attendance_attendance_sheet_settings`;
CREATE TABLE IF NOT EXISTS `attendance_attendance_sheet_settings` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `core_firms_id` int UNSIGNED NOT NULL,
  `header_settings` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `body_settings` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `body_settings_name` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `footer_settings` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `footer_settings_name` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `key0062` (`core_firms_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `attendance_bonus_types`
--

DROP TABLE IF EXISTS `attendance_bonus_types`;
CREATE TABLE IF NOT EXISTS `attendance_bonus_types` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `core_firms_id` int UNSIGNED NOT NULL,
  `payroll_software_id` varchar(8) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `overtime` tinyint NOT NULL DEFAULT '0',
  `bonus_within_shift` tinyint NOT NULL DEFAULT '0' COMMENT 'Egyéb műszakon belüli pótlék',
  `excess_hour` tinyint NOT NULL DEFAULT '0' COMMENT 'Többletóra',
  `deficit_hour` tinyint NOT NULL DEFAULT '0' COMMENT 'Hiányóra',
  `abscence_hour` tinyint NOT NULL DEFAULT '0' COMMENT 'csúsztatás órái',
  `rest_time_difference` tinyint NOT NULL DEFAULT '0' COMMENT 'pihenőnapi különbség',
  PRIMARY KEY (`id`),
  KEY `key4002` (`core_firms_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `attendance_day_types`
--

DROP TABLE IF EXISTS `attendance_day_types`;
CREATE TABLE IF NOT EXISTS `attendance_day_types` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `attendance_holiday_rules`
--

DROP TABLE IF EXISTS `attendance_holiday_rules`;
CREATE TABLE IF NOT EXISTS `attendance_holiday_rules` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `notification` tinyint(1) NOT NULL,
  `core_firms_id` int NOT NULL,
  `year` int NOT NULL COMMENT 'szabályzat érvényessége',
  `creator_legal_entities_id` int NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `attendance_holiday_rules_legal_entities_rel`
--

DROP TABLE IF EXISTS `attendance_holiday_rules_legal_entities_rel`;
CREATE TABLE IF NOT EXISTS `attendance_holiday_rules_legal_entities_rel` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `attendance_holiday_rule_id` int UNSIGNED NOT NULL,
  `core_legal_entities_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key2106` (`attendance_holiday_rule_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `attendance_holiday_rule_schedules`
--

DROP TABLE IF EXISTS `attendance_holiday_rule_schedules`;
CREATE TABLE IF NOT EXISTS `attendance_holiday_rule_schedules` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `attendance_holiday_rule_id` int UNSIGNED NOT NULL,
  `planning_from_date` date NOT NULL,
  `planning_to_date` date NOT NULL,
  `planning_deadline` date DEFAULT NULL,
  `percentage_of_holidays` tinyint NOT NULL,
  `minimum_number_of_required_days` tinyint DEFAULT NULL,
  `worker_has` tinyint DEFAULT NULL,
  `required_at_once` tinyint DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `key2108` (`attendance_holiday_rule_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `attendance_holiday_rule_schedules_permission_groups_rel`
--

DROP TABLE IF EXISTS `attendance_holiday_rule_schedules_permission_groups_rel`;
CREATE TABLE IF NOT EXISTS `attendance_holiday_rule_schedules_permission_groups_rel` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `attendance_holiday_rule_schedule_id` int UNSIGNED NOT NULL,
  `core_permission_group_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key2107` (`attendance_holiday_rule_schedule_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `attendance_legal_entities_abscences`
--

DROP TABLE IF EXISTS `attendance_legal_entities_abscences`;
CREATE TABLE IF NOT EXISTS `attendance_legal_entities_abscences` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `registered_calendar_id` int UNSIGNED DEFAULT NULL,
  `payroll_calendar_id` int UNSIGNED DEFAULT NULL,
  `attendance_legal_entities_shifts_id` int UNSIGNED DEFAULT NULL,
  `attendance_abscence_types_id` int UNSIGNED DEFAULT NULL,
  `attendance_offday_types_id` int UNSIGNED DEFAULT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `note_legal_entity` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `note_boss` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `creator_legal_entity_id` int UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT '1000-01-01 00:00:00',
  `updated_at` datetime DEFAULT '1000-01-01 00:00:00',
  PRIMARY KEY (`id`),
  KEY `key0053` (`registered_calendar_id`),
  KEY `key0054` (`payroll_calendar_id`),
  KEY `key0055` (`attendance_legal_entities_shifts_id`),
  KEY `key0056` (`attendance_abscence_types_id`),
  KEY `key0057` (`attendance_offday_types_id`),
  KEY `key0058` (`creator_legal_entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `attendance_legal_entities_bonuses_within_shift`
--

DROP TABLE IF EXISTS `attendance_legal_entities_bonuses_within_shift`;
CREATE TABLE IF NOT EXISTS `attendance_legal_entities_bonuses_within_shift` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `registered_calendar_id` int UNSIGNED NOT NULL,
  `payroll_calendar_id` int UNSIGNED NOT NULL,
  `attendance_legal_entities_shifts_id` int UNSIGNED NOT NULL,
  `attendance_bonus_types_id` int UNSIGNED NOT NULL,
  `creator_legal_entity_id` int UNSIGNED NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key3006` (`registered_calendar_id`),
  KEY `key3007` (`payroll_calendar_id`),
  KEY `key3008` (`attendance_legal_entities_shifts_id`),
  KEY `key3009` (`attendance_bonus_types_id`),
  KEY `key3010` (`creator_legal_entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `attendance_legal_entities_calendar`
--

DROP TABLE IF EXISTS `attendance_legal_entities_calendar`;
CREATE TABLE IF NOT EXISTS `attendance_legal_entities_calendar` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `core_legal_entities_id` int UNSIGNED NOT NULL DEFAULT '0',
  `_date` date NOT NULL,
  `balance` int NOT NULL DEFAULT '0',
  `rolled_balance` int NOT NULL DEFAULT '0',
  `balance_to_overtime` tinyint NOT NULL DEFAULT '0' COMMENT '0: ha nincs még feladva túlóraként, 1: ha már fel lett adva',
  `legal_entity_confirmed` tinyint NOT NULL DEFAULT '0',
  `legal_entity_confirmed_date` datetime DEFAULT '1000-01-01 00:00:00',
  `boss_confirmed` tinyint NOT NULL DEFAULT '0',
  `boss_confirmed_date` datetime DEFAULT '1000-01-01 00:00:00',
  `boss_confirmed_id` int UNSIGNED DEFAULT NULL,
  `winaccess_failure` tinyint DEFAULT '0' COMMENT 'sikertelen volt-e a WinAccess beemelési kísérlet (érvényes értékek: 0 - nem, 1 - igen)',
  `full_day_bonus` tinyint NOT NULL DEFAULT '0' COMMENT 'Ki lett-e jelölve egész napos pótlékra a nap (igen: 1, nem: 0, alapértelmezett: 0)',
  `created_at` datetime DEFAULT '1000-01-01 00:00:00',
  `updated_at` datetime DEFAULT '1000-01-01 00:00:00',
  PRIMARY KEY (`id`),
  KEY `key0035` (`core_legal_entities_id`),
  KEY `key0036` (`boss_confirmed_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `attendance_legal_entities_calendar_attendance_places_of_work_rel`
--

DROP TABLE IF EXISTS `attendance_legal_entities_calendar_attendance_places_of_work_rel`;
CREATE TABLE IF NOT EXISTS `attendance_legal_entities_calendar_attendance_places_of_work_rel` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `attendance_legal_entities_calendar_id` int UNSIGNED DEFAULT NULL,
  `attendance_places_of_work_id` int UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `key0049` (`attendance_legal_entities_calendar_id`),
  KEY `key0050` (`attendance_places_of_work_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `attendance_legal_entities_calendar_rejections`
--

DROP TABLE IF EXISTS `attendance_legal_entities_calendar_rejections`;
CREATE TABLE IF NOT EXISTS `attendance_legal_entities_calendar_rejections` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `attendance_legal_entities_calendar_id` int UNSIGNED DEFAULT NULL,
  `core_legal_entities_id` int UNSIGNED DEFAULT NULL,
  `note` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT '1000-01-01 00:00:00',
  `updated_at` datetime DEFAULT '1000-01-01 00:00:00',
  PRIMARY KEY (`id`),
  KEY `key0051` (`attendance_legal_entities_calendar_id`),
  KEY `key0052` (`core_legal_entities_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `attendance_legal_entities_carried_balance`
--

DROP TABLE IF EXISTS `attendance_legal_entities_carried_balance`;
CREATE TABLE IF NOT EXISTS `attendance_legal_entities_carried_balance` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `core_legal_entities_id` int UNSIGNED NOT NULL COMMENT 'jogviszony azonosítója',
  `balance` int NOT NULL COMMENT 'áthozott balance idő percben',
  PRIMARY KEY (`id`),
  KEY `key2008` (`core_legal_entities_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `attendance_legal_entities_details_backup`
--

DROP TABLE IF EXISTS `attendance_legal_entities_details_backup`;
CREATE TABLE IF NOT EXISTS `attendance_legal_entities_details_backup` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `core_legal_entities_id` int UNSIGNED NOT NULL,
  `content` json NOT NULL,
  `status` tinyint(1) DEFAULT NULL COMMENT 'Látta e már',
  `valid_time` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `key2568` (`core_legal_entities_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `attendance_legal_entities_holiday_amounts`
--

DROP TABLE IF EXISTS `attendance_legal_entities_holiday_amounts`;
CREATE TABLE IF NOT EXISTS `attendance_legal_entities_holiday_amounts` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `core_legal_entities_id` int UNSIGNED NOT NULL,
  `attendance_offday_types_id` int UNSIGNED NOT NULL,
  `year` int NOT NULL,
  `value` int NOT NULL,
  `used_value` int NOT NULL,
  `amount_from_hq` int UNSIGNED DEFAULT NULL COMMENT 'Felhasznált szabadságok értéke a Központi csv importálás során kap értéket',
  PRIMARY KEY (`id`),
  KEY `key4016` (`core_legal_entities_id`),
  KEY `key4017` (`attendance_offday_types_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `attendance_legal_entities_outside_shifts_time`
--

DROP TABLE IF EXISTS `attendance_legal_entities_outside_shifts_time`;
CREATE TABLE IF NOT EXISTS `attendance_legal_entities_outside_shifts_time` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` tinyint NOT NULL DEFAULT '0' COMMENT '1: készenlét, 2: ügyelet',
  `attendance_legal_entities_calendar_id` int UNSIGNED DEFAULT NULL,
  `attendance_standby_types_id` int UNSIGNED DEFAULT NULL COMMENT 'a készenlét szótári azonosítója',
  `creator_legal_entity_id` int UNSIGNED DEFAULT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `note_legal_entity` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `note_boss` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key0045` (`attendance_legal_entities_calendar_id`),
  KEY `key1020` (`attendance_standby_types_id`),
  KEY `key0046` (`creator_legal_entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `attendance_legal_entities_outside_shifts_time_bonuses_rel`
--

DROP TABLE IF EXISTS `attendance_legal_entities_outside_shifts_time_bonuses_rel`;
CREATE TABLE IF NOT EXISTS `attendance_legal_entities_outside_shifts_time_bonuses_rel` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `attendance_legal_entities_outside_shifts_time_id` int UNSIGNED DEFAULT NULL,
  `attendance_bonus_types_id` int UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `key0047` (`attendance_legal_entities_outside_shifts_time_id`),
  KEY `key0048` (`attendance_bonus_types_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `attendance_legal_entities_overtimes`
--

DROP TABLE IF EXISTS `attendance_legal_entities_overtimes`;
CREATE TABLE IF NOT EXISTS `attendance_legal_entities_overtimes` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `registered_calendar_id` int UNSIGNED DEFAULT NULL,
  `payroll_calendar_id` int UNSIGNED DEFAULT NULL,
  `attendance_legal_entities_shifts_id` int UNSIGNED DEFAULT NULL,
  `creator_legal_entity_id` int UNSIGNED DEFAULT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `overtime_minutes` int NOT NULL DEFAULT '0',
  `shifted_minutes` int NOT NULL DEFAULT '0',
  `export_date` date DEFAULT NULL,
  `note_legal_entity` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `note_boss` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `key0039` (`registered_calendar_id`),
  KEY `key0040` (`payroll_calendar_id`),
  KEY `key0041` (`attendance_legal_entities_shifts_id`),
  KEY `key0042` (`creator_legal_entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `attendance_legal_entities_overtimes_bonuses_rel`
--

DROP TABLE IF EXISTS `attendance_legal_entities_overtimes_bonuses_rel`;
CREATE TABLE IF NOT EXISTS `attendance_legal_entities_overtimes_bonuses_rel` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `attendance_legal_entities_overtimes_id` int UNSIGNED DEFAULT NULL,
  `attendance_bonus_types_id` int UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `key0043` (`attendance_legal_entities_overtimes_id`),
  KEY `key0044` (`attendance_bonus_types_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `attendance_legal_entities_shifts`
--

DROP TABLE IF EXISTS `attendance_legal_entities_shifts`;
CREATE TABLE IF NOT EXISTS `attendance_legal_entities_shifts` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `registered_calendar_id` int UNSIGNED DEFAULT NULL,
  `payroll_calendar_id` int UNSIGNED DEFAULT NULL,
  `attendance_shift_types_id` int UNSIGNED DEFAULT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `shift_break_time` int NOT NULL DEFAULT '0',
  `balance_flag` tinyint NOT NULL DEFAULT '0',
  `preparation_start` time DEFAULT NULL COMMENT 'A felkészülési idő kezdete',
  `preparation_end` time DEFAULT NULL COMMENT 'A felkészülési idő vége',
  PRIMARY KEY (`id`),
  KEY `key0037` (`registered_calendar_id`),
  KEY `key0038` (`attendance_shift_types_id`),
  KEY `key0064` (`payroll_calendar_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `attendance_legal_entities_shifts_balances`
--

DROP TABLE IF EXISTS `attendance_legal_entities_shifts_balances`;
CREATE TABLE IF NOT EXISTS `attendance_legal_entities_shifts_balances` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `registered_calendar_id` int UNSIGNED DEFAULT NULL,
  `payroll_calendar_id` int UNSIGNED DEFAULT NULL,
  `attendance_legal_entities_shifts_id` int UNSIGNED DEFAULT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `negative` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `key0059` (`registered_calendar_id`),
  KEY `key0060` (`payroll_calendar_id`),
  KEY `key0061` (`attendance_legal_entities_shifts_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `attendance_legal_entities_shifts_workhours`
--

DROP TABLE IF EXISTS `attendance_legal_entities_shifts_workhours`;
CREATE TABLE IF NOT EXISTS `attendance_legal_entities_shifts_workhours` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `registered_calendar_id` int UNSIGNED DEFAULT NULL,
  `payroll_calendar_id` int UNSIGNED DEFAULT NULL,
  `attendance_legal_entities_shifts_id` int UNSIGNED DEFAULT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key3000` (`registered_calendar_id`),
  KEY `key3001` (`payroll_calendar_id`),
  KEY `key3002` (`attendance_legal_entities_shifts_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `attendance_legal_stock`
--

DROP TABLE IF EXISTS `attendance_legal_stock`;
CREATE TABLE IF NOT EXISTS `attendance_legal_stock` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `core_firms_id` int UNSIGNED DEFAULT NULL COMMENT 'külső kulcs a cég azonosítójára',
  `core_legal_entities_id` int UNSIGNED NOT NULL COMMENT 'külső kulcs a jogviszony azonosítójára',
  `attendance_abscence_types_id` int UNSIGNED NOT NULL COMMENT 'külső kulcs a távolléttípus azonosítójára',
  `start_date` date NOT NULL COMMENT 'a jogviszony ettől a naptól van távol',
  `end_date` date NOT NULL COMMENT 'a jogviszony eddig a napig van távol',
  PRIMARY KEY (`id`),
  KEY `key1008` (`core_legal_entities_id`),
  KEY `key1009` (`attendance_abscence_types_id`),
  KEY `key1010` (`core_firms_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `attendance_offday_types`
--

DROP TABLE IF EXISTS `attendance_offday_types`;
CREATE TABLE IF NOT EXISTS `attendance_offday_types` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `payroll_software_id` varchar(8) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `selectable` tinyint NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `attendance_offday_types_core_firms_order`
--

DROP TABLE IF EXISTS `attendance_offday_types_core_firms_order`;
CREATE TABLE IF NOT EXISTS `attendance_offday_types_core_firms_order` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `attendance_offday_types_id` int UNSIGNED NOT NULL,
  `core_firms_id` int UNSIGNED NOT NULL,
  `_order` tinyint DEFAULT NULL,
  `status` tinyint NOT NULL,
  `core_other_incomes_id` int UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `key0013` (`attendance_offday_types_id`),
  KEY `key0014` (`core_firms_id`),
  KEY `key2078` (`core_other_incomes_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `attendance_places_of_work`
--

DROP TABLE IF EXISTS `attendance_places_of_work`;
CREATE TABLE IF NOT EXISTS `attendance_places_of_work` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `core_firms_id` int UNSIGNED NOT NULL,
  `name` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `status` tinyint NOT NULL,
  `bonus_status` tinyint NOT NULL DEFAULT '0' COMMENT 'Pótlékolható',
  `authorization` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL COMMENT 'rögzítés hatásköre, ki rögzítheti az adott helyet - (Admin: 1, Vezető: 2, Dolgozó: 3)',
  PRIMARY KEY (`id`),
  KEY `key4007` (`core_firms_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `attendance_places_of_work_bonuses_rel`
--

DROP TABLE IF EXISTS `attendance_places_of_work_bonuses_rel`;
CREATE TABLE IF NOT EXISTS `attendance_places_of_work_bonuses_rel` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `attendance_places_of_work_id` int UNSIGNED NOT NULL COMMENT 'külső kulcs a munkavégzés helye táblájához',
  `attendance_bonus_types_id` int UNSIGNED NOT NULL COMMENT 'külső kulcs a pótlékok táblájához',
  PRIMARY KEY (`id`),
  KEY `key2098` (`attendance_places_of_work_id`),
  KEY `key2099` (`attendance_bonus_types_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `attendance_rounding_settings`
--

DROP TABLE IF EXISTS `attendance_rounding_settings`;
CREATE TABLE IF NOT EXISTS `attendance_rounding_settings` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `rounding_id` tinyint NOT NULL,
  `core_firms_id` int UNSIGNED NOT NULL,
  `prevalence` tinyint NOT NULL,
  `rate` tinyint NOT NULL,
  `accuracy` tinyint NOT NULL,
  `rule` tinyint NOT NULL,
  `type` tinyint NOT NULL DEFAULT '1' COMMENT 'Típus (1: törzs, 2: lábléc)',
  `display` tinyint NOT NULL DEFAULT '1' COMMENT 'Megjelenítés (1: óra:perc, 2: óra)',
  PRIMARY KEY (`id`),
  KEY `key1011` (`core_firms_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `attendance_scheduler_templates`
--

DROP TABLE IF EXISTS `attendance_scheduler_templates`;
CREATE TABLE IF NOT EXISTS `attendance_scheduler_templates` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `firm_id` tinyint DEFAULT NULL,
  `legal_entity_id` int UNSIGNED NOT NULL DEFAULT '0',
  `title` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `default` tinyint NOT NULL DEFAULT '0',
  `config` varchar(1000) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `attendance_shift_breaks`
--

DROP TABLE IF EXISTS `attendance_shift_breaks`;
CREATE TABLE IF NOT EXISTS `attendance_shift_breaks` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `attendance_shift_types_id` int UNSIGNED NOT NULL COMMENT 'műszaktípus azonosítója a attendance_shift_types táblából',
  `start_time` time NOT NULL DEFAULT '00:00:00' COMMENT 'kezdő időpont',
  `end_time` time NOT NULL DEFAULT '00:00:00' COMMENT 'záró időpont',
  `is_bonused` tinyint NOT NULL DEFAULT '0' COMMENT 'Pótlékolható-e',
  `length` int NOT NULL DEFAULT '0' COMMENT 'szünet tartama',
  PRIMARY KEY (`id`),
  KEY `key7000` (`attendance_shift_types_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `attendance_shift_types`
--

DROP TABLE IF EXISTS `attendance_shift_types`;
CREATE TABLE IF NOT EXISTS `attendance_shift_types` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `core_firms_id` int UNSIGNED NOT NULL,
  `payroll_software_id` varchar(8) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `code` varchar(8) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `trunk_time_start` time NOT NULL DEFAULT '00:00:00',
  `trunk_time_end` time NOT NULL DEFAULT '00:00:00',
  `edge_time_start` time NOT NULL DEFAULT '00:00:00',
  `edge_time_end` time NOT NULL DEFAULT '00:00:00',
  `bonus1_time_start` time DEFAULT NULL,
  `bonus1_time_end` time DEFAULT NULL,
  `bonus2_time_start` time DEFAULT NULL,
  `bonus2_time_end` time DEFAULT NULL,
  `breaktime` int DEFAULT NULL,
  `breaktime_start` time DEFAULT NULL,
  `breaktime_end` time DEFAULT NULL,
  `trunk_attendance_bonuses` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `edge_attendance_bonuses` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `status` tinyint NOT NULL DEFAULT '1',
  `editable` tinyint(1) NOT NULL DEFAULT '1',
  `sunday_bonus_time_start` time DEFAULT NULL COMMENT 'vasárnapi pótlék kezdete',
  `sunday_bonus_time_end` time DEFAULT NULL COMMENT 'vasárnapi pótlék vége',
  `sunday_bonus_type_id` int UNSIGNED DEFAULT NULL COMMENT 'külső kulcs a pótlékok táblájához',
  `holiday_bonus_time_start` time DEFAULT NULL COMMENT 'ünnepnapi pótlék kezdete',
  `holiday_bonus_time_end` time DEFAULT NULL COMMENT 'ünnepnapi pótlék vége',
  `holiday_bonus_type_id` int UNSIGNED DEFAULT NULL COMMENT 'külső kulcs a pótlékok táblájához',
  PRIMARY KEY (`id`),
  KEY `key4003` (`core_firms_id`),
  KEY `key20300` (`sunday_bonus_type_id`),
  KEY `key20301` (`holiday_bonus_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `attendance_standby_overtimes`
--

DROP TABLE IF EXISTS `attendance_standby_overtimes`;
CREATE TABLE IF NOT EXISTS `attendance_standby_overtimes` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `attendance_legal_entities_outside_shifts_time_id` int UNSIGNED NOT NULL COMMENT 'a készenlét azonosítója a jogviszony naptárában',
  `start_time` time NOT NULL COMMENT 'a túlóra kezdő időpontja',
  `end_time` time NOT NULL COMMENT 'a túlóra záró időpontja',
  `work_by_traveling` tinyint NOT NULL DEFAULT '0' COMMENT 'utazással járt-e a munkavégzés (0 - nem, 1 - igen)',
  `note` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL COMMENT 'megjegyzés a túlórához',
  PRIMARY KEY (`id`),
  KEY `key1021` (`attendance_legal_entities_outside_shifts_time_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `attendance_standby_types`
--

DROP TABLE IF EXISTS `attendance_standby_types`;
CREATE TABLE IF NOT EXISTS `attendance_standby_types` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `core_firms_id` int UNSIGNED NOT NULL COMMENT 'külső kulcs a core_firms tábla id mezőjére',
  `code` varchar(3) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `name` varchar(45) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL COMMENT 'Készenlét megnevezése',
  `start_time` time NOT NULL COMMENT 'Készenlét kezdetének ideje',
  `end_time` time NOT NULL COMMENT 'Készenlét lezárásának ideje',
  `travel_time` int NOT NULL COMMENT 'Utazási idő hossza percben',
  `status` tinyint NOT NULL COMMENT 'Állapot: 0: inaktív, 1: aktív',
  `editable` tinyint NOT NULL DEFAULT '1' COMMENT 'Szerkeszthető: 0: nem, 1: igen',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UK_attendance_standby_types_core_firm_id_code` (`core_firms_id`,`code`),
  UNIQUE KEY `UK_attendance_standby_types_core_firms_id_name` (`core_firms_id`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `attendance_verification_conditions`
--

DROP TABLE IF EXISTS `attendance_verification_conditions`;
CREATE TABLE IF NOT EXISTS `attendance_verification_conditions` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `core_firms_id` int DEFAULT NULL,
  `workplan_category` int DEFAULT NULL COMMENT 'melyik munkarendre irányul a vizsgálat',
  `type` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '' COMMENT 'milyen típusra irányul a vizsgálat (munkaidő, munkanap, stb.)',
  `operator` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '' COMMENT 'a vizsgálat iránya (minimum, maximum)',
  `unit` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '' COMMENT 'mennyiségi egység (óra, nap, darab, stb.)',
  `period` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '' COMMENT 'vizsgált időszak (nap, hét, hónap, stb.)',
  `value` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '' COMMENT 'milyen értékre irányul a vizsgálat',
  `base_rule` int NOT NULL DEFAULT '0' COMMENT 'alap szabály-e?',
  `prev_version_id` int DEFAULT NULL COMMENT 'előző verzió azonsítója',
  `validity_start` datetime DEFAULT '1970-01-01 00:00:00' COMMENT 'érvényesség kezdete',
  `validity_end` datetime DEFAULT NULL COMMENT 'érvényesség vége',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `attendance_verification_conditions_details`
--

DROP TABLE IF EXISTS `attendance_verification_conditions_details`;
CREATE TABLE IF NOT EXISTS `attendance_verification_conditions_details` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `attendance_verification_conditions_id` int UNSIGNED NOT NULL,
  `core_localization_id` int UNSIGNED NOT NULL DEFAULT '0',
  `title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `error_message` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `key2061` (`attendance_verification_conditions_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `attendance_verification_conditions_parameters`
--

DROP TABLE IF EXISTS `attendance_verification_conditions_parameters`;
CREATE TABLE IF NOT EXISTS `attendance_verification_conditions_parameters` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `core_localization_id` int UNSIGNED NOT NULL DEFAULT '0',
  `parameter` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `parameter_type` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `parameter_value` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `attendance_verification_condition_systems`
--

DROP TABLE IF EXISTS `attendance_verification_condition_systems`;
CREATE TABLE IF NOT EXISTS `attendance_verification_condition_systems` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `core_firms_id` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key0032` (`core_firms_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `attendance_verification_condition_values`
--

DROP TABLE IF EXISTS `attendance_verification_condition_values`;
CREATE TABLE IF NOT EXISTS `attendance_verification_condition_values` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `attendance_verification_condition_system_id` int UNSIGNED NOT NULL,
  `attendance_verification_condition_id` int UNSIGNED NOT NULL,
  `value` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `key0033` (`attendance_verification_condition_system_id`),
  KEY `key0034` (`attendance_verification_condition_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `attendance_workday_divisions`
--

DROP TABLE IF EXISTS `attendance_workday_divisions`;
CREATE TABLE IF NOT EXISTS `attendance_workday_divisions` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `attendance_legal_entities_calendar_id` int UNSIGNED NOT NULL COMMENT 'a nap naptári azonosítója',
  `attendance_legal_entities_shifts_id` int UNSIGNED DEFAULT NULL COMMENT 'a műszak naptári azonosítója',
  `attendance_shift_breaks_id` int UNSIGNED DEFAULT NULL COMMENT 'müszak szünetének azonosítója',
  `attendance_legal_entities_overtimes_id` int UNSIGNED DEFAULT NULL COMMENT 'a műszakon kívüli túlóra naptári azonosítója',
  `attendance_legal_entities_outside_shifts_time_id` int UNSIGNED DEFAULT NULL COMMENT 'a készenlét naptári azonosítója',
  `attendance_standby_overtimes_id` int UNSIGNED DEFAULT NULL COMMENT 'a készenlét alatti túlóra naptári azonosítója',
  `attendance_legal_entities_abscences_id` int UNSIGNED DEFAULT NULL COMMENT 'a távollét naptári azonosítója',
  `start_time` time NOT NULL COMMENT 'a kezdés időpontja az adott network-ön',
  `end_time` time NOT NULL COMMENT 'a zárás időpontja az adott network-ön',
  `percent_start` int DEFAULT NULL,
  `percent_end` int DEFAULT NULL,
  `categories` json DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `key1027` (`attendance_legal_entities_calendar_id`),
  KEY `key1028` (`attendance_legal_entities_shifts_id`),
  KEY `key1029` (`attendance_legal_entities_overtimes_id`),
  KEY `key1030` (`attendance_legal_entities_outside_shifts_time_id`),
  KEY `key1031` (`attendance_standby_overtimes_id`),
  KEY `key1329` (`attendance_legal_entities_abscences_id`),
  KEY `key1330` (`attendance_shift_breaks_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `attendance_workday_divisions_order`
--

DROP TABLE IF EXISTS `attendance_workday_divisions_order`;
CREATE TABLE IF NOT EXISTS `attendance_workday_divisions_order` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `core_firms_id` int UNSIGNED NOT NULL,
  `divisions_id` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL COMMENT 'Code szerinti id: magyarázat a workdaydivision osztályban',
  `status` tinyint NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key2021` (`core_firms_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `attendance_workday_division_export_settings`
--

DROP TABLE IF EXISTS `attendance_workday_division_export_settings`;
CREATE TABLE IF NOT EXISTS `attendance_workday_division_export_settings` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `identification` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL COMMENT 'jogviszony azonosítása',
  `core_firms_id` int UNSIGNED NOT NULL,
  `factory_code` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL COMMENT 'Üzemkód',
  `cost_center` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL COMMENT 'Költséghely',
  `jobnumber` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL COMMENT 'Munkaszám',
  `skip_incapable_of_work` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'A keresőképtelenség kihagyását jelző flag',
  `core_general_ledger_analytics_ids` json DEFAULT NULL COMMENT 'dimenzió - fökönyvek azonosítói',
  `export_division_with_date` tinyint NOT NULL DEFAULT '0' COMMENT 'A felosztás tartalmazzon dátumot az exportban',
  `export_in_sum` tinyint NOT NULL DEFAULT '0' COMMENT 'Az export összegezve készüljön',
  `time_format_number` tinyint NOT NULL DEFAULT '0' COMMENT 'időadat exportálása szám formátumba 12:30=12,5',
  PRIMARY KEY (`id`),
  KEY `key1347` (`core_firms_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `attendance_workplans`
--

DROP TABLE IF EXISTS `attendance_workplans`;
CREATE TABLE IF NOT EXISTS `attendance_workplans` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `core_firms_id` int UNSIGNED NOT NULL,
  `payroll_software_id` varchar(8) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
  `name` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `attendance_workplan_categories_id` int UNSIGNED DEFAULT NULL COMMENT 'külső kulcs a munkarend kategóriák táblájához',
  `attendance_verification_condition_systems_id` int UNSIGNED DEFAULT NULL COMMENT 'külső kulcs a feltételrendszer tábla azonosítójához',
  `check_by_law` tinyint NOT NULL DEFAULT '0' COMMENT 'ellenőrizzünk-e Munka Törvénykönyve szerint (0: nem (alapértelmezett), 1: igen)',
  `warning_or_forbidding` tinyint NOT NULL DEFAULT '0' COMMENT 'ha ellenőrzünk és hiba van, figyelmeztessünk vagy tiltsunk (0: figyelmeztessünk (alapértelmezett), 1: tiltsunk)',
  `attendance_access_control_systems_id` int UNSIGNED DEFAULT NULL COMMENT 'külső kulcs a beléptetőrendszer azonosítójához',
  `attendance_access_control_systems_rule_id` int UNSIGNED DEFAULT NULL COMMENT 'beléptető rendszerben használt szabályrendszer',
  `holiday_bonus_id` int UNSIGNED DEFAULT NULL COMMENT 'ünnepnapi pótlék azonosítója az attendance_bonus_types táblából',
  `overtime_holiday_bonus_id` int UNSIGNED DEFAULT NULL COMMENT 'ünnepnapi túlóra pótlék azonosítója az attendance_bonus_types táblából',
  `restday_bonus_id` int UNSIGNED DEFAULT NULL COMMENT 'pihenőnapi pótlék azonosítója az attendance_bonus_types táblából',
  `sunday_bonus_id` int UNSIGNED DEFAULT NULL COMMENT 'vasárnapi pótlék azonosítója az attendance_bonus_types táblából',
  `overtime_bonus_id` int UNSIGNED DEFAULT NULL COMMENT 'túlóra pótlék azonosítója az attendance_bonus_types táblából',
  `overtime_base_bonus_id` int UNSIGNED DEFAULT NULL COMMENT 'túlóra alap pótlék azonosítója az attendance_bonus_types táblából',
  `stand_by_bonus_id` int UNSIGNED DEFAULT NULL COMMENT 'készenléti pótlék azonosítója az attendance_bonus_types táblából',
  `duty_bonus_id` int UNSIGNED DEFAULT NULL COMMENT 'ügyeleti pótlék azonosítója az attendance_bonus_types táblából',
  `balance_collector` tinyint NOT NULL DEFAULT '0' COMMENT 'keletkezik-e balanszidő (0: nem (alapértelmezett), 1: igen)',
  `balance_rolling` tinyint NOT NULL DEFAULT '0' COMMENT 'görgeti-e a keletkezett balanszidőt (0: nem (alapértelmezett), 1: igen)',
  `overtime_automatically_balance` tinyint NOT NULL DEFAULT '0' COMMENT 'a keletkezett túlmunka automatikusan a balanszidőbe kerüljön-e (0: nem (alapértelmezett), 1: igen)',
  `working_on_saturday` tinyint NOT NULL DEFAULT '0' COMMENT 'munkavégzés szombatra elrendelhető-e (0: nem (alapértelmezett), 1: igen)',
  `working_on_sunday` tinyint NOT NULL DEFAULT '0' COMMENT 'munkavégzés vasárnapra elrendelhető-e (0: nem (alapértelmezett), 1: igen)',
  `working_on_holiday` tinyint NOT NULL DEFAULT '0' COMMENT 'munkavégzés ünnepnapra elrendelhető-e (0: nem (alapértelmezett), 1: igen)',
  `workday_time_start` time NOT NULL DEFAULT '00:00:00' COMMENT 'speciális esetben egy nap nem 0 órakor kezdődik, hanem ekkor',
  `automatic_monitoring_bonus` tinyint NOT NULL DEFAULT '0' COMMENT 'műszak/éjszakai pótlék automatikus figyelése - exporthoz (igen: 1, nem: 0, alapértelmezett: 0)',
  `automatic_calculations_bonus` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'pótlékszámítás automatikus legyen-e - jelenléti ívhez (igen: 1, nem: 0, alapértelmezett: 1)',
  `how_quotient_of_difference_calculate_shift_bonus` tinyint(1) DEFAULT NULL COMMENT 'hány % eltérés esetén számoljon műszak pótlékot (érvényes értékek: 1-99)',
  `minimum_difference_between_shift_starts` tinyint DEFAULT NULL COMMENT 'műszak kezdések közötti minimum eltérés órában (érvényes értékek: 1-12)',
  `working_with_schedule_planner` tinyint NOT NULL DEFAULT '0' COMMENT 'a munkarend együttműködik a beosztástervezővel (igen: 1, nem: 0, alapértelmezett: 0)',
  `supports_the_work_time_limits` tinyint NOT NULL DEFAULT '0' COMMENT 'a munkarend támogatja a munkaidőkereteket (igen: 1, nem: 0, alapértelmezett: 0)',
  `night_shift_start` time DEFAULT NULL COMMENT 'éjszakai műszak kezdete',
  `night_shift_end` time DEFAULT NULL COMMENT 'éjszakai műszak vége',
  `night_shift_bonus_id` int UNSIGNED DEFAULT NULL COMMENT 'éjszakai műszak pótlék azonosítója az attendance_bonus_types táblából',
  `afternoon_shift_start` time DEFAULT NULL COMMENT 'délutáni műszak kezdete',
  `afternoon_shift_end` time DEFAULT NULL COMMENT 'délutáni műszak vége',
  `afternoon_shift_bonus_id` int UNSIGNED DEFAULT NULL COMMENT 'délutáni műszak pótlék azonosítója az attendance_bonus_types táblából',
  `number_of_hours_per_day` decimal(4,2) DEFAULT NULL COMMENT 'napi óraszám - az éves túlórakeret kiszámításához (érvényes értékek: 0.01-24, 0.01-es léptetéssel)',
  `number_of_hours_per_day_is_default_if_absence` tinyint NOT NULL DEFAULT '0' COMMENT 'Egész napos távollét esetén a munkarend alapértelmezett óraszáma kerüljön számfejtésre a műszak óraszáma helyett (igen: 1, nem: 0, alapértelmezett: 0)',
  `status` tinyint NOT NULL DEFAULT '1',
  `shift_bonus_start` time DEFAULT NULL COMMENT 'műszak pótlék kezdete',
  `shift_bonus_end` time DEFAULT NULL COMMENT 'műszak pótlék vége',
  `shift_bonus_bonus_id` int UNSIGNED DEFAULT NULL COMMENT 'műszak pótlék azonosítója az attendance_bonus_types táblából',
  `overtime_shift_bonus_bonus_id` int UNSIGNED DEFAULT NULL COMMENT 'túlóráért járó műszakpótlék azonosítója az attendance_bonus_types táblából',
  `overtime_shift_bonus_afternoon_bonus_id` int UNSIGNED DEFAULT NULL COMMENT 'Túlóráért járó délutáni műszakpótlék azonosítója az attendance_bonus_types táblából',
  `overtime_shift_bonus_night_bonus_id` int UNSIGNED DEFAULT NULL COMMENT 'Túlóráért járó éjszakai műszakpótlék azonosítója az attendance_bonus_types táblából',
  `extra_bonus_start` time DEFAULT NULL COMMENT 'extra pótlék kezdete',
  `extra_bonus_end` time DEFAULT NULL COMMENT 'extra pótlék vége',
  `extra_bonus_bonus_id` int UNSIGNED DEFAULT NULL COMMENT 'extra pótlék azonosítója az attendance_bonus_types táblából',
  `travel_time_workday_bonus_type_id` int UNSIGNED DEFAULT NULL COMMENT 'utazási idő munkanap pótlék tárgyhónapban',
  `travel_time_restday_bonus_type_id` int UNSIGNED DEFAULT NULL COMMENT 'utazási idő pihenőnap pótlék tárgyhónapban',
  `preparation_time_length` tinyint DEFAULT NULL COMMENT 'Felkészülési idő hossza műszak előtt (érvényes értékek: 0-60)',
  `preparation_time_range` tinyint DEFAULT NULL COMMENT 'Felkészülés eltolási idősávja (érvényes értékek: 0-120)',
  `preparation_time_bonus_id` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL COMMENT 'Felkészülési idő pótlék azonosítója az attendance_bonus_types táblából',
  `show_day_details_menu` tinyint NOT NULL DEFAULT '1' COMMENT 'Nap részletezése menüpont megjelenítése',
  `place_of_work_bonus_status_listen` tinyint NOT NULL DEFAULT '0' COMMENT 'munkavégzés helye pótlékolásának figyelése',
  PRIMARY KEY (`id`),
  UNIQUE KEY `attendance_workplans_name_core_firms_id_unique` (`name`,`core_firms_id`),
  KEY `key0015` (`core_firms_id`),
  KEY `key0016` (`attendance_workplan_categories_id`),
  KEY `key0017` (`attendance_verification_condition_systems_id`),
  KEY `key0018` (`attendance_access_control_systems_id`),
  KEY `key0019` (`holiday_bonus_id`),
  KEY `key0020` (`restday_bonus_id`),
  KEY `key0021` (`sunday_bonus_id`),
  KEY `key0022` (`overtime_bonus_id`),
  KEY `key0023` (`stand_by_bonus_id`),
  KEY `key0024` (`duty_bonus_id`),
  KEY `key1005` (`night_shift_bonus_id`),
  KEY `key1006` (`afternoon_shift_bonus_id`),
  KEY `key1007` (`overtime_base_bonus_id`),
  KEY `key20044` (`travel_time_workday_bonus_type_id`),
  KEY `key20046` (`travel_time_restday_bonus_type_id`),
  KEY `key2060` (`attendance_access_control_systems_rule_id`),
  KEY `key7013` (`overtime_holiday_bonus_id`),
  KEY `key7014` (`overtime_shift_bonus_bonus_id`),
  KEY `key7015` (`overtime_shift_bonus_afternoon_bonus_id`),
  KEY `key7016` (`overtime_shift_bonus_night_bonus_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `attendance_workplans_additional_bonuses`
--

DROP TABLE IF EXISTS `attendance_workplans_additional_bonuses`;
CREATE TABLE IF NOT EXISTS `attendance_workplans_additional_bonuses` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `attendance_workplans_id` int UNSIGNED NOT NULL,
  `attendance_bonus_types_id` int UNSIGNED NOT NULL,
  `type` tinyint NOT NULL DEFAULT '0' COMMENT 'jogcím mire vonatkozik (1: többlet óra, 2: hiány óra) ',
  PRIMARY KEY (`id`),
  KEY `key0030` (`attendance_workplans_id`),
  KEY `key0031` (`attendance_bonus_types_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `attendance_workplans_core_legal_entities_rel`
--

DROP TABLE IF EXISTS `attendance_workplans_core_legal_entities_rel`;
CREATE TABLE IF NOT EXISTS `attendance_workplans_core_legal_entities_rel` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `attendance_workplans_id` int UNSIGNED NOT NULL,
  `core_legal_entities_id` int UNSIGNED NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `key0025` (`attendance_workplans_id`),
  KEY `key0026` (`core_legal_entities_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `attendance_workplans_full_day_bonuses`
--

DROP TABLE IF EXISTS `attendance_workplans_full_day_bonuses`;
CREATE TABLE IF NOT EXISTS `attendance_workplans_full_day_bonuses` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `attendance_workplans_id` int UNSIGNED NOT NULL COMMENT 'külső kulcs a munkarendek táblájához',
  `attendance_bonus_types_id` int UNSIGNED NOT NULL COMMENT 'külső kulcs a pótlékok táblájához',
  PRIMARY KEY (`id`),
  KEY `key2009` (`attendance_workplans_id`),
  KEY `key2010` (`attendance_bonus_types_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `attendance_workplan_categories`
--

DROP TABLE IF EXISTS `attendance_workplan_categories`;
CREATE TABLE IF NOT EXISTS `attendance_workplan_categories` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `attendance_workplan_shifts`
--

DROP TABLE IF EXISTS `attendance_workplan_shifts`;
CREATE TABLE IF NOT EXISTS `attendance_workplan_shifts` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `attendance_workplans_id` int UNSIGNED NOT NULL,
  `attendance_day_types_id` int UNSIGNED NOT NULL,
  `attendance_shift_types_id` int UNSIGNED DEFAULT NULL,
  `_date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key0027` (`attendance_workplans_id`),
  KEY `key0028` (`attendance_day_types_id`),
  KEY `key0029` (`attendance_shift_types_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `attendance_worktimelimits`
--

DROP TABLE IF EXISTS `attendance_worktimelimits`;
CREATE TABLE IF NOT EXISTS `attendance_worktimelimits` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `core_firms_id` int UNSIGNED NOT NULL,
  `name` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `start_date` date NOT NULL COMMENT 'érvényesség kezdete (éééé-hh-nn)',
  `end_date` date NOT NULL COMMENT 'érvényesség vége (éééé-hh-nn)',
  `daily_workhours` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL COMMENT 'napi óraszám',
  PRIMARY KEY (`id`),
  UNIQUE KEY `attendance_worktimelimits_name_core_firms_id_unique` (`name`,`core_firms_id`),
  KEY `key2001` (`core_firms_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `attendance_worktimelimit_abscences`
--

DROP TABLE IF EXISTS `attendance_worktimelimit_abscences`;
CREATE TABLE IF NOT EXISTS `attendance_worktimelimit_abscences` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `attendance_worktimelimits_id` int UNSIGNED NOT NULL COMMENT 'munkaidőkeret azonosítója az attendance_worktimelimits táblából',
  `abscence_types_id` int UNSIGNED NOT NULL COMMENT 'A távollét azonosítója az attendance_abscence_types_core_firms_rel táblában',
  PRIMARY KEY (`id`),
  KEY `key2041` (`attendance_worktimelimits_id`),
  KEY `key2042` (`abscence_types_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `attendance_worktimelimit_abscence_bonus`
--

DROP TABLE IF EXISTS `attendance_worktimelimit_abscence_bonus`;
CREATE TABLE IF NOT EXISTS `attendance_worktimelimit_abscence_bonus` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `attendance_worktimelimits_id` int UNSIGNED NOT NULL COMMENT 'munkaidőkeret azonosítója az attendance_worktimelimits táblából',
  `attendance_bonus_types_id` int UNSIGNED NOT NULL COMMENT 'A pótlék azonosítója az attendance_bonus_types táblában',
  PRIMARY KEY (`id`),
  KEY `key20236` (`attendance_worktimelimits_id`),
  KEY `key20237` (`attendance_bonus_types_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `attendance_worktimelimit_assignments`
--

DROP TABLE IF EXISTS `attendance_worktimelimit_assignments`;
CREATE TABLE IF NOT EXISTS `attendance_worktimelimit_assignments` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `attendance_worktimelimits_id` int UNSIGNED NOT NULL COMMENT 'munkaidőkeret azonosítója az attendance_worktimelimits táblából',
  `core_legal_entities_id` int UNSIGNED NOT NULL COMMENT 'jogviszony azonosítója a core_legal_entities táblából',
  `start_date` date NOT NULL COMMENT 'hozzárendelés kezdete (éééé-hh-nn)',
  `end_date` date NOT NULL COMMENT 'hozzárendelés vége (éééé-hh-nn)',
  PRIMARY KEY (`id`),
  KEY `key20038` (`attendance_worktimelimits_id`),
  KEY `key20039` (`core_legal_entities_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `attendance_worktimelimit_deficit`
--

DROP TABLE IF EXISTS `attendance_worktimelimit_deficit`;
CREATE TABLE IF NOT EXISTS `attendance_worktimelimit_deficit` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `attendance_worktimelimits_id` int UNSIGNED NOT NULL COMMENT 'munkaidőkeret azonosítója az attendance_worktimelimits táblából',
  `attendance_bonus_types_id` int UNSIGNED NOT NULL COMMENT 'A pótlék azonosítója az attendance_bonus_types táblában',
  PRIMARY KEY (`id`),
  KEY `key20034` (`attendance_worktimelimits_id`),
  KEY `key20035` (`attendance_bonus_types_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `attendance_worktimelimit_excess`
--

DROP TABLE IF EXISTS `attendance_worktimelimit_excess`;
CREATE TABLE IF NOT EXISTS `attendance_worktimelimit_excess` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `attendance_worktimelimits_id` int UNSIGNED NOT NULL COMMENT 'munkaidőkeret azonosítója az attendance_worktimelimits táblából',
  `attendance_bonus_types_id` int UNSIGNED NOT NULL COMMENT 'A pótlék azonosítója az attendance_bonus_types táblában',
  PRIMARY KEY (`id`),
  KEY `key20036` (`attendance_worktimelimits_id`),
  KEY `key20037` (`attendance_bonus_types_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `attendance_worktimelimit_rest_time_difference_bonus`
--

DROP TABLE IF EXISTS `attendance_worktimelimit_rest_time_difference_bonus`;
CREATE TABLE IF NOT EXISTS `attendance_worktimelimit_rest_time_difference_bonus` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `attendance_worktimelimits_id` int UNSIGNED NOT NULL COMMENT 'munkaidőkeret azonosítója az attendance_worktimelimits táblából',
  `attendance_bonus_types_id` int UNSIGNED NOT NULL COMMENT 'A pótlék azonosítója az attendance_bonus_types táblában',
  PRIMARY KEY (`id`),
  KEY `key21034` (`attendance_worktimelimits_id`),
  KEY `key21035` (`attendance_bonus_types_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_actions`
--

DROP TABLE IF EXISTS `core_actions`;
CREATE TABLE IF NOT EXISTS `core_actions` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `core_menu_items_id` int UNSIGNED DEFAULT NULL,
  `core_modules_id` int UNSIGNED DEFAULT NULL,
  `name` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `is_api` tinyint(1) NOT NULL DEFAULT '0',
  `description` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `is_visible` tinyint NOT NULL DEFAULT '1',
  `default_permission_level` int UNSIGNED DEFAULT NULL,
  `action_without_menu` tinyint NOT NULL DEFAULT '0' COMMENT 'a programban külön kezelendő, mert nem menüből indul, nem tartozik hozzá egyedi ajax hívás!',
  PRIMARY KEY (`id`),
  KEY `key011` (`core_menu_items_id`),
  KEY `key012` (`core_modules_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_activities`
--

DROP TABLE IF EXISTS `core_activities`;
CREATE TABLE IF NOT EXISTS `core_activities` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `core_firms_id` int UNSIGNED NOT NULL,
  `type` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key1043` (`core_firms_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_allow_cron_job`
--

DROP TABLE IF EXISTS `core_allow_cron_job`;
CREATE TABLE IF NOT EXISTS `core_allow_cron_job` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL COMMENT 'engedélyezett cron neve',
  `core_firms_id` int UNSIGNED NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `data` json DEFAULT NULL COMMENT 'A futáshoz szükséges adatok',
  PRIMARY KEY (`id`),
  KEY `key2037` (`core_firms_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_basic_calendar`
--

DROP TABLE IF EXISTS `core_basic_calendar`;
CREATE TABLE IF NOT EXISTS `core_basic_calendar` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `year` int NOT NULL,
  `date` date NOT NULL,
  `day_type` int NOT NULL,
  `created_at` datetime DEFAULT '1000-01-01 00:00:00',
  `updated_at` datetime DEFAULT '1000-01-01 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_cities`
--

DROP TABLE IF EXISTS `core_cities`;
CREATE TABLE IF NOT EXISTS `core_cities` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `core_counties_id` int UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `key009` (`core_counties_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_components`
--

DROP TABLE IF EXISTS `core_components`;
CREATE TABLE IF NOT EXISTS `core_components` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `directory` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `_order` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_cost_centers`
--

DROP TABLE IF EXISTS `core_cost_centers`;
CREATE TABLE IF NOT EXISTS `core_cost_centers` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `core_firms_id` int UNSIGNED NOT NULL,
  `payroll_software_id` varchar(160) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `key0007` (`core_firms_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_counties`
--

DROP TABLE IF EXISTS `core_counties`;
CREATE TABLE IF NOT EXISTS `core_counties` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_custom_reports`
--

DROP TABLE IF EXISTS `core_custom_reports`;
CREATE TABLE IF NOT EXISTS `core_custom_reports` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `core_legal_entities_id` int UNSIGNED NOT NULL,
  `core_firms_id` int UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL COMMENT 'Riport megnevezése',
  `description` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL COMMENT 'Riport leírása',
  `data_fields` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL COMMENT 'Kiválasztott mezők adatai JSON tömbben',
  `data_filters` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL COMMENT 'Kiválasztott szűrők adatai JSON tömbben',
  `created_at` datetime DEFAULT '1000-01-01 00:00:00',
  `updated_at` datetime DEFAULT '1000-01-01 00:00:00',
  PRIMARY KEY (`id`),
  KEY `key1015` (`core_firms_id`),
  KEY `key1016` (`core_legal_entities_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_departments`
--

DROP TABLE IF EXISTS `core_departments`;
CREATE TABLE IF NOT EXISTS `core_departments` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `core_firms_id` int UNSIGNED NOT NULL,
  `payroll_software_id` varchar(160) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `key0006` (`core_firms_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_email_identification`
--

DROP TABLE IF EXISTS `core_email_identification`;
CREATE TABLE IF NOT EXISTS `core_email_identification` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `core_person_id` int UNSIGNED NOT NULL,
  `access_token` varchar(120) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL COMMENT 'a levélben küldött karaktersorozat hashe',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `key6002` (`core_person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_email_notifications`
--

DROP TABLE IF EXISTS `core_email_notifications`;
CREATE TABLE IF NOT EXISTS `core_email_notifications` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `core_email_notification_types_id` int UNSIGNED NOT NULL,
  `blade_file_name` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `core_localization_id` int UNSIGNED DEFAULT NULL,
  `core_legal_entities_id` int UNSIGNED DEFAULT NULL,
  `source_core_legal_entities_id` int UNSIGNED DEFAULT NULL COMMENT 'Az értesítést generáló jogviszony azonosítója',
  `email_address` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `subject` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `variables` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `expiration` datetime NOT NULL,
  `sent_time` datetime DEFAULT NULL,
  `sent_status` tinyint NOT NULL,
  `error_code` int DEFAULT NULL,
  `error_message` varchar(1000) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `blocked_name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `key4004` (`core_email_notification_types_id`),
  KEY `key4005` (`core_localization_id`),
  KEY `key5007` (`source_core_legal_entities_id`),
  KEY `key1017` (`core_legal_entities_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_email_notification_subjects`
--

DROP TABLE IF EXISTS `core_email_notification_subjects`;
CREATE TABLE IF NOT EXISTS `core_email_notification_subjects` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `core_email_notification_types_type` int UNSIGNED NOT NULL,
  `subtype` tinyint NOT NULL DEFAULT '0' COMMENT 'értesítés altípusát jelöli 0/1',
  `core_firms_id` int UNSIGNED NOT NULL,
  `localization_id` int UNSIGNED DEFAULT NULL,
  `subject` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key5001` (`core_firms_id`),
  KEY `key5003` (`localization_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_email_notification_types`
--

DROP TABLE IF EXISTS `core_email_notification_types`;
CREATE TABLE IF NOT EXISTS `core_email_notification_types` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` int UNSIGNED NOT NULL,
  `subtype` tinyint NOT NULL DEFAULT '0' COMMENT 'meghatározza, az adott típus altípusát 0: dolgozói 1: vezetői altípust jelöl',
  `extra_notification` tinyint NOT NULL DEFAULT '0' COMMENT 'Meghatározza hogy az adott típus megjelenjen-e az extra értesítést létrehozó/szerkesztő felületen. 0:nem 1:igen',
  `core_firms_id` int UNSIGNED NOT NULL,
  `is_combined` tinyint NOT NULL,
  `display` tinyint NOT NULL DEFAULT '1' COMMENT 'megjeleníthető',
  `expiration_unit` tinyint DEFAULT NULL,
  `expiration_values` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `timing_time` time DEFAULT NULL,
  `template` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `core_email_notification_types_type_index` (`type`),
  KEY `core_email_notification_types_core_firms_id_index` (`core_firms_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_establishments`
--

DROP TABLE IF EXISTS `core_establishments`;
CREATE TABLE IF NOT EXISTS `core_establishments` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `core_firms_id` int UNSIGNED NOT NULL,
  `payroll_software_id` varchar(160) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `key0005` (`core_firms_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_extended_hierarchy`
--

DROP TABLE IF EXISTS `core_extended_hierarchy`;
CREATE TABLE IF NOT EXISTS `core_extended_hierarchy` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent_core_legal_entities_id` int UNSIGNED NOT NULL,
  `child_core_legal_entities_id` int UNSIGNED NOT NULL,
  `core_firms_id` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key3003` (`parent_core_legal_entities_id`),
  KEY `key3004` (`child_core_legal_entities_id`),
  KEY `key3005` (`core_firms_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_extra_email_addresses`
--

DROP TABLE IF EXISTS `core_extra_email_addresses`;
CREATE TABLE IF NOT EXISTS `core_extra_email_addresses` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `core_legal_entities_id` int UNSIGNED NOT NULL,
  `email_address` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `core_email_notifications_types_id` int UNSIGNED NOT NULL,
  `core_localization_id` int UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT '1000-01-01 00:00:00',
  `updated_at` datetime DEFAULT '1000-01-01 00:00:00',
  PRIMARY KEY (`id`),
  KEY `key5004` (`core_legal_entities_id`),
  KEY `key5005` (`core_email_notifications_types_id`),
  KEY `key5006` (`core_localization_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_extra_permission_core_modules_core_firms_rel`
--

DROP TABLE IF EXISTS `core_extra_permission_core_modules_core_firms_rel`;
CREATE TABLE IF NOT EXISTS `core_extra_permission_core_modules_core_firms_rel` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `core_firms_id` int UNSIGNED NOT NULL COMMENT 'cég azonosító',
  `core_modules_id` int UNSIGNED NOT NULL COMMENT 'modul azonosítója',
  `usable` tinyint DEFAULT NULL COMMENT 'A modul használhatósága',
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL COMMENT 'A modul elnevezése',
  PRIMARY KEY (`id`),
  KEY `key1343` (`core_firms_id`),
  KEY `key1344` (`core_modules_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_firms`
--

DROP TABLE IF EXISTS `core_firms`;
CREATE TABLE IF NOT EXISTS `core_firms` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `registration_number` varchar(12) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `tax_id` varchar(45) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `name` varchar(45) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `directory` varchar(45) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '' COMMENT 'A cég könyvtára, automatikusan képződik a cégnévből',
  `postal_code` smallint NOT NULL DEFAULT '0',
  `core_cities_id` int UNSIGNED NOT NULL DEFAULT '0',
  `public_place_name` varchar(45) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `core_public_places_id` int UNSIGNED NOT NULL DEFAULT '0',
  `public_place_number` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `staircase` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `floor_door` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `phone_number` varchar(45) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `status` tinyint NOT NULL DEFAULT '1',
  `notification_sender` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `default_id` tinyint NOT NULL DEFAULT '0' COMMENT '1 esetén ennek a cégnek a default nameje kerül megjelenítésre jelszó küldése esetén',
  `default_name` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `skip_tax_id_validation` tinyint NOT NULL DEFAULT '0' COMMENT 'import során figyelembe kell-e venni az adószámot (igen: 1, nem: 0, alapértelmezett: 0)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `core_firms_registration_number_unique` (`registration_number`),
  UNIQUE KEY `core_firms_tax_id_unique` (`tax_id`),
  KEY `key016` (`core_cities_id`),
  KEY `key017` (`core_public_places_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_firms_core_components_rel`
--

DROP TABLE IF EXISTS `core_firms_core_components_rel`;
CREATE TABLE IF NOT EXISTS `core_firms_core_components_rel` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `core_firms_id` int UNSIGNED NOT NULL DEFAULT '0',
  `core_components_id` int UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `key018` (`core_firms_id`),
  KEY `key019` (`core_components_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_general_ledger_analytics`
--

DROP TABLE IF EXISTS `core_general_ledger_analytics`;
CREATE TABLE IF NOT EXISTS `core_general_ledger_analytics` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `core_firms_id` int UNSIGNED NOT NULL,
  `payroll_software_id` varchar(36) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key1035` (`core_firms_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_general_ledger_analytics_value`
--

DROP TABLE IF EXISTS `core_general_ledger_analytics_value`;
CREATE TABLE IF NOT EXISTS `core_general_ledger_analytics_value` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `core_firms_id` int UNSIGNED NOT NULL,
  `payroll_software_id` varchar(36) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `core_general_ledger_analytics_id` int UNSIGNED NOT NULL,
  `code` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `key2012` (`core_firms_id`),
  KEY `key2013` (`core_general_ledger_analytics_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_hierarchy`
--

DROP TABLE IF EXISTS `core_hierarchy`;
CREATE TABLE IF NOT EXISTS `core_hierarchy` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent_core_legal_entities_id` int UNSIGNED NOT NULL,
  `child_core_legal_entities_id` int UNSIGNED NOT NULL,
  `core_firms_id` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key0002` (`parent_core_legal_entities_id`),
  KEY `key0003` (`child_core_legal_entities_id`),
  KEY `key0004` (`core_firms_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_hierarchy_import`
--

DROP TABLE IF EXISTS `core_hierarchy_import`;
CREATE TABLE IF NOT EXISTS `core_hierarchy_import` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `from_date` date NOT NULL,
  `core_firms_id` int NOT NULL,
  `parent_payroll_software_id` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `child_core_legal_entities_id` int UNSIGNED NOT NULL,
  `processed` tinyint NOT NULL DEFAULT '0',
  `fail_message` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL COMMENT 'entity-parent nem létezik; loop-hurok; recurrence-ismétlődés; no_change-nincs változás; other-egyéb',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_hierarchy_updater`
--

DROP TABLE IF EXISTS `core_hierarchy_updater`;
CREATE TABLE IF NOT EXISTS `core_hierarchy_updater` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `from_date` datetime NOT NULL,
  `parent_payroll_software_id` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `child_core_legal_entities_id` int UNSIGNED NOT NULL,
  `core_firms_id` int UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_hierarchy_updater_logs`
--

DROP TABLE IF EXISTS `core_hierarchy_updater_logs`;
CREATE TABLE IF NOT EXISTS `core_hierarchy_updater_logs` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `new_parent_id` int UNSIGNED NOT NULL,
  `old_parent_id` int UNSIGNED DEFAULT NULL,
  `child_id` int UNSIGNED NOT NULL,
  `firm_id` int UNSIGNED NOT NULL,
  `result` int UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_holidays`
--

DROP TABLE IF EXISTS `core_holidays`;
CREATE TABLE IF NOT EXISTS `core_holidays` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `core_firms_id` int UNSIGNED DEFAULT NULL,
  `date` date NOT NULL,
  `type` tinyint NOT NULL DEFAULT '1' COMMENT '1 - Ünnepnap, 2 - Áthelyezett pihenőnap, 3 - Áthelyezett munkanap',
  `created_at` datetime DEFAULT '1000-01-01 00:00:00',
  `updated_at` datetime DEFAULT '1000-01-01 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `core_holidays_core_firms_id_date_unique` (`core_firms_id`,`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_legal_entities`
--

DROP TABLE IF EXISTS `core_legal_entities`;
CREATE TABLE IF NOT EXISTS `core_legal_entities` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_legal_entities_attributes`
--

DROP TABLE IF EXISTS `core_legal_entities_attributes`;
CREATE TABLE IF NOT EXISTS `core_legal_entities_attributes` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `system` int NOT NULL DEFAULT '0',
  `core_firms_id` int NOT NULL DEFAULT '0' COMMENT 'Ha az értéke 0, akkor az összes céghez kapcsolódik',
  `visible_for_owner` tinyint NOT NULL DEFAULT '1',
  `visible_for_boss` tinyint NOT NULL DEFAULT '1',
  `visible_for_other` int UNSIGNED NOT NULL DEFAULT '0',
  `editable_for_owner` tinyint NOT NULL DEFAULT '0',
  `editable_for_boss` tinyint NOT NULL DEFAULT '0',
  `editable_for_other` int UNSIGNED NOT NULL DEFAULT '0',
  `visible_in_header` tinyint NOT NULL DEFAULT '0',
  `deadline_if_required` datetime DEFAULT '1000-01-01 00:00:00',
  `value_required` tinyint NOT NULL DEFAULT '1',
  `value_unique` tinyint NOT NULL DEFAULT '0',
  `value_regex` varchar(63) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `created_at` datetime DEFAULT '1000-01-01 00:00:00',
  `updated_at` datetime DEFAULT '1000-01-01 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_legal_entities_attributes_labels`
--

DROP TABLE IF EXISTS `core_legal_entities_attributes_labels`;
CREATE TABLE IF NOT EXISTS `core_legal_entities_attributes_labels` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `core_legal_entities_attributes_id` int UNSIGNED NOT NULL DEFAULT '0',
  `core_localization_id` int UNSIGNED NOT NULL DEFAULT '0',
  `value` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `key034` (`core_legal_entities_attributes_id`),
  KEY `key035` (`core_localization_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_legal_entities_attributes_values`
--

DROP TABLE IF EXISTS `core_legal_entities_attributes_values`;
CREATE TABLE IF NOT EXISTS `core_legal_entities_attributes_values` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `core_legal_entities_id` int UNSIGNED NOT NULL DEFAULT '0',
  `core_legal_entities_attributes_id` int UNSIGNED NOT NULL DEFAULT '0',
  `value` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `created_at` datetime DEFAULT '1000-01-01 00:00:00',
  `updated_at` datetime DEFAULT '1000-01-01 00:00:00',
  PRIMARY KEY (`id`),
  KEY `key032` (`core_legal_entities_id`),
  KEY `key033` (`core_legal_entities_attributes_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_legal_entities_core_activities_assignments`
--

DROP TABLE IF EXISTS `core_legal_entities_core_activities_assignments`;
CREATE TABLE IF NOT EXISTS `core_legal_entities_core_activities_assignments` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `core_legal_entities_id` int UNSIGNED NOT NULL,
  `core_activity_id` int UNSIGNED NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `key1041` (`core_legal_entities_id`),
  KEY `key1042` (`core_activity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_legal_entities_cost_center_assignments`
--

DROP TABLE IF EXISTS `core_legal_entities_cost_center_assignments`;
CREATE TABLE IF NOT EXISTS `core_legal_entities_cost_center_assignments` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `core_legal_entities_id` int UNSIGNED NOT NULL,
  `core_cost_centers_id` int UNSIGNED NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `key4014` (`core_legal_entities_id`),
  KEY `key4015` (`core_cost_centers_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_legal_entities_department_assignments`
--

DROP TABLE IF EXISTS `core_legal_entities_department_assignments`;
CREATE TABLE IF NOT EXISTS `core_legal_entities_department_assignments` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `core_legal_entities_id` int UNSIGNED NOT NULL,
  `core_departments_id` int UNSIGNED NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `key4010` (`core_legal_entities_id`),
  KEY `key4011` (`core_departments_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_legal_entities_establishment_assignments`
--

DROP TABLE IF EXISTS `core_legal_entities_establishment_assignments`;
CREATE TABLE IF NOT EXISTS `core_legal_entities_establishment_assignments` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `core_legal_entities_id` int UNSIGNED NOT NULL,
  `core_establishments_id` int UNSIGNED NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `key4012` (`core_legal_entities_id`),
  KEY `key4013` (`core_establishments_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_legal_entities_general_ledger_analytic_value_assignments`
--

DROP TABLE IF EXISTS `core_legal_entities_general_ledger_analytic_value_assignments`;
CREATE TABLE IF NOT EXISTS `core_legal_entities_general_ledger_analytic_value_assignments` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `core_legal_entities_id` int UNSIGNED NOT NULL,
  `core_general_ledger_analytics_id` int UNSIGNED NOT NULL,
  `core_general_ledger_analytics_value_id` int UNSIGNED NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `key1025` (`core_legal_entities_id`),
  KEY `key2026` (`core_general_ledger_analytics_id`),
  KEY `key2027` (`core_general_ledger_analytics_value_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_legal_entities_profit_center_assignments`
--

DROP TABLE IF EXISTS `core_legal_entities_profit_center_assignments`;
CREATE TABLE IF NOT EXISTS `core_legal_entities_profit_center_assignments` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `core_legal_entities_id` int UNSIGNED NOT NULL,
  `core_profit_centers_id` int UNSIGNED NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `key20042` (`core_legal_entities_id`),
  KEY `key20043` (`core_profit_centers_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_legal_entities_system_attributes`
--

DROP TABLE IF EXISTS `core_legal_entities_system_attributes`;
CREATE TABLE IF NOT EXISTS `core_legal_entities_system_attributes` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `developer_description` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `editable` tinyint NOT NULL DEFAULT '0' COMMENT '1: akkor is módosítható, ha van számfejtő szoftver a rendszerben (alapértelmezett), 0: csak akkor módosítható, ha nincs számfejtő szoftver a rendszerben',
  `show_in_legal_entity_selector` tinyint NOT NULL DEFAULT '0',
  `model_name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_localization`
--

DROP TABLE IF EXISTS `core_localization`;
CREATE TABLE IF NOT EXISTS `core_localization` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `language` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `language_original` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `language_file` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `status` tinyint NOT NULL DEFAULT '1',
  `_order` tinyint NOT NULL DEFAULT '0',
  `precedency_order` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_logs`
--

DROP TABLE IF EXISTS `core_logs`;
CREATE TABLE IF NOT EXISTS `core_logs` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `core_firms_id` int UNSIGNED DEFAULT NULL,
  `core_legal_entities_id` int UNSIGNED DEFAULT NULL,
  `substitution_core_persons_id` int UNSIGNED DEFAULT NULL,
  `IP_address` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `core_log_messages_id` int UNSIGNED NOT NULL DEFAULT '0',
  `core_components_id` int UNSIGNED DEFAULT NULL,
  `core_modules_id` int UNSIGNED DEFAULT NULL,
  `core_actions_id` int UNSIGNED DEFAULT NULL,
  `success_type` tinyint NOT NULL DEFAULT '0',
  `logged_data` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT '1000-01-01 00:00:00',
  PRIMARY KEY (`id`),
  KEY `key001` (`core_legal_entities_id`),
  KEY `key003` (`core_log_messages_id`),
  KEY `key004` (`core_components_id`),
  KEY `key005` (`core_modules_id`),
  KEY `key006` (`core_actions_id`),
  KEY `key002` (`substitution_core_persons_id`),
  KEY `key1014` (`core_firms_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_log_messages`
--

DROP TABLE IF EXISTS `core_log_messages`;
CREATE TABLE IF NOT EXISTS `core_log_messages` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `category` int UNSIGNED NOT NULL DEFAULT '0' COMMENT '1 - rendszer, 2 - felhasználó, 3 - import, 4 - értesítés',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_menu_items`
--

DROP TABLE IF EXISTS `core_menu_items`;
CREATE TABLE IF NOT EXISTS `core_menu_items` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `core_modules_id` int UNSIGNED DEFAULT NULL,
  `route` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `parent` int UNSIGNED NOT NULL DEFAULT '0',
  `_order` int UNSIGNED NOT NULL DEFAULT '0',
  `core_parameters_id` int UNSIGNED DEFAULT NULL,
  `core_parameters_value` varchar(31) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '' COMMENT 'akkor jelenik meg a menüpont (a jogosultságon túl), ha előző oszlopban megadott rendszerparaméternek ez az értéke',
  `visible_for_outsourced_admin` tinyint NOT NULL DEFAULT '1',
  `visible_for_substitution` tinyint NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `key013` (`core_modules_id`),
  KEY `key40001` (`core_parameters_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_modules`
--

DROP TABLE IF EXISTS `core_modules`;
CREATE TABLE IF NOT EXISTS `core_modules` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `core_components_id` int UNSIGNED DEFAULT NULL,
  `directory` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `key010` (`core_components_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_other_incomes`
--

DROP TABLE IF EXISTS `core_other_incomes`;
CREATE TABLE IF NOT EXISTS `core_other_incomes` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `core_firms_id` int UNSIGNED NOT NULL,
  `payroll_software_id` varchar(8) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `name` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `status` tinyint NOT NULL DEFAULT '1' COMMENT 'egyéb jövedelem állapota (0: inaktív, 1: aktív)',
  PRIMARY KEY (`id`),
  KEY `key2007` (`core_firms_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_parameters`
--

DROP TABLE IF EXISTS `core_parameters`;
CREATE TABLE IF NOT EXISTS `core_parameters` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `core_components_id` int UNSIGNED NOT NULL DEFAULT '0',
  `core_modules_id` int UNSIGNED DEFAULT NULL,
  `type` int NOT NULL DEFAULT '0' COMMENT '1: szabadszavas, 2: egyszeres lista, 3: többszörös lista, 4: slider',
  `minimum_value` int UNSIGNED DEFAULT NULL,
  `maximum_value` int UNSIGNED DEFAULT NULL,
  `default_value` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1: aktív, 0: inaktív, 2: központ által vezérelt',
  `_order` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `key022` (`core_components_id`),
  KEY `key023` (`core_modules_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_parameters_core_firms_status`
--

DROP TABLE IF EXISTS `core_parameters_core_firms_status`;
CREATE TABLE IF NOT EXISTS `core_parameters_core_firms_status` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `core_parameters_id` int UNSIGNED NOT NULL,
  `core_firms_id` int UNSIGNED NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `key1345` (`core_parameters_id`),
  KEY `key1346` (`core_firms_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_parameters_core_firms_values`
--

DROP TABLE IF EXISTS `core_parameters_core_firms_values`;
CREATE TABLE IF NOT EXISTS `core_parameters_core_firms_values` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `core_parameters_id` int UNSIGNED NOT NULL,
  `core_firms_id` int UNSIGNED NOT NULL,
  `value` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `key024` (`core_parameters_id`),
  KEY `key025` (`core_firms_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_parameters_core_parameter_values_rel`
--

DROP TABLE IF EXISTS `core_parameters_core_parameter_values_rel`;
CREATE TABLE IF NOT EXISTS `core_parameters_core_parameter_values_rel` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `core_parameters_id` int UNSIGNED NOT NULL DEFAULT '0',
  `core_parameter_values_id` int UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `key026` (`core_parameters_id`),
  KEY `key027` (`core_parameter_values_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_parameter_values`
--

DROP TABLE IF EXISTS `core_parameter_values`;
CREATE TABLE IF NOT EXISTS `core_parameter_values` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `value` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '' COMMENT 'a nyelvi állományban a parameter_value_X értéke tartozik hozzá szöveges értékként',
  `status` tinyint NOT NULL DEFAULT '1',
  `_order` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_passwords_logs`
--

DROP TABLE IF EXISTS `core_passwords_logs`;
CREATE TABLE IF NOT EXISTS `core_passwords_logs` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `core_persons_id` int UNSIGNED NOT NULL DEFAULT '0',
  `password` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `login_count` int NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
  PRIMARY KEY (`id`),
  KEY `key40008` (`core_persons_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_permission_groups`
--

DROP TABLE IF EXISTS `core_permission_groups`;
CREATE TABLE IF NOT EXISTS `core_permission_groups` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `group_type` tinyint NOT NULL DEFAULT '0' COMMENT '1: külsős admin, 2: belsős admin, 3: dolgozó, ha 0, akkor az nem default csoport és így a felületen módosítható',
  `core_firms_id` int UNSIGNED DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT '1000-01-01 00:00:00',
  `updated_at` datetime DEFAULT '1000-01-01 00:00:00',
  PRIMARY KEY (`id`),
  KEY `key0001` (`core_firms_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_permission_groups_action_permissions_rel`
--

DROP TABLE IF EXISTS `core_permission_groups_action_permissions_rel`;
CREATE TABLE IF NOT EXISTS `core_permission_groups_action_permissions_rel` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `core_permission_groups_id` int UNSIGNED NOT NULL DEFAULT '0',
  `core_actions_id` int UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `key007` (`core_permission_groups_id`),
  KEY `key008` (`core_actions_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_permission_groups_core_legal_entities_rel`
--

DROP TABLE IF EXISTS `core_permission_groups_core_legal_entities_rel`;
CREATE TABLE IF NOT EXISTS `core_permission_groups_core_legal_entities_rel` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `core_permission_groups_id` int UNSIGNED NOT NULL DEFAULT '0',
  `core_legal_entities_id` int UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `key014` (`core_permission_groups_id`),
  KEY `key015` (`core_legal_entities_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_persons`
--

DROP TABLE IF EXISTS `core_persons`;
CREATE TABLE IF NOT EXISTS `core_persons` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_persons_attributes`
--

DROP TABLE IF EXISTS `core_persons_attributes`;
CREATE TABLE IF NOT EXISTS `core_persons_attributes` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `system` int NOT NULL DEFAULT '0',
  `core_firms_id` int NOT NULL DEFAULT '0' COMMENT 'Ha az értéke 0, akkor az összes céghez kapcsolódik',
  `visible_for_owner` tinyint NOT NULL DEFAULT '1',
  `visible_for_boss` tinyint NOT NULL DEFAULT '1',
  `visible_for_other` int UNSIGNED NOT NULL DEFAULT '0',
  `editable_for_owner` tinyint NOT NULL DEFAULT '0',
  `editable_for_boss` tinyint NOT NULL DEFAULT '0',
  `editable_for_other` int UNSIGNED NOT NULL DEFAULT '0',
  `visible_in_header` tinyint(1) NOT NULL DEFAULT '0',
  `deadline_if_required` datetime DEFAULT '1000-01-01 00:00:00',
  `value_required` tinyint NOT NULL DEFAULT '1',
  `value_unique` tinyint NOT NULL DEFAULT '0',
  `value_email` tinyint NOT NULL DEFAULT '0',
  `value_regex` varchar(63) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `created_at` datetime DEFAULT '1000-01-01 00:00:00',
  `updated_at` datetime DEFAULT '1000-01-01 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_persons_attributes_labels`
--

DROP TABLE IF EXISTS `core_persons_attributes_labels`;
CREATE TABLE IF NOT EXISTS `core_persons_attributes_labels` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `core_persons_attributes_id` int UNSIGNED NOT NULL DEFAULT '0',
  `core_localization_id` int UNSIGNED NOT NULL DEFAULT '0',
  `value` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `key030` (`core_persons_attributes_id`),
  KEY `key031` (`core_localization_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_persons_attributes_values`
--

DROP TABLE IF EXISTS `core_persons_attributes_values`;
CREATE TABLE IF NOT EXISTS `core_persons_attributes_values` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `core_persons_id` int UNSIGNED NOT NULL DEFAULT '0',
  `core_persons_attributes_id` int UNSIGNED NOT NULL DEFAULT '0',
  `value` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `created_at` datetime DEFAULT '1000-01-01 00:00:00',
  `updated_at` datetime DEFAULT '1000-01-01 00:00:00',
  PRIMARY KEY (`id`),
  KEY `key028` (`core_persons_id`),
  KEY `key029` (`core_persons_attributes_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_persons_system_attributes`
--

DROP TABLE IF EXISTS `core_persons_system_attributes`;
CREATE TABLE IF NOT EXISTS `core_persons_system_attributes` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `developer_description` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `editable` tinyint NOT NULL DEFAULT '0' COMMENT '1: akkor is módosítható, ha van számfejtő szoftver a rendszerben (alapértelmezett), 0: csak akkor módosítható, ha nincs számfejtő szoftver a rendszerben',
  `show_in_legal_entity_selector` tinyint NOT NULL DEFAULT '0',
  `model_name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_person_detection_environments`
--

DROP TABLE IF EXISTS `core_person_detection_environments`;
CREATE TABLE IF NOT EXISTS `core_person_detection_environments` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `core_person_id` int UNSIGNED NOT NULL,
  `ip_address` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `device_type` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `browser_family` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `browser_version` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `platform_family` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `platform_version` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `key6001` (`core_person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_profit_centers`
--

DROP TABLE IF EXISTS `core_profit_centers`;
CREATE TABLE IF NOT EXISTS `core_profit_centers` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `core_firms_id` int UNSIGNED NOT NULL COMMENT 'külső kulcs a core_firms tábla id mezőjére',
  `payroll_software_id` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
  `name` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL COMMENT 'Profitcentrum megnevezése',
  `establishment_id` int UNSIGNED DEFAULT NULL COMMENT 'külső kulcs a telephelyekre (core_establishments tábla id mezőjére)',
  `status` tinyint NOT NULL COMMENT 'Állapot: 0: inaktív, 1: aktív',
  PRIMARY KEY (`id`),
  KEY `key7004` (`core_firms_id`),
  KEY `key7005` (`establishment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_public_places`
--

DROP TABLE IF EXISTS `core_public_places`;
CREATE TABLE IF NOT EXISTS `core_public_places` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_reports`
--

DROP TABLE IF EXISTS `core_reports`;
CREATE TABLE IF NOT EXISTS `core_reports` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `core_legal_entities_id` int UNSIGNED NOT NULL,
  `core_firms_id` int UNSIGNED NOT NULL,
  `core_report_classes_id` int UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `data` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `core_legal_entity_attribute_ids` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `core_person_attribute_ids` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `created_at` datetime DEFAULT '1000-01-01 00:00:00',
  `updated_at` datetime DEFAULT '1000-01-01 00:00:00',
  PRIMARY KEY (`id`),
  KEY `key4025` (`core_legal_entities_id`),
  KEY `key4026` (`core_firms_id`),
  KEY `key4027` (`core_report_classes_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_report_categories`
--

DROP TABLE IF EXISTS `core_report_categories`;
CREATE TABLE IF NOT EXISTS `core_report_categories` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `_order` tinyint NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_report_classes`
--

DROP TABLE IF EXISTS `core_report_classes`;
CREATE TABLE IF NOT EXISTS `core_report_classes` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `core_report_categories_id` int UNSIGNED NOT NULL,
  `class_name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `description` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '' COMMENT 'A riport leírása',
  PRIMARY KEY (`id`),
  KEY `key4024` (`core_report_categories_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_saved_legal_entity_filters`
--

DROP TABLE IF EXISTS `core_saved_legal_entity_filters`;
CREATE TABLE IF NOT EXISTS `core_saved_legal_entity_filters` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `core_legal_entities_id` int UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `filter_data` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key1018` (`core_legal_entities_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_session_container`
--

DROP TABLE IF EXISTS `core_session_container`;
CREATE TABLE IF NOT EXISTS `core_session_container` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `session_id` varchar(40) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `core_legal_entities_id` int UNSIGNED DEFAULT NULL,
  `values` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `permission_changed` tinyint NOT NULL DEFAULT '0',
  `expiration_time` datetime DEFAULT '1000-01-01 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `core_session_container_session_id_unique` (`session_id`),
  KEY `key1019` (`core_legal_entities_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_special_permissions`
--

DROP TABLE IF EXISTS `core_special_permissions`;
CREATE TABLE IF NOT EXISTS `core_special_permissions` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `core_firms_id` int UNSIGNED NOT NULL,
  `source_permission_group_id` int UNSIGNED NOT NULL COMMENT 'ez a forráscsoport, ennek tagjai gyakorolják a jogokat',
  `target_permission_group_id` int UNSIGNED NOT NULL COMMENT 'ez a célcsoport, ennek tagjain gyakorolja forráscsoport a jogokat',
  PRIMARY KEY (`id`),
  UNIQUE KEY `special_permissions_unique_1` (`name`,`core_firms_id`),
  UNIQUE KEY `special_permissions_unique_2` (`core_firms_id`,`source_permission_group_id`,`target_permission_group_id`),
  KEY `key1001` (`source_permission_group_id`),
  KEY `key1002` (`target_permission_group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_special_permissions_core_actions_rel`
--

DROP TABLE IF EXISTS `core_special_permissions_core_actions_rel`;
CREATE TABLE IF NOT EXISTS `core_special_permissions_core_actions_rel` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `core_special_permissions_id` int UNSIGNED NOT NULL,
  `core_actions_id` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key1003` (`core_special_permissions_id`),
  KEY `key1004` (`core_actions_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_special_person_statuses`
--

DROP TABLE IF EXISTS `core_special_person_statuses`;
CREATE TABLE IF NOT EXISTS `core_special_person_statuses` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `core_legal_entities_id` int UNSIGNED NOT NULL,
  `core_special_person_status_types_id` int UNSIGNED NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `approval` tinyint NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT '1000-01-01 00:00:00',
  `updated_at` datetime DEFAULT '1000-01-01 00:00:00',
  PRIMARY KEY (`id`),
  KEY `key40009` (`core_legal_entities_id`),
  KEY `key40010` (`core_special_person_status_types_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_special_person_status_types`
--

DROP TABLE IF EXISTS `core_special_person_status_types`;
CREATE TABLE IF NOT EXISTS `core_special_person_status_types` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `_order` tinyint NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_sso_oauth_clients`
--

DROP TABLE IF EXISTS `core_sso_oauth_clients`;
CREATE TABLE IF NOT EXISTS `core_sso_oauth_clients` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `client_id` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `secret_key` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `authorize_url` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL COMMENT 'Authentikációs URL',
  `token_check_url` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL COMMENT 'Token ellenőrzés URL',
  `user_authentication_url` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL COMMENT 'Felhasználó lekérésének útvonala',
  `scope` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '' COMMENT 'A felhasználhatósági jogosultság: pl: view-user',
  `user_interface` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL COMMENT 'ejelenlét oldali interface neve',
  `status` tinyint(1) NOT NULL COMMENT 'Állapot',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_subdomain_attributes`
--

DROP TABLE IF EXISTS `core_subdomain_attributes`;
CREATE TABLE IF NOT EXISTS `core_subdomain_attributes` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `attribute` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `value` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `created_at` datetime DEFAULT '1000-01-01 00:00:00',
  `updated_at` datetime DEFAULT '1000-01-01 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_substitutions`
--

DROP TABLE IF EXISTS `core_substitutions`;
CREATE TABLE IF NOT EXISTS `core_substitutions` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `core_legal_entities_id` int UNSIGNED NOT NULL DEFAULT '0',
  `core_persons_id` int UNSIGNED NOT NULL DEFAULT '0',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `note` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT '1000-01-01 00:00:00',
  `updated_at` datetime DEFAULT '1000-01-01 00:00:00',
  PRIMARY KEY (`id`),
  KEY `key020` (`core_legal_entities_id`),
  KEY `key021` (`core_persons_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_surface_notifications`
--

DROP TABLE IF EXISTS `core_surface_notifications`;
CREATE TABLE IF NOT EXISTS `core_surface_notifications` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `notification_type` int UNSIGNED NOT NULL COMMENT 'Az értesítés típusának azonosítója',
  `subtype` int UNSIGNED NOT NULL,
  `target_legal_entities_id` int UNSIGNED NOT NULL COMMENT 'A cél jogviszony azonosítója',
  `source_legal_entities_id` int UNSIGNED NOT NULL COMMENT 'Az értesítést generáló jogviszony azonosítója',
  `subject` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `use_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Aktiv értesítés 0:nem, 1:igen',
  `core_email_notifications_id` int DEFAULT NULL COMMENT 'Az email üzenet azonosítója',
  `variables` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci COMMENT 'Az értesítésben megjelenítendő adatok',
  `combinable` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci COMMENT 'Jóváhagyás, elutasításhoz köthető adatok',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `key4210` (`target_legal_entities_id`),
  KEY `key4211` (`source_legal_entities_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_temporary_hierarchy`
--

DROP TABLE IF EXISTS `core_temporary_hierarchy`;
CREATE TABLE IF NOT EXISTS `core_temporary_hierarchy` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent_core_legal_entities_id` int UNSIGNED NOT NULL,
  `child_core_legal_entities_id` int UNSIGNED NOT NULL,
  `core_firms_id` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key0008` (`parent_core_legal_entities_id`),
  KEY `key0009` (`child_core_legal_entities_id`),
  KEY `key0010` (`core_firms_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_unique_reports`
--

DROP TABLE IF EXISTS `core_unique_reports`;
CREATE TABLE IF NOT EXISTS `core_unique_reports` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `core_legal_entities_id` int UNSIGNED NOT NULL,
  `core_firms_id` int UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `data_fields` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `data_filters` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `data_order` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `start_date` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `end_date` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `assigned_date` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT '1000-01-01 00:00:00',
  `updated_at` datetime DEFAULT '1000-01-01 00:00:00',
  PRIMARY KEY (`id`),
  KEY `key4030` (`core_legal_entities_id`),
  KEY `key4031` (`core_firms_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `core_unique_reports_structure`
--

DROP TABLE IF EXISTS `core_unique_reports_structure`;
CREATE TABLE IF NOT EXISTS `core_unique_reports_structure` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` int NOT NULL COMMENT '1 - Saját adatok, 2 - Személyek adatai',
  `class_id` int NOT NULL COMMENT '1 - Személyes adatlap, 2 - Jelenléti ív, 3 - Helyettesítések, 4 - Jogi állományok',
  `field` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `class_name` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '' COMMENT 'A mezőhöz tartozó osztály neve',
  `filter` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL COMMENT 'nonfilter - a mező nem szűrhető, date - dátumosan szűrhető, class - osztályosan szűrhető, text - szövegesen szűrhető, int - egész szám szerint',
  `active` tinyint NOT NULL DEFAULT '1' COMMENT 'A mező megjeleníthetősége a mezőválasztóban',
  `data` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL COMMENT 'egyéb adatok tárolására szánt hely',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `seeds`
--

DROP TABLE IF EXISTS `seeds`;
CREATE TABLE IF NOT EXISTS `seeds` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `seed` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `batch` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `sync_api_token`
--

DROP TABLE IF EXISTS `sync_api_token`;
CREATE TABLE IF NOT EXISTS `sync_api_token` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `client` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `token` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `key` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `sync_token_actions_rel`
--

DROP TABLE IF EXISTS `sync_token_actions_rel`;
CREATE TABLE IF NOT EXISTS `sync_token_actions_rel` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `sync_api_token_id` int UNSIGNED NOT NULL,
  `core_actions_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `9059` (`sync_api_token_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- A nézet helyettes szerkezete `view_holiday_schedule_query`
-- (Lásd alább az aktuális nézetet)
--
DROP VIEW IF EXISTS `view_holiday_schedule_query`;
CREATE TABLE IF NOT EXISTS `view_holiday_schedule_query` (
`core_legal_entities_id` int
,`attendance_holiday_rule_id` int unsigned
,`holiday_rule_name` varchar(255)
,`attendance_holiday_rules_schedule_id` int unsigned
,`core_permission_group_id` text
,`planning_year` int
,`planning_from_date` date
,`planning_to_date` date
,`planning_deadline` date
,`percentage_of_holidays` tinyint
,`minimum_number_of_required_days` tinyint
,`worker_has` tinyint
,`required_at_once` tinyint
,`total_holiday_amounts` decimal(32,0)
,`so_far_total` decimal(58,0)
,`percentage_proportionally_holiday_amounts` decimal(60,0)
,`used` decimal(45,0)
,`used_dates` text
,`used_created_ats` text
,`used_abscences_ids` text
,`planned` decimal(45,0)
,`planned_dates` text
,`planned_created_ats` text
,`planned_abscences_ids` text
);

-- --------------------------------------------------------

--
-- A nézet helyettes szerkezete `view_legal_entities_abscences_query`
-- (Lásd alább az aktuális nézetet)
--
DROP VIEW IF EXISTS `view_legal_entities_abscences_query`;
CREATE TABLE IF NOT EXISTS `view_legal_entities_abscences_query` (
`attendance_legal_entities_calendar_id` int unsigned
,`attendance_legal_entities_abscences_id` int unsigned
,`attendance_abscence_types_id` int unsigned
,`core_firms_id` varchar(256)
,`_date` date
,`core_legal_entities_id` int unsigned
,`creator_legal_entity_id` int unsigned
,`legal_entity_confirmed` tinyint
,`boss_confirmed` tinyint
,`boss_confirmed_id` int unsigned
);

-- --------------------------------------------------------

--
-- A nézet helyettes szerkezete `view_legal_entities_offdays_query`
-- (Lásd alább az aktuális nézetet)
--
DROP VIEW IF EXISTS `view_legal_entities_offdays_query`;
CREATE TABLE IF NOT EXISTS `view_legal_entities_offdays_query` (
`attendance_legal_entities_calendar_id` int unsigned
,`attendance_legal_entities_abscences_id` int unsigned
,`attendance_offday_types_id` int unsigned
,`core_firms_id` varchar(256)
,`_date` date
,`core_legal_entities_id` int unsigned
,`creator_legal_entity_id` int unsigned
,`legal_entity_confirmed` tinyint
,`boss_confirmed` tinyint
,`boss_confirmed_id` int unsigned
,`created_at` date
);

-- --------------------------------------------------------

--
-- A nézet helyettes szerkezete `view_legal_entities_query`
-- (Lásd alább az aktuális nézetet)
--
DROP VIEW IF EXISTS `view_legal_entities_query`;
CREATE TABLE IF NOT EXISTS `view_legal_entities_query` (
`core_legal_entities_id` int unsigned
,`core_firms_id` text
,`legal_entity_name` text
,`core_establishments_id` text
,`core_departments_id` text
,`start_date` text
,`last_workday_date` text
,`end_date` text
,`last_export_date` text
,`outsourced_admin` text
,`payroll_software_id` text
,`virtual_legal_entity` text
,`core_persons_id` text
,`person_name` text
,`person_email` text
);

-- --------------------------------------------------------

--
-- A nézet helyettes szerkezete `view_persons_query`
-- (Lásd alább az aktuális nézetet)
--
DROP VIEW IF EXISTS `view_persons_query`;
CREATE TABLE IF NOT EXISTS `view_persons_query` (
`core_persons_id` int unsigned
,`email` text
,`name` text
,`ohr` text
,`other_identification` text
,`password` text
,`active` text
,`lang` text
,`password_repeat` text
,`tax_id` text
,`forbid_logging_in` text
);

-- --------------------------------------------------------

--
-- A nézet helyettes szerkezete `view_special_permissions_query`
-- (Lásd alább az aktuális nézetet)
--
DROP VIEW IF EXISTS `view_special_permissions_query`;
CREATE TABLE IF NOT EXISTS `view_special_permissions_query` (
`core_actions_id` int unsigned
,`source_legal_entity_id` int unsigned
,`target_legal_entity_id` int unsigned
,`target_person_id` int unsigned
,`target_person_name` varchar(256)
);

-- --------------------------------------------------------

--
-- Nézet szerkezete `view_holiday_schedule_query`
--
DROP TABLE IF EXISTS `view_holiday_schedule_query`;

DROP VIEW IF EXISTS `view_holiday_schedule_query`;
CREATE ALGORITHM=UNDEFINED DEFINER=`ej2_showtime_p`@`localhost` SQL SECURITY DEFINER VIEW `view_holiday_schedule_query`  AS SELECT `legal_entities_rel`.`core_legal_entities_id` AS `core_legal_entities_id`, `rules`.`id` AS `attendance_holiday_rule_id`, `rules`.`name` AS `holiday_rule_name`, `rules_schedules`.`id` AS `attendance_holiday_rules_schedule_id`, `permission_groups_rel`.`core_permission_group_id` AS `core_permission_group_id`, `rules`.`year` AS `planning_year`, `rules_schedules`.`planning_from_date` AS `planning_from_date`, `rules_schedules`.`planning_to_date` AS `planning_to_date`, `rules_schedules`.`planning_deadline` AS `planning_deadline`, `rules_schedules`.`percentage_of_holidays` AS `percentage_of_holidays`, `rules_schedules`.`minimum_number_of_required_days` AS `minimum_number_of_required_days`, `rules_schedules`.`worker_has` AS `worker_has`, `rules_schedules`.`required_at_once` AS `required_at_once`, `holiday_amounts`.`value` AS `total_holiday_amounts`, sum(`total`.`so_far_total`) AS `so_far_total`, (case when (`rules_schedules`.`planning_to_date` <> `max`.`last_max`) then round(((`holiday_amounts`.`value` / 100) * `rules_schedules`.`percentage_of_holidays`),0) else (round(((`holiday_amounts`.`value` / 100) * `rules_schedules`.`percentage_of_holidays`),0) - (sum(`total`.`so_far_total`) - `holiday_amounts`.`value`)) end) AS `percentage_proportionally_holiday_amounts`, `offdays`.`used` AS `used`, `offdays`.`used_dates` AS `used_dates`, `offdays`.`used_created_ats` AS `used_created_ats`, `offdays`.`used_abscences_ids` AS `used_abscences_ids`, `offdays`.`planned` AS `planned`, `offdays`.`planned_dates` AS `planned_dates`, `offdays`.`planned_created_ats` AS `planned_created_ats`, `offdays`.`planned_abscences_ids` AS `planned_abscences_ids` FROM (((((((`attendance_holiday_rules` `rules` join `attendance_holiday_rules_legal_entities_rel` `legal_entities_rel` on((`legal_entities_rel`.`attendance_holiday_rule_id` = `rules`.`id`))) left join `attendance_holiday_rule_schedules` `rules_schedules` on((`rules_schedules`.`attendance_holiday_rule_id` = `rules`.`id`))) left join (select `attendance_holiday_rule_schedules`.`attendance_holiday_rule_id` AS `max_rules_id`,max(`attendance_holiday_rule_schedules`.`planning_to_date`) AS `last_max` from `attendance_holiday_rule_schedules` where (`attendance_holiday_rule_schedules`.`percentage_of_holidays` > 0) group by `max_rules_id`) `max` on((`rules`.`id` = `max`.`max_rules_id`))) left join (select `attendance_holiday_rules_legal_entities_rel`.`core_legal_entities_id` AS `legal_entity`,`attendance_legal_entities_holiday_amounts`.`year` AS `total_year`,`attendance_holiday_rules`.`id` AS `rules_id`,`attendance_holiday_rule_schedules`.`id` AS `schedules_id`,round((sum(`attendance_legal_entities_holiday_amounts`.`value`) * (`attendance_holiday_rule_schedules`.`percentage_of_holidays` / 100)),0) AS `so_far_total` from (((`attendance_holiday_rules_legal_entities_rel` left join `attendance_holiday_rules` on((`attendance_holiday_rules`.`id` = `attendance_holiday_rules_legal_entities_rel`.`attendance_holiday_rule_id`))) left join `attendance_holiday_rule_schedules` on((`attendance_holiday_rule_schedules`.`attendance_holiday_rule_id` = `attendance_holiday_rules`.`id`))) left join `attendance_legal_entities_holiday_amounts` on(((`attendance_holiday_rules_legal_entities_rel`.`core_legal_entities_id` = `attendance_legal_entities_holiday_amounts`.`core_legal_entities_id`) and (0 <> find_in_set(`attendance_legal_entities_holiday_amounts`.`attendance_offday_types_id`,'1,2,3,4,5,6,7,99')) and (`attendance_legal_entities_holiday_amounts`.`year` = `attendance_holiday_rules`.`year`)))) group by `attendance_holiday_rules_legal_entities_rel`.`core_legal_entities_id`,`attendance_legal_entities_holiday_amounts`.`year`,`attendance_holiday_rules`.`id`,`attendance_holiday_rule_schedules`.`id`) `total` on(((`total`.`legal_entity` = `legal_entities_rel`.`core_legal_entities_id`) and (`total`.`total_year` = `rules`.`year`)))) left join (select `attendance_holiday_rule_schedules_permission_groups_rel`.`attendance_holiday_rule_schedule_id` AS `attendance_holiday_rule_schedule_id`,concat(group_concat(`attendance_holiday_rule_schedules_permission_groups_rel`.`core_permission_group_id` order by `attendance_holiday_rule_schedules_permission_groups_rel`.`core_permission_group_id` ASC separator ',')) AS `core_permission_group_id` from `attendance_holiday_rule_schedules_permission_groups_rel` group by `attendance_holiday_rule_schedules_permission_groups_rel`.`attendance_holiday_rule_schedule_id`) `permission_groups_rel` on((`permission_groups_rel`.`attendance_holiday_rule_schedule_id` = `rules_schedules`.`id`))) left join (select `attendance_legal_entities_holiday_amounts`.`core_legal_entities_id` AS `core_legal_entities_id`,`attendance_legal_entities_holiday_amounts`.`year` AS `year`,sum(`attendance_legal_entities_holiday_amounts`.`value`) AS `value`,sum(`attendance_legal_entities_holiday_amounts`.`used_value`) AS `used_value` from `attendance_legal_entities_holiday_amounts` where (0 <> find_in_set(`attendance_legal_entities_holiday_amounts`.`attendance_offday_types_id`,'1,2,3,4,5,6,7,99')) group by `attendance_legal_entities_holiday_amounts`.`core_legal_entities_id`,`attendance_legal_entities_holiday_amounts`.`year`) `holiday_amounts` on(((`holiday_amounts`.`core_legal_entities_id` = `legal_entities_rel`.`core_legal_entities_id`) and (`holiday_amounts`.`year` = `rules`.`year`)))) left join (select `dates`.`core_legal_entities_id` AS `core_legal_entities_id`,`dates`.`id` AS `id`,sum(`dates`.`used`) AS `used`,group_concat(`dates`.`used_dates` separator ',') AS `used_dates`,group_concat(`dates`.`used_created_ats` separator ',') AS `used_created_ats`,group_concat(`dates`.`used_abscences_ids` separator ',') AS `used_abscences_ids`,sum(`dates`.`planned`) AS `planned`,group_concat(`dates`.`planned_dates` separator ',') AS `planned_dates`,group_concat(`dates`.`planned_created_ats` separator ',') AS `planned_created_ats`,group_concat(`dates`.`planned_abscences_ids` separator ',') AS `planned_abscences_ids` from (select `calendar`.`core_legal_entities_id` AS `core_legal_entities_id`,`rules_schedules`.`id` AS `id`,sum((case when `calendar`.`_date` then 1 else 0 end)) AS `used`,group_concat(`calendar`.`_date` order by `calendar`.`_date` ASC separator ',') AS `used_dates`,group_concat(cast(`abscences`.`created_at` as date) order by `calendar`.`_date` ASC separator ',') AS `used_created_ats`,group_concat(`abscences`.`id` order by `calendar`.`_date` ASC separator ',') AS `used_abscences_ids`,NULL AS `planned`,NULL AS `planned_dates`,NULL AS `planned_created_ats`,NULL AS `planned_abscences_ids` from ((((`attendance_legal_entities_abscences` `abscences` join `attendance_legal_entities_calendar` `calendar` on((`calendar`.`id` = `abscences`.`registered_calendar_id`))) join `attendance_holiday_rules_legal_entities_rel` `legal_entities_rel` on((`legal_entities_rel`.`core_legal_entities_id` = `calendar`.`core_legal_entities_id`))) join `attendance_holiday_rules` `rules` on((`rules`.`id` = `legal_entities_rel`.`attendance_holiday_rule_id`))) join `attendance_holiday_rule_schedules` `rules_schedules` on((`rules_schedules`.`attendance_holiday_rule_id` = `rules`.`id`))) where ((`calendar`.`_date` between `rules_schedules`.`planning_from_date` and `rules_schedules`.`planning_to_date`) and (`calendar`.`legal_entity_confirmed` = 1) and (`calendar`.`boss_confirmed` = 1) and (`abscences`.`attendance_abscence_types_id` is null) and (`abscences`.`attendance_offday_types_id` is not null) and (0 <> find_in_set(`abscences`.`attendance_offday_types_id`,'1,2,3,4,5,6,7,99'))) group by `calendar`.`core_legal_entities_id`,`rules_schedules`.`id` union select `calendar`.`core_legal_entities_id` AS `core_legal_entities_id`,`rules_schedules`.`id` AS `id`,NULL AS `used`,NULL AS `used_dates`,NULL AS `used_created_ats`,NULL AS `used_abscences_ids`,sum((case when `calendar`.`_date` then 1 else 0 end)) AS `planned`,group_concat(`calendar`.`_date` order by `calendar`.`_date` ASC separator ',') AS `planned_dates`,group_concat(cast(`abscences`.`created_at` as date) order by `calendar`.`_date` ASC separator ',') AS `planned_created_ats`,group_concat(`abscences`.`id` order by `calendar`.`_date` ASC separator ',') AS `planned_abscences_ids` from ((((`attendance_legal_entities_abscences` `abscences` join `attendance_legal_entities_calendar` `calendar` on((`calendar`.`id` = `abscences`.`registered_calendar_id`))) join `attendance_holiday_rules_legal_entities_rel` `legal_entities_rel` on((`legal_entities_rel`.`core_legal_entities_id` = `calendar`.`core_legal_entities_id`))) join `attendance_holiday_rules` `rules` on((`rules`.`id` = `legal_entities_rel`.`attendance_holiday_rule_id`))) join `attendance_holiday_rule_schedules` `rules_schedules` on((`rules_schedules`.`attendance_holiday_rule_id` = `rules`.`id`))) where ((`calendar`.`_date` between `rules_schedules`.`planning_from_date` and `rules_schedules`.`planning_to_date`) and ((`calendar`.`legal_entity_confirmed` = 0) or (`calendar`.`boss_confirmed` = 0)) and (`abscences`.`attendance_abscence_types_id` is null) and (`abscences`.`attendance_offday_types_id` is not null) and (0 <> find_in_set(`abscences`.`attendance_offday_types_id`,'1,2,3,4,5,6,7,99'))) group by `calendar`.`core_legal_entities_id`,`rules_schedules`.`id`) `dates` group by `dates`.`core_legal_entities_id`,`dates`.`id`) `offdays` on(((`offdays`.`core_legal_entities_id` = `legal_entities_rel`.`core_legal_entities_id`) and (`offdays`.`id` = `rules_schedules`.`id`)))) GROUP BY `legal_entities_rel`.`core_legal_entities_id`, `rules`.`id`, `rules_schedules`.`id`, `holiday_amounts`.`value`, `offdays`.`used`, `offdays`.`used_dates`, `offdays`.`planned`, `offdays`.`planned_dates` ORDER BY `legal_entities_rel`.`core_legal_entities_id` ASC, `rules`.`id` ASC, `rules_schedules`.`id` ASC ;

-- --------------------------------------------------------

--
-- Nézet szerkezete `view_legal_entities_abscences_query`
--
DROP TABLE IF EXISTS `view_legal_entities_abscences_query`;

DROP VIEW IF EXISTS `view_legal_entities_abscences_query`;
CREATE ALGORITHM=UNDEFINED DEFINER=`ej2_showtime_p`@`localhost` SQL SECURITY DEFINER VIEW `view_legal_entities_abscences_query`  AS SELECT `abs`.`payroll_calendar_id` AS `attendance_legal_entities_calendar_id`, `abs`.`id` AS `attendance_legal_entities_abscences_id`, `abs`.`attendance_abscence_types_id` AS `attendance_abscence_types_id`, `lea`.`value` AS `core_firms_id`, `cal`.`_date` AS `_date`, `cal`.`core_legal_entities_id` AS `core_legal_entities_id`, `abs`.`creator_legal_entity_id` AS `creator_legal_entity_id`, `cal`.`legal_entity_confirmed` AS `legal_entity_confirmed`, `cal`.`boss_confirmed` AS `boss_confirmed`, `cal`.`boss_confirmed_id` AS `boss_confirmed_id` FROM ((`attendance_legal_entities_abscences` `abs` join `attendance_legal_entities_calendar` `cal` on((`abs`.`payroll_calendar_id` = `cal`.`id`))) join `core_legal_entities_attributes_values` `lea` on(((`cal`.`core_legal_entities_id` = `lea`.`core_legal_entities_id`) and (`lea`.`core_legal_entities_attributes_id` = 2)))) WHERE ((`abs`.`attendance_abscence_types_id` is not null) AND (`abs`.`attendance_legal_entities_shifts_id` is null)) ;

-- --------------------------------------------------------

--
-- Nézet szerkezete `view_legal_entities_offdays_query`
--
DROP TABLE IF EXISTS `view_legal_entities_offdays_query`;

DROP VIEW IF EXISTS `view_legal_entities_offdays_query`;
CREATE ALGORITHM=UNDEFINED DEFINER=`ej2_showtime_p`@`localhost` SQL SECURITY DEFINER VIEW `view_legal_entities_offdays_query`  AS SELECT `off`.`payroll_calendar_id` AS `attendance_legal_entities_calendar_id`, `off`.`id` AS `attendance_legal_entities_abscences_id`, `off`.`attendance_offday_types_id` AS `attendance_offday_types_id`, `lea`.`value` AS `core_firms_id`, `cal`.`_date` AS `_date`, `cal`.`core_legal_entities_id` AS `core_legal_entities_id`, `off`.`creator_legal_entity_id` AS `creator_legal_entity_id`, `cal`.`legal_entity_confirmed` AS `legal_entity_confirmed`, `cal`.`boss_confirmed` AS `boss_confirmed`, `cal`.`boss_confirmed_id` AS `boss_confirmed_id`, cast(`off`.`created_at` as date) AS `created_at` FROM ((`attendance_legal_entities_abscences` `off` join `attendance_legal_entities_calendar` `cal` on((`off`.`payroll_calendar_id` = `cal`.`id`))) join `core_legal_entities_attributes_values` `lea` on(((`cal`.`core_legal_entities_id` = `lea`.`core_legal_entities_id`) and (`lea`.`core_legal_entities_attributes_id` = 2)))) WHERE ((`off`.`attendance_offday_types_id` is not null) AND (`off`.`attendance_legal_entities_shifts_id` is null)) ;

-- --------------------------------------------------------

--
-- Nézet szerkezete `view_legal_entities_query`
--
DROP TABLE IF EXISTS `view_legal_entities_query`;

DROP VIEW IF EXISTS `view_legal_entities_query`;
CREATE ALGORITHM=UNDEFINED DEFINER=`ej2_showtime_p`@`localhost` SQL SECURITY DEFINER VIEW `view_legal_entities_query`  AS SELECT `core_legal_entities`.`core_legal_entities_id` AS `core_legal_entities_id`, `core_legal_entities`.`core_firms_id` AS `core_firms_id`, `core_legal_entities`.`legal_entity_name` AS `legal_entity_name`, `core_legal_entities`.`core_establishments_id` AS `core_establishments_id`, `core_legal_entities`.`core_departments_id` AS `core_departments_id`, `core_legal_entities`.`start_date` AS `start_date`, `core_legal_entities`.`last_workday_date` AS `last_workday_date`, `core_legal_entities`.`end_date` AS `end_date`, `core_legal_entities`.`last_export_date` AS `last_export_date`, `core_legal_entities`.`outsourced_admin` AS `outsourced_admin`, `core_legal_entities`.`payroll_software_id` AS `payroll_software_id`, `core_legal_entities`.`virtual_legal_entity` AS `virtual_legal_entity`, `core_legal_entities`.`core_persons_id` AS `core_persons_id`, `core_persons`.`person_name` AS `person_name`, `core_persons`.`person_email` AS `person_email` FROM ((select `core_legal_entities_attributes_values`.`core_legal_entities_id` AS `core_legal_entities_id`,group_concat(if((`core_legal_entities_attributes_values`.`core_legal_entities_attributes_id` = 2),`core_legal_entities_attributes_values`.`value`,'') separator '') AS `core_firms_id`,group_concat(if((`core_legal_entities_attributes_values`.`core_legal_entities_attributes_id` = 1),`core_legal_entities_attributes_values`.`value`,'') separator '') AS `legal_entity_name`,group_concat(if((`core_legal_entities_attributes_values`.`core_legal_entities_attributes_id` = 10),`core_legal_entities_attributes_values`.`value`,'') separator '') AS `core_establishments_id`,group_concat(if((`core_legal_entities_attributes_values`.`core_legal_entities_attributes_id` = 11),`core_legal_entities_attributes_values`.`value`,'') separator '') AS `core_departments_id`,group_concat(if((`core_legal_entities_attributes_values`.`core_legal_entities_attributes_id` = 7),`core_legal_entities_attributes_values`.`value`,'') separator '') AS `start_date`,group_concat(if((`core_legal_entities_attributes_values`.`core_legal_entities_attributes_id` = 14),`core_legal_entities_attributes_values`.`value`,'') separator '') AS `last_workday_date`,group_concat(if((`core_legal_entities_attributes_values`.`core_legal_entities_attributes_id` = 8),`core_legal_entities_attributes_values`.`value`,'') separator '') AS `end_date`,group_concat(if((`core_legal_entities_attributes_values`.`core_legal_entities_attributes_id` = 9),`core_legal_entities_attributes_values`.`value`,'') separator '') AS `last_export_date`,group_concat(if((`core_legal_entities_attributes_values`.`core_legal_entities_attributes_id` = 16),`core_legal_entities_attributes_values`.`value`,'') separator '') AS `outsourced_admin`,group_concat(if((`core_legal_entities_attributes_values`.`core_legal_entities_attributes_id` = 15),`core_legal_entities_attributes_values`.`value`,'') separator '') AS `payroll_software_id`,group_concat(if((`core_legal_entities_attributes_values`.`core_legal_entities_attributes_id` = 18),`core_legal_entities_attributes_values`.`value`,'') separator '') AS `virtual_legal_entity`,group_concat(if((`core_legal_entities_attributes_values`.`core_legal_entities_attributes_id` = 3),`core_legal_entities_attributes_values`.`value`,'') separator '') AS `core_persons_id` from `core_legal_entities_attributes_values` where (`core_legal_entities_attributes_values`.`core_legal_entities_attributes_id` in (1,2,3,7,8,9,10,11,14,15,16,18)) group by `core_legal_entities_attributes_values`.`core_legal_entities_id`) `core_legal_entities` join (select `core_persons_attributes_values`.`core_persons_id` AS `core_persons_id`,group_concat(if((`core_persons_attributes_values`.`core_persons_attributes_id` = 4),`core_persons_attributes_values`.`value`,'') separator '') AS `person_name`,group_concat(if((`core_persons_attributes_values`.`core_persons_attributes_id` = 2),`core_persons_attributes_values`.`value`,'') separator '') AS `person_email` from `core_persons_attributes_values` where (`core_persons_attributes_values`.`core_persons_attributes_id` in (2,4)) group by `core_persons_attributes_values`.`core_persons_id`) `core_persons` on((`core_persons`.`core_persons_id` = `core_legal_entities`.`core_persons_id`))) ;

-- --------------------------------------------------------

--
-- Nézet szerkezete `view_persons_query`
--
DROP TABLE IF EXISTS `view_persons_query`;

DROP VIEW IF EXISTS `view_persons_query`;
CREATE ALGORITHM=UNDEFINED DEFINER=`ej2_showtime_p`@`localhost` SQL SECURITY DEFINER VIEW `view_persons_query`  AS SELECT `core_persons`.`core_persons_id` AS `core_persons_id`, `core_persons`.`email` AS `email`, `core_persons`.`name` AS `name`, `core_persons`.`ohr` AS `ohr`, `core_persons`.`other_identification` AS `other_identification`, `core_persons`.`password` AS `password`, `core_persons`.`active` AS `active`, `core_persons`.`lang` AS `lang`, `core_persons`.`password_repeat` AS `password_repeat`, `core_persons`.`tax_id` AS `tax_id`, `core_persons`.`forbid_logging_in` AS `forbid_logging_in` FROM (select `core_persons_attributes_values`.`core_persons_id` AS `core_persons_id`,group_concat(if((`core_persons_attributes_values`.`core_persons_attributes_id` = 1),`core_persons_attributes_values`.`value`,'') separator '') AS `ohr`,group_concat(if((`core_persons_attributes_values`.`core_persons_attributes_id` = 2),`core_persons_attributes_values`.`value`,'') separator '') AS `email`,group_concat(if((`core_persons_attributes_values`.`core_persons_attributes_id` = 3),`core_persons_attributes_values`.`value`,'') separator '') AS `other_identification`,group_concat(if((`core_persons_attributes_values`.`core_persons_attributes_id` = 4),`core_persons_attributes_values`.`value`,'') separator '') AS `name`,group_concat(if((`core_persons_attributes_values`.`core_persons_attributes_id` = 5),`core_persons_attributes_values`.`value`,'') separator '') AS `password`,group_concat(if((`core_persons_attributes_values`.`core_persons_attributes_id` = 6),`core_persons_attributes_values`.`value`,'') separator '') AS `active`,group_concat(if((`core_persons_attributes_values`.`core_persons_attributes_id` = 7),`core_persons_attributes_values`.`value`,'') separator '') AS `lang`,group_concat(if((`core_persons_attributes_values`.`core_persons_attributes_id` = 8),`core_persons_attributes_values`.`value`,'') separator '') AS `password_repeat`,group_concat(if((`core_persons_attributes_values`.`core_persons_attributes_id` = 9),`core_persons_attributes_values`.`value`,'') separator '') AS `tax_id`,group_concat(if((`core_persons_attributes_values`.`core_persons_attributes_id` = 10),`core_persons_attributes_values`.`value`,'') separator '') AS `forbid_logging_in` from `core_persons_attributes_values` where (`core_persons_attributes_values`.`core_persons_attributes_id` in (1,2,3,4,5,6,7,8,9,10)) group by `core_persons_attributes_values`.`core_persons_id`) AS `core_persons` ;

-- --------------------------------------------------------

--
-- Nézet szerkezete `view_special_permissions_query`
--
DROP TABLE IF EXISTS `view_special_permissions_query`;

DROP VIEW IF EXISTS `view_special_permissions_query`;
CREATE ALGORITHM=UNDEFINED DEFINER=`ej2_showtime_p`@`localhost` SQL SECURITY DEFINER VIEW `view_special_permissions_query`  AS SELECT `rel0`.`core_actions_id` AS `core_actions_id`, `rel1`.`core_legal_entities_id` AS `source_legal_entity_id`, `rel2`.`core_legal_entities_id` AS `target_legal_entity_id`, `p`.`core_persons_id` AS `target_person_id`, `p`.`value` AS `target_person_name` FROM (((((`core_special_permissions` `sp` join `core_special_permissions_core_actions_rel` `rel0` on((`rel0`.`core_special_permissions_id` = `sp`.`id`))) join `core_permission_groups_core_legal_entities_rel` `rel1` on((`rel1`.`core_permission_groups_id` = `sp`.`source_permission_group_id`))) join `core_permission_groups_core_legal_entities_rel` `rel2` on((`rel2`.`core_permission_groups_id` = `sp`.`target_permission_group_id`))) join `core_legal_entities_attributes_values` `le` on(((`rel2`.`core_legal_entities_id` = `le`.`core_legal_entities_id`) and (`le`.`core_legal_entities_attributes_id` = 3)))) join `core_persons_attributes_values` `p` on(((`le`.`value` = `p`.`core_persons_id`) and (`p`.`core_persons_attributes_id` = 4)))) ;

--
-- Megkötések a kiírt táblákhoz
--

--
-- Megkötések a táblához `attendance_abscence_types_core_firms_rel`
--
ALTER TABLE `attendance_abscence_types_core_firms_rel`
  ADD CONSTRAINT `key2077` FOREIGN KEY (`core_other_incomes_id`) REFERENCES `core_other_incomes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key4018` FOREIGN KEY (`attendance_abscence_types_id`) REFERENCES `attendance_abscence_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key4019` FOREIGN KEY (`core_firms_id`) REFERENCES `core_firms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `attendance_access_control_systems_calendar_rel`
--
ALTER TABLE `attendance_access_control_systems_calendar_rel`
  ADD CONSTRAINT `key2057` FOREIGN KEY (`calendar_id`) REFERENCES `attendance_legal_entities_calendar` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `attendance_access_control_systems_extra_addresses`
--
ALTER TABLE `attendance_access_control_systems_extra_addresses`
  ADD CONSTRAINT `key20031` FOREIGN KEY (`core_firms_id`) REFERENCES `core_firms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `attendance_access_control_systems_rules`
--
ALTER TABLE `attendance_access_control_systems_rules`
  ADD CONSTRAINT `key2058` FOREIGN KEY (`core_firms_id`) REFERENCES `core_firms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `attendance_approval_buttons`
--
ALTER TABLE `attendance_approval_buttons`
  ADD CONSTRAINT `key12798` FOREIGN KEY (`legalentity_id`) REFERENCES `core_legal_entities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key12799` FOREIGN KEY (`core_email_notifications_id`) REFERENCES `core_email_notifications` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `attendance_approval_links`
--
ALTER TABLE `attendance_approval_links`
  ADD CONSTRAINT `key7006` FOREIGN KEY (`user_id`) REFERENCES `core_legal_entities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key7007` FOREIGN KEY (`legalentity_id`) REFERENCES `core_legal_entities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key7008` FOREIGN KEY (`core_email_notifications_id`) REFERENCES `core_email_notifications` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `attendance_attendance_sheet_export_settings`
--
ALTER TABLE `attendance_attendance_sheet_export_settings`
  ADD CONSTRAINT `key1012` FOREIGN KEY (`core_firms_id`) REFERENCES `core_firms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `attendance_attendance_sheet_settings`
--
ALTER TABLE `attendance_attendance_sheet_settings`
  ADD CONSTRAINT `key0062` FOREIGN KEY (`core_firms_id`) REFERENCES `core_firms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `attendance_bonus_types`
--
ALTER TABLE `attendance_bonus_types`
  ADD CONSTRAINT `key036` FOREIGN KEY (`core_firms_id`) REFERENCES `core_firms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key4002` FOREIGN KEY (`core_firms_id`) REFERENCES `core_firms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `attendance_holiday_rules_legal_entities_rel`
--
ALTER TABLE `attendance_holiday_rules_legal_entities_rel`
  ADD CONSTRAINT `key2106` FOREIGN KEY (`attendance_holiday_rule_id`) REFERENCES `attendance_holiday_rules` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `attendance_holiday_rule_schedules`
--
ALTER TABLE `attendance_holiday_rule_schedules`
  ADD CONSTRAINT `key2108` FOREIGN KEY (`attendance_holiday_rule_id`) REFERENCES `attendance_holiday_rules` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `attendance_holiday_rule_schedules_permission_groups_rel`
--
ALTER TABLE `attendance_holiday_rule_schedules_permission_groups_rel`
  ADD CONSTRAINT `key2107` FOREIGN KEY (`attendance_holiday_rule_schedule_id`) REFERENCES `attendance_holiday_rule_schedules` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `attendance_legal_entities_abscences`
--
ALTER TABLE `attendance_legal_entities_abscences`
  ADD CONSTRAINT `key0053` FOREIGN KEY (`registered_calendar_id`) REFERENCES `attendance_legal_entities_calendar` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key0054` FOREIGN KEY (`payroll_calendar_id`) REFERENCES `attendance_legal_entities_calendar` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key0055` FOREIGN KEY (`attendance_legal_entities_shifts_id`) REFERENCES `attendance_legal_entities_shifts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key0056` FOREIGN KEY (`attendance_abscence_types_id`) REFERENCES `attendance_abscence_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key0057` FOREIGN KEY (`attendance_offday_types_id`) REFERENCES `attendance_offday_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key0058` FOREIGN KEY (`creator_legal_entity_id`) REFERENCES `core_legal_entities` (`id`);

--
-- Megkötések a táblához `attendance_legal_entities_bonuses_within_shift`
--
ALTER TABLE `attendance_legal_entities_bonuses_within_shift`
  ADD CONSTRAINT `key3006` FOREIGN KEY (`registered_calendar_id`) REFERENCES `attendance_legal_entities_calendar` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key3007` FOREIGN KEY (`payroll_calendar_id`) REFERENCES `attendance_legal_entities_calendar` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key3008` FOREIGN KEY (`attendance_legal_entities_shifts_id`) REFERENCES `attendance_legal_entities_shifts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key3009` FOREIGN KEY (`attendance_bonus_types_id`) REFERENCES `attendance_bonus_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key3010` FOREIGN KEY (`creator_legal_entity_id`) REFERENCES `core_legal_entities` (`id`);

--
-- Megkötések a táblához `attendance_legal_entities_calendar`
--
ALTER TABLE `attendance_legal_entities_calendar`
  ADD CONSTRAINT `key0035` FOREIGN KEY (`core_legal_entities_id`) REFERENCES `core_legal_entities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key0036` FOREIGN KEY (`boss_confirmed_id`) REFERENCES `core_legal_entities` (`id`);

--
-- Megkötések a táblához `attendance_legal_entities_calendar_attendance_places_of_work_rel`
--
ALTER TABLE `attendance_legal_entities_calendar_attendance_places_of_work_rel`
  ADD CONSTRAINT `key0049` FOREIGN KEY (`attendance_legal_entities_calendar_id`) REFERENCES `attendance_legal_entities_calendar` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key0050` FOREIGN KEY (`attendance_places_of_work_id`) REFERENCES `attendance_places_of_work` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `attendance_legal_entities_calendar_rejections`
--
ALTER TABLE `attendance_legal_entities_calendar_rejections`
  ADD CONSTRAINT `key0051` FOREIGN KEY (`attendance_legal_entities_calendar_id`) REFERENCES `attendance_legal_entities_calendar` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key0052` FOREIGN KEY (`core_legal_entities_id`) REFERENCES `core_legal_entities` (`id`);

--
-- Megkötések a táblához `attendance_legal_entities_carried_balance`
--
ALTER TABLE `attendance_legal_entities_carried_balance`
  ADD CONSTRAINT `key2008` FOREIGN KEY (`core_legal_entities_id`) REFERENCES `core_legal_entities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `attendance_legal_entities_details_backup`
--
ALTER TABLE `attendance_legal_entities_details_backup`
  ADD CONSTRAINT `key2568` FOREIGN KEY (`core_legal_entities_id`) REFERENCES `core_legal_entities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `attendance_legal_entities_holiday_amounts`
--
ALTER TABLE `attendance_legal_entities_holiday_amounts`
  ADD CONSTRAINT `key4016` FOREIGN KEY (`core_legal_entities_id`) REFERENCES `core_legal_entities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key4017` FOREIGN KEY (`attendance_offday_types_id`) REFERENCES `attendance_offday_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `attendance_legal_entities_outside_shifts_time`
--
ALTER TABLE `attendance_legal_entities_outside_shifts_time`
  ADD CONSTRAINT `key0045` FOREIGN KEY (`attendance_legal_entities_calendar_id`) REFERENCES `attendance_legal_entities_calendar` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key0046` FOREIGN KEY (`creator_legal_entity_id`) REFERENCES `core_legal_entities` (`id`),
  ADD CONSTRAINT `key1020` FOREIGN KEY (`attendance_standby_types_id`) REFERENCES `attendance_standby_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `attendance_legal_entities_outside_shifts_time_bonuses_rel`
--
ALTER TABLE `attendance_legal_entities_outside_shifts_time_bonuses_rel`
  ADD CONSTRAINT `key0047` FOREIGN KEY (`attendance_legal_entities_outside_shifts_time_id`) REFERENCES `attendance_legal_entities_outside_shifts_time` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key0048` FOREIGN KEY (`attendance_bonus_types_id`) REFERENCES `attendance_bonus_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `attendance_legal_entities_overtimes`
--
ALTER TABLE `attendance_legal_entities_overtimes`
  ADD CONSTRAINT `key0039` FOREIGN KEY (`registered_calendar_id`) REFERENCES `attendance_legal_entities_calendar` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key0040` FOREIGN KEY (`payroll_calendar_id`) REFERENCES `attendance_legal_entities_calendar` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key0041` FOREIGN KEY (`attendance_legal_entities_shifts_id`) REFERENCES `attendance_legal_entities_shifts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key0042` FOREIGN KEY (`creator_legal_entity_id`) REFERENCES `core_legal_entities` (`id`);

--
-- Megkötések a táblához `attendance_legal_entities_overtimes_bonuses_rel`
--
ALTER TABLE `attendance_legal_entities_overtimes_bonuses_rel`
  ADD CONSTRAINT `key0043` FOREIGN KEY (`attendance_legal_entities_overtimes_id`) REFERENCES `attendance_legal_entities_overtimes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key0044` FOREIGN KEY (`attendance_bonus_types_id`) REFERENCES `attendance_bonus_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `attendance_legal_entities_shifts`
--
ALTER TABLE `attendance_legal_entities_shifts`
  ADD CONSTRAINT `key0037` FOREIGN KEY (`registered_calendar_id`) REFERENCES `attendance_legal_entities_calendar` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key0038` FOREIGN KEY (`attendance_shift_types_id`) REFERENCES `attendance_shift_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key0064` FOREIGN KEY (`payroll_calendar_id`) REFERENCES `attendance_legal_entities_calendar` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `attendance_legal_entities_shifts_balances`
--
ALTER TABLE `attendance_legal_entities_shifts_balances`
  ADD CONSTRAINT `key0059` FOREIGN KEY (`registered_calendar_id`) REFERENCES `attendance_legal_entities_calendar` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key0060` FOREIGN KEY (`payroll_calendar_id`) REFERENCES `attendance_legal_entities_calendar` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key0061` FOREIGN KEY (`attendance_legal_entities_shifts_id`) REFERENCES `attendance_legal_entities_shifts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `attendance_legal_entities_shifts_workhours`
--
ALTER TABLE `attendance_legal_entities_shifts_workhours`
  ADD CONSTRAINT `key3000` FOREIGN KEY (`registered_calendar_id`) REFERENCES `attendance_legal_entities_calendar` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key3001` FOREIGN KEY (`payroll_calendar_id`) REFERENCES `attendance_legal_entities_calendar` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key3002` FOREIGN KEY (`attendance_legal_entities_shifts_id`) REFERENCES `attendance_legal_entities_shifts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `attendance_legal_stock`
--
ALTER TABLE `attendance_legal_stock`
  ADD CONSTRAINT `key1008` FOREIGN KEY (`core_legal_entities_id`) REFERENCES `core_legal_entities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key1009` FOREIGN KEY (`attendance_abscence_types_id`) REFERENCES `attendance_abscence_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key1010` FOREIGN KEY (`core_firms_id`) REFERENCES `core_firms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `attendance_offday_types_core_firms_order`
--
ALTER TABLE `attendance_offday_types_core_firms_order`
  ADD CONSTRAINT `key0013` FOREIGN KEY (`attendance_offday_types_id`) REFERENCES `attendance_offday_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key0014` FOREIGN KEY (`core_firms_id`) REFERENCES `core_firms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key2078` FOREIGN KEY (`core_other_incomes_id`) REFERENCES `core_other_incomes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `attendance_places_of_work`
--
ALTER TABLE `attendance_places_of_work`
  ADD CONSTRAINT `key4007` FOREIGN KEY (`core_firms_id`) REFERENCES `core_firms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `attendance_places_of_work_bonuses_rel`
--
ALTER TABLE `attendance_places_of_work_bonuses_rel`
  ADD CONSTRAINT `key2098` FOREIGN KEY (`attendance_places_of_work_id`) REFERENCES `attendance_places_of_work` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key2099` FOREIGN KEY (`attendance_bonus_types_id`) REFERENCES `attendance_bonus_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `attendance_rounding_settings`
--
ALTER TABLE `attendance_rounding_settings`
  ADD CONSTRAINT `key1011` FOREIGN KEY (`core_firms_id`) REFERENCES `core_firms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `attendance_shift_breaks`
--
ALTER TABLE `attendance_shift_breaks`
  ADD CONSTRAINT `key7000` FOREIGN KEY (`attendance_shift_types_id`) REFERENCES `attendance_shift_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `attendance_shift_types`
--
ALTER TABLE `attendance_shift_types`
  ADD CONSTRAINT `key037` FOREIGN KEY (`core_firms_id`) REFERENCES `core_firms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key20300` FOREIGN KEY (`sunday_bonus_type_id`) REFERENCES `attendance_bonus_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key20301` FOREIGN KEY (`holiday_bonus_type_id`) REFERENCES `attendance_bonus_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `attendance_standby_overtimes`
--
ALTER TABLE `attendance_standby_overtimes`
  ADD CONSTRAINT `key1021` FOREIGN KEY (`attendance_legal_entities_outside_shifts_time_id`) REFERENCES `attendance_legal_entities_outside_shifts_time` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `attendance_standby_types`
--
ALTER TABLE `attendance_standby_types`
  ADD CONSTRAINT `key7001` FOREIGN KEY (`core_firms_id`) REFERENCES `core_firms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `attendance_verification_conditions_details`
--
ALTER TABLE `attendance_verification_conditions_details`
  ADD CONSTRAINT `key2061` FOREIGN KEY (`attendance_verification_conditions_id`) REFERENCES `attendance_verification_conditions` (`id`);

--
-- Megkötések a táblához `attendance_verification_condition_systems`
--
ALTER TABLE `attendance_verification_condition_systems`
  ADD CONSTRAINT `key0032` FOREIGN KEY (`core_firms_id`) REFERENCES `core_firms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `attendance_verification_condition_values`
--
ALTER TABLE `attendance_verification_condition_values`
  ADD CONSTRAINT `key0033` FOREIGN KEY (`attendance_verification_condition_system_id`) REFERENCES `attendance_verification_condition_systems` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key0034` FOREIGN KEY (`attendance_verification_condition_id`) REFERENCES `attendance_verification_conditions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `attendance_workday_divisions`
--
ALTER TABLE `attendance_workday_divisions`
  ADD CONSTRAINT `key1027` FOREIGN KEY (`attendance_legal_entities_calendar_id`) REFERENCES `attendance_legal_entities_calendar` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key1028` FOREIGN KEY (`attendance_legal_entities_shifts_id`) REFERENCES `attendance_legal_entities_shifts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key1029` FOREIGN KEY (`attendance_legal_entities_overtimes_id`) REFERENCES `attendance_legal_entities_overtimes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key1030` FOREIGN KEY (`attendance_legal_entities_outside_shifts_time_id`) REFERENCES `attendance_legal_entities_outside_shifts_time` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key1031` FOREIGN KEY (`attendance_standby_overtimes_id`) REFERENCES `attendance_standby_overtimes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key1329` FOREIGN KEY (`attendance_legal_entities_abscences_id`) REFERENCES `attendance_legal_entities_abscences` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key1330` FOREIGN KEY (`attendance_shift_breaks_id`) REFERENCES `attendance_shift_breaks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `attendance_workday_divisions_order`
--
ALTER TABLE `attendance_workday_divisions_order`
  ADD CONSTRAINT `key2021` FOREIGN KEY (`core_firms_id`) REFERENCES `core_firms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `attendance_workplans`
--
ALTER TABLE `attendance_workplans`
  ADD CONSTRAINT `key0015` FOREIGN KEY (`core_firms_id`) REFERENCES `core_firms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key0016` FOREIGN KEY (`attendance_workplan_categories_id`) REFERENCES `attendance_workplan_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key0017` FOREIGN KEY (`attendance_verification_condition_systems_id`) REFERENCES `attendance_verification_condition_systems` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key0018` FOREIGN KEY (`attendance_access_control_systems_id`) REFERENCES `attendance_access_control_systems` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key0019` FOREIGN KEY (`holiday_bonus_id`) REFERENCES `attendance_bonus_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key0020` FOREIGN KEY (`restday_bonus_id`) REFERENCES `attendance_bonus_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key0021` FOREIGN KEY (`sunday_bonus_id`) REFERENCES `attendance_bonus_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key0022` FOREIGN KEY (`overtime_bonus_id`) REFERENCES `attendance_bonus_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key0023` FOREIGN KEY (`stand_by_bonus_id`) REFERENCES `attendance_bonus_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key0024` FOREIGN KEY (`duty_bonus_id`) REFERENCES `attendance_bonus_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key1005` FOREIGN KEY (`night_shift_bonus_id`) REFERENCES `attendance_bonus_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key1006` FOREIGN KEY (`afternoon_shift_bonus_id`) REFERENCES `attendance_bonus_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key1007` FOREIGN KEY (`overtime_base_bonus_id`) REFERENCES `attendance_bonus_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key20044` FOREIGN KEY (`travel_time_workday_bonus_type_id`) REFERENCES `attendance_bonus_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key20046` FOREIGN KEY (`travel_time_restday_bonus_type_id`) REFERENCES `attendance_bonus_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key2060` FOREIGN KEY (`attendance_access_control_systems_rule_id`) REFERENCES `attendance_access_control_systems_rules` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `key7013` FOREIGN KEY (`overtime_holiday_bonus_id`) REFERENCES `attendance_bonus_types` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `key7014` FOREIGN KEY (`overtime_shift_bonus_bonus_id`) REFERENCES `attendance_bonus_types` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `key7015` FOREIGN KEY (`overtime_shift_bonus_afternoon_bonus_id`) REFERENCES `attendance_bonus_types` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `key7016` FOREIGN KEY (`overtime_shift_bonus_night_bonus_id`) REFERENCES `attendance_bonus_types` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Megkötések a táblához `attendance_workplans_additional_bonuses`
--
ALTER TABLE `attendance_workplans_additional_bonuses`
  ADD CONSTRAINT `key0030` FOREIGN KEY (`attendance_workplans_id`) REFERENCES `attendance_workplans` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key0031` FOREIGN KEY (`attendance_bonus_types_id`) REFERENCES `attendance_bonus_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `attendance_workplans_core_legal_entities_rel`
--
ALTER TABLE `attendance_workplans_core_legal_entities_rel`
  ADD CONSTRAINT `key0025` FOREIGN KEY (`attendance_workplans_id`) REFERENCES `attendance_workplans` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key0026` FOREIGN KEY (`core_legal_entities_id`) REFERENCES `core_legal_entities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `attendance_workplans_full_day_bonuses`
--
ALTER TABLE `attendance_workplans_full_day_bonuses`
  ADD CONSTRAINT `key2009` FOREIGN KEY (`attendance_workplans_id`) REFERENCES `attendance_workplans` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key2010` FOREIGN KEY (`attendance_bonus_types_id`) REFERENCES `attendance_bonus_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `attendance_workplan_shifts`
--
ALTER TABLE `attendance_workplan_shifts`
  ADD CONSTRAINT `key0027` FOREIGN KEY (`attendance_workplans_id`) REFERENCES `attendance_workplans` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key0028` FOREIGN KEY (`attendance_day_types_id`) REFERENCES `attendance_day_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key0029` FOREIGN KEY (`attendance_shift_types_id`) REFERENCES `attendance_shift_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `attendance_worktimelimits`
--
ALTER TABLE `attendance_worktimelimits`
  ADD CONSTRAINT `key2001` FOREIGN KEY (`core_firms_id`) REFERENCES `core_firms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `attendance_worktimelimit_abscences`
--
ALTER TABLE `attendance_worktimelimit_abscences`
  ADD CONSTRAINT `key2041` FOREIGN KEY (`attendance_worktimelimits_id`) REFERENCES `attendance_worktimelimits` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key2042` FOREIGN KEY (`abscence_types_id`) REFERENCES `attendance_abscence_types_core_firms_rel` (`attendance_abscence_types_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `attendance_worktimelimit_abscence_bonus`
--
ALTER TABLE `attendance_worktimelimit_abscence_bonus`
  ADD CONSTRAINT `key20236` FOREIGN KEY (`attendance_worktimelimits_id`) REFERENCES `attendance_worktimelimits` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key20237` FOREIGN KEY (`attendance_bonus_types_id`) REFERENCES `attendance_bonus_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `attendance_worktimelimit_assignments`
--
ALTER TABLE `attendance_worktimelimit_assignments`
  ADD CONSTRAINT `key20038` FOREIGN KEY (`attendance_worktimelimits_id`) REFERENCES `attendance_worktimelimits` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key20039` FOREIGN KEY (`core_legal_entities_id`) REFERENCES `core_legal_entities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `attendance_worktimelimit_deficit`
--
ALTER TABLE `attendance_worktimelimit_deficit`
  ADD CONSTRAINT `key20034` FOREIGN KEY (`attendance_worktimelimits_id`) REFERENCES `attendance_worktimelimits` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key20035` FOREIGN KEY (`attendance_bonus_types_id`) REFERENCES `attendance_bonus_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `attendance_worktimelimit_excess`
--
ALTER TABLE `attendance_worktimelimit_excess`
  ADD CONSTRAINT `key20036` FOREIGN KEY (`attendance_worktimelimits_id`) REFERENCES `attendance_worktimelimits` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key20037` FOREIGN KEY (`attendance_bonus_types_id`) REFERENCES `attendance_bonus_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `attendance_worktimelimit_rest_time_difference_bonus`
--
ALTER TABLE `attendance_worktimelimit_rest_time_difference_bonus`
  ADD CONSTRAINT `key21034` FOREIGN KEY (`attendance_worktimelimits_id`) REFERENCES `attendance_worktimelimits` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key21035` FOREIGN KEY (`attendance_bonus_types_id`) REFERENCES `attendance_bonus_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `core_actions`
--
ALTER TABLE `core_actions`
  ADD CONSTRAINT `key011` FOREIGN KEY (`core_menu_items_id`) REFERENCES `core_menu_items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key012` FOREIGN KEY (`core_modules_id`) REFERENCES `core_modules` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `core_activities`
--
ALTER TABLE `core_activities`
  ADD CONSTRAINT `key1043` FOREIGN KEY (`core_firms_id`) REFERENCES `core_firms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `core_allow_cron_job`
--
ALTER TABLE `core_allow_cron_job`
  ADD CONSTRAINT `key2037` FOREIGN KEY (`core_firms_id`) REFERENCES `core_firms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `core_cities`
--
ALTER TABLE `core_cities`
  ADD CONSTRAINT `key009` FOREIGN KEY (`core_counties_id`) REFERENCES `core_counties` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `core_cost_centers`
--
ALTER TABLE `core_cost_centers`
  ADD CONSTRAINT `key0007` FOREIGN KEY (`core_firms_id`) REFERENCES `core_firms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `core_custom_reports`
--
ALTER TABLE `core_custom_reports`
  ADD CONSTRAINT `key1015` FOREIGN KEY (`core_firms_id`) REFERENCES `core_firms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key1016` FOREIGN KEY (`core_legal_entities_id`) REFERENCES `core_legal_entities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `core_departments`
--
ALTER TABLE `core_departments`
  ADD CONSTRAINT `key0006` FOREIGN KEY (`core_firms_id`) REFERENCES `core_firms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `core_email_identification`
--
ALTER TABLE `core_email_identification`
  ADD CONSTRAINT `key6002` FOREIGN KEY (`core_person_id`) REFERENCES `core_persons` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `core_email_notifications`
--
ALTER TABLE `core_email_notifications`
  ADD CONSTRAINT `key0011` FOREIGN KEY (`core_email_notification_types_id`) REFERENCES `core_email_notification_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key0012` FOREIGN KEY (`core_localization_id`) REFERENCES `core_localization` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key1017` FOREIGN KEY (`core_legal_entities_id`) REFERENCES `core_legal_entities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key5007` FOREIGN KEY (`source_core_legal_entities_id`) REFERENCES `core_legal_entities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `core_email_notification_subjects`
--
ALTER TABLE `core_email_notification_subjects`
  ADD CONSTRAINT `key5001` FOREIGN KEY (`core_firms_id`) REFERENCES `core_firms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key5003` FOREIGN KEY (`localization_id`) REFERENCES `core_localization` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `core_email_notification_types`
--
ALTER TABLE `core_email_notification_types`
  ADD CONSTRAINT `key5000` FOREIGN KEY (`core_firms_id`) REFERENCES `core_firms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `core_establishments`
--
ALTER TABLE `core_establishments`
  ADD CONSTRAINT `key0005` FOREIGN KEY (`core_firms_id`) REFERENCES `core_firms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `core_extended_hierarchy`
--
ALTER TABLE `core_extended_hierarchy`
  ADD CONSTRAINT `key3003` FOREIGN KEY (`parent_core_legal_entities_id`) REFERENCES `core_legal_entities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key3004` FOREIGN KEY (`child_core_legal_entities_id`) REFERENCES `core_legal_entities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key3005` FOREIGN KEY (`core_firms_id`) REFERENCES `core_firms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `core_extra_email_addresses`
--
ALTER TABLE `core_extra_email_addresses`
  ADD CONSTRAINT `key5004` FOREIGN KEY (`core_legal_entities_id`) REFERENCES `core_legal_entities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key5005` FOREIGN KEY (`core_email_notifications_types_id`) REFERENCES `core_email_notification_types` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `key5006` FOREIGN KEY (`core_localization_id`) REFERENCES `core_localization` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `core_extra_permission_core_modules_core_firms_rel`
--
ALTER TABLE `core_extra_permission_core_modules_core_firms_rel`
  ADD CONSTRAINT `key1343` FOREIGN KEY (`core_firms_id`) REFERENCES `core_firms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key1344` FOREIGN KEY (`core_modules_id`) REFERENCES `core_modules` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `core_firms`
--
ALTER TABLE `core_firms`
  ADD CONSTRAINT `key016` FOREIGN KEY (`core_cities_id`) REFERENCES `core_cities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key017` FOREIGN KEY (`core_public_places_id`) REFERENCES `core_public_places` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `core_firms_core_components_rel`
--
ALTER TABLE `core_firms_core_components_rel`
  ADD CONSTRAINT `key018` FOREIGN KEY (`core_firms_id`) REFERENCES `core_firms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key019` FOREIGN KEY (`core_components_id`) REFERENCES `core_components` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `core_general_ledger_analytics`
--
ALTER TABLE `core_general_ledger_analytics`
  ADD CONSTRAINT `key1035` FOREIGN KEY (`core_firms_id`) REFERENCES `core_firms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `core_general_ledger_analytics_value`
--
ALTER TABLE `core_general_ledger_analytics_value`
  ADD CONSTRAINT `key2012` FOREIGN KEY (`core_firms_id`) REFERENCES `core_firms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key2013` FOREIGN KEY (`core_general_ledger_analytics_id`) REFERENCES `core_general_ledger_analytics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `core_hierarchy`
--
ALTER TABLE `core_hierarchy`
  ADD CONSTRAINT `key0002` FOREIGN KEY (`parent_core_legal_entities_id`) REFERENCES `core_legal_entities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key0003` FOREIGN KEY (`child_core_legal_entities_id`) REFERENCES `core_legal_entities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key0004` FOREIGN KEY (`core_firms_id`) REFERENCES `core_firms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `core_holidays`
--
ALTER TABLE `core_holidays`
  ADD CONSTRAINT `key1013` FOREIGN KEY (`core_firms_id`) REFERENCES `core_firms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `core_legal_entities_attributes_labels`
--
ALTER TABLE `core_legal_entities_attributes_labels`
  ADD CONSTRAINT `key034` FOREIGN KEY (`core_legal_entities_attributes_id`) REFERENCES `core_legal_entities_attributes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key035` FOREIGN KEY (`core_localization_id`) REFERENCES `core_localization` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `core_legal_entities_attributes_values`
--
ALTER TABLE `core_legal_entities_attributes_values`
  ADD CONSTRAINT `key032` FOREIGN KEY (`core_legal_entities_id`) REFERENCES `core_legal_entities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key033` FOREIGN KEY (`core_legal_entities_attributes_id`) REFERENCES `core_legal_entities_attributes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `core_legal_entities_core_activities_assignments`
--
ALTER TABLE `core_legal_entities_core_activities_assignments`
  ADD CONSTRAINT `key1041` FOREIGN KEY (`core_legal_entities_id`) REFERENCES `core_legal_entities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key1042` FOREIGN KEY (`core_activity_id`) REFERENCES `core_activities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `core_legal_entities_cost_center_assignments`
--
ALTER TABLE `core_legal_entities_cost_center_assignments`
  ADD CONSTRAINT `key4014` FOREIGN KEY (`core_legal_entities_id`) REFERENCES `core_legal_entities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key4015` FOREIGN KEY (`core_cost_centers_id`) REFERENCES `core_cost_centers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `core_legal_entities_department_assignments`
--
ALTER TABLE `core_legal_entities_department_assignments`
  ADD CONSTRAINT `key4010` FOREIGN KEY (`core_legal_entities_id`) REFERENCES `core_legal_entities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key4011` FOREIGN KEY (`core_departments_id`) REFERENCES `core_departments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `core_legal_entities_establishment_assignments`
--
ALTER TABLE `core_legal_entities_establishment_assignments`
  ADD CONSTRAINT `key4012` FOREIGN KEY (`core_legal_entities_id`) REFERENCES `core_legal_entities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key4013` FOREIGN KEY (`core_establishments_id`) REFERENCES `core_establishments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `core_legal_entities_general_ledger_analytic_value_assignments`
--
ALTER TABLE `core_legal_entities_general_ledger_analytic_value_assignments`
  ADD CONSTRAINT `key1025` FOREIGN KEY (`core_legal_entities_id`) REFERENCES `core_legal_entities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key2026` FOREIGN KEY (`core_general_ledger_analytics_id`) REFERENCES `core_general_ledger_analytics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key2027` FOREIGN KEY (`core_general_ledger_analytics_value_id`) REFERENCES `core_general_ledger_analytics_value` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `core_legal_entities_profit_center_assignments`
--
ALTER TABLE `core_legal_entities_profit_center_assignments`
  ADD CONSTRAINT `key20042` FOREIGN KEY (`core_legal_entities_id`) REFERENCES `core_legal_entities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key20043` FOREIGN KEY (`core_profit_centers_id`) REFERENCES `core_profit_centers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `core_logs`
--
ALTER TABLE `core_logs`
  ADD CONSTRAINT `key001` FOREIGN KEY (`core_legal_entities_id`) REFERENCES `core_legal_entities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key002` FOREIGN KEY (`substitution_core_persons_id`) REFERENCES `core_persons` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key003` FOREIGN KEY (`core_log_messages_id`) REFERENCES `core_log_messages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key004` FOREIGN KEY (`core_components_id`) REFERENCES `core_components` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key005` FOREIGN KEY (`core_modules_id`) REFERENCES `core_modules` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key006` FOREIGN KEY (`core_actions_id`) REFERENCES `core_actions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key1014` FOREIGN KEY (`core_firms_id`) REFERENCES `core_firms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `core_menu_items`
--
ALTER TABLE `core_menu_items`
  ADD CONSTRAINT `key013` FOREIGN KEY (`core_modules_id`) REFERENCES `core_modules` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key40001` FOREIGN KEY (`core_parameters_id`) REFERENCES `core_parameters` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `core_modules`
--
ALTER TABLE `core_modules`
  ADD CONSTRAINT `key010` FOREIGN KEY (`core_components_id`) REFERENCES `core_components` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `core_other_incomes`
--
ALTER TABLE `core_other_incomes`
  ADD CONSTRAINT `key2007` FOREIGN KEY (`core_firms_id`) REFERENCES `core_firms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `core_parameters`
--
ALTER TABLE `core_parameters`
  ADD CONSTRAINT `key022` FOREIGN KEY (`core_components_id`) REFERENCES `core_components` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key023` FOREIGN KEY (`core_modules_id`) REFERENCES `core_modules` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `core_parameters_core_firms_status`
--
ALTER TABLE `core_parameters_core_firms_status`
  ADD CONSTRAINT `key1345` FOREIGN KEY (`core_parameters_id`) REFERENCES `core_parameters` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key1346` FOREIGN KEY (`core_firms_id`) REFERENCES `core_firms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `core_parameters_core_firms_values`
--
ALTER TABLE `core_parameters_core_firms_values`
  ADD CONSTRAINT `key024` FOREIGN KEY (`core_parameters_id`) REFERENCES `core_parameters` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key025` FOREIGN KEY (`core_firms_id`) REFERENCES `core_firms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `core_parameters_core_parameter_values_rel`
--
ALTER TABLE `core_parameters_core_parameter_values_rel`
  ADD CONSTRAINT `key026` FOREIGN KEY (`core_parameters_id`) REFERENCES `core_parameters` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key027` FOREIGN KEY (`core_parameter_values_id`) REFERENCES `core_parameter_values` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `core_passwords_logs`
--
ALTER TABLE `core_passwords_logs`
  ADD CONSTRAINT `key40008` FOREIGN KEY (`core_persons_id`) REFERENCES `core_persons` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `core_permission_groups`
--
ALTER TABLE `core_permission_groups`
  ADD CONSTRAINT `key0001` FOREIGN KEY (`core_firms_id`) REFERENCES `core_firms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `core_permission_groups_action_permissions_rel`
--
ALTER TABLE `core_permission_groups_action_permissions_rel`
  ADD CONSTRAINT `key007` FOREIGN KEY (`core_permission_groups_id`) REFERENCES `core_permission_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key008` FOREIGN KEY (`core_actions_id`) REFERENCES `core_actions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `core_permission_groups_core_legal_entities_rel`
--
ALTER TABLE `core_permission_groups_core_legal_entities_rel`
  ADD CONSTRAINT `key014` FOREIGN KEY (`core_permission_groups_id`) REFERENCES `core_permission_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key015` FOREIGN KEY (`core_legal_entities_id`) REFERENCES `core_legal_entities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `core_persons_attributes_labels`
--
ALTER TABLE `core_persons_attributes_labels`
  ADD CONSTRAINT `key030` FOREIGN KEY (`core_persons_attributes_id`) REFERENCES `core_persons_attributes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key031` FOREIGN KEY (`core_localization_id`) REFERENCES `core_localization` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `core_persons_attributes_values`
--
ALTER TABLE `core_persons_attributes_values`
  ADD CONSTRAINT `key028` FOREIGN KEY (`core_persons_id`) REFERENCES `core_persons` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key029` FOREIGN KEY (`core_persons_attributes_id`) REFERENCES `core_persons_attributes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `core_person_detection_environments`
--
ALTER TABLE `core_person_detection_environments`
  ADD CONSTRAINT `key6001` FOREIGN KEY (`core_person_id`) REFERENCES `core_persons` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `core_profit_centers`
--
ALTER TABLE `core_profit_centers`
  ADD CONSTRAINT `key7004` FOREIGN KEY (`core_firms_id`) REFERENCES `core_firms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key7005` FOREIGN KEY (`establishment_id`) REFERENCES `core_establishments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `core_reports`
--
ALTER TABLE `core_reports`
  ADD CONSTRAINT `key4025` FOREIGN KEY (`core_legal_entities_id`) REFERENCES `core_legal_entities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key4026` FOREIGN KEY (`core_firms_id`) REFERENCES `core_firms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key4027` FOREIGN KEY (`core_report_classes_id`) REFERENCES `core_report_classes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `core_report_classes`
--
ALTER TABLE `core_report_classes`
  ADD CONSTRAINT `key4024` FOREIGN KEY (`core_report_categories_id`) REFERENCES `core_report_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `core_saved_legal_entity_filters`
--
ALTER TABLE `core_saved_legal_entity_filters`
  ADD CONSTRAINT `key1018` FOREIGN KEY (`core_legal_entities_id`) REFERENCES `core_legal_entities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `core_session_container`
--
ALTER TABLE `core_session_container`
  ADD CONSTRAINT `key1019` FOREIGN KEY (`core_legal_entities_id`) REFERENCES `core_legal_entities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `core_special_permissions`
--
ALTER TABLE `core_special_permissions`
  ADD CONSTRAINT `key1000` FOREIGN KEY (`core_firms_id`) REFERENCES `core_firms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key1001` FOREIGN KEY (`source_permission_group_id`) REFERENCES `core_permission_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key1002` FOREIGN KEY (`target_permission_group_id`) REFERENCES `core_permission_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `core_special_permissions_core_actions_rel`
--
ALTER TABLE `core_special_permissions_core_actions_rel`
  ADD CONSTRAINT `key1003` FOREIGN KEY (`core_special_permissions_id`) REFERENCES `core_special_permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key1004` FOREIGN KEY (`core_actions_id`) REFERENCES `core_actions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `core_special_person_statuses`
--
ALTER TABLE `core_special_person_statuses`
  ADD CONSTRAINT `key40009` FOREIGN KEY (`core_legal_entities_id`) REFERENCES `core_legal_entities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key40010` FOREIGN KEY (`core_special_person_status_types_id`) REFERENCES `core_special_person_status_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `core_substitutions`
--
ALTER TABLE `core_substitutions`
  ADD CONSTRAINT `key020` FOREIGN KEY (`core_legal_entities_id`) REFERENCES `core_legal_entities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key021` FOREIGN KEY (`core_persons_id`) REFERENCES `core_persons` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `core_surface_notifications`
--
ALTER TABLE `core_surface_notifications`
  ADD CONSTRAINT `key4210` FOREIGN KEY (`target_legal_entities_id`) REFERENCES `core_legal_entities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key4211` FOREIGN KEY (`source_legal_entities_id`) REFERENCES `core_legal_entities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `core_temporary_hierarchy`
--
ALTER TABLE `core_temporary_hierarchy`
  ADD CONSTRAINT `key0008` FOREIGN KEY (`parent_core_legal_entities_id`) REFERENCES `core_legal_entities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key0009` FOREIGN KEY (`child_core_legal_entities_id`) REFERENCES `core_legal_entities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key0010` FOREIGN KEY (`core_firms_id`) REFERENCES `core_firms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `core_unique_reports`
--
ALTER TABLE `core_unique_reports`
  ADD CONSTRAINT `key4030` FOREIGN KEY (`core_legal_entities_id`) REFERENCES `core_legal_entities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key4031` FOREIGN KEY (`core_firms_id`) REFERENCES `core_firms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `sync_token_actions_rel`
--
ALTER TABLE `sync_token_actions_rel`
  ADD CONSTRAINT `9059` FOREIGN KEY (`sync_api_token_id`) REFERENCES `sync_api_token` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

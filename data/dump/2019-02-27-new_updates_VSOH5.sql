--
-- New columns in `entity`
--

ALTER TABLE `entity`
	CHANGE `is_allowed` `is_member` tinyint(1) DEFAULT '0',
 	ADD COLUMN `ch_code_dara` varchar(45) COLLATE utf8_bin NOT NULL AFTER `ch_code`,
 	ADD COLUMN `cadc` varchar(45) COLLATE utf8_bin NOT NULL AFTER `ch_code_dara`,
	ADD COLUMN `district` int(4) DEFAULT NULL AFTER `use_all_paymentservices`,
	ADD COLUMN `opel_contract_pw` varchar(45) DEFAULT NULL AFTER `district`,
	ADD COLUMN `opel_contract_nf` varchar(45) DEFAULT NULL AFTER `opel_contract_pw`,
	ADD COLUMN `opel_contract_type` varchar(45) DEFAULT NULL AFTER `opel_contract_nf`,
	ADD COLUMN `chevrolet_contract` varchar(45) DEFAULT NULL AFTER `opel_contract_type`,
	ADD COLUMN `us_contract` varchar(45) DEFAULT NULL AFTER `chevrolet_contract`,
	ADD COLUMN `chevrolet_sp_fr` tinyint(1) DEFAULT '0' AFTER `us_contract`,
	ADD COLUMN `chevrolet_sp_de` tinyint(1) DEFAULT '0' AFTER `chevrolet_sp_fr`,
	ADD COLUMN `gm_us_dealer` tinyint(1) DEFAULT '0' AFTER `chevrolet_sp_de`,
	ADD COLUMN `gm_us_sp` tinyint(1) DEFAULT '0' AFTER `gm_us_dealer`,
	ADD COLUMN `gl` varchar(45) DEFAULT NULL AFTER `gm_us_sp`,
	ADD COLUMN `sales_2017` bigint(20) DEFAULT NULL AFTER `gl`,
	ADD COLUMN `annual_contribution_status` bigint(20) DEFAULT NULL AFTER `sales_2017`,
	ADD COLUMN `annual_contribution_volume` bigint(20) DEFAULT NULL AFTER `annual_contribution_status`,
	ADD COLUMN `annual_contribution_sport` bigint(20) DEFAULT NULL AFTER `annual_contribution_volume`,
	ADD COLUMN `annual_contribution_opel` bigint(20) DEFAULT NULL AFTER `annual_contribution_sport`,
	ADD COLUMN `annual_contribution_chevrolet` bigint(20) DEFAULT NULL AFTER `annual_contribution_opel`,
	ADD COLUMN `annual_contribution_us` bigint(20) DEFAULT NULL AFTER `annual_contribution_chevrolet`,
	ADD COLUMN `annual_contribution_total` bigint(20) DEFAULT NULL AFTER `annual_contribution_us`
;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `brand`
--

CREATE TABLE `brand` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(120) COLLATE utf8_bin NOT NULL,
  `name` varchar(120) COLLATE utf8_bin NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Tabellenstruktur für Tabelle `brand_translation`
--

CREATE TABLE `brand_translation` (
  `id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `title` varchar(120) COLLATE utf8_bin DEFAULT NULL,
  `lang` char(2) COLLATE utf8_bin NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Indizes für die Tabelle `brand`
--
ALTER TABLE `brand`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`);

--
-- Indizes für die Tabelle `brand_translation`
--
ALTER TABLE `brand_translation`
  ADD PRIMARY KEY (`id`,`lang`);

--
-- AUTO_INCREMENT für Tabelle `brand`
--
ALTER TABLE `brand`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints der Tabelle `brand_translation`
--
ALTER TABLE `brand_translation`
  ADD CONSTRAINT `brand_translation_id_brand_id` FOREIGN KEY (`id`) REFERENCES `brand` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- indsert data for `brand`
--
INSERT INTO
`brand` (`short`, `name`, `created_at`, `updated_at`, `deleted_at`)
VALUES
('opel', 'Opel', '2019-02-26 06:50:53', '2019-02-26 06:50:53', NULL),
('chevrolet', 'Chevrolet', '2019-02-26 06:50:53', '2019-02-26 06:50:53', NULL),
('us', 'US', '2019-02-26 06:50:53', '2019-02-26 06:50:53', NULL);

--
-- indsert data for `brand_translation`
--
INSERT INTO
`brand_translation` (`id`, `title`, `lang`, `created_at`, `updated_at`, `deleted_at`)
VALUES
(1,'Opel','en','2019-02-26 00:00:00','2019-02-26 00:00:00',NULL),
(1,'Opel','de','2019-02-26 00:00:00','2019-02-26 00:00:00',NULL),
(2,'Chevrolet','en','2019-02-26 00:00:00','2019-02-26 00:00:00',NULL),
(2,'Chevrolet','de','2019-02-26 00:00:00','2019-02-26 00:00:00',NULL),
(3,'US','en','2019-02-26 00:00:00','2019-02-26 00:00:00',NULL),
(3,'US','de','2019-02-26 00:00:00','2019-02-26 00:00:00',NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `entity_m2n_brand`
--

CREATE TABLE `entity_m2n_brand` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `entity_id` bigint(20) UNSIGNED NOT NULL,
  `brand_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Indizes für die Tabelle `entity_m2n_brand`
--
ALTER TABLE `entity_m2n_brand`
  ADD PRIMARY KEY (`id`),
  ADD KEY `entity_id_idx_idx` (`entity_id`),
  ADD KEY `brand_id_idx_idx` (`brand_id`);

--
-- AUTO_INCREMENT für Tabelle `entity_m2n_brand`
--
ALTER TABLE `entity_m2n_brand`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints der Tabelle `entity_m2n_brand`
--
ALTER TABLE `entity_m2n_brand`
  ADD CONSTRAINT `entity_m2n_brand_entity_id_entity_id` FOREIGN KEY (`entity_id`) REFERENCES `entity` (`id`),
  ADD CONSTRAINT `entity_m2n_brand_brand_id_brand_id` FOREIGN KEY (`brand_id`) REFERENCES `brand` (`id`);

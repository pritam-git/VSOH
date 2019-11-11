--
-- Tabellenstruktur für Tabelle `assembly`
--

CREATE TABLE `assembly` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(120) COLLATE utf8_bin NOT NULL,
  `publish_datetime` datetime DEFAULT NULL,
  `en_media_id` bigint(20) UNSIGNED DEFAULT NULL,
  `fr_media_id` bigint(20) UNSIGNED DEFAULT NULL,
  `de_media_id` bigint(20) UNSIGNED DEFAULT NULL,
  `it_media_id` bigint(20) UNSIGNED DEFAULT NULL,
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

ALTER TABLE `dates` ADD `closed_registration_date` DATE NOT NULL AFTER `publish_datetime`;
ALTER TABLE `dates` CHANGE `published` `published` TINYINT(1) NULL DEFAULT '0';

ALTER TABLE `entity` ADD `ch_code` varchar(45) NOT NULL AFTER `id`;
ALTER TABLE `entity` CHANGE `parent_user_id` `parent_user_id` BIGINT(20) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `entity` CHANGE `department_id` `department_id` bigint(20) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `entity` ADD `title` varchar(80) DEFAULT NULL AFTER `lastname`;
ALTER TABLE `entity` ADD `kanton_id` bigint(20) UNSIGNED DEFAULT NULL AFTER `city`;
ALTER TABLE `entity` ADD `contract_type_id` bigint(20) UNSIGNED DEFAULT NULL AFTER `country_id`;
ALTER TABLE `entity` ADD `sales_statistics_id` bigint(20) UNSIGNED DEFAULT NULL AFTER `contract_type_id`;
ALTER TABLE `entity` ADD `manager_phone` varchar(80) DEFAULT NULL AFTER `www`;


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

ALTER TABLE `news` CHANGE `published` `published` TINYINT(1) NULL DEFAULT '0';

--
-- Tabellenstruktur für Tabelle `otc`
--

CREATE TABLE `otc` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(120) COLLATE utf8_bin NOT NULL,
  `publish_datetime` datetime DEFAULT NULL,
  `en_media_id` bigint(20) UNSIGNED DEFAULT NULL,
  `fr_media_id` bigint(20) UNSIGNED DEFAULT NULL,
  `de_media_id` bigint(20) UNSIGNED DEFAULT NULL,
  `it_media_id` bigint(20) UNSIGNED DEFAULT NULL,
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

ALTER TABLE `protocol` CHANGE `published` `published` TINYINT(1) NULL DEFAULT '0';

ALTER TABLE `sales_statistics_protocol` CHANGE `published` `published` TINYINT(1) NULL DEFAULT '0';


--
-- Indizes für die Tabelle `assembly`
--
ALTER TABLE `assembly`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`),
  ADD KEY `it_media_id_idx_idx` (`it_media_id`),
  ADD KEY `de_media_id_idx_idx` (`de_media_id`),
  ADD KEY `fr_media_id_idx_idx` (`fr_media_id`),
  ADD KEY `en_media_id_idx_idx` (`en_media_id`);

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

-- --------------------------------------------------------

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

-- --------------------------------------------------------

--
-- Indizes für die Tabelle `entity`
--
ALTER TABLE `entity`
  ADD KEY `kanton_idx` (`kanton_id`),
  ADD KEY `contract_type_id_idx_idx` (`contract_type_id`),
  ADD KEY `sales_statistics_id_idx_idx` (`sales_statistics_id`);

--
-- Indizes für die Tabelle `kanton`
--
ALTER TABLE `kanton`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`),
  ADD KEY `sales_statistics_id_idx_idx` (`sales_statistics_id`);

--
-- Indizes für die Tabelle `otc`
--
ALTER TABLE `otc`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`),
  ADD KEY `it_media_id_idx_idx` (`it_media_id`),
  ADD KEY `de_media_id_idx_idx` (`de_media_id`),
  ADD KEY `fr_media_id_idx_idx` (`fr_media_id`),
  ADD KEY `en_media_id_idx_idx` (`en_media_id`);

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

ALTER TABLE `role`
  ADD KEY `short_idx` (`short`);

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
-- AUTO_INCREMENT für Tabelle `contract_type`
--
ALTER TABLE `contract_type`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `kanton`
--
ALTER TABLE `kanton`
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
-- Constraints der Tabelle `assembly`
--
ALTER TABLE `assembly`
  ADD CONSTRAINT `assembly_de_media_id_media_id` FOREIGN KEY (`de_media_id`) REFERENCES `media` (`id`),
  ADD CONSTRAINT `assembly_en_media_id_media_id` FOREIGN KEY (`en_media_id`) REFERENCES `media` (`id`),
  ADD CONSTRAINT `assembly_fr_media_id_media_id` FOREIGN KEY (`fr_media_id`) REFERENCES `media` (`id`),
  ADD CONSTRAINT `assembly_it_media_id_media_id` FOREIGN KEY (`it_media_id`) REFERENCES `media` (`id`);

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
-- Constraints der Tabelle `contract_type_translation`
--
ALTER TABLE `contract_type_translation`
  ADD CONSTRAINT `contract_type_translation_id_contract_type_id` FOREIGN KEY (`id`) REFERENCES `contract_type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `entity`
--
ALTER TABLE `entity`
  ADD CONSTRAINT `entity_contract_type_id_contract_type_id` FOREIGN KEY (`contract_type_id`) REFERENCES `contract_type` (`id`),
  ADD CONSTRAINT `entity_department_id_department_id` FOREIGN KEY (`department_id`) REFERENCES `department` (`id`),
  ADD CONSTRAINT `entity_kanton_id_kanton_id` FOREIGN KEY (`kanton_id`) REFERENCES `kanton` (`id`),
  ADD CONSTRAINT `entity_parent_user_id_entity_id` FOREIGN KEY (`parent_user_id`) REFERENCES `entity` (`id`),
  ADD CONSTRAINT `entity_sales_statistics_id_sales_statistics_id` FOREIGN KEY (`sales_statistics_id`) REFERENCES `sales_statistics` (`id`);

--
-- Constraints der Tabelle `kanton`
--
ALTER TABLE `kanton`
  ADD CONSTRAINT `kanton_sales_statistics_id_sales_statistics_id` FOREIGN KEY (`sales_statistics_id`) REFERENCES `sales_statistics` (`id`);

--
-- Constraints der Tabelle `news_m2n_department`
--
ALTER TABLE `news_m2n_department`
  ADD CONSTRAINT `news_m2n_department_department_id_department_id` FOREIGN KEY (`department_id`) REFERENCES `department` (`id`),
  ADD CONSTRAINT `news_m2n_department_news_id_news_id` FOREIGN KEY (`news_id`) REFERENCES `news` (`id`);

--
-- Constraints der Tabelle `otc`
--
ALTER TABLE `otc`
  ADD CONSTRAINT `otc_de_media_id_media_id` FOREIGN KEY (`de_media_id`) REFERENCES `media` (`id`),
  ADD CONSTRAINT `otc_en_media_id_media_id` FOREIGN KEY (`en_media_id`) REFERENCES `media` (`id`),
  ADD CONSTRAINT `otc_fr_media_id_media_id` FOREIGN KEY (`fr_media_id`) REFERENCES `media` (`id`),
  ADD CONSTRAINT `otc_it_media_id_media_id` FOREIGN KEY (`it_media_id`) REFERENCES `media` (`id`);

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
-- Constraints der Tabelle `protocol_m2n_department`
--
ALTER TABLE `protocol_m2n_department`
  ADD CONSTRAINT `protocol_m2n_department_department_id_department_id` FOREIGN KEY (`department_id`) REFERENCES `department` (`id`),
  ADD CONSTRAINT `protocol_m2n_department_protocol_id_protocol_id` FOREIGN KEY (`protocol_id`) REFERENCES `protocol` (`id`);

--
-- Constraints der Tabelle `sales_statistics_protocol_m2n_department`
--
ALTER TABLE `sales_statistics_protocol_m2n_department`
  ADD CONSTRAINT `sddi` FOREIGN KEY (`department_id`) REFERENCES `department` (`id`),
  ADD CONSTRAINT `sssi_1` FOREIGN KEY (`sales_statistics_protocol_id`) REFERENCES `sales_statistics_protocol` (`id`);


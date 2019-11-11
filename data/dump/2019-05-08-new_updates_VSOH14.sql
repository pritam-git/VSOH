--
-- Create table 'assembly_m2n_sales_statistics'
--

CREATE TABLE `assembly_m2n_sales_statistics` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `assembly_id` bigint(20) UNSIGNED NOT NULL,
  `sales_statistics_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Create table 'dates_m2n_sales_statistics'
--

CREATE TABLE `dates_m2n_sales_statistics` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dates_id` bigint(20) UNSIGNED NOT NULL,
  `sales_statistics_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Create table 'otc_m2n_sales_statistics'
--

CREATE TABLE `otc_m2n_sales_statistics` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `otc_id` bigint(20) UNSIGNED NOT NULL,
  `sales_statistics_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Create table 'protocol_m2n_sales_statistics'
--

CREATE TABLE `protocol_m2n_sales_statistics` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `protocol_id` bigint(20) UNSIGNED NOT NULL,
  `sales_statistics_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Create table 'team_members'
--

CREATE TABLE `team_members` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short` varchar(120) COLLATE utf8_bin NOT NULL,
  `name` varchar(120) COLLATE utf8_bin NOT NULL,
  `email` varchar(255) COLLATE utf8_bin NOT NULL,
  `phone` varchar(80) COLLATE utf8_bin DEFAULT NULL,
  `media_image_id` bigint(20) UNSIGNED DEFAULT NULL,
  `position` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Create table 'team_members_translation'
--

CREATE TABLE `team_members_translation` (
  `id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `ushort` varchar(120) COLLATE utf8_bin NOT NULL,
  `title` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `function` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `lang` char(2) COLLATE utf8_bin NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Add keys and indexes to table 'assembly_m2n_sales_statistics'
--

ALTER TABLE `assembly_m2n_sales_statistics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assembly_id_idx` (`assembly_id`),
  ADD KEY `sales_statistics_id_idx` (`sales_statistics_id`);

--
-- Add keys and indexes to table 'dates_m2n_sales_statistics'
--

ALTER TABLE `dates_m2n_sales_statistics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dates_id_idx` (`dates_id`),
  ADD KEY `sales_statistics_id_idx` (`sales_statistics_id`);

--
-- Add keys and indexes to table 'dates_questions'
--

ALTER TABLE `dates_questions`
  DROP KEY `parent_question_id_idx_idx`,
  ADD KEY `question_id_idx` (`parent_question_id`);

--
-- Add keys and indexes to table 'otc_m2n_sales_statistics'
--

ALTER TABLE `otc_m2n_sales_statistics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `otc_id_idx` (`otc_id`),
  ADD KEY `sales_statistics_id_idx` (`sales_statistics_id`);

--
-- Add keys and indexes to table 'protocol_m2n_sales_statistics'
--

ALTER TABLE `protocol_m2n_sales_statistics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `protocol_id_idx` (`protocol_id`),
  ADD KEY `sales_statistics_id_idx` (`sales_statistics_id`);

--
-- Add keys and indexes to table 'team_members'
--

ALTER TABLE `team_members`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`),
  ADD KEY `media_image_id_idx` (`media_image_id`),
  ADD KEY `position_idx` (`position`);

--
-- Add keys and indexes to table 'team_members_translation'
--

ALTER TABLE `team_members_translation`
  ADD PRIMARY KEY (`id`,`lang`);

--
-- Modify id settings for table 'assembly_m2n_sales_statistics'
--

ALTER TABLE `assembly_m2n_sales_statistics`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Modify id settings for table 'dates_m2n_sales_statistics'
--

ALTER TABLE `dates_m2n_sales_statistics`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Modify id settings for table 'otc_m2n_sales_statistics'
--

ALTER TABLE `otc_m2n_sales_statistics`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Modify id settings for table 'protocol_m2n_sales_statistics'
--

ALTER TABLE `protocol_m2n_sales_statistics`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Modify id settings for table 'team_members'
--

ALTER TABLE `team_members`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Add constraints to table 'assembly_m2n_sales_statistics'
--

ALTER TABLE `assembly_m2n_sales_statistics`
  ADD CONSTRAINT `assembly_m2n_sales_statistics_assembly_id_assembly_id` FOREIGN KEY (`assembly_id`) REFERENCES `assembly` (`id`),
  ADD CONSTRAINT `assi` FOREIGN KEY (`sales_statistics_id`) REFERENCES `sales_statistics` (`id`);

--
-- Add constraints to table 'assembly_m2n_sales_statistics'
--

ALTER TABLE `dates_m2n_sales_statistics`
  ADD CONSTRAINT `dates_m2n_sales_statistics_dates_id_dates_id` FOREIGN KEY (`dates_id`) REFERENCES `dates` (`id`),
  ADD CONSTRAINT `dssi` FOREIGN KEY (`sales_statistics_id`) REFERENCES `sales_statistics` (`id`);

--
-- Add constraints to table 'dates_questions'
--

ALTER TABLE `dates_questions`
  DROP FOREIGN KEY `parent_question_id`,
  ADD CONSTRAINT `dates_questions_parent_question_id_dates_questions_id` FOREIGN KEY (`parent_question_id`) REFERENCES `dates_questions` (`id`);

--
-- Add constraints to table 'otc_m2n_sales_statistics'
--

ALTER TABLE `otc_m2n_sales_statistics`
  ADD CONSTRAINT `otc_m2n_sales_statistics_otc_id_otc_id` FOREIGN KEY (`otc_id`) REFERENCES `otc` (`id`),
  ADD CONSTRAINT `otc_m2n_sales_statistics_sales_statistics_id_sales_statistics_id` FOREIGN KEY (`sales_statistics_id`) REFERENCES `sales_statistics` (`id`);

--
-- Add constraints to table 'protocol_m2n_sales_statistics'
--

ALTER TABLE `protocol_m2n_sales_statistics`
  ADD CONSTRAINT `protocol_m2n_sales_statistics_protocol_id_protocol_id` FOREIGN KEY (`protocol_id`) REFERENCES `protocol` (`id`),
  ADD CONSTRAINT `pssi` FOREIGN KEY (`sales_statistics_id`) REFERENCES `sales_statistics` (`id`);

--
-- Add constraints to table 'team_members'
--

ALTER TABLE `team_members`
  ADD CONSTRAINT `team_members_media_image_id_media_id` FOREIGN KEY (`media_image_id`) REFERENCES `media` (`id`);

--
-- Add constraints to table 'team_members_translation'
--

ALTER TABLE `team_members_translation`
  ADD CONSTRAINT `team_members_translation_id_team_members_id` FOREIGN KEY (`id`) REFERENCES `team_members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

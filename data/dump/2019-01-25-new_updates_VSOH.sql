--
-- remove sales statistics model
--
SET FOREIGN_KEY_CHECKS=0;
DROP TABLE `sales_statistics_protocol_translation`;
DROP TABLE `sales_statistics_protocol_m2n_department`;
DROP TABLE `sales_statistics_protocol`;
SET FOREIGN_KEY_CHECKS=1;
--
-- protocol model
--

ALTER TABLE `protocol`
  CHANGE COLUMN `p_date` `publish_datetime` DATETIME NULL;

ALTER TABLE `protocol`
  DROP FOREIGN KEY `protocol_en_media_id_media_id`;
ALTER TABLE `protocol`
  DROP COLUMN `en_media_id`,
  DROP KEY `en_media_id_idx`;

ALTER TABLE `protocol`
  DROP FOREIGN KEY `protocol_it_media_id_media_id`;
ALTER TABLE `protocol`
  DROP COLUMN `it_media_id`,
  DROP KEY `it_media_id_idx`;

ALTER TABLE `protocol`
  ADD COLUMN `fr_presentation_media_id` bigint(20) UNSIGNED NULL DEFAULT NULL AFTER `de_media_id`,
  ADD COLUMN `de_presentation_media_id` bigint(20) UNSIGNED NULL DEFAULT NULL AFTER `fr_presentation_media_id`;

ALTER TABLE `protocol`
  ADD KEY `fr_presentation_media_id_idx` (`fr_presentation_media_id`),
  ADD KEY `de_presentation_media_id_idx` (`de_presentation_media_id`);

ALTER TABLE `protocol`
  ADD CONSTRAINT `protocol_fr_presentation_media_id_media_id` FOREIGN KEY (`fr_presentation_media_id`) REFERENCES `media` (`id`),
  ADD CONSTRAINT `protocol_de_presentation_media_id_media_id` FOREIGN KEY (`de_presentation_media_id`) REFERENCES `media` (`id`);

--
-- news model
--

ALTER TABLE `news`
  DROP FOREIGN KEY `news_en_media_id_media_id`;
ALTER TABLE `news`
  DROP COLUMN `en_media_id`,
  DROP KEY `en_media_id_idx_idx`;

ALTER TABLE `news`
  DROP FOREIGN KEY `news_it_media_id_media_id`;
ALTER TABLE `news`
  DROP COLUMN `it_media_id`,
  DROP KEY `it_media_id_idx_idx`;

ALTER TABLE `news`
  ADD COLUMN `fr_presentation_media_id` bigint(20) UNSIGNED NULL DEFAULT NULL AFTER `de_media_id`,
  ADD COLUMN `de_presentation_media_id` bigint(20) UNSIGNED NULL DEFAULT NULL AFTER `fr_presentation_media_id`;

ALTER TABLE `news`
  ADD KEY `fr_presentation_media_id_idx_idx` (`fr_presentation_media_id`),
  ADD KEY `de_presentation_media_id_idx_idx` (`de_presentation_media_id`);

ALTER TABLE `news`
  ADD CONSTRAINT `news_fr_presentation_media_id_media_id` FOREIGN KEY (`fr_presentation_media_id`) REFERENCES `media` (`id`),
  ADD CONSTRAINT `news_de_presentation_media_id_media_id` FOREIGN KEY (`de_presentation_media_id`) REFERENCES `media` (`id`);

--
-- dates model
--

ALTER TABLE `dates`
  DROP FOREIGN KEY `dates_en_media_id_media_id`;
ALTER TABLE `dates`
  DROP COLUMN `en_media_id`,
  DROP KEY `en_media_id_idx_idx`;

ALTER TABLE `dates`
  DROP FOREIGN KEY `dates_it_media_id_media_id`;
ALTER TABLE `dates`
  DROP COLUMN `it_media_id`,
  DROP KEY `it_media_id_idx_idx`;

ALTER TABLE `dates`
  ADD COLUMN `fr_presentation_media_id` bigint(20) UNSIGNED NULL DEFAULT NULL AFTER `de_media_id`,
  ADD COLUMN `de_presentation_media_id` bigint(20) UNSIGNED NULL DEFAULT NULL AFTER `fr_presentation_media_id`;

ALTER TABLE `dates`
  ADD KEY `fr_presentation_media_id_idx_idx` (`fr_presentation_media_id`),
  ADD KEY `de_presentation_media_id_idx_idx` (`de_presentation_media_id`);

ALTER TABLE `dates`
  ADD CONSTRAINT `dates_fr_presentation_media_id_media_id` FOREIGN KEY (`fr_presentation_media_id`) REFERENCES `media` (`id`),
  ADD CONSTRAINT `dates_de_presentation_media_id_media_id` FOREIGN KEY (`de_presentation_media_id`) REFERENCES `media` (`id`);

--
-- otc model
--

ALTER TABLE `otc`
  DROP FOREIGN KEY `otc_en_media_id_media_id`;
ALTER TABLE `otc`
  DROP COLUMN `en_media_id`,
  DROP KEY `en_media_id_idx_idx`;

ALTER TABLE `otc`
  DROP FOREIGN KEY `otc_it_media_id_media_id`;
ALTER TABLE `otc`
  DROP COLUMN `it_media_id`,
  DROP KEY `it_media_id_idx_idx`;

ALTER TABLE `otc`
  ADD COLUMN `fr_presentation_media_id` bigint(20) UNSIGNED NULL DEFAULT NULL AFTER `de_media_id`,
  ADD COLUMN `de_presentation_media_id` bigint(20) UNSIGNED NULL DEFAULT NULL AFTER `fr_presentation_media_id`;

ALTER TABLE `otc`
  ADD KEY `fr_presentation_media_id_idx_idx` (`fr_presentation_media_id`),
  ADD KEY `de_presentation_media_id_idx_idx` (`de_presentation_media_id`);

ALTER TABLE `otc`
  ADD CONSTRAINT `otc_fr_presentation_media_id_media_id` FOREIGN KEY (`fr_presentation_media_id`) REFERENCES `media` (`id`),
  ADD CONSTRAINT `otc_de_presentation_media_id_media_id` FOREIGN KEY (`de_presentation_media_id`) REFERENCES `media` (`id`);

--
-- assembly model
--

ALTER TABLE `assembly`
  DROP FOREIGN KEY `assembly_en_media_id_media_id`;
ALTER TABLE `assembly`
  DROP COLUMN `en_media_id`,
  DROP KEY `en_media_id_idx_idx`;

ALTER TABLE `assembly`
  DROP FOREIGN KEY `assembly_it_media_id_media_id`;
ALTER TABLE `assembly`
  DROP COLUMN `it_media_id`,
  DROP KEY `it_media_id_idx_idx`;

ALTER TABLE `assembly`
  ADD COLUMN `fr_presentation_media_id` bigint(20) UNSIGNED NULL DEFAULT NULL AFTER `de_media_id`,
  ADD COLUMN `de_presentation_media_id` bigint(20) UNSIGNED NULL DEFAULT NULL AFTER `fr_presentation_media_id`;

ALTER TABLE `assembly`
  ADD KEY `fr_presentation_media_id_idx_idx` (`fr_presentation_media_id`),
  ADD KEY `de_presentation_media_id_idx_idx` (`de_presentation_media_id`);

ALTER TABLE `assembly`
  ADD CONSTRAINT `assembly_fr_presentation_media_id_media_id` FOREIGN KEY (`fr_presentation_media_id`) REFERENCES `media` (`id`),
  ADD CONSTRAINT `assembly_de_presentation_media_id_media_id` FOREIGN KEY (`de_presentation_media_id`) REFERENCES `media` (`id`);

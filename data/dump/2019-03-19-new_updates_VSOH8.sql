--
-- New columns in `dates`
--

ALTER TABLE `dates`
	DROP COLUMN `published`,
	ADD COLUMN `place` varchar(80) COLLATE utf8_bin DEFAULT NULL AFTER `closed_registration_date`,
	ADD COLUMN `datetime` datetime DEFAULT NULL AFTER `place`,
	ADD COLUMN `subject_of_negotiations` varchar(120) COLLATE utf8_bin DEFAULT NULL AFTER `datetime`,
	ADD COLUMN `comment` varchar(255) COLLATE utf8_bin DEFAULT NULL AFTER `subject_of_negotiations`,
	ADD COLUMN `publish_fr` tinyint(1) DEFAULT '0' AFTER `comment`,
	ADD COLUMN `publish_de` tinyint(1) DEFAULT '0' AFTER `publish_fr`;


ALTER TABLE `dates`
  MODIFY COLUMN `subject_of_negotiations` mediumtext COLLATE utf8_bin DEFAULT NULL,
  MODIFY COLUMN `comment` mediumtext COLLATE utf8_bin DEFAULT NULL;
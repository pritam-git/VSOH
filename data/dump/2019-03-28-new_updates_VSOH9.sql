--
-- New columns in `dates`
--

ALTER TABLE `dates`
	DROP COLUMN `datetime`,
	ADD COLUMN `start_datetime` datetime DEFAULT NULL AFTER `place`,
	ADD COLUMN `end_datetime` datetime DEFAULT NULL AFTER `start_datetime`;

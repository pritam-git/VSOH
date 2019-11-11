--
-- New columns in `entity`
--

ALTER TABLE `entity`
  ADD COLUMN `edit_request_data` mediumtext COLLATE utf8_bin DEFAULT NULL AFTER `annual_contribution_total`;

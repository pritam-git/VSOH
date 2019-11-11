--
-- New columns in `entity`
--

ALTER TABLE `entity`
  ADD COLUMN `is_first_login` tinyint(1) DEFAULT '1' AFTER `manager_phone`;

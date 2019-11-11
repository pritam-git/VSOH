--
-- New columns in `brand`
--

ALTER TABLE `brand`
	ADD COLUMN `media_image_id` bigint(20) UNSIGNED DEFAULT NULL AFTER `name`;

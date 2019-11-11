--
-- New columns in `entity`
--

ALTER TABLE `entity`
  ADD COLUMN `super_parent_id` bigint(20) UNSIGNED DEFAULT NULL AFTER `parent_user_id`,
  ADD COLUMN `is_allowed` tinyint(1) DEFAULT '0' AFTER `department_id`;

--
-- new index in `entity`
--
ALTER TABLE `entity`
  ADD KEY `super_parent_id_idx_idx` (`super_parent_id`);

--
-- foreign key for super parent id
--
ALTER TABLE `entity`
  ADD CONSTRAINT `entity_super_parent_id_entity_id` FOREIGN KEY (`super_parent_id`) REFERENCES `entity` (`id`);

--
-- indsert data for `contract_type`
--
INSERT INTO
`contract_type` (`short`, `name`, `created_at`, `updated_at`, `deleted_at`)
VALUES
('dealer', 'Dealer', '2019-01-07 06:50:53', '2019-01-07 06:50:53', NULL),
('agent', 'Agent', '2019-01-07 06:50:53', '2019-01-07 06:50:53', NULL),
('partner', 'Partner', '2019-01-07 06:50:53', '2019-01-07 06:50:53', NULL);



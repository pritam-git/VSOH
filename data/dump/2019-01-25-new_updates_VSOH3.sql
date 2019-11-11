ALTER TABLE `entity`
  ADD KEY `entity_role_id_idx` (`role_id`);

ALTER TABLE `media` DROP KEY `media_media_type_id`

CREATE TABLE `region_dates` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `short` varchar(120) COLLATE utf8_bin NOT NULL,
  `publish_datetime` datetime DEFAULT NULL,
  `closed_registration_date` date NOT NULL,
  `place` varchar(80) COLLATE utf8_bin DEFAULT NULL,
  `start_datetime` datetime DEFAULT NULL,
  `end_datetime` datetime DEFAULT NULL,
  `subject_of_negotiations` mediumtext COLLATE utf8_bin,
  `comment` mediumtext COLLATE utf8_bin,
  `publish_fr` tinyint(1) DEFAULT '0',
  `publish_de` tinyint(1) DEFAULT '0',
  `fr_media_id` bigint(20) unsigned DEFAULT NULL,
  `de_media_id` bigint(20) unsigned DEFAULT NULL,
  `fr_presentation_media_id` bigint(20) unsigned DEFAULT NULL,
  `de_presentation_media_id` bigint(20) unsigned DEFAULT NULL,
  `is_bookable` tinyint(1) DEFAULT '0',
  `sales_statistics_id` bigint(20) unsigned NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `short` (`short`),
  KEY `fr_media_id_idx` (`fr_media_id`),
  KEY `de_media_id_idx` (`de_media_id`),
  KEY `fr_presentation_media_id_idx` (`fr_presentation_media_id`),
  KEY `de_presentation_media_id_idx` (`de_presentation_media_id`),
  KEY `sales_statistics_id0_idx` (`sales_statistics_id`),
  CONSTRAINT `region_dates_de_media_id_media_id` FOREIGN KEY (`de_media_id`) REFERENCES `media` (`id`),
  CONSTRAINT `region_dates_de_presentation_media_id_media_id` FOREIGN KEY (`de_presentation_media_id`) REFERENCES `media` (`id`),
  CONSTRAINT `region_dates_fr_media_id_media_id` FOREIGN KEY (`fr_media_id`) REFERENCES `media` (`id`),
  CONSTRAINT `region_dates_fr_presentation_media_id_media_id` FOREIGN KEY (`fr_presentation_media_id`) REFERENCES `media` (`id`),
  CONSTRAINT `region_dates_sales_statistics_id_sales_statistics_id` FOREIGN KEY (`sales_statistics_id`) REFERENCES `sales_statistics` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


CREATE TABLE `region_dates_m2n_department` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `region_date_id` bigint(20) unsigned NOT NULL,
  `department_id` bigint(20) unsigned NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `region_date_id_idx` (`region_date_id`),
  KEY `department_id_idx` (`department_id`),
  CONSTRAINT `region_dates_m2n_department_department_id_department_id` FOREIGN KEY (`department_id`) REFERENCES `department` (`id`),
  CONSTRAINT `region_dates_m2n_department_region_date_id_region_dates_id` FOREIGN KEY (`region_date_id`) REFERENCES `region_dates` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


CREATE TABLE `region_dates_m2n_questions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `region_date_id` bigint(20) unsigned NOT NULL,
  `question_id` bigint(20) unsigned NOT NULL,
  `position` bigint(20) unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `region_date_id_idx` (`region_date_id`),
  KEY `question_id1_idx` (`question_id`),
  CONSTRAINT `region_dates_m2n_questions_question_id_dates_questions_id` FOREIGN KEY (`question_id`) REFERENCES `dates_questions` (`id`),
  CONSTRAINT `region_dates_m2n_questions_region_date_id_region_dates_id` FOREIGN KEY (`region_date_id`) REFERENCES `region_dates` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


CREATE TABLE `region_dates_participants` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `participant_name` varchar(120) COLLATE utf8_bin NOT NULL,
  `region_date_id` bigint(20) unsigned NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id_idx` (`user_id`),
  KEY `region_date_id_idx` (`region_date_id`),
  CONSTRAINT `region_dates_participants_region_date_id_region_dates_id` FOREIGN KEY (`region_date_id`) REFERENCES `region_dates` (`id`),
  CONSTRAINT `region_dates_participants_user_id_entity_id` FOREIGN KEY (`user_id`) REFERENCES `entity` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


CREATE TABLE `region_dates_participants_answers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `region_date_id` bigint(20) unsigned NOT NULL,
  `question_id` bigint(20) unsigned NOT NULL,
  `answer` varchar(120) COLLATE utf8_bin NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id_idx` (`user_id`),
  KEY `region_date_id_idx` (`region_date_id`),
  KEY `question_id0_idx` (`question_id`),
  CONSTRAINT `region_dates_participants_answers_question_id_dates_questions_id` FOREIGN KEY (`question_id`) REFERENCES `dates_questions` (`id`),
  CONSTRAINT `region_dates_participants_answers_region_date_id_region_dates_id` FOREIGN KEY (`region_date_id`) REFERENCES `region_dates` (`id`),
  CONSTRAINT `region_dates_participants_answers_user_id_entity_id` FOREIGN KEY (`user_id`) REFERENCES `entity` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


CREATE TABLE `region_dates_translation` (
  `id` bigint(20) unsigned NOT NULL,
  `ushort` varchar(120) COLLATE utf8_bin NOT NULL,
  `uname` varchar(120) COLLATE utf8_bin NOT NULL,
  `title` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `description` mediumtext COLLATE utf8_bin,
  `content` mediumtext COLLATE utf8_bin,
  `lang` char(2) COLLATE utf8_bin NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`,`lang`),
  CONSTRAINT `region_dates_translation_id_region_dates_id` FOREIGN KEY (`id`) REFERENCES `region_dates` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

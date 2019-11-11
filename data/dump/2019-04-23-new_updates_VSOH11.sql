CREATE TABLE `dates_questions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `short` varchar(120) COLLATE utf8_bin NOT NULL,
  `name` varchar(120) COLLATE utf8_bin NOT NULL,
  `is_checkbox` tinyint(1) DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `short` (`short`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `dates_questions_translation` (
  `id` bigint(20) unsigned NOT NULL,
  `title` varchar(120) COLLATE utf8_bin DEFAULT NULL,
  `lang` char(2) COLLATE utf8_bin NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`,`lang`),
  CONSTRAINT `dates_questions_translation_id_dates_questions_id` FOREIGN KEY (`id`) REFERENCES `dates_questions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `dates_m2n_questions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `dates_id` bigint(20) unsigned NOT NULL,
  `question_id` bigint(20) unsigned NOT NULL,
  `position` bigint(20) unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dates_id_idx_idx` (`dates_id`),
  KEY `question_id1_idx_idx` (`question_id`),
  CONSTRAINT `dates_m2n_questions_dates_id_dates_id` FOREIGN KEY (`dates_id`) REFERENCES `dates` (`id`),
  CONSTRAINT `dates_m2n_questions_question_id_dates_questions_id` FOREIGN KEY (`question_id`) REFERENCES `dates_questions` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `dates_participants_answers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `date_id` bigint(20) unsigned NOT NULL,
  `question_id` bigint(20) unsigned NOT NULL,
  `answer` varchar(120) COLLATE utf8_bin NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id_idx_idx` (`user_id`),
  KEY `date_id_idx_idx` (`date_id`),
  KEY `question_id0_idx_idx` (`question_id`),
  CONSTRAINT `dates_participants_answers_date_id_dates_id` FOREIGN KEY (`date_id`) REFERENCES `dates` (`id`),
  CONSTRAINT `dates_participants_answers_question_id_dates_questions_id` FOREIGN KEY (`question_id`) REFERENCES `dates_questions` (`id`),
  CONSTRAINT `dates_participants_answers_user_id_entity_id` FOREIGN KEY (`user_id`) REFERENCES `entity` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

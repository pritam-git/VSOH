--
-- New columns in `dates_questions`
--

ALTER TABLE `dates_questions`
	ADD COLUMN `is_required` tinyint(1) DEFAULT '0' AFTER `is_checkbox`,
	ADD COLUMN `parent_question_id` bigint(20) unsigned DEFAULT NULL AFTER `is_required`;

ALTER TABLE `dates_questions`
	ADD KEY `parent_question_id_idx_idx` (`parent_question_id`);

ALTER TABLE `dates_questions` 
	ADD CONSTRAINT `parent_question_id` FOREIGN KEY (`parent_question_id`) REFERENCES `dates_questions` (`id`);


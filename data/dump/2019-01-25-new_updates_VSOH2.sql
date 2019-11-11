--
-- Tabellenstruktur für Tabelle `dates_participants`
--

CREATE TABLE `dates_participants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `participant_name` varchar(120) COLLATE utf8_bin NOT NULL,
  `date_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


--
-- Indizes für die Tabelle `dates_participants`
--
ALTER TABLE `dates_participants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id_idx_idx` (`user_id`),
  ADD KEY `date_id_idx_idx` (`date_id`);

--
-- AUTO_INCREMENT für Tabelle `dates_participants`
--
ALTER TABLE `dates_participants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints der Tabelle `dates_participants`
--
ALTER TABLE `dates_participants`
  ADD CONSTRAINT `dates_participants_date_id_dates_id` FOREIGN KEY (`date_id`) REFERENCES `dates` (`id`),
  ADD CONSTRAINT `dates_participants_user_id_entity_id` FOREIGN KEY (`user_id`) REFERENCES `entity` (`id`);

ALTER TABLE `prefix_mailing` ADD COLUMN `mailing_type` VARCHAR(100) DEFAULT NULL;

ALTER TABLE `prefix_user` ADD `user_notice_digest_best_topics_hash` varchar(16) NOT NULL;
ALTER TABLE `prefix_user` ADD `user_settings_notice_digest_best_topics` tinyint(1) NOT NULL DEFAULT '1';
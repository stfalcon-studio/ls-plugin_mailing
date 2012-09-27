ALTER TABLE `prefix_mailing` ADD COLUMN `mailing_talk` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `prefix_mailing_queue` ADD COLUMN `sended` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0';

ALTER TABLE `prefix_user` ADD `user_no_digest` TINYINT(1) NOT NULL DEFAULT '0';
ALTER TABLE `prefix_user` ADD `user_no_digest_hash` VARCHAR(16) NOT NULL;
CREATE TABLE IF NOT EXISTS `prefix_mailing` (
    `mailing_id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
    `send_by_user_id` INT( 11 ) UNSIGNED NOT NULL ,
    `mailing_title` VARCHAR( 200 ) NOT NULL ,
    `mailing_text` TEXT NOT NULL ,
    `mailing_count` INT( 11 ) UNSIGNED NOT NULL ,
    `mailing_active` TINYINT( 1 ) NOT NULL DEFAULT '0',
    `mailing_sex` TEXT NOT NULL ,
    `mailing_lang`  TEXT NOT NULL,
    `mailing_date` DATETIME NOT NULL ,
    `mailing_talk` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0',
    INDEX `send_by_user_id` (`send_by_user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `prefix_mailing_queue` (
    `id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `mailing_id` INT( 11 ) UNSIGNED NOT NULL,
    `user_id` INT( 11 ) UNSIGNED NOT NULL,
    `talk_id` INT( 11 ) UNSIGNED DEFAULT NULL,
    `sended` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0',
    INDEX `mailing_id` (`mailing_id`),
    INDEX `user_id` (`user_id`),
    INDEX `talk_id` (`talk_id`)
) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;

ALTER TABLE `prefix_mailing`
ADD CONSTRAINT `prefix_mailing_ibfk_1` FOREIGN KEY (`send_by_user_id`) REFERENCES `prefix_user` (`user_id`);

ALTER TABLE `prefix_mailing_queue`
ADD CONSTRAINT `prefix_mailing_queue_ibfk_1` FOREIGN KEY (`mailing_id`) REFERENCES `prefix_mailing` (`mailing_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `prefix_mailing_queue`
ADD CONSTRAINT `prefix_mailing_queue_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `prefix_mailing_queue`
ADD CONSTRAINT `prefix_mailing_queue_ibfk_3` FOREIGN KEY (`talk_id`) REFERENCES `prefix_talk` (`talk_id`) ON DELETE SET NULL ON UPDATE SET NULL;

ALTER TABLE `prefix_user` ADD `user_no_digest` TINYINT(1) NOT NULL DEFAULT '0';
ALTER TABLE `prefix_user` ADD `user_no_digest_hash` VARCHAR(16) NOT NULL;
ALTER TABLE `prefix_mailing` ADD COLUMN `mailing_talk` tinyint(1) unsigned NOT NULL DEFAULT '0';
ALTER TABLE `prefix_mailing` ADD COLUMN `mailing_direct` tinyint(1) unsigned NOT NULL DEFAULT '0';

ALTER TABLE `prefix_mailing_queue` ADD COLUMN `sended` tinyint(1) unsigned NOT NULL DEFAULT '0';

UPDATE `prefix_mailing_queue` SET `sended` = '1' WHERE 1=1;

ALTER TABLE `prefix_mailing` DROP `mailing_direct` ;
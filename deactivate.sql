ALTER TABLE `prefix_user` DROP `user_no_digest`;
ALTER TABLE `prefix_user` DROP `user_no_digest_hash`;

ALTER TABLE `prefix_mailing` DROP FOREIGN KEY `prefix_mailing_ibfk_1`;
ALTER TABLE `prefix_mailing_queue` DROP FOREIGN KEY `prefix_mailing_queue_ibfk_1`;
ALTER TABLE `prefix_mailing_queue` DROP FOREIGN KEY `prefix_mailing_queue_ibfk_2`;
ALTER TABLE `prefix_mailing_queue` DROP FOREIGN KEY `prefix_mailing_queue_ibfk_3`;

DROP TABLE IF EXISTS `prefix_mailing_queue`;
DROP TABLE IF EXISTS `prefix_mailing`;

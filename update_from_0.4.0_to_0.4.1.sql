ALTER TABLE `prefix_mailing` ADD COLUMN `mailing_type` VARCHAR(32) DEFAULT NULL;
ALTER TABLE `prefix_user` ADD COLUMN `user_subscribes` VARCHAR(255) DEFAULT NULL;
ALTER TABLE `prefix_user` DROP COLUMN `user_no_digest`;
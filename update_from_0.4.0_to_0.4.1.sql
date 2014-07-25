ALTER TABLE `prefix_mailing` ADD COLUMN `mailing_type` VARCHAR(32) DEFAULT NULL;
ALTER TABLE `prefix_user` ADD COLUMN `user_subscribes` VARCHAR(500) DEFAULT NULL;

UPDATE `prefix_user` AS _u
SET _u.user_subscribes = IF(`user_no_digest` = 0 , '["mailing"]', '[]');

ALTER TABLE `prefix_user` DROP COLUMN `user_no_digest`;
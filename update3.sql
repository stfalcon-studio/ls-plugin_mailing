ALTER TABLE `prefix_user` ADD `user_no_digest` TINYINT(1) NOT NULL DEFAULT '0';
ALTER TABLE `prefix_user` ADD `user_no_digest_hash` VARCHAR(16) NOT NULL;
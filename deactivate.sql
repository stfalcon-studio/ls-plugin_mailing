DROP TABLE IF EXISTS `prefix_mailing_queue`;
DROP TABLE IF EXISTS `prefix_mailing`;

ALTER TABLE `prefix_user`
  DROP `user_no_digest`,
  DROP `user_no_digest_hash`;
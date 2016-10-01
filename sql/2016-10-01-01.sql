rename table `user` to `kv_user`;
alter table kv_user add uid bigint(20) unsigned NOT NULL after provider;
alter table kv_user modify userId char(36) NOT NULL;

CREATE TABLE `kv_group` (
  `groupId` char(36) NOT NULL,
  `gameId` char(36) DEFAULT NULL,
  `questId` char(36) DEFAULT NULL,
  `pointId` char(36) DEFAULT NULL,
  `users` blob,
  `pin` smallint(4) unsigned DEFAULT NULL,
  `active` tinyint(2) unsigned DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

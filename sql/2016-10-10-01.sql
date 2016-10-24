CREATE TABLE `kv_log` (
  `gameId` char(36) DEFAULT NULL,
  `groupId` char(36) NOT NULL,
  `userId` char(36) DEFAULT NULL,
  `created` datetime,
  `content` blob
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

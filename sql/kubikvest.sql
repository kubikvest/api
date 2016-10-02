create database if not exists `kubikvest` character set utf8 collate utf8_general_ci;

use kubikvest;

DROP TABLE IF EXISTS `kv_user`;

CREATE TABLE `kv_user` (
  `userId` char(36) NOT NULL,
  `provider` char(2) DEFAULT NULL,
  `uid` bigint(20) unsigned NOT NULL,
  `accessToken` varchar(255) DEFAULT NULL,
  `groupId` char(36) DEFAULT NULL,
  `ttl` int(11) unsigned DEFAULT NULL,
  `questId` char(36) DEFAULT NULL,
  `pointId` char(36) DEFAULT NULL,
  `logQuest` blob,
  `startTask` datetime
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `kv_group`;

CREATE TABLE `kv_group` (
  `groupId` char(36) NOT NULL,
  `gameId` char(36) DEFAULT NULL,
  `questId` char(36) DEFAULT NULL,
  `pointId` char(36) DEFAULT NULL,
  `users` blob,
  `pin` smallint(4) unsigned DEFAULT NULL,
  `startPoint` datetime,
  `active` tinyint(2) unsigned DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

insert kv_user (userId, provider, uid, accessToken) values ('adff5c92-008c-47ac-bad8-11be43ea1469', 'vk', 1111, 'token');

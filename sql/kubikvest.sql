create database if not exists `kubikvest` character set utf8 collate utf8_general_ci;

use kubikvest;

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `userId` bigint(20) unsigned NOT NULL,
  `provider` char(2)  DEFAULT NULL,
  `accessToken` varchar(255) DEFAULT NULL,
  `groupId` char(36) DEFAULT NULL,
  `questId` char(36) DEFAULT NULL,
  `pointId` char(36) DEFAULT NULL,
  `logQuest` blob,
  `startTask` datetime
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


insert user (userId, provider, accessToken) value (1111, 'vk', 'token');

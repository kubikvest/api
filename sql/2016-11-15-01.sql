  ALTER TABLE `kv_user`
CHANGE COLUMN `userId`      `user_id`      CHAR(36),
CHANGE COLUMN `accessToken` `access_token` VARCHAR(255),
CHANGE COLUMN `groupId`     `group_id`     CHAR(36),
CHANGE COLUMN `questId`     `quest_id`     CHAR(36) DEFAULT NULL,
CHANGE COLUMN `pointId`     `point_id`     CHAR(36) DEFAULT NULL,
CHANGE COLUMN `logQuest`    `log_quest`    BLOB,
CHANGE COLUMN `startTask`   `start_task`   DATETIME;

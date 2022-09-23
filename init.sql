CREATE TABLE IF NOT EXISTS `cmw_minecraft_servers`
(
    `minecraft_server_id`          INT          NOT NULL AUTO_INCREMENT,
    `minecraft_server_name`        VARCHAR(255) NOT NULL,
    `minecraft_server_ip`          VARCHAR(255) NOT NULL,
    `minecraft_server_port`        INT(5)       NULL,
    `minecraft_server_last_update` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`minecraft_server_id`),
    UNIQUE KEY (`minecraft_server_name`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;
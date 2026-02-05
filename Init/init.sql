CREATE TABLE IF NOT EXISTS `cmw_minecraft_servers`
(
    `minecraft_server_id`          INT          NOT NULL AUTO_INCREMENT,
    `minecraft_server_name`        VARCHAR(255) NOT NULL,
    `minecraft_server_ip`          VARCHAR(255) NOT NULL,
    `minecraft_server_port`        INT(5)       NULL,
    `minecraft_server_cmwl_port`        INT(5)       NULL,
    `minecraft_server_cmwl_token`        VARCHAR(255)       NULL,
    `minecraft_server_cmwl_status`        INT(1)       NULL,
    `minecraft_server_last_update` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `minecraft_server_status`      TINYINT(1)   NOT NULL DEFAULT 0 COMMENT '-1 = maintenance\r\n0 = offline\r\n1 = online',
    `minecraft_server_is_fav`      TINYINT(1)   NOT NULL DEFAULT 0,
    PRIMARY KEY (`minecraft_server_id`),
    UNIQUE KEY (`minecraft_server_name`)
) ENGINE = InnoDB
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `cmw_minecraft_history`
(
    minecraft_history_id           INT AUTO_INCREMENT PRIMARY KEY,
    user_id                        INT NOT NULL,
    minecraft_history_server_name  VARCHAR(255) NOT NULL,
    minecraft_history_title        VARCHAR(255) NULL,
    minecraft_history_desc         VARCHAR(255) NULL,
    minecraft_history_created_at   TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    minecraft_history_updated_at   TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_user_id_minecraft_history FOREIGN KEY (user_id)
    REFERENCES cmw_users (user_id) ON UPDATE CASCADE ON DELETE CASCADE
    ) ENGINE = InnoDB
    CHARACTER SET = utf8mb4
    COLLATE = utf8mb4_unicode_ci;
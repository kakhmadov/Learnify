-- schema_mysql.sql
-- Datenbank anlegen und auswählen
CREATE DATABASE IF NOT EXISTS `db3dHIT_s1`
    DEFAULT CHARACTER SET utf8mb4
    DEFAULT COLLATE utf8mb4_unicode_ci;
USE `db3dHIT_s1`;

-- ---------------------------------------------------
-- Falls schon existierend, werden zuerst alte Tabellen gelöscht
-- ---------------------------------------------------
DROP TABLE IF EXISTS comments;
DROP TABLE IF EXISTS files;
DROP TABLE IF EXISTS folders;
DROP TABLE IF EXISTS users;

-- ---------------------------------------------------
-- Tabelle: users
-- ---------------------------------------------------
CREATE TABLE users (
                       id             INT          NOT NULL AUTO_INCREMENT,
                       username       VARCHAR(50)  NOT NULL,
                       email          VARCHAR(255) NOT NULL,
                       password_hash  VARCHAR(255) NOT NULL,
                       created_at     TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
                       PRIMARY KEY (id),
                       UNIQUE KEY ux_users_username (username),
                       UNIQUE KEY ux_users_email    (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------
-- Tabelle: folders
-- ---------------------------------------------------
CREATE TABLE folders (
                         id               INT          NOT NULL AUTO_INCREMENT,
                         user_id          INT          NOT NULL,
                         name             VARCHAR(100) NOT NULL,
                         parent_folder_id INT          NULL,
                         created_at       TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
                         PRIMARY KEY (id),
                         UNIQUE KEY ux_folders_user_parent_name (user_id, parent_folder_id, name),
                         KEY fk_folders_user   (user_id),
                         KEY fk_folders_parent (parent_folder_id),
                         CONSTRAINT fk_folders_user
                             FOREIGN KEY (user_id) REFERENCES users(id)
                                 ON DELETE CASCADE,
                         CONSTRAINT fk_folders_parent
                             FOREIGN KEY (parent_folder_id) REFERENCES folders(id)
                                 ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------
-- Tabelle: files
-- ---------------------------------------------------
CREATE TABLE files (
                       id           INT          NOT NULL AUTO_INCREMENT,
                       user_id      INT          NOT NULL,
                       folder_id    INT          NULL,
                       filename     VARCHAR(255) NOT NULL,
                       file_type    ENUM('docx','pdf','pptx','zip','png','jpg','mp4','mp3') NOT NULL,
                       subject      VARCHAR(100) NULL,
                       description  TEXT         NULL,
                       is_public    TINYINT(1)   NOT NULL DEFAULT 0,
                       uploaded_at  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
                       PRIMARY KEY (id),
                       KEY fk_files_user   (user_id),
                       KEY fk_files_folder (folder_id),
                       CONSTRAINT fk_files_user
                           FOREIGN KEY (user_id) REFERENCES users(id)
                               ON DELETE CASCADE,
                       CONSTRAINT fk_files_folder
                           FOREIGN KEY (folder_id) REFERENCES folders(id)
                               ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------
-- Tabelle: comments
-- ---------------------------------------------------
CREATE TABLE comments (
                          id                INT       NOT NULL AUTO_INCREMENT,
                          file_id           INT       NOT NULL,
                          user_id           INT       NOT NULL,
                          parent_comment_id INT       NULL,
                          content           TEXT      NOT NULL,
                          created_at        TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                          PRIMARY KEY (id),
                          KEY fk_comments_file   (file_id),
                          KEY fk_comments_user   (user_id),
                          KEY fk_comments_parent (parent_comment_id),
                          CONSTRAINT fk_comments_file
                              FOREIGN KEY (file_id) REFERENCES files(id)
                                  ON DELETE CASCADE,
                          CONSTRAINT fk_comments_user
                              FOREIGN KEY (user_id) REFERENCES users(id)
                                  ON DELETE CASCADE,
                          CONSTRAINT fk_comments_parent
                              FOREIGN KEY (parent_comment_id) REFERENCES comments(id)
                                  ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

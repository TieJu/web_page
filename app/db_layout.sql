-- -----------------------------------------------------
-- Table `sections`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `sections` ;

CREATE TABLE IF NOT EXISTS `sections` (
  `id` INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL,
  `name` VARCHAR(128) NULL,
  `controller` VARCHAR(64) NULL,
  `action` VARCHAR(64) NULL,
  `ordering` INTEGER NULL,
  `parent_id` INTEGER NULL,
  `right` TINYINT(1) NULL,
  `params` TEXT NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL);

CREATE UNIQUE INDEX `id_UNIQUEsections` ON `sections` (`id` ASC);


-- -----------------------------------------------------
-- Table `users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `users` ;

CREATE TABLE IF NOT EXISTS `users` (
  `id` INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL,
  `username` VARCHAR(128) NULL COLLATE NOCASE,
  `email` VARCHAR(128) NULL COLLATE NOCASE,
  `password` VARCHAR(128) NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL);

CREATE UNIQUE INDEX `id_UNIQUEusers` ON `users` (`id` ASC);


-- -----------------------------------------------------
-- Table `user_groups`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `user_groups` ;

CREATE TABLE IF NOT EXISTS `user_groups` (
  `id` INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL,
  `name` VARCHAR(64) NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL);

CREATE UNIQUE INDEX `id_UNIQUEuser_groups` ON `user_groups` (`id` ASC);


-- -----------------------------------------------------
-- Table `user_groups_users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `user_groups_users` ;

CREATE TABLE IF NOT EXISTS `user_groups_users` (
  `id` INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL,
  `user_id` INTEGER NULL,
  `user_group_id` INTEGER NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  CONSTRAINT `user_id`
    FOREIGN KEY (`user_id`)
    REFERENCES `users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `user_group_id`
    FOREIGN KEY (`user_group_id`)
    REFERENCES `user_groups` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

CREATE UNIQUE INDEX `id_UNIQUEuser_groups_users` ON `user_groups_users` (`id` ASC);


-- -----------------------------------------------------
-- Table `user_permissions`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `user_permissions` ;

CREATE TABLE IF NOT EXISTS `user_permissions` (
  `id` INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL,
  `user_id` INTEGER NULL,
  `permission` VARCHAR(64) NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  CONSTRAINT `user_id`
    FOREIGN KEY (`user_id`)
    REFERENCES `users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

CREATE UNIQUE INDEX `id_UNIQUEuser_permissions` ON `user_permissions` (`id` ASC);


-- -----------------------------------------------------
-- Table `user_group_permissions`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `user_group_permissions` ;

CREATE TABLE IF NOT EXISTS `user_group_permissions` (
  `id` INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL,
  `user_group_id` INTEGER NULL,
  `permission` VARCHAR(64) NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  CONSTRAINT `user_group_id`
    FOREIGN KEY (`user_group_id`)
    REFERENCES `user_groups` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

CREATE UNIQUE INDEX `id_UNIQUEuser_group_permissions` ON `user_group_permissions` (`id` ASC);


-- -----------------------------------------------------
-- Table `forums`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `forums` ;

CREATE TABLE IF NOT EXISTS `forums` (
  `id` INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL,
  `parent_id` INTEGER NULL,
  `name` VARCHAR(128) NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL);

CREATE UNIQUE INDEX `id_UNIQUEforums` ON `forums` (`id` ASC);


-- -----------------------------------------------------
-- Table `forum_threads`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `forum_threads` ;

CREATE TABLE IF NOT EXISTS `forum_threads` (
  `id` INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL,
  `author_id` INTEGER NULL,
  `forum_id` INTEGER NULL,
  `name` VARCHAR(128) NULL,
  `locked` TINYINT(1) NULL,
  `sticky` TINYINT(1) NULL,
  `article` TINYINT(1) NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  CONSTRAINT `forum_id`
    FOREIGN KEY (`forum_id`)
    REFERENCES `forums` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `author_id`
    FOREIGN KEY (`author_id`)
    REFERENCES `users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

CREATE UNIQUE INDEX `id_UNIQUEforum_threads` ON `forum_threads` (`id` ASC);


-- -----------------------------------------------------
-- Table `forum_posts`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `forum_posts` ;

CREATE TABLE IF NOT EXISTS `forum_posts` (
  `id` INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL,
  `thread_id` INTEGER NULL,
  `author_id` INTEGER NULL,
  `name` VARCHAR(128) NULL,
  `post` TEXT NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  CONSTRAINT `thread_id`
    FOREIGN KEY (`thread_id`)
    REFERENCES `forum_threads` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `author_id`
    FOREIGN KEY (`author_id`)
    REFERENCES `users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

CREATE UNIQUE INDEX `id_UNIQUEforum_posts` ON `forum_posts` (`id` ASC);


-- -----------------------------------------------------
-- Table `projects`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `projects` ;

CREATE TABLE IF NOT EXISTS `projects` (
  `id` INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL,
  `name` VARCHAR(128) NULL,
  `description` TEXT NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL);

CREATE UNIQUE INDEX `id_UNIQUEprojects` ON `projects` (`id` ASC);

-- -----------------------------------------------------
-- Table `project_version_status`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `project_version_statuses` ;

CREATE TABLE IF NOT EXISTS `project_version_statuses` (
  `id` INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL,
  `name` VARCHAR(64) NULL,
  `property_string` TEXT NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL );

CREATE UNIQUE INDEX `id_UNIQUEproject_version_statuses` ON `project_version_status` (`id` ASC);

-- -----------------------------------------------------
-- Table `project_versions`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `project_versions` ;

CREATE TABLE IF NOT EXISTS `project_versions` (
  `id` INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL,
  `project_id` INTEGER NULL,
  `status_id` INTEGER NULL,
  `name` VARCHAR(128) NULL,
  `description` TEXT NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  CONSTRAINT `project_id`
    FOREIGN KEY (`project_id`)
    REFERENCES `projects` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `status_id`
    FOREIGN KEY (`status_id`)
    REFERENCES `project_version_statuses` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

CREATE UNIQUE INDEX `id_UNIQUEproject_versions` ON `project_versions` (`id` ASC);


-- -----------------------------------------------------
-- Table `statuses`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `statuses` ;

CREATE TABLE IF NOT EXISTS `statuses` (
  `id` INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL,
  `name` VARCHAR(64) NULL,
  `property_string` TEXT NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL);

CREATE UNIQUE INDEX `id_UNIQUEstatuses` ON `statuses` (`id` ASC);


-- -----------------------------------------------------
-- Table `priorities`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `priorities` ;

CREATE TABLE IF NOT EXISTS `priorities` (
  `id` INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL,
  `name` VARCHAR(64) NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL);

CREATE UNIQUE INDEX `id_UNIQUEpriorities` ON `priorities` (`id` ASC);


-- -----------------------------------------------------
-- Table `reports`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `reports` ;

CREATE TABLE IF NOT EXISTS `reports` (
  `id` INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL,
  `author_id` INTEGER UNSIGNED NULL,
  `project_version_id` INTEGER UNSIGNED NULL,
  `target_version_id` INTEGER UNSIGNED NULL,
  `priority_id` INTEGER UNSIGNED NULL,
  `assigned_to_id` INTEGER UNSIGNED NULL,
  `status_id` INTEGER UNSIGNED NULL,
  `name` VARCHAR(128) NULL,
  `description` TEXT NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  CONSTRAINT `project_version_id`
    FOREIGN KEY (`project_version_id`)
    REFERENCES `project_versions` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `status_id`
    FOREIGN KEY (`status_id`)
    REFERENCES `statuses` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `priority_id`
    FOREIGN KEY (`priority_id`)
    REFERENCES `priorities` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `autor_id`
    FOREIGN KEY (`author_id`)
    REFERENCES `users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

CREATE UNIQUE INDEX `id_UNIQUEreports` ON `reports` (`id` ASC);


-- -----------------------------------------------------
-- Table `settings`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `settings` ;

CREATE TABLE IF NOT EXISTS `settings` (
  `id` INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL,
  `item_key` VARCHAR(128) NULL,
  `item_value` TEXT NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL);

CREATE UNIQUE INDEX `id_UNIQUEsettings` ON `settings` (`id` ASC);


-- -----------------------------------------------------
-- Table `forum_user_group_permissions`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `forum_user_group_permissions` ;

CREATE TABLE IF NOT EXISTS `forum_user_group_permissions` (
  `id` INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL,
  `forum_id` INTEGER UNSIGNED NULL,
  `user_group_id` INTEGER UNSIGNED NULL,
  `permission` VARCHAR(64) NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  CONSTRAINT `forum_id`
    FOREIGN KEY (`forum_id`)
    REFERENCES `forums` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `group_id`
    FOREIGN KEY (`user_group_id`)
    REFERENCES `user_groups` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

CREATE UNIQUE INDEX `id_UNIQUEforum_user_group_permissions` ON `forum_user_group_permissions` (`id` ASC);

-- -----------------------------------------------------
-- Table `report_comments`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `report_comments` ;

CREATE TABLE IF NOT EXISTS `report_comments` (
  `id` INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL,
  `report_id` INTEGER UNSIGNED NULL,
  `author_id` INTEGER UNSIGNED NULL,
  `comment` TEXT NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  CONSTRAINT `report_id`
    FOREIGN KEY (`report_id`)
    REFERENCES `reports` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `author_id`
    FOREIGN KEY (`author_id`)
    REFERENCES `users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

CREATE UNIQUE INDEX `id_UNIQUEreport_comments` ON `report_comments` (`id` ASC);

-- -----------------------------------------------------
-- Table `project_user_group_permissions`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `project_user_group_permissions` ;

CREATE TABLE IF NOT EXISTS `project_user_group_permissions` (
  `id` INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL,
  `project_id` INTEGER UNSIGNED NULL,
  `user_group_id` INTEGER UNSIGNED NULL,
  `permission` VARCHAR(64) NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  CONSTRAINT `project_id`
    FOREIGN KEY (`project_id`)
    REFERENCES `projects` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `group_id`
    FOREIGN KEY (`user_group_id`)
    REFERENCES `user_groups` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

CREATE UNIQUE INDEX `id_UNIQUEproject_user_group_permissions` ON `project_user_group_permissions` (`id` ASC);

-- -----------------------------------------------------
-- Table `tags`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tags` ;

CREATE TABLE IF NOT EXISTS `tags` (
  `id` INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL,
  `name` TEXT NULL COLLATE NOCASE,
  `created` DATETIME NULL,
  `modified` DATETIME NULL);

CREATE UNIQUE INDEX `id_UNIQUEtags` ON `tags` (`id` ASC);

-- -----------------------------------------------------
-- Table `forum_posts_tags`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `forum_posts_tags` ;

CREATE TABLE IF NOT EXISTS `forum_posts_tags` (
  `id` INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL,
  `forum_post_id` INTEGER UNSIGNED NULL,
  `tag_id` INTEGER UNSIGNED NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  CONSTRAINT `forum_post_id`
    FOREIGN KEY (`forum_post_id`)
    REFERENCES `forum_posts` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `tag_id`
    FOREIGN KEY (`tag_id`)
    REFERENCES `tags` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

CREATE UNIQUE INDEX `id_UNIQUEforum_posts_tags` ON `forum_posts_tags` (`id` ASC);


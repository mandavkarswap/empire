-- Lent records
CREATE TABLE `sw_fs_receivables` (
	`id` MEDIUMINT(8) unsigned NOT NULL AUTO_INCREMENT,
	`borrower_id` SMALLINT(4) unsigned,
	`amount` MEDIUMINT(8) unsigned NOT NULL,
	`lending_date` DATETIME DEFAULT NOW(),
	`return_date` DATETIME,
	`is_paid` TINYINT(1) unsigned DEFAULT '0',
	`creation_date` DATETIME DEFAULT NOW(),
	`updation_date` DATETIME,
	`comment` VARCHAR(256),
	PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- Return
-- can be used when borrower is returning in
-- installments
-- update is paid of table sw_fs_receivables
CREATE TABLE `sw_fs_receivable_returns` (
	`id` MEDIUMINT unsigned NOT NULL AUTO_INCREMENT,
	`receivable_id` MEDIUMINT(8) unsigned NOT NULL,
	`amount` INT(8) unsigned NOT NULL,
	`comment` VARCHAR(256),
	`creation_date` DATETIME NOT NULL,
	`updation_date` DATETIME NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- borrower
-- TODO : Add is active column
CREATE TABLE `sw_fs_borrower_master` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB

-- Insert new borrower
DROP PROCEDURE IF EXISTS `sp_sw_fs_insert_new_borrower`;
CREATE PROCEDURE `sp_sw_fs_insert_new_borrower`(
    arg_borrower_name varchar(100)
)
BEGIN

	INSERT IGNORE INTO sw_fs_borrower_master(name) values(arg_borrower_name);

	SELECT LAST_INSERT_ID() as new_borrower_id;
END

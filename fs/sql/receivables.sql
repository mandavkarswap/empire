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
CREATE TABLE `sw_fs_borrower_master` (
	`id` MEDIUMINT unsigned NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(256) NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB;

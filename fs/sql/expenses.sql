CREATE TABLE `sw_fs_expenses` (
	`id` MEDIUMINT(8) unsigned NOT NULL AUTO_INCREMENT,
	`type_id` SMALLINT(4) unsigned DEFAULT '1' COMMENT 'Expense types',
	`amount` MEDIUMINT(8) unsigned,
	`comment` VARCHAR(256) DEFAULT '',
	`creation_date` DATETIME DEFAULT NOW(),
	`updation_date` DATETIME DEFAULT '0000-00-00 00:00:00',
	PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE TABLE `sw_fs_loaner_master` (
	`id` MEDIUMINT(8) unsigned NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(100) NOT NULL UNIQUE,
	`is_active` TINYINT(1) unsigned DEFAULT '1' COMMENT '1 => active, 0=in-active',
	PRIMARY KEY (`id`)
);

CREATE TABLE `sw_fs_notes_payable_paid` (
	`id` MEDIUMINT(8) unsigned NOT NULL AUTO_INCREMENT,
	`notes_payable_id` MEDIUMINT(8) unsigned NOT NULL,
	`amount` MEDIUMINT(8) unsigned NOT NULL,
	`comment` VARCHAR(256) DEFAULT NULL,
	`creation_date` DATETIME NOT NULL,
	`updation_date` DATETIME NOT NULL,
	PRIMARY KEY (`id`)
);

CREATE TABLE `sw_fs_notes_payable` (
	`id` MEDIUMINT(8) unsigned NOT NULL AUTO_INCREMENT,
	`loaner_id` MEDIUMINT(8) unsigned NOT NULL,
	`amount` MEDIUMINT(8) unsigned NOT NULL,
	`amount_payable` MEDIUMINT(8) unsigned NOT NULL,
	`lending_date` DATETIME NOT NULL,
	`return_date` DATETIME NOT NULL,
	`is_paid` TINYINT(1) unsigned DEFAULT '0' COMMENT '1 => Paid, 0=> Remaining',
	`comment` VARCHAR(256) NOT NULL,
	`creation_date` DATETIME NOT NULL,
	`updation_date` DATETIME NOT NULL,
	PRIMARY KEY (`id`)
);

-- Insert New Loaner;
DROP PROCEDURE IF EXISTS `sp_sw_fs_insert_new_loaner`;
CREATE PROCEDURE `sp_sw_fs_insert_new_loaner`(
    arg_loaner_name varchar(256)
)
BEGIN

	INSERT IGNORE INTO sw_fs_loaner_master(name) values(arg_loaner_name);

	SELECT LAST_INSERT_ID() as new_loaner_id;   
END


-- Income Type Master
CREATE TABLE `sw_fs_income_type_master` (
	`id` MEDIUMINT(8) unsigned NOT NULL AUTO_INCREMENT,
	`type` VARCHAR(100) NOT NULL,
	`is_active` TINYINT(1) unsigned DEFAULT '1' COMMENT '1=Active, 0= In-active',
	PRIMARY KEY (`id`),
	UNIQUE KEY `unique_type` (`type`)
);

-- Income Transaction Master
CREATE TABLE `sw_fs_income_transaction_master` (
	`id` MEDIUMINT(8) unsigned NOT NULL AUTO_INCREMENT,
	`income_id` MEDIUMINT(8) unsigned NOT NULL,
	`date` DATETIME NOT NULL,
	`amount` INT(8) unsigned NOT NULL,
	`comment` VARCHAR(256),
	`creation_date` DATETIME DEFAULT NOW(),
	`updation_date` DATETIME DEFAULT '0000-00-00 00:00:00',
	PRIMARY KEY (`id`)
);

-- Insert New Income Type
DROP PROCEDURE IF EXISTS `sp_sw_fs_insert_new_income_type`;
CREATE PROCEDURE `sp_sw_fs_insert_new_income_type`(
    arg_income_type varchar(100)
)
BEGIN

	INSERT IGNORE INTO sw_fs_income_type_master(type) values(arg_income_type);

	SELECT LAST_INSERT_ID() as new_income_type_id;
END



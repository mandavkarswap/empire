-- Bank Account Master
CREATE TABLE `sw_fs_bank_account_master` (
	`id` TINYINT(1) unsigned NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(100) NOT NULL UNIQUE,
	`is_active` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '1 => Active, 0 => In-Active',
	PRIMARY KEY (`id`)
);

-- INSERT IGNORE INTO sw_fs_bank_account_master(name) values('AXIS_9150XXXXXXXX978');
-- INSERT IGNORE INTO sw_fs_bank_account_master(name) values('SBI_329XXXX7234');

-- bank account balance log master
CREATE TABLE `sw_fs_bank_account_balance_log` (
  `id` MEDIUMINT(8) unsigned NOT NULL AUTO_INCREMENT,
  `account_id` MEDIUMINT(8) unsigned NOT NULL,
  `ammount` MEDIUMINT(8) unsigned NOT NULL,
  `comment` varchar(256) DEFAULT NULL,
  `creation_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `updation_date` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
);

-- Insert New Balance Entry
DROP PROCEDURE IF EXISTS `sp_sw_fs_insert_bank_balance_entry`;
CREATE PROCEDURE `sp_sw_fs_insert_bank_balance_entry`(
    arg_bank_id TINYINT(1),
    arg_amount MEDIUMINT(8),
    arg_creation_date datetime
)
BEGIN

	INSERT INTO sw_fs_bank_account_balance_log(account_id, ammount, creation_date) values(arg_bank_id, arg_amount, arg_creation_date);

	SELECT LAST_INSERT_ID() as new_balance_id;
END;

-- CALL sp_sw_fs_insert_bank_balance_entry(1, 58688, NOW());
-- CALL sp_sw_fs_insert_bank_balance_entry(2, 56061, NOW());

-- Select latest Balance Account Wise Entry
DROP PROCEDURE IF EXISTS `sp_sw_fs_select_latest_bank_balance`;
CREATE PROCEDURE `sp_sw_fs_select_latest_bank_balance`()
BEGIN
  SELECT b.name as name, a.ammount as amount FROM sw_fs_bank_account_balance_log a
  JOIN sw_fs_bank_account_master b ON a.account_id = b.id
  WHERE a.creation_date IN (
      SELECT MAX(creation_date)
      FROM sw_fs_bank_account_balance_log
      GROUP BY account_id
    )
  GROUP BY name;
END;

-- CALL sp_sw_fs_select_latest_bank_balance();

-- Select total Balance Account Wise Entry
DROP PROCEDURE IF EXISTS `sp_sw_fs_select_total_bank_balance`;
CREATE PROCEDURE `sp_sw_fs_select_total_bank_balance`()
BEGIN
  SELECT SUM(a.ammount) as amount FROM sw_fs_bank_account_balance_log a
  WHERE creation_date IN (
      SELECT MAX(creation_date)
      FROM sw_fs_bank_account_balance_log
      GROUP BY account_id
    );
END;

-- CALL sp_sw_fs_select_total_bank_balance();
-- TRUNCATE table sw_fs_bank_account_balance_log;
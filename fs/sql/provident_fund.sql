-- Provident Find Master
CREATE TABLE `sw_fs_provident_fund_master` (
  `id` TINYINT(1) unsigned NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL UNIQUE,
  `is_active` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '1 => Active, 0 => In-Active',
  PRIMARY KEY (`id`)
);

-- INSERT IGNORE INTO sw_fs_provident_fund_master(name) values('UAN_100XXXXXX903');

-- bank provident fund log master
CREATE TABLE `sw_fs_provident_fund_balance_log` (
  `id` MEDIUMINT(8) unsigned NOT NULL AUTO_INCREMENT,
  `pf_id` MEDIUMINT(8) unsigned NOT NULL,
  `amount_invested` MEDIUMINT(8) unsigned NOT NULL,
  `current_value` MEDIUMINT(8) unsigned NOT NULL,
  `comment` varchar(256) DEFAULT NULL,
  `creation_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `updation_date` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
);

-- Insert Proviedent Entry
DROP PROCEDURE IF EXISTS `sp_sw_fs_insert_provident_fund_entry`;
CREATE PROCEDURE `sp_sw_fs_insert_provident_fund_entry`(
    arg_pf_id TINYINT(1),
    arg_amount_invested MEDIUMINT(8),
    arg_current_value MEDIUMINT(8),
    arg_creation_date datetime
)
BEGIN

  INSERT INTO sw_fs_provident_fund_balance_log(pf_id, amount_invested, current_value, creation_date) values(arg_pf_id, arg_amount_invested, arg_current_value, arg_creation_date);

  SELECT LAST_INSERT_ID() as new_log_id;
END;

-- CALL sp_sw_fs_insert_provident_fund_entry(1, 127822, 127822, NOW());

-- Select latest Provident Find Wise Entry
DROP PROCEDURE IF EXISTS `sp_sw_fs_select_latest_pf_balance`;
CREATE PROCEDURE `sp_sw_fs_select_latest_pf_balance`()
BEGIN
  SELECT b.name as name, a.current_value as current_value FROM sw_fs_provident_fund_balance_log a
  JOIN sw_fs_provident_fund_master b ON a.pf_id = b.id
  WHERE a.creation_date IN (
      SELECT MAX(creation_date)
      FROM sw_fs_provident_fund_balance_log
      GROUP BY pf_id
    )
  GROUP BY name;
END;

-- CALL sp_sw_fs_select_latest_pf_balance();

-- Select total Provident Finds Wise Entry
DROP PROCEDURE IF EXISTS `sp_sw_fs_select_total_pf_balance`;
CREATE PROCEDURE `sp_sw_fs_select_total_pf_balance`()
BEGIN
  SELECT SUM(a.current_value) as current_value FROM sw_fs_provident_fund_balance_log a
  WHERE creation_date IN (
      SELECT MAX(creation_date)
      FROM sw_fs_provident_fund_balance_log
      GROUP BY pf_id
    );
END;

-- CALL sp_sw_fs_select_total_pf_balance();
-- TRUNCATE table sw_fs_provident_fund_balance_log;
-- Doodads Master
CREATE TABLE `sw_fs_doodads_master` (
  `id` TINYINT(1) unsigned NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL UNIQUE,
  `purchase_value` MEDIUMINT(8) unsigned NOT NULL,
  `current_value` MEDIUMINT(8) unsigned NOT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '1 => Active, 0 => In-Active',
  `creation_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `updation_date` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
);

-- Insert Doodads Entry
DROP PROCEDURE IF EXISTS `sp_sw_fs_insert_doodads`;
CREATE PROCEDURE `sp_sw_fs_insert_doodads`(
  arg_name VARCHAR(100),
  arg_purchase_value MEDIUMINT(8),
  arg_current_value MEDIUMINT(8),
  arg_creation_date datetime
)
BEGIN
  INSERT INTO sw_fs_doodads_master(name, purchase_value, current_value, creation_date) values(arg_name, arg_purchase_value, arg_current_value, arg_creation_date);

  SELECT LAST_INSERT_ID() as new_log_id;
END;

-- CALL sp_sw_fs_insert_doodads('MTB', 45000, 26000, NOW());
-- CALL sp_sw_fs_insert_doodads('Hybrid', 16000, 8000, NOW());
-- CALL sp_sw_fs_insert_doodads('Laptop', 33000, 15000, NOW());
-- CALL sp_sw_fs_insert_doodads('Mp3player', 12000,8000, NOW());
-- CALL sp_sw_fs_insert_doodads('Kindle', 4500, 4000, NOW());
-- CALL sp_sw_fs_insert_doodads('GoPro', 18000,10000, NOW());


-- Select Doodads Name Wise
DROP PROCEDURE IF EXISTS `sp_sw_fs_select_doodads_type_total`;
CREATE PROCEDURE `sp_sw_fs_select_doodads_type_total`()
BEGIN
  SELECT name, current_value as amount FROM sw_fs_doodads_master;
END;

-- CALL sp_sw_fs_select_doodads_total();

-- Select Doodads Total
DROP PROCEDURE IF EXISTS `sp_sw_fs_select_doodads_total`;
CREATE PROCEDURE `sp_sw_fs_select_doodads_total`()
BEGIN
  SELECT SUM(current_value) as amount FROM sw_fs_doodads_master;
END;

-- CALL sp_sw_fs_select_doodads_total();
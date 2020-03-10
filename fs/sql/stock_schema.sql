-- Stock Master Table
CREATE TABLE `sw_fs_stock_master` (
	`id` MEDIUMINT(8) unsigned NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(100) NOT NULL UNIQUE,
	`is_active` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '1 => Active, 0 => In-Active',
	PRIMARY KEY (`id`)
);

-- Term Master Table
CREATE TABLE `sw_fs_stock_term_master` (
	`id` MEDIUMINT(8) unsigned NOT NULL AUTO_INCREMENT,
	`term` VARCHAR(100) NOT NULL UNIQUE,
	`duration_in_month` DECIMAL(4,2) unsigned NOT NULL DEFAULT 1.0 COMMENT '1 month is considered as duration of 30 days',
	PRIMARY KEY (`id`)
);

-- Stock Transaction Table
CREATE TABLE `sw_fs_stock_transaction_master` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `stock_id` int(8) unsigned NOT NULL,
  `date` datetime NOT NULL,
  `term_master` tinyint(1) unsigned NOT NULL,
  `transaction_type` char(1) NOT NULL COMMENT 'b => buy, s => sell',
  `quantity` mediumint(8) unsigned NOT NULL,
  `single_share_price` decimal(16,6) unsigned NOT NULL COMMENT 'Without tax, price is when bidded/sold',
  `effective_single_share_price` decimal(16,6) unsigned NOT NULL COMMENT 'With tax being distributed among all share equally',
  `transaction_cost` decimal(16,6) unsigned NOT NULL COMMENT 'Total cost of this transaction including all possible taxes',
  `comment` varchar(256) DEFAULT NULL,
  `creation_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updation_date` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
)

-- Insert New Income Type
-- call sp_sw_fs_insert_new_stock('lux industries');
-- TODO : HOW To handle missed inserts? hint check sw_fs_stock_master ids
DROP PROCEDURE IF EXISTS `sp_sw_fs_insert_new_stock`;
CREATE PROCEDURE `sp_sw_fs_insert_new_stock`(
    arg_stock_name varchar(100)
)
BEGIN

	INSERT IGNORE INTO sw_fs_stock_master(name) values(arg_stock_name);

	SELECT LAST_INSERT_ID() as new_stock_id;
END
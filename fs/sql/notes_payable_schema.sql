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
	`remaining_amount` mediumint(8) unsigned NOT NULL,
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

-- Insert Notes Payable Transaction
DROP PROCEDURE IF EXISTS `sp_sw_fs_insert_notes_payable_paid`;
CREATE PROCEDURE `sp_sw_fs_insert_notes_payable_paid`(
    arg_payable_id mediumint(8) unsigned,
    arg_paid_amount mediumint(8) unsigned,
    arg_date datetime,
    arg_comment varchar(256)
)
BEGIN
    
	-- Check provided id exists
	SET @count = 0;
	SET @remainingAmount = 0;
	SET @id = arg_payable_id;
	SET @idCount = CONCAT("SELECT count(*), remaining_amount INTO @count, @remainingAmount FROM sw_fs_notes_payable WHERE id=", arg_payable_id);

	PREPARE stmt FROM @idCount;
	EXECUTE stmt;
	DEALLOCATE PREPARE stmt;

	IF @count > 0 AND @remainingAmount > 0 THEN
		
		-- Insert in payable transaction table;
		SET @insertTranscationTable = CONCAT("INSERT INTO sw_fs_notes_payable_paid(notes_payable_id, amount, comment, creation_date) values('", arg_payable_id,
			"','", arg_paid_amount,
			"','", arg_comment,
			"','", arg_date, "')"
			);

		PREPARE stmt_insertTranscationTable FROM @insertTranscationTable;
		EXECUTE stmt_insertTranscationTable;
		DEALLOCATE PREPARE stmt_insertTranscationTable;

		SELECT ROW_COUNT();

		-- Update in Remaining amount in payable master
		SET @difference = @remainingAmount - arg_paid_amount;
		
		IF @difference = 0 THEN
			SET @updateReceivable = CONCAT(
"UPDATE sw_fs_notes_payable",
" SET remaining_amount=", @difference,
" , is_paid=1",
" , updation_date='", arg_date, "'",	
" WHERE id=", arg_payable_id);

		ELSE
			SET @updateReceivable = CONCAT(
"UPDATE sw_fs_notes_payable"
" SET remaining_amount=", @difference,
" , updation_date='", arg_date, "'",
" WHERE id=", arg_payable_id);

		END IF;

		-- select @updateReceivable;

		PREPARE stmt_insertTranscationTable FROM @updateReceivable;
		EXECUTE stmt_insertTranscationTable;
		DEALLOCATE PREPARE stmt_insertTranscationTable;		
		
		select ROW_COUNT();

	END IF;
END


DROP PROCEDURE IF EXISTS `sp_sw_fs_insert_receivable_payback`;
CREATE PROCEDURE sp_sw_fs_insert_receivable_payback(
    arg_receivable_id mediumint(8) unsigned,
    arg_receivable_amount mediumint(8) unsigned,
    arg_date datetime,
    arg_comment varchar(256)
)
BEGIN
    
	-- Check provided id exists
	SET @count = 0;
	SET @remainingAmount = 0;
	SET @id = arg_receivable_id;
	SET @idCount = CONCAT("SELECT count(*), remaining_amount INTO @count, @remainingAmount FROM sw_fs_receivables WHERE id=", arg_receivable_id);
	-- SET @idCount = SELECT count(*) FROM sw_fs_receivables WHERE id=@id;

	PREPARE stmt FROM @idCount;
	EXECUTE stmt;
	DEALLOCATE PREPARE stmt;

	-- SELECT @count, @remainingAmount;

	IF @count > 0 AND @remainingAmount > 0 THEN
		
		-- Insert in receivable transaction table;
		SET @insertTranscationTable = CONCAT("INSERT INTO sw_fs_receivable_returns(receivable_id, amount, comment, creation_date) values('", arg_receivable_id,
			"','", arg_receivable_amount,
			"','", arg_comment,
			"','", arg_date, "')"
			);

		-- select @insertTranscationTable;

		PREPARE stmt_insertTranscationTable FROM @insertTranscationTable;
		EXECUTE stmt_insertTranscationTable;
		DEALLOCATE PREPARE stmt_insertTranscationTable;

		SELECT ROW_COUNT();

		-- Update in Remaining amount in receivable master
		SET @difference = @remainingAmount - arg_receivable_amount;
		
		IF @difference = 0 THEN
			SET @updateReceivable = CONCAT(
"UPDATE sw_fs_receivables",
" SET remaining_amount=", @difference,
" , is_paid=1",
" , updation_date='", arg_date, "'",	
" WHERE id=", arg_receivable_id);

		ELSE
			SET @updateReceivable = CONCAT(
"UPDATE sw_fs_receivables"
" SET remaining_amount=", @difference,
" , updation_date='", arg_date, "'",
" WHERE id=", arg_receivable_id);

		END IF;

		-- select @updateReceivable;

		PREPARE stmt_insertTranscationTable FROM @updateReceivable;
		EXECUTE stmt_insertTranscationTable;
		DEALLOCATE PREPARE stmt_insertTranscationTable;		
		
		select ROW_COUNT();

	END IF;
END
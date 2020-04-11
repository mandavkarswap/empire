-- Select Financial Statement
DROP PROCEDURE IF EXISTS `sp_sw_fs_select_fs`;
CREATE PROCEDURE `sp_sw_fs_select_fs`()
BEGIN	
	
	SET @incomeSalary = 'SELECT a.id, b.type, SUM(a.amount) as amount FROM sw_fs_income_transaction_master a
	LEFT JOIN sw_fs_income_type_master b ON a.income_id = b.id;';

	SET @incomeSalaryTotal = 'SELECT "Total Income", SUM(amount) AS amount from sw_fs_income_transaction_master;';

	SET @expenseTypeTotal = 'SELECT b.name as typeName, a.totalExpense from (
SELECT type_id as type_id, SUM(amount) totalExpense from sw_fs_expenses
GROUP BY type_id
ORDER BY totalExpense DESC) a
LEFT JOIN sw_expenses_type_master b ON a.type_id=b.id;';

	SET @expenseTotal = 'SELECT "Total Expenses", SUM(amount) AS amount from sw_fs_expenses;';

	SET @stockTypeTotal = 'SELECT b.name, a.quantity, (a.transaction_cost/a.quantity) as effective_price, a.quantity*(a.transaction_cost/a.quantity) as total_price FROM sw_fs_stock_transaction_master a LEFT JOIN sw_fs_stock_master b ON a.stock_id = b.id WHERE a.transaction_type="b" GROUP BY name';

	SET @stockTotal = 'SELECT SUM(quantity*(transaction_cost/quantity)) as total_stock_price FROM sw_fs_stock_transaction_master WHERE transaction_type="b"';

	SET @notesNameTotal = 'SELECT b.name, SUM(a.remaining_amount) as remaining_amount FROM sw_fs_notes_payable a
		LEFT JOIN sw_fs_loaner_master b ON a.loaner_id = b.id
		WHERE a.is_paid=0 GROUP BY b.name;';

	SET @notesTotal = 'SELECT SUM(remaining_amount) as remaining_amount FROM sw_fs_notes_payable WHERE is_paid=0;';

	SET @receivablesNameTotal = 'SELECT b.name as name, SUM(a.remaining_amount) as remaining_amount FROM sw_fs_receivables a
		LEFT JOIN sw_fs_borrower_master b ON a.borrower_id = b.id
		WHERE a.is_paid=0 GROUP BY name;';

	SET @receibalesTotal = 'SELECT SUM(remaining_amount) as remaining_amount FROM sw_fs_receivables WHERE is_paid=0;';

	SET @bankNameTotal = 'CALL sp_sw_fs_select_latest_bank_balance();';

	SET @bankTotal = 'CALL sp_sw_fs_select_total_bank_balance();';

	SET @mutualFundWiseTotal = 'CALL sp_sw_fs_select_latest_mf_balance();';

	SET @mutualFundTotal = 'CALL sp_sw_fs_select_total_mf_balance();';

	SET @providentFundWiseTotal = 'CALL sp_sw_fs_select_latest_pf_balance();';

	SET @providentFundTotal = 'CALL sp_sw_fs_select_total_pf_balance();';

	SET @doodadNameWiseTotal = 'CALL sp_sw_fs_select_doodads_type_total();';

	SET @doodadTotal = 'CALL sp_sw_fs_select_doodads_total();';

	-- Result Set Income Salary
	PREPARE stmt FROM @incomeSalary;
	EXECUTE stmt;
	DEALLOCATE PREPARE stmt;

	-- Result Set Income Salary Total
	PREPARE stmt FROM @incomeSalaryTotal;
	EXECUTE stmt;
	DEALLOCATE PREPARE stmt;

	-- Result Set Expense Type Total
	PREPARE stmt FROM @expenseTypeTotal;
	EXECUTE stmt;
	DEALLOCATE PREPARE stmt;

	-- Result Set Expense Total
	PREPARE stmt FROM @expenseTotal;
	EXECUTE stmt;
	DEALLOCATE PREPARE stmt;

	-- Result Set Stock Type Total
	PREPARE stmt FROM @stockTypeTotal;
	EXECUTE stmt;
	DEALLOCATE PREPARE stmt;

	-- Result Set Stock Total
	PREPARE stmt FROM @stockTotal;
	EXECUTE stmt;
	DEALLOCATE PREPARE stmt;

	-- Result Set Notes name Total
	PREPARE stmt FROM @notesNameTotal;
	EXECUTE stmt;
	DEALLOCATE PREPARE stmt;

	-- Result Set Notes Total
	PREPARE stmt FROM @notesTotal;
	EXECUTE stmt;
	DEALLOCATE PREPARE stmt;

	-- Result Set Receibales Name Total
	PREPARE stmt FROM @receivablesNameTotal;
	EXECUTE stmt;
	DEALLOCATE PREPARE stmt;

	-- Result Set Receivables Total
	PREPARE stmt FROM @receibalesTotal;
	EXECUTE stmt;
	DEALLOCATE PREPARE stmt;

	-- Result Set Bank Name Total
	PREPARE stmt FROM @bankNameTotal;
	EXECUTE stmt;
	DEALLOCATE PREPARE stmt;

	-- Result Set Bank Total
	PREPARE stmt FROM @bankTotal;
	EXECUTE stmt;
	DEALLOCATE PREPARE stmt;

	-- Result Set Mutual Fund Wise Total
	PREPARE stmt FROM @mutualFundWiseTotal;
	EXECUTE stmt;
	DEALLOCATE PREPARE stmt;

	-- Result Set Mutual Fund Total
	PREPARE stmt FROM @mutualFundTotal;
	EXECUTE stmt;
	DEALLOCATE PREPARE stmt;

	-- Result Set Provident Fund Wise Total
	PREPARE stmt FROM @providentFundWiseTotal;
	EXECUTE stmt;
	DEALLOCATE PREPARE stmt;

	-- Result Set Provident Fund Total
	PREPARE stmt FROM @providentFundTotal;
	EXECUTE stmt;
	DEALLOCATE PREPARE stmt;

	-- Result Set Doodad Name Wise Total
	PREPARE stmt FROM @doodadNameWiseTotal;
	EXECUTE stmt;
	DEALLOCATE PREPARE stmt;

	-- Result Set Doodad Total
	PREPARE stmt FROM @doodadTotal;
	EXECUTE stmt;
	DEALLOCATE PREPARE stmt;
END

-- CALL sp_sw_fs_select_fs();
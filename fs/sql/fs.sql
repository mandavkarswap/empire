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

	SET @notesNameTotal = 'SELECT b.name, a.quantity, (a.transaction_cost/a.quantity) as effective_price, a.quantity*(a.transaction_cost/a.quantity) as total_price FROM sw_fs_stock_transaction_master a LEFT JOIN sw_fs_stock_master b ON a.stock_id = b.id WHERE a.transaction_type="b" GROUP BY name';

	SET @stockTotal = 'SELECT SUM(quantity*(transaction_cost/quantity)) as total_stock_price FROM sw_fs_stock_transaction_master WHERE transaction_type="b"';

SELECT a.id, b.name, a.amount, a.amount_payable, a.remaining_amount, DATE(a.lending_date) as lending_date, DATE(a.return_date) as return_date, DATE(a.updation_date) as last_payment_date, a.comment FROM sw_fs_notes_payable a
LEFT JOIN sw_fs_loaner_master b ON a.loaner_id = b.id
WHERE a.is_paid=0;

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

END
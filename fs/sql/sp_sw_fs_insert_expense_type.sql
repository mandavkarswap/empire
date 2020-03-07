DROP PROCEDURE IF EXISTS `sp_sw_fs_insert_expense_type`;
CREATE PROCEDURE `sp_sw_fs_insert_expense_type`(
    arg_expense_type_name varchar(256)
)
BEGIN

	INSERT IGNORE INTO sw_expenses_type_master(name) values(arg_expense_type_name);

	SELECT LAST_INSERT_ID() as expense_type_id;   
END
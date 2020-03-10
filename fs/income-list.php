<?php
/**
Page to list Income

TODO : 
Add date filter
Add Month filter
*/
require_once('config/config.inc.php');

require_once(DOC_ROOT . '/libs/CommonFunctions.php');

DB::$user = DB_USER;
DB::$password = DB_PASSWORD;
DB::$dbName = DB_NAME;

$pageName = 'Income List';

$tableArr = array(
              'type' => array(
                      'viewcol' => 'Source',
                      'align' => 'left',
                      ),
              'amount' => array(
                      'viewcol' => 'Amount',
                      'align' => 'right',
                      ),
              'date' => array(
                      'viewcol' => 'Date',
                      'align' => 'left'
                      ),
              'comment' => array(
                      'viewcol' => 'Comment',
                      'align' => 'right',
                      )
          );

$result = DB::query("
SELECT a.id, b.type, a.amount, DATE(a.date) as date, a.comment FROM sw_fs_income_transaction_master a
LEFT JOIN sw_fs_income_type_master b ON a.income_id = b.id;"
          );

$tableHTML = getTableHTML($tableArr, $result);
?>
<!DOCTYPE HTML>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $pageName; ?> | FS</title>
<style>
.error {color: #FF0000;}
</style>
</head>
<body>
<center>
<h1><?php echo $pageName; ?></h2>
<?php
  echo "<h4>All Income</h4>";
  echo $tableHTML;
?>
</center>
</body>
</html>
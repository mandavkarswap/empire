<?php
/**
Page to list Stock Portfolio

TODO : 
Add date filter
Add Month filter
*/
require_once('config/config.inc.php');

require_once(DOC_ROOT . '/libs/CommonFunctions.php');

DB::$user = DB_USER;
DB::$password = DB_PASSWORD;
DB::$dbName = DB_NAME;

$pageName = 'Stock Portfolio';

$tableArr = array(
              'name' => array(
                      'viewcol' => 'Name',
                      'align' => 'left',
                      ),
              'quantity' => array(
                      'viewcol' => 'Quantity',
                      'align' => 'right',
                      ),
              'effective_price' => array(
                      'viewcol' => 'ePrice',
                      'align' => 'right',
                      ),
              'transaction_cost' => array(
                      'viewcol' => 'Amount',
                      'align' => 'right',
                      ),
          );

// $result = DB::query("
// SELECT b.name, a.quantity, SUM(a.transaction_cost) as transaction_cost, (a.transaction_cost/a.quantity) as effective_price FROM sw_fs_stock_transaction_master a LEFT JOIN sw_fs_stock_master b ON a.stock_id = b.id WHERE a.transaction_type='b' GROUP BY name;
// ");
// UNIION FOR TOTAL FTW
$result = DB::query("
(SELECT b.name, a.quantity, SUM(a.transaction_cost) as transaction_cost, (a.transaction_cost/a.quantity) as effective_price FROM sw_fs_stock_transaction_master a LEFT JOIN sw_fs_stock_master b ON a.stock_id = b.id WHERE a.transaction_type='b' GROUP BY name)
UNION
(SELECT 'Total' AS name, SUM(quantity) AS quantity, SUM(transaction_cost) as transaction_cost, '-' as effective_price FROM sw_fs_stock_transaction_master WHERE transaction_type='b');
");

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
  echo "<h4>All Stocks</h4>";
  echo $tableHTML;
?>
</center>
</body>
</html>

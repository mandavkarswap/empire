<?php
/**
TODO : 
Dynamic date
validation
*/
require_once('config/config.inc.php');

require_once(DOC_ROOT . '/libs/CommonFunctions.php');

$today = date('Y-m-d', strtotime('today')) . ' 00:00:00';
$tomorrow = date('Y-m-d', strtotime('tomorrow')) . ' 00:00:00';
$startOfTheMonth = date('Y-m-01') . ' 00:00:00';
$startOfTheNextMonth = date('Y-m-01', strtotime('next month')) . ' 00:00:00';


DB::$user = DB_USER;
DB::$password = DB_PASSWORD;
DB::$dbName = DB_NAME;

// DB::debugMode();

// [id] => 23
// [type_id] => 27
// [amount] => 26
// [comment] => Home to OG home
// [creation_date] => 2020-01-26 13:05:31
// [updation_date] => 0000-00-00 00:00:2020-01-20
// [name] => commute
$dailyTableArr = array(
              'name' => array(
                      'viewcol' => 'Type',
                      'align' => 'left',
                      ),
              'amount' => array(
                      'viewcol' => 'Amount',
                      'align' => 'right',
                      ),
              'comment' => array(
                      'viewcol' => 'Comment',
                      'align' => 'right',
                      )
          );


// [id] => 1
// [type_id] => 10
// [amount] => 50
// [comment] => Cheetos and jeeru
// [creation_date] => 2020-01-20 23:06:55
// [updation_date] => 0000-00-00 00:00:00
// [name] => food
$monthlyTableArr = array(
            'creation_date' => array(
                      'viewcol' => 'Date',
                      'align' => 'left'
                      ),
            'name' => array(
                      'viewcol' => 'Type',
                      'align' => 'left'
                      ),
            'amount' => array(
                      'viewcol' => 'Amount',
                      'align' => 'right'
                      ),
          );

// [typeName] => food
// [totalExpense] => 2742
$monthlyTypeWiseTableArr = array(
            'typeName' => array(
                      'viewcol' => 'Type',
                      'align'=> 'right'
                      ),
            'totalExpense' => array(
                      'viewcol' => 'Total Amount',
                      'align'=> 'left'
                      ),
          );

$resultsDaily = DB::query("
SELECT a.*, b.name FROM sw_fs_expenses a
LEFT JOIN sw_expenses_type_master b ON a.type_id = b.id
WHERE a.creation_date >=%s
AND a.creation_date <%s;",
            $today,
            $tomorrow
          );

$dailyTableHTML = getTableHTML($dailyTableArr, $resultsDaily);

// [id] => 1
// [type_id] => 10
// [amount] => 50
// [comment] => Cheetos and jeeru
// [creation_date] => 2020-01-20 23:06:55
// [updation_date] => 0000-00-00 00:00:00
// [name] => food
$resultsMonthly = DB::query("
SELECT a.id, a.type_id, a.amount, a.comment, DATE(a.creation_date) as creation_date, b.name FROM sw_fs_expenses a
LEFT JOIN sw_expenses_type_master b ON a.type_id = b.id
WHERE a.creation_date >=%s
AND a.creation_date <%s
ORDER BY creation_date DESC;",
            $startOfTheMonth,
            $startOfTheNextMonth
          );
$monthlyTableHTML = getTableHTML($monthlyTableArr, $resultsMonthly);

$resultsMonthlyTypewise = DB::query("
SELECT b.name as typeName, a.totalExpense from (
SELECT type_id as type_id, SUM(amount) totalExpense from sw_fs_expenses
WHERE creation_date >=%s
AND creation_date <%s
GROUP BY type_id
ORDER BY totalExpense DESC) a
LEFT JOIN sw_expenses_type_master b ON a.type_id=b.id",
            $startOfTheMonth,
            $startOfTheNextMonth
          );
$monthlyTypeWiseTableHTML = getTableHTML($monthlyTypeWiseTableArr, $resultsMonthlyTypewise);
?>
<!DOCTYPE HTML>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Expense Stats | FS</title>
<style>
.error {color: #FF0000;}
</style>
</head>
<body>
<center>
<h1>Expense Stats</h2>
<?php
  echo "<h4>Daily</h4>";
  echo $dailyTableHTML;
  echo "<h4>Monthly</h4>";
  echo $monthlyTableHTML;
  echo "<h4>Monthly TypeWise</h4>";
  echo $monthlyTypeWiseTableHTML;
?>
</center>
</body>
</html>
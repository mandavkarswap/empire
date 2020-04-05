<?php
/**
TODO : 
validation
*/
require_once('config/config.inc.php');

require_once(DOC_ROOT . '/libs/CommonFunctions.php');

$page = htmlspecialchars($_SERVER["PHP_SELF"]);
$today = date('Y-m-d', strtotime('today')) . ' 00:00:00';
$tomorrow = date('Y-m-d', strtotime('tomorrow')) . ' 00:00:00';
$startOfTheMonth = date('Y-m-01') . ' 00:00:00';
$startOfTheNextMonth = date('Y-m-01', strtotime('next month')) . ' 00:00:00';

if (!empty($_POST)) {
  if (!empty($_POST['mt'])) {
    $yearMonth = $_POST['mt'] . '-';
    $today = $yearMonth . '01' . ' 00:00:00';
    $tomorrow = $yearMonth . '02' . ' 00:00:00';
    $startOfTheMonth = $yearMonth . '01' . ' 00:00:00';
    $currMonth = explode('-', $yearMonth)[1];
    $currYear = explode('-', $yearMonth)[0];
    $nextMonth = $currMonth < 12 ? $currMonth + 1 : 1;
    $nextMonth = $nextMonth < 10 ? '0'. $nextMonth : $nextMonth;
    $startOfTheNextMonth = $currYear . '-' . $nextMonth . '-' . '01' . ' 00:00:00';
  }
}


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
            'comment' => array(
                      'viewcol' => 'Comment',
                      'align' => 'right',
                      )
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
$tableId = 'daily';
$dailyTableHTML = getTableHTML($dailyTableArr, $resultsDaily, $tableId);

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
$tableId = 'monthly';
$monthlyTableHTML = getTableHTML($monthlyTableArr, $resultsMonthly, $tableId);

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
$tableId = 'monthlytype';
$monthlyTypeWiseTableHTML = getTableHTML($monthlyTypeWiseTableArr, $resultsMonthlyTypewise, $tableId);
?>
<!DOCTYPE HTML>
<html>
<head>
  <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->

  <?php echo getPageMeta();?>

  <title>Expense Stats | FS</title>

  <?php echo getPageCss();?>

<style>
.error {color: #FF0000;}
</style>
</head>
<body>
<div class="container">
  
  <center>
    <form method="post" action="<?php $page;?>">  
      Month: <input type="month" name="mt" >
      <input type="submit" name="submit" value="Submit">  
    </form>

    <h1>Expense Stats</h1>
    <?php
      if (empty($_POST['mt'])) {
        echo "<h4>Daily</h4>";
        echo $dailyTableHTML;
      }
      echo "<h4>Monthly</h4>";
      echo $monthlyTableHTML;
      echo "<h4>Monthly TypeWise</h4>";
      echo $monthlyTypeWiseTableHTML;
    ?>
  </center>

  <?php echo getPageJS();?>

  <!-- Your custom scripts (optional) -->
  <script type="text/javascript">
    $(document).ready(function(){
      $('#daily').DataTable({
            paging: true
      });
      $('#monthly').DataTable({
            paging: true
      });
      $('#monthlytype').DataTable({
            paging: true
      });
    });
  </script>
</div>
</body>
</html> 
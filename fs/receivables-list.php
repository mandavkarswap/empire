<?php
require_once('config/config.inc.php');

require_once(DOC_ROOT . '/libs/CommonFunctions.php');

DB::$user = DB_USER;
DB::$password = DB_PASSWORD;
DB::$dbName = DB_NAME;

$tableArr = array(
              'name' => array(
                      'viewcol' => 'Borrower',
                      'align' => 'left',
                      ),
              'amount' => array(
                      'viewcol' => 'Amount',
                      'align' => 'right',
                      ),
              'remaining_amount' => array(
                      'viewcol' => 'Remaining',
                      'align' => 'right',
                      ),
              'lending_date' => array(
                      'viewcol' => ' Borrow Date',
                      'align' => 'left'
                      ),
              'return_date' => array(
                      'viewcol' => 'Return Date',
                      'align' => 'left'
                      ),
              'comment' => array(
                      'viewcol' => 'Comment',
                      'align' => 'right',
                      )
          );

$result = DB::query("
SELECT a.id, b.name, a.amount, a.remaining_amount, DATE(a.lending_date) as lending_date, DATE(a.return_date) as return_date, a.comment FROM sw_fs_receivables a
LEFT JOIN sw_fs_borrower_master b ON a.borrower_id = b.id
WHERE a.is_paid=0;"
          );

$tableHTML = getTableHTML($tableArr, $result);
?>
<!DOCTYPE HTML>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Receivables List | FS</title>
<style>
.error {color: #FF0000;}
</style>
</head>
<body>
<center>
<h1>Receivables List</h2>
<?php
  echo "<h4>All Borrowers</h4>";
  echo $tableHTML;
?>
</center>
</body>
</html>
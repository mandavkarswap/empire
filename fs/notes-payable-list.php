<?php
/**
Page to list Notes Payable
*/
require_once('config/config.inc.php');

require_once(DOC_ROOT . '/libs/CommonFunctions.php');

DB::$user = DB_USER;
DB::$password = DB_PASSWORD;
DB::$dbName = DB_NAME;

$pageName = 'Notes Payable List';

$tableArr = array(
              'name' => array(
                      'viewcol' => 'Loaner',
                      'align' => 'left',
                      ),
              'amount' => array(
                      'viewcol' => 'Amount',
                      'align' => 'right',
                      ),
              'amount_payable' => array(
                      'viewcol' => 'Payable',
                      'align' => 'right',
                      ),
              'remaining_amount' => array(
                      'viewcol' => 'Remaining',
                      'align' => 'right',
                      ),
              'lending_date' => array(
                      'viewcol' => ' Loan Date',
                      'align' => 'left'
                      ),
              'return_date' => array(
                      'viewcol' => 'Return Date',
                      'align' => 'left'
                      ),
              'last_payment_date' => array(
                      'viewcol' => 'Recent Payment Date',
                      'align' => 'left'
                      ),
              'comment' => array(
                      'viewcol' => 'Comment',
                      'align' => 'right',
                      )
          );

$result = DB::query("
SELECT a.id, b.name, a.amount, a.amount_payable, a.remaining_amount, DATE(a.lending_date) as lending_date, DATE(a.return_date) as return_date, DATE(a.updation_date) as last_payment_date, a.comment FROM sw_fs_notes_payable a
LEFT JOIN sw_fs_loaner_master b ON a.loaner_id = b.id
WHERE a.is_paid=0;"
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
  echo "<h4>All Loaners</h4>";
  echo $tableHTML;
?>
</center>
</body>
</html>
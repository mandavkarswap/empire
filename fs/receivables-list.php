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
SELECT a.id, b.name, a.amount, a.remaining_amount, DATE(a.lending_date) as lending_date, DATE(a.return_date) as return_date, DATE(a.updation_date) as last_payment_date, a.comment FROM sw_fs_receivables a
LEFT JOIN sw_fs_borrower_master b ON a.borrower_id = b.id
WHERE a.is_paid=0;"
          );

$tableId = 'receivables';
$tableHTML = getTableHTML($tableArr, $result, $tableId);
?>
<!DOCTYPE HTML>
<html>
<head>
  <?php echo getPageMeta();?>
  <title>Receivables List | FS</title>
  <?php echo getPageCss();?>
<style>
.error {color: #FF0000;}
</style>
</head>
<body>
<div class="container">
  <center>
  <h1>Receivables List</h2>
  <?php
    echo "<h4>All Borrowers</h4>";
    echo $tableHTML;
  ?>
  </center>
  <?php echo getPageJS();?>

  <!-- Your custom scripts (optional) -->
  <script type="text/javascript">
    $(document).ready(function(){
      $('#receivables').DataTable({
            paging: true
      });
    });
  </script>
</div>
</body>
</html>
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

// Results
// $result = DB::query("
// SELECT a.id, b.type, a.amount, DATE(a.date) as date, a.comment FROM sw_fs_income_transaction_master a
// LEFT JOIN sw_fs_income_type_master b ON a.income_id = b.id ORDER BY a.date DESC;"
//           );

// UNION ftw for total line :D :D :D
$result = DB::query("
(SELECT a.id, b.type, a.amount, DATE(a.date) as date, a.comment FROM sw_fs_income_transaction_master a
LEFT JOIN sw_fs_income_type_master b ON a.income_id = b.id ORDER BY a.date DESC)
UNION
(SELECT \"-\" AS id, \"Total\" AS type, SUM(amount) AS amount, \"-\" AS date, \"-\" AS comment from sw_fs_income_transaction_master);
");

$tableId = 'income';
$tableHTML = getTableHTML($tableArr, $result, $tableId);
?>
<!DOCTYPE HTML>
<html>
<head>
  <?php echo getPageMeta();?>
  <title><?php echo $pageName; ?> | FS</title>
  <?php echo getPageCss();?>
<style>
.error {color: #FF0000;}
</style>
</head>
<body>
<div class="container">
  <center>
  <h1><?php echo $pageName; ?></h2>
  <?php
    echo "<h4>All Income</h4>";
    echo $tableHTML;
  ?>
  </center>
  <?php echo getPageJS();?>

  <!-- Your custom scripts (optional) -->
  <script type="text/javascript">
    $(document).ready(function(){
      $('#income').DataTable({
            paging: true
      });
    });
  </script>
</div>
</body>
</html>

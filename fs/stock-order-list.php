<?php
/**
Page to list Stock Orders

TODO : 
Add date filter
Add Month filter
*/
require_once('config/config.inc.php');

require_once(DOC_ROOT . '/libs/CommonFunctions.php');

DB::$user = DB_USER;
DB::$password = DB_PASSWORD;
DB::$dbName = DB_NAME;

$pageName = 'Stock Orders';

$tableArr = array(
              'name' => array(
                      'viewcol' => 'Name',
                      'align' => 'left',
                      ),
              'transaction_type' => array(
                      'viewcol' => 'Order',
                      'align' => 'left',
                      ),
              'quantity' => array(
                      'viewcol' => 'Quantity',
                      'align' => 'right',
                      ),
              'single_share_price' => array(
                      'viewcol' => 'Single Share Price',
                      'align' => 'right',
                      ),
              'effective_single_share_price' => array(
                      'viewcol' => 'ePrice Single Share',
                      'align' => 'right',
                      ),
              'transaction_cost' => array(
                      'viewcol' => 'Amount',
                      'align' => 'right',
                      ),
              'term_master' => array(
                      'viewcol' => 'Term',
                      'align' => 'right',
                      ),
              'date' => array(
                      'viewcol' => 'Date',
                      'align' => 'left',
                      ),
              'comment' => array(
                      'viewcol' => 'comment',
                      'align' => 'left',
                      ),
          );

$result = DB::query("
SELECT b.name, a.quantity, DATE(a.date) as date, a.term_master, a.transaction_type, a.quantity, a.single_share_price, a.effective_single_share_price, a.transaction_cost, a.comment FROM sw_fs_stock_transaction_master a LEFT JOIN sw_fs_stock_master b ON a.stock_id = b.id ORDER BY a.date DESC ;
");

$tableId = 'orderlist';
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
  <h1><?php echo $pageName; ?></h1>
  <?php
    echo "<h4>Stock Orders</h4>";
    echo $tableHTML;
  ?>
  </center>
  <?php echo getPageJS();?>

  <!-- Your custom scripts (optional) -->
  <script type="text/javascript">
    $(document).ready(function(){
      $('#orderlist').DataTable({
            paging: true
      });
    });
  </script>
</div>
</body>
</html>
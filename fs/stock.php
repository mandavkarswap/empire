  <?php
  /**
  Page to insert Stock
    Lists Stocks
    Inserts Stock Transaction

  TODO :
  SP to insert and select 
  */
  require_once('config/config.inc.php');
  require_once(DOC_ROOT . '/libs/CommonFunctions.php');

  // define variables and set to empty values
  $page = htmlspecialchars($_SERVER["PHP_SELF"]);
  $pageName = 'Stock Transactions';
  $stockId = !empty($_POST["stid"]) ? $_POST["stid"] : '';
  $transactionType = !empty($_POST["tt"]) ? $_POST["tt"] : '';
  $singleSharePrice = !empty($_POST["sishpr"]) ? $_POST["sishpr"] : 0;
  $quantity = !empty($_POST["qt"]) ? $_POST["qt"] : 0;
  $transactionPrice = !empty($_POST["tamt"]) ? $_POST["tamt"] : 0;
  $termId = !empty($_POST["tid"]) ? $_POST["tid"] : 0;
  $date = !empty($_POST["tdt"]) ? $_POST["tdt"] . ' 00:00:00' : date('Y-m-d H:i:s');
  $comment = !empty($_POST["ct"]) ? $_POST["ct"] : '';

  $showAlert = false;

  DB::$user = DB_USER;
  DB::$password = DB_PASSWORD;
  DB::$dbName = DB_NAME;
  // DB::debugMode(true);

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
      if (!empty($stockId)
          && !empty($transactionType)
          && !empty($singleSharePrice)
          && !empty($quantity)
          && !empty($transactionPrice)
          ) {

        // echo "<pre>";
        // print_r($_POST);
        // die();
      
      DB::insert('sw_fs_stock_transaction_master', array(
        'stock_id' => $stockId,
        'date' => $date,
        'term_master' => $termId,
        'transaction_type' => $transactionType,
        'quantity' => $quantity,
        'single_share_price' => number_format(floatval($singleSharePrice), 3, '.', ''),
        'effective_single_share_price' => number_format(floatval($transactionPrice/$quantity), 3, '.', ''),
        'transaction_cost' => number_format($transactionPrice, 3, '.', ''),
        'comment' => $comment,
      ));

      if (DB::insertId() > 0) {
        $showAlert = true;
      }

      // TODO : validate
      // TODO : class structure
    } else {
      echo "err1";
      die();
    }
  }

  // Populate Stock Types
  $result = DB::query("
  SELECT id, name FROM sw_fs_stock_master where is_active=1;
  ");

  if (!empty($result)) {
    $optList = array();
    foreach ($result as $stockInfo) {
      $optList[$stockInfo['id']] = ucwords($stockInfo['name']);
    }
  }

  $htmlOptionStocks = '';
  if (!empty($optList)) {
    $htmlOptionStocks = getSelectOptions($optList);    
  }

  // Populate Term Types
  $resultTerm = DB::query("
  SELECT id, term FROM sw_fs_stock_term_master;
  ");

  if (!empty($resultTerm)) {
    $optListTerm = array();
    foreach ($resultTerm as $termInfo) {
      $optListTerm[$termInfo['id']] = ucwords($termInfo['term']);
    }
  }

  $htmlOption = '';
  if (!empty($optListTerm)) {
    $htmlOption = getSelectOptions($optListTerm);    
  }

  ?>
  <!DOCTYPE HTML>
  <html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageName;?> | FS</title>
  <style>
  .error {color: #FF0000;}
  </style>
  </head>
  <body>

  <h2><?php echo $pageName;?></h2>
  <p><span class="error">* required field</span></p>
  <form method="post" action="<?php echo $page;?>">  
    Stock *:<select name="stid">
      <option value="">Select Stock</option>
      <?php echo $htmlOptionStocks;?>
    </select>
    <br>
    <br>
    Transaction Type *:<select name="tt">
      <option value="">Select Type</option>
      <option value="b">Buy</option>
      <option value="s">Sell</option>
    </select>
    <br>
    <br>
    Quantity *: <input type="text" name="qt">
    <br>
    <br>
    Single Share Price *: <input type="text" name="sishpr">
    <br>
    <br>
    Transaction Amount *: <input type="text" name="tamt">
    <br>
    <br>
    Term :<select name="tid">
      <option value="">Select term</option>
      <?php echo $optListTerm;?>
    </select>
    <br>
    <br>
    Date: <input type="date" name="tdt">
    <br>
    <br>
    Comment: <input type="text" name="ct">
    <br>
    <br>
    <input type="submit" name="submit" value="Submit">  
    <input type="reset">
  </form>

  <?php
    if ($showAlert) {
  ?>
    <script type="text/javascript">
      window.alert('Stock Recorded !!');

      // Confirm form resubmit issue
      // window.location.href = "<?php echo $page;?>";
    </script>
  <?php
    }
  ?>

  </body>
  </html>
<?php
require_once('config/config.inc.php');


// define variables and set to empty values
$page = htmlspecialchars($_SERVER["PHP_SELF"]);
$receivableId = !empty($_POST["reid"]) ? $_POST["reid"] : '';
$returnedAmount = !empty($_POST["ramt"]) ? $_POST["ramt"] : 0;
$date = !empty($_POST["rdt"]) ? $_POST["rdt"] . ' 00:00:00' : date('Y-m-d H:i:s');
$comment = !empty($_POST["ct"]) ? $_POST["ct"] : '';
 "";
$showAlert = false;

DB::$user = DB_USER;
DB::$password = DB_PASSWORD;
DB::$dbName = DB_NAME;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($borrowerId)
        && !empty($borrowedAmount)) {
    

    DB::insert('sw_fs_receivables', array(
      'borrower_id' => $borrowerId,
      'amount' => $borrowedAmount,
      'lending_date' => $borrowDate,
      'return_date' => $returnDate,
      'comment' => $comment,
    ));

    if (DB::insertId() > 0) {
      $showAlert = true;
    }

    // TODO : validate
    // TODO : class structure
    // TODO : select for expense type master
  } else {
    echo "err1";
    die();
  }
} else {

  $result = DB::query("
SELECT a.id, b.name, a.amount FROM sw_fs_receivables a
LEFT JOIN sw_fs_borrower_master b ON a.borrower_id = b.id
WHERE a.is_paid=0;"
  );


  if (!empty($result)) {
    $optList = array();

    foreach ($result as $receivableInfo) {
      $optList[$receivableInfo['id']] = $receivableInfo['name'] . ' (' . $receivableInfo['amount'] . ')';
    }
  }
  // echo '<pre>';
  // print_r($optList);
  // die();

  $htmlOption = '';
  if (!empty($optList)) {
    $htmlOption = getSelectOptions($optList);    
  }
}
?>
<!DOCTYPE HTML>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Received Receivables | FS</title>
<style>
.error {color: #FF0000;}
</style>
</head>
<body>

<h2>Received Receivables</h2>
<p><span class="error">* required field</span></p>
<form method="post" action="<?php $page;?>">  
  Receivable *:<select name="reid">
    <option value="">Select Borrower</option>
    <?php echo $htmlOption;?>
  </select>
  <br>
  <br>
  Received Amount *: <input type="text" name="ramt" value="<?php echo $email;?>">
  <br>
  <br>
  Return Date: <input type="date" name="rdt">
  <br>
  <br>
  Comment: <input type="text" name="ct" value="<?php echo $email;?>">
  <br>
  <br>
  <input type="submit" name="submit" value="Submit">  
  <input type="reset">
</form>

<?php
  if ($showAlert) {
?>
  <script type="text/javascript">
    window.alert('Recorded !!');

    // Confirm form resubmit issue
    window.location.href = "<?php echo $page;?>";
  </script>
<?php
  }
?>

</body>
</html>
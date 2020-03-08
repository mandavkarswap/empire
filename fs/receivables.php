<?php
require_once('config/config.inc.php');


// define variables and set to empty values
$page = htmlspecialchars($_SERVER["PHP_SELF"]);
$borrowerId = !empty($_POST["bn"]) ? $_POST["bn"] : '';
$borrowedAmount = !empty($_POST["at"]) ? $_POST["at"] : 0;
$borrowDate = !empty($_POST["bdt"]) ? $_POST["bdt"] . ' 00:00:00' : date('Y-m-d H:i:s');
$returnDate = !empty($_POST["rdt"]) ? $_POST["rdt"] . ' 00:00:00' : '0000-00-00 00:00:00' /*Means no gurantee of return date*/;
$comment = !empty($_POST["ct"]) ? $_POST["ct"] : '';
 "";
$showAlert = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($borrowerId)
        && !empty($borrowedAmount)) {
    
    DB::$user = DB_USER;
    DB::$password = DB_PASSWORD;
    DB::$dbName = DB_NAME;

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
}
?>
<!DOCTYPE HTML>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Receivables | FS</title>
<style>
.error {color: #FF0000;}
</style>
</head>
<body>

<h2>Receivables</h2>
<p><span class="error">* required field</span></p>
<form method="post" action="<?php echo $page;?>">  
  Borrower *:<select name="bn">
    <option value="">Select Borrower</option>
    <option value="1">Omkar Phavare</option>
    <option value="2">Sanjana Ghanekar</option>
    <option value="3">Swapnil Desai</option>
    <option value="4">Shailesh Khochare</option>
    <option value="5">Devendra Shelar</option>
    <option value="6">Rohit Sawant</option>
    <option value="7">Mrunal Mandavkar</option>
    <option value="8">Sugandha Mandavkar</option>
    <option value="9">Sandesh Mandavkar</option>
  </select>
  <br>
  <br>
  Amount *: <input type="text" name="at" value="<?php echo $email;?>">
  <br>
  <br>
  Comment: <input type="text" name="ct" value="<?php echo $email;?>">
  <br>
  <br>
  Borrow Date: <input type="date" name="bdt">
  <br>
  <br>
  Return Date: <input type="date" name="rdt">
  <br>
  <br>
  <input type="submit" name="submit" value="Submit">  
  <input type="reset">
</form>

<?php
  if ($showAlert) {
?>
  <script type="text/javascript">
    window.alert('Borrow Recorded !!');

    // Confirm form resubmit issue
    window.location.href = "<?php echo $page;?>";
  </script>
<?php
  }
?>

</body>
</html>
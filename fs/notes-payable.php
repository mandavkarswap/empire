<?php
/**
Interface to insert Notes Payable
  Lists Loaner
  Inserts Note Payable 
  TODO :
  Table needs new column, amount remaining
*/
require_once('config/config.inc.php');
require_once(DOC_ROOT . '/libs/CommonFunctions.php');

// define variables and set to empty values
$page = htmlspecialchars($_SERVER["PHP_SELF"]);
$pageName = 'Notes Payable';
$loanerId = !empty($_POST["ln"]) ? $_POST["ln"] : '';
$loanedAmount = !empty($_POST["at"]) ? $_POST["at"] : 0;
$amountPayable = !empty($_POST["atp"]) ? $_POST["atp"] : 0;
$loanDate = !empty($_POST["ldt"]) ? $_POST["ldt"] . ' 00:00:00' : date('Y-m-d H:i:s');
$returnDate = !empty($_POST["rdt"]) ? $_POST["rdt"] . ' 00:00:00' : '0000-00-00 00:00:00' /*Means no gurantee of return date*/;
$comment = !empty($_POST["ct"]) ? $_POST["ct"] : '';

$showAlert = false;

DB::$user = DB_USER;
DB::$password = DB_PASSWORD;
DB::$dbName = DB_NAME;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($loanerId)
        && !empty($loanedAmount)) {
    

    DB::insert('sw_fs_notes_payable', array(
      'loaner_id' => $loanerId,
      'amount' => $loanedAmount,
      'amount_payable' => $amountPayable,
      'lending_date' => $loanDate,
      'return_date' => $returnDate,
      'comment' => $comment,
      'creation_date' => $loanDate,
      'updation_date' => '0000-00-00 00:00:00'
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

// Populate Loaners
$result = DB::query("
SELECT id, name FROM sw_fs_loaner_master where is_active=1;
");

if (!empty($result)) {
  $optList = array();
  foreach ($result as $loanerInfo) {
    $optList[$loanerInfo['id']] = ucwords($loanerInfo['name']);
  }
}

$htmlOption = '';
if (!empty($optList)) {
  $htmlOption = getSelectOptions($optList);    
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
  Loaner *:<select name="ln">
    <option value="">Select Loaner</option>
    <?php echo $htmlOption;?>
  </select>
  <br>
  <br>
  Amount *: <input type="text" name="at">
  <br>
  <br>
  Amount Payable *: <input type="text" name="atp">
  <br>
  <br>
  Loan Date: <input type="date" name="ldt">
  <br>
  <br>
  Return Date: <input type="date" name="rdt">
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
    window.alert('Borrow Recorded !!');

    // Confirm form resubmit issue
    window.location.href = "<?php echo $page;?>";
  </script>
<?php
  }
?>

</body>
</html>
<?php
require_once('config/config.inc.php');
?>
<!DOCTYPE HTML>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Expense | FS</title>
<style>
.error {color: #FF0000;}
</style>
</head>
<body>
<?php
// define variables and set to empty values
$page = htmlspecialchars($_SERVER["PHP_SELF"]);
$expenseTypeId = !empty($_POST["et"]) ? $_POST["et"] : 0;
$expenseAmount = !empty($_POST["at"]) ? $_POST["at"] : 0;
$comment = !empty($_POST["ct"]) ? $_POST["ct"] : '';
 "";
$showAlert = false;

if ($_SERVER["REQUEST_METHOD"] == "POST"
    && !empty($expenseTypeId)
    && !empty($expenseAmount)) {
  
  DB::$user = DB_USER;
  DB::$password = DB_PASSWORD;
  DB::$dbName = DB_NAME;

  DB::insert('sw_fs_expenses', array(
    'type_id' => $expenseTypeId,
    'amount' => $expenseAmount,
    'comment' => $comment,
  ));

  if (DB::insertId() > 0) {
    $showAlert = true;
  }

  // TODO : validate
  // TODO : class structure
  // TODO : select for expense type master
}
?>

<h2>Expense</h2>
<p><span class="error">* required field</span></p>
<form method="post" action="<?php $page;?>">  
  Expense Type *:
  <select name="et">
    <option value="">Select Type</option>
    <option value="1">other</option>
    <option value="2">taxes_income</option>
    <option value="3">taxes_real_estate</option>
    <option value="4">home_mortage</option>
    <option value="5">home_rent</option>
    <option value="6">utilities</option>
    <option value="7">maintenance</option>
    <option value="8">insurance</option>
    <option value="9">clothing</option>
    <option value="10">food</option>
    <option value="11">credit_card</option>
    <option value="12">gym</option>
    <option value="13">cycle</option>
    <option value="14">nutrition</option>
    <option value="15">gifts</option>
    <option value="16">books</option>
    <option value="17">subscriptions</option>
    <option value="18">phone_bill</option>
    <option value="19">smoke</option>
    <option value="20">alcohol</option>
    <option value="21">movies</option>
    <option value="22">concerts</option>
    <option value="23">vacation</option>
    <option value="24">medicine</option>
    <option value="25">doctor</option>
    <option value="26">equipments</option>
    <option value="27">commute</option>    
  </select>
  <br>
  <br>
  Amount *: <input type="text" name="at" value="<?php echo $email;?>">
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
    window.alert('Expense Recorded !!');

    // Confirm form resubmit issue
    window.location.href = "<?php echo $page;?>";
  </script>
<?php
  }
?>

</body>
</html>
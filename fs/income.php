<?php
/**
Interface to insert Income
  Lists Income Types
  Inserts Income

TODO :
SP to insert and select 
*/
require_once('config/config.inc.php');
require_once(DOC_ROOT . '/libs/CommonFunctions.php');

// define variables and set to empty values
$page = htmlspecialchars($_SERVER["PHP_SELF"]);
$pageName = 'Income';
$incomeId = !empty($_POST["incs"]) ? $_POST["incs"] : '';
$incomeAmount = !empty($_POST["at"]) ? $_POST["at"] : 0;
$incomeDate = !empty($_POST["idt"]) ? $_POST["idt"] . ' 00:00:00' : date('Y-m-d H:i:s');
$comment = !empty($_POST["ct"]) ? $_POST["ct"] : '';

$showAlert = false;

DB::$user = DB_USER;
DB::$password = DB_PASSWORD;
DB::$dbName = DB_NAME;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($incomeId)
        && !empty($incomeAmount)) {
    

    DB::insert('sw_fs_income_transaction_master', array(
      'income_id' => $incomeId,
      'date' => $incomeDate,
      'amount' => $incomeAmount,
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

// Populate Income Types
$result = DB::query("
SELECT id, type FROM sw_fs_income_type_master where is_active=1;
");

if (!empty($result)) {
  $optList = array();
  foreach ($result as $incomeInfo) {
    $optList[$incomeInfo['id']] = ucwords($incomeInfo['type']);
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
  Source *:<select name="incs">
    <option value="">Select Source</option>
    <?php echo $htmlOption;?>
  </select>
  <br>
  <br>
  Amount *: <input type="text" name="at">
  <br>
  <br>
  Income Date: <input type="date" name="idt">
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
    window.alert('Income Recorded !!');

    // Confirm form resubmit issue
    window.location.href = "<?php echo $page;?>";
  </script>
<?php
  }
?>

</body>
</html>
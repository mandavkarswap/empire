<?php
/**
Loaner Insert
Expense name must be small case
Expense name must conatin alphabets and/or spaces
*/
require_once('config/config.inc.php');

require_once(DOC_ROOT . '/libs/CommonFunctions.php');

// define variables and set to empty values
$page = htmlspecialchars($_SERVER["PHP_SELF"]);
$pageName = 'Loaner Insert';
$loanerName = !empty($_POST["lonm"]) ? $_POST["lonm"] : '';

$showAlert = false;

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($loanerName)) {
  DB::$user = DB_USER;
  DB::$password = DB_PASSWORD;
  DB::$dbName = DB_NAME;

  $result = DB::query("CALL sp_sw_fs_insert_new_loaner(%s)",
    strtolower($loanerName)
  );
  
  $showAlert = true;
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
<form method="post" action="<?php $page;?>">
  Loaner Name *: <input type="text" name="lonm">
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
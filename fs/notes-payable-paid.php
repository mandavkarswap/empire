<?php
require_once('config/config.inc.php');

require_once(DOC_ROOT . '/libs/CommonFunctions.php');

// define variables and set to empty values
$page = htmlspecialchars($_SERVER["PHP_SELF"]);
$pageName = 'Notes Payable Paid';
$notesPayableId = !empty($_POST["npid"]) ? $_POST["npid"] : '';
$returnedAmount = !empty($_POST["ramt"]) ? $_POST["ramt"] : 0;
$date = !empty($_POST["rdt"]) ? $_POST["rdt"] . ' 00:00:00' : date('Y-m-d H:i:s');
$comment = !empty($_POST["ct"]) ? $_POST["ct"] : '';
 "";
$showAlert = false;

DB::$user = DB_USER;
DB::$password = DB_PASSWORD;
DB::$dbName = DB_NAME;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($notesPayableId)
        && !empty($returnedAmount)) {

    $result = DB::query("CALL sp_sw_fs_insert_notes_payable_paid(%s, %s, %s, %s)",
      $notesPayableId,
      $returnedAmount,
      $date,
      $comment
    );
  
    // echo "<pre>";
    // print_r($result);
    // die();
  
    // TODO : Need validation
    // if (DB::insertId() > 0) {
    
    $showAlert = true;
    // }

    // TODO : validate
    // TODO : class structure
    // TODO : select for expense type master
  } else {
    echo "err1";
    die();
  }
}

// Populate Notes Payable
$result = DB::query("
SELECT a.id, b.name, a.amount, a.amount_payable FROM sw_fs_notes_payable a
LEFT JOIN sw_fs_loaner_master b ON a.loaner_id = b.id
WHERE a.is_paid=0;"
);

if (!empty($result)) {
  $optList = array();

  foreach ($result as $npInfo) {
    $optList[$npInfo['id']] = ucwords($npInfo['name']) . ' (' . $npInfo['amount'] . '-' . $npInfo['amount_payable'] .')';
  }
}
// echo '<pre>';
// print_r($optList);
// die();

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
  Notes Payable *:<select name="npid">
    <option value="">Select Notes</option>
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

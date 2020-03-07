<?php
require_once('config/config.inc.php');
require_once(DOC_ROOT . '/libs/CommonFunctions.php');
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
$date = !empty($_POST["dt"]) ? $_POST["dt"] . ' 00:00:00' : '';
$comment = !empty($_POST["ct"]) ? $_POST["ct"] : '';
 "";
$showAlert = false;

DB::$user = DB_USER;
DB::$password = DB_PASSWORD;
DB::$dbName = DB_NAME;

if ($_SERVER["REQUEST_METHOD"] == "POST"
    && !empty($expenseTypeId)
    && !empty($expenseAmount)) {
  

  if (!empty($date)) {
    DB::insert('sw_fs_expenses', array(
      'type_id' => $expenseTypeId,
      'amount' => $expenseAmount,
      'comment' => $comment,
      'creation_date' => $date,
    ));
  } else {
    DB::insert('sw_fs_expenses', array(
      'type_id' => $expenseTypeId,
      'amount' => $expenseAmount,
      'comment' => $comment,
    ));
  }

  if (DB::insertId() > 0) {
    $showAlert = true;
  }

  // TODO : validate
  // TODO : class structure
  // TODO : select for expense type master
}

// Populate expeses
$result = DB::query("
SELECT id, name FROM sw_expenses_type_master;
");

if (!empty($result)) {

  $priorityArr = array(
                '10',//food
                '27',//commute
                '16',//books
                '9',//clothing
                '24',//medicine
                '5',//home_rent
                '18',//phone_bill
                '17',//subscriptions
                '21',//movies
                '13',//cycle
                '6',//utilities
                '4',//home_mortage
                '7',//maintenance
                '8',//insurance
                '2',//taxes_income
                '3',//taxes_real_estate
                '11',//credit_card
                '12',//gym
                '14',//nutrition
                '15',//gifts
                '22',//concerts
                '23',//vacation
                '25',//doctor
                '26',//equipments
                // '20',//alcohol // These were commented just to show that priority sorting works
                // '19',//smoke
                // '1',//other
            );
  $optList = array();
  foreach ($result as $expenseTypeInfo) {
    $optList[$expenseTypeInfo['id']] = $expenseTypeInfo['name'];
  }

  // $optList should always contain more records than $priorityArr
  $diff = array_diff(array_keys($optList), $priorityArr);
  // echo "<pre>";
  // print_r($diff);
  // die();
  // This way priority expense ids always get lowest index
  $sortedList = array_merge($priorityArr, $diff);

  $sortedOptList = array();
  foreach ($sortedList as $expenseId) {
    $sortedOptList[$expenseId] = $optList[$expenseId];
  }
}

$htmlOption = '';
if (!empty($sortedOptList)) {
  $htmlOption = getSelectOptions($sortedOptList);    
}
?>

<h2>Expense</h2>
<p><span class="error">* required field</span></p>
<form method="post" action="<?php $page;?>">  
  Expense Type *:
  <select name="et">
    <option value="">Select Type</option>
    <?php echo $htmlOption;?> 
  </select>
  <br>
  <br>
  Amount *: <input type="text" name="at" value="<?php echo $email;?>">
  <br>
  <br>
  Comment: <input type="text" name="ct" value="<?php echo $email;?>">
  <br>
  <br>
  Date: <input type="date" name="dt" >
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